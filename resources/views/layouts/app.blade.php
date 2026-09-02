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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Driver.js for Product Tour --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', system-ui, sans-serif; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        aside.sidebar-nav::-webkit-scrollbar { display: none; }
        aside.sidebar-nav { -ms-overflow-style: none; scrollbar-width: none; }
        .nav-link { transition: all 0.15s ease; }
        .nav-link.active { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #1d4ed8; border-right: 3px solid #3b82f6; }
        .nav-link:not(.active):hover { background: #f8fafc; color: #334155; }
        .kpi-card { transition: all 0.2s ease; }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="flex h-screen overflow-hidden" style="background: #f0f4f8;">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="tour-sidebar" class="sidebar-nav w-64 flex flex-col flex-shrink-0 overflow-y-auto" style="background: #ffffff; border-right: 1px solid #e8edf2;">

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
        <nav class="flex-1 px-3 pb-4 space-y-0.5" x-data="{ tripsOpen: {{ request()->routeIs('trips.*') ? 'true' : 'false' }} }">
    @php
    $navItems = [
        ['label'=>'Dashboard',   'route'=>'dashboard',         'pattern'=>'dashboard',       'tourId'=>'tour-nav-dashboard',   'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'badge'=>null],
        ['label'=>'Deliverymen', 'route'=>'deliverymen.index', 'pattern'=>'deliverymen.*',   'tourId'=>'tour-nav-deliverymen', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'badge'=>null],
        ['label'=>'Markets',     'route'=>'markets.index',     'pattern'=>'markets.*',       'tourId'=>'tour-nav-markets',     'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'badge'=>null],
        ['label'=>'Invoices',    'route'=>'invoices.index',    'pattern'=>'invoices.*',      'tourId'=>'tour-nav-invoices',    'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'badge'=>null],
        ['label'=>'Stock',       'route'=>'stock.index',       'pattern'=>'stock.*',         'tourId'=>'tour-nav-stock',       'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'badge'=>'3'],
        ['label'=>'Returns',     'route'=>'returns.index',     'pattern'=>'returns.*',       'tourId'=>'tour-nav-returns',     'icon'=>'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', 'badge'=>'4'],
        ['label'=>'Collections', 'route'=>'collections.index', 'pattern'=>'collections.*',   'tourId'=>'tour-nav-collections', 'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'badge'=>null],
        ['label'=>'Settlements', 'route'=>'settlements.index', 'pattern'=>'settlements.*',   'tourId'=>'tour-nav-settlements', 'icon'=>'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3', 'badge'=>'2'],
        ['label'=>'Ledgers',     'route'=>'ledgers.index',     'pattern'=>'ledgers.*',       'tourId'=>'tour-nav-ledgers',     'icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'badge'=>null],
        ['label'=>'Reports',     'route'=>'reports.index',     'pattern'=>'reports.*',       'tourId'=>'tour-nav-reports',     'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'badge'=>null],
    ];
    @endphp

    {{-- Dashboard first --}}
    @php $dash = $navItems[0]; $isDash = request()->routeIs($dash['pattern']); @endphp
    <a id="{{ $dash['tourId'] }}" href="{{ route($dash['route']) }}"
       class="nav-link {{ $isDash ? 'active' : '' }} flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isDash ? '' : 'text-slate-500' }}">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $isDash ? 'bg-blue-100' : 'bg-slate-50' }}">
                <svg class="w-4 h-4 {{ $isDash ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dash['icon'] }}"/>
                </svg>
            </div>
            <span>Dashboard</span>
        </div>
    </a>

    {{-- ── Trips expandable ── --}}
    @php $tripsActive = request()->routeIs('trips.*'); @endphp
    <div>
        <button
            id="tour-nav-trips"
            @click="tripsOpen = !tripsOpen"
            class="nav-link w-full {{ $tripsActive ? 'active' : '' }} flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $tripsActive ? '' : 'text-slate-500' }}"
        >
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $tripsActive ? 'bg-blue-100' : 'bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ $tripsActive ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <span>Trips</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $tripsActive ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500' }}">8</span>
                <svg class="w-3.5 h-3.5 transition-transform" :class="tripsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </button>

        {{-- Sub-items --}}
        <div x-show="tripsOpen" x-cloak x-transition class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left: 2px solid #e2e8f0;">
            <a id="tour-nav-trips-all" href="{{ route('trips.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('trips.index') && !request()->has('filter') ? 'text-blue-600 bg-blue-50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                All Trips
            </a>
            <a id="tour-nav-trips-open" href="{{ route('trips.index', ['filter' => 'open']) }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->get('filter') === 'open' ? 'text-orange-600 bg-orange-50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Open Trips
                <span class="ml-auto text-xs font-bold px-1.5 py-0.5 rounded-full bg-orange-100 text-orange-600">3</span>
            </a>
        </div>
    </div>

    {{-- Remaining nav items (skip index 0 which is Dashboard) --}}
    @foreach(array_slice($navItems, 1) as $item)
    @php $isActive = request()->routeIs($item['pattern']); @endphp
    <a id="{{ $item['tourId'] }}" href="{{ route($item['route']) }}"
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
        <div id="tour-user-footer" class="px-4 py-4 mx-3 mb-3 rounded-xl" style="background: #f8fafc; border: 1px solid #e8edf2;">
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
                {{-- Start Tour Button --}}
                <button id="start-tour-btn" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium hover:bg-blue-100" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; transition: all 0.2s;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Start Tour
                </button>
                {{-- Status pill --}}
                <div id="tour-system-status" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">
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

    {{-- Driver.js Implementation --}}
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
    <style>
        .driver-popover {
            font-family: 'Inter', system-ui, sans-serif !important;
            border-radius: 12px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            border: 1px solid #e2e8f0 !important;
        }
        .driver-popover-title {
            font-size: 1.125rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
        }
        .driver-popover-description {
            font-size: 0.875rem !important;
            color: #475569 !important;
            line-height: 1.5 !important;
            margin-top: 0.5rem !important;
        }
        .driver-popover-footer button {
            background-color: #3b82f6 !important;
            color: white !important;
            border-radius: 6px !important;
            border: none !important;
            padding: 6px 12px !important;
            font-weight: 500 !important;
            text-shadow: none !important;
            transition: all 0.2s;
        }
        .driver-popover-footer button:hover {
            background-color: #2563eb !important;
        }
        .driver-popover-footer button.driver-popover-prev-btn {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }
        .driver-popover-footer button.driver-popover-prev-btn:hover {
            background-color: #e2e8f0 !important;
        }
        .driver-popover-progress-text {
            color: #64748b !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const driver = window.driver.js.driver;

            /**
             * Open the Trips expandable dropdown in the sidebar.
             * Mutates Alpine's reactive data so x-show reacts immediately.
             */
            function openTripsMenu() {
                const navEl = document.querySelector('nav[x-data]');
                if (!navEl) return;
                // Alpine v3 stores reactive data on _x_dataStack
                const stack = navEl._x_dataStack;
                if (stack) {
                    for (const data of stack) {
                        if (typeof data.tripsOpen !== 'undefined') {
                            data.tripsOpen = true;
                            return;
                        }
                    }
                }
                // Fallback: click the button to trigger Alpine's @click handler
                const btn = document.getElementById('tour-nav-trips');
                if (btn) {
                    const subMenu = btn.closest('div')?.querySelector('[x-show]');
                    if (!subMenu || getComputedStyle(subMenu).display === 'none') {
                        btn.click();
                    }
                }
            }

            /**
             * Scroll the sidebar so the given element is visible and centered.
             * Uses 'instant' (not 'smooth') so scrolling completes synchronously
             * before Driver.js measures the element's position for the overlay.
             */
            function scrollSidebarToEl(el) {
                if (!el) return;
                el.scrollIntoView({ behavior: 'instant', block: 'center' });
            }

            const driverObj = driver({
                showProgress: true,
                animate: true,
                allowClose: true,
                progressText: 'Step @{{current}} of @{{total}}',
                steps: [
                    {
                        popover: {
                            title: 'Welcome to DOMS',
                            description: 'Welcome to DOMS — the Delivery Order Management System. Click Next to take a guided tour of every section in the sidebar.'
                        }
                    },
                    {
                        element: '#tour-nav-dashboard',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Dashboard',
                            description: 'Your command center. See a live overview of all key metrics — total trips, active deliverymen, pending invoices, and today\'s activity at a glance.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-trips',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Trips',
                            description: 'Manage all delivery trips. This button expands a sub-menu with quick filters. The badge shows total trips recorded. Click Next to see the sub-menu options.',
                            side: 'right', align: 'center',
                            // Open the dropdown THEN advance — gives Alpine time to show sub-items
                            onNextClick: () => {
                                openTripsMenu();
                                setTimeout(() => driverObj.moveNext(), 350);
                            }
                        }
                    },
                    {
                        element: '#tour-nav-trips-all',
                        onHighlightStarted: (el) => {
                            openTripsMenu();                  // ensure open when going backwards
                            setTimeout(() => scrollSidebarToEl(el), 100);
                        },
                        popover: {
                            title: 'All Trips',
                            description: 'View the complete list of every delivery trip regardless of status — open, closed, or settled. Search, sort, and manage them all from here.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-trips-open',
                        onHighlightStarted: (el) => {
                            openTripsMenu();
                            setTimeout(() => scrollSidebarToEl(el), 100);
                        },
                        popover: {
                            title: 'Open Trips',
                            description: 'A focused view showing only trips still in progress and not yet settled. The orange badge shows how many trips are currently open.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-deliverymen',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Deliverymen',
                            description: 'Manage your delivery team. Add new deliverymen, view their trip history, track their active assignments, and update their information.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-markets',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Markets',
                            description: 'Manage all your market or shop locations. Each market is linked to trips and invoices so you can track deliveries and payments per location.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-invoices',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Invoices',
                            description: 'View and manage all delivery invoices. Create new invoices, attach them to trips, and track payment status for each market you serve.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-stock',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Stock',
                            description: 'Track your inventory and stock levels. Monitor items dispatched with deliverymen and reconcile what was sold versus what was returned. The badge shows pending stock entries.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-returns',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Returns',
                            description: 'Record and manage goods returned by deliverymen or markets. The badge indicates the number of unresolved return entries awaiting review.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-collections',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Collections',
                            description: 'Record cash or payment collections made by deliverymen from markets. All collections are linked to their respective trips and invoices for full traceability.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-settlements',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Settlements',
                            description: 'Settle accounts with deliverymen after completing their trips. This section calculates the balance between goods dispatched, returned, and cash collected. The badge shows pending settlements.',
                            side: 'right', align: 'start'
                        }
                    },
                    {
                        element: '#tour-nav-ledgers',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Ledgers',
                            description: 'View detailed financial ledgers for each deliveryman and market. Get a complete running balance of all transactions, helping you keep your accounts accurate and up to date.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-reports',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Reports',
                            description: 'Generate and view business reports — sales summaries, trip performance, collection totals, and more. Use reports to make data-driven decisions for your delivery operations.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-user-footer',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Your Account',
                            description: 'Your account profile and the current DOMS system version are shown here at the bottom of the sidebar.',
                            side: 'top', align: 'center'
                        }
                    },
                    {
                        element: '#tour-system-status',
                        popover: {
                            title: 'System Status',
                            description: 'This indicator shows whether the DOMS system is running normally. A green light means everything is online and operational.',
                            side: 'bottom', align: 'center'
                        }
                    },
                    {
                        element: 'main.flex-1',
                        popover: {
                            title: 'Main Working Area',
                            description: 'This is where all your content is displayed. Every section you navigate to will load its data and forms right here. That wraps up the tour — you are all set!',
                            side: 'left', align: 'start'
                        }
                    }
                ]
            });

            const startBtn = document.getElementById('start-tour-btn');
            if (startBtn) {
                startBtn.addEventListener('click', function() {
                    driverObj.drive();
                });
            }
        });
    </script>
</body>
</html>
