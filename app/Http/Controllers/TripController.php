<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripCollection;
use App\Models\TripExpense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TripController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter');
        $query = Trip::query()->latest('trip_date')->latest('id');
        if ($filter === 'open') {
            $query->where('status', '!=', 'CLOSED');
        }
        $trips = $query->get()->map(fn (Trip $trip) => $this->present($trip))->all();
        $pageTitle = $filter === 'open' ? 'Open Trips' : 'All Trips';
        $deliverymen = $this->deliverymenWithVehicles();

        return view('trips.index', compact('trips', 'filter', 'pageTitle', 'deliverymen'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateTrip($request);
        $driver = collect($this->deliverymenWithVehicles())->firstWhere('name', $data['deliveryman_name']);
        $sequence = Trip::whereDate('trip_date', $data['trip_date'])->count() + 1;
        Trip::create([...$data,
            'trip_number' => sprintf('TR-%s-%03d', $data['trip_date'], $sequence),
            'deliveryman_id' => $driver['id'] ?? null,
            'vehicle' => $data['vehicle'] ?: ($driver['vehicle'] ?? ''),
            'status' => 'DRAFT',
        ]);

        return to_route('trips.index', ['filter' => 'open'])->with('success', 'Trip created and added to Open Trips.');
    }

    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $this->ensureEditable($trip);
        $data = $this->validateTrip($request, true);
        $current = array_search($trip->status, Trip::STATUSES, true);
        $next = array_search($data['status'], Trip::STATUSES, true);
        abort_if($next === false || $next < $current, 422, 'A trip cannot move backwards in its lifecycle.');
        $trip->update($data);

        return back()->with('success', 'Trip updated.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        abort_unless($trip->status === 'DRAFT' && ! $trip->collections()->exists() && ! $trip->expenses()->exists(), 422, 'Only an empty draft trip can be deleted.');
        $trip->delete();

        return to_route('trips.index')->with('success', 'Draft trip deleted.');
    }

    public function show(Trip $trip): View
    {
        $trip->load(['collections' => fn ($q) => $q->latest('collected_at'), 'expenses' => fn ($q) => $q->latest('expense_date'), 'settlement']);
        $presentedTrip = $this->present($trip);
        $invoices = [];
        $returns = [];
        $collections = $trip->collections->map(fn ($item) => [
            'id' => $item->id, 'customer' => $item->customer, 'invoice_number' => $item->invoice_number,
            'amount' => (float) $item->amount, 'method' => $item->method,
            'collected_at' => $item->collected_at->format('Y-m-d H:i'),
        ])->all();
        $expenses = $trip->expenses->map(fn ($item) => [
            'id' => $item->id, 'category' => $item->category, 'amount' => (float) $item->amount,
            'description' => $item->description, 'expense_date' => $item->expense_date->format('Y-m-d'),
        ])->all();
        $collected = (float) $trip->collections->sum('amount');
        $expenseTotal = (float) $trip->expenses->sum('amount');
        $settlement = [
            'expected_cash' => (float) $trip->expected_cash, 'collected_amount' => $collected,
            'expense_amount' => $expenseTotal,
            'shortage_amount' => (float) $trip->expected_cash - $collected - $expenseTotal,
            'shortage_classification' => $trip->settlement?->shortage_classification ?? 'PENDING',
        ];
        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Trips', 'route' => route('trips.index')],
            ['label' => $trip->trip_number, 'route' => null],
        ];
        $trip = $presentedTrip;

        return view('trips.show', compact('trip', 'invoices', 'collections', 'expenses', 'returns', 'settlement', 'breadcrumbs'));
    }

    public function storeCollection(Request $request, Trip $trip): RedirectResponse
    {
        $this->ensureEditable($trip);
        $data = $request->validate([
            'customer' => ['required', 'string', 'max:255'], 'invoice_number' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'gt:0'], 'method' => ['required', Rule::in(['Cash', 'Cheque', 'Transfer'])],
            'cheque_number' => ['nullable', 'required_if:method,Cheque', 'string', 'max:100'],
            'bank_name' => ['nullable', 'required_if:method,Cheque', 'string', 'max:150'],
            'instrument_date' => ['nullable', 'required_if:method,Cheque', 'date'],
            'bank_reference' => ['nullable', 'required_if:method,Transfer', 'string', 'max:150'],
            'collected_at' => ['required', 'date'], 'notes' => ['nullable', 'string', 'max:1000'],
            'expense_category' => ['nullable', 'required_with:expense_amount', Rule::in(['Fuel', 'Toll', 'Parking', 'Loading', 'Driver Allowance', 'Vehicle Repair', 'Other'])],
            'expense_amount' => ['nullable', 'numeric', 'gt:0'],
            'expense_date' => ['nullable', 'required_with:expense_amount', 'date'],
            'expense_description' => ['nullable', 'string', 'max:1000'],
        ]);
        DB::transaction(function () use ($trip, $data) {
            $collectionData = Arr::except($data, ['expense_category', 'expense_amount', 'expense_date', 'expense_description']);
            $trip->collections()->create([...$collectionData, 'collection_ref' => 'COL-'.str()->upper(str()->random(10))]);

            if (! empty($data['expense_amount'])) {
                $trip->expenses()->create([
                    'expense_ref' => 'EXP-'.str()->upper(str()->random(10)),
                    'category' => $data['expense_category'],
                    'amount' => $data['expense_amount'],
                    'expense_date' => $data['expense_date'],
                    'description' => $data['expense_description'] ?? null,
                ]);
            }
            if (in_array($trip->status, ['DRAFT', 'READY', 'DISPATCHED', 'COMPLETED'], true)) {
                $trip->update(['status' => 'SETTLEMENT PENDING']);
            }
        });

        return to_route('trips.show', $trip)->with('success', 'Collection recorded. The trip remains open until you use Close Trip.');
    }

    public function updateDeliveryResult(Request $request, Trip $trip): RedirectResponse
    {
        $this->ensureEditable($trip);
        $data = $request->validate([
            'delivery_result' => ['required', Rule::in(['DELIVERED', 'PARTIAL', 'DELAYED', 'NOT DELIVERED', 'RESERVICE', 'OTHER'])],
            'follow_up_date' => ['nullable', 'required_if:delivery_result,DELAYED,RESERVICE', 'date'],
            'delivery_notes' => ['nullable', 'required_if:delivery_result,OTHER,NOT DELIVERED,PARTIAL', 'string', 'max:1000'],
        ]);
        $trip->update([
            ...$data,
            'status' => in_array($trip->status, ['DRAFT', 'READY', 'DISPATCHED'], true) ? 'COMPLETED' : $trip->status,
        ]);

        return to_route('trips.show', $trip)->with('success', 'Delivery result saved. The trip is still open for collections and expenses.');
    }

    public function updateCollection(Request $request, Trip $trip, TripCollection $collection): RedirectResponse
    {
        $this->ensureOwnedAndEditable($trip, $collection->trip_id);
        $collection->update($request->validate([
            'customer' => ['required', 'string', 'max:255'], 'invoice_number' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'gt:0'], 'method' => ['required', Rule::in(['Cash', 'Cheque', 'Transfer'])],
            'collected_at' => ['required', 'date'], 'notes' => ['nullable', 'string', 'max:1000'],
        ]));

        return back()->with('success', 'Collection updated.');
    }

    public function storeExpense(Request $request, Trip $trip): RedirectResponse
    {
        $this->ensureEditable($trip);
        $data = $request->validate([
            'category' => ['required', Rule::in(['Fuel', 'Toll', 'Parking', 'Loading', 'Driver Allowance', 'Vehicle Repair', 'Other'])],
            'amount' => ['required', 'numeric', 'gt:0'], 'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        $trip->expenses()->create([...$data, 'expense_ref' => 'EXP-'.str()->upper(str()->random(10))]);

        return to_route('trips.show', $trip)->with('success', 'Trip expense recorded.');
    }

    public function updateExpense(Request $request, Trip $trip, TripExpense $expense): RedirectResponse
    {
        $this->ensureOwnedAndEditable($trip, $expense->trip_id);
        $expense->update($request->validate([
            'category' => ['required', Rule::in(['Fuel', 'Toll', 'Parking', 'Loading', 'Driver Allowance', 'Vehicle Repair', 'Other'])],
            'amount' => ['required', 'numeric', 'gt:0'], 'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]));

        return back()->with('success', 'Trip expense updated.');
    }

    public function close(Request $request, Trip $trip): RedirectResponse
    {
        $this->ensureEditable($trip);
        abort_unless($trip->collections()->exists(), 422, 'Record at least one collection before closing the trip.');
        $collected = (float) $trip->collections()->sum('amount');
        $expenses = (float) $trip->expenses()->sum('amount');
        $difference = round((float) $trip->expected_cash - $collected - $expenses, 2);
        $data = $request->validate([
            'shortage_classification' => [Rule::requiredIf(abs($difference) > 0.009), 'nullable', Rule::in(['MARKET SHORT', 'DELIVERYMAN SHORT', 'APPROVED WRITE-OFF', 'PENDING INVESTIGATION'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        DB::transaction(function () use ($trip, $collected, $expenses, $difference, $data) {
            $trip->settlement()->updateOrCreate([], [
                'expected_cash' => $trip->expected_cash, 'collected_amount' => $collected,
                'expense_amount' => $expenses, 'difference_amount' => $difference,
                'shortage_classification' => abs($difference) > 0.009 ? $data['shortage_classification'] : null,
                'notes' => $data['notes'] ?? null, 'settled_at' => now(),
            ]);
            $trip->update(['status' => 'CLOSED', 'closed_at' => now()]);
        });

        return to_route('trips.show', $trip)->with('success', 'Trip settled, closed, and locked from further editing.');
    }

    private function validateTrip(Request $request, bool $editing = false): array
    {
        return $request->validate([
            'trip_date' => ['required', 'date'], 'deliveryman_name' => ['required', 'string', 'max:255'],
            'vehicle' => ['required', 'string', 'max:255'], 'market_area' => ['required', 'string', 'max:255'],
            'source_dlf' => ['nullable', 'string', 'max:255'], 'load_value' => ['required', 'numeric', 'min:0'],
            'expected_cash' => ['required', 'numeric', 'min:0'],
            'status' => [$editing ? 'required' : 'nullable', Rule::in(Trip::STATUSES)],
        ]);
    }

    private function ensureEditable(Trip $trip): void
    {
        abort_if($trip->isClosed(), 422, 'Closed trips are locked and cannot be edited.');
    }

    private function ensureOwnedAndEditable(Trip $trip, int $ownerId): void
    {
        abort_unless($trip->id === $ownerId, 404);
        $this->ensureEditable($trip);
    }

    private function present(Trip $trip): array
    {
        return ['id' => $trip->id, 'trip_id' => $trip->trip_number, 'date' => $trip->trip_date->format('Y-m-d'),
            'deliveryman' => ['id' => $trip->deliveryman_id ?? 1, 'name' => $trip->deliveryman_name], 'vehicle' => $trip->vehicle,
            'market_area' => $trip->market_area, 'source_dlf' => $trip->source_dlf, 'status' => $trip->status,
            'delivery_result' => $trip->delivery_result, 'follow_up_date' => $trip->follow_up_date?->format('Y-m-d'),
            'delivery_notes' => $trip->delivery_notes, 'load_value' => (float) $trip->load_value, 'expected_cash' => (float) $trip->expected_cash];
    }

    private function deliverymenWithVehicles(): array
    {
        return [
            ['id' => 1, 'name' => 'Ahmed Khan', 'employee_id' => 'EMP-001', 'vehicle' => 'Toyota Hilux - ABC-123', 'area' => 'Gulshan-e-Iqbal'],
            ['id' => 2, 'name' => 'Bilal Raza', 'employee_id' => 'EMP-002', 'vehicle' => 'Suzuki Ravi - DEF-456', 'area' => 'North Nazimabad'],
            ['id' => 3, 'name' => 'Usman Tariq', 'employee_id' => 'EMP-003', 'vehicle' => 'Mazda Truck - GHI-789', 'area' => 'Orangi Town'],
            ['id' => 4, 'name' => 'Zubair Malik', 'employee_id' => 'EMP-004', 'vehicle' => 'Toyota Hilux - JKL-012', 'area' => 'Liaquatabad'],
            ['id' => 5, 'name' => 'Kashif Hussain', 'employee_id' => 'EMP-005', 'vehicle' => 'Suzuki Carry - MNO-345', 'area' => 'Saddar'],
        ];
    }
}
