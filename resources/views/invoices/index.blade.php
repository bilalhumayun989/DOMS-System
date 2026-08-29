@extends('layouts.app')
@php $pageTitle = 'Invoices'; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-lg">All Invoices</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ count($invoices) }} invoices</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice #</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trip ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Value</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoices as $inv)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-blue-600 font-medium">{{ $inv['invoice_number'] }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $inv['customer'] }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('trips.show', $inv['trip_id']) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $inv['trip_id_display'] }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $inv['date'] }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($inv['total_value']) }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$inv['status']"/></td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('invoices.show', $inv['id']) }}" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-medium transition-colors">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
