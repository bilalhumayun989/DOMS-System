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
    <div class="rounded-2xl overflow-hidden" style="background: #ffffff; border: 1px solid #e8edf2;">
        <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-3" style="border-bottom: 1px solid #f1f5f9;">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Stock Overview</h2>
                <p class="text-xs mt-0.5 font-medium" style="color: #94a3b8;">{{ count($skus) }} SKUs tracked</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @php
                    $outCount = count(array_filter($skus, fn($s) => $s['stock_status'] === 'Out of Stock'));
                    $lowCount = count(array_filter($skus, fn($s) => $s['stock_status'] === 'Low Stock'));
                @endphp
                @if($outCount > 0)
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#fff1f2;color:#ef4444;">{{ $outCount }} Out of Stock</span>
                @endif
                @if($lowCount > 0)
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full" style="background:#fffbeb;color:#b45309;">{{ $lowCount }} Low Stock</span>
                @endif
                <button
                    @click="openCreate()"
                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                    style="background:#3b82f6;color:#ffffff;"
                >
                    + Add SKU
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: #fafbfc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">SKU Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Category</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Stock</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Reorder</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color:#94a3b8;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($skus as $sku)
                    <tr class="border-t hover:bg-slate-50 transition-colors {{ $sku['stock_status'] === 'Out of Stock' ? 'bg-red-50/30' : '' }}" style="border-color: #f1f5f9;">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-gray-700">{{ $sku['sku_code'] }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $sku['product_name'] }}</td>
                        <td class="px-6 py-4"><span class="text-xs font-semibold px-2.5 py-1 rounded-lg" style="background:#f1f5f9;color:#475569;">{{ $sku['category'] }}</span></td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold {{ $sku['current_stock'] === 0 ? 'text-red-600' : ($sku['stock_status'] === 'Low Stock' ? 'text-amber-600' : 'text-gray-900') }}">{{ $sku['current_stock'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $sku['reorder_point'] }}</td>
                        <td class="px-6 py-4"><x-status-badge :status="$sku['stock_status']"/></td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('stock.show', $sku['id']) }}" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg" style="background:#eff6ff;color:#3b82f6;">View →</a>
                                <button
                                    @click="openEdit({{ json_encode($sku) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background:#f0fdf4;color:#16a34a;"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="openDelete({{ json_encode($sku) }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg"
                                    style="background:#fff1f2;color:#ef4444;"
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

    {{-- Backdrop --}}
    <div
        x-show="open"
        x-cloak
        x-transition
        @click="close()"
        style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:50;"
    ></div>

    {{-- Create / Edit Modal --}}
    <div
        x-show="open && (mode === 'create' || mode === 'edit')"
        x-cloak
        x-transition
        @click.stop
        style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:0.75rem;box-shadow:0 4px 24px rgba(0,0,0,0.12);width:100%;max-width:28rem;padding:1.5rem;z-index:51;"
    >
        <h3 class="text-base font-bold text-gray-900 mb-4" x-text="mode === 'create' ? 'Add SKU' : 'Edit SKU'"></h3>
        <form method="POST" :action="mode === 'create' ? '{{ route('stock.index') }}' : '{{ url('stock') }}/' + selected?.id">
            @csrf
            <input x-show="mode === 'edit'" type="hidden" name="_method" value="PUT">

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">SKU Code</label>
                    <input
                        type="text"
                        name="sku_code"
                        required
                        :value="selected?.sku_code ?? ''"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Product Name</label>
                    <input
                        type="text"
                        name="product_name"
                        required
                        :value="selected?.product_name ?? ''"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Category</label>
                    <input
                        type="text"
                        name="category"
                        required
                        :value="selected?.category ?? ''"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Current Stock</label>
                    <input
                        type="number"
                        name="current_stock"
                        required
                        min="0"
                        :value="selected?.current_stock ?? 0"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:#64748b;">Reorder Point</label>
                    <input
                        type="number"
                        name="reorder_point"
                        required
                        min="0"
                        :value="selected?.reorder_point ?? 0"
                        class="w-full text-sm"
                        style="background:#fff;border:1px solid #e2e8f0;border-radius:0.375rem;padding:0.625rem 0.75rem;"
                    >
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button
                    type="button"
                    @click="close()"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#f1f5f9;color:#475569;"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#3b82f6;color:#ffffff;"
                >
                    Save
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-show="open && mode === 'delete'"
        x-cloak
        x-transition
        @click.stop
        style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:0.75rem;box-shadow:0 4px 24px rgba(0,0,0,0.12);width:100%;max-width:28rem;padding:1.5rem;z-index:51;"
    >
        <h3 class="text-base font-bold text-gray-900 mb-3">Delete SKU</h3>
        <p class="text-sm text-gray-600 mb-5">
            Are you sure you want to delete SKU <span class="font-bold text-gray-900" x-text="selected?.sku_code"></span>?
        </p>
        <form method="POST" :action="'{{ url('stock') }}/' + selected?.id">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="close()"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#f1f5f9;color:#475569;"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="text-xs font-semibold px-4 py-2 rounded-lg"
                    style="background:#ef4444;color:#ffffff;"
                >
                    Confirm Delete
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
