@extends('layouts.app')
@php $pageTitle = 'Invoices'; @endphp

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
                <h2 class="page-card-title">All Invoices</h2>
                <p class="page-card-sub">{{ count($invoices) }} invoices</p>
            </div>
            <button @click="openCreate()" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Invoice
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Invoice #</th>
                        <th class="text-left">Customer</th>
                        <th class="text-left">Trip</th>
                        <th class="text-left">Date</th>
                        <th class="text-right">Value</th>
                        <th class="text-left">Status</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr>
                        <td>
                            <span class="font-mono text-xs font-bold" style="color: #222222;">{{ $inv['invoice_number'] }}</span>
                        </td>
                        <td class="text-sm font-semibold text-gray-800">{{ $inv['customer'] }}</td>
                        <td>
                            <a href="{{ route('trips.show', $inv['trip_id']) }}" class="font-mono text-xs font-semibold" style="color: #222222;">
                                {{ $inv['trip_id_display'] }}
                            </a>
                        </td>
                        <td class="text-xs text-gray-500">{{ $inv['date'] }}</td>
                        <td class="text-right text-sm font-bold text-gray-800">{{ pkr($inv['total_value']) }}</td>
                        <td><x-status-badge :status="$inv['status']"/></td>
                        <td>
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('invoices.show', $inv['id']) }}" class="btn-row btn-view">
                                    View →
                                </a>
                                <button @click="openEdit({{ json_encode($inv) }})" class="btn-row btn-edit">
                                    Edit
                                </button>
                                <button @click="openDelete({{ json_encode($inv) }})" class="btn-row btn-delete">
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
                    x-text="mode === 'create' ? 'Add Invoice' : 'Edit Invoice'"></h3>

                <form method="POST" :action="mode === 'create' ? '{{ route('invoices.store') }}' : '{{ url('invoices') }}/' + selected.id">
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">
                    <div class="space-y-3"><div><label class="modal-label">Market</label><select name="market_id" class="modal-input" x-init="$el.value = selected?.market_id ?? ''"><option value="">Select market</option>@foreach($markets as $market)<option value="{{ $market->id }}">{{ $market->name }}</option>@endforeach</select></div><datalist id="invoice-trips">@foreach($trips as $trip)<option value="{{ $trip->trip_number }}">{{ $trip->deliveryman_name }}</option>@endforeach</datalist>

                        <div>
                            <label class="modal-label">Invoice Number <span style="color:#222222;">*</span></label>
                            <input
                                type="text"
                                required
                                :value="selected?.invoice_number ?? ''"
                                placeholder="e.g. INV-2025-001"
                                class="modal-input"
                             name="invoice_number">
                        </div>

                        <div>
                            <label class="modal-label">Customer / Market <span style="color:#222222;">*</span></label>
                            <input
                                type="text"
                                required
                                :value="selected?.customer ?? ''"
                                placeholder="e.g. Gulshan-e-Iqbal Market"
                                class="modal-input"
                             name="customer">
                        </div>

                        <div>
                            <label class="modal-label">Trip ID <span style="color:#222222;">*</span></label>
                            <input
                                type="text"
                                required
                                :value="selected?.trip_id_display ?? ''"
                                placeholder="e.g. TR-2025-07-01-001"
                                class="modal-input"
                             list="invoice-trips" name="trip_id_display">
                        </div>

                        <div>
                            <label class="modal-label">Date <span style="color:#222222;">*</span></label>
                            <input
                                type="date"
                                required
                                :value="selected?.date ?? ''"
                                class="modal-input"
                             name="date">
                        </div>

                        <div>
                            <label class="modal-label">Total Value <span style="color:#222222;">*</span></label>
                            <input
                                type="number"
                                required
                                min="0"
                                :value="selected?.total_value ?? ''"
                                placeholder="0"
                                class="modal-input"
                             step="0.01" name="total_value">
                        </div>

                        <div>
                            <label class="modal-label">Status <span style="color:#222222;">*</span></label>
                            <select name="status"
                                required
                                class="modal-input"
                                x-init="$el.value = selected?.status ?? 'NOT DELIVERED'"
                            >
                                <option value="DELIVERED">DELIVERED</option>
                                <option value="PARTIAL">PARTIAL</option>
                                <option value="NOT DELIVERED">NOT DELIVERED</option>
                                <option value="RESERVICE">RESERVICE</option>
                            </select>
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
                <h3 class="text-base font-bold text-gray-900 mb-3">Delete Invoice</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Are you sure you want to delete Invoice <span class="font-semibold text-gray-800" x-text="selected?.invoice_number"></span>?
                    This action cannot be undone.
                </p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                    <form method="POST" :action="'{{ url('invoices') }}/' + selected.id">@csrf @method('DELETE')<button type="submit" class="btn-modal-delete">Confirm Delete</button></form>
                </div>
            </div>
        </template>

    </div>{{-- /modal panel --}}

</div>{{-- /x-data --}}
@endsection
