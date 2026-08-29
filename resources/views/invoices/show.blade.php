@extends('layouts.app')
@php $pageTitle = $invoice['invoice_number']; @endphp

@section('content')
<div class="rounded-2xl p-6 mb-5" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="font-mono text-xl font-bold text-gray-900">{{ $invoice['invoice_number'] }}</span>
                <x-status-badge :status="$invoice['status']"/>
            </div>
            <p class="text-sm text-gray-600">Customer: <span class="font-semibold text-gray-800">{{ $invoice['customer'] }}</span></p>
            <p class="text-sm text-gray-600 mt-1">Trip: <a href="{{ route('trips.show',$invoice['trip_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $invoice['trip_id_display'] }}</a></p>
            <p class="text-sm text-gray-600 mt-1">Date: <span class="font-semibold text-gray-800">{{ $invoice['date'] }}</span></p>
        </div>
        <div class="px-6 py-4 rounded-2xl text-right" style="background: #eff6ff;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color:#93c5fd;">Invoice Total</p>
            <p class="text-3xl font-bold" style="color:#1d4ed8;">{{ pkr($invoice['total_value']) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-blue-500"></div>
            <h3 class="font-bold text-gray-800">Line Items</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">SKU</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Product</th>
                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Ordered</th>
                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Delivered</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Unit Price</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Total</th>
            </tr></thead>
            <tbody>
                @foreach($lineItems as $item)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3 font-mono text-xs font-semibold text-gray-600">{{ $item['sku'] }}</td>
                    <td class="px-5 py-3 text-sm font-semibold text-gray-800">{{ $item['product'] }}</td>
                    <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $item['ordered_qty'] }}</td>
                    <td class="px-5 py-3 text-center text-sm font-bold {{ $item['delivered_qty']<$item['ordered_qty']?'text-amber-500':'text-green-600' }}">{{ $item['delivered_qty'] }}</td>
                    <td class="px-5 py-3 text-right text-sm text-gray-600">{{ pkr($item['unit_price']) }}</td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-gray-900">{{ pkr($item['line_total']) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#fafbfc; border-top: 2px solid #e8edf2;">
                    <td colspan="5" class="px-5 py-3 text-right text-sm font-bold text-gray-700">Invoice Total</td>
                    <td class="px-5 py-3 text-right text-base font-bold" style="color:#1d4ed8;">{{ pkr($invoice['total_value']) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-green-500"></div>
            <h3 class="font-bold text-gray-800">Payments</h3>
        </div>
        <div class="divide-y" style="divide-color: #f1f5f9;">
            @forelse($collections as $col)
            <div class="px-6 py-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-lg font-bold" style="color:#16a34a;">{{ pkr($col['amount']) }}</span>
                    <span class="text-xs font-semibold px-2 py-1 rounded-lg {{ $col['method']==='Cash'?'bg-green-50 text-green-600':($col['method']==='Cheque'?'bg-blue-50 text-blue-600':'bg-purple-50 text-purple-600') }}">{{ $col['method'] }}</span>
                </div>
                <p class="text-xs text-gray-400">Ref: {{ $col['ref'] }} · {{ $col['date'] }}</p>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-sm text-gray-400">No payments recorded</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
