@extends('layouts.app')
@php $pageTitle = 'Returns'; @endphp

@section('content')
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-3" style="border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Returns</h2>
            <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($returns) }} records</p>
        </div>
        <div class="flex gap-2">
            @foreach(['All','Pending','Restocked'] as $opt)
            <a href="{{ route('returns.index',['status'=>$opt]) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
               style="{{ $filter===$opt ? 'background:#3b82f6;color:#ffffff;' : 'background:#f1f5f9;color:#475569;' }}">
                {{ $opt }}
            </a>
            @endforeach
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Return ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Trip</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Driver</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Product</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Qty</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Reason</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Status</th>
            </tr></thead>
            <tbody>
                @foreach($returns as $ret)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-gray-600">{{ $ret['return_ref'] }}</td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $ret['date'] }}</td>
                    <td class="px-6 py-4"><a href="{{ route('trips.show',$ret['trip_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $ret['trip_display'] }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $ret['deliveryman'] }}</td>
                    <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-600">{{ $ret['sku'] }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $ret['product'] }}</td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-800">{{ $ret['qty_returned'] }}</td>
                    <td class="px-6 py-4"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-red-50 text-red-600">{{ $ret['reason'] }}</span></td>
                    <td class="px-6 py-4"><x-status-badge :status="$ret['status']"/></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
