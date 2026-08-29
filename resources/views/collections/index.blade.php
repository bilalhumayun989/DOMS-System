@extends('layouts.app')
@php $pageTitle = 'Collections'; @endphp

@section('content')
{{-- Daily Total Banner --}}
<div class="rounded-2xl p-6 mb-5 flex items-center justify-between flex-wrap gap-4"
     style="background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #bbf7d0;">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#86efac;">Total Collected Today</p>
        <p class="text-3xl font-bold" style="color:#15803d;">{{ pkr($dailyTotal) }}</p>
    </div>
    <div class="text-right">
        <p class="text-xs font-medium" style="color:#4ade80;">{{ count($collections) }} collection records</p>
    </div>
</div>

<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-3" style="border-bottom: 1px solid #f1f5f9;">
        <h2 class="text-lg font-bold text-gray-900">Collections</h2>
        <div class="flex gap-2">
            @foreach(['All','Cash','Cheque','Transfer'] as $opt)
            <a href="{{ route('collections.index',['method'=>$opt]) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
               style="{{ $methodFilter===$opt ? 'background:#3b82f6;color:#ffffff;' : 'background:#f1f5f9;color:#475569;' }}">
                {{ $opt }}
            </a>
            @endforeach
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Ref</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Invoice</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Trip</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Method</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Driver</th>
            </tr></thead>
            <tbody>
                @foreach($collections as $col)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">{{ $col['collection_ref'] }}</td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $col['date'] }}</td>
                    <td class="px-6 py-4"><a href="{{ route('markets.show',$col['market_id']) }}" class="text-sm font-semibold text-gray-800 hover:text-blue-600">{{ $col['customer'] }}</a></td>
                    <td class="px-6 py-4"><a href="{{ route('invoices.show',$col['invoice_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $col['invoice_number'] }}</a></td>
                    <td class="px-6 py-4"><a href="{{ route('trips.show',$col['trip_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $col['trip_display'] }}</a></td>
                    <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($col['amount']) }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $col['method']==='Cash'?'bg-green-50 text-green-600':($col['method']==='Cheque'?'bg-blue-50 text-blue-600':'bg-purple-50 text-purple-600') }}">{{ $col['method'] }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $col['deliveryman'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
