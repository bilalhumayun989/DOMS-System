@extends('layouts.app')
@php $pageTitle = 'Daily Driver & Trip Settlements'; @endphp

@section('content')
<div x-data="{ open: false, selected: null, showSettlement(item) { window.location.href = '/settlements/' + item.id; }, close() { this.open = false; } }" x-effect="document.body.style.overflow = open ? 'hidden' : ''" @keydown.escape.window="close()">
    <div class="mb-5 flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-widest text-gray-800">End-of-Day Reconciliation</p><h2 class="mt-1 text-2xl font-black text-slate-900">Daily Driver &amp; Trip Settlements</h2><p class="mt-1 text-sm text-slate-500">End-of-Day Reconciliation for Deliverymen, Stock, Cash Collections, and Shortages</p></div><a href="{{ route('settlements.create') }}" class="btn-primary">+ Add Settlement</a></div>
    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">@foreach([['Total Settlements Today',$totalSettlements,'#111111','#efefef'],['Total Net Cash Collected',$totalCash,'#111111','#f0f0f0'],['Total Market Credit Issued (Udhaar)',$totalCredit,'#444444','#ececec'],['Total Pending Shortages Today',$totalShortage,'#111111','#e8e8e8']] as [$label,$value,$color,$background])<div class="min-h-32 rounded-xl border border-slate-200 p-5 shadow-sm" style="background:{{ $background }}"><p class="text-xs font-bold uppercase tracking-wide" style="color:{{ $color }};opacity:.75">{{ $label }}</p><p class="mt-2 text-2xl font-black" style="color:{{ $color }}">{{ pkr($value) }}</p></div>@endforeach</div>

    <section class="page-card">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Settlements Main Table</h3>
                <p class="page-card-sub">Trip clearing, stock reconciliation, and cash drawer handover.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full doms-table min-w-[90rem]">
                <thead>
                    <tr>
                        <th class="text-left">Settlement ID / Slip No</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Trip / Route ID</th>
                        <th class="text-left">Deliveryman / Driver Name</th>
                        <th class="text-left">Market / Area</th>
                        <th class="text-left">Distributor Name</th>
                        <th class="text-right">Net Sales Value (PKR)</th>
                        <th class="text-right">Expected Cash (PKR)</th>
                        <th class="text-right">Actual Submitted Cash (PKR)</th>
                        <th class="text-right">Shortage / Excess (PKR)</th>
                        <th class="text-left">Settlement Status</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settlements as $settlement)
                    <tr class="">
                        <td class="font-mono text-xs font-black text-gray-900">{{ $settlement['settlement_ref'] }}</td>
                        <td class="whitespace-nowrap">{{ $settlement['date'] }}</td>
                        <td class="font-mono text-xs">{{ $settlement['trip_display'] }}</td>
                        <td class="font-semibold">{{ $settlement['deliveryman'] }}</td>
                        <td>{{ $settlement['market'] }}</td>
                        <td>{{ $settlement['distributor'] }}</td>
                        <td class="text-right font-bold">{{ pkr($settlement['net_sales']) }}</td>
                        <td class="text-right">{{ pkr($settlement['expected_cash']) }}</td>
                        <td class="text-right font-bold text-gray-700">{{ pkr($settlement['actual_cash']) }}</td>
                        <td class="text-right font-bold {{ $settlement['shortage'] > 0 ? 'text-gray-900' : 'text-slate-400' }}">{{ $settlement['shortage'] > 0 ? pkr($settlement['shortage']) : 'PKR 0.00' }}</td>
                        <td><x-status-badge :status="$settlement['status']" /></td>
                        <td>
                            <button type="button" @click="showSettlement({{ json_encode($settlement) }})" class="btn-row btn-view whitespace-nowrap">View Settlement Details</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @if(false)<div x-show="open"></div>@endif
</div>
@endsection
