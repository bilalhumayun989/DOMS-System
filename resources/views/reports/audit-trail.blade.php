@extends('layouts.app')
@php $pageTitle = 'Audit Trail'; @endphp

@section('content')
<div class="mb-5"><a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-500 hover:text-blue-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Back to Reports</a></div>
<div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e8edf2;">
    <div class="px-6 py-5 flex items-center gap-2" style="border-bottom:1px solid #f1f5f9;">
        <div class="w-1 h-5 rounded-full bg-red-400"></div>
        <h2 class="text-lg font-bold text-gray-900">Audit Trail</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr style="background:#fafbfc;">
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Timestamp</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">User</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Action</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Entity</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Details</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Approved By</th>
            </tr></thead>
            <tbody>
                @foreach($rows as $row)
                <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color:#f1f5f9;">
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $row['timestamp'] }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $row['user'] }}</td>
                    <td class="px-6 py-4"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600">{{ $row['action'] }}</span></td>
                    <td class="px-6 py-4 font-mono text-xs font-semibold" style="color:#3b82f6;">{{ $row['entity'] }}</td>
                    <td class="px-6 py-4 text-xs text-gray-600 max-w-xs">{{ $row['details'] }}</td>
                    <td class="px-6 py-4 text-xs font-semibold {{ $row['approved_by']?'text-green-600':'text-gray-300' }}">{{ $row['approved_by']??'—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
