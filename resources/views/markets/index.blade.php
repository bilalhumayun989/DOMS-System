@extends('layouts.app')
@php $pageTitle = 'Markets'; @endphp

@section('content')
<div
    x-data="{
        open: false,
        mode: 'create',
        selected: null,
        openCreate() { this.mode = 'create'; this.selected = null; this.open = true; },
        openEdit(r)   { this.mode = 'edit';   this.selected = r;    this.open = true; },
        openDelete(r) { this.mode = 'delete'; this.selected = r;    this.open = true; },
        close()       { this.open = false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>

    {{-- ── Page card ── --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">

        {{-- Header --}}
        <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
            <div>
                <h2 class="text-lg font-bold text-gray-900">All Markets</h2>
                <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($markets) }} markets</p>
            </div>
            <button
                @click="openCreate()"
                class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2 rounded-lg text-white"
                style="background: #3b82f6;"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Market
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: #fafbfc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Market Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Area</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Invoices</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Total Value</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Collected</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Outstanding</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($markets as $market)
                    <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $market['name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $market['area'] }}</td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-gray-800">{{ $market['total_invoices'] }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ pkr($market['total_value']) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold" style="color: #16a34a;">{{ pkr($market['total_collected']) }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($market['outstanding_balance'] > 0)
                                <span class="text-sm font-bold" style="color: #ef4444;">{{ pkr($market['outstanding_balance']) }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('markets.show', $market['id']) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                   style="background: #eff6ff; color: #3b82f6;">
                                    View →
                                </a>
                                <button
                                    @click="openEdit({{ json_encode($market) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background: #f0fdf4; color: #16a34a;"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($market) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background: #fff1f2; color: #ef4444;"
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

    {{-- ── Backdrop ── --}}
    <div
        x-show="open"
        x-cloak
        x-transition.duration.200ms
        @click="close()"
        style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50;"
    ></div>

    {{-- ── Modal panel ── --}}
    <div
        x-show="open"
        x-cloak
        x-transition.duration.200ms
        @click.stop
        style="position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%); background: #fff; border-radius: 0.75rem; box-shadow: 0 4px 24px rgba(0,0,0,0.12); width: 100%; max-width: 28rem; padding: 1.5rem; z-index: 51;"
    >

        {{-- ── Create / Edit form ── --}}
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4"
                    x-text="mode === 'create' ? 'Add Market' : 'Edit Market'"></h3>

                <form @submit.prevent="close()">
                    <div class="space-y-3">

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color: #64748b;">Market Name <span style="color:#ef4444;">*</span></label>
                            <input
                                type="text"
                                required
                                :value="selected?.name ?? ''"
                                placeholder="e.g. Gulshan-e-Iqbal Market"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color: #64748b;">Area / Region <span style="color:#ef4444;">*</span></label>
                            <input
                                type="text"
                                required
                                :value="selected?.area ?? ''"
                                placeholder="e.g. Gulshan-e-Iqbal"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color: #64748b;">Contact Person</label>
                            <input
                                type="text"
                                :value="selected?.contact_person ?? ''"
                                placeholder="e.g. Ali Hassan"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color: #64748b;">Contact Phone</label>
                            <input
                                type="text"
                                :value="selected?.contact_phone ?? ''"
                                placeholder="e.g. 0300-0000000"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color: #64748b;">Outstanding Balance</label>
                            <input
                                type="number"
                                min="0"
                                :value="selected?.outstanding_balance ?? 0"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                    </div>

                    <div class="flex justify-end gap-2 mt-5">
                        <button
                            type="button"
                            @click="close()"
                            class="text-xs font-semibold px-4 py-2 rounded-lg"
                            style="background: #f1f5f9; color: #64748b;"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="text-xs font-semibold px-4 py-2 rounded-lg text-white"
                            style="background: #3b82f6;"
                        >Save</button>
                    </div>
                </form>
            </div>
        </template>

        {{-- ── Delete confirmation ── --}}
        <template x-if="mode === 'delete'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-3">Delete Market</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Are you sure you want to delete <span class="font-semibold text-gray-800" x-text="selected?.name"></span>?
                    This action cannot be undone.
                </p>
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="close()"
                        class="text-xs font-semibold px-4 py-2 rounded-lg"
                        style="background: #f1f5f9; color: #64748b;"
                    >Cancel</button>
                    <button
                        type="button"
                        @click="close()"
                        class="text-xs font-semibold px-4 py-2 rounded-lg text-white"
                        style="background: #ef4444;"
                    >Confirm Delete</button>
                </div>
            </div>
        </template>

    </div>{{-- /modal panel --}}

</div>{{-- /x-data --}}
@endsection
