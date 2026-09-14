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
    <div class="page-card">

        {{-- Header --}}
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">All Markets</h2>
                <p class="page-card-sub">{{ count($markets) }} markets</p>
            </div>
            <button @click="openCreate()" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Market
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Market Name</th>
                        <th class="text-left">Area</th>
                        <th class="text-center">Invoices</th>
                        <th class="text-right">Total Value</th>
                        <th class="text-right">Collected</th>
                        <th class="text-right">Outstanding</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($markets as $market)
                    <tr>
                        <td class="text-sm font-semibold text-gray-800">{{ $market['name'] }}</td>
                        <td class="text-sm text-gray-500">{{ $market['area'] }}</td>
                        <td class="text-center text-sm font-bold text-gray-800">{{ $market['total_invoices'] }}</td>
                        <td class="text-right text-sm font-bold text-gray-800">{{ pkr($market['total_value']) }}</td>
                        <td class="text-right text-sm font-semibold" style="color: #333333;">{{ pkr($market['total_collected']) }}</td>
                        <td class="text-right">
                            @if($market['outstanding_balance'] > 0)
                                <span class="text-sm font-bold" style="color: #222222;">{{ pkr($market['outstanding_balance']) }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('markets.show', $market['id']) }}" class="btn-row btn-view">
                                    View →
                                </a>
                                <button @click="openEdit({{ json_encode($market) }})" class="btn-row btn-edit">
                                    Edit
                                </button>
                                <button @click="openDelete({{ json_encode($market) }})" class="btn-row btn-delete">
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
        class="modal-backdrop"
    ></div>

    {{-- ── Modal panel ── --}}
    <div
        x-show="open"
        x-cloak
        x-transition.duration.200ms
        @click.stop
        class="modal-panel"
        style="max-width: 28rem;"
    >

        {{-- ── Create / Edit form ── --}}
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4"
                    x-text="mode === 'create' ? 'Add Market' : 'Edit Market'"></h3>

                <form @submit.prevent="close()">
                    <div class="space-y-3">

                        <div>
                            <label class="modal-label">Market Name <span style="color:#222222;">*</span></label>
                            <input
                                type="text"
                                required
                                :value="selected?.name ?? ''"
                                placeholder="e.g. Gulshan-e-Iqbal Market"
                                class="modal-input"
                            >
                        </div>

                        <div>
                            <label class="modal-label">Area / Region <span style="color:#222222;">*</span></label>
                            <input
                                type="text"
                                required
                                :value="selected?.area ?? ''"
                                placeholder="e.g. Gulshan-e-Iqbal"
                                class="modal-input"
                            >
                        </div>

                        <div>
                            <label class="modal-label">Contact Person</label>
                            <input
                                type="text"
                                :value="selected?.contact_person ?? ''"
                                placeholder="e.g. Ali Hassan"
                                class="modal-input"
                            >
                        </div>

                        <div>
                            <label class="modal-label">Contact Phone</label>
                            <input
                                type="text"
                                :value="selected?.contact_phone ?? ''"
                                placeholder="e.g. 0300-0000000"
                                class="modal-input"
                            >
                        </div>

                        <div>
                            <label class="modal-label">Outstanding Balance</label>
                            <input
                                type="number"
                                min="0"
                                :value="selected?.outstanding_balance ?? 0"
                                class="modal-input"
                            >
                        </div>

                    </div>

                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save">Save</button>
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
                    <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                    <button type="button" @click="close()" class="btn-modal-delete">Confirm Delete</button>
                </div>
            </div>
        </template>

    </div>{{-- /modal panel --}}

</div>{{-- /x-data --}}
@endsection
