<?php

namespace App\Http\Controllers;

use App\Models\Deliveryman;
use App\Models\Trip;
use App\Models\TripCollection;
use App\Models\TripSettlement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DeliverymanController extends Controller
{
    private function deliverymen(): array
    {
        return Deliveryman::orderBy('id')->get()->map(function (Deliveryman $driver): array {
            $trips = Trip::where('deliveryman_id', $driver->id)->get();

            return [...$driver->toArray(), 'total_trips' => $trips->count(), 'active_trips' => $trips->whereNotIn('status', ['CLOSED', 'SETTLED'])->count(),
                'total_collected' => (float) TripCollection::whereIn('trip_id', $trips->pluck('id'))->sum('amount'),
                'outstanding_shortages' => (float) TripSettlement::whereIn('trip_id', $trips->pluck('id'))->where('difference_amount', '>', 0)->sum('difference_amount')];
        })->all();
    }

    public function index(): View
    {
        $deliverymen = $this->deliverymen();

        return view('deliverymen.index', compact('deliverymen'));
    }

    public function show(int $id): View
    {
        $deliveryman = collect($this->deliverymen())->firstWhere('id', $id);
        if (! $deliveryman) {
            abort(404);
        }

        $tripHistory = Trip::where('deliveryman_id', $id)->with(['collections', 'expenses', 'settlement'])->get()->map(function (Trip $trip): array {
            $collected = (float) $trip->collections->sum('amount');
            $shortage = (float) ($trip->settlement?->difference_amount ?? 0);

            return ['id' => $trip->id, 'trip_id' => $trip->trip_number, 'date' => $trip->trip_date->toDateString(), 'market_area' => $trip->market_area,
                'distributor' => 'AAA Traders', 'vehicle' => $trip->vehicle, 'status' => $trip->status, 'load_value' => (float) $trip->load_value,
                'collected' => $collected, 'shortage' => $shortage, 'opening_stock' => (float) $trip->load_value, 'returned_stock' => 0,
                'damaged_stock' => 0, 'net_sales' => (float) $trip->expected_cash, 'gross_sales' => (float) $trip->load_value, 'discounts' => 0,
                'cash_collected' => (float) $trip->collections->where('method', 'Cash')->sum('amount'), 'cheques_collected' => (float) $trip->collections->where('method', 'Cheque')->sum('amount'),
                'online_transfers' => (float) $trip->collections->where('method', 'Bank Transfer')->sum('amount'), 'market_credit' => 0,
                'submitted' => $collected, 'expected_cash' => (float) $trip->expected_cash, 'actual_cash' => $collected,
                'trip_shortage' => $shortage, 'accumulated_shortage' => $shortage];
        })->all();
        $summary = ['total_trips' => count($tripHistory), 'total_value_delivered' => array_sum(array_column($tripHistory, 'load_value')),
            'total_collected' => $deliveryman['total_collected'], 'total_shortages' => $deliveryman['outstanding_shortages'], 'ledger_balance' => $deliveryman['outstanding_shortages']];
        $ledgerEntries = [];

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Deliverymen', 'route' => route('deliverymen.index')],
            ['label' => $deliveryman['name'], 'route' => null],
        ];

        return view('deliverymen.show', compact('deliveryman', 'tripHistory', 'summary', 'ledgerEntries', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        Deliveryman::create($this->validatedData($request));

        return to_route('deliverymen.index')->with('success', 'Record created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $record = Deliveryman::findOrFail($id);
        $record->update($this->validatedData($request, $id));

        return to_route('deliverymen.index')->with('success', 'Record updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = Deliveryman::findOrFail($id);
        abort_if(Trip::where('deliveryman_id', $id)->exists(), 422, 'This deliveryman has trips and cannot be deleted.');
        $record->delete();

        return to_route('deliverymen.index')->with('success', 'Record deleted.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('deliverymen')->ignore($id)],
            'phone' => ['required', 'string', 'max:40'],
            'vehicle' => ['nullable', 'string', 'max:150'],
            'joined_at' => ['required', 'date'],
            'assigned_areas' => ['nullable', 'array'],
            'assigned_areas.*' => ['string', 'max:150'],
        ]);
        $data['assigned_areas'] = $data['assigned_areas'] ?? [];

        return $data;
    }
}
