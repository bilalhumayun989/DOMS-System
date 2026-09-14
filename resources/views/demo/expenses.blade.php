@extends('layouts.app')
@php
    $pageTitle = 'Expenses';
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
                    <h2 class="page-card-title">Expenses</h2>
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-gray-200 text-gray-700 border border-gray-300">
                        Demo Limit: {{ $used }}/{{ $limit }} Used
                    </span>
                </div>
                <p class="page-card-sub">{{ count($expenses) }} expenses recorded</p>
            </div>
            <button @click="mode = 'create'; selected = null; open = true;" class="btn-primary flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Expense
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Ref</th>
                        <th class="text-left">Category</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Description</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $exp)
                    <tr>
                        <td class="font-mono text-xs font-semibold text-slate-800">{{ $exp['ref'] }}</td>
                        <td class="font-semibold text-slate-800">{{ $exp['category'] }}</td>
                        <td class="text-slate-600 text-xs">{{ $exp['date'] }}</td>
                        <td class="text-slate-600 text-xs">{{ $exp['description'] }}</td>
                        <td class="text-right font-bold text-slate-900">{{ pkr($exp['amount']) }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="mode = 'view'; selected = {{ json_encode($exp) }}; open = true;" class="btn-row btn-view">View</button>
                                <button @click="mode = 'edit'; selected = {{ json_encode($exp) }}; open = true;" class="btn-row btn-edit">Edit</button>
                                <button @click="mode = 'delete'; selected = {{ json_encode($exp) }}; open = true;" class="btn-row btn-delete">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400 text-xs">No expenses recorded yet.</td>
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
                <h3 class="text-base font-bold text-slate-900 mb-4" x-text="mode === 'create' ? 'Record Demo Expense' : 'Edit Demo Expense'"></h3>
                <form :action="mode === 'create' ? '{{ route('demo.expenses.store') }}' : '/demo/expenses/' + selected?.id" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div>
                        <label class="modal-label">Category</label>
                        <select name="category" class="modal-input">
                            <option value="Fuel">Fuel</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Toll Tax">Toll Tax</option>
                            <option value="Refreshments">Refreshments</option>
                            <option value="Miscellaneous">Miscellaneous</option>
                        </select>
                    </div>
                    <div>
                        <label class="modal-label">Amount (PKR)</label>
                        <input type="number" step="0.01" name="amount" required class="modal-input" :value="selected?.amount ?? ''" placeholder="1500">
                    </div>
                    <div>
                        <label class="modal-label">Description</label>
                        <input type="text" name="description" class="modal-input" :value="selected?.description ?? ''" placeholder="e.g. Fuel refill for trip">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="open = false" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save" x-text="mode === 'create' ? 'Record Expense' : 'Save Changes'"></button>
                    </div>
                </form>
            </div>
        </template>

        <template x-if="mode === 'view'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4">Expense Details</h3>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">Ref:</span><span class="font-mono font-bold text-slate-800" x-text="selected?.ref"></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">Category:</span><span class="font-bold text-slate-800" x-text="selected?.category"></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">Date:</span><span class="font-bold text-slate-800" x-text="selected?.date"></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-slate-500 font-semibold">Description:</span><span class="font-bold text-slate-800" x-text="selected?.description"></span></div>
                    <div class="flex justify-between py-1"><span class="text-slate-500 font-semibold">Amount:</span><span class="font-bold text-slate-900" x-text="'PKR ' + selected?.amount"></span></div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-5">
                    <button type="button" @click="open = false" class="btn-modal-cancel">Close</button>
                </div>
            </div>
        </template>

        <template x-if="mode === 'delete'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Delete Expense</h3>
                <p class="text-xs text-slate-600 mb-4">Are you sure you want to delete expense <span class="font-bold text-slate-900" x-text="selected?.ref"></span>?</p>
                <form :action="'/demo/expenses/' + selected?.id" method="POST">
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
