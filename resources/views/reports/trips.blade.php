@extends('layouts.app')
@php $pageTitle = 'Trip Report'; @endphp

@section('content')
<div class="mb-5">
    <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-500 hover:text-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Reports
    </a>
</div>
<div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center gap-2" style="border-bottom:1px solid #f1f5f9;">
        <div class="w-1 h-5 rounded-full bg-blue-500"></div>
        <h2 class="text-lg font-bold text-gray-900">{{ $selectedDay ? 'Daily Sheet — Day '.$selectedDay : 'Trip Report — Current Month' }}</h2>
    </div>
    <table class="w-full">
        <thead><tr style="background:#fafbfc;">
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Trips</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Load Value</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Collected</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Shortage</th>
        </tr></thead>
        <tbody>
            @if(count($rows) === 0)
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">No trip sheet entries for this day.</td>
            </tr>
            @endif
            @foreach($rows as $row)
            <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color:#f1f5f9;">
                <td class="px-6 py-4 text-sm font-semibold text-gray-700">{{ $row['date'] }}</td>
                <td class="px-6 py-4 text-center text-sm font-bold text-gray-800">{{ $row['trips'] }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ pkr($row['load_value']) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($row['collected']) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold {{ $row['shortage']>0?'text-red-500':'text-gray-300' }}">{{ $row['shortage']>0?pkr($row['shortage']):'—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#fafbfc;border-top:2px solid #e2e8f0;">
                <td class="px-6 py-4 text-sm font-bold text-gray-700">Totals</td>
                <td class="px-6 py-4 text-center text-sm font-bold text-gray-900">{{ array_sum(array_column($rows,'trips')) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ pkr(array_sum(array_column($rows,'load_value'))) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr(array_sum(array_column($rows,'collected'))) }}</td>
                <td class="px-6 py-4 text-right text-sm font-bold" style="color:#ef4444;">{{ pkr(array_sum(array_column($rows,'shortage'))) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
