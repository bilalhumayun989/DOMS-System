@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $trip['route_id'] ?? 'Demo Trip' }}</h2>
            <p class="text-xs font-semibold text-slate-400 mt-0.5">Demo trip — read-only view &nbsp;·&nbsp; Status: <strong class="text-slate-700">{{ $trip['status'] }}</strong></p>
        </div>
        <a href="{{ route('demo.trips') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Trips
        </a>
    </div>

    {{-- Demo notice --}}
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-100 border border-gray-300 text-gray-700 text-sm font-semibold">
        <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        This is a demo trip. Collections, expenses, and close operations are not available in demo mode.
    </div>

    {{-- Trip Info Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="page-card p-6 space-y-4">
            <h3 class="font-black text-slate-900 text-sm uppercase tracking-wider border-b border-slate-100 pb-3">Trip Details</h3>
            @foreach([
                ['Trip Number', $trip['route_id']],
                ['Date', $trip['date']],
                ['Status', $trip['status']],
                ['Deliveryman', $trip['deliveryman']],
                ['Vehicle', $trip['vehicle']],
                ['Market Area', $trip['market_area']],
                ['Distributor', $trip['distributor']],
            ] as [$label, $value])
            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">{{ $label }}</span>
                <span class="text-sm font-semibold text-slate-800">{{ $value ?? '—' }}</span>
            </div>
            @endforeach
        </div>

        <div class="page-card p-6 space-y-4">
            <h3 class="font-black text-slate-900 text-sm uppercase tracking-wider border-b border-slate-100 pb-3">Financial Summary</h3>
            @foreach([
                ['Load Value', pkr($trip['load_value'])],
                ['Expected Cash', pkr($trip['expected_cash'])],
                ['Collections', pkr(0)],
                ['Expenses', pkr(0)],
                ['Net Difference', pkr($trip['expected_cash'])],
            ] as [$label, $value])
            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">{{ $label }}</span>
                <span class="text-sm font-semibold text-slate-800">{{ $value }}</span>
            </div>
            @endforeach

            <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-500 text-center">
                In the real system, you can add collections, expenses, and close trips here.
            </div>
        </div>

    </div>

</div>
@endsection
