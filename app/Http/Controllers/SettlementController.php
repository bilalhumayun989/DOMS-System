<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SettlementController extends Controller
{
    public function index(): View
    {
        $settlements = $this->settlementRecords();
        $totalSettlements = collect($settlements)->sum('net_sales');
        $totalCash = collect($settlements)->sum('actual_cash');
        $totalCredit = collect($settlements)->sum('credit');
        $totalShortage = collect($settlements)->sum('shortage');

        return view('settlements.index', compact('settlements', 'totalSettlements', 'totalCash', 'totalCredit', 'totalShortage'));
    }

    public function create(): View
    {
        $tripOptions = [
            ['id' => 'TR-2026-09-02-001', 'date' => '03-Sep-2026', 'driver' => 'Ahmed Khan', 'vehicle' => 'KHI-1234', 'market' => 'Gulshan-e-Iqbal', 'distributor' => 'AAA Traders', 'net_sales' => 120000, 'credit' => 20000, 'collections' => 68000, 'expenses' => 2500],
            ['id' => 'TR-2026-09-02-002', 'date' => '03-Sep-2026', 'driver' => 'Bilal Raza', 'vehicle' => 'KHI-4567', 'market' => 'Saddar', 'distributor' => 'AAA Traders', 'net_sales' => 95000, 'credit' => 10000, 'collections' => 75000, 'expenses' => 1800],
            ['id' => 'TR-2026-09-01-001', 'date' => '02-Sep-2026', 'driver' => 'Usman Tariq', 'vehicle' => 'KHI-7890', 'market' => 'North Nazimabad', 'distributor' => 'AAA Traders', 'net_sales' => 72000, 'credit' => 7000, 'collections' => 62000, 'expenses' => 1600],
        ];

        return view('settlements.create', compact('tripOptions'));
    }

    public function show(int $settlement): View
    {
        $record = collect($this->settlementRecords())->firstWhere('id', $settlement);
        abort_unless($record, 404);

        return view('settlements.show', compact('record'));
    }

    private function settlementRecords(): array
    {
        return [
            ['id' => 1, 'settlement_ref' => 'SET-2026-09-001', 'date' => '03-Sep-2026', 'trip_display' => 'TR-2026-09-02-001', 'trip_id' => 1, 'deliveryman' => 'Ahmed Khan', 'vehicle' => 'KHI-1234', 'market' => 'Gulshan-e-Iqbal', 'distributor' => 'AAA Traders', 'net_sales' => 120000, 'expected_cash' => 88000, 'actual_cash' => 80000, 'shortage' => 8000, 'status' => 'Shortage Flagged', 'loaded' => 140000, 'returned' => 18000, 'damaged' => 2000, 'discounts' => 7000, 'credit' => 20000, 'cheques' => 18000, 'transfers' => 8000, 'action' => 'Charge to Salesman Ledger', 'settled_by' => 'Manager', 'excess' => 0],
            ['id' => 2, 'settlement_ref' => 'SET-2026-09-002', 'date' => '03-Sep-2026', 'trip_display' => 'TR-2026-09-02-002', 'trip_id' => 2, 'deliveryman' => 'Bilal Raza', 'vehicle' => 'KHI-4567', 'market' => 'Saddar', 'distributor' => 'AAA Traders', 'net_sales' => 95000, 'expected_cash' => 75000, 'actual_cash' => 75000, 'shortage' => 0, 'status' => 'Fully Cleared', 'loaded' => 110000, 'returned' => 15000, 'damaged' => 0, 'discounts' => 5000, 'credit' => 10000, 'cheques' => 7000, 'transfers' => 3000, 'action' => 'None', 'settled_by' => 'Admin', 'excess' => 0],
            ['id' => 3, 'settlement_ref' => 'SET-2026-09-003', 'date' => '02-Sep-2026', 'trip_display' => 'TR-2026-09-01-001', 'trip_id' => 3, 'deliveryman' => 'Usman Tariq', 'vehicle' => 'KHI-7890', 'market' => 'North Nazimabad', 'distributor' => 'AAA Traders', 'net_sales' => 72000, 'expected_cash' => 62000, 'actual_cash' => 62000, 'shortage' => 0, 'status' => 'Fully Cleared', 'loaded' => 80000, 'returned' => 8000, 'damaged' => 0, 'discounts' => 3000, 'credit' => 7000, 'cheques' => 5000, 'transfers' => 2000, 'action' => 'None', 'settled_by' => 'Manager', 'excess' => 0],
            ['id' => 4, 'settlement_ref' => 'SET-2026-09-004', 'date' => '01-Sep-2026', 'trip_display' => 'TR-2026-08-31-001', 'trip_id' => 4, 'deliveryman' => 'Kashif Hussain', 'vehicle' => 'KHI-3456', 'market' => 'Clifton', 'distributor' => 'AAA Traders', 'net_sales' => 68000, 'expected_cash' => 55000, 'actual_cash' => 52000, 'shortage' => 3000, 'status' => 'Pending Audit', 'loaded' => 75000, 'returned' => 7000, 'damaged' => 0, 'discounts' => 2500, 'credit' => 10000, 'cheques' => 4000, 'transfers' => 1000, 'action' => 'Recover from Salary', 'settled_by' => 'Pending', 'excess' => 0],
        ];
    }
}
