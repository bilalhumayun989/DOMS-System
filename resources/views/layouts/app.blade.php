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
    /* ─── Grayscale Design Tokens ───────────────────────────────── */
    :root {
        --clr-bg:          #f2f2f2;   /* page background — very light grey  */
        --clr-surface:     #ffffff;   /* cards, panels — white              */
        --clr-border:      #d4d4d4;   /* card / table borders               */
        --clr-border-sub:  #e8e8e8;   /* subtle row dividers                */
        --clr-text-head:   #111111;   /* headings — near black              */
        --clr-text-body:   #333333;   /* body copy — dark grey              */
        --clr-text-muted:  #666666;   /* secondary labels — mid grey        */
        --clr-text-faint:  #999999;   /* placeholders, column headers       */

        /* Primary — dark charcoal (replaces blue) */
        --clr-primary:     #222222;
        --clr-primary-bg:  #efefef;
        --clr-primary-bdr: #cccccc;

        /* Success / Edit — medium grey */
        --clr-success:     #444444;
        --clr-success-bg:  #f0f0f0;
        --clr-edit-bg:     #f0f0f0;
        --clr-edit-text:   #444444;

        /* Danger / Delete — dark grey (NOT red) */
        --clr-danger:      #1a1a1a;
        --clr-danger-bg:   #e8e8e8;

        /* Warning — grey-amber-ish tone */
        --clr-warning:     #555555;
        --clr-warning-bg:  #ececec;
    }

    /* ─── Reset & Scrollbar ─────────────────────────────────────── */
    [x-cloak] { display: none !important; }
    * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: #f0f0f0; }
    ::-webkit-scrollbar-thumb { background: #cccccc; border-radius: 999px; }
    ::-webkit-scrollbar-thumb:hover { background: #999999; }
    aside.sidebar-nav::-webkit-scrollbar { display: none; }
    aside.sidebar-nav { -ms-overflow-style: none; scrollbar-width: none; }

    /* ─── Nav Links ──────────────────────────────────────────────── */
    .nav-link { transition: all 0.15s ease; }
    .nav-link.active {
        background: #e8e8e8;
        color: #111111;
        border-right: 3px solid #222222;
        border-radius: 0.75rem !important;
    }
    .nav-link.active svg { color: #111111 !important; }
    .nav-link:not(.active):hover { background: #f5f5f5; color: #222222; }

    /* ─── Page card ──────────────────────────────────────────────── */
    .page-card {
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: 1rem;
        overflow: hidden;
    }
    .page-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--clr-border-sub);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .page-card-title { font-size: 1rem;   font-weight: 700; color: var(--clr-text-head); }
    .page-card-sub   { font-size: 0.7rem; font-weight: 600; color: var(--clr-text-faint); margin-top: 1px; }

    /* ─── Table ──────────────────────────────────────────────────── */
    .doms-table th {
        padding: 0.75rem 1.5rem;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--clr-text-faint);
        background: #f8f8f8;
        white-space: nowrap;
    }
    .doms-table td {
        padding: 1rem 1.5rem;
        font-size: 0.8125rem;
        color: var(--clr-text-body);
        vertical-align: middle;
    }
    .doms-table tbody tr {
        border-top: 1px solid var(--clr-border-sub);
        transition: background 0.12s;
    }
    .doms-table tbody tr:hover { background: #f5f5f5; }

    /* ─── Row action buttons ─────────────────────────────────────── */
    .btn-view   { background: #efefef; color: #222222; border: 1px solid #cccccc; }
    .btn-edit   { background: #f0f0f0; color: #444444; border: 1px solid #dddddd; }
    .btn-delete { background: #e8e8e8; color: #1a1a1a; border: 1px solid #cccccc; }
    .btn-row    { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.7rem; font-weight: 600; padding: 0.375rem 0.75rem; border-radius: 0.5rem; transition: background 0.15s; cursor: pointer; }
    .btn-row:hover { background: #dddddd !important; color: #111111 !important; }

    /* ─── Primary header button ──────────────────────────────────── */
    .btn-primary { background: #222222; color: #ffffff; border: none; font-size: 0.75rem; font-weight: 600; padding: 0.5rem 1rem; border-radius: 0.5rem; display: inline-flex; align-items: center; gap: 0.375rem; cursor: pointer; transition: background 0.15s; }
    .btn-primary:hover { background: #111111; }

    /* ─── KPI card hover ─────────────────────────────────────────── */
    .kpi-card { transition: all 0.2s ease; }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.12), 0 4px 6px -2px rgba(0,0,0,0.06); }

    /* ─── Mono IDs ───────────────────────────────────────────────── */
    .mono-id { font-family: 'JetBrains Mono', 'Fira Code', monospace; font-size: 0.7rem; font-weight: 700; color: #222222; }

    /* ─── Modal ──────────────────────────────────────────────────── */
    .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; }
    .modal-panel    { position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%); background: #ffffff; border-radius: 0.875rem; box-shadow: 0 8px 32px rgba(0,0,0,0.16); z-index: 51; width: 100%; }
    .modal-label    { display: block; font-size: 0.7rem; font-weight: 700; color: #555555; margin-bottom: 0.3rem; }
    .modal-input    { width: 100%; font-size: 0.8125rem; background: #ffffff; border: 1px solid #d4d4d4; border-radius: 0.5rem; padding: 0.6rem 0.75rem; color: #333333; transition: border-color 0.15s; }
    .modal-input:focus { outline: none; border-color: #666666; box-shadow: 0 0 0 3px rgba(0,0,0,0.08); }
    .modal-input[readonly] { background: #f5f5f5; color: #999999; }
    .btn-modal-save   { background: #222222; color: #ffffff; border: none; font-size: 0.8125rem; font-weight: 600; padding: 0.5rem 1.25rem; border-radius: 0.5rem; cursor: pointer; transition: background 0.15s; }
    .btn-modal-save:hover   { background: #111111; }
    .btn-modal-cancel { background: #efefef; color: #555555; border: none; font-size: 0.8125rem; font-weight: 600; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; transition: background 0.15s; }
    .btn-modal-cancel:hover { background: #e0e0e0; }
    .btn-modal-delete { background: #333333; color: #ffffff; border: none; font-size: 0.8125rem; font-weight: 600; padding: 0.5rem 1.25rem; border-radius: 0.5rem; cursor: pointer; transition: background 0.15s; }
    .btn-modal-delete:hover { background: #111111; }
</style>
</head>
<body class="flex flex-col h-screen overflow-hidden" style="background: #f2f2f2;">

    @php $isDemoMode = session('demo_mode', false); @endphp

    {{-- ===== DEMO BANNER ===== --}}
    @if($isDemoMode)
    <div style="background: linear-gradient(90deg, #222222 0%, #444444 100%);" class="flex items-center justify-between px-6 py-2.5 flex-shrink-0 shadow-sm z-50 gap-4">
        <div class="flex items-center gap-3 flex-shrink-0">
            <svg class="w-4 h-4 text-yellow-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="text-white font-black text-sm tracking-tight">DEMO MODE</span>
            <span class="text-gray-200 text-xs font-medium hidden lg:inline">Sandboxed session — nothing is saved to the real database.</span>
        </div>
        <form action="{{ route('demo.exit') }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit" class="bg-white text-gray-700 text-xs font-black px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors whitespace-nowrap shadow-sm">Exit Demo</button>
        </form>
    </div>
    @endif

    {{-- ===== MAIN FLEX WRAPPER ===== --}}
    <div class="flex flex-1 overflow-hidden min-h-0">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="tour-sidebar" class="sidebar-nav w-64 flex flex-col flex-shrink-0 overflow-y-auto" style="background: #f9f9f9; border-right: 1px solid #d4d4d4;">

        {{-- Logo & Collapse Icon --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-base flex-shrink-0 shadow-sm {{ $isDemoMode ? 'bg-gray-700' : 'bg-slate-900' }}">
                    {{ $isDemoMode ? 'D' : 'K' }}
                </div>
                <div>
                    <div class="font-extrabold text-slate-900 text-sm tracking-tight flex items-center gap-1.5">
                        Kravio / DOMS
                        @if($isDemoMode)
                        <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 border border-gray-200 leading-none">DEMO</span>
                        @endif
                    </div>
                    <div class="text-[10px] font-semibold text-slate-400">{{ $isDemoMode ? 'Demo Session' : 'Delivery Ops' }}</div>
                </div>
            </div>
            <button class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors" title="Toggle Sidebar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
            </button>
        </div>

        {{-- Nav Label --}}
        <div class="px-5 pt-5 pb-2">
            <span class="text-xs font-semibold uppercase tracking-widest" style="color: #94a3b8;">Main Menu</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 pb-4 space-y-0.5">
    @php
    $navItems = [
        ['label'=>'Dashboard',   'route'=> $isDemoMode?'demo.dashboard':'dashboard',         'pattern'=> $isDemoMode?'demo.dashboard':'dashboard',       'tourId'=>'tour-nav-dashboard',   'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'badge'=>null],
        ['label'=>'Banks',       'route'=> $isDemoMode?'demo.banks':'banks.index',           'pattern'=> $isDemoMode?'demo.banks':'banks.*',             'tourId'=>'tour-nav-banks',       'icon'=>'M3 10h18M5 10v9m4-9v9m6-9v9m4-9v9M3 19h18M12 3l9 5H3l9-5z', 'badge'=>null],
        ['label'=>'Expenses',    'route'=> $isDemoMode?'demo.expenses':'expenses.index',     'pattern'=> $isDemoMode?'demo.expenses':'expenses.*',        'tourId'=>'tour-nav-expenses',    'icon'=>'M9 14l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z', 'badge'=>null],
        ['label'=>'Invoices',    'route'=> $isDemoMode?'demo.invoices':'invoices.index',     'pattern'=> $isDemoMode?'demo.invoices':'invoices.*',        'tourId'=>'tour-nav-invoices',    'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'badge'=>null],
        ['label'=>'Deliverymen', 'route'=> $isDemoMode?'demo.deliverymen':'deliverymen.index', 'pattern'=> $isDemoMode?'demo.deliverymen':'deliverymen.*', 'tourId'=>'tour-nav-deliverymen', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'badge'=>null],
        ['label'=>'Markets',     'route'=> $isDemoMode?'demo.markets':'markets.index',       'pattern'=> $isDemoMode?'demo.markets':'markets.*',          'tourId'=>'tour-nav-markets',     'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'badge'=>null],
        ['label'=>'Stock',       'route'=> $isDemoMode?'demo.stock':'stock.index',           'pattern'=> $isDemoMode?'demo.stock':'stock.*',              'tourId'=>'tour-nav-stock',       'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'badge'=> $isDemoMode?null:'3'],
        ['label'=>'Returns',     'route'=> $isDemoMode?'demo.returns':'returns.index',       'pattern'=> $isDemoMode?'demo.returns':'returns.*',          'tourId'=>'tour-nav-returns',     'icon'=>'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6', 'badge'=> $isDemoMode?null:'4'],
        ['label'=>'Settlements', 'route'=> $isDemoMode?'demo.settlements':'settlements.index', 'pattern'=> $isDemoMode?'demo.settlements':'settlements.*', 'tourId'=>'tour-nav-settlements', 'icon'=>'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3', 'badge'=> $isDemoMode?null:'2'],
        ['label'=>'Ledgers',     'route'=> $isDemoMode?'demo.ledgers':'ledgers.index',       'pattern'=> $isDemoMode?'demo.ledgers':'ledgers.*',          'tourId'=>'tour-nav-ledgers',     'icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'badge'=>null],
        ['label'=>'Reports',     'route'=> $isDemoMode?'demo.reports':'reports.index',       'pattern'=> $isDemoMode?'demo.reports':'reports.*',          'tourId'=>'tour-nav-reports',     'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'badge'=>null],
    ];
    @endphp

    {{-- Dashboard first --}}
    @php $dash = $navItems[0]; $isDash = request()->routeIs($dash['pattern']); @endphp
    <a id="{{ $dash['tourId'] }}" href="{{ route($dash['route']) }}"
       class="nav-link {{ $isDash ? 'active' : '' }} flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isDash ? '' : 'text-slate-500' }}">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $isDash ? 'bg-gray-100' : 'bg-slate-50' }}">
                <svg class="w-4 h-4 {{ $isDash ? 'text-gray-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dash['icon'] }}"/>
                </svg>
            </div>
            <span>Dashboard</span>
        </div>
    </a>

    {{-- Trips --}}
    @php
        $tripsActive = $isDemoMode ? request()->routeIs('demo.trips*') : request()->routeIs('trips.*');
        $tripsHref   = $isDemoMode ? route('demo.trips') : route('trips.index');
    @endphp
    <a id="tour-nav-trips" href="{{ $tripsHref }}"
       class="nav-link w-full {{ $tripsActive ? 'active' : '' }} flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $tripsActive ? '' : 'text-slate-500' }}">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $tripsActive ? 'bg-gray-100' : 'bg-slate-50' }}">
                    <svg class="w-4 h-4 {{ $tripsActive ? 'text-gray-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <span>Trips</span>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $tripsActive ? 'bg-gray-800 text-white' : 'bg-slate-100 text-slate-500' }}">Trips</span>
    </a>

    {{-- Remaining nav items (skip index 0 which is Dashboard) --}}
    @foreach(array_slice($navItems, 1) as $item)
    @php $isActive = request()->routeIs($item['pattern']); @endphp
    <a id="{{ $item['tourId'] }}" href="{{ route($item['route']) }}"
       class="nav-link {{ $isActive ? 'active' : '' }} flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium {{ $isActive ? '' : 'text-slate-500' }}">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $isActive ? 'bg-gray-100' : 'bg-slate-50' }}">
                <svg class="w-4 h-4 {{ $isActive ? 'text-gray-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
            </div>
            <span>{{ $item['label'] }}</span>
        </div>
        @if($item['badge'])
        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $isActive ? 'bg-gray-800 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $item['badge'] }}</span>
        @endif
    </a>
    @endforeach
        </nav>

        {{-- User Footer --}}
        <div id="tour-user-footer" class="px-4 py-3.5 mx-3 mb-3 rounded-2xl bg-slate-50 border border-slate-200/80">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-black flex-shrink-0 bg-slate-900 shadow-sm">
                        A
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">Admin / Owner</div>
                        <div class="text-[10px] font-semibold text-slate-400 truncate">admin@gmail.com</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-gray-900 hover:bg-gray-100 transition-colors" title="Sign Out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex flex-col flex-1 overflow-hidden">

        {{-- TOP HEADER --}}
        <header class="flex items-center justify-between px-8 py-3.5 flex-shrink-0" style="background:#ffffff; border-bottom: 1px solid #d4d4d4;">
            <div class="flex items-center gap-6">
                <div>
                    <h1 class="text-lg font-extrabold text-slate-800 tracking-tight">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    <p class="text-[11px] font-semibold text-slate-400 mt-0.5">{{ now()->format('l, d F Y') }}</p>
                </div>
                
                {{-- Search Bar --}}
                <div class="hidden md:flex items-center relative w-72">
                    <input type="text" placeholder="Search anything" class="w-full bg-slate-50 border border-slate-200 text-xs text-slate-700 rounded-xl pl-9 pr-12 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="absolute right-3 top-2 px-1.5 py-0.5 text-[10px] font-extrabold text-slate-400 bg-slate-200/60 rounded border border-slate-200">⌘K</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Notifications Icon --}}
                <button class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="w-2 h-2 rounded-full bg-gray-700 absolute top-2 right-2"></span>
                </button>

                {{-- Start Tour Button --}}
                <button id="start-tour-btn" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold hover:bg-gray-100" style="background: #efefef; color: #222222; border: 1px solid #cccccc; transition: all 0.2s;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Start Tour
                </button>

                {{-- Status pill --}}
                <div id="tour-system-status" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold" style="background: #f0f0f0; color: #333333; border: 1px solid #cccccc;">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500 inline-block"></span>
                    System Online
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
                    <a href="{{ $crumb['route'] }}" class="text-xs font-medium text-gray-700 hover:text-gray-900">{{ $crumb['label'] }}</a>
                    @else
                    <span class="text-xs font-semibold text-gray-700">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
            @endisset

            @yield('content')
        </main>
    </div>

    </div>{{-- /flex flex-1 min-h-0 wrapper --}}

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
            background-color: #333333 !important;
            color: white !important;
            border-radius: 6px !important;
            border: none !important;
            padding: 6px 12px !important;
            font-weight: 500 !important;
            text-shadow: none !important;
            transition: all 0.2s;
        }
        .driver-popover-footer button:hover {
            background-color: #111111 !important;
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
                            description: 'Manage daily delivery trips. Use the month and year filters to find trips, then add, edit, delete, or view each trip sheet.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-banks',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Banks',
                            description: 'Review AAA Traders bank accounts, cash in hand, deposits, withdrawals, running balances, and account-specific transaction ledgers. Add Bank opens the account form.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-expenses',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Expenses',
                            description: 'Track vehicle, driver, warehouse, and office expenses. Add Expense opens the dedicated entry page, where categories and trip links can be selected.',
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
                            title: 'Return Claims',
                            description: 'Review returned goods as claims, including expiry, damage, market returns, distributor status, credit notes, and dedicated claim detail pages.',
                            side: 'right', align: 'center'
                        }
                    },
                    {
                        element: '#tour-nav-settlements',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Settlements',
                            description: 'Reconcile driver cash, stock, market credit, and shortages at end of day. Add Settlement selects an existing Trip ID and prepares its clearing sheet.',
                            side: 'right', align: 'start'
                        }
                    },
                    {
                        element: '#tour-nav-ledgers',
                        onHighlightStarted: (el) => scrollSidebarToEl(el),
                        popover: {
                            title: 'Ledgers',
                            description: 'Review EBM and CFL supplier payables plus driver shortage receivables. Create entries with debit or credit values and open full voucher audit details.',
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
