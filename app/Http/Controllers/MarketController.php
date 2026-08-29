<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MarketController extends Controller
{
    private function markets(): array
    {
        return [
            ['id' => 1, 'name' => 'Gulshan-e-Iqbal', 'area' => 'East Karachi', 'contact' => 'Tariq Ahmed', 'phone' => '0213-4567890', 'total_invoices' => 18, 'total_value' => 485000, 'total_collected' => 420000, 'outstanding_balance' => 65000],
            ['id' => 2, 'name' => 'North Nazimabad', 'area' => 'Central Karachi', 'contact' => 'Saleem Baig', 'phone' => '0213-5678901', 'total_invoices' => 15, 'total_value' => 362000, 'total_collected' => 310000, 'outstanding_balance' => 52000],
            ['id' => 3, 'name' => 'Liaquatabad', 'area' => 'Central Karachi', 'contact' => 'Rashid Khan', 'phone' => '0213-6789012', 'total_invoices' => 12, 'total_value' => 298000, 'total_collected' => 270000, 'outstanding_balance' => 28000],
            ['id' => 4, 'name' => 'Orangi Town', 'area' => 'West Karachi', 'contact' => 'Imran Siddiqui', 'phone' => '0213-7890123', 'total_invoices' => 14, 'total_value' => 325000, 'total_collected' => 290000, 'outstanding_balance' => 35000],
            ['id' => 5, 'name' => 'Korangi Industrial', 'area' => 'East Karachi', 'contact' => 'Naeem Chaudhry', 'phone' => '0213-8901234', 'total_invoices' => 20, 'total_value' => 612000, 'total_collected' => 570000, 'outstanding_balance' => 42000],
            ['id' => 6, 'name' => 'SITE Area', 'area' => 'West Karachi', 'contact' => 'Wasim Akram', 'phone' => '0213-9012345', 'total_invoices' => 11, 'total_value' => 278000, 'total_collected' => 255000, 'outstanding_balance' => 23000],
            ['id' => 7, 'name' => 'Saddar', 'area' => 'Central Karachi', 'contact' => 'Arif Hussain', 'phone' => '0213-0123456', 'total_invoices' => 16, 'total_value' => 392000, 'total_collected' => 360000, 'outstanding_balance' => 32000],
            ['id' => 8, 'name' => 'Clifton', 'area' => 'South Karachi', 'contact' => 'Faisal Sheikh', 'phone' => '0213-1234567', 'total_invoices' => 9, 'total_value' => 445000, 'total_collected' => 420000, 'outstanding_balance' => 25000],
        ];
    }

    public function index(): View
    {
        $markets = $this->markets();
        return view('markets.index', compact('markets'));
    }

    public function show(int $id): View
    {
        $market = collect($this->markets())->firstWhere('id', $id);
        if (! $market) abort(404);

        $invoices = [
            ['id' => 1, 'invoice_number' => 'INV-001', 'date' => '2025-07-15', 'trip_id' => 'TR-2025-07-15-001', 'trip_db_id' => 1, 'value' => 35000, 'collected' => 35000, 'status' => 'DELIVERED'],
            ['id' => 2, 'invoice_number' => 'INV-002', 'date' => '2025-07-15', 'trip_id' => 'TR-2025-07-15-001', 'trip_db_id' => 1, 'value' => 48000, 'collected' => 22000, 'status' => 'PARTIAL'],
            ['id' => 4, 'invoice_number' => 'INV-010', 'date' => '2025-07-13', 'trip_id' => 'TR-2025-07-13-002', 'trip_db_id' => 9, 'value' => 42000, 'collected' => 42000, 'status' => 'DELIVERED'],
            ['id' => 5, 'invoice_number' => 'INV-015', 'date' => '2025-07-11', 'trip_id' => 'TR-2025-07-11-001', 'trip_db_id' => 11, 'value' => 38500, 'collected' => 30000, 'status' => 'PARTIAL'],
        ];

        $ledgerEntries = [
            ['date' => '2025-07-15', 'reference' => 'INV-001', 'type' => 'Sale', 'debit' => 35000, 'credit' => 0, 'balance' => 35000],
            ['date' => '2025-07-15', 'reference' => 'TR-2025-07-15-001', 'type' => 'Collection', 'debit' => 0, 'credit' => 35000, 'balance' => 0],
            ['date' => '2025-07-15', 'reference' => 'INV-002', 'type' => 'Sale', 'debit' => 48000, 'credit' => 0, 'balance' => 48000],
            ['date' => '2025-07-15', 'reference' => 'TR-2025-07-15-001', 'type' => 'Partial Payment', 'debit' => 0, 'credit' => 22000, 'balance' => 26000],
        ];

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Markets', 'route' => route('markets.index')],
            ['label' => $market['name'], 'route' => null],
        ];

        return view('markets.show', compact('market', 'invoices', 'ledgerEntries', 'breadcrumbs'));
    }
}
