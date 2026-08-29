<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $kpiCards = [
            ['title' => "Today's Active Trips", 'value' => '8', 'icon' => 'truck', 'color' => 'blue', 'route' => route('trips.index')],
            ['title' => 'Total Sales Value', 'value' => pkr(487500), 'icon' => 'currency', 'color' => 'green', 'route' => route('trips.index')],
            ['title' => 'Total Collections', 'value' => pkr(312000), 'icon' => 'banknotes', 'color' => 'green', 'route' => route('collections.index')],
            ['title' => 'Outstanding Shortages', 'value' => pkr(24500), 'icon' => 'warning', 'color' => 'red', 'route' => route('settlements.index')],
            ['title' => 'Pending Returns', 'value' => '4', 'icon' => 'return', 'color' => 'amber', 'route' => route('returns.index')],
            ['title' => 'Active Deliverymen', 'value' => '5', 'icon' => 'users', 'color' => 'blue', 'route' => route('deliverymen.index')],
            ['title' => 'Stock Alerts', 'value' => '3', 'icon' => 'cube', 'color' => 'amber', 'route' => route('stock.index')],
            ['title' => 'Pending Settlements', 'value' => '2', 'icon' => 'scale', 'color' => 'amber', 'route' => route('settlements.index')],
        ];

        $todaysTrips = [
            ['id' => 1, 'trip_id' => 'TR-2025-07-15-001', 'deliveryman' => 'Ahmed Khan', 'market_area' => 'Gulshan-e-Iqbal', 'status' => 'DISPATCHED', 'load_value' => 125400],
            ['id' => 2, 'trip_id' => 'TR-2025-07-15-002', 'deliveryman' => 'Bilal Raza', 'market_area' => 'North Nazimabad', 'status' => 'COMPLETED', 'load_value' => 98700],
            ['id' => 3, 'trip_id' => 'TR-2025-07-15-003', 'deliveryman' => 'Usman Tariq', 'market_area' => 'Orangi Town', 'status' => 'DISPATCHED', 'load_value' => 87300],
            ['id' => 4, 'trip_id' => 'TR-2025-07-15-004', 'deliveryman' => 'Zubair Malik', 'market_area' => 'Liaquatabad', 'status' => 'READY', 'load_value' => 112000],
            ['id' => 5, 'trip_id' => 'TR-2025-07-15-005', 'deliveryman' => 'Kashif Hussain', 'market_area' => 'Saddar', 'status' => 'SETTLEMENT PENDING', 'load_value' => 64100],
        ];

        $recentCollections = [
            ['customer' => 'Al-Noor General Store', 'amount' => 45000, 'method' => 'Cash', 'trip_id' => 'TR-2025-07-15-002'],
            ['customer' => 'City Mart', 'amount' => 32500, 'method' => 'Cheque', 'trip_id' => 'TR-2025-07-15-002'],
            ['customer' => 'Pak Traders', 'amount' => 18000, 'method' => 'Transfer', 'trip_id' => 'TR-2025-07-14-003'],
            ['customer' => 'Hassan Brothers', 'amount' => 27000, 'method' => 'Cash', 'trip_id' => 'TR-2025-07-14-001'],
            ['customer' => 'Metro Supplies', 'amount' => 51200, 'method' => 'Cash', 'trip_id' => 'TR-2025-07-13-002'],
        ];

        $topShortages = [
            ['deliveryman' => 'Ahmed Khan', 'trip_id' => 'TR-2025-07-12-001', 'amount' => 12500, 'classification' => 'Deliveryman Short'],
            ['deliveryman' => 'Bilal Raza', 'trip_id' => 'TR-2025-07-10-002', 'amount' => 8000, 'classification' => 'Market Short'],
            ['deliveryman' => 'Usman Tariq', 'trip_id' => 'TR-2025-07-09-003', 'amount' => 4000, 'classification' => 'Pending Investigation'],
        ];

        return view('dashboard.index', compact('kpiCards', 'todaysTrips', 'recentCollections', 'topShortages'));
    }
}
