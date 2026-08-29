@extends('layouts.app')
@php $pageTitle = 'Deliveryman Report'; @endphp

@section('content')
<div class="mb-4"><a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Reports</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-800 text-lg">Deliveryman Performance Report</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Total Trips</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Value</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Collected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Shortages</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Shortage Rate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['name'] }}</td>
                    <td class="px-4 py-3 text-center font-medium text-gray-800">{{ $row['total_trips'] }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($row['total_value']) }}</td>
                    <td class="px-4 py-3 text-right font-medium text-green-600">{{ pkr($row['total_collected']) }}</td>
                    <td class="px-4 py-3 text-right {{ $row['total_shortages'] > 0 ? 'text-red-600 font-bold' : 'text-gray-400' }}">{{ $row['total_shortages'] > 0 ? pkr($row['total_shortages']) : '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $row['shortage_rate'] > 0.5 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ number_format($row['shortage_rate'], 1) }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
