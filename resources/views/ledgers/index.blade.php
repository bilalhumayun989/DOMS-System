@extends('layouts.app')
@php $pageTitle = 'Ledgers'; @endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-blue-500"></div>
            <div>
                <h3 class="font-bold text-gray-800">Market Ledgers</h3>
                <p class="text-xs" style="color:#94a3b8;">Outstanding balances per customer</p>
            </div>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Customer</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Market</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Debit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Credit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Balance</th>
            </tr></thead>
            <tbody>
                @foreach($marketLedgers as $ml)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3.5"><a href="{{ route('markets.show',$ml['market_id']) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">{{ $ml['name'] }}</a></td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">{{ $ml['market'] }}</td>
                    <td class="px-5 py-3.5 text-right text-sm font-semibold" style="color:#ef4444;">{{ pkr($ml['total_debit']) }}</td>
                    <td class="px-5 py-3.5 text-right text-sm font-semibold" style="color:#16a34a;">{{ pkr($ml['total_credit']) }}</td>
                    <td class="px-5 py-3.5 text-right text-sm font-bold {{ $ml['balance']>0?'text-red-500':'text-green-600' }}">{{ pkr($ml['balance']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-purple-500"></div>
            <div>
                <h3 class="font-bold text-gray-800">Deliveryman Ledgers</h3>
                <p class="text-xs" style="color:#94a3b8;">Outstanding balances per driver</p>
            </div>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">EMP ID</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Debit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Credit</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Balance</th>
            </tr></thead>
            <tbody>
                @foreach($deliverymanLedgers as $dl)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3.5"><a href="{{ route('deliverymen.show',$dl['deliveryman_id']) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">{{ $dl['name'] }}</a></td>
                    <td class="px-5 py-3.5 font-mono text-xs font-semibold text-gray-500">{{ $dl['employee_id'] }}</td>
                    <td class="px-5 py-3.5 text-right text-sm font-semibold" style="color:#ef4444;">{{ pkr($dl['total_debit']) }}</td>
                    <td class="px-5 py-3.5 text-right text-sm font-semibold" style="color:#16a34a;">{{ pkr($dl['total_credit']) }}</td>
                    <td class="px-5 py-3.5 text-right text-sm font-bold {{ $dl['balance']>0?'text-red-500':'text-green-600' }}">{{ pkr($dl['balance']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
