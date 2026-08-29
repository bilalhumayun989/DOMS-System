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
@endsection
