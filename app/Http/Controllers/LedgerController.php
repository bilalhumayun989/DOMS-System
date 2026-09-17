<?php

namespace App\Http\Controllers;

use App\Models\Deliveryman;
use App\Models\Ledger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LedgerController extends Controller
{
    public function index(): View
    {
        $entries = Ledger::orderBy('entry_date')->orderBy('id')->get();
        $supplierLedgers = $entries->where('ledger_group', 'Supplier')->map(fn (Ledger $entry): array => $this->present($entry))->values()->all();
        $driverLedgers = $entries->where('ledger_group', 'Driver')->map(fn (Ledger $entry): array => [
            ...$this->present($entry), 'driver' => $entry->entity_name, 'employee_id' => Deliveryman::where('name', $entry->entity_name)->value('employee_id') ?? '',
            'trip' => $entry->linked_invoice_trip ?? '', 'market' => '', 'expected' => $entry->entry_type === 'Debit' ? (float) $entry->amount : 0,
            'actual' => $entry->entry_type === 'Credit' ? (float) $entry->amount : 0,
            'shortage' => $entry->entry_type === 'Debit' ? (float) $entry->amount : -(float) $entry->amount,
        ])->values()->all();
        $ebmBalance = $entries->where('entity_name', 'EBM - English Biscuit Manufacturers')->sum(fn (Ledger $entry): float => $entry->entry_type === 'Debit' ? (float) $entry->amount : -(float) $entry->amount);
        $cflBalance = $entries->where('entity_name', 'CFL - Coronet Foods Limited')->sum(fn (Ledger $entry): float => $entry->entry_type === 'Debit' ? (float) $entry->amount : -(float) $entry->amount);
        $driverBalance = collect($driverLedgers)->sum('shortage');
        $transactionCount = $entries->count();

        return view('ledgers.index', compact('supplierLedgers', 'driverLedgers', 'ebmBalance', 'cflBalance', 'driverBalance', 'transactionCount'));
    }

    public function create(): View
    {
        return view('ledgers.create', ['drivers' => Deliveryman::pluck('name')->all()]);
    }

    public function edit(int $id): View
    {
        return view('ledgers.create', ['drivers' => Deliveryman::pluck('name')->all(), 'entry' => Ledger::findOrFail($id)->toArray(), 'editId' => $id]);
    }

    public function show(int $id): View
    {
        return view('ledgers.show', ['entry' => $this->present(Ledger::findOrFail($id))]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $entry = DB::transaction(function () use ($data): Ledger {
            $entry = Ledger::create($data);
            $this->recalculate($entry->ledger_group, $entry->entity_name);

            return $entry;
        });

        return to_route('ledgers.show', $entry->id)->with('success', 'Ledger entry saved.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $entry = Ledger::findOrFail($id);
        $data = $this->validatedData($request);
        DB::transaction(function () use ($entry, $data): void {
            $group = $entry->ledger_group;
            $entity = $entry->entity_name;
            $entry->update($data);
            $this->recalculate($group, $entity);
            $this->recalculate($entry->ledger_group, $entry->entity_name);
        });

        return to_route('ledgers.show', $id)->with('success', 'Ledger entry updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $entry = Ledger::findOrFail($id);
        DB::transaction(function () use ($entry): void {
            $entry->delete();
            $this->recalculate($entry->ledger_group, $entry->entity_name);
        });

        return to_route('ledgers.index')->with('success', 'Ledger entry deleted.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'ledger_group' => ['required', Rule::in(['Supplier', 'Driver'])], 'entity_name' => ['required', 'string', 'max:255'],
            'entry_date' => ['required', 'date'], 'voucher_reference' => ['required', 'string', 'max:255'],
            'transaction_category' => ['required', 'string', 'max:255'], 'entry_type' => ['required', 'in:Debit,Credit'],
            'amount' => ['required', 'numeric', 'gt:0'], 'payment_method' => ['nullable', 'string', 'max:255'],
            'bank_reference' => ['nullable', 'string', 'max:255'], 'linked_invoice_trip' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:5000'], 'document' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ]);
        unset($data['document']);
        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('ledger-documents');
        }

        return $data;
    }

    private function recalculate(string $group, string $entity): void
    {
        $balance = 0;
        foreach (Ledger::where('ledger_group', $group)->where('entity_name', $entity)->orderBy('entry_date')->orderBy('id')->lockForUpdate()->get() as $entry) {
            $previous = $balance;
            $balance += $entry->entry_type === 'Debit' ? (float) $entry->amount : -(float) $entry->amount;
            $entry->update(['previous_balance' => $previous, 'running_balance' => round($balance, 2)]);
        }
    }

    private function present(Ledger $entry): array
    {
        return [...$entry->toArray(), 'date' => $entry->entry_date->toDateString(), 'entity' => $entry->entity_name,
            'reference' => $entry->voucher_reference, 'type' => $entry->transaction_category, 'description' => $entry->remarks,
            'debit' => $entry->entry_type === 'Debit' ? (float) $entry->amount : 0,
            'credit' => $entry->entry_type === 'Credit' ? (float) $entry->amount : 0,
            'balance' => (float) $entry->running_balance, 'sku_items' => []];
    }

    public function document(int $id): StreamedResponse
    {
        $entry = Ledger::findOrFail($id);
        abort_unless($entry->document_path && Storage::exists($entry->document_path), 404);

        return Storage::download($entry->document_path);
    }
}
