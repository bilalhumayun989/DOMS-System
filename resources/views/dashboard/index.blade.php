@extends('layouts.app')
@php $pageTitle = 'Dashboard'; @endphp

@section('content')

{{-- Welcome banner --}}
<div class="rounded-2xl p-6 mb-6 flex items-center justify-between"
     style="background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 50%, #60a5fa 100%);">
    <div>
        <h2 class="text-xl font-bold text-white">Good morning, Admin! 👋</h2>
        <p class="text-blue-100 text-sm mt-1">Here's what's happening with your deliveries today.</p>
    </div>
    <div class="text-right hidden md:block">
        <p class="text-3xl font-bold text-white">{{ now()->format('d') }}</p>
        <p class="text-blue-200 text-sm font-medium">{{ now()->format('M Y') }}</p>
    </div>
</div>

{{-- KPI Cards --}}
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
$cardStyles = [
    'blue'  => ['bg'=>'#eff6ff','border'=>'#bfdbfe','icon_bg'=>'#3b82f6','text'=>'#1d4ed8','label'=>'#93c5fd'],
    'green' => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','icon_bg'=>'#22c55e','text'=>'#15803d','label'=>'#86efac'],
    'red'   => ['bg'=>'#fff1f2','border'=>'#fecdd3','icon_bg'=>'#ef4444','text'=>'#b91c1c','label'=>'#fca5a5'],
    'amber' => ['bg'=>'#fffbeb','border'=>'#fde68a','icon_bg'=>'#f59e0b','text'=>'#b45309','label'=>'#fcd34d'],
];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach($kpiCards as $card)
    @php $s = $cardStyles[$card['color']] ?? $cardStyles['blue']; @endphp
    <a href="{{ $card['route'] }}"
       class="kpi-card rounded-2xl p-5 block"
       style="background: {{ $s['bg'] }}; border: 1px solid {{ $s['border'] }};">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: {{ $s['icon_bg'] }};">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$card['icon']] ?? $iconPaths['cube'] }}"/>
                </svg>
            </div>
            <svg class="w-4 h-4 opacity-40" style="color: {{ $s['text'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
        <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: {{ $s['label'] }};">{{ $card['title'] }}</p>
        <p class="text-2xl font-bold" style="color: {{ $s['text'] }};">{{ $card['value'] }}</p>
    </a>
    @endforeach
</div>

{{-- Today's Trips + Recent Collections --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- Today's Trips --}}
    <div class="xl:col-span-2 rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
            <div class="flex items-center gap-2">
                <div class="w-1 h-5 rounded-full bg-blue-500"></div>
                <h3 class="font-semibold text-gray-800">Today's Trips</h3>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background: #eff6ff; color: #3b82f6;">{{ count($todaysTrips) }}</span>
            </div>
            <a href="{{ route('trips.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700 flex items-center gap-1">
                View All
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: #fafbfc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Market</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Value</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todaysTrips as $trip)
                    <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                        <td class="px-6 py-3.5">
                            <span class="font-mono text-xs font-semibold" style="color: #3b82f6;">{{ $trip['trip_id'] }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-sm font-medium text-gray-700">{{ $trip['deliveryman'] }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-500">{{ $trip['market_area'] }}</td>
                        <td class="px-6 py-3.5"><x-status-badge :status="$trip['status']"/></td>
                        <td class="px-6 py-3.5 text-right text-sm font-semibold text-gray-800">{{ pkr($trip['load_value']) }}</td>
                        <td class="px-6 py-3.5 text-center">
                            <a href="{{ route('trips.show', $trip['id']) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                               style="background: #eff6ff; color: #3b82f6;">
                                View
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Collections --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
            <div class="flex items-center gap-2">
                <div class="w-1 h-5 rounded-full bg-green-500"></div>
                <h3 class="font-semibold text-gray-800">Recent Collections</h3>
            </div>
            <a href="{{ route('collections.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700">View All →</a>
        </div>
        <div class="divide-y" style="divide-color: #f1f5f9;">
            @foreach($recentCollections as $col)
            <div class="px-6 py-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $col['customer'] }}</p>
                        <p class="text-xs mt-0.5 font-mono" style="color: #94a3b8;">{{ $col['trip_id'] }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold" style="color: #16a34a;">{{ pkr($col['amount']) }}</p>
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
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
        <div class="flex items-center gap-2">
            <div class="w-1 h-5 rounded-full bg-red-400"></div>
            <h3 class="font-semibold text-gray-800">Open Shortages</h3>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background: #fff1f2; color: #ef4444;">{{ count($topShortages) }}</span>
        </div>
        <a href="{{ route('settlements.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700">View All →</a>
    </div>
    <table class="w-full">
        <thead>
            <tr style="background: #fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Deliveryman</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip ID</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Shortage</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Classification</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topShortages as $s)
            <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                <td class="px-6 py-3.5 text-sm font-semibold text-gray-800">{{ $s['deliveryman'] }}</td>
                <td class="px-6 py-3.5 font-mono text-xs font-semibold" style="color: #3b82f6;">{{ $s['trip_id'] }}</td>
                <td class="px-6 py-3.5 text-right text-sm font-bold" style="color: #ef4444;">{{ pkr($s['amount']) }}</td>
                <td class="px-6 py-3.5"><x-status-badge :status="$s['classification']"/></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
