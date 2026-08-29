<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LedgerController extends Controller
{
    public function index(): View
    {
        $marketLedgers = [
            ['id' => 1, 'name' => 'Al-Noor General Store', 'market' => 'Gulshan-e-Iqbal', 'market_id' => 1, 'total_debit' => 83000, 'total_credit' => 57000, 'balance' => 26000],
            ['id' => 2, 'name' => 'Hassan Brothers', 'market' => 'North Nazimabad', 'market_id' => 2, 'total_debit' => 65500, 'total_credit' => 56200, 'balance' => 9300],
            ['id' => 3, 'name' => 'Clifton Mart', 'market' => 'Clifton', 'market_id' => 8, 'total_debit' => 103000, 'total_credit' => 98000, 'balance' => 5000],
        ];

        $deliverymanLedgers = [
            ['id' => 1, 'name' => 'Ahmed Khan', 'employee_id' => 'EMP-001', 'deliveryman_id' => 1, 'total_debit' => 268400, 'total_credit' => 255900, 'balance' => 12500],
            ['id' => 2, 'name' => 'Bilal Raza', 'employee_id' => 'EMP-002', 'deliveryman_id' => 2, 'total_debit' => 193700, 'total_credit' => 185700, 'balance' => 8000],
            ['id' => 3, 'name' => 'Usman Tariq', 'employee_id' => 'EMP-003', 'deliveryman_id' => 3, 'total_debit' => 178500, 'total_credit' => 174500, 'balance' => 4000],
        ];

        return view('ledgers.index', compact('marketLedgers', 'deliverymanLedgers'));
    }
}
