@extends('layouts.app')
@php $pageTitle = $sku['sku_code']; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h2 class="text-xl font-bold text-gray-800 font-mono">{{ $sku['sku_code'] }}</h2>
                <x-status-badge :status="$sku['stock_status']"/>
            </div>
            <p class="text-lg font-medium text-gray-700">{{ $sku['product_name'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Category: <span class="font-medium text-gray-700">{{ $sku['category'] }}</span></p>
        </div>
        <div class="flex gap-6">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Current Stock</p>
                <p class="text-3xl font-bold mt-1 {{ $sku['current_stock'] === 0 ? 'text-red-600' : ($sku['stock_status'] === 'Low Stock' ? 'text-amber-600' : 'text-gray-800') }}">{{ $sku['current_stock'] }}</p>
                <p class="text-xs text-gray-400">units</p>
            </div>
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Reorder Point</p>
                <p class="text-3xl font-bold text-gray-600 mt-1">{{ $sku['reorder_point'] }}</p>
                <p class="text-xs text-gray-400">units</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Stock Movement History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Movement Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trip ID</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qty Change</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Balance After</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($movements as $mov)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $mov['date'] }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $mov['type'] === 'Dispatch' ? 'bg-orange-100 text-orange-700' : ($mov['type'] === 'Return' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">{{ $mov['type'] }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($mov['trip_db_id'])
                        <a href="{{ route('trips.show', $mov['trip_db_id']) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $mov['trip_id'] }}</a>
                        @else
                        <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center font-bold {{ $mov['qty_change'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $mov['qty_change'] > 0 ? '+' : '' }}{{ $mov['qty_change'] }}
                    </td>
                    <td class="px-4 py-3 text-center font-medium text-gray-800">{{ $mov['balance_after'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
