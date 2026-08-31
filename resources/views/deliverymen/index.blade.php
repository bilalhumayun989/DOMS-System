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
            this.selectedAreas = r.areas ?? [];
            this.open = true;
        },
        openDelete(r) { this.mode = 'delete'; this.selected = r; this.open = true; },
        close() { this.open = false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>
    {{-- ── Page card ─────────────────────────────────────────────────── --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">

        {{-- Header --}}
        <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
            <div>
                <h2 class="text-lg font-bold text-gray-900">All Deliverymen</h2>
                <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($deliverymen) }} drivers registered</p>
            </div>
            <button
                @click="openCreate()"
                class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2 rounded-lg transition-opacity hover:opacity-90"
                style="background: #3b82f6; color: #fff;"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Deliveryman
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: #fafbfc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Employee ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Assigned Areas</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Total Trips</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Active</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Collected Today</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Shortages</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliverymen as $dm)
                    @php $areas = $driverAreas[$dm['id']] ?? []; @endphp
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
                        <td class="px-6 py-4">
                            @if(count($areas) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($areas as $area)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:#eff6ff;color:#3b82f6;">{{ $area }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
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
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('deliverymen.show', $dm['id']) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                   style="background: #eff6ff; color: #3b82f6;">
                                    View →
                                </a>
                                <button
                                    @click="openEdit({{ json_encode(array_merge($dm, ['areas' => $areas])) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                    style="background: #f0fdf4; color: #16a34a;"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($dm) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
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
        class="fixed inset-0"
        style="background: rgba(0,0,0,0.5); z-index: 50;"
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
        class="fixed rounded-xl shadow-2xl p-6"
        style="top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; max-width: 34rem; width: calc(100% - 2rem); max-height: 90vh; overflow-y: auto; z-index: 51;"
        x-cloak
    >
        {{-- ── Create / Edit form ───────────────────────────── --}}
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-5"
                    x-text="mode === 'create' ? 'Add Deliveryman' : 'Edit Deliveryman'"></h3>

                <form @submit.prevent="close()">
                    <div class="grid grid-cols-1 gap-4">

                        {{-- Name --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Name <span class="text-red-400">*</span></label>
                            <input
                                type="text"
                                required
                                x-bind:value="selected?.name ?? ''"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Employee ID --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Employee ID <span class="text-red-400">*</span></label>
                            <input
                                type="text"
                                required
                                x-bind:value="selected?.employee_id ?? ''"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone <span class="text-red-400">*</span></label>
                            <input
                                type="text"
                                required
                                x-bind:value="selected?.phone ?? ''"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Vehicle --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Vehicle</label>
                            <input
                                type="text"
                                x-bind:value="selected?.vehicle ?? ''"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Join Date --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Join Date <span class="text-red-400">*</span></label>
                            <input
                                type="date"
                                required
                                x-bind:value="selected?.joined_at ?? ''"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.375rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Assigned Areas --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-2">
                                Assigned Areas
                                <span class="text-xs font-normal text-gray-400 ml-1">(select one or more)</span>
                            </label>

                            {{-- Selected count badge --}}
                            <div class="mb-2">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                      style="background:#eff6ff;color:#3b82f6;"
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
                                            ? 'background:#eff6ff;color:#3b82f6;border:1.5px solid #93c5fd;'
                                            : 'background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0;'"
                                    >
                                        <span class="w-4 h-4 rounded flex items-center justify-center flex-shrink-0 transition-all"
                                              :style="isAreaSelected(area) ? 'background:#3b82f6;' : 'background:#e2e8f0;'">
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
                            class="text-sm font-semibold px-4 py-2 rounded-lg"
                            style="background: #f1f5f9; color: #64748b;"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="text-sm font-semibold px-5 py-2 rounded-lg text-white"
                            style="background: #3b82f6;"
                        >Save</button>
                    </div>
                </form>
            </div>
        </template>

        {{-- ── Delete confirmation ──────────────────────────── --}}
        <template x-if="mode === 'delete'">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: #fff1f2;">
                        <svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="text-sm font-semibold px-4 py-2 rounded-lg"
                        style="background: #f1f5f9; color: #64748b;"
                    >Cancel</button>
                    <button
                        @click="close()"
                        class="text-sm font-semibold px-5 py-2 rounded-lg text-white"
                        style="background: #ef4444;"
                    >Confirm Delete</button>
                </div>
            </div>
        </template>
    </div>

</div>
@endsection
