<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DeliverymanController extends Controller
{
    private function deliverymen(): array
    {
        return [
            ['id' => 1, 'name' => 'Ahmed Khan', 'employee_id' => 'EMP-001', 'phone' => '0300-1234567', 'joined_at' => '2023-03-01', 'total_trips' => 48, 'active_trips' => 1, 'total_collected' => 98000, 'outstanding_shortages' => 12500],
            ['id' => 2, 'name' => 'Bilal Raza', 'employee_id' => 'EMP-002', 'phone' => '0301-2345678', 'joined_at' => '2023-05-15', 'total_trips' => 41, 'active_trips' => 1, 'total_collected' => 76000, 'outstanding_shortages' => 8000],
            ['id' => 3, 'name' => 'Usman Tariq', 'employee_id' => 'EMP-003', 'phone' => '0302-3456789', 'joined_at' => '2023-07-20', 'total_trips' => 36, 'active_trips' => 1, 'total_collected' => 65000, 'outstanding_shortages' => 4000],
            ['id' => 4, 'name' => 'Zubair Malik', 'employee_id' => 'EMP-004', 'phone' => '0303-4567890', 'joined_at' => '2024-01-10', 'total_trips' => 29, 'active_trips' => 1, 'total_collected' => 88000, 'outstanding_shortages' => 0],
            ['id' => 5, 'name' => 'Kashif Hussain', 'employee_id' => 'EMP-005', 'phone' => '0304-5678901', 'joined_at' => '2024-03-05', 'total_trips' => 22, 'active_trips' => 1, 'total_collected' => 50000, 'outstanding_shortages' => 0],
        ];
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

        $tripHistory = [
            ['id' => 1, 'trip_id' => 'TR-2025-07-15-001', 'date' => '2025-07-15', 'market_area' => 'Gulshan-e-Iqbal', 'distributor' => 'AAA Traders', 'vehicle' => 'Toyota Hilux - ABC-123', 'status' => 'DISPATCHED', 'load_value' => 125400, 'collected' => 0, 'shortage' => 0, 'opening_stock' => 125400, 'returned_stock' => 15400, 'damaged_stock' => 2000, 'net_sales' => 108000, 'gross_sales' => 115000, 'discounts' => 7000, 'cash_collected' => 62000, 'cheques_collected' => 18000, 'online_transfers' => 8000, 'market_credit' => 20000, 'submitted' => 108000, 'expected_cash' => 88000, 'actual_cash' => 80000, 'trip_shortage' => 8000, 'accumulated_shortage' => 12500],
            ['id' => 6, 'trip_id' => 'TR-2025-07-14-001', 'date' => '2025-07-14', 'market_area' => 'Clifton', 'distributor' => 'Main Distributor', 'vehicle' => 'Suzuki Ravi - DEF-456', 'status' => 'SETTLED', 'load_value' => 143000, 'collected' => 107500, 'shortage' => 2500, 'opening_stock' => 143000, 'returned_stock' => 23000, 'damaged_stock' => 0, 'net_sales' => 120000, 'gross_sales' => 126000, 'discounts' => 6000, 'cash_collected' => 85000, 'cheques_collected' => 22000, 'online_transfers' => 5000, 'market_credit' => 8000, 'submitted' => 120000, 'expected_cash' => 112000, 'actual_cash' => 109500, 'trip_shortage' => 2500, 'accumulated_shortage' => 12500],
            ['id' => 11, 'trip_id' => 'TR-2025-07-11-001', 'date' => '2025-07-11', 'market_area' => 'Saddar', 'distributor' => 'AAA Traders', 'vehicle' => 'Toyota Hilux - ABC-123', 'status' => 'COMPLETED', 'load_value' => 88900, 'collected' => 62000, 'shortage' => 0, 'opening_stock' => 88900, 'returned_stock' => 8900, 'damaged_stock' => 0, 'net_sales' => 80000, 'gross_sales' => 84000, 'discounts' => 4000, 'cash_collected' => 62000, 'cheques_collected' => 10000, 'online_transfers' => 3000, 'market_credit' => 5000, 'submitted' => 80000, 'expected_cash' => 75000, 'actual_cash' => 75000, 'trip_shortage' => 0, 'accumulated_shortage' => 12500],
            ['id' => 10, 'trip_id' => 'TR-2025-07-12-001', 'date' => '2025-07-12', 'market_area' => 'North Nazimabad', 'distributor' => 'Main Distributor', 'vehicle' => 'Toyota Hilux - ABC-123', 'status' => 'COMPLETED', 'load_value' => 67800, 'collected' => 48000, 'shortage' => 10000, 'opening_stock' => 67800, 'returned_stock' => 7800, 'damaged_stock' => 0, 'net_sales' => 60000, 'gross_sales' => 64000, 'discounts' => 4000, 'cash_collected' => 48000, 'cheques_collected' => 2000, 'online_transfers' => 0, 'market_credit' => 10000, 'submitted' => 60000, 'expected_cash' => 50000, 'actual_cash' => 40000, 'trip_shortage' => 10000, 'accumulated_shortage' => 12500],
        ];

        $summary = [
            'total_trips' => $deliveryman['total_trips'],
            'total_value_delivered' => 425100,
            'total_collected' => 217500,
            'total_shortages' => 12500,
            'ledger_balance' => 12500,
        ];

        $ledgerEntries = [
            ['date' => '2025-07-14', 'trip_id' => 'TR-2025-07-14-001', 'type' => 'Goods Out', 'debit' => 143000, 'credit' => 0, 'balance' => 143000],
            ['date' => '2025-07-14', 'trip_id' => 'TR-2025-07-14-001', 'type' => 'Collection', 'debit' => 0, 'credit' => 107500, 'balance' => 35500],
            ['date' => '2025-07-14', 'trip_id' => 'TR-2025-07-14-001', 'type' => 'Returns', 'debit' => 0, 'credit' => 23000, 'balance' => 12500],
            ['date' => '2025-07-12', 'trip_id' => 'TR-2025-07-12-001', 'type' => 'Shortage', 'debit' => 10000, 'credit' => 0, 'balance' => 22500],
            ['date' => '2025-07-12', 'trip_id' => 'TR-2025-07-12-001', 'type' => 'Recovery', 'debit' => 0, 'credit' => 10000, 'balance' => 12500],
        ];

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Deliverymen', 'route' => route('deliverymen.index')],
            ['label' => $deliveryman['name'], 'route' => null],
        ];

        return view('deliverymen.show', compact('deliveryman', 'tripHistory', 'summary', 'ledgerEntries', 'breadcrumbs'));
    }
}
