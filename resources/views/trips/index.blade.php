@extends('layouts.app')
@php $pageTitle = 'Trips'; @endphp

@section('content')
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 class="text-lg font-bold text-gray-900">All Trips</h2>
            <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($trips) }} trips on record</p>
        </div>
        <div class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold" style="background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            {{ count($trips) }} Total
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: #fafbfc;">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Deliveryman</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Market / Area</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Load Value</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Expected Cash</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $trip)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-bold" style="color: #3b82f6;">{{ $trip['trip_id'] }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $trip['date'] }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}"
                           class="text-sm font-semibold text-gray-800 hover:text-blue-600 transition-colors">
                            {{ $trip['deliveryman']['name'] }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $trip['vehicle'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $trip['market_area'] }}</td>
                    <td class="px-6 py-4"><x-status-badge :status="$trip['status']"/></td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ $trip['load_value'] > 0 ? pkr($trip['load_value']) : '—' }}</td>
                    <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $trip['expected_cash'] > 0 ? pkr($trip['expected_cash']) : '—' }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('trips.show', $trip['id']) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                           style="background: #eff6ff; color: #3b82f6;">
                            View →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
