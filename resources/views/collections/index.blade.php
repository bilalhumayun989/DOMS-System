@extends('layouts.app')
@php $pageTitle = 'Collections'; @endphp

@section('content')
<div
    x-data="{
        open: false,
        mode: 'create',
        selected: null,
        openCreate() { this.mode='create'; this.selected=null; this.open=true; },
        openEdit(r)   { this.mode='edit';   this.selected=r;    this.open=true; },
        openDelete(r) { this.mode='delete'; this.selected=r;    this.open=true; },
        close()       { this.open=false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>

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

    {{-- Table Card --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-3" style="border-bottom: 1px solid #f1f5f9;">
            <div class="flex items-center gap-3 flex-wrap">
                <h2 class="text-lg font-bold text-gray-900">Collections</h2>
                <button
                    @click="openCreate()"
                    class="text-xs font-semibold px-4 py-2 rounded-xl transition-colors"
                    style="background:#3b82f6;color:#ffffff;">
                    + Add Collection
                </button>
            </div>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Actions</th>
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
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button
                                    @click="openEdit({{ json_encode($col) }})"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                    style="background:#f0fdf4;color:#16a34a;">
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($col) }})"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                    style="background:#fff1f2;color:#ef4444;">
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
        style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:50;">
    </div>

    {{-- Modal Panel --}}
    <div
        x-show="open"
        x-cloak
        x-transition
        @click.stop
        style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:0.75rem;box-shadow:0 4px 24px rgba(0,0,0,0.12);width:100%;max-width:28rem;padding:1.5rem;z-index:51;">

        {{-- Create / Edit Form --}}
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4" x-text="mode === 'create' ? 'Add Collection' : 'Edit Collection'"></h3>
                <form @submit.prevent="close()">
                    <div class="space-y-3">

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Collection ID</label>
                            <input
                                type="text"
                                readonly
                                :value="selected?.collection_ref ?? 'COL-' + Date.now()"
                                class="w-full text-sm"
                                style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Date</label>
                            <input
                                type="date"
                                required
                                :value="selected?.date ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Customer / Market</label>
                            <input
                                type="text"
                                required
                                :value="selected?.customer ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Invoice Number</label>
                            <input
                                type="text"
                                required
                                :value="selected?.invoice_number ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Trip ID</label>
                            <input
                                type="text"
                                required
                                :value="selected?.trip_display ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Amount</label>
                            <input
                                type="number"
                                required
                                min="0"
                                :value="selected?.amount ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Method</label>
                            <select
                                required
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                                <option value="">Select method…</option>
                                <template x-for="opt in ['Cash','Cheque','Transfer']" :key="opt">
                                    <option :value="opt" :selected="selected?.method === opt" x-text="opt"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Deliveryman</label>
                            <input
                                type="text"
                                required
                                :value="selected?.deliveryman ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                    </div>
                    <div class="flex justify-end gap-2 mt-5">
                        <button
                            type="button"
                            @click="close()"
                            class="text-sm font-semibold px-5 py-2 rounded-lg"
                            style="background:#f1f5f9;color:#64748b;">
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="text-sm font-semibold px-5 py-2 rounded-lg"
                            style="background:#3b82f6;color:#fff;">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </template>

        {{-- Delete Confirmation --}}
        <template x-if="mode === 'delete'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-3">Delete Collection</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Are you sure you want to delete Collection
                    <span class="font-semibold text-gray-900" x-text="selected?.collection_ref"></span>?
                </p>
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="close()"
                        class="text-sm font-semibold px-5 py-2 rounded-lg"
                        style="background:#f1f5f9;color:#64748b;">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="close()"
                        class="text-sm font-semibold px-5 py-2 rounded-lg"
                        style="background:#ef4444;color:#fff;">
                        Confirm Delete
                    </button>
                </div>
            </div>
        </template>

    </div>

</div>
@endsection
