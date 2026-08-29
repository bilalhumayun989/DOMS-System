@extends('layouts.app')
@php $pageTitle = 'Invoices'; @endphp

@section('content')
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 class="text-lg font-bold text-gray-900">All Invoices</h2>
            <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($invoices) }} invoices</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Invoice #</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Date</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Value</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Status</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;"></th>
            </tr></thead>
            <tbody>
                @foreach($invoices as $inv)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4"><span class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $inv['invoice_number'] }}</span></td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $inv['customer'] }}</td>
                    <td class="px-6 py-4"><a href="{{ route('trips.show',$inv['trip_id']) }}" class="font-mono text-xs font-semibold" style="color:#3b82f6;">{{ $inv['trip_id_display'] }}</a></td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $inv['date'] }}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ pkr($inv['total_value']) }}</td>
                    <td class="px-6 py-4"><x-status-badge :status="$inv['status']"/></td>
                    <td class="px-6 py-4 text-center"><a href="{{ route('invoices.show',$inv['id']) }}" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:#eff6ff;color:#3b82f6;">View →</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
