@extends('layouts.app')
@php $pageTitle = $market['name']; @endphp

@section('content')
<div class="rounded-2xl p-6 mb-5" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="flex items-start justify-between flex-wrap gap-4 mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $market['name'] }}</h2>
            <div class="flex flex-wrap gap-3 mt-2">
                <span class="text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#f1f5f9;color:#475569;">📍 {{ $market['area'] }}</span>
                <span class="text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#f1f5f9;color:#475569;">👤 {{ $market['contact'] }}</span>
                <span class="text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#f1f5f9;color:#475569;">📞 {{ $market['phone'] }}</span>
            </div>
        </div>
        <div class="px-5 py-3 rounded-2xl text-right" style="background: {{ $market['outstanding_balance']>0?'#fff1f2':'#f0fdf4' }};">
            <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: {{ $market['outstanding_balance']>0?'#fca5a5':'#86efac' }};">Outstanding</p>
            <p class="text-2xl font-bold" style="color: {{ $market['outstanding_balance']>0?'#b91c1c':'#15803d' }};">{{ pkr($market['outstanding_balance']) }}</p>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4 pt-5" style="border-top: 1px solid #f1f5f9;">
        @foreach([['Total Invoices',$market['total_invoices'],false,'#3b82f6','#eff6ff'],['Total Value',$market['total_value'],true,'#1d4ed8','#eff6ff'],['Collected',$market['total_collected'],true,'#15803d','#f0fdf4']] as [$l,$v,$f,$c,$b])
        <div class="px-4 py-3 rounded-xl text-center" style="background: {{ $b }};">
            <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: {{ $c }}; opacity:0.7">{{ $l }}</p>
            <p class="text-lg font-bold" style="color: {{ $c }};">{{ $f ? pkr($v) : $v }}</p>
        </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-blue-500"></div>
            <h3 class="font-bold text-gray-800">Invoice History</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Invoice</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Value</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Status</th>
            </tr></thead>
            <tbody>
                @foreach($invoices as $inv)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3"><a href="{{ route('invoices.show',$inv['id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $inv['invoice_number'] }}</a></td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $inv['date'] }}</td>
                    <td class="px-5 py-3"><a href="{{ route('trips.show',$inv['trip_db_id']) }}" class="font-mono text-xs font-semibold" style="color:#3b82f6;">{{ $inv['trip_id'] }}</a></td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">{{ pkr($inv['value']) }}</td>
                    <td class="px-5 py-3"><x-status-badge :status="$inv['status']"/></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-purple-500"></div>
            <h3 class="font-bold text-gray-800">Market Ledger</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Ref</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Type</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Debit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Credit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Balance</th>
            </tr></thead>
            <tbody>
                @foreach($ledgerEntries as $e)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $e['date'] }}</td>
                    <td class="px-5 py-3 font-mono text-xs font-semibold text-gray-600">{{ $e['reference'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600">{{ $e['type'] }}</td>
                    <td class="px-5 py-3 text-right text-xs font-semibold" style="color:#ef4444;">{{ $e['debit']>0?pkr($e['debit']):'—' }}</td>
                    <td class="px-5 py-3 text-right text-xs font-semibold" style="color:#16a34a;">{{ $e['credit']>0?pkr($e['credit']):'—' }}</td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">{{ pkr($e['balance']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
