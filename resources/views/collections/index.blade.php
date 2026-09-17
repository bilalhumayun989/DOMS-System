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
         style="background: linear-gradient(135deg,#f0f0f0,#ebebeb); border: 1px solid #cccccc;">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#888888;">Total Collected Today</p>
            <p class="text-3xl font-bold" style="color:#111111;">{{ pkr($dailyTotal) }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-medium" style="color:#666666;">{{ count($collections) }} collection records</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="page-card">
        <div class="page-card-header">
            <div class="flex items-center gap-3 flex-wrap">
                <h2 class="page-card-title">Collections</h2>
                <button @click="openCreate()" class="btn-primary">
                    + Add Collection
                </button>
            </div>
            <div class="flex gap-2">
                @foreach(['All','Cash','Cheque','Transfer'] as $opt)
                <a href="{{ route('collections.index',['method'=>$opt]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
                   style="{{ $methodFilter===$opt ? 'background:#222222;color:#ffffff;' : 'background:#f1f5f9;color:#475569;' }}">
                    {{ $opt }}
                </a>
                @endforeach
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Ref</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Customer</th>
                        <th class="text-left">Invoice</th>
                        <th class="text-left">Trip</th>
                        <th class="text-right">Amount</th>
                        <th class="text-left">Method</th>
                        <th class="text-left">Driver</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collections as $col)
                    <tr class="">
                        <td class="font-mono text-xs font-semibold text-gray-500">{{ $col['collection_ref'] }}</td>
                        <td class="text-xs text-gray-500">{{ $col['date'] }}</td>
                        <td><a href="{{ $col['market_id'] ? route('markets.show',$col['market_id']) : route('markets.index') }}" class="text-sm font-semibold text-gray-800">{{ $col['customer'] }}</a></td>
                        <td><a href="{{ $col['invoice_id'] ? route('invoices.show',$col['invoice_id']) : route('invoices.index') }}" class="font-mono text-xs font-bold" style="color:#222222;">{{ $col['invoice_number'] }}</a></td>
                        <td><a href="{{ route('trips.show',$col['trip_id']) }}" class="font-mono text-xs font-bold" style="color:#222222;">{{ $col['trip_display'] }}</a></td>
                        <td class="text-right text-sm font-bold" style="color:#333333;">{{ pkr($col['amount']) }}</td>
                        <td>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $col['method']==='Cash'?'bg-gray-100 text-gray-700':($col['method']==='Cheque'?'bg-gray-100 text-gray-800':'bg-gray-100 text-gray-700') }}">{{ $col['method'] }}</span>
                        </td>
                        <td class="text-sm text-gray-600">{{ $col['deliveryman'] }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button @click="openEdit({{ json_encode($col) }})" class="btn-row btn-edit">Edit</button>
                                <button @click="openDelete({{ json_encode($col) }})" class="btn-row btn-delete">Delete</button>
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
        class="modal-backdrop">
    </div>

    {{-- Modal Panel --}}
    <div
        x-show="open"
        x-cloak
        x-transition
        @click.stop
        class="modal-panel"
        style="max-width:28rem;max-height:90vh;overflow-y:auto;padding:1.5rem;">

        {{-- Create / Edit Form --}}
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4" x-text="mode === 'create' ? 'Add Collection' : 'Edit Collection'"></h3>
                <form method="POST" :action="mode === 'edit' ? '{{ url('collections') }}/' + selected.id : '{{ route('collections.store') }}'">@csrf<input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">
                    <div class="space-y-3">

                        <div>
                            <label class="modal-label">Collection ID</label>
                            <input
                                type="text"
                                readonly
                                :value="selected?.collection_ref ?? 'COL-' + Date.now()"
                                class="modal-input" name="collection_ref">
                        </div>

                        <div>
                            <label class="modal-label">Date</label>
                            <input type="date" required :value="selected?.date ?? ''" class="modal-input" name="date">
                        </div>

                        <div>
                            <label class="modal-label">Customer / Market</label>
                            <input type="text" required :value="selected?.customer ?? ''" class="modal-input" name="customer">
                        </div>

                        <div>
                            <label class="modal-label">Invoice Number</label>
                            <input type="text" required :value="selected?.invoice_number ?? ''" class="modal-input" name="invoice_number">
                        </div>

                        <div>
                            <label class="modal-label">Trip ID</label>
                            <input type="text" required :value="selected?.trip_display ?? ''" class="modal-input" :readonly="mode === 'edit'" name="trip_display">
                        </div>

                        <div>
                            <label class="modal-label">Amount</label>
                            <input type="number" required min="0" :value="selected?.amount ?? ''" class="modal-input" step="0.01" name="amount">
                        </div>

                        <div>
                            <label class="modal-label">Method</label>
                            <select required name="method" class="modal-input">
                                <option value="">Select method…</option>
                                <template x-for="opt in ['Cash','Cheque','Transfer']" :key="opt">
                                    <option :value="opt" :selected="selected?.method === opt" x-text="opt"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="modal-label">Deliveryman (from trip)</label>
                            <input type="text" readonly :value="selected?.deliveryman ?? ''" class="modal-input" name="deliveryman">
                        </div>

                    </div>
                    <div class="space-y-3 mt-3">
<label class="modal-label">Cheque Number (for cheques)</label><input name="cheque_number" :value="selected?.cheque_number ?? ''" class="modal-input">
<label class="modal-label">Bank Name (for cheques)</label><input name="bank_name" :value="selected?.bank_name ?? ''" class="modal-input">
<label class="modal-label">Cheque Date</label><input type="date" name="instrument_date" :value="selected?.instrument_date ?? ''" class="modal-input">
<label class="modal-label">Bank Reference (for transfers)</label><input name="bank_reference" :value="selected?.bank_reference ?? ''" class="modal-input">
</div><div class="flex justify-end gap-2 mt-5">
                        <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save">Save</button>
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
                    <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                    <form method="POST" :action="'{{ url('collections') }}/' + selected.id">@csrf @method('DELETE')<button type="submit" class="btn-modal-delete">Confirm Delete</button></form>
                </div>
            </div>
        </template>

    </div>

</div>
@endsection
