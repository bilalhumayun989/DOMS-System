<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('status', 'All');

        $returns = [
            ['id' => 1, 'return_ref' => 'RET-001', 'date' => '2025-07-15', 'trip_id' => 1, 'trip_display' => 'TR-2025-07-15-001', 'deliveryman' => 'Ahmed Khan', 'sku' => 'BEV-001', 'product' => 'Pepsi 1.5L', 'qty_returned' => 6, 'reason' => 'REFUSED', 'status' => 'Pending'],
            ['id' => 2, 'return_ref' => 'RET-002', 'date' => '2025-07-15', 'trip_id' => 1, 'trip_display' => 'TR-2025-07-15-001', 'deliveryman' => 'Ahmed Khan', 'sku' => 'SNK-003', 'product' => 'Biscuits Marie 150g', 'qty_returned' => 12, 'reason' => 'DAMAGED', 'status' => 'Restocked'],
            ['id' => 3, 'return_ref' => 'RET-003', 'date' => '2025-07-14', 'trip_id' => 2, 'trip_display' => 'TR-2025-07-15-002', 'deliveryman' => 'Bilal Raza', 'sku' => 'BEV-004', 'product' => 'Sprite 1.5L', 'qty_returned' => 24, 'reason' => 'EXPIRED', 'status' => 'Restocked'],
            ['id' => 4, 'return_ref' => 'RET-004', 'date' => '2025-07-14', 'trip_id' => 6, 'trip_display' => 'TR-2025-07-14-001', 'deliveryman' => 'Ahmed Khan', 'sku' => 'HH-001', 'product' => 'Surf Excel 500g', 'qty_returned' => 8, 'reason' => 'REFUSED', 'status' => 'Restocked'],
            ['id' => 5, 'return_ref' => 'RET-005', 'date' => '2025-07-14', 'trip_id' => 7, 'trip_display' => 'TR-2025-07-14-002', 'deliveryman' => 'Bilal Raza', 'sku' => 'SNK-001', 'product' => 'Lays Classic 100g', 'qty_returned' => 18, 'reason' => 'EXCESS', 'status' => 'Pending'],
            ['id' => 6, 'return_ref' => 'RET-006', 'date' => '2025-07-13', 'trip_id' => 8, 'trip_display' => 'TR-2025-07-13-001', 'deliveryman' => 'Usman Tariq', 'sku' => 'BEV-002', 'product' => 'Coca-Cola 1.5L', 'qty_returned' => 12, 'reason' => 'DAMAGED', 'status' => 'Restocked'],
            ['id' => 7, 'return_ref' => 'RET-007', 'date' => '2025-07-13', 'trip_id' => 9, 'trip_display' => 'TR-2025-07-13-002', 'deliveryman' => 'Zubair Malik', 'sku' => 'SNK-006', 'product' => 'Pringles Original 165g', 'qty_returned' => 6, 'reason' => 'REFUSED', 'status' => 'Pending'],
            ['id' => 8, 'return_ref' => 'RET-008', 'date' => '2025-07-12', 'trip_id' => 10, 'trip_display' => 'TR-2025-07-12-001', 'deliveryman' => 'Kashif Hussain', 'sku' => 'HH-005', 'product' => 'Head & Shoulders 200ml', 'qty_returned' => 4, 'reason' => 'EXPIRED', 'status' => 'Restocked'],
            ['id' => 9, 'return_ref' => 'RET-009', 'date' => '2025-07-11', 'trip_id' => 11, 'trip_display' => 'TR-2025-07-11-001', 'deliveryman' => 'Ahmed Khan', 'sku' => 'BEV-005', 'product' => 'Fanta 1.5L', 'qty_returned' => 18, 'reason' => 'EXCESS', 'status' => 'Restocked'],
            ['id' => 10, 'return_ref' => 'RET-010', 'date' => '2025-07-11', 'trip_id' => 11, 'trip_display' => 'TR-2025-07-11-001', 'deliveryman' => 'Ahmed Khan', 'sku' => 'SNK-002', 'product' => 'Kurkure 70g', 'qty_returned' => 30, 'reason' => 'REFUSED', 'status' => 'Pending'],
            ['id' => 11, 'return_ref' => 'RET-011', 'date' => '2025-07-10', 'trip_id' => 15, 'trip_display' => 'TR-2025-07-08-001', 'deliveryman' => 'Kashif Hussain', 'sku' => 'HH-002', 'product' => 'Ariel Detergent 1kg', 'qty_returned' => 10, 'reason' => 'DAMAGED', 'status' => 'Restocked'],
            ['id' => 12, 'return_ref' => 'RET-012', 'date' => '2025-07-08', 'trip_id' => 15, 'trip_display' => 'TR-2025-07-08-001', 'deliveryman' => 'Kashif Hussain', 'sku' => 'BEV-003', 'product' => 'Nestle Water 1.5L', 'qty_returned' => 24, 'reason' => 'EXCESS', 'status' => 'Restocked'],
        ];

        if ($filter !== 'All') {
            $returns = array_values(array_filter($returns, fn($r) => $r['status'] === $filter));
        }

        return view('returns.index', compact('returns', 'filter'));
    }
}
