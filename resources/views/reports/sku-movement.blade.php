@extends('layouts.app')
@php $pageTitle = 'SKU Movement Report'; @endphp

@section('content')
<div class="mb-4"><a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Reports</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-800 text-lg">SKU Movement Report</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">SKU</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Dispatched</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Returned</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Adjusted</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Net Movement</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $row['date'] }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $row['sku'] }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['product'] }}</td>
                    <td class="px-4 py-3 text-center text-orange-600 font-medium">{{ $row['dispatched'] }}</td>
                    <td class="px-4 py-3 text-center text-blue-600 font-medium">{{ $row['returned'] > 0 ? $row['returned'] : '—' }}</td>
                    <td class="px-4 py-3 text-center text-gray-600">{{ $row['adjusted'] != 0 ? $row['adjusted'] : '—' }}</td>
                    <td class="px-4 py-3 text-center font-bold {{ $row['net_movement'] < 0 ? 'text-red-600' : 'text-green-600' }}">{{ $row['net_movement'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
