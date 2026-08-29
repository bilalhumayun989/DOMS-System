@extends('layouts.app')
@php $pageTitle = 'Deliverymen'; @endphp

@section('content')
<div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 class="text-lg font-bold text-gray-900">All Deliverymen</h2>
            <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($deliverymen) }} drivers registered</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: #fafbfc;">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Employee ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Phone</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Total Trips</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Active</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Collected Today</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Shortages</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliverymen as $dm)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 style="background: linear-gradient(135deg,#3b82f6,#8b5cf6);">
                                {{ strtoupper(substr($dm['name'],0,1)) }}
                            </div>
                            <span class="text-sm font-semibold text-gray-800">{{ $dm['name'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">{{ $dm['employee_id'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $dm['phone'] }}</td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-800">{{ $dm['total_trips'] }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                            {{ $dm['active_trips']>0?'bg-green-100 text-green-700':'bg-gray-100 text-gray-400' }}">
                            {{ $dm['active_trips'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ pkr($dm['total_collected']) }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($dm['outstanding_shortages'] > 0)
                        <span class="text-sm font-bold" style="color: #ef4444;">{{ pkr($dm['outstanding_shortages']) }}</span>
                        @else
                        <span class="text-sm text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('deliverymen.show',$dm['id']) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                           style="background: #eff6ff; color: #3b82f6;">View →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
