@extends('layouts.app')
@php $pageTitle = 'Settlements'; @endphp

@section('content')
<div
    x-data="{
        open: false,
        mode: 'edit',
        selected: null,
        openCreate() { this.mode='create'; this.selected=null; this.open=true; },
        openEdit(r)   { this.mode='edit';   this.selected=r;    this.open=true; },
        openDelete(r) { this.mode='delete'; this.selected=r;    this.open=true; },
        close()       { this.open=false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>

    {{-- Table Card --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Settlements</h2>
                <p class="text-xs mt-0.5 font-medium" style="color:#94a3b8;">{{ count($settlements) }} records</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr style="background: #fafbfc;">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Ref</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Trip</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Expected</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Collected</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Shortage</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Classification</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Actions</th>
                </tr></thead>
                <tbody>
                    @foreach($settlements as $s)
                    <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">{{ $s['settlement_ref'] }}</td>
                        <td class="px-6 py-4"><a href="{{ route('trips.show',$s['trip_id']) }}" class="font-mono text-xs font-bold" style="color:#3b82f6;">{{ $s['trip_display'] }}</a></td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $s['deliveryman'] }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $s['date'] }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-800">{{ pkr($s['expected_cash']) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($s['collected_amount']) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold {{ $s['shortage_amount']>0?'text-red-500':'text-gray-300' }}">{{ $s['shortage_amount']>0?pkr($s['shortage_amount']):'—' }}</td>
                        <td class="px-6 py-4">@if($s['shortage_classification'])<x-status-badge :status="$s['shortage_classification']"/>@else<span class="text-gray-300 text-xs">—</span>@endif</td>
                        <td class="px-6 py-4"><x-status-badge :status="$s['status']"/></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button
                                    @click="openEdit({{ json_encode($s) }})"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                    style="background:#f0fdf4;color:#16a34a;">
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($s) }})"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                    style="background:#fff1f2;color:#ef4444;">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #fafbfc; border-top: 2px solid #e2e8f0;">
                        <td colspan="4" class="px-6 py-4 text-sm font-bold text-gray-700 text-right">Totals</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ pkr($totals['expected_cash']) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold" style="color:#16a34a;">{{ pkr($totals['collected_amount']) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold" style="color:#ef4444;">{{ pkr($totals['shortage_amount']) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
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

        {{-- Edit Form --}}
        <template x-if="mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4">Edit Settlement</h3>
                <form @submit.prevent="close()">
                    <div class="space-y-3">

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Settlement ID</label>
                            <input
                                type="text"
                                readonly
                                :value="selected?.settlement_ref ?? ''"
                                class="w-full text-sm"
                                style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Trip ID</label>
                            <input
                                type="text"
                                readonly
                                :value="selected?.trip_display ?? ''"
                                class="w-full text-sm"
                                style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Deliveryman</label>
                            <input
                                type="text"
                                :value="selected?.deliveryman ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Date</label>
                            <input
                                type="date"
                                :value="selected?.date ?? ''"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Expected Cash</label>
                            <input
                                type="number"
                                min="0"
                                :value="selected?.expected_cash ?? 0"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Collected Amount</label>
                            <input
                                type="number"
                                min="0"
                                :value="selected?.collected_amount ?? 0"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Shortage Amount</label>
                            <input
                                type="number"
                                min="0"
                                :value="selected?.shortage_amount ?? 0"
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Shortage Classification</label>
                            <select
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                                <option value="">None</option>
                                <template x-for="opt in ['Market Short','Deliveryman Short','Approved Write-Off','Pending Investigation']" :key="opt">
                                    <option :value="opt" :selected="selected?.shortage_classification === opt" x-text="opt"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Settlement Status</label>
                            <select
                                class="w-full text-sm"
                                style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;">
                                <template x-for="opt in ['Pending','Settled','Closed']" :key="opt">
                                    <option :value="opt" :selected="selected?.status === opt" x-text="opt"></option>
                                </template>
                            </select>
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
                <h3 class="text-base font-bold text-gray-900 mb-3">Delete Settlement</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Are you sure you want to delete Settlement
                    <span class="font-semibold text-gray-900" x-text="selected?.settlement_ref"></span>?
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
