<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $kpiCards = [
            ['title' => 'Total Investment', 'value' => pkr(24905962.43), 'icon' => 'currency', 'color' => 'blue', 'route' => route('banks.index')],
            ['title' => 'Bank Balance', 'value' => pkr(15328288), 'icon' => 'banknotes', 'color' => 'green', 'route' => route('banks.index')],
            ['title' => 'Distribution Credit (Cr)', 'value' => pkr(9577674.43), 'icon' => 'currency', 'color' => 'amber', 'route' => route('ledgers.index')],
            ['title' => 'Cash In Hand', 'value' => pkr(0), 'icon' => 'banknotes', 'color' => 'blue', 'route' => route('banks.index')],
            ['title' => 'Stock In Cash', 'value' => pkr(0), 'icon' => 'cube', 'color' => 'green', 'route' => route('stock.index')],
            ['title' => 'Market Credit (Cr)', 'value' => pkr(0), 'icon' => 'currency', 'color' => 'red', 'route' => route('collections.index')],
            ['title' => 'Pending Claims', 'value' => pkr(0), 'icon' => 'warning', 'color' => 'amber', 'route' => route('settlements.index')],
            ['title' => 'Total Stock Summary', 'value' => pkr(0), 'icon' => 'cube', 'color' => 'blue', 'route' => route('stock.index')],
        ];

        $todaysTrips = [
            ['id' => 1, 'route_id' => 'TR-001', 'deliveryman' => 'Ahmed Khan', 'deliveryman_id' => 1, 'distributor' => 'AAA Traders', 'market_area' => 'Gulshan-e-Iqbal', 'date' => '02-09-2026'],
            ['id' => 2, 'route_id' => 'TR-002', 'deliveryman' => 'Bilal Raza', 'deliveryman_id' => 2, 'distributor' => 'Main Distributor', 'market_area' => 'North Nazimabad', 'date' => '02-09-2026'],
            ['id' => 3, 'route_id' => 'TR-003', 'deliveryman' => 'Usman Tariq', 'deliveryman_id' => 3, 'distributor' => 'AAA Traders', 'market_area' => 'Orangi Town', 'date' => '02-09-2026'],
            ['id' => 4, 'route_id' => 'TR-004', 'deliveryman' => 'Zubair Malik', 'deliveryman_id' => 4, 'distributor' => 'Main Distributor', 'market_area' => 'Liaquatabad', 'date' => '02-09-2026'],
            ['id' => 5, 'route_id' => 'TR-005', 'deliveryman' => 'Kashif Hussain', 'deliveryman_id' => 5, 'distributor' => 'AAA Traders', 'market_area' => 'Saddar', 'date' => '02-09-2026'],
        ];

        $recentCollections = [
            ['customer' => 'Al-Noor General Store', 'amount' => 45000, 'method' => 'Cash', 'trip_id' => 'TR-2025-07-15-002'],
            ['customer' => 'City Mart', 'amount' => 32500, 'method' => 'Cheque', 'trip_id' => 'TR-2025-07-15-002'],
            ['customer' => 'Pak Traders', 'amount' => 18000, 'method' => 'Transfer', 'trip_id' => 'TR-2025-07-14-003'],
            ['customer' => 'Hassan Brothers', 'amount' => 27000, 'method' => 'Cash', 'trip_id' => 'TR-2025-07-14-001'],
            ['customer' => 'Metro Supplies', 'amount' => 51200, 'method' => 'Cash', 'trip_id' => 'TR-2025-07-13-002'],
        ];

        $topShortages = [
            ['id' => 1, 'deliveryman' => 'Ahmed Khan', 'deliveryman_id' => 1, 'trip_id' => 'TR-2025-07-12-001', 'market_area' => 'Gulshan-e-Iqbal', 'amount' => 12500, 'recovery_status' => 'Pending'],
            ['id' => 2, 'deliveryman' => 'Bilal Raza', 'deliveryman_id' => 2, 'trip_id' => 'TR-2025-07-10-002', 'market_area' => 'North Nazimabad', 'amount' => 8000, 'recovery_status' => 'Recovered'],
            ['id' => 3, 'deliveryman' => 'Usman Tariq', 'deliveryman_id' => 3, 'trip_id' => 'TR-2025-07-09-003', 'market_area' => 'Orangi Town', 'amount' => 4000, 'recovery_status' => 'Deducted'],
        ];

        return view('dashboard.index', compact('kpiCards', 'todaysTrips', 'recentCollections', 'topShortages'));
    }
}
