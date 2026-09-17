<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BankController extends Controller
{
    public function index(): View
    {
        $accounts = BankAccount::with('transactions')->get()->map(fn (BankAccount $bank): array => $this->accountData($bank))->all();
        $transactions = $this->transactions();
        $monthly = collect($transactions)->filter(fn (array $row): bool => substr($row['date'], 0, 7) === now()->format('Y-m'));
        $totalDeposits = $monthly->where('type', 'Deposit / Credit')->sum('amount');
        $totalWithdrawals = $monthly->where('type', 'Withdrawal / Debit')->sum('amount');
        $combinedBalance = collect($accounts)->sum('current');
        $cashBalance = collect($accounts)->where('type', 'Cash Account')->sum('current');

        return view('banks.index', compact('accounts', 'transactions', 'totalDeposits', 'totalWithdrawals', 'combinedBalance', 'cashBalance'));
    }

    public function show(BankAccount $bank): View
    {
        return view('banks.show', ['account' => $this->accountData($bank), 'transactions' => $this->transactions($bank->id)]);
    }

    public function store(Request $request): RedirectResponse
    {
        BankAccount::create($request->validate([
            'bank' => ['required', 'string', 'max:150'],
            'account' => ['required', 'string', 'max:100', 'unique:bank_accounts,account'],
            'opening' => ['required', 'numeric', 'min:0', 'max:999999999999'],
            'opening_date' => ['required', 'date'],
            'type' => ['required', Rule::in(['Business Current', 'Business Savings', 'Cash Account'])],
            'branch' => ['required', 'string', 'max:150'],
            'status' => ['required', Rule::in(['Active', 'Inactive'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]));

        return to_route('banks.index')->with('success', 'Bank account created.');
    }

    public function storeTransaction(Request $request): RedirectResponse
    {
        BankTransaction::create($this->validateTransaction($request));

        return to_route('banks.index')->with('success', 'Transaction saved.');
    }

    public function updateTransaction(Request $request, BankTransaction $transaction): RedirectResponse
    {
        $transaction->update($this->validateTransaction($request, $transaction));

        return to_route('banks.index')->with('success', 'Transaction updated.');
    }

    public function destroyTransaction(BankTransaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return to_route('banks.index')->with('success', 'Transaction deleted. Balances recalculated.');
    }

    private function validateTransaction(Request $request, ?BankTransaction $transaction = null): array
    {
        $data = $request->validate([
            'bank_id' => ['required', 'integer', 'exists:bank_accounts,id'],
            'date' => ['required', 'date'],
            'type' => ['required', Rule::in(['Deposit / Credit', 'Withdrawal / Debit'])],
            'category' => ['required', Rule::in(['Primary Lifting', 'Retail Collection', 'Wholesale Collection', 'Pending Claim', 'Expenses', 'Transfer'])],
            'reference' => ['required', 'string', 'max:100', Rule::unique('bank_transactions')->ignore($transaction?->id)],
            'description' => ['nullable', 'string', 'max:5000'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999999'],
        ]);
        $bank = BankAccount::findOrFail($data['bank_id']);
        if ($bank->status !== 'Active' || $bank->opening_date->gt($data['date'])) {
            throw ValidationException::withMessages(['bank_id' => 'Choose an active account and a date on or after its opening date.']);
        }

        return $data;
    }

    private function accountData(BankAccount $bank): array
    {
        $current = $bank->opening + $bank->transactions->sum(fn (BankTransaction $row): float => $row->type === 'Deposit / Credit' ? $row->amount : -$row->amount);

        return [...$bank->toArray(), 'current' => round($current, 2), 'updated' => $bank->transactions->max('date')?->format('d-M-Y') ?? $bank->opening_date->format('d-M-Y')];
    }

    private function transactions(?int $bankId = null): array
    {
        $balances = BankAccount::pluck('opening', 'id')->all();

        return BankTransaction::with('bank')->when($bankId, fn ($query) => $query->where('bank_id', $bankId))->orderBy('date')->orderBy('id')->get()->map(function (BankTransaction $row) use (&$balances): array {
            $balances[$row->bank_id] += $row->type === 'Deposit / Credit' ? $row->amount : -$row->amount;

            return [...$row->toArray(), 'date' => $row->date->toDateString(), 'bank' => $row->bank->bank, 'account' => $row->bank->account, 'running_balance' => round($balances[$row->bank_id], 2)];
        })->all();
    }
}
