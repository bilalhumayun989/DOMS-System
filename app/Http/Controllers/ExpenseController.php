<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Trip;
use App\Models\TripExpense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = $this->expenseRecords();
        $todayTotal = collect($expenses)->where('date', now()->toDateString())->sum('amount');
        $monthlyFuelMaintenance = collect($expenses)->filter(fn (array $row): bool => substr($row['date'], 0, 7) === now()->format('Y-m'))->whereIn('category', ['Fuel', 'Vehicle Fuel', 'Vehicle Repair', 'Repair/Maintenance'])->sum('amount');
        $staffTotal = collect($expenses)->whereIn('category', ['Driver Allowance', 'Driver Meals / Allowance'])->sum('amount');
        $pendingTotal = collect($expenses)->where('status', 'Pending Verification')->sum('amount');

        return view('expenses.index', compact('expenses', 'todayTotal', 'monthlyFuelMaintenance', 'staffTotal', 'pendingTotal'));
    }

    public function create(): View
    {
        return view('expenses.create', ['trips' => Trip::where('status', '!=', 'CLOSED')->get()]);
    }

    public function show(string $expense): View|RedirectResponse
    {
        if (str_starts_with($expense, 'trip-')) {
            $record = TripExpense::findOrFail(substr($expense, 5));

            return to_route('trips.show', $record->trip_id);
        }
        $record = collect($this->expenseRecords())->firstWhere('id', (int) $expense);
        abort_unless($record, 404);

        return view('expenses.show', compact('record'));
    }

    private function expenseRecords(): array
    {
        $records = Expense::orderBy('id')->get()->toArray();
        $tripExpenses = TripExpense::with('trip')->whereNotIn('expense_ref', array_column($records, 'expense_id'))->get()->map(fn (TripExpense $expense): array => [
            'id' => 'trip-'.$expense->id, 'expense_id' => $expense->expense_ref, 'date' => $expense->expense_date->toDateString(),
            'category' => $expense->category, 'source' => 'Trip Collection', 'driver' => $expense->trip->deliveryman_name,
            'route' => $expense->trip->vehicle, 'amount' => (float) $expense->amount, 'voucher' => $expense->expense_ref, 'status' => 'Pending Verification',
        ])->all();

        return array_merge($records, $tripExpenses);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date'], 'category' => ['required', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:5000'], 'amount' => ['required', 'numeric', 'gt:0'],
            'source' => ['required', 'in:Cash in Hand,Bank Account,Driver Petty Cash'],
            'payment_source' => ['nullable', 'string', 'max:150'], 'voucher' => ['nullable', 'string', 'max:150'],
            'trip_id' => ['nullable', 'integer', 'exists:trips,id'], 'driver' => ['nullable', 'string', 'max:150'],
            'route' => ['nullable', 'string', 'max:150'], 'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
        $trip = ! empty($data['trip_id']) ? Trip::findOrFail($data['trip_id']) : null;
        abort_if($trip?->isClosed(), 422, 'Closed trips are locked.');
        unset($data['attachment']);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('expense-receipts');
        }
        $expense = DB::transaction(function () use ($data, $trip): Expense {
            $expense = Expense::create([...$data, 'expense_id' => 'EXP-'.str()->upper(str()->random(10)),
                'status' => 'Pending Verification', 'approved_by' => 'Pending', 'receipt' => $data['voucher'] ?? '',
                'created_by' => auth()->user()?->name ?? 'Admin', 'market' => $trip?->market_area ?? '',
                'driver' => $trip?->deliveryman_name ?? ($data['driver'] ?? ''), 'route' => $trip?->vehicle ?? ($data['route'] ?? '')]);
            if ($trip) {
                $trip->expenses()->create(['expense_ref' => $expense->expense_id, 'category' => $expense->category,
                    'amount' => $expense->amount, 'expense_date' => $expense->date, 'description' => $expense->notes]);
            }

            return $expense;
        });

        return to_route('expenses.show', $expense->id)->with('success', 'Expense saved.');
    }

    public function attachment(Expense $expense): StreamedResponse
    {
        abort_unless($expense->attachment_path && Storage::exists($expense->attachment_path), 404);

        return Storage::download($expense->attachment_path);
    }
}
