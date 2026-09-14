@extends('layouts.app')
@php
    $pageTitle = 'Stock';
    $limit = \App\Http\Controllers\DemoController::DEMO_LIMIT;
@endphp

@section('content')

@if(session('success'))
<div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700">
    <span>✅ {{ session('success') }}</span>
</div>
@endif

@if(session('demo_limit'))
<div class="mb-4 flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-gray-100 border border-gray-300 text-xs font-bold text-gray-700">
    <div class="flex items-center gap-2">
        <span>⚠️ {{ session('demo_limit') }}</span>
    </div>
</div>
@endif

<div x-data="{ open: false, mode: 'create', selected: null }">
    <div class="page-card">
        <div class="page-card-header flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="page-card-title">Stock Inventory</h2>
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-gray-200 text-gray-700 border border-gray-300">
                        Demo Limit: {{ $used }}/{{ $limit }} Used
                    </span>
                </div>
                <p class="page-card-sub">{{ count($stockItems) }} stock items listed</p>
            </div>
            <button @click="mode = 'create'; selected = null; open = true;" class="btn-primary flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Stock Item
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Item Name</th>
                        <th class="text-left">SKU</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockItems as $item)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $item['name'] }}</td>
                        <td class="font-mono text-xs font-semibold text-slate-500">{{ $item['sku'] }}</td>
                        <td class="text-center font-bold text-slate-800">{{ $item['quantity'] }}</td>
                        <td class="text-right font-bold text-slate-900">{{ pkr($item['unit_price']) }}</td>
                        <td class="text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                {{ $item['status'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="mode = 'view'; selected = {{ json_encode($item) }}; open = true;" class="btn-row btn-view">View</button>
                                <button @click="mode = 'edit'; selected = {{ json_encode($item) }}; open = true;" class="btn-row btn-edit">Edit</button>
                                <button @click="mode = 'delete'; selected = {{ json_encode($item) }}; open = true;" class="btn-row btn-delete">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400 text-xs">No stock items added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Backdrop --}}
    <div x-show="open" class="modal-backdrop" x-cloak @click="open = false"></div>

    {{-- Modal Panel --}}
    <div x-show="open" class="modal-panel" style="max-width: 28rem; width: 100%; padding: 1.5rem;" x-cloak>
        <template x-if="mode === 'create' || mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4" x-text="mode === 'create' ? 'Add Demo Stock Item' : 'Edit Demo Stock Item'"></h3>
                <form :action="mode === 'create' ? '{{ route('demo.stock.store') }}' : '/demo/stock/' + selected?.id" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div>
                        <label class="modal-label">Product Name</label>
                        <input type="text" name="name" required class="modal-input" :value="selected?.name ?? ''" placeholder="e.g. Premium Oil Pack 1L">
                    </div>
                    <div>
                        <label class="modal-label">SKU</label>
                        <input type="text" name="sku" class="modal-input" :value="selected?.sku ?? ''" placeholder="e.g. SKU-PROD-001">
                    </div>
                    <div>
                        <label class="modal-label">Quantity</label>
                        <input type="number" name="quantity" required class="modal-input" :value="selected?.quantity ?? ''" placeholder="50">
                    </div>
                    <div>
                        <label class="modal-label">Unit Price (PKR)</label>
                        <input type="number" step="0.01" name="unit_price" required class="modal-input" :value="selected?.unit_price ?? ''" placeholder="450">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="open = false" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save" x-text="mode === 'create' ? 'Add Stock Item' : 'Save Changes'"></button>
                    </div>
                </form>
            </div>
        </template>

        <template x-if="mode === 'view'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4">Stock Item Details</h3>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">Name:</span><span class="font-bold text-slate-800" x-text="selected?.name"></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">SKU:</span><span class="font-mono font-bold text-slate-800" x-text="selected?.sku"></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">Quantity:</span><span class="font-bold text-slate-800" x-text="selected?.quantity"></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">Unit Price:</span><span class="font-bold text-slate-800" x-text="'PKR ' + selected?.unit_price"></span></div>
                    <div class="flex justify-between py-1"><span class="text-slate-500 font-semibold">Status:</span><span class="font-bold text-slate-900" x-text="selected?.status"></span></div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-5">
                    <button type="button" @click="open = false" class="btn-modal-cancel">Close</button>
                </div>
            </div>
        </template>

        <template x-if="mode === 'delete'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Delete Stock Item</h3>
                <p class="text-xs text-slate-600 mb-4">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="selected?.name"></span>?</p>
                <form :action="'/demo/stock/' + selected?.id" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="open = false" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-delete">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </template>
    </div>
</div>
@endsection
