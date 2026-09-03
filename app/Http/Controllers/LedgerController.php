<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LedgerController extends Controller
{
    public function index(): View
    {
        $supplierLedgers = $this->supplierEntries();
        $driverLedgers = $this->driverEntries();
        $ebmBalance = collect($supplierLedgers)->where('entity', 'EBM')->sum('debit') - collect($supplierLedgers)->where('entity', 'EBM')->sum('credit');
        $cflBalance = collect($supplierLedgers)->where('entity', 'CFL')->sum('debit') - collect($supplierLedgers)->where('entity', 'CFL')->sum('credit');
        $driverBalance = collect($driverLedgers)->sum('shortage');
        $transactionCount = count($supplierLedgers) + count($driverLedgers);

        return view('ledgers.index', compact('supplierLedgers', 'driverLedgers', 'ebmBalance', 'cflBalance', 'driverBalance', 'transactionCount'));
    }

    public function create(): View
    {
        return view('ledgers.create', ['drivers' => ['Ahmed Khan', 'Bilal Raza', 'Usman Tariq', 'Zubair Malik', 'Kashif Hussain']]);
    }

    public function edit(int $id): View
    {
        $entry = collect(array_merge($this->supplierEntries(), $this->driverEntries()))->firstWhere('id', $id);
        abort_unless($entry, 404);

        return view('ledgers.create', [
            'drivers' => ['Ahmed Khan', 'Bilal Raza', 'Usman Tariq', 'Zubair Malik', 'Kashif Hussain'],
            'editId' => $id,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        abort_unless(collect(array_merge($this->supplierEntries(), $this->driverEntries()))->contains('id', $id), 404);

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry updated successfully (demo only).');
    }

    public function destroy(int $id): RedirectResponse
    {
        abort_unless(collect(array_merge($this->supplierEntries(), $this->driverEntries()))->contains('id', $id), 404);

        return redirect()->route('ledgers.index')->with('success', 'Ledger entry deleted successfully (demo only).');
    }

    public function store(Request $request): View
    {
        $validated = $request->validate([
            'ledger_group' => ['required', 'string'], 'entity_name' => ['required', 'string'], 'entry_date' => ['required', 'date'],
            'voucher_reference' => ['required', 'string'], 'transaction_category' => ['required', 'string'], 'entry_type' => ['required', 'in:Debit,Credit'],
            'amount' => ['required', 'numeric', 'min:0'], 'payment_method' => ['nullable', 'string'], 'bank_reference' => ['nullable', 'string'],
            'linked_invoice_trip' => ['nullable', 'string'], 'remarks' => ['nullable', 'string'],
        ]);
        $entry = array_merge($validated, ['previous_balance' => 0, 'running_balance' => (float) $request->input('amount', 0), 'payment_method' => $request->input('payment_method', 'Not specified'), 'bank_reference' => $request->input('bank_reference', 'Not provided'), 'verification_status' => 'Pending Verification', 'created_by' => 'Admin', 'sku_items' => []]);

        return view('ledgers.show', compact('entry'));
    }

    public function show(int $id): View
    {
        $entry = collect(array_merge($this->supplierEntries(), $this->driverEntries()))->firstWhere('id', $id);
        abort_unless($entry, 404);

        if (isset($entry['driver'])) {
            $entry = [
                'entity' => $entry['driver'].' ('.$entry['employee_id'].')',
                'reference' => 'DRIVER-'.$entry['trip'],
                'date' => $entry['date'],
                'type' => 'Driver Cash Shortage',
                'previous_balance' => $entry['balance'] - $entry['shortage'],
                'amount' => $entry['shortage'],
                'running_balance' => $entry['balance'],
                'payment_method' => 'Cash Drawer',
                'bank_reference' => 'N/A',
                'linked_invoice_trip' => $entry['trip'],
                'remarks' => 'Cash reconciliation for '.$entry['market'].' route. Expected '.pkr($entry['expected']).' and submitted '.pkr($entry['actual']).'.',
                'verification_status' => $entry['shortage'] > 0 ? 'Pending Verification' : 'Verified',
                'created_by' => 'Admin',
                'sku_items' => [],
            ];
        }

        return view('ledgers.show', compact('entry'));
    }

    private function supplierEntries(): array
    {
        return [
            ['id' => 1, 'entity' => 'EBM', 'date' => '03-Sep-2026', 'reference' => 'EBM-INV-8892', 'type' => 'Stock Lifting', 'description' => 'Primary biscuits stock received', 'debit' => 600000, 'credit' => 0, 'balance' => 600000, 'payment_method' => 'Bank A', 'bank_reference' => 'LIFT-260903', 'linked_invoice_trip' => 'INV-8892', 'verification_status' => 'Verified', 'created_by' => 'Accountant', 'sku_items' => [['name' => 'Sooper FP', 'units' => '120 Cartons', 'rate' => 2400, 'total' => 288000], ['name' => 'Rio Chocolate', 'units' => '100 Cartons', 'rate' => 1800, 'total' => 180000]]],
            ['id' => 2, 'entity' => 'EBM', 'date' => '04-Sep-2026', 'reference' => 'PAY-EBM-004', 'type' => 'Bank Payment', 'description' => 'Payment against primary invoice', 'debit' => 0, 'credit' => 250000, 'balance' => 350000, 'payment_method' => 'Bank A', 'bank_reference' => 'CHQ-4412', 'linked_invoice_trip' => 'EBM-INV-8892', 'verification_status' => 'Verified', 'created_by' => 'Admin', 'sku_items' => []],
            ['id' => 3, 'entity' => 'CFL', 'date' => '03-Sep-2026', 'reference' => 'CFL-INV-117', 'type' => 'Stock Lifting', 'description' => 'Coronet Foods primary stock', 'debit' => 425000, 'credit' => 0, 'balance' => 425000, 'payment_method' => 'Bank B', 'bank_reference' => 'LIFT-260903-C', 'linked_invoice_trip' => 'INV-117', 'verification_status' => 'Pending Verification', 'created_by' => 'Accountant', 'sku_items' => [['name' => 'Gluco Family', 'units' => '80 Cartons', 'rate' => 2483.33, 'total' => 198666.40]]],
            ['id' => 4, 'entity' => 'CFL', 'date' => '05-Sep-2026', 'reference' => 'CLM-CFL-09', 'type' => 'Damage/Expiry Claim', 'description' => 'Credit for damaged cartons', 'debit' => 0, 'credit' => 35000, 'balance' => 390000, 'payment_method' => 'Credit Note', 'bank_reference' => 'CN-2026-09', 'linked_invoice_trip' => 'CFL-INV-117', 'verification_status' => 'Verified', 'created_by' => 'Admin', 'sku_items' => []],
        ];
    }

    private function driverEntries(): array
    {
        return [
            ['id' => 101, 'driver' => 'Ahmed Khan', 'employee_id' => 'EMP-001', 'date' => '03-Sep-2026', 'trip' => 'TR-2026-09-02-001', 'market' => 'Gulshan-e-Iqbal', 'expected' => 88000, 'actual' => 80000, 'shortage' => 8000, 'balance' => 12500],
            ['id' => 102, 'driver' => 'Bilal Raza', 'employee_id' => 'EMP-002', 'date' => '03-Sep-2026', 'trip' => 'TR-2026-09-02-002', 'market' => 'Saddar', 'expected' => 75000, 'actual' => 75000, 'shortage' => 0, 'balance' => 8000],
            ['id' => 103, 'driver' => 'Usman Tariq', 'employee_id' => 'EMP-003', 'date' => '02-Sep-2026', 'trip' => 'TR-2026-09-01-001', 'market' => 'North Nazimabad', 'expected' => 62000, 'actual' => 60000, 'shortage' => 2000, 'balance' => 6000],
        ];
    }
}
