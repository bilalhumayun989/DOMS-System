@extends('layouts.app')
@php $pageTitle = $sku['sku_code']; @endphp

@section('content')
<div class="rounded-2xl p-6 mb-5" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="font-mono text-xl font-bold text-gray-900">{{ $sku['sku_code'] }}</span>
                <x-status-badge :status="$sku['stock_status']"/>
            </div>
            <p class="text-lg font-semibold text-gray-700">{{ $sku['product_name'] }}</p>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg mt-2 inline-block" style="background:#f1f5f9;color:#475569;">{{ $sku['category'] }}</span>
        </div>
        <div class="flex gap-4">
            <div class="px-6 py-4 rounded-2xl text-center" style="background: {{ $sku['current_stock']===0?'#fff1f2':($sku['stock_status']==='Low Stock'?'#fffbeb':'#f0fdf4') }};">
                <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: {{ $sku['current_stock']===0?'#fca5a5':($sku['stock_status']==='Low Stock'?'#fcd34d':'#86efac') }};">Current Stock</p>
                <p class="text-3xl font-bold" style="color: {{ $sku['current_stock']===0?'#b91c1c':($sku['stock_status']==='Low Stock'?'#b45309':'#15803d') }};">{{ $sku['current_stock'] }}</p>
                <p class="text-xs mt-1" style="color:#94a3b8;">units</p>
            </div>
            <div class="px-6 py-4 rounded-2xl text-center" style="background:#f8fafc;">
                <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color:#94a3b8;">Reorder Point</p>
                <p class="text-3xl font-bold text-gray-600">{{ $sku['reorder_point'] }}</p>
                <p class="text-xs mt-1" style="color:#94a3b8;">units</p>
            </div>
        </div>
    </div>
</div>

<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
        <div class="w-1 h-5 rounded-full bg-amber-400"></div>
        <h3 class="font-bold text-gray-800">Stock Movement History</h3>
    </div>
    <table class="w-full">
        <thead><tr style="background: #fafbfc;">
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Type</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Trip</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Qty Change</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Balance After</th>
        </tr></thead>
        <tbody>
            @foreach($movements as $mov)
            <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                <td class="px-6 py-4 text-xs text-gray-500">{{ $mov['date'] }}</td>
                <td class="px-6 py-4">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg
                        {{ $mov['type']==='Dispatch'?'bg-orange-50 text-orange-600':($mov['type']==='Return'?'bg-blue-50 text-blue-600':'bg-gray-100 text-gray-600') }}">
                        {{ $mov['type'] }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    @if($mov['trip_db_id'])
                    <a href="{{ route('trips.show',$mov['trip_db_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $mov['trip_id'] }}</a>
                    @else<span class="text-gray-300 text-xs">—</span>@endif
                </td>
                <td class="px-6 py-4 text-center text-sm font-bold {{ $mov['qty_change']<0?'text-red-500':'text-green-600' }}">
                    {{ $mov['qty_change']>0?'+'.$mov['qty_change']:$mov['qty_change'] }}
                </td>
                <td class="px-6 py-4 text-center text-sm font-bold text-gray-800">{{ $mov['balance_after'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
