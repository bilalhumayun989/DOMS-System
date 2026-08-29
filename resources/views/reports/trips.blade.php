@extends('layouts.app')
@php $pageTitle = 'Trip Report'; @endphp

@section('content')
<div class="mb-4">
    <a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Reports</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-lg">Trip Report — Current Month</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Trips</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Load Value</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Collected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Shortage</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-700 font-medium">{{ $row['date'] }}</td>
                    <td class="px-4 py-3 text-center font-medium text-gray-800">{{ $row['trips'] }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($row['load_value']) }}</td>
                    <td class="px-4 py-3 text-right font-medium text-green-600">{{ pkr($row['collected']) }}</td>
                    <td class="px-4 py-3 text-right {{ $row['shortage'] > 0 ? 'font-bold text-red-600' : 'text-gray-400' }}">{{ $row['shortage'] > 0 ? pkr($row['shortage']) : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                <tr>
                    <td class="px-4 py-3 font-bold text-gray-700">Totals</td>
                    <td class="px-4 py-3 text-center font-bold text-gray-800">{{ array_sum(array_column($rows, 'trips')) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800">{{ pkr(array_sum(array_column($rows, 'load_value'))) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">{{ pkr(array_sum(array_column($rows, 'collected'))) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-red-600">{{ pkr(array_sum(array_column($rows, 'shortage'))) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
