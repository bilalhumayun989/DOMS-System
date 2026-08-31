@extends('layouts.app')
@php $pageTitle = $pageTitle ?? 'Trips'; @endphp

@section('content')
<div
    x-data="{
        open: false,
        mode: 'create',
        selected: null,
        driverMap: {{ json_encode(collect($deliverymen)->keyBy('name')->map(fn($d) => ['vehicle' => $d['vehicle'], 'area' => $d['area']])->toArray()) }},
        formVehicle: '',
        formArea: '',
        formDriver: '',
        dlfFileName: '',
        onDriverChange(name) {
            if (this.driverMap[name]) {
                this.formVehicle = this.driverMap[name].vehicle;
                this.formArea    = this.driverMap[name].area;
            } else {
                this.formVehicle = '';
                this.formArea    = '';
            }
            this.formDriver = name;
        },
        openCreate() {
            this.mode = 'create';
            this.selected = null;
            this.formVehicle = '';
            this.formArea = '';
            this.formDriver = '';
            this.dlfFileName = '';
            this.open = true;
        },
        openEdit(r) {
            this.mode = 'edit';
            this.selected = r;
            this.formVehicle = r.vehicle ?? '';
            this.formArea    = r.market_area ?? '';
            this.formDriver  = r.deliveryman?.name ?? '';
            this.dlfFileName = r.source_dlf ?? '';
            this.open = true;
        },
        openDelete(r)     { this.mode = 'delete';     this.selected = r; this.open = true; },
        openCollection(r) { this.mode = 'collection'; this.selected = r; this.open = true; },
        close() { this.open = false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>
    {{-- ── Page card ── --}}
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">

        {{-- Header --}}
        <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #f1f5f9;">
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $pageTitle }}</h2>
                <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($trips) }} trips</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Filter tabs --}}
                <a href="{{ route('trips.index') }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                   style="{{ ($filter !== 'open') ? 'background:#3b82f6;color:#fff;' : 'background:#f1f5f9;color:#475569;' }}">
                    All Trips
                </a>
                <a href="{{ route('trips.index', ['filter' => 'open']) }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                   style="{{ ($filter === 'open') ? 'background:#f97316;color:#fff;' : 'background:#f1f5f9;color:#475569;' }}">
                    Open Trips
                </a>
                <button
                    @click="openCreate()"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background: #3b82f6; color: #fff;"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Trip
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: #fafbfc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Trip ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Deliveryman</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Market / Area</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Load Value</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Expected Cash</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #94a3b8;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trips as $trip)
                    <tr class="border-t hover:bg-slate-50 transition-colors" style="border-color: #f1f5f9;">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs font-bold" style="color: #3b82f6;">{{ $trip['trip_id'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $trip['date'] }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}"
                               class="text-sm font-semibold text-gray-800 hover:text-blue-600 transition-colors">
                                {{ $trip['deliveryman']['name'] }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $trip['vehicle'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $trip['market_area'] }}</td>
                        <td class="px-6 py-4"><x-status-badge :status="$trip['status']"/></td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-gray-800">{{ $trip['load_value'] > 0 ? pkr($trip['load_value']) : '—' }}</td>
                        <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $trip['expected_cash'] > 0 ? pkr($trip['expected_cash']) : '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                <a href="{{ route('trips.show', $trip['id']) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                   style="background: #eff6ff; color: #3b82f6;">
                                    View →
                                </a>
                                @if($filter === 'open')
                                <button
                                    @click="openCollection({{ json_encode($trip) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background: #f0fdf4; color: #16a34a;"
                                >
                                    + Collect
                                </button>
                                @endif
                                <button
                                    @click="openEdit({{ json_encode($trip) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background: #fafafa; color: #64748b; border: 1px solid #e2e8f0;"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($trip) }})"
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
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
        class="fixed inset-0"
        style="background: rgba(0,0,0,0.5); z-index: 50;"
    ></div>

    {{-- ── Modal panel ── --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.stop
        class="fixed rounded-xl shadow-2xl overflow-y-auto"
        style="top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; max-width: 34rem; width: calc(100% - 2rem); max-height: 90vh; z-index: 51; padding: 1.5rem;"
    >

        {{-- ── Create / Edit Trip form ── --}}
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-gray-900" x-text="mode === 'create' ? 'New Trip' : 'Edit Trip'"></h3>
                    <button @click="close()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form @submit.prevent="close()">
                    <div class="grid grid-cols-2 gap-4">

                        {{-- Trip ID (full width) --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Trip ID <span class="text-gray-400 font-normal">(auto-generated)</span></label>
                            <input
                                type="text"
                                readonly
                                x-bind:value="mode === 'edit' ? selected?.trip_id : 'TR-' + new Date().toISOString().slice(0,10) + '-NEW'"
                                class="w-full text-sm font-mono"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem; color: #64748b;"
                            >
                        </div>

                        {{-- Date --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Date <span class="text-red-400">*</span></label>
                            <input
                                type="date"
                                required
                                x-bind:value="selected?.date ?? new Date().toISOString().slice(0,10)"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Status (edit only) --}}
                        <div x-show="mode === 'edit'">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                            <select class="w-full text-sm" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;">
                                @foreach(['DRAFT','READY','DISPATCHED','COMPLETED','SETTLEMENT PENDING','SETTLED','CLOSED'] as $st)
                                <option :selected="selected?.status === '{{ $st }}'">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Deliveryman (full width) — auto-fills Vehicle + Area --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Deliveryman <span class="text-red-400">*</span></label>
                            <select
                                required
                                x-model="formDriver"
                                @change="onDriverChange($event.target.value)"
                                class="w-full text-sm"
                                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;"
                            >
                                <option value="">— Select Driver —</option>
                                @foreach($deliverymen as $dm)
                                <option value="{{ $dm['name'] }}">{{ $dm['name'] }} ({{ $dm['employee_id'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Vehicle (auto-filled) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                Vehicle
                                <span class="text-xs font-normal text-blue-500 ml-1">auto-filled</span>
                            </label>
                            <input
                                type="text"
                                x-model="formVehicle"
                                placeholder="Auto-fills on driver select"
                                class="w-full text-sm"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Market / Area (auto-filled) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                Market / Area <span class="text-red-400">*</span>
                                <span class="text-xs font-normal text-blue-500 ml-1">auto-filled</span>
                            </label>
                            <input
                                type="text"
                                required
                                x-model="formArea"
                                placeholder="Auto-fills on driver select"
                                class="w-full text-sm"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;"
                            >
                        </div>

                        {{-- Source DLF — file upload (full width) --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Source DLF <span class="text-gray-400 font-normal">(Delivery Load Form)</span></label>
                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold px-4 py-2.5 rounded-lg cursor-pointer"
                                       style="background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Upload DLF
                                    <input type="file" accept=".pdf,.xlsx,.xls,.csv" class="hidden"
                                           @change="dlfFileName = $event.target.files[0]?.name ?? ''">
                                </label>
                                <span class="text-xs text-gray-500 truncate flex-1" x-text="dlfFileName || 'No file selected'"></span>
                                <template x-if="dlfFileName">
                                    <button type="button" @click="dlfFileName = ''" class="text-gray-400 hover:text-red-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </template>
                            </div>
                            <p class="text-xs mt-1" style="color: #94a3b8;">Accepted: PDF, Excel, CSV</p>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 mt-6 pt-4" style="border-top: 1px solid #f1f5f9;">
                        <button type="button" @click="close()" class="text-sm font-semibold px-4 py-2 rounded-lg" style="background: #f1f5f9; color: #64748b;">Cancel</button>
                        <button type="submit" class="text-sm font-semibold px-5 py-2 rounded-lg text-white" style="background: #3b82f6;">
                            <span x-text="mode === 'create' ? 'Create Trip' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </template>

        {{-- ── Add Collection modal ── --}}
        <template x-if="mode === 'collection'">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-base font-bold text-gray-900">Add Collection</h3>
                    <button @click="close()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mb-5">
                    Trip: <span class="font-semibold text-blue-600 font-mono" x-text="selected?.trip_id"></span>
                    &nbsp;·&nbsp;
                    Driver: <span class="font-semibold text-gray-700" x-text="selected?.deliveryman?.name"></span>
                </p>

                <form @submit.prevent="close()">
                    <div class="grid grid-cols-2 gap-4">

                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Customer / Market <span class="text-red-400">*</span></label>
                            <input type="text" required placeholder="e.g. Al-Noor General Store" class="w-full text-sm"
                                   style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Invoice Number <span class="text-red-400">*</span></label>
                            <input type="text" required placeholder="e.g. INV-001" class="w-full text-sm"
                                   style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Amount (PKR) <span class="text-red-400">*</span></label>
                            <input type="number" required min="0" placeholder="0" class="w-full text-sm"
                                   style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Payment Method <span class="text-red-400">*</span></label>
                            <select required class="w-full text-sm" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;">
                                <option value="">— Select —</option>
                                <option>Cash</option>
                                <option>Cheque</option>
                                <option>Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Date <span class="text-red-400">*</span></label>
                            <input type="date" required :value="new Date().toISOString().slice(0,10)" class="w-full text-sm"
                                   style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea rows="2" placeholder="Any remarks..." class="w-full text-sm resize-none"
                                      style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 0.75rem;"></textarea>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-4" style="border-top: 1px solid #f1f5f9;">
                        <button type="button" @click="close()" class="text-sm font-semibold px-4 py-2 rounded-lg" style="background: #f1f5f9; color: #64748b;">Cancel</button>
                        <button type="submit" class="text-sm font-semibold px-5 py-2 rounded-lg text-white" style="background: #16a34a;">
                            Record Collection
                        </button>
                    </div>
                </form>
            </div>
        </template>

        {{-- ── Delete confirmation ── --}}
        <template x-if="mode === 'delete'">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: #fff1f2;">
                        <svg class="w-5 h-5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Delete Trip</h3>
                </div>
                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to delete Trip
                    <span class="font-bold text-gray-900" x-text="selected?.trip_id"></span>?
                    This action cannot be undone.
                </p>
                <div class="flex items-center justify-end gap-3">
                    <button @click="close()" class="text-sm font-semibold px-4 py-2 rounded-lg" style="background: #f1f5f9; color: #64748b;">Cancel</button>
                    <button @click="close()" class="text-sm font-semibold px-5 py-2 rounded-lg text-white" style="background: #ef4444;">Confirm Delete</button>
                </div>
            </div>
        </template>

    </div>

</div>
@endsection
