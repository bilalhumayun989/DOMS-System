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
    'blue'   => 'bg-blue-50 text-blue-600',
    'indigo' => 'bg-indigo-50 text-indigo-600',
    'teal'   => 'bg-teal-50 text-teal-600',
    'amber'  => 'bg-amber-50 text-amber-600',
    'purple' => 'bg-purple-50 text-purple-600',
    'green'  => 'bg-green-50 text-green-600',
    'red'    => 'bg-red-50 text-red-600',
];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($reports as $report)
    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-100">
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 {{ $colorMap[$report['color']] ?? 'bg-gray-100 text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$report['icon']] ?? '' }}"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800">{{ $report['title'] }}</h3>
                <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $report['description'] }}</p>
                <a href="{{ $report['route'] }}"
                   class="inline-flex items-center gap-1 mt-4 text-sm font-medium text-blue-600 hover:text-blue-800">
                    View Report
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
