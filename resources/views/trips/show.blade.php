@extends('layouts.app')
@php $pageTitle = $trip['trip_id']; @endphp

@section('content')
@if(session('success'))
<div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
    <p class="font-bold">Please fix this:</p>
    <ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-3">
                <span class="font-mono text-xl font-bold text-slate-900">{{ $trip['trip_id'] }}</span>
                <x-status-badge :status="$trip['status']"/>
            </div>
            <p class="text-sm text-slate-500">
                <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}" class="font-semibold text-slate-700 hover:text-blue-600">{{ $trip['deliveryman']['name'] }}</a>
                · {{ $trip['market_area'] }} · {{ $trip['vehicle'] }}
            </p>
            <p class="mt-1 text-xs text-slate-400">DLF: {{ $trip['source_dlf'] ?: 'Not provided' }} · {{ $trip['date'] }}</p>
        </div>
        <a href="{{ route('trips.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">Back to Trips</a>
    </div>
</div>

<div class="mb-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
    @php
        $tripCollected = (float) ($trip['collected_amount'] ?? 0);
        $tripExpenseTotal = (float) ($trip['expense_amount'] ?? 0);
        $tripExpected = (float) ($trip['expected_cash'] ?? 0);
        $tripShortage = $tripExpected - $tripCollected - $tripExpenseTotal;
    @endphp

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Load Value</p>
        <p class="mt-2 text-xl font-bold text-slate-900">{{ pkr($trip['load_value'] ?? 0) }}</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Expected Cash</p>
        <p class="mt-2 text-xl font-bold text-slate-900">{{ pkr($tripExpected) }}</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Collected</p>
        <p class="mt-2 text-xl font-bold text-emerald-600">{{ pkr($tripCollected) }}</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Expenses</p>
        <p class="mt-2 text-xl font-bold text-amber-600">{{ pkr($tripExpenseTotal) }}</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Shortage / Excess</p>
        <p class="mt-2 text-xl font-bold {{ $tripShortage >= 0 ? 'text-red-600' : 'text-green-600' }}">{{ pkr(abs($tripShortage)) }}</p>
    </div>
</div>

<div class="grid gap-5 xl:grid-cols-2">
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-black text-slate-900">Trip Details</h3>
        <div class="grid gap-4 text-sm md:grid-cols-2">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Deliveryman</p>
                <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}" class="mt-1 inline-block font-semibold text-slate-800 hover:text-blue-600">{{ $trip['deliveryman']['name'] }}</a>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Vehicle</p>
                <p class="mt-1 font-semibold text-slate-800">{{ $trip['vehicle'] }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Market / Area</p>
                <p class="mt-1 font-semibold text-slate-800">{{ $trip['market_area'] }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Distributor</p>
                <p class="mt-1 font-semibold text-slate-800">{{ $trip['distributor'] ?? 'Main Distributor' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Trip Date</p>
                <p class="mt-1 font-semibold text-slate-800">{{ $trip['date'] }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Source DLF</p>
                <p class="mt-1 font-semibold text-slate-800">{{ $trip['source_dlf'] ?: 'Not provided' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Delivery Notes</p>
                <p class="mt-1 text-slate-700">{{ $trip['delivery_notes'] ?: 'No delivery notes recorded.' }}</p>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-black text-slate-900">Collection Summary</h3>
        @if(!empty($collections))
            <div class="space-y-3">
                @foreach($collections as $collection)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $collection['customer'] }}</p>
                                <p class="text-xs text-slate-500">{{ $collection['invoice_number'] }} · {{ $collection['method'] }}</p>
                            </div>
                            <span class="text-sm font-bold text-emerald-600">{{ pkr($collection['amount']) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500">No collections have been recorded for this trip yet.</p>
        @endif
    </section>
</div>

<section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between gap-3">
        <h3 class="text-lg font-black text-slate-900">Collections</h3>
        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">{{ count($collections ?? []) }} records</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Invoice</th>
                    <th class="px-4 py-3">Method</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($collections ?? [] as $collection)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $collection['customer'] }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $collection['invoice_number'] }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $collection['method'] }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ pkr($collection['amount']) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $collection['collected_at'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-5 text-center text-sm text-slate-500">No collections entered.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between gap-3">
        <h3 class="text-lg font-black text-slate-900">Expenses</h3>
        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">{{ count($expenses ?? []) }} records</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($expenses ?? [] as $expense)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $expense['category'] }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $expense['description'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-right font-bold text-amber-600">{{ pkr($expense['amount']) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $expense['expense_date'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-5 text-center text-sm text-slate-500">No expenses entered.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>


@endsection
