<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = $this->expenseRecords();
        $todayTotal = collect($expenses)->where('date', '03-Sep-2026')->sum('amount');
        $monthlyFuelMaintenance = collect($expenses)->whereIn('category', ['Vehicle Fuel', 'Vehicle Repair'])->sum('amount');
        $staffTotal = collect($expenses)->whereIn('category', ['Driver Allowance', 'Driver Meals / Allowance'])->sum('amount');
        $pendingTotal = collect($expenses)->where('status', 'Pending Verification')->sum('amount');

        return view('expenses.index', compact('expenses', 'todayTotal', 'monthlyFuelMaintenance', 'staffTotal', 'pendingTotal'));
    }

    public function create(): View
    {
        return view('expenses.create');
    }

    public function show(int $expense): View
    {
        $record = collect($this->expenseRecords())->firstWhere('id', $expense);
        abort_unless($record, 404);

        return view('expenses.show', compact('record'));
    }

    private function expenseRecords(): array
    {
        return [
            ['id' => 1, 'expense_id' => 'EXP-2026-09-001', 'date' => '03-Sep-2026', 'category' => 'Vehicle Fuel', 'source' => 'Paid from Cash Drawer', 'driver' => 'Ahmed Khan', 'route' => 'KHI-1234 / Gulshan Route', 'amount' => 8500, 'voucher' => 'Fuel Slip #4412', 'status' => 'Approved', 'approved_by' => 'Admin', 'payment_source' => 'Cash', 'receipt' => 'Fuel Slip #4412', 'market' => 'Gulshan-e-Iqbal', 'created_by' => 'Admin', 'created_at' => '03-Sep-2026', 'notes' => 'Diesel for morning dispatch.'],
            ['id' => 2, 'expense_id' => 'EXP-2026-09-002', 'date' => '03-Sep-2026', 'category' => 'Driver Allowance', 'source' => 'Paid from Cash Drawer', 'driver' => 'Bilal Raza', 'route' => 'KHI-4567 / Saddar Route', 'amount' => 4500, 'voucher' => 'ALW-260903-02', 'status' => 'Pending Verification', 'approved_by' => 'Pending', 'payment_source' => 'Cash', 'receipt' => 'ALW-260903-02', 'market' => 'Saddar', 'created_by' => 'Operator 1', 'created_at' => '03-Sep-2026', 'notes' => 'Daily route allowance awaiting receipt verification.'],
            ['id' => 3, 'expense_id' => 'EXP-2026-09-003', 'date' => '02-Sep-2026', 'category' => 'Vehicle Repair', 'source' => 'Paid from Bank Account', 'driver' => 'Usman Tariq', 'route' => 'KHI-7890 / Orangi Route', 'amount' => 12000, 'voucher' => 'REP-8831', 'status' => 'Approved', 'approved_by' => 'Admin', 'payment_source' => 'Bank A', 'receipt' => 'REP-8831', 'market' => 'Orangi Town', 'created_by' => 'Admin', 'created_at' => '02-Sep-2026', 'notes' => 'Brake service and minor van repair.'],
            ['id' => 4, 'expense_id' => 'EXP-2026-09-004', 'date' => '01-Sep-2026', 'category' => 'Warehouse Rent', 'source' => 'Paid from Bank Account', 'driver' => 'N/A', 'route' => 'AAA Traders Warehouse', 'amount' => 30000, 'voucher' => 'RENT-SEP-26', 'status' => 'Approved', 'approved_by' => 'Admin', 'payment_source' => 'Bank B', 'receipt' => 'RENT-SEP-26', 'market' => 'Warehouse', 'created_by' => 'Admin', 'created_at' => '01-Sep-2026', 'notes' => 'September warehouse rent.'],
            ['id' => 5, 'expense_id' => 'EXP-2026-09-005', 'date' => '31-Aug-2026', 'category' => 'Utilities', 'source' => 'Driver Out-of-Pocket', 'driver' => 'Kashif Hussain', 'route' => 'MNO-345 / North Route', 'amount' => 2500, 'voucher' => 'UTIL-901', 'status' => 'Rejected', 'approved_by' => 'Admin', 'payment_source' => 'Driver Petty Cash', 'receipt' => 'UTIL-901', 'market' => 'North Nazimabad', 'created_by' => 'Operator 1', 'created_at' => '31-Aug-2026', 'notes' => 'Receipt image was not legible.'],
        ];
    }
}
