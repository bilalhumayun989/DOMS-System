@extends('layouts.app')
@php $pageTitle = $invoice['invoice_number']; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h2 class="text-xl font-bold text-gray-800 font-mono">{{ $invoice['invoice_number'] }}</h2>
                <x-status-badge :status="$invoice['status']"/>
            </div>
            <p class="text-sm text-gray-600">Customer: <span class="font-medium text-gray-800">{{ $invoice['customer'] }}</span></p>
            <p class="text-sm text-gray-600 mt-1">Trip:
                <a href="{{ route('trips.show', $invoice['trip_id']) }}" class="font-mono text-xs text-blue-600 hover:underline font-medium">{{ $invoice['trip_id_display'] }}</a>
            </p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 uppercase">Invoice Date</p>
            <p class="font-bold text-gray-800 mt-1">{{ $invoice['date'] }}</p>
            <p class="text-xs text-gray-500 uppercase mt-3">Total Value</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ pkr($invoice['total_value']) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Line Items</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">SKU</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Ordered</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Delivered</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Unit Price</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Line Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($lineItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5 font-mono text-xs text-gray-600">{{ $item['sku'] }}</td>
                        <td class="px-4 py-2.5 font-medium text-gray-800">{{ $item['product'] }}</td>
                        <td class="px-4 py-2.5 text-center text-gray-700">{{ $item['ordered_qty'] }}</td>
                        <td class="px-4 py-2.5 text-center font-medium {{ $item['delivered_qty'] < $item['ordered_qty'] ? 'text-amber-600' : 'text-green-600' }}">{{ $item['delivered_qty'] }}</td>
                        <td class="px-4 py-2.5 text-right text-gray-700">{{ pkr($item['unit_price']) }}</td>
                        <td class="px-4 py-2.5 text-right font-bold text-gray-800">{{ pkr($item['line_total']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Invoice Total:</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900 text-base">{{ pkr($invoice['total_value']) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-semibold text-gray-800">Payments Received</h3></div>
        <div class="divide-y divide-gray-100">
            @forelse($collections as $col)
            <div class="px-5 py-4">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-green-600">{{ pkr($col['amount']) }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $col['method'] === 'Cash' ? 'bg-green-100 text-green-700' : ($col['method'] === 'Cheque' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">{{ $col['method'] }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Ref: {{ $col['ref'] }} · {{ $col['date'] }}</p>
            </div>
            @empty
            <div class="px-5 py-4 text-sm text-gray-400 text-center">No payments recorded</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
