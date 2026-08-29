@extends('layouts.app')
@php $pageTitle = 'Dashboard'; @endphp

@section('content')

{{-- KPI Cards --}}
@php
$iconPaths = [
    'truck'    => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
    'currency' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1',
    'banknotes'=> 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
    'warning'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'return'   => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6',
    'users'    => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'cube'     => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    'scale'    => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
];
$iconBgMap = [
    'blue'  => 'background:#dbeafe; color:#2563eb;',
    'green' => 'background:#dcfce7; color:#16a34a;',
    'red'   => 'background:#fee2e2; color:#dc2626;',
    'amber' => 'background:#fef3c7; color:#d97706;',
];
$borderMap = [
    'blue'  => 'border-left:4px solid #3b82f6;',
    'green' => 'border-left:4px solid #22c55e;',
    'red'   => 'border-left:4px solid #ef4444;',
    'amber' => 'border-left:4px solid #f59e0b;',
];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach($kpiCards as $card)
    <a href="{{ $card['route'] }}"
       class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5 group"
       style="{{ $borderMap[$card['color']] ?? 'border-left:4px solid #94a3b8;' }}">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="{{ $iconBgMap[$card['color']] ?? 'background:#f1f5f9; color:#64748b;' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$card['icon']] ?? $iconPaths['cube'] }}"/>
                </svg>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
        <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">{{ $card['title'] }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ $card['value'] }}</p>
    </a>
    @endforeach
</div>

{{-- Today's Trips + Recent Collections --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- Today's Trips --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center gap-2">
                <h3 class="font-semibold text-gray-800 text-base">Today's Trips</h3>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">{{ count($todaysTrips) }}</span>
            </div>
            <a href="{{ route('trips.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Trip ID</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Driver</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Market</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Value</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($todaysTrips as $trip)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-blue-600">{{ $trip['trip_id'] }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-700">{{ $trip['deliveryman'] }}</td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $trip['market_area'] }}</td>
                        <td class="px-5 py-3.5"><x-status-badge :status="$trip['status']"/></td>
                        <td class="px-5 py-3.5 text-right font-semibold text-gray-800">{{ pkr($trip['load_value']) }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <a href="{{ route('trips.show', $trip['id']) }}"
                               class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                View →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Collections --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-base">Recent Collections</h3>
            <a href="{{ route('collections.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700">View All →</a>
        </div>
        <div>
            @foreach($recentCollections as $col)
            <div class="px-6 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $col['customer'] }}</p>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $col['trip_id'] }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-green-600">{{ pkr($col['amount']) }}</p>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full mt-0.5 inline-block
                            {{ $col['method']==='Cash' ? 'bg-green-50 text-green-600' : ($col['method']==='Cheque' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600') }}">
                            {{ $col['method'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Shortages --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
        <div class="flex items-center gap-2">
            <h3 class="font-semibold text-gray-800 text-base">Open Shortages</h3>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-500">{{ count($topShortages) }}</span>
        </div>
        <a href="{{ route('settlements.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Deliveryman</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Trip ID</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Classification</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($topShortages as $s)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $s['deliveryman'] }}</td>
                    <td class="px-5 py-3.5 font-mono text-xs font-bold text-blue-600">{{ $s['trip_id'] }}</td>
                    <td class="px-5 py-3.5 text-right font-bold text-red-600">{{ pkr($s['amount']) }}</td>
                    <td class="px-5 py-3.5"><x-status-badge :status="$s['classification']"/></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
