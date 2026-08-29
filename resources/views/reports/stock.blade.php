@extends('layouts.app')
@php $pageTitle = 'Stock Report'; @endphp

@section('content')
<div class="mb-4"><a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Reports</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-800 text-lg">Stock Report by Category</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Total SKUs</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">In Stock</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Low Stock</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Out of Stock</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Units</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['category'] }}</td>
                    <td class="px-4 py-3 text-center text-gray-700">{{ $row['total_skus'] }}</td>
                    <td class="px-4 py-3 text-center font-medium text-green-600">{{ $row['in_stock'] }}</td>
                    <td class="px-4 py-3 text-center font-medium text-amber-600">{{ $row['low_stock'] }}</td>
                    <td class="px-4 py-3 text-center font-medium text-red-600">{{ $row['out_of_stock'] }}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800">{{ number_format($row['total_units']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
