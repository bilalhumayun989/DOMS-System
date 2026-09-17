@extends('layouts.app')
@php $pageTitle = 'Stock'; @endphp

@section('content')
<div
    x-data="{
        open: false,
        mode: 'create',
        selected: null,
        openCreate() { this.mode='create'; this.selected=null; this.open=true; },
        openEdit(r) { this.mode='edit'; this.selected=r; this.open=true; },
        openDelete(r) { this.mode='delete'; this.selected=r; this.open=true; },
        close() { this.open=false; }
    }"
    x-effect="document.body.style.overflow = open ? 'hidden' : ''"
    @keydown.escape.window="close()"
>
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Stock Overview</h2>
                <p class="page-card-sub">{{ count($skus) }} SKUs tracked</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @php
                    $outCount = count(array_filter($skus, fn($s) => $s['stock_status'] === 'Out of Stock'));
                    $lowCount = count(array_filter($skus, fn($s) => $s['stock_status'] === 'Low Stock'));
                @endphp
                @if($outCount > 0)
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#e8e8e8;color:#222222;">{{ $outCount }} Out of Stock</span>
                @endif
                @if($lowCount > 0)
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#ececec;color:#444444;">{{ $lowCount }} Low Stock</span>
                @endif
                <button @click="openCreate()" class="btn-primary">
                    + Add SKU
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">SKU Code</th>
                        <th class="text-left">Product</th>
                        <th class="text-left">Category</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Reorder</th>
                        <th class="text-left">Status</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($skus as $sku)
                    <tr class="{{ $sku['stock_status'] === 'Out of Stock' ? 'bg-gray-50/30' : '' }}">
                        <td class="font-mono text-xs font-bold text-gray-700">{{ $sku['sku_code'] }}</td>
                        <td class="text-sm font-semibold text-gray-800">{{ $sku['product_name'] }}</td>
                        <td><span class="text-xs font-semibold px-2.5 py-1 rounded-lg" style="background:#f1f5f9;color:#475569;">{{ $sku['category'] }}</span></td>
                        <td class="text-center">
                            <span class="text-sm font-bold {{ $sku['current_stock'] === 0 ? 'text-gray-900' : ($sku['stock_status'] === 'Low Stock' ? 'text-gray-700' : 'text-gray-900') }}">{{ $sku['current_stock'] }}</span>
                        </td>
                        <td class="text-center text-sm text-gray-500">{{ $sku['reorder_point'] }}</td>
                        <td><x-status-badge :status="$sku['stock_status']"/></td>
                        <td class="text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('stock.show', $sku['id']) }}" class="btn-row btn-view">View →</a>
                                <button @click="openEdit({{ json_encode($sku) }})" class="btn-row btn-edit">Edit</button>
                                <button @click="openDelete({{ json_encode($sku) }})" class="btn-row btn-delete">Delete</button>
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
        class="modal-backdrop"
    ></div>

    {{-- Create / Edit Modal --}}
    <div
        x-show="open && (mode === 'create' || mode === 'edit')"
        x-cloak
        x-transition
        @click.stop
        class="modal-panel"
        style="max-width:28rem;padding:1.5rem;"
    >
        <h3 class="text-base font-bold text-gray-900 mb-4" x-text="mode === 'create' ? 'Add SKU' : 'Edit SKU'"></h3>
        <form method="POST" :action="mode === 'create' ? '{{ route('stock.index') }}' : '{{ url('stock') }}/' + selected?.id">
            @csrf
            <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">

            <div class="space-y-3">
                <div>
                    <label class="modal-label">SKU Code</label>
                    <input type="text" name="sku_code" required :value="selected?.sku_code ?? ''" class="modal-input">
                </div>
                <div>
                    <label class="modal-label">Product Name</label>
                    <input type="text" name="product_name" required :value="selected?.product_name ?? ''" class="modal-input">
                </div>
                <div>
                    <label class="modal-label">Category</label>
                    <input type="text" name="category" required :value="selected?.category ?? ''" class="modal-input">
                </div>
                <div>
                    <label class="modal-label">Current Stock</label>
                    <input type="number" name="current_stock" required min="0" :value="selected?.current_stock ?? 0" class="modal-input">
                </div>
                <div>
                    <label class="modal-label">Reorder Point</label>
                    <input type="number" name="reorder_point" required min="0" :value="selected?.reorder_point ?? 0" class="modal-input">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                <button type="submit" class="btn-modal-save">Save</button>
            </div>
        </form>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-show="open && mode === 'delete'"
        x-cloak
        x-transition
        @click.stop
        class="modal-panel"
        style="max-width:28rem;padding:1.5rem;"
    >
        <h3 class="text-base font-bold text-gray-900 mb-3">Delete SKU</h3>
        <p class="text-sm text-gray-600 mb-5">
            Are you sure you want to delete SKU <span class="font-bold text-gray-900" x-text="selected?.sku_code"></span>?
        </p>
        <form method="POST" :action="'{{ url('stock') }}/' + selected?.id">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <div class="flex justify-end gap-2">
                <button type="button" @click="close()" class="btn-modal-cancel">Cancel</button>
                <button type="submit" class="btn-modal-delete">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>
@endsection
