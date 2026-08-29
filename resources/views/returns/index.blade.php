@extends('layouts.app')
@php $pageTitle = 'Returns'; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-semibold text-gray-800 text-lg">Returns</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ count($returns) }} records</p>
        </div>
        <div class="flex gap-2">
            @foreach(['All', 'Pending', 'Restocked'] as $option)
            <a href="{{ route('returns.index', ['status' => $option]) }}"
               class="px-4 py-1.5 rounded-full text-xs font-medium transition-colors {{ $filter === $option ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $option }}
            </a>
            @endforeach
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Return ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trip</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deliveryman</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">SKU</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Reason</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($returns as $ret)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600 font-medium">{{ $ret['return_ref'] }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $ret['date'] }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('trips.show', $ret['trip_id']) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $ret['trip_display'] }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $ret['deliveryman'] }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $ret['sku'] }}</td>
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $ret['product'] }}</td>
                    <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $ret['qty_returned'] }}</td>
                    <td class="px-4 py-3"><span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-full">{{ $ret['reason'] }}</span></td>
                    <td class="px-4 py-3"><x-status-badge :status="$ret['status']"/></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
