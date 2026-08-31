@extends('layouts.app')
@php $pageTitle = 'Returns'; @endphp

@section('content')
<div
    x-data="{
        open: false,
        mode: 'edit',
        selected: null,
        openEdit(r) { this.mode='edit'; this.selected=r; this.open=true; },
        openDelete(r) { this.mode='delete'; this.selected=r; this.open=true; },
        close() { this.open=false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-3" style="border-bottom: 1px solid #f1f5f9;">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Returns</h2>
                <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($returns) }} records</p>
            </div>
            <div class="flex gap-2">
                @foreach(['All', 'Pending', 'Restocked'] as $opt)
                <a href="{{ route('returns.index', ['status' => $opt]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
                   style="{{ $filter === $opt ? 'background:#3b82f6;color:#ffffff;' : 'background:#f1f5f9;color:#475569;' }}">
                    {{ $opt }}
                </a>
                @endforeach
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: #fafbfc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Return ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Trip</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Product</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $ret)
                    <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-gray-600">{{ $ret['return_ref'] }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $ret['date'] }}</td>
                        <td class="px-6 py-4"><a href="{{ route('trips.show', $ret['trip_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $ret['trip_display'] }}</a></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $ret['deliveryman'] }}</td>
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-600">{{ $ret['sku'] }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $ret['product'] }}</td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-gray-800">{{ $ret['qty_returned'] }}</td>
                        <td class="px-6 py-4"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-red-50 text-red-600">{{ $ret['reason'] }}</span></td>
                        <td class="px-6 py-4"><x-status-badge :status="$ret['status']"/></td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1">
                                <button
                                    @click="openEdit({{ json_encode($ret) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background:#f0fdf4;color:#16a34a;"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($ret) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background:#fff1f2;color:#ef4444;"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Backdrop --}}
    <div
        x-show="open"
        x-cloak
        x-transition
        @click="close()"
        style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:50;"
    ></div>

    {{-- Edit Modal --}}
    <div
        x-show="open && mode === 'edit'"
        x-cloak
        x-transition
        @click.stop
        style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:0.75rem;box-shadow:0 4px 24px rgba(0,0,0,0.12);width:100%;max-width:28rem;padding:1.5rem;z-index:51;"
    >
        <h3 class="text-base font-bold text-gray-900 mb-4">Edit Return</h3>
        <form method="POST" :action="'{{ url('returns') }}/' + selected?.id">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Return ID</label>
                    <input
                        type="text"
                        name="return_ref"
                        readonly
                        :value="selected?.return_ref ?? ''"
                        class="w-full text-sm"
                        style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;color:#94a3b8;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Trip ID</label>
                    <input
                        type="text"
                        name="trip_display"
                        :value="selected?.trip_display ?? ''"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Deliveryman</label>
                    <input
                        type="text"
                        name="deliveryman"
                        :value="selected?.deliveryman ?? ''"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">SKU</label>
                    <input
                        type="text"
                        name="sku"
                        :value="selected?.sku ?? ''"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Product Name</label>
                    <input
                        type="text"
                        name="product"
                        :value="selected?.product ?? ''"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Qty Returned</label>
                    <input
                        type="number"
                        name="qty_returned"
                        min="1"
                        :value="selected?.qty_returned ?? 1"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Reason Code</label>
                    <select
                        name="reason"
                        x-init="$watch('selected', v => { if (v) $el.value = v.reason ?? 'REFUSED'; })"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                        <option value="REFUSED">REFUSED</option>
                        <option value="DAMAGED">DAMAGED</option>
                        <option value="EXPIRED">EXPIRED</option>
                        <option value="EXCESS">EXCESS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Status</label>
                    <select
                        name="status"
                        x-init="$watch('selected', v => { if (v) $el.value = v.status ?? 'Pending'; })"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                        <option value="Pending">Pending</option>
                        <option value="Restocked">Restocked</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button
                    type="button"
                    @click="close()"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#f1f5f9;color:#475569;"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#3b82f6;color:#ffffff;"
                >
                    Save
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-show="open && mode === 'delete'"
        x-cloak
        x-transition
        @click.stop
        style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:0.75rem;box-shadow:0 4px 24px rgba(0,0,0,0.12);width:100%;max-width:28rem;padding:1.5rem;z-index:51;"
    >
        <h3 class="text-base font-bold text-gray-900 mb-3">Delete Return</h3>
        <p class="text-sm text-gray-600 mb-5">
            Are you sure you want to delete Return <span class="font-bold text-gray-900" x-text="selected?.return_ref"></span>?
        </p>
        <form method="POST" :action="'{{ url('returns') }}/' + selected?.id">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="close()"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#f1f5f9;color:#475569;"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#ef4444;color:#ffffff;"
                >
                    Confirm Delete
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
