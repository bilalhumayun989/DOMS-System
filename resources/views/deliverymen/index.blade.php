@extends('layouts.app')
@php $pageTitle = 'Deliverymen'; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-lg">All Deliverymen</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ count($deliverymen) }} drivers registered</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Employee ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Phone</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Trips</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Active Today</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Collected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Shortages</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($deliverymen as $dm)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $dm['name'] }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $dm['employee_id'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $dm['phone'] }}</td>
                    <td class="px-4 py-3 text-center font-medium text-gray-800">{{ $dm['total_trips'] }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold {{ $dm['active_trips'] > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $dm['active_trips'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($dm['total_collected']) }}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="{{ $dm['outstanding_shortages'] > 0 ? 'text-red-600 font-bold' : 'text-gray-400' }}">
                            {{ $dm['outstanding_shortages'] > 0 ? pkr($dm['outstanding_shortages']) : '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('deliverymen.show', $dm['id']) }}" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-medium transition-colors">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
