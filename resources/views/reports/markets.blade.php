@extends('layouts.app')
@php $pageTitle = 'Market Report'; @endphp

@section('content')
<div class="mb-4"><a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Reports</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-800 text-lg">Market / Customer Report</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Market</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Invoices</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Sales</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Collected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Outstanding</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">0-30 Days</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">31-60 Days</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">60+ Days</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['name'] }}</td>
                    <td class="px-4 py-3 text-center text-gray-700">{{ $row['total_invoices'] }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($row['total_sales']) }}</td>
                    <td class="px-4 py-3 text-right font-medium text-green-600">{{ pkr($row['total_collected']) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-red-600">{{ pkr($row['outstanding']) }}</td>
                    <td class="px-4 py-3 text-right text-gray-600">{{ pkr($row['aging_0_30']) }}</td>
                    <td class="px-4 py-3 text-right text-amber-600">{{ pkr($row['aging_31_60']) }}</td>
                    <td class="px-4 py-3 text-right text-red-600">{{ pkr($row['aging_60_plus']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
