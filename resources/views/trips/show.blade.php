@extends('layouts.app')
@php $pageTitle = $trip['trip_id']; @endphp

@section('content')

{{-- Trip Header Card --}}
<div class="rounded-2xl p-6 mb-5" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="flex items-start justify-between flex-wrap gap-4 mb-5">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="font-mono text-xl font-bold text-gray-900">{{ $trip['trip_id'] }}</span>
                <x-status-badge :status="$trip['status']"/>
            </div>
            <p class="text-sm text-gray-500">DLF: <span class="font-semibold text-gray-700">{{ $trip['source_dlf'] ?? '—' }}</span></p>
        </div>
        <div class="text-right px-5 py-3 rounded-2xl" style="background: #f8fafc;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: #94a3b8;">Load Value</p>
            <p class="text-2xl font-bold text-gray-900">{{ pkr($trip['load_value']) }}</p>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-5" style="border-top: 1px solid #f1f5f9;">
        @foreach([['Date',$trip['date'],null,null],['Market',$trip['market_area'],null,null],['Vehicle',$trip['vehicle'],null,null],['Expected Cash',pkr($trip['expected_cash']),null,null]] as [$lbl,$val])
        <div class="px-4 py-3 rounded-xl" style="background: #fafbfc;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: #94a3b8;">{{ $lbl }}</p>
            <p class="text-sm font-bold text-gray-800">{{ $val }}</p>
        </div>
        @endforeach
    </div>
    <div class="mt-3 px-4 py-3 rounded-xl" style="background: #eff6ff;">
        <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color: #93c5fd;">Deliveryman</p>
        <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}"
           class="text-sm font-bold text-blue-600 hover:text-blue-800">{{ $trip['deliveryman']['name'] }} →</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
    {{-- Delivery Results --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-blue-500"></div>
            <h3 class="font-bold text-gray-800">Delivery Results</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Invoice</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Customer</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Value</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Result</th>
            </tr></thead>
            <tbody>
                @foreach($invoices as $inv)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3">
                        <a href="{{ route('invoices.show', $inv['id']) }}" class="font-mono text-xs font-bold" style="color: #3b82f6;">{{ $inv['invoice_number'] }}</a>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $inv['customer'] }}</td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-gray-800">{{ pkr($inv['value']) }}</td>
                    <td class="px-5 py-3"><x-status-badge :status="$inv['status']"/></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Collections --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-green-500"></div>
            <h3 class="font-bold text-gray-800">Collections</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Customer</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Amount</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Method</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Time</th>
            </tr></thead>
            <tbody>
                @forelse($collections as $col)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $col['customer'] }}</td>
                    <td class="px-5 py-3 text-right text-sm font-bold" style="color: #16a34a;">{{ pkr($col['amount']) }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $col['method']==='Cash'?'bg-green-50 text-green-600':($col['method']==='Cheque'?'bg-blue-50 text-blue-600':'bg-purple-50 text-purple-600') }}">{{ $col['method'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $col['collected_at'] }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-6 text-center text-sm text-gray-400">No collections yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    {{-- Returns --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-4 flex items-center gap-2" style="border-bottom: 1px solid #f1f5f9;">
            <div class="w-1 h-5 rounded-full bg-amber-400"></div>
            <h3 class="font-bold text-gray-800">Returns</h3>
        </div>
        <table class="w-full">
            <thead><tr style="background: #fafbfc;">
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">SKU</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Product</th>
                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Qty</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Reason</th>
            </tr></thead>
            <tbody>
                @forelse($returns as $ret)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-5 py-3 font-mono text-xs font-semibold text-gray-600">{{ $ret['sku'] }}</td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $ret['product'] }}</td>
                    <td class="px-5 py-3 text-center text-sm font-bold text-gray-800">{{ $ret['qty'] }}</td>
                    <td class="px-5 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-600">{{ $ret['reason'] }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-6 text-center text-sm text-gray-400">No returns</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Settlement Summary --}}
    <div class="rounded-2xl p-6" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-1 h-5 rounded-full bg-purple-500"></div>
            <h3 class="font-bold text-gray-800">Settlement Summary</h3>
        </div>
        <div class="space-y-3">
            @foreach([['Expected Cash',$settlement['expected_cash'],'#1d4ed8','#eff6ff'],['Collected',$settlement['collected_amount'],'#15803d','#f0fdf4'],['Shortage',$settlement['shortage_amount'],'#b91c1c','#fff1f2']] as [$lbl,$val,$clr,$bg])
            <div class="flex justify-between items-center px-4 py-3.5 rounded-xl" style="background: {{ $bg }};">
                <span class="text-sm font-semibold text-gray-700">{{ $lbl }}</span>
                <span class="text-base font-bold" style="color: {{ $clr }};">{{ pkr($val) }}</span>
            </div>
            @endforeach
            <div class="flex justify-between items-center px-4 py-3.5 rounded-xl" style="background: #fafbfc;">
                <span class="text-sm font-semibold text-gray-700">Classification</span>
                <x-status-badge :status="$settlement['shortage_classification']"/>
            </div>
        </div>
    </div>
</div>
@endsection
