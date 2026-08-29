<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOMS — {{ $pageTitle ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .nav-link { transition: all 0.15s ease; }
        .nav-link.active { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #1d4ed8; border-right: 3px solid #3b82f6; }
        .nav-link:not(.active):hover { background: #f8fafc; color: #334155; }
        .kpi-card { transition: all 0.2s ease; }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="flex h-screen overflow-hidden" style="background: #f0f4f8;">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-64 flex flex-col flex-shrink-0 overflow-y-auto" style="background: #ffffff; border-right: 1px solid #e8edf2;">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5" style="border-bottom: 1px solid #e8edf2;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-base flex-shrink-0"
                 style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                D
            </div>
            <div>
                <div class="font-bold text-gray-900 text-sm tracking-wide">DOMS</div>
                <div class="text-xs" style="color: #94a3b8;">Delivery Management</div>
            </div>
        </div>

        {{-- Nav Label --}}
        <div class="px-5 pt-5 pb-2">
            <span class="text-xs font-semibold uppercase tracking-widest" style="color: #94a3b8;">Main Menu</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 pb-4 space-y-0.5">
            @php
            $navItems = [
                ['label'=>'Dashboard',   'route'=>'dashboard',         'pattern'=>'dashboard',       'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'badge'=>null],
                ['label'=>'Trips',       'route'=>'trips.index',       'pattern'=>'trips.*',         'icon'=>'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'badge'=>'8'],
                ['label'=>'Deliverymen', 'route'=>'deliverymen.index', 'pattern'=>'deliverymen.*',   'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'badge'=>null],
                ['label'=>'Markets',     'route'=>'markets.index',     'pattern'=>'markets.*',       'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'badge'=>null],
                ['label'=>'Invoices',    'route'=>'invoices.index',    'pattern'=>'invoices.*',      'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'badge'=>null],
                ['label'=>'Stock',       'route'=>'stock.index',       'pattern'=>'stock.*',         'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'badge'=>'3'],
                ['label'=>'Returns',     'route'=>'returns.index',     'pattern'=>'returns.*',       'icon'=>'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', 'badge'=>'4'],
                ['label'=>'Collections', 'route'=>'collections.index', 'pattern'=>'collections.*',   'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'badge'=>null],
                ['label'=>'Settlements', 'route'=>'settlements.index', 'pattern'=>'settlements.*',   'icon'=>'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3', 'badge'=>'2'],
                ['label'=>'Ledgers',     'route'=>'ledgers.index',     'pattern'=>'ledgers.*',       'icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'badge'=>null],
                ['label'=>'Reports',     'route'=>'reports.index',     'pattern'=>'reports.*',       'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'badge'=>null],
            ];
            @endphp

            @foreach($navItems as $item)
            @php $isActive = request()->routeIs($item['pattern']); @endphp
            <a href="{{ route($item['route']) }}"
               class="nav-link {{ $isActive ? 'active' : '' }} flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive ? '' : 'text-slate-500' }}">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-blue-100' : 'bg-slate-50' }}">
                        <svg class="w-4 h-4 {{ $isActive ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <span>{{ $item['label'] }}</span>
                </div>
                @if($item['badge'])
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $isActive ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $item['badge'] }}</span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- User Footer --}}
        <div class="px-4 py-4 mx-3 mb-3 rounded-xl" style="background: #f8fafc; border: 1px solid #e8edf2;">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                     style="background: linear-gradient(135deg, #3b82f6, #8b5cf6);">A</div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-semibold text-gray-700 truncate">Admin / Owner</div>
                    <div class="text-xs truncate" style="color: #94a3b8;">DOMS v1.0</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex flex-col flex-1 overflow-hidden">

        {{-- TOP HEADER --}}
        <header class="flex items-center justify-between px-8 py-4 flex-shrink-0"
                style="background: #ffffff; border-bottom: 1px solid #e8edf2;">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $pageTitle ?? 'Dashboard' }}</h1>
                <p class="text-xs mt-0.5" style="color: #94a3b8;">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Status pill --}}
                <div class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                    System Online
                </div>
                {{-- Date pill --}}
                <div class="px-3 py-2 rounded-xl text-xs font-medium" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">
                    {{ now()->format('d M Y') }}
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 overflow-y-auto px-8 py-6">

            {{-- Breadcrumbs --}}
            @isset($breadcrumbs)
            <nav class="flex items-center gap-1.5 mb-5">
                @foreach($breadcrumbs as $i => $crumb)
                    @if($i > 0)
                    <svg class="w-3.5 h-3.5" style="color: #cbd5e1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    @endif
                    @if($crumb['route'])
                    <a href="{{ $crumb['route'] }}" class="text-xs font-medium text-blue-500 hover:text-blue-700">{{ $crumb['label'] }}</a>
                    @else
                    <span class="text-xs font-semibold text-gray-700">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
            @endisset

            @yield('content')
        </main>
    </div>

</body>
</html>
