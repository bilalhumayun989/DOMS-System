@extends('layouts.app')
@php $pageTitle = 'Settlements'; @endphp

@section('content')
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Settlements</h2>
            <p class="text-xs mt-0.5 font-medium" style="color:#94a3b8;">{{ count($settlements) }} records</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Ref</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Trip</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Driver</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Expected</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Collected</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Shortage</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Classification</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Status</th>
            </tr></thead>
            <tbody>
                @foreach($settlements as $s)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">{{ $s['settlement_ref'] }}</td>
                    <td class="px-6 py-4"><a href="{{ route('trips.show',$s['trip_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $s['trip_display'] }}</a></td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $s['deliveryman'] }}</td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $s['date'] }}</td>
                    <td class="px-6 py-4 text-right text-sm font-semibold text-gray-800">{{ pkr($s['expected_cash']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($s['collected_amount']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold {{ $s['shortage_amount']>0?'text-red-500':'text-gray-300' }}">{{ $s['shortage_amount']>0?pkr($s['shortage_amount']):'—' }}</td>
                    <td class="px-6 py-4">@if($s['shortage_classification'])<x-status-badge :status="$s['shortage_classification']"/>@else<span class="text-gray-300 text-xs">—</span>@endif</td>
                    <td class="px-6 py-4"><x-status-badge :status="$s['status']"/></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #fafbfc; border-top: 2px solid #e2e8f0;">
                    <td colspan="4" class="px-6 py-4 text-sm font-bold text-gray-700 text-right">Totals</td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ pkr($totals['expected_cash']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($totals['collected_amount']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold" style="color:#ef4444;">{{ pkr($totals['shortage_amount']) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
