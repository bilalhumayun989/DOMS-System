@extends('layouts.app')
@php $pageTitle = 'Stock Report'; @endphp

@section('content')
<div class="mb-5"><a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-500 hover:text-blue-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Reports</a></div>
<div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center gap-2" style="border-bottom:1px solid #f1f5f9;">
        <div class="w-1 h-5 rounded-full bg-amber-400"></div>
        <h2 class="text-lg font-bold text-gray-900">Stock Report by Category</h2>
    </div>
    <table class="w-full">
        <thead><tr style="background:#fafbfc;">
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Category</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Total SKUs</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">In Stock</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Low Stock</th>
            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Out of Stock</th>
            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Total Units</th>
        </tr></thead>
        <tbody>
            @foreach($rows as $row)
            <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color:#f1f5f9;">
                <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $row['category'] }}</td>
                <td class="px-6 py-4 text-center text-sm font-bold text-gray-700">{{ $row['total_skus'] }}</td>
                <td class="px-6 py-4 text-center"><span class="text-xs font-bold px-2 py-1 rounded-lg bg-green-50 text-green-600">{{ $row['in_stock'] }}</span></td>
                <td class="px-6 py-4 text-center"><span class="text-xs font-bold px-2 py-1 rounded-lg bg-amber-50 text-amber-600">{{ $row['low_stock'] }}</span></td>
                <td class="px-6 py-4 text-center"><span class="text-xs font-bold px-2 py-1 rounded-lg {{ $row['out_of_stock']>0?'bg-red-50 text-red-600':'bg-gray-50 text-gray-400' }}">{{ $row['out_of_stock'] }}</span></td>
                <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ number_format($row['total_units']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
