<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TripController extends Controller
{
    private function trips(): array
    {
        return [
            ['id' => 1, 'trip_id' => 'TR-2025-07-15-001', 'date' => '2025-07-15', 'deliveryman' => ['id' => 1, 'name' => 'Ahmed Khan'], 'vehicle' => 'Toyota Hilux – ABC-123', 'market_area' => 'Gulshan-e-Iqbal', 'source_dlf' => 'DLF-2025-07-15-001', 'status' => 'DISPATCHED', 'load_value' => 125400, 'expected_cash' => 98000],
            ['id' => 2, 'trip_id' => 'TR-2025-07-15-002', 'date' => '2025-07-15', 'deliveryman' => ['id' => 2, 'name' => 'Bilal Raza'], 'vehicle' => 'Suzuki Ravi – DEF-456', 'market_area' => 'North Nazimabad', 'source_dlf' => 'DLF-2025-07-15-002', 'status' => 'COMPLETED', 'load_value' => 98700, 'expected_cash' => 76000],
            ['id' => 3, 'trip_id' => 'TR-2025-07-15-003', 'date' => '2025-07-15', 'deliveryman' => ['id' => 3, 'name' => 'Usman Tariq'], 'vehicle' => 'Mazda Truck – GHI-789', 'market_area' => 'Orangi Town', 'source_dlf' => 'DLF-2025-07-15-003', 'status' => 'DISPATCHED', 'load_value' => 87300, 'expected_cash' => 65000],
            ['id' => 4, 'trip_id' => 'TR-2025-07-15-004', 'date' => '2025-07-15', 'deliveryman' => ['id' => 4, 'name' => 'Zubair Malik'], 'vehicle' => 'Toyota Hilux – JKL-012', 'market_area' => 'Liaquatabad', 'source_dlf' => 'DLF-2025-07-15-004', 'status' => 'READY', 'load_value' => 112000, 'expected_cash' => 88000],
            ['id' => 5, 'trip_id' => 'TR-2025-07-15-005', 'date' => '2025-07-15', 'deliveryman' => ['id' => 5, 'name' => 'Kashif Hussain'], 'vehicle' => 'Suzuki Carry – MNO-345', 'market_area' => 'Saddar', 'source_dlf' => 'DLF-2025-07-15-005', 'status' => 'SETTLEMENT PENDING', 'load_value' => 64100, 'expected_cash' => 50000],
            ['id' => 6, 'trip_id' => 'TR-2025-07-14-001', 'date' => '2025-07-14', 'deliveryman' => ['id' => 1, 'name' => 'Ahmed Khan'], 'vehicle' => 'Toyota Hilux – ABC-123', 'market_area' => 'Clifton', 'source_dlf' => 'DLF-2025-07-14-001', 'status' => 'SETTLED', 'load_value' => 143000, 'expected_cash' => 110000],
            ['id' => 7, 'trip_id' => 'TR-2025-07-14-002', 'date' => '2025-07-14', 'deliveryman' => ['id' => 2, 'name' => 'Bilal Raza'], 'vehicle' => 'Suzuki Ravi – DEF-456', 'market_area' => 'SITE Area', 'source_dlf' => 'DLF-2025-07-14-002', 'status' => 'CLOSED', 'load_value' => 95000, 'expected_cash' => 72000],
            ['id' => 8, 'trip_id' => 'TR-2025-07-13-001', 'date' => '2025-07-13', 'deliveryman' => ['id' => 3, 'name' => 'Usman Tariq'], 'vehicle' => 'Mazda Truck – GHI-789', 'market_area' => 'Korangi Industrial', 'source_dlf' => 'DLF-2025-07-13-001', 'status' => 'COMPLETED', 'load_value' => 178500, 'expected_cash' => 140000],
            ['id' => 9, 'trip_id' => 'TR-2025-07-13-002', 'date' => '2025-07-13', 'deliveryman' => ['id' => 4, 'name' => 'Zubair Malik'], 'vehicle' => 'Toyota Hilux – JKL-012', 'market_area' => 'Gulshan-e-Iqbal', 'source_dlf' => 'DLF-2025-07-13-002', 'status' => 'SETTLEMENT PENDING', 'load_value' => 92000, 'expected_cash' => 70000],
            ['id' => 10, 'trip_id' => 'TR-2025-07-12-001', 'date' => '2025-07-12', 'deliveryman' => ['id' => 5, 'name' => 'Kashif Hussain'], 'vehicle' => 'Suzuki Carry – MNO-345', 'market_area' => 'North Nazimabad', 'source_dlf' => 'DLF-2025-07-12-001', 'status' => 'COMPLETED', 'load_value' => 67800, 'expected_cash' => 52000],
            ['id' => 11, 'trip_id' => 'TR-2025-07-11-001', 'date' => '2025-07-11', 'deliveryman' => ['id' => 1, 'name' => 'Ahmed Khan'], 'vehicle' => 'Toyota Hilux – ABC-123', 'market_area' => 'Saddar', 'source_dlf' => 'DLF-2025-07-11-001', 'status' => 'COMPLETED', 'load_value' => 88900, 'expected_cash' => 68000],
            ['id' => 12, 'trip_id' => 'TR-2025-07-10-001', 'date' => '2025-07-10', 'deliveryman' => ['id' => 2, 'name' => 'Bilal Raza'], 'vehicle' => 'Suzuki Ravi – DEF-456', 'market_area' => 'Liaquatabad', 'source_dlf' => 'DLF-2025-07-10-001', 'status' => 'DRAFT', 'load_value' => 0, 'expected_cash' => 0],
            ['id' => 13, 'trip_id' => 'TR-2025-07-10-002', 'date' => '2025-07-10', 'deliveryman' => ['id' => 3, 'name' => 'Usman Tariq'], 'vehicle' => 'Mazda Truck – GHI-789', 'market_area' => 'Orangi Town', 'source_dlf' => 'DLF-2025-07-10-002', 'status' => 'DRAFT', 'load_value' => 0, 'expected_cash' => 0],
            ['id' => 14, 'trip_id' => 'TR-2025-07-09-001', 'date' => '2025-07-09', 'deliveryman' => ['id' => 4, 'name' => 'Zubair Malik'], 'vehicle' => 'Toyota Hilux – JKL-012', 'market_area' => 'SITE Area', 'source_dlf' => 'DLF-2025-07-09-001', 'status' => 'READY', 'load_value' => 54000, 'expected_cash' => 42000],
            ['id' => 15, 'trip_id' => 'TR-2025-07-08-001', 'date' => '2025-07-08', 'deliveryman' => ['id' => 5, 'name' => 'Kashif Hussain'], 'vehicle' => 'Suzuki Carry – MNO-345', 'market_area' => 'Clifton', 'source_dlf' => 'DLF-2025-07-08-001', 'status' => 'COMPLETED', 'load_value' => 119000, 'expected_cash' => 91000],
        ];
    }

    private function invoicesForTrip(int $tripId): array
    {
        $all = [
            1 => [
                ['id' => 1, 'invoice_number' => 'INV-001', 'customer' => 'Al-Noor General Store', 'value' => 35000, 'status' => 'DELIVERED'],
                ['id' => 2, 'invoice_number' => 'INV-002', 'customer' => 'City Mart', 'value' => 48000, 'status' => 'PARTIAL'],
                ['id' => 3, 'invoice_number' => 'INV-003', 'customer' => 'Gulshan Traders', 'value' => 42400, 'status' => 'NOT DELIVERED'],
            ],
            2 => [
                ['id' => 4, 'invoice_number' => 'INV-004', 'customer' => 'Hassan Brothers', 'value' => 27000, 'status' => 'DELIVERED'],
                ['id' => 5, 'invoice_number' => 'INV-005', 'customer' => 'Metro Supplies', 'value' => 38500, 'status' => 'DELIVERED'],
                ['id' => 6, 'invoice_number' => 'INV-006', 'customer' => 'Nazimabad Store', 'value' => 33200, 'status' => 'PARTIAL'],
            ],
            3 => [
                ['id' => 7, 'invoice_number' => 'INV-007', 'customer' => 'Pak Traders', 'value' => 29000, 'status' => 'DELIVERED'],
                ['id' => 8, 'invoice_number' => 'INV-008', 'customer' => 'Orangi Mart', 'value' => 31500, 'status' => 'RESERVICE'],
                ['id' => 9, 'invoice_number' => 'INV-009', 'customer' => 'Town Stores', 'value' => 26800, 'status' => 'DELIVERED'],
            ],
        ];
        return $all[$tripId] ?? [
            ['id' => $tripId * 10, 'invoice_number' => 'INV-' . str_pad($tripId * 10, 3, '0', STR_PAD_LEFT), 'customer' => 'Sample Customer', 'value' => 45000, 'status' => 'DELIVERED'],
            ['id' => $tripId * 10 + 1, 'invoice_number' => 'INV-' . str_pad($tripId * 10 + 1, 3, '0', STR_PAD_LEFT), 'customer' => 'Another Customer', 'value' => 32000, 'status' => 'PARTIAL'],
        ];
    }

    public function index(): View
    {
        $filter = request()->get('filter'); // 'open' or null
        $all = $this->trips();

        if ($filter === 'open') {
            $trips = array_values(array_filter($all, fn($t) => in_array($t['status'], ['DRAFT', 'READY', 'DISPATCHED'])));
            $pageTitle = 'Open Trips';
        } else {
            $trips = $all;
            $pageTitle = 'All Trips';
        }

        $deliverymen = $this->deliverymenWithVehicles();
        return view('trips.index', compact('trips', 'filter', 'pageTitle', 'deliverymen'));
    }

    private function deliverymenWithVehicles(): array
    {
        return [
            ['id' => 1, 'name' => 'Ahmed Khan',    'employee_id' => 'EMP-001', 'vehicle' => 'Toyota Hilux – ABC-123',   'area' => 'Gulshan-e-Iqbal'],
            ['id' => 2, 'name' => 'Bilal Raza',    'employee_id' => 'EMP-002', 'vehicle' => 'Suzuki Ravi – DEF-456',    'area' => 'North Nazimabad'],
            ['id' => 3, 'name' => 'Usman Tariq',   'employee_id' => 'EMP-003', 'vehicle' => 'Mazda Truck – GHI-789',   'area' => 'Orangi Town'],
            ['id' => 4, 'name' => 'Zubair Malik',  'employee_id' => 'EMP-004', 'vehicle' => 'Toyota Hilux – JKL-012',  'area' => 'Liaquatabad'],
            ['id' => 5, 'name' => 'Kashif Hussain','employee_id' => 'EMP-005', 'vehicle' => 'Suzuki Carry – MNO-345',  'area' => 'Saddar'],
        ];
    }

    public function show(int $id): View
    {
        $trip = collect($this->trips())->firstWhere('id', $id);
        if (! $trip) abort(404);

        $invoices = $this->invoicesForTrip($id);

        $collections = [
            ['customer' => 'Al-Noor General Store', 'amount' => 35000, 'method' => 'Cash', 'collected_at' => '2025-07-15 14:30'],
            ['customer' => 'City Mart', 'amount' => 22000, 'method' => 'Cheque', 'collected_at' => '2025-07-15 15:10'],
        ];

        $returns = [
            ['sku' => 'BEV-001', 'product' => 'Pepsi 1.5L', 'qty' => 6, 'reason' => 'REFUSED', 'date' => '2025-07-15'],
            ['sku' => 'SNK-003', 'product' => 'Lays Classic 100g', 'qty' => 12, 'reason' => 'DAMAGED', 'date' => '2025-07-15'],
        ];

        $settlement = [
            'expected_cash' => $trip['expected_cash'],
            'collected_amount' => $trip['expected_cash'] - 7500,
            'shortage_amount' => 7500,
            'shortage_classification' => 'Deliveryman Short',
        ];

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Trips', 'route' => route('trips.index')],
            ['label' => $trip['trip_id'], 'route' => null],
        ];

        return view('trips.show', compact('trip', 'invoices', 'collections', 'returns', 'settlement', 'breadcrumbs'));
    }
}
