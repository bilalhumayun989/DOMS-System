@extends('layouts.app')
@php $pageTitle = 'Dashboard'; @endphp

@section('content')

{{-- KPI Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
    $iconPaths = [
        'truck'    => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
        'currency' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1m-8.485-4.5A9 9 0 1120.485 7.5',
        'banknotes'=> 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        'warning'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'return'   => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6',
        'users'    => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'cube'     => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'scale'    => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
    ];
    $colorMap = [
        'blue'  => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'red'   => 'bg-red-50 text-red-600',
        'amber' => 'bg-amber-50 text-amber-600',
    ];
    $borderMap = [
        'blue'  => 'border-l-4 border-blue-400',
        'green' => 'border-l-4 border-green-400',
        'red'   => 'border-l-4 border-red-400',
        'amber' => 'border-l-4 border-amber-400',
    ];
    @endphp

    @foreach($kpiCards as $card)
    <a href="{{ $card['route'] }}"
       class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-5 {{ $borderMap[$card['color']] ?? 'border-l-4 border-gray-300' }} block group">
        <div class="flex items-start justify-between">
            <p class="text-sm text-gray-500 font-medium group-hover:text-gray-700">{{ $card['title'] }}</p>
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ $colorMap[$card['color']] ?? 'bg-gray-100 text-gray-500' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$card['icon']] ?? $iconPaths['cube'] }}"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-2xl font-bold text-gray-800">{{ $card['value'] }}</p>
        <p class="mt-1 text-xs text-gray-400">Click to view details →</p>
    </a>
    @endforeach
</div>

{{-- Today's Trips + Recent Collections --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Today's Trips --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Today's Trips</h2>
            <a href="{{ route('trips.index') }}" class="text-xs text-blue-600 hover:underline">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Trip ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Deliveryman</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Market</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Load Value</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($todaysTrips as $trip)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs text-blue-600 font-medium">{{ $trip['trip_id'] }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $trip['deliveryman'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $trip['market_area'] }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$trip['status']"/></td>
                        <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($trip['load_value']) }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('trips.show', $trip['id']) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Collections --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Recent Collections</h2>
            <a href="{{ route('collections.index') }}" class="text-xs text-blue-600 hover:underline">View All →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($recentCollections as $col)
            <div class="px-5 py-3">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $col['customer'] }}</p>
                    <span class="text-sm font-bold text-green-600 ml-2 flex-shrink-0">{{ pkr($col['amount']) }}</span>
                </div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs px-1.5 py-0.5 rounded {{ $col['method'] === 'Cash' ? 'bg-green-100 text-green-700' : ($col['method'] === 'Cheque' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">{{ $col['method'] }}</span>
                    <span class="text-xs text-gray-400 font-mono">{{ $col['trip_id'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Shortages --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Top Open Shortages</h2>
        <a href="{{ route('settlements.index') }}" class="text-xs text-blue-600 hover:underline">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Deliveryman</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Trip ID</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Classification</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($topShortages as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-700 font-medium">{{ $s['deliveryman'] }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-blue-600">{{ $s['trip_id'] }}</td>
                    <td class="px-4 py-3 text-right font-bold text-red-600">{{ pkr($s['amount']) }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$s['classification']"/></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
