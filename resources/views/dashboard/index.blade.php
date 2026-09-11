@extends('layouts.app')
@php $pageTitle = 'Dashboard'; @endphp

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    .doms-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem;
    }
    .doms-middle-grid {
        display: grid;
        grid-template-columns: minmax(0, 2.1fr) minmax(0, 1fr);
        gap: 1.5rem;
    }
    .doms-charts-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.5rem;
    }
    @media (max-width: 1024px) {
        .doms-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .doms-middle-grid { grid-template-columns: minmax(0, 1fr); }
        .doms-charts-grid { grid-template-columns: minmax(0, 1fr); }
    }
    @media (max-width: 640px) {
        .doms-kpi-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
    }
</style>

@php
$iconPaths = [
    'truck'    => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
    'currency' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1',
    'banknotes'=> 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
    'warning'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'cube'     => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
];
$iconBgMap = [
    'blue'  => 'bg-blue-50 text-blue-600 border-blue-200',
    'green' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
    'red'   => 'bg-rose-50 text-rose-600 border-rose-200',
    'amber' => 'bg-amber-50 text-amber-600 border-amber-200',
];
$borderLeftMap = [
    'blue'  => 'border-l-4 border-l-blue-500',
    'green' => 'border-l-4 border-l-emerald-500',
    'red'   => 'border-l-4 border-l-rose-500',
    'amber' => 'border-l-4 border-l-amber-500',
];
@endphp

<div class="space-y-6">

    {{-- SECTION 1: 8 KPI CARDS GRID (4 Columns x 2 Rows) --}}
    <div class="doms-kpi-grid">
        @foreach($kpiCards as $card)
        <a href="{{ $card['route'] }}"
           class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 block group {{ $borderLeftMap[$card['color']] ?? 'border-l-4 border-l-slate-400' }}">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center border flex-shrink-0 {{ $iconBgMap[$card['color']] ?? 'bg-slate-100 text-slate-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$card['icon']] ?? $iconPaths['cube'] }}"/>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">{{ $card['title'] }}</p>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $card['value'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- SECTION 2: TODAY'S TRIPS + QUICK DAY SELECTOR --}}
    <div class="doms-middle-grid">

        {{-- Today's Trips Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden min-w-0">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="font-black text-slate-900 text-base">Today's Trips</h3>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">{{ count($todaysTrips) }}</span>
                </div>
                <a href="{{ route('trips.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-extrabold uppercase tracking-wider">
                            <th class="px-5 py-3.5">Route ID / Trip ID</th>
                            <th class="px-5 py-3.5">Driver Name</th>
                            <th class="px-5 py-3.5">Distributor</th>
                            <th class="px-5 py-3.5">Market / Area</th>
                            <th class="px-5 py-3.5 text-right">Date</th>
                            <th class="px-5 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @foreach($todaysTrips as $trip)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-bold text-blue-600">
                                <a href="{{ route('trips.show', $trip['id']) }}" class="hover:underline">{{ $trip['route_id'] }}</a>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                <a href="{{ route('deliverymen.show', $trip['deliveryman_id']) }}" class="hover:text-blue-600">{{ $trip['deliveryman'] }}</a>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $trip['distributor'] }}</td>
                            <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $trip['market_area'] }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-800">{{ $trip['date'] }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ route('trips.show', $trip['id']) }}"
                                   class="text-xs font-bold px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors inline-block">
                                    View →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Day Selector (Days 1 – 31) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 overflow-hidden min-w-0">
            <div class="px-1 py-1 mb-3">
                <h3 class="font-black text-slate-900 text-base">Quick Day Selector (Days 1 – 31)</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Click any day for report breakdown</p>
            </div>
            <div class="grid grid-cols-5 gap-2">
                @foreach(range(1, 31) as $day)
                <a href="{{ route('reports.trips', ['day' => $day]) }}"
                   class="flex h-9 items-center justify-center rounded-xl border border-blue-100 bg-blue-50/70 text-xs font-bold text-blue-600 transition-all hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:shadow-sm">
                    Day {{ $day }}
                </a>
                @endforeach
            </div>
        </div>

    </div>

    {{-- SECTION 3: DAILY SHORTAGES & RECOVERIES TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="font-black text-slate-900 text-base">Daily Shortages &amp; Recoveries</h3>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-500">{{ count($topShortages) }}</span>
            </div>
            <a href="{{ route('settlements.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-extrabold uppercase tracking-wider">
                        <th class="px-5 py-3.5">Driver / Deliveryman Name</th>
                        <th class="px-5 py-3.5">Route ID / Trip ID</th>
                        <th class="px-5 py-3.5">Market / Area</th>
                        <th class="px-5 py-3.5 text-right">Shortage Amount (PKR)</th>
                        <th class="px-5 py-3.5">Recovery Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                    @foreach($topShortages as $s)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5 font-bold text-slate-900">
                            <a href="{{ route('deliverymen.show', $s['deliveryman_id']) }}" class="hover:text-blue-600">{{ $s['deliveryman'] }}</a>
                        </td>
                        <td class="px-5 py-3.5 font-mono font-bold text-blue-600">
                            <a href="{{ route('trips.show', $s['id']) }}" class="hover:underline">{{ $s['trip_id'] }}</a>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $s['market_area'] }}</td>
                        <td class="px-5 py-3.5 text-right font-black text-rose-600 text-sm">{{ pkr($s['amount']) }}</td>
                        <td class="px-5 py-3.5"><x-status-badge :status="$s['recovery_status']"/></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-100 bg-slate-50/80 font-bold text-slate-800">
                        <td colspan="5" class="px-5 py-3.5">
                            <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-1">
                                <span>Total Shortage Today: <strong class="text-rose-600 font-black text-sm">{{ pkr(array_sum(array_column($topShortages, 'amount'))) }}</strong></span>
                                <span>Total Recovered: <strong class="text-emerald-600 font-black text-sm">{{ pkr(array_sum(array_map(fn ($shortage) => $shortage['recovery_status'] === 'Recovered' ? $shortage['amount'] : 0, $topShortages))) }}</strong></span>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- SECTION 4: 2 REPORT ANALYTICS GRAPHS BELOW --}}
    <div class="doms-charts-grid">

        {{-- Graph 1: Trip & Delivery Volume Trend --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="p-1 rounded-md bg-slate-100 text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </span>
                        <h3 class="text-base font-extrabold text-slate-900">Trip &amp; Delivery Volume Trend</h3>
                    </div>
                    <span class="text-2xl font-black text-slate-900 mt-2 block tracking-tight">4,790 Trips</span>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">+8% vs last week</span>
            </div>
            <div id="trip-volume-chart" class="w-full min-h-[260px]"></div>
        </div>

        {{-- Graph 2: Weekly Collections & Cash Recovery Performance --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="p-1 rounded-md bg-emerald-50 text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1"/>
                            </svg>
                        </span>
                        <h3 class="text-base font-extrabold text-slate-900">Collections &amp; Revenue Analytics</h3>
                    </div>
                    <span class="text-2xl font-black text-slate-900 mt-2 block tracking-tight">PKR 174,500.00</span>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Weekly Summary</span>
            </div>
            <div id="collections-trend-chart" class="w-full min-h-[260px]"></div>
        </div>

    </div>

</div>

{{-- ApexCharts Script Initialization --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Bar Chart: Trip & Delivery Volume Trend (Kravio exact styling with dark selected Tue bar)
    const volumeChartOptions = {
        series: [{
            name: 'Trips',
            data: [320, 450, 584, 490, 610, 530, 410]
        }],
        chart: {
            type: 'bar',
            height: 260,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '45%',
                distributed: true
            }
        },
        colors: ['#E2E8F0', '#E2E8F0', '#1D2939', '#E2E8F0', '#E2E8F0', '#E2E8F0', '#E2E8F0'],
        dataLabels: { enabled: false },
        legend: { show: false },
        xaxis: {
            categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#94A3B8', fontSize: '12px', fontWeight: 600 } }
        },
        yaxis: {
            labels: { style: { colors: '#CBD5E1', fontSize: '11px', fontWeight: 500 } }
        },
        grid: { borderColor: '#F1F5F9', strokeDashArray: 4 },
        tooltip: { theme: 'dark', y: { formatter: (val) => val + " trips" } }
    };

    const volumeChart = new ApexCharts(document.querySelector("#trip-volume-chart"), volumeChartOptions);
    volumeChart.render();

    // 2. Area Chart: Weekly Collections & Revenue Analytics
    const collectionsChartOptions = {
        series: [{
            name: 'Collection (PKR)',
            data: [45000, 72000, 95000, 110000, 145000, 160000, 174500]
        }],
        chart: {
            type: 'area',
            height: 260,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#10B981'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        xaxis: {
            categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#94A3B8', fontSize: '12px', fontWeight: 600 } }
        },
        yaxis: {
            labels: {
                style: { colors: '#CBD5E1', fontSize: '11px', fontWeight: 500 },
                formatter: (val) => "Rs " + (val / 1000) + "k"
            }
        },
        grid: { borderColor: '#F1F5F9', strokeDashArray: 4 },
        tooltip: { theme: 'dark', y: { formatter: (val) => "PKR " + val.toLocaleString() } }
    };

    const collectionsChart = new ApexCharts(document.querySelector("#collections-trend-chart"), collectionsChartOptions);
    collectionsChart.render();

});
</script>

@endsection
