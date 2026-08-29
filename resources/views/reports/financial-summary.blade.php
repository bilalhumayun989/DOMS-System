@extends('layouts.app')
@php $pageTitle = 'Financial Summary'; @endphp

@section('content')
<div class="mb-5"><a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-500 hover:text-blue-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Reports</a></div>
<div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center gap-2" style="border-bottom:1px solid #f1f5f9;">
        <div class="w-1 h-5 rounded-full bg-green-500"></div>
        <h2 class="text-lg font-bold text-gray-900">Financial Summary — Current Month</h2>
    </div>
    <table class="w-full">
        <thead><tr style="background:#fafbfc;">
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Sales</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Collections</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Shortages</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Returns</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Expenses</th>
        </tr></thead>
        <tbody>
            @foreach($rows as $row)
            <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color:#f1f5f9;">
                <td class="px-6 py-4 text-sm font-semibold text-gray-700">{{ $row['date'] }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ pkr($row['sales']) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($row['collections']) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold {{ $row['shortages']>0?'text-red-500':'text-gray-300' }}">{{ $row['shortages']>0?pkr($row['shortages']):'—' }}</td>
                <td class="px-6 py-4 text-right text-sm font-semibold" style="color:#b45309;">{{ pkr($row['returns']) }}</td>
                <td class="px-6 py-4 text-right text-sm text-gray-600">{{ pkr($row['expenses']) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#fafbfc;border-top:2px solid #e2e8f0;">
                <td class="px-6 py-4 text-sm font-bold text-gray-700">Totals</td>
                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ pkr(array_sum(array_column($rows,'sales'))) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr(array_sum(array_column($rows,'collections'))) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold" style="color:#ef4444;">{{ pkr(array_sum(array_column($rows,'shortages'))) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold" style="color:#b45309;">{{ pkr(array_sum(array_column($rows,'returns'))) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold text-gray-600">{{ pkr(array_sum(array_column($rows,'expenses'))) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
