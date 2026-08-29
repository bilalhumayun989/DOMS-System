@extends('layouts.app')
@php $pageTitle = 'Collections'; @endphp

@section('content')
{{-- Daily Total --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6 border-l-4 border-green-400">
    <p class="text-xs text-gray-500 uppercase tracking-wide">Daily Total Collected</p>
    <p class="text-3xl font-bold text-green-600 mt-1">{{ pkr($dailyTotal) }}</p>
    <p class="text-xs text-gray-400 mt-1">{{ count($collections) }} collection records shown</p>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h2 class="font-semibold text-gray-800 text-lg">Collections</h2>
        <div class="flex gap-2">
            @foreach(['All', 'Cash', 'Cheque', 'Transfer'] as $option)
            <a href="{{ route('collections.index', ['method' => $option]) }}"
               class="px-4 py-1.5 rounded-full text-xs font-medium transition-colors {{ $methodFilter === $option ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $option }}
            </a>
            @endforeach
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ref</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trip</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Method</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deliveryman</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($collections as $col)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $col['collection_ref'] }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $col['date'] }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('markets.show', $col['market_id']) }}" class="text-blue-600 hover:underline font-medium">{{ $col['customer'] }}</a>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('invoices.show', $col['invoice_id']) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $col['invoice_number'] }}</a>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('trips.show', $col['trip_id']) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $col['trip_display'] }}</a>
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">{{ pkr($col['amount']) }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $col['method'] === 'Cash' ? 'bg-green-100 text-green-700' : ($col['method'] === 'Cheque' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">{{ $col['method'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $col['deliveryman'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
