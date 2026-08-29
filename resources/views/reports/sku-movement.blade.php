@extends('layouts.app')
@php $pageTitle = 'SKU Movement Report'; @endphp

@section('content')
<div class="mb-5"><a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-500 hover:text-blue-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Reports</a></div>
<div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center gap-2" style="border-bottom:1px solid #f1f5f9;">
        <div class="w-1 h-5 rounded-full bg-purple-500"></div>
        <h2 class="text-lg font-bold text-gray-900">SKU Movement Report</h2>
    </div>
    <table class="w-full">
        <thead><tr style="background:#fafbfc;">
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">SKU</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Product</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Dispatched</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Returned</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Adjusted</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Net</th>
        </tr></thead>
        <tbody>
            @foreach($rows as $row)
            <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color:#f1f5f9;">
                <td class="px-6 py-4 text-xs text-gray-500">{{ $row['date'] }}</td>
                <td class="px-6 py-4 font-mono text-xs font-bold text-gray-700">{{ $row['sku'] }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $row['product'] }}</td>
                <td class="px-6 py-4 text-center text-sm font-semibold" style="color:#f97316;">{{ $row['dispatched'] }}</td>
                <td class="px-6 py-4 text-center text-sm font-semibold" style="color:#3b82f6;">{{ $row['returned']>0?$row['returned']:'—' }}</td>
                <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $row['adjusted']!=0?$row['adjusted']:'—' }}</td>
                <td class="px-6 py-4 text-center text-sm font-bold {{ $row['net_movement']<0?'text-red-500':'text-green-600' }}">{{ $row['net_movement'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
