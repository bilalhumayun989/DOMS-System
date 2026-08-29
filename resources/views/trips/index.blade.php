@extends('layouts.app')
@php $pageTitle = 'Trips'; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-gray-800 text-lg">All Trips</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ count($trips) }} trips total</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Trip ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Deliveryman</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Vehicle</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Market / Area</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Load Value</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Expected Cash</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($trips as $trip)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-blue-600 font-medium">{{ $trip['trip_id'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $trip['date'] }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}" class="text-blue-600 hover:underline font-medium">
                            {{ $trip['deliveryman']['name'] }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $trip['vehicle'] }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $trip['market_area'] }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$trip['status']"/></td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ $trip['load_value'] > 0 ? pkr($trip['load_value']) : '—' }}</td>
                    <td class="px-4 py-3 text-right text-gray-600">{{ $trip['expected_cash'] > 0 ? pkr($trip['expected_cash']) : '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('trips.show', $trip['id']) }}" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-medium transition-colors">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
