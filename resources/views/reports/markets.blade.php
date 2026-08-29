@extends('layouts.app')
@php $pageTitle = 'Market Report'; @endphp

@section('content')
<div class="mb-5"><a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-500 hover:text-blue-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Reports</a></div>
<div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center gap-2" style="border-bottom:1px solid #f1f5f9;">
        <div class="w-1 h-5 rounded-full bg-teal-500"></div>
        <h2 class="text-lg font-bold text-gray-900">Market / Customer Report</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background:#fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Market</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Invoices</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Total Sales</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Collected</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Outstanding</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">0-30d</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">31-60d</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">60+d</th>
            </tr></thead>
            <tbody>
                @foreach($rows as $row)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color:#f1f5f9;">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $row['name'] }}</td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-700">{{ $row['total_invoices'] }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ pkr($row['total_sales']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($row['total_collected']) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold" style="color:#ef4444;">{{ pkr($row['outstanding']) }}</td>
                    <td class="px-6 py-4 text-right text-sm text-gray-600">{{ pkr($row['aging_0_30']) }}</td>
                    <td class="px-6 py-4 text-right text-sm" style="color:#b45309;">{{ pkr($row['aging_31_60']) }}</td>
                    <td class="px-6 py-4 text-right text-sm" style="color:#ef4444;">{{ pkr($row['aging_60_plus']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
