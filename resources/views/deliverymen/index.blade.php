@extends('layouts.app')
@php $pageTitle = 'Deliverymen'; @endphp

@section('content')
@php
$driverAreas = [
    1 => ['Gulshan-e-Iqbal', 'Clifton'],
    2 => ['North Nazimabad', 'SITE Area'],
    3 => ['Orangi Town', 'Korangi Industrial'],
    4 => ['Liaquatabad', 'SITE Area'],
    5 => ['Saddar', 'Clifton'],
];
@endphp

<div
    x-data="{
        open: false,
        mode: 'create',
        selected: null,
        availableAreas: [
            'Gulshan-e-Iqbal',
            'North Nazimabad',
            'Liaquatabad',
            'Orangi Town',
            'Korangi Industrial',
            'SITE Area',
            'Saddar',
            'Clifton'
        ],
        selectedAreas: [],
        toggleArea(area) {
            const idx = this.selectedAreas.indexOf(area);
            if (idx === -1) {
                this.selectedAreas.push(area);
            } else {
                this.selectedAreas.splice(idx, 1);
            }
        },
        isAreaSelected(area) {
            return this.selectedAreas.includes(area);
        },
        openCreate() {
            this.mode = 'create';
            this.selected = null;
            this.selectedAreas = [];
            this.open = true;
        },
        openEdit(r) {
            this.mode = 'edit';
            this.selected = r;
            this.selectedAreas = r.assigned_areas ?? [];
            this.open = true;
        },
        openDelete(r) { this.mode = 'delete'; this.selected = r; this.open = true; },
        close() { this.open = false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>
    {{-- ── Page card ─────────────────────────────────────────────────── --}}
    <div class="page-card">

        {{-- Header --}}
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">All Deliverymen</h2>
                <p class="page-card-sub">{{ count($deliverymen) }} drivers registered</p>
            </div>
            <button
                @click="openCreate()"
                class="btn-primary"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Deliveryman
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th class="text-left">Employee ID</th>
                        <th class="text-left">Phone</th>
                        <th class="text-left">Assigned Areas</th>
                        <th class="text-center">Total Trips</th>
                        <th class="text-center">Active</th>
                        <th class="text-right">Total Collected</th>
                        <th class="text-right">Shortages</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliverymen as $dm)
                    @php $areas = $dm['assigned_areas'] ?? []; @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                     style="background: linear-gradient(135deg,#444444,#666666);">
                                    {{ strtoupper(substr($dm['name'],0,1)) }}
                                </div>
                                <span class="text-sm font-semibold text-gray-800">{{ $dm['name'] }}</span>
                            </div>
                        </td>
                        <td class="font-mono text-xs font-semibold text-gray-500">{{ $dm['employee_id'] }}</td>
                        <td class="text-sm text-gray-600">{{ $dm['phone'] }}</td>
                        <td>
                            @if(count($areas) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($areas as $area)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:#efefef;color:#222222;">{{ $area }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="text-center text-sm font-bold text-gray-800">{{ $dm['total_trips'] }}</td>
                        <td class="text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                                {{ $dm['active_trips']>0?'bg-gray-100 text-gray-700':'bg-gray-100 text-gray-400' }}">
                                {{ $dm['active_trips'] }}
                            </span>
                        </td>
                        <td class="text-right text-sm font-bold text-gray-800">{{ pkr($dm['total_collected']) }}</td>
                        <td class="text-right">
                            @if($dm['outstanding_shortages'] > 0)
                            <span class="text-sm font-bold" style="color: #222222;">{{ pkr($dm['outstanding_shortages']) }}</span>
                            @else
                            <span class="text-sm text-gray-300">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('deliverymen.show', $dm['id']) }}"
                                   class="btn-row btn-view">
                                    View →
                                </a>
                                <button
                                    @click="openEdit({{ json_encode(array_merge($dm, ['areas' => $areas])) }})"
                                    class="btn-row btn-edit"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($dm) }})"
                                    class="btn-row btn-delete"
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

    {{-- ── Backdrop ────────────────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="modal-backdrop"
        x-cloak
    ></div>

    {{-- ── Modal panel ─────────────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.stop
        class="modal-panel"
        style="max-width: 34rem; width: calc(100% - 2rem); max-height: 90vh; overflow-y: auto; padding: 1.5rem;"
        x-cloak
    >
        {{-- ── Create / Edit form ───────────────────────────── --}}
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-5"
                    x-text="mode === 'create' ? 'Add Deliveryman' : 'Edit Deliveryman'"></h3>

                <form method="POST" :action="mode === 'create' ? '{{ route('deliverymen.store') }}' : '{{ url('deliverymen') }}/' + selected.id">
                    @csrf
<template x-for="area in selectedAreas" :key="area"><input type="hidden" name="assigned_areas[]" :value="area"></template>
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">
                    <div class="grid grid-cols-1 gap-4">

                        {{-- Name --}}
                        <div>
                            <label class="modal-label">Name <span class="text-gray-400">*</span></label>
                            <input
                                type="text"
                                required
                                x-bind:value="selected?.name ?? ''"
                                class="modal-input"
                             name="name">
                        </div>

                        {{-- Employee ID --}}
                        <div>
                            <label class="modal-label">Employee ID <span class="text-gray-400">*</span></label>
                            <input
                                type="text"
                                required
                                x-bind:value="selected?.employee_id ?? ''"
                                class="modal-input"
                             name="employee_id">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="modal-label">Phone <span class="text-gray-400">*</span></label>
                            <input
                                type="text"
                                required
                                x-bind:value="selected?.phone ?? ''"
                                class="modal-input"
                             name="phone">
                        </div>

                        {{-- Vehicle --}}
                        <div>
                            <label class="modal-label">Vehicle</label>
                            <input
                                type="text"
                                x-bind:value="selected?.vehicle ?? ''"
                                class="modal-input"
                             name="vehicle">
                        </div>

                        {{-- Join Date --}}
                        <div>
                            <label class="modal-label">Join Date <span class="text-gray-400">*</span></label>
                            <input
                                type="date"
                                required
                                x-bind:value="selected?.joined_at ?? ''"
                                class="modal-input"
                             name="joined_at">
                        </div>

                        {{-- Assigned Areas --}}
                        <div>
                            <label class="modal-label">
                                Assigned Areas
                                <span class="text-xs font-normal text-gray-400 ml-1">(select one or more)</span>
                            </label>

                            {{-- Selected count badge --}}
                            <div class="mb-2">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                      style="background:#efefef;color:#222222;"
                                      x-text="selectedAreas.length + ' area' + (selectedAreas.length !== 1 ? 's' : '') + ' selected'">
                                </span>
                            </div>

                            {{-- Checkbox grid --}}
                            <div class="grid grid-cols-2 gap-2">
                                <template x-for="area in availableAreas" :key="area">
                                    <button
                                        type="button"
                                        @click="toggleArea(area)"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium transition-all text-left"
                                        :style="isAreaSelected(area)
                                            ? 'background:#efefef;color:#222222;border:1.5px solid #aaaaaa;'
                                            : 'background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0;'"
                                    >
                                        <span class="w-4 h-4 rounded flex items-center justify-center flex-shrink-0 transition-all"
                                              :style="isAreaSelected(area) ? 'background:#222222;' : 'background:#e2e8f0;'">
                                            <svg x-show="isAreaSelected(area)" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                        <span x-text="area"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button
                            type="button"
                            @click="close()"
                            class="btn-modal-cancel"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="btn-modal-save"
                        >Save</button>
                    </div>
                </form>
            </div>
        </template>

        {{-- ── Delete confirmation ──────────────────────────── --}}
        <template x-if="mode === 'delete'">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: #e8e8e8;">
                        <svg class="w-5 h-5" style="color: #222222;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Delete Deliveryman</h3>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to delete
                    <span class="font-bold text-gray-900" x-text="selected?.name"></span>?
                    This action cannot be undone.
                </p>

                <div class="flex items-center justify-end gap-3">
                    <button
                        @click="close()"
                        class="btn-modal-cancel"
                    >Cancel</button>
                    <button
                        @click="close()"
                        class="btn-modal-delete"
                    >Confirm Delete</button>
                </div>
            </div>
        </template>
    </div>

</div>
@endsection
