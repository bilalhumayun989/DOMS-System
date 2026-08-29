@extends('layouts.app')
@php $pageTitle = 'Financial Summary'; @endphp

@section('content')
<div class="mb-4"><a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Reports</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-800 text-lg">Financial Summary — Current Month</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Sales</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Collections</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Shortages</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Returns</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Expenses</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-700">{{ $row['date'] }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($row['sales']) }}</td>
                    <td class="px-4 py-3 text-right font-medium text-green-600">{{ pkr($row['collections']) }}</td>
                    <td class="px-4 py-3 text-right {{ $row['shortages'] > 0 ? 'text-red-600 font-bold' : 'text-gray-400' }}">{{ $row['shortages'] > 0 ? pkr($row['shortages']) : '—' }}</td>
                    <td class="px-4 py-3 text-right text-amber-600 font-medium">{{ pkr($row['returns']) }}</td>
                    <td class="px-4 py-3 text-right text-gray-600">{{ pkr($row['expenses']) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                <tr>
                    <td class="px-4 py-3 font-bold text-gray-700">Totals</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800">{{ pkr(array_sum(array_column($rows, 'sales'))) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">{{ pkr(array_sum(array_column($rows, 'collections'))) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-red-600">{{ pkr(array_sum(array_column($rows, 'shortages'))) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-amber-600">{{ pkr(array_sum(array_column($rows, 'returns'))) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-600">{{ pkr(array_sum(array_column($rows, 'expenses'))) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
