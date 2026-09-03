@extends('layouts.app')
@php $pageTitle = $deliveryman['name']; @endphp

@section('content')
{{-- Profile Header --}}
<div class="rounded-2xl p-6 mb-5" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="flex items-center gap-5 mb-5">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-bold flex-shrink-0"
             style="background: linear-gradient(135deg,#3b82f6,#8b5cf6);">
            {{ strtoupper(substr($deliveryman['name'],0,1)) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $deliveryman['name'] }}</h2>
            <div class="flex flex-wrap gap-3 mt-2">
                <span class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#f1f5f9;color:#475569;">🪪 {{ $deliveryman['employee_id'] }}</span>
                <span class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#f1f5f9;color:#475569;">📞 {{ $deliveryman['phone'] }}</span>
                <span class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#f1f5f9;color:#475569;">📅 {{ $deliveryman['joined_at'] }}</span>
            </div>
        </div>
    </div>
    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 pt-5" style="border-top: 1px solid #f1f5f9;">
        @foreach([['Total Trips',$summary['total_trips'],false,'#3b82f6','#eff6ff'],['Value Delivered',$summary['total_value_delivered'],true,'#1d4ed8','#eff6ff'],['Collected',$summary['total_collected'],true,'#15803d','#f0fdf4'],['Shortages',$summary['total_shortages'],true,'#b91c1c','#fff1f2'],['Ledger Balance',$summary['ledger_balance'],true,'#b91c1c','#fff1f2']] as [$lbl,$val,$fmt,$clr,$bg])
        <div class="px-4 py-3 rounded-xl text-center" style="background: {{ $bg }};">
            <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: {{ $clr }}; opacity: 0.7;">{{ $lbl }}</p>
            <p class="text-lg font-bold" style="color: {{ $clr }};">{{ $fmt ? pkr($val) : $val }}</p>
        </div>
        @endforeach
    </div>
</div>

<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="text-lg font-black text-slate-900">Daily Trip Sheets</h2>
        <p class="mt-1 text-sm text-slate-500">Complete stock, market sales, collections, and account adjustment for each trip.</p>
    </div>
    <a href="{{ route('deliverymen.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-xs font-bold text-slate-600">Back to Deliverymen</a>
</div>

<div class="space-y-5">
@foreach($tripHistory as $trip)
<article class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
        <div><a href="{{ route('trips.show', $trip['id']) }}" class="font-mono text-sm font-black text-blue-600">{{ $trip['trip_id'] }}</a><p class="mt-1 text-xs text-slate-500">{{ date('d-M-Y', strtotime($trip['date'])) }} · Day {{ (int) date('d', strtotime($trip['date'])) }}</p></div>
        <x-status-badge :status="$trip['status']" />
    </div>
    <div class="grid grid-cols-2 gap-4 border-b border-slate-100 px-6 py-5 md:grid-cols-4">
        @foreach([['Salesman / Deliveryman ID & Name', ['employee_id' => $deliveryman['employee_id'], 'name' => $deliveryman['name'], 'id' => $deliveryman['id']]],['Distributor Name',$trip['distributor']],['Market / Route Name',$trip['market_area']],['Vehicle / Van Number',$trip['vehicle']]] as [$label,$value])
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $label }}</p>
            @if(is_array($value))
                <a href="{{ route('deliverymen.show', $value['id']) }}" class="mt-1 inline-block text-sm font-semibold text-slate-800 hover:text-blue-600">{{ $value['employee_id'] }} · {{ $value['name'] }}</a>
            @else
                <p class="mt-1 text-sm font-semibold text-slate-800">{{ $value }}</p>
            @endif
        </div>
        @endforeach
    </div>
    <div class="grid gap-6 p-6 lg:grid-cols-3">
        <section><h3 class="mb-3 text-sm font-black text-slate-800">Distributor Stock Load &amp; Value</h3><div class="space-y-2 text-sm">
            @foreach([['Opening / Issued Stock Value (PKR)',$trip['opening_stock']],['Unsold Returned Stock Value (PKR)',$trip['returned_stock']],['Damaged / Expired Stock Value (PKR)',$trip['damaged_stock']],['Net Sales Value (PKR)',$trip['net_sales']]] as [$label,$value])<div class="flex justify-between gap-3"><span class="text-slate-500">{{ $label }}</span><strong class="text-slate-800">{{ pkr($value) }}</strong></div>@endforeach
        </div></section>
        <section><h3 class="mb-3 text-sm font-black text-slate-800">Market Collections &amp; Sales Breakdown</h3><div class="space-y-2 text-sm">
            @foreach([['Gross Market Sales (PKR)',$trip['gross_sales']],['Trade Discounts / Schemes (PKR)',$trip['discounts']],['Cash Collected from Market (PKR)',$trip['cash_collected']],['Cheques Collected (PKR)',$trip['cheques_collected']],['Online Transfers (PKR)',$trip['online_transfers']],['Market Credit / Udhaar (PKR)',$trip['market_credit']]] as [$label,$value])<div class="flex justify-between gap-3"><span class="text-slate-500">{{ $label }}</span><strong class="text-slate-800">{{ pkr($value) }}</strong></div>@endforeach
        </div></section>
        <section><h3 class="mb-3 text-sm font-black text-slate-800">Final Settlement &amp; Deliveryman Account Adjustment</h3><div class="space-y-2 text-sm">
            @foreach([['Total Value Submitted to Office (PKR)',$trip['submitted']],['Expected Cash from Salesman (PKR)',$trip['expected_cash']],['Actual Cash Handed Over (PKR)',$trip['actual_cash']],['Trip Cash Shortage (PKR)',$trip['trip_shortage']],['Accumulated Salesman Shortage Balance (PKR)',$trip['accumulated_shortage']]] as [$label,$value])<div class="flex justify-between gap-3"><span class="text-slate-500">{{ $label }}</span><strong class="{{ str_contains($label, 'Shortage') || str_contains($label, 'Balance') ? 'text-red-600' : 'text-slate-800' }}">{{ pkr($value) }}</strong></div>@endforeach
        </div></section>
    </div>
    <div class="border-t border-slate-100 px-6 py-4"><h3 class="mb-3 text-sm font-black text-slate-800">Stock Items</h3><div class="grid grid-cols-2 gap-3 text-sm md:grid-cols-4"><div><span class="text-slate-500">Product / SKU</span><strong class="ml-2 text-slate-800">Sooper FP</strong></div><div><span class="text-slate-500">Opening / Issued</span><strong class="ml-2 text-slate-800">0 cartons</strong></div><div><span class="text-slate-500">Returned / Damaged</span><strong class="ml-2 text-slate-800">0 / 0</strong></div><div><span class="text-slate-500">Net Sold</span><strong class="ml-2 text-slate-800">0 cartons</strong></div></div></div>
</article>
@endforeach
</div>

@if(false)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    {{-- Trip History --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-blue-500"></div>
            <h3 class="font-bold text-gray-800">Trip History</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Market</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Status</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Collected</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Shortage</th>
            </tr></thead>
            <tbody>
                @foreach($tripHistory as $t)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3">
                        <a href="{{ route('trips.show',$t['id']) }}" class="font-mono text-xs font-bold" style="color: #3b82f6;">{{ $t['trip_id'] }}</a>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $t['date'] }}</td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $t['market_area'] }}</td>
                    <td class="px-5 py-3"><x-status-badge :status="$t['status']"/></td>
                    <td class="px-5 py-3 text-right text-sm font-semibold" style="color: #16a34a;">{{ $t['collected']>0?pkr($t['collected']):'—' }}</td>
                    <td class="px-5 py-3 text-right text-sm font-bold {{ $t['shortage']>0?'text-red-500':'text-gray-300' }}">{{ $t['shortage']>0?pkr($t['shortage']):'—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Ledger --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-purple-500"></div>
            <h3 class="font-bold text-gray-800">Ledger Entries</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Type</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Debit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Credit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Balance</th>
            </tr></thead>
            <tbody>
                @foreach($ledgerEntries as $e)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $e['date'] }}</td>
                    <td class="px-5 py-3 font-mono text-xs font-semibold" style="color: #3b82f6;">{{ $e['trip_id'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600">{{ $e['type'] }}</td>
                    <td class="px-5 py-3 text-right text-xs font-semibold" style="color: #ef4444;">{{ $e['debit']>0?pkr($e['debit']):'—' }}</td>
                    <td class="px-5 py-3 text-right text-xs font-semibold" style="color: #16a34a;">{{ $e['credit']>0?pkr($e['credit']):'—' }}</td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">{{ pkr($e['balance']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
