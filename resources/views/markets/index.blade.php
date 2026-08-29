@extends('layouts.app')
@php $pageTitle = 'Markets'; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-lg">All Markets</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ count($markets) }} markets</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Market Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Area / Region</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Invoices</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Value</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Collected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Outstanding</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($markets as $market)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $market['name'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $market['area'] }}</td>
                    <td class="px-4 py-3 text-center text-gray-700 font-medium">{{ $market['total_invoices'] }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($market['total_value']) }}</td>
                    <td class="px-4 py-3 text-right font-medium text-green-600">{{ pkr($market['total_collected']) }}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="{{ $market['outstanding_balance'] > 0 ? 'text-red-600 font-bold' : 'text-gray-400' }}">{{ pkr($market['outstanding_balance']) }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('markets.show', $market['id']) }}" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-medium transition-colors">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
