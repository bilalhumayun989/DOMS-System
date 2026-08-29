<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        $methodFilter = $request->query('method', 'All');
        $showAll = $request->boolean('show_all_methods', false);

        $collections = [
            ['id' => 1, 'collection_ref' => 'COL-001', 'date' => '2025-07-15', 'customer' => 'Al-Noor General Store', 'market_id' => 1, 'invoice_number' => 'INV-001', 'invoice_id' => 1, 'trip_id' => 1, 'trip_display' => 'TR-2025-07-15-001', 'amount' => 35000, 'method' => 'Cash', 'deliveryman' => 'Ahmed Khan'],
            ['id' => 2, 'collection_ref' => 'COL-002', 'date' => '2025-07-15', 'customer' => 'City Mart', 'market_id' => 1, 'invoice_number' => 'INV-002', 'invoice_id' => 2, 'trip_id' => 1, 'trip_display' => 'TR-2025-07-15-001', 'amount' => 22000, 'method' => 'Cheque', 'deliveryman' => 'Ahmed Khan'],
            ['id' => 3, 'collection_ref' => 'COL-003', 'date' => '2025-07-15', 'customer' => 'Hassan Brothers', 'market_id' => 2, 'invoice_number' => 'INV-004', 'invoice_id' => 4, 'trip_id' => 2, 'trip_display' => 'TR-2025-07-15-002', 'amount' => 27000, 'method' => 'Cash', 'deliveryman' => 'Bilal Raza'],
            ['id' => 4, 'collection_ref' => 'COL-004', 'date' => '2025-07-15', 'customer' => 'Metro Supplies', 'market_id' => 2, 'invoice_number' => 'INV-005', 'invoice_id' => 5, 'trip_id' => 2, 'trip_display' => 'TR-2025-07-15-002', 'amount' => 38500, 'method' => 'Transfer', 'deliveryman' => 'Bilal Raza'],
            ['id' => 5, 'collection_ref' => 'COL-005', 'date' => '2025-07-15', 'customer' => 'Nazimabad Store', 'market_id' => 2, 'invoice_number' => 'INV-006', 'invoice_id' => 6, 'trip_id' => 2, 'trip_display' => 'TR-2025-07-15-002', 'amount' => 15000, 'method' => 'Cash', 'deliveryman' => 'Bilal Raza'],
            ['id' => 6, 'collection_ref' => 'COL-006', 'date' => '2025-07-15', 'customer' => 'Pak Traders', 'market_id' => 4, 'invoice_number' => 'INV-007', 'invoice_id' => 7, 'trip_id' => 3, 'trip_display' => 'TR-2025-07-15-003', 'amount' => 29000, 'method' => 'Cash', 'deliveryman' => 'Usman Tariq'],
            ['id' => 7, 'collection_ref' => 'COL-007', 'date' => '2025-07-14', 'customer' => 'Clifton Mart', 'market_id' => 8, 'invoice_number' => 'INV-010', 'invoice_id' => 10, 'trip_id' => 6, 'trip_display' => 'TR-2025-07-14-001', 'amount' => 55000, 'method' => 'Cheque', 'deliveryman' => 'Ahmed Khan'],
            ['id' => 8, 'collection_ref' => 'COL-008', 'date' => '2025-07-14', 'customer' => 'Sea View Stores', 'market_id' => 8, 'invoice_number' => 'INV-011', 'invoice_id' => 11, 'trip_id' => 6, 'trip_display' => 'TR-2025-07-14-001', 'amount' => 48000, 'method' => 'Cash', 'deliveryman' => 'Ahmed Khan'],
            ['id' => 9, 'collection_ref' => 'COL-009', 'date' => '2025-07-14', 'customer' => 'SITE Traders', 'market_id' => 6, 'invoice_number' => 'INV-012', 'invoice_id' => 12, 'trip_id' => 7, 'trip_display' => 'TR-2025-07-14-002', 'amount' => 40000, 'method' => 'Transfer', 'deliveryman' => 'Bilal Raza'],
            ['id' => 10, 'collection_ref' => 'COL-010', 'date' => '2025-07-13', 'customer' => 'Korangi Supplies', 'market_id' => 5, 'invoice_number' => 'INV-014', 'invoice_id' => 14, 'trip_id' => 8, 'trip_display' => 'TR-2025-07-13-001', 'amount' => 72000, 'method' => 'Cash', 'deliveryman' => 'Usman Tariq'],
        ];

        if (! $showAll && $methodFilter !== 'All') {
            $collections = array_values(array_filter($collections, fn($c) => $c['method'] === $methodFilter));
        }

        $dailyTotal = array_sum(array_column($collections, 'amount'));

        return view('collections.index', compact('collections', 'methodFilter', 'dailyTotal'));
    }
}
