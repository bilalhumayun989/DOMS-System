@extends('layouts.app')
@php $pageTitle = 'Settlements'; @endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-lg">Settlements</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ count($settlements) }} settlement records</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ref</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Trip</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deliveryman</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Expected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Collected</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Shortage</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Classification</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($settlements as $s)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $s['settlement_ref'] }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('trips.show', $s['trip_id']) }}" class="font-mono text-xs text-blue-600 hover:underline font-medium">{{ $s['trip_display'] }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $s['deliveryman'] }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $s['date'] }}</td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">{{ pkr($s['expected_cash']) }}</td>
                    <td class="px-4 py-3 text-right font-medium text-green-600">{{ pkr($s['collected_amount']) }}</td>
                    <td class="px-4 py-3 text-right {{ $s['shortage_amount'] > 0 ? 'font-bold text-red-600' : 'text-gray-400' }}">{{ $s['shortage_amount'] > 0 ? pkr($s['shortage_amount']) : '—' }}</td>
                    <td class="px-4 py-3">
                        @if($s['shortage_classification'])
                        <x-status-badge :status="$s['shortage_classification']"/>
                        @else
                        <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3"><x-status-badge :status="$s['status']"/></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                <tr>
                    <td colspan="4" class="px-4 py-3 text-sm font-bold text-gray-700 text-right">Totals:</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800">{{ pkr($totals['expected_cash']) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">{{ pkr($totals['collected_amount']) }}</td>
                    <td class="px-4 py-3 text-right font-bold text-red-600">{{ pkr($totals['shortage_amount']) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
