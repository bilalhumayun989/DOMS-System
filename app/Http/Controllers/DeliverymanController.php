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
        if (! $deliveryman) abort(404);

        $tripHistory = [
            ['id' => 1, 'trip_id' => 'TR-2025-07-15-001', 'date' => '2025-07-15', 'market_area' => 'Gulshan-e-Iqbal', 'status' => 'DISPATCHED', 'load_value' => 125400, 'collected' => 0, 'shortage' => 0],
            ['id' => 6, 'trip_id' => 'TR-2025-07-14-001', 'date' => '2025-07-14', 'market_area' => 'Clifton', 'status' => 'SETTLED', 'load_value' => 143000, 'collected' => 107500, 'shortage' => 2500],
            ['id' => 11, 'trip_id' => 'TR-2025-07-11-001', 'date' => '2025-07-11', 'market_area' => 'Saddar', 'status' => 'COMPLETED', 'load_value' => 88900, 'collected' => 62000, 'shortage' => 0],
            ['id' => 10, 'trip_id' => 'TR-2025-07-12-001', 'date' => '2025-07-12', 'market_area' => 'North Nazimabad', 'status' => 'COMPLETED', 'load_value' => 67800, 'collected' => 48000, 'shortage' => 10000],
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
