<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BankController extends Controller
{
    public function index(): View
    {
        $accounts = [
            ['id' => 1, 'bank' => 'Bank A', 'account' => 'PK00 AAAA 0001', 'opening' => 12500000, 'current' => 10428288, 'updated' => '02-Sep-2026'],
            ['id' => 2, 'bank' => 'Bank B', 'account' => 'PK00 AAAA 0002', 'opening' => 6500000, 'current' => 4860000, 'updated' => '02-Sep-2026'],
            ['id' => 3, 'bank' => 'Cash in Hand', 'account' => 'CASH-001', 'opening' => 1240000, 'current' => 1240000, 'updated' => '02-Sep-2026'],
        ];
        $transactions = [
            ['bank_id' => 1, 'date' => '02-Sep-2026', 'bank' => 'Bank A', 'account' => 'PK00 AAAA 0001', 'type' => 'Deposit / Credit', 'category' => 'Retail Collection', 'reference' => 'DEP-260902', 'description' => 'Market collections deposit', 'amount' => 850000],
            ['bank_id' => 2, 'date' => '02-Sep-2026', 'bank' => 'Bank B', 'account' => 'PK00 AAAA 0002', 'type' => 'Withdrawal / Debit', 'category' => 'Expenses', 'reference' => 'EXP-260902', 'description' => 'Delivery fleet fuel and loading', 'amount' => 140000],
            ['bank_id' => 1, 'date' => '01-Sep-2026', 'bank' => 'Bank A', 'account' => 'PK00 AAAA 0001', 'type' => 'Deposit / Credit', 'category' => 'Primary Lifting', 'reference' => 'LIFT-260901', 'description' => 'Distributor stock settlement', 'amount' => 1250000],
            ['bank_id' => 3, 'date' => '31-Aug-2026', 'bank' => 'Cash in Hand', 'account' => 'CASH-001', 'type' => 'Deposit / Credit', 'category' => 'Retail Collection', 'reference' => 'CASH-260831', 'description' => 'Cash collection received', 'amount' => 275000],
            ['bank_id' => 2, 'date' => '30-Aug-2026', 'bank' => 'Bank B', 'account' => 'PK00 AAAA 0002', 'type' => 'Withdrawal / Debit', 'category' => 'Transfer', 'reference' => 'TRF-260830', 'description' => 'Transfer to Bank A', 'amount' => 300000],
        ];
        $totalDeposits = collect($transactions)->where('type', 'Deposit / Credit')->sum('amount');
        $totalWithdrawals = collect($transactions)->where('type', 'Withdrawal / Debit')->sum('amount');
        $combinedBalance = collect($accounts)->sum('current');
        $cashBalance = collect($accounts)->firstWhere('bank', 'Cash in Hand')['current'];

        return view('banks.index', compact('accounts', 'transactions', 'totalDeposits', 'totalWithdrawals', 'combinedBalance', 'cashBalance'));
    }

    public function show(int $bank): View
    {
        $accounts = [
            1 => ['bank' => 'Bank A', 'account' => 'PK00 AAAA 0001', 'opening' => 12500000, 'current' => 10428288, 'updated' => '02-Sep-2026', 'type' => 'Business Current', 'branch' => 'Main Boulevard Branch', 'status' => 'Active'],
            2 => ['bank' => 'Bank B', 'account' => 'PK00 AAAA 0002', 'opening' => 6500000, 'current' => 4860000, 'updated' => '02-Sep-2026', 'type' => 'Business Savings', 'branch' => 'Clifton Branch', 'status' => 'Active'],
            3 => ['bank' => 'Cash in Hand', 'account' => 'CASH-001', 'opening' => 1240000, 'current' => 1240000, 'updated' => '02-Sep-2026', 'type' => 'Cash Account', 'branch' => 'AAA Traders Office', 'status' => 'Active'],
        ];
        abort_unless(isset($accounts[$bank]), 404);

        $transactions = match ($bank) {
            1 => [
                ['date' => '02-Sep-2026', 'type' => 'Deposit / Credit', 'category' => 'Retail Collection', 'reference' => 'DEP-260902', 'description' => 'Market collections deposit', 'amount' => 850000],
                ['date' => '01-Sep-2026', 'type' => 'Deposit / Credit', 'category' => 'Primary Lifting', 'reference' => 'LIFT-260901', 'description' => 'Distributor stock settlement', 'amount' => 1250000],
            ],
            2 => [
                ['date' => '02-Sep-2026', 'type' => 'Withdrawal / Debit', 'category' => 'Expenses', 'reference' => 'EXP-260902', 'description' => 'Delivery fleet fuel and loading', 'amount' => 140000],
                ['date' => '30-Aug-2026', 'type' => 'Withdrawal / Debit', 'category' => 'Transfer', 'reference' => 'TRF-260830', 'description' => 'Transfer to Bank A', 'amount' => 300000],
            ],
            3 => [
                ['date' => '31-Aug-2026', 'type' => 'Deposit / Credit', 'category' => 'Retail Collection', 'reference' => 'CASH-260831', 'description' => 'Cash collection received', 'amount' => 275000],
            ],
        };

        return view('banks.show', ['account' => $accounts[$bank], 'transactions' => $transactions]);
    }
}
