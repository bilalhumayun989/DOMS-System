@extends('layouts.app')
@php $pageTitle = 'Reports'; @endphp

@section('content')
@php
$iconPaths = [
    'truck'    => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
    'users'    => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'map'      => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
    'cube'     => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    'chart'    => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    'currency' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1m-8.485-4.5A9 9 0 1120.485 7.5',
    'shield'   => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
];
$colorMap = [
    'blue'   => ['bg'=>'#eff6ff','icon_bg'=>'#3b82f6','color'=>'#1d4ed8','border'=>'#bfdbfe'],
    'indigo' => ['bg'=>'#eef2ff','icon_bg'=>'#6366f1','color'=>'#4338ca','border'=>'#c7d2fe'],
    'teal'   => ['bg'=>'#f0fdfa','icon_bg'=>'#14b8a6','color'=>'#0f766e','border'=>'#99f6e4'],
    'amber'  => ['bg'=>'#fffbeb','icon_bg'=>'#f59e0b','color'=>'#b45309','border'=>'#fde68a'],
    'purple' => ['bg'=>'#faf5ff','icon_bg'=>'#a855f7','color'=>'#7e22ce','border'=>'#e9d5ff'],
    'green'  => ['bg'=>'#f0fdf4','icon_bg'=>'#22c55e','color'=>'#15803d','border'=>'#bbf7d0'],
    'red'    => ['bg'=>'#fff1f2','icon_bg'=>'#ef4444','color'=>'#b91c1c','border'=>'#fecdd3'],
];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach($reports as $report)
    @php $s = $colorMap[$report['color']] ?? $colorMap['blue']; @endphp
    <div class="rounded-2xl p-6 transition-all hover:shadow-lg" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background: {{ $s['icon_bg'] }};">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$report['icon']] ?? '' }}"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-base">{{ $report['title'] }}</h3>
                <p class="text-sm mt-1 leading-relaxed" style="color:#64748b;">{{ $report['description'] }}</p>
            </div>
        </div>
        <a href="{{ $report['route'] }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold transition-colors"
           style="background: {{ $s['bg'] }}; color: {{ $s['color'] }}; border: 1px solid {{ $s['border'] }};">
            View Report
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endforeach
</div>
@endsection
