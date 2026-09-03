@extends('layouts.app')
@php $pageTitle = $account['bank']; @endphp

@section('content')
<div x-data="{ transactionOpen: false }" @keydown.escape.window="transactionOpen = false">
<div class="mb-5 flex flex-wrap items-center justify-between gap-4">
    <div><a href="{{ route('banks.index') }}" class="text-sm font-bold text-blue-600">&larr; Back to Banks</a><h2 class="mt-2 text-2xl font-black text-slate-900">{{ $account['bank'] }}</h2><p class="mt-1 text-sm text-slate-500">Bank account detail and transaction ledger</p></div>
    <button type="button" @click="transactionOpen = true" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-bold text-white">+ New Transaction</button>
</div>

<div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach([['Account Number',$account['account']],['Account Type',$account['type']],['Branch / Location',$account['branch']],['Account Status',$account['status']]] as [$label,$value])
    <div class="rounded-xl border border-slate-200 bg-white p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $label }}</p><p class="mt-2 text-sm font-black text-slate-800">{{ $value }}</p></div>
    @endforeach
</div>

<div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-blue-50 p-5"><p class="text-xs font-bold uppercase tracking-wide text-blue-700">Opening Balance</p><p class="mt-2 text-2xl font-black text-blue-800">{{ pkr($account['opening']) }}</p></div>
    <div class="rounded-xl border border-slate-200 bg-emerald-50 p-5"><p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Current Balance</p><p class="mt-2 text-2xl font-black text-emerald-800">{{ pkr($account['current']) }}</p></div>
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-500">Last Updated</p><p class="mt-2 text-lg font-black text-slate-800">{{ $account['updated'] }}</p></div>
</div>

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
    <div class="border-b border-slate-100 px-6 py-5"><h3 class="text-lg font-black text-slate-900">{{ $account['bank'] }} Transactions</h3><p class="mt-1 text-xs text-slate-500">Account-specific deposits, withdrawals, and running balance.</p></div>
    <div class="overflow-x-auto"><table class="w-full min-w-[60rem] text-sm"><thead><tr class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-400"><th class="px-5 py-3">Date</th><th class="px-5 py-3">Transaction Type</th><th class="px-5 py-3">Category</th><th class="px-5 py-3">Reference</th><th class="px-5 py-3">Description</th><th class="px-5 py-3 text-right">Amount (PKR)</th><th class="px-5 py-3 text-right">Running Balance</th></tr></thead><tbody class="divide-y divide-slate-100">@php $running = $account['opening']; @endphp @foreach($transactions as $transaction) @php $running += $transaction['type'] === 'Deposit / Credit' ? $transaction['amount'] : -$transaction['amount']; @endphp<tr><td class="px-5 py-3.5">{{ $transaction['date'] }}</td><td class="px-5 py-3.5 font-semibold">{{ $transaction['type'] }}</td><td class="px-5 py-3.5">{{ $transaction['category'] }}</td><td class="px-5 py-3.5 font-mono text-xs">{{ $transaction['reference'] }}</td><td class="px-5 py-3.5 text-slate-600">{{ $transaction['description'] }}</td><td class="px-5 py-3.5 text-right font-bold">{{ pkr($transaction['amount']) }}</td><td class="px-5 py-3.5 text-right font-black text-blue-700">{{ pkr($running) }}</td></tr>@endforeach</tbody></table></div>
</section>
<div x-show="transactionOpen" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" @click.self="transactionOpen = false">
    <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between"><div><h3 class="text-lg font-black text-slate-900">New Transaction: {{ $account['bank'] }}</h3><p class="mt-1 text-xs text-slate-500">Add a deposit or withdrawal to this account ledger.</p></div><button type="button" @click="transactionOpen = false" class="text-xl text-slate-400">&times;</button></div>
        <form class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="transactionOpen = false">
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Date *</label><input type="date" required class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Transaction Type *</label><select required class="w-full rounded-lg border-slate-200 text-sm"><option>Deposit / Credit</option><option>Withdrawal / Debit</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Category *</label><select required class="w-full rounded-lg border-slate-200 text-sm"><option>Primary Lifting</option><option>Retail Collection</option><option>Wholesale Collection</option><option>Pending Claim</option><option>Expenses</option><option>Transfer</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Reference / Cheque No.</label><input placeholder="e.g. DEP-260903" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Amount (PKR) *</label><input type="number" min="0.01" step="0.01" required placeholder="0.00" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs font-bold text-slate-600">Description / Notes</label><textarea rows="3" placeholder="Transaction details for audit trail" class="w-full rounded-lg border-slate-200 text-sm"></textarea></div>
            <div class="flex justify-end gap-2 md:col-span-2"><button type="button" @click="transactionOpen = false" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-600">Cancel</button><button class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white">Save Transaction</button></div>
        </form>
    </div>
</div>
</div>
@endsection
