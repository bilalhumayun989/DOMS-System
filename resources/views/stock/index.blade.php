@extends('layouts.app')
@php $pageTitle = 'Stock'; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-lg">Stock Overview</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ count($skus) }} SKUs tracked</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">SKU Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Product Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Current Stock</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Reorder Point</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($skus as $sku)
                <tr class="hover:bg-gray-50 transition-colors {{ $sku['stock_status'] === 'Out of Stock' ? 'bg-red-50' : '' }}">
                    <td class="px-4 py-3 font-mono text-xs text-gray-700 font-medium">{{ $sku['sku_code'] }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $sku['product_name'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $sku['category'] }}</td>
                    <td class="px-4 py-3 text-center font-bold {{ $sku['current_stock'] === 0 ? 'text-red-600' : ($sku['stock_status'] === 'Low Stock' ? 'text-amber-600' : 'text-gray-800') }}">{{ $sku['current_stock'] }}</td>
                    <td class="px-4 py-3 text-center text-gray-600">{{ $sku['reorder_point'] }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$sku['stock_status']"/></td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('stock.show', $sku['id']) }}" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-medium transition-colors">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
