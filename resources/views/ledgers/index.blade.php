@extends('layouts.app')
@php $pageTitle = 'Ledgers'; @endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Market Ledgers --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Market Ledgers</h2>
            <p class="text-xs text-gray-500 mt-0.5">Outstanding balances per customer</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Market</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Debit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Credit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($marketLedgers as $ml)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <a href="{{ route('markets.show', $ml['market_id']) }}" class="text-blue-600 hover:underline">{{ $ml['name'] }}</a>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $ml['market'] }}</td>
                        <td class="px-4 py-3 text-right text-red-600 font-medium">{{ pkr($ml['total_debit']) }}</td>
                        <td class="px-4 py-3 text-right text-green-600 font-medium">{{ pkr($ml['total_credit']) }}</td>
                        <td class="px-4 py-3 text-right font-bold {{ $ml['balance'] > 0 ? 'text-red-600' : 'text-green-600' }}">{{ pkr($ml['balance']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Deliveryman Ledgers --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Deliveryman Ledgers</h2>
            <p class="text-xs text-gray-500 mt-0.5">Outstanding balances per driver</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">EMP ID</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Debit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Credit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($deliverymanLedgers as $dl)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <a href="{{ route('deliverymen.show', $dl['deliveryman_id']) }}" class="text-blue-600 hover:underline">{{ $dl['name'] }}</a>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $dl['employee_id'] }}</td>
                        <td class="px-4 py-3 text-right text-red-600 font-medium">{{ pkr($dl['total_debit']) }}</td>
                        <td class="px-4 py-3 text-right text-green-600 font-medium">{{ pkr($dl['total_credit']) }}</td>
                        <td class="px-4 py-3 text-right font-bold {{ $dl['balance'] > 0 ? 'text-red-600' : 'text-green-600' }}">{{ pkr($dl['balance']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
