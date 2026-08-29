@extends('layouts.app')
@php $pageTitle = 'Audit Trail'; @endphp

@section('content')
<div class="mb-4"><a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Reports</a></div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-800 text-lg">Audit Trail</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Timestamp</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Action</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Entity</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Details</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Approved By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-xs text-gray-500 font-mono">{{ $row['timestamp'] }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $row['user'] }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $row['action'] }}</span></td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $row['entity'] }}</td>
                    <td class="px-4 py-3 text-gray-700 text-xs">{{ $row['details'] }}</td>
                    <td class="px-4 py-3 text-xs {{ $row['approved_by'] ? 'text-green-600 font-medium' : 'text-gray-400' }}">{{ $row['approved_by'] ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
