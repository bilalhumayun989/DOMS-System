<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOMS — {{ $pageTitle ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex h-screen bg-gray-100 overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-800 text-white flex flex-col flex-shrink-0 overflow-y-auto">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-700">
            <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-white text-sm">D</div>
            <div>
                <div class="font-bold text-white text-sm tracking-wide">DOMS</div>
                <div class="text-slate-400 text-xs">Distribution System</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5">
            @php
            $navItems = [
                ['label' => 'Dashboard',    'route' => 'dashboard',         'pattern' => 'dashboard',           'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['label' => 'Trips',        'route' => 'trips.index',       'pattern' => 'trips.*',             'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['label' => 'Deliverymen',  'route' => 'deliverymen.index', 'pattern' => 'deliverymen.*',       'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Markets',      'route' => 'markets.index',     'pattern' => 'markets.*',           'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                ['label' => 'Invoices',     'route' => 'invoices.index',    'pattern' => 'invoices.*',          'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label' => 'Stock',        'route' => 'stock.index',       'pattern' => 'stock.*',             'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['label' => 'Returns',      'route' => 'returns.index',     'pattern' => 'returns.*',           'icon' => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6'],
                ['label' => 'Collections',  'route' => 'collections.index', 'pattern' => 'collections.*',       'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => 'Settlements',  'route' => 'settlements.index', 'pattern' => 'settlements.*',       'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3'],
                ['label' => 'Ledgers',      'route' => 'ledgers.index',     'pattern' => 'ledgers.*',           'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => 'Reports',      'route' => 'reports.index',     'pattern' => 'reports.*',           'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ];
            @endphp

            @foreach($navItems as $item)
            @php $isActive = request()->routeIs($item['pattern']); @endphp
            <a href="{{ route($item['route']) }}"
               class="{{ $isActive ? 'bg-slate-700 text-white border-l-4 border-blue-400 pl-4' : 'text-slate-300 hover:bg-slate-700 hover:text-white pl-5' }} flex items-center gap-3 py-2.5 pr-3 rounded-lg transition-colors duration-150 text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-slate-700 text-xs text-slate-400">
            <div class="font-medium text-slate-300">Owner / Admin</div>
            <div>DOMS v1.0 — Demo Mode</div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex flex-col flex-1 overflow-hidden">
        {{-- Top header --}}
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $pageTitle ?? 'Dashboard' }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                    ● System Online
                </span>
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">A</div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @isset($breadcrumbs)
            <nav class="flex items-center gap-1.5 text-sm text-gray-500 mb-4">
                @foreach($breadcrumbs as $i => $crumb)
                    @if($i > 0)
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    @endif
                    @if($crumb['route'])
                    <a href="{{ $crumb['route'] }}" class="text-blue-600 hover:underline">{{ $crumb['label'] }}</a>
                    @else
                    <span class="text-gray-700 font-medium">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
            @endisset

            @yield('content')
        </main>
    </div>

</body>
</html>
