@extends('layouts.app')
@php $pageTitle = 'Reports'; @endphp

@section('content')
<div class="page-card space-y-6">
    <div class="page-card-header">
        <div>
            <h2 class="page-card-title">Reports & Analytics</h2>
            <p class="page-card-sub">Overview of operational metrics in demo mode</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
            <span class="text-xs font-semibold text-slate-500">Trip Efficiency</span>
            <div class="text-xl font-black text-slate-900">96.4%</div>
            <span class="text-[10px] text-gray-700 font-bold">↑ 2.1% from yesterday</span>
        </div>
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
            <span class="text-xs font-semibold text-slate-500">Collection Recovery</span>
            <div class="text-xl font-black text-slate-900">PKR 48,000</div>
            <span class="text-[10px] text-slate-500 font-bold">Demo simulated total</span>
        </div>
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
            <span class="text-xs font-semibold text-slate-500">Shortage Ratio</span>
            <div class="text-xl font-black text-slate-900">0.8%</div>
            <span class="text-[10px] text-gray-700 font-bold">Within normal threshold</span>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-slate-900 text-white space-y-3 text-center">
        <h3 class="text-lg font-bold">Demo Analytics Ready</h3>
        <p class="text-xs text-slate-400 max-w-md mx-auto">All reports adapt live to the items created within your demo sandbox session.</p>
    </div>
</div>
@endsection
