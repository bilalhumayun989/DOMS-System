@extends('layouts.app')
@php $pageTitle = $trip['trip_id']; @endphp

@section('content')
{{-- Trip Header --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h2 class="text-xl font-bold text-gray-800 font-mono">{{ $trip['trip_id'] }}</h2>
                <x-status-badge :status="$trip['status']"/>
            </div>
            <p class="text-sm text-gray-500">Source DLF: <span class="font-medium text-gray-700">{{ $trip['source_dlf'] ?? '—' }}</span></p>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold text-gray-800">{{ pkr($trip['load_value']) }}</p>
            <p class="text-xs text-gray-500">Load Value</p>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-5 pt-5 border-t border-gray-100">
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Date</p>
            <p class="font-medium text-gray-800 mt-1">{{ $trip['date'] }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Deliveryman</p>
            <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}" class="font-medium text-blue-600 hover:underline mt-1 block">{{ $trip['deliveryman']['name'] }}</a>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Vehicle</p>
            <p class="font-medium text-gray-800 mt-1 text-sm">{{ $trip['vehicle'] }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Market / Area</p>
            <p class="font-medium text-gray-800 mt-1">{{ $trip['market_area'] }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Expected Cash</p>
            <p class="font-medium text-gray-800 mt-1">{{ pkr($trip['expected_cash']) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Delivery Results --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Delivery Results</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Invoice</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Value</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoices as $inv)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5">
                        <a href="{{ route('invoices.show', $inv['id']) }}" class="text-blue-600 hover:underline font-mono text-xs font-medium">{{ $inv['invoice_number'] }}</a>
                    </td>
                    <td class="px-4 py-2.5 text-gray-700">{{ $inv['customer'] }}</td>
                    <td class="px-4 py-2.5 text-right font-medium text-gray-800">{{ pkr($inv['value']) }}</td>
                    <td class="px-4 py-2.5"><x-status-badge :status="$inv['status']"/></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Collections --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Collections</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Method</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($collections as $col)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-700">{{ $col['customer'] }}</td>
                    <td class="px-4 py-2.5 text-right font-bold text-green-600">{{ pkr($col['amount']) }}</td>
                    <td class="px-4 py-2.5">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $col['method'] === 'Cash' ? 'bg-green-100 text-green-700' : ($col['method'] === 'Cheque' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">{{ $col['method'] }}</span>
                    </td>
                    <td class="px-4 py-2.5 text-xs text-gray-500">{{ $col['collected_at'] }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400 text-sm">No collections yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Returns --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Returns</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">SKU</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Qty</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Reason</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($returns as $ret)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 font-mono text-xs text-gray-600">{{ $ret['sku'] }}</td>
                    <td class="px-4 py-2.5 text-gray-700">{{ $ret['product'] }}</td>
                    <td class="px-4 py-2.5 text-right font-medium text-gray-800">{{ $ret['qty'] }}</td>
                    <td class="px-4 py-2.5"><span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-full">{{ $ret['reason'] }}</span></td>
                    <td class="px-4 py-2.5 text-xs text-gray-500">{{ $ret['date'] }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400 text-sm">No returns</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Settlement Summary --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Settlement Summary</h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-600">Expected Cash</span>
                <span class="font-bold text-gray-800">{{ pkr($settlement['expected_cash']) }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-600">Collected Amount</span>
                <span class="font-bold text-green-600">{{ pkr($settlement['collected_amount']) }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-sm text-gray-600">Shortage Amount</span>
                <span class="font-bold text-red-600">{{ pkr($settlement['shortage_amount']) }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-sm text-gray-600">Classification</span>
                <x-status-badge :status="$settlement['shortage_classification']"/>
            </div>
        </div>
    </div>
</div>
@endsection
