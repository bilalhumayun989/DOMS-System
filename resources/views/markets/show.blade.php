@extends('layouts.app')
@php $pageTitle = $market['name']; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $market['name'] }}</h2>
            <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600">
                <span>📍 {{ $market['area'] }}</span>
                <span>👤 {{ $market['contact'] }}</span>
                <span>📞 {{ $market['phone'] }}</span>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Outstanding Balance</p>
            <p class="text-2xl font-bold {{ $market['outstanding_balance'] > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">{{ pkr($market['outstanding_balance']) }}</p>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4 mt-5 pt-5 border-t border-gray-100">
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase">Total Invoices</p>
            <p class="text-xl font-bold text-gray-800 mt-1">{{ $market['total_invoices'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase">Total Value</p>
            <p class="text-xl font-bold text-gray-800 mt-1">{{ pkr($market['total_value']) }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase">Total Collected</p>
            <p class="text-xl font-bold text-green-600 mt-1">{{ pkr($market['total_collected']) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Invoice History</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Invoice</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Trip</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Value</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoices as $inv)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5">
                            <a href="{{ route('invoices.show', $inv['id']) }}" class="font-mono text-xs text-blue-600 hover:underline font-medium">{{ $inv['invoice_number'] }}</a>
                        </td>
                        <td class="px-4 py-2.5 text-xs text-gray-500">{{ $inv['date'] }}</td>
                        <td class="px-4 py-2.5">
                            <a href="{{ route('trips.show', $inv['trip_db_id']) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $inv['trip_id'] }}</a>
                        </td>
                        <td class="px-4 py-2.5 text-right font-medium text-gray-800">{{ pkr($inv['value']) }}</td>
                        <td class="px-4 py-2.5"><x-status-badge :status="$inv['status']"/></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Market Ledger</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Reference</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Debit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Credit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($ledgerEntries as $e)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5 text-xs text-gray-500">{{ $e['date'] }}</td>
                        <td class="px-4 py-2.5 font-mono text-xs text-gray-600">{{ $e['reference'] }}</td>
                        <td class="px-4 py-2.5 text-gray-700 text-xs">{{ $e['type'] }}</td>
                        <td class="px-4 py-2.5 text-right text-red-600 font-medium text-xs">{{ $e['debit'] > 0 ? pkr($e['debit']) : '—' }}</td>
                        <td class="px-4 py-2.5 text-right text-green-600 font-medium text-xs">{{ $e['credit'] > 0 ? pkr($e['credit']) : '—' }}</td>
                        <td class="px-4 py-2.5 text-right font-bold text-gray-800 text-xs">{{ pkr($e['balance']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
