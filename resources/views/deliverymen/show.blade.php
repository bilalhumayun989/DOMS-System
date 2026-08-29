@extends('layouts.app')
@php $pageTitle = $deliveryman['name']; @endphp

@section('content')
{{-- Profile Header --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center gap-5">
        <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
            {{ strtoupper(substr($deliveryman['name'], 0, 1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-gray-800">{{ $deliveryman['name'] }}</h2>
            <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600">
                <span>🪪 {{ $deliveryman['employee_id'] }}</span>
                <span>📞 {{ $deliveryman['phone'] }}</span>
                <span>📅 Joined: {{ $deliveryman['joined_at'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    @foreach([['label'=>'Total Trips','value'=>$summary['total_trips'],'fmt'=>false],['label'=>'Value Delivered','value'=>$summary['total_value_delivered'],'fmt'=>true],['label'=>'Total Collected','value'=>$summary['total_collected'],'fmt'=>true],['label'=>'Total Shortages','value'=>$summary['total_shortages'],'fmt'=>true,'red'=>true],['label'=>'Ledger Balance','value'=>$summary['ledger_balance'],'fmt'=>true,'red'=>true]] as $stat)
    <div class="bg-white rounded-xl shadow-sm p-4 text-center border-b-4 {{ isset($stat['red']) && $stat['red'] && $stat['value'] > 0 ? 'border-red-400' : 'border-blue-400' }}">
        <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $stat['label'] }}</p>
        <p class="text-xl font-bold mt-1 {{ isset($stat['red']) && $stat['red'] && $stat['value'] > 0 ? 'text-red-600' : 'text-gray-800' }}">
            {{ $stat['fmt'] ? pkr($stat['value']) : $stat['value'] }}
        </p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Trip History --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Trip History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Trip ID</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Market</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Collected</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Shortage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($tripHistory as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5">
                            <a href="{{ route('trips.show', $t['id']) }}" class="font-mono text-xs text-blue-600 hover:underline font-medium">{{ $t['trip_id'] }}</a>
                        </td>
                        <td class="px-4 py-2.5 text-gray-600 text-xs">{{ $t['date'] }}</td>
                        <td class="px-4 py-2.5 text-gray-700">{{ $t['market_area'] }}</td>
                        <td class="px-4 py-2.5"><x-status-badge :status="$t['status']"/></td>
                        <td class="px-4 py-2.5 text-right font-medium text-green-600">{{ $t['collected'] > 0 ? pkr($t['collected']) : '—' }}</td>
                        <td class="px-4 py-2.5 text-right {{ $t['shortage'] > 0 ? 'text-red-600 font-bold' : 'text-gray-400' }}">{{ $t['shortage'] > 0 ? pkr($t['shortage']) : '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ledger --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Ledger Entries</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Trip</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Debit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Credit</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($ledgerEntries as $entry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5 text-xs text-gray-500">{{ $entry['date'] }}</td>
                        <td class="px-4 py-2.5 font-mono text-xs text-blue-600">{{ $entry['trip_id'] }}</td>
                        <td class="px-4 py-2.5 text-gray-700 text-xs">{{ $entry['type'] }}</td>
                        <td class="px-4 py-2.5 text-right text-red-600 font-medium text-xs">{{ $entry['debit'] > 0 ? pkr($entry['debit']) : '—' }}</td>
                        <td class="px-4 py-2.5 text-right text-green-600 font-medium text-xs">{{ $entry['credit'] > 0 ? pkr($entry['credit']) : '—' }}</td>
                        <td class="px-4 py-2.5 text-right font-bold text-gray-800 text-xs">{{ pkr($entry['balance']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
