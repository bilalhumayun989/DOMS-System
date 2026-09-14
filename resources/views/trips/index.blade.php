@extends('layouts.app')
@php $pageTitle = $pageTitle ?? 'Trips'; @endphp

@section('content')
@if(session('success'))
<div class="mb-4 rounded-xl px-4 py-3 text-sm font-semibold bg-gray-100 text-gray-700 border border-green-200">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-4 rounded-xl px-4 py-3 text-sm bg-gray-100 text-gray-900 border border-red-200">{{ $errors->first() }}</div>
@endif
<div
    x-data="{
        open: false,
        mode: 'create',
        selected: null,
        driverMap: {{ json_encode(collect($deliverymen)->keyBy('name')->map(fn($d) => ['vehicle' => $d['vehicle'], 'area' => $d['area']])->toArray()) }},
        formVehicle: '',
        formArea: '',
        formDriver: '',
        formDistributor: '',
        collectMethod: 'Cash',
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
            this.formDistributor = '';
            this.dlfFileName = '';
            this.open = true;
        },
        openEdit(r) {
            this.mode = 'edit';
            this.selected = r;
            this.formVehicle = r.vehicle ?? '';
            this.formArea    = r.market_area ?? '';
            this.formDriver  = r.deliveryman?.name ?? '';
            this.formDistributor = r.distributor ?? 'Main Distributor';
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
    <div class="page-card">

        {{-- Header --}}
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">{{ $pageTitle }}</h2>
                <p class="page-card-sub">{{ count($trips) }} trips</p>
            </div>
            <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('trips.index') }}" class="flex items-center gap-2">
                            <select name="month" class="rounded-lg border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                                <option value="">Month</option>
                                @foreach(range(1, 12) as $monthOption)
                                <option value="{{ $monthOption }}" @selected($month === $monthOption)>{{ date('F', mktime(0, 0, 0, $monthOption, 1)) }}</option>
                                @endforeach
                            </select>
                            <select name="year" class="rounded-lg border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                                <option value="">Year</option>
                                @foreach(range(now()->year - 2, now()->year + 1) as $yearOption)
                                <option value="{{ $yearOption }}" @selected($year === $yearOption)>{{ $yearOption }}</option>
                                @endforeach
                            </select>
                            <button class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">Filter</button>
                        </form>
                <button
                    @click="openCreate()"
                    class="btn-primary"
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
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Trip ID</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Deliveryman</th>
                        <th class="text-left">Vehicle</th>
                        <th class="text-left">Market / Area</th>
                        <th class="text-left">Status</th>
                        <th class="text-right">Load Value</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trips as $trip)
                    <tr>
                        <td>
                            <span class="font-mono text-xs font-bold" style="color: #222222;">{{ $trip['trip_id'] }}</span>
                        </td>
                        <td class="text-sm text-gray-600">{{ $trip['date'] }}</td>
                        <td>
                            <a href="{{ route('deliverymen.show', $trip['deliveryman']['id']) }}"
                               class="text-sm font-semibold text-gray-800 transition-colors">
                                {{ $trip['deliveryman']['name'] }}
                            </a>
                        </td>
                        <td class="text-xs text-gray-500">{{ $trip['vehicle'] }}</td>
                        <td class="text-sm text-gray-700">{{ $trip['market_area'] }}</td>
                        <td><x-status-badge :status="$trip['status']"/></td>
                        <td class="text-right text-sm font-bold text-gray-800">{{ $trip['load_value'] > 0 ? pkr($trip['load_value']) : '—' }}</td>
                        <td>
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                <a href="{{ route('trips.show', $trip['id']) }}"
                                   class="btn-row btn-view">
                                    View →
                                </a>
                                @if($trip['status'] !== 'CLOSED')
                                <button
                                    @click="openEdit({{ json_encode($trip) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background: #fafafa; color: #64748b; border: 1px solid #e2e8f0;"
                                >
                                    Edit
                                </button>
                                @endif
                                @if($trip['status'] === 'DRAFT')
                                <button
                                    @click="openDelete({{ json_encode($trip) }})"
                                    class="btn-row btn-delete"
                                >
                                    Delete
                                </button>
                                @endif
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
        class="modal-backdrop"
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
        class="modal-panel overflow-y-auto"
        style="max-width: 34rem; width: calc(100% - 2rem); max-height: 90vh; padding: 1.5rem;"
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

                <form method="POST" :action="mode === 'edit' ? '{{ url('/trips') }}/' + selected?.id : '{{ route('trips.store') }}'">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <div class="grid grid-cols-2 gap-4">

                        {{-- Trip ID (full width) --}}
                        <div class="col-span-2">
                            <label class="modal-label">Trip ID <span class="text-gray-400 font-normal">(auto-generated)</span></label>
                            <input
                                type="text"
                                readonly
                                x-bind:value="mode === 'edit' ? selected?.trip_id : 'TR-' + new Date().toISOString().slice(0,10) + '-NEW'"
                                class="modal-input font-mono"
                            >
                        </div>

                        {{-- Date --}}
                        <div>
                            <label class="modal-label">Date <span class="text-gray-400">*</span></label>
                            <input
                                type="date"
                                name="trip_date"
                                required
                                x-bind:value="selected?.date ?? new Date().toISOString().slice(0,10)"
                                class="modal-input"
                            >
                        </div>

                        <input type="hidden" name="status" :value="selected?.status ?? 'DRAFT'">
                        <input type="hidden" name="expected_cash" :value="selected?.expected_cash ?? 0">

                        {{-- Deliveryman (full width) — auto-fills Vehicle + Area --}}
                        <div class="col-span-2">
                            <label class="modal-label">Deliveryman <span class="text-gray-400">*</span></label>
                            <select
                                name="deliveryman_name"
                                required
                                x-model="formDriver"
                                @change="onDriverChange($event.target.value)"
                                class="modal-input"
                            >
                                <option value="">— Select Driver —</option>
                                @foreach($deliverymen as $dm)
                                <option value="{{ $dm['name'] }}">{{ $dm['name'] }} ({{ $dm['employee_id'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Vehicle (auto-filled) --}}
                        <div>
                            <label class="modal-label">
                                Vehicle
                                <span class="text-xs font-normal text-gray-500 ml-1">auto-filled</span>
                            </label>
                            <input
                                type="text"
                                name="vehicle"
                                x-model="formVehicle"
                                placeholder="Auto-fills on driver select"
                                class="modal-input"
                            >
                        </div>

                        <div>
                            <label class="modal-label">Day Number</label>
                            <input type="number" min="1" max="31" readonly :value="selected?.date ? Number(selected.date.slice(-2)) : new Date().getDate()" class="modal-input">
                        </div>

                        <div class="col-span-2">
                            <label class="modal-label">Distributor Name</label>
                            <input type="text" name="distributor" x-model="formDistributor" value="Main Distributor" placeholder="e.g. AAA Traders" class="modal-input">
                        </div>

                        {{-- Market / Area (auto-filled) --}}
                        <div>
                            <label class="modal-label">
                                Market / Area <span class="text-gray-400">*</span>
                                <span class="text-xs font-normal text-gray-500 ml-1">auto-filled</span>
                            </label>
                            <input
                                type="text"
                                name="market_area"
                                required
                                x-model="formArea"
                                placeholder="Auto-fills on driver select"
                                class="modal-input"
                            >
                        </div>

                        {{-- Source DLF — file upload (full width) --}}
                        <div class="col-span-2">
                            <label class="modal-label">Source DLF <span class="text-gray-400 font-normal">(Delivery Load Form)</span></label>
                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold px-4 py-2.5 rounded-lg cursor-pointer"
                                       style="background: #efefef; color: #222222; border: 1px solid #cccccc;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Upload DLF
                                    <input type="file" accept=".pdf,.xlsx,.xls,.csv" class="hidden"
                                           @change="dlfFileName = $event.target.files[0]?.name ?? ''">
                                </label>
                                <span class="text-xs text-gray-500 truncate flex-1" x-text="dlfFileName || 'No file selected'"></span>
                                <template x-if="dlfFileName">
                                    <button type="button" @click="dlfFileName = ''" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </template>
                            </div>
                            <p class="text-xs mt-1" style="color: #94a3b8;">Accepted: PDF, Excel, CSV</p>
                        </div>

                    </div>

                    <div class="mt-5 rounded-xl border border-slate-200 p-4">
                        <h4 class="text-sm font-black text-slate-800">Stock &amp; Issue Entry</h4>
                        <div class="mt-3 overflow-x-auto">
                            <table class="w-full min-w-[42rem] text-xs">
                                <thead><tr class="text-left text-slate-400"><th class="pb-2">Product / SKU Name</th><th class="pb-2">Opening Stock</th><th class="pb-2">Fresh Issue</th><th class="pb-2">Total Issued</th><th class="pb-2">Returned</th><th class="pb-2">Damage / Expired</th><th class="pb-2">Net Sold</th></tr></thead>
                                <tbody><tr><td class="pr-2"><select class="w-full rounded border-slate-200 text-xs"><option>Sooper FP</option><option>Rio</option><option>Pepsi 1.5L</option><option>Coca-Cola 1.5L</option></select></td><td class="pr-2"><input type="number" value="0" class="w-full rounded border-slate-200 text-xs"></td><td class="pr-2"><input type="number" value="0" class="w-full rounded border-slate-200 text-xs"></td><td class="pr-2"><input readonly value="0" class="w-full rounded border-slate-200 bg-slate-50 text-xs"></td><td class="pr-2"><input type="number" value="0" class="w-full rounded border-slate-200 text-xs"></td><td class="pr-2"><input type="number" value="0" class="w-full rounded border-slate-200 text-xs"></td><td><input readonly value="0" class="w-full rounded border-slate-200 bg-slate-50 text-xs"></td></tr></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-slate-200 p-4">
                        <h4 class="text-sm font-black text-slate-800">Sales &amp; Financial Cash Entry</h4>
                        <div class="mt-3 grid grid-cols-2 gap-4">
                            @foreach(['Total Gross Sales Amount (PKR)', 'Discount / Scheme Given (PKR)', 'Net Sales Amount (PKR)', 'Cash Collected (PKR)', 'Cheque Collected (PKR)', 'Online Bank Transfer (PKR)', 'Market Credit / Udhaar (PKR)'] as $financialField)
                            <div class="{{ $loop->last ? 'col-span-2' : '' }}"><label class="modal-label">{{ $financialField }}</label><input type="number" min="0" step="0.01" value="0" class="modal-input"></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="col-span-2">
                            <label class="modal-label">Source DLF Reference</label>
                            <input type="text" name="source_dlf" :value="selected?.source_dlf ?? dlfFileName" placeholder="e.g. DLF-10245" class="modal-input">
                        </div>
                        <div>
                            <label class="modal-label">Load Value (PKR)</label>
                            <input type="number" name="load_value" min="0" step="0.01" required :value="selected?.load_value ?? 0" class="modal-input">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 mt-6 pt-4" style="border-top: 1px solid #f1f5f9;">
                        <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save">
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
                    Trip: <span class="font-semibold text-gray-800 font-mono" x-text="selected?.trip_id"></span>
                    &nbsp;·&nbsp;
                    Driver: <span class="font-semibold text-gray-700" x-text="selected?.deliveryman?.name"></span>
                </p>

                <form method="POST" :action="'{{ url('/trips') }}/' + selected?.id + '/collections'">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">

                        <div class="col-span-2">
                            <label class="modal-label">Customer / Market <span class="text-gray-400">*</span></label>
                            <input type="text" name="customer" required placeholder="e.g. Al-Noor General Store" class="modal-input">
                        </div>

                        <div>
                            <label class="modal-label">Invoice Number <span class="text-gray-400">*</span></label>
                            <input type="text" name="invoice_number" required placeholder="e.g. INV-001" class="modal-input">
                        </div>

                        <div>
                            <label class="modal-label">Amount (PKR) <span class="text-gray-400">*</span></label>
                            <input type="number" name="amount" required min="0.01" step="0.01" placeholder="0" class="modal-input">
                        </div>

                        <div>
                            <label class="modal-label">Payment Method <span class="text-gray-400">*</span></label>
                            <select name="method" x-model="collectMethod" required class="modal-input">
                                <option value="">— Select —</option>
                                <option>Cash</option>
                                <option>Cheque</option>
                                <option>Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label class="modal-label">Date <span class="text-gray-400">*</span></label>
                            <input type="datetime-local" name="collected_at" required :value="new Date().toISOString().slice(0,16)" class="modal-input">
                        </div>

                        <div class="col-span-2">
                            <label class="modal-label">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea name="notes" rows="2" placeholder="Any remarks..." class="modal-input resize-none"></textarea>
                        </div>

                        <input name="cheque_number" placeholder="Cheque number (if cheque)" class="text-sm rounded-lg border-slate-200">
                        <input name="bank_name" placeholder="Bank name (if cheque)" class="text-sm rounded-lg border-slate-200">
                        <input type="date" name="instrument_date" class="text-sm rounded-lg border-slate-200">
                        <input name="bank_reference" placeholder="Transfer reference" class="text-sm rounded-lg border-slate-200">

                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-4" style="border-top: 1px solid #f1f5f9;">
                        <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save">
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
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: #e8e8e8;">
                        <svg class="w-5 h-5" style="color: #222222;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <button @click="close()" class="btn-modal-cancel">Cancel</button>
                    <form method="POST" :action="'{{ url('/trips') }}/' + selected?.id">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-modal-delete">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </template>

    </div>

</div>
@endsection
