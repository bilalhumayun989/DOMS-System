@extends('layouts.app')
@php $pageTitle = 'Stock'; @endphp

@section('content')
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Stock Overview</h2>
            <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($skus) }} SKUs tracked</p>
        </div>
        <div class="flex gap-2">
            @php $outCount = count(array_filter($skus, fn($s)=>$s['stock_status']==='Out of Stock')); $lowCount = count(array_filter($skus, fn($s)=>$s['stock_status']==='Low Stock')); @endphp
            @if($outCount > 0)<span class="text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#fff1f2;color:#ef4444;">{{ $outCount }} Out of Stock</span>@endif
            @if($lowCount > 0)<span class="text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#fffbeb;color:#b45309;">{{ $lowCount }} Low Stock</span>@endif
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">SKU Code</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Product</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Category</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Stock</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Reorder</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Status</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;"></th>
            </tr></thead>
            <tbody>
                @foreach($skus as $sku)
                <tr class="border-t hover:bg-slate-50 transition-colors {{ $sku['stock_status']==='Out of Stock'?'bg-red-50/30':'' }}" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-gray-700">{{ $sku['sku_code'] }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $sku['product_name'] }}</td>
                    <td class="px-6 py-4"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg" style="background:#f1f5f9;color:#475569;">{{ $sku['category'] }}</span></td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-bold {{ $sku['current_stock']===0?'text-red-600':($sku['stock_status']==='Low Stock'?'text-amber-600':'text-gray-900') }}">{{ $sku['current_stock'] }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $sku['reorder_point'] }}</td>
                    <td class="px-6 py-4"><x-status-badge :status="$sku['stock_status']"/></td>
                    <td class="px-6 py-4 text-center"><a href="{{ route('stock.show',$sku['id']) }}" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:#eff6ff;color:#3b82f6;">View →</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
