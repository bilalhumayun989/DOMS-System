@extends('layouts.app')
@php $pageTitle = 'Markets'; @endphp

@section('content')
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 class="text-lg font-bold text-gray-900">All Markets</h2>
            <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($markets) }} markets</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Market Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Area</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Invoices</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Total Value</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Collected</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Outstanding</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;"></th>
            </tr></thead>
            <tbody>
                @foreach($markets as $market)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $market['name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $market['area'] }}</td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-800">{{ $market['total_invoices'] }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ pkr($market['total_value']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-semibold" style="color: #16a34a;">{{ pkr($market['total_collected']) }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($market['outstanding_balance'] > 0)
                        <span class="text-sm font-bold" style="color: #ef4444;">{{ pkr($market['outstanding_balance']) }}</span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('markets.show',$market['id']) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                           style="background: #eff6ff; color: #3b82f6;">View →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
