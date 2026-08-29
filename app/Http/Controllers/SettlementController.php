<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SettlementController extends Controller
{
    public function index(): View
    {
        $settlements = [
            ['id' => 1, 'settlement_ref' => 'SET-001', 'trip_id' => 6, 'trip_display' => 'TR-2025-07-14-001', 'deliveryman' => 'Ahmed Khan', 'date' => '2025-07-14', 'expected_cash' => 110000, 'collected_amount' => 103000, 'shortage_amount' => 7000, 'shortage_classification' => 'Deliveryman Short', 'status' => 'Settled'],
            ['id' => 2, 'settlement_ref' => 'SET-002', 'trip_id' => 7, 'trip_display' => 'TR-2025-07-14-002', 'deliveryman' => 'Bilal Raza', 'date' => '2025-07-14', 'expected_cash' => 72000, 'collected_amount' => 72000, 'shortage_amount' => 0, 'shortage_classification' => null, 'status' => 'Closed'],
            ['id' => 3, 'settlement_ref' => 'SET-003', 'trip_id' => 5, 'trip_display' => 'TR-2025-07-15-005', 'deliveryman' => 'Kashif Hussain', 'date' => '2025-07-15', 'expected_cash' => 50000, 'collected_amount' => 42000, 'shortage_amount' => 8000, 'shortage_classification' => 'Market Short', 'status' => 'Pending'],
            ['id' => 4, 'settlement_ref' => 'SET-004', 'trip_id' => 9, 'trip_display' => 'TR-2025-07-13-002', 'deliveryman' => 'Zubair Malik', 'date' => '2025-07-13', 'expected_cash' => 70000, 'collected_amount' => 66500, 'shortage_amount' => 3500, 'shortage_classification' => 'Pending Investigation', 'status' => 'Pending'],
        ];

        $totals = [
            'expected_cash' => array_sum(array_column($settlements, 'expected_cash')),
            'collected_amount' => array_sum(array_column($settlements, 'collected_amount')),
            'shortage_amount' => array_sum(array_column($settlements, 'shortage_amount')),
        ];

        return view('settlements.index', compact('settlements', 'totals'));
    }
}
