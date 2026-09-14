@extends('layouts.app')
@php
    $pageTitle = 'Banks';
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
                    <h2 class="page-card-title">Bank Accounts</h2>
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-gray-200 text-gray-700 border border-gray-300">
                        Demo Limit: {{ $used }}/{{ $limit }} Used
                    </span>
                </div>
                <p class="page-card-sub">{{ count($banks) }} bank accounts listed</p>
            </div>
            <button @click="mode = 'create'; selected = null; open = true;" class="btn-primary flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Bank Account
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full doms-table">
                <thead>
                    <tr>
                        <th class="text-left">Bank Name</th>
                        <th class="text-left">Account Type</th>
                        <th class="text-right">Balance</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banks as $b)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $b['name'] }}</td>
                        <td class="text-slate-600 text-xs font-medium">{{ $b['type'] }}</td>
                        <td class="text-right font-bold text-slate-900">{{ pkr($b['balance']) }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="mode = 'view'; selected = {{ json_encode($b) }}; open = true;" class="btn-row btn-view">View</button>
                                <button @click="mode = 'edit'; selected = {{ json_encode($b) }}; open = true;" class="btn-row btn-edit">Edit</button>
                                <button @click="mode = 'delete'; selected = {{ json_encode($b) }}; open = true;" class="btn-row btn-delete">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-slate-400 text-xs">No bank accounts added yet.</td>
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
        {{-- CREATE MODE --}}
        <template x-if="mode === 'create'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4">Add Demo Bank Account</h3>
                <form action="{{ route('demo.banks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="modal-label">Bank Name</label>
                        <input type="text" name="name" required class="modal-input" placeholder="e.g. HBL Main Branch">
                    </div>
                    <div>
                        <label class="modal-label">Account Type</label>
                        <input type="text" name="type" required class="modal-input" placeholder="e.g. Current Account">
                    </div>
                    <div>
                        <label class="modal-label">Balance (PKR)</label>
                        <input type="number" step="0.01" name="balance" required class="modal-input" placeholder="50000">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="open = false" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save">Add Bank Account</button>
                    </div>
                </form>
            </div>
        </template>

        {{-- EDIT MODE --}}
        <template x-if="mode === 'edit'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4">Edit Demo Bank Account</h3>
                <form :action="'/demo/banks/' + selected?.id" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div>
                        <label class="modal-label">Bank Name</label>
                        <input type="text" name="name" required class="modal-input" :value="selected?.name" placeholder="e.g. HBL Main Branch">
                    </div>
                    <div>
                        <label class="modal-label">Account Type</label>
                        <input type="text" name="type" required class="modal-input" :value="selected?.type" placeholder="e.g. Current Account">
                    </div>
                    <div>
                        <label class="modal-label">Balance (PKR)</label>
                        <input type="number" step="0.01" name="balance" required class="modal-input" :value="selected?.balance" placeholder="50000">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="open = false" class="btn-modal-cancel">Cancel</button>
                        <button type="submit" class="btn-modal-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </template>

        {{-- VIEW MODE --}}
        <template x-if="mode === 'view'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-4">Bank Account Details</h3>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-gray-600 font-semibold">Bank Name:</span><span class="font-bold text-slate-800" x-text="selected?.name"></span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100"><span class="text-gray-600 font-semibold">Account Type:</span><span class="font-bold text-slate-800" x-text="selected?.type"></span></div>
                    <div class="flex justify-between py-1"><span class="text-gray-600 font-semibold">Balance:</span><span class="font-bold text-slate-900" x-text="'PKR ' + selected?.balance"></span></div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-5">
                    <button type="button" @click="open = false" class="btn-modal-cancel">Close</button>
                </div>
            </div>
        </template>

        {{-- DELETE MODE --}}
        <template x-if="mode === 'delete'">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Delete Bank Account</h3>
                <p class="text-xs text-slate-600 mb-4">Are you sure you want to delete bank account <span class="font-bold text-slate-900" x-text="selected?.name"></span>?</p>
                <form :action="'/demo/banks/' + selected?.id" method="POST">
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
