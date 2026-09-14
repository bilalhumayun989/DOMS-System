@extends('layouts.app')
@php $limit = \App\Http\Controllers\DemoController::DEMO_LIMIT; @endphp

@section('content')

@if(session('success'))
<div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-sm font-semibold">
    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('demo_limit'))
<div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-100 border border-gray-300 text-gray-700 text-sm font-semibold">
    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    ⚠ Demo Limit Reached — {{ session('demo_limit') }}
</div>
@endif
@if($errors->any())
<div class="mb-4 px-4 py-3 rounded-xl bg-gray-100 border border-gray-300 text-gray-700 text-sm font-semibold">
    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
</div>
@endif

<div x-data="{ showAdd: false, mode: 'create', selected: null }">
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h2 class="page-card-title">Trips</h2>
                <p class="page-card-sub">DEMO SESSION — {{ count($trips) }} trip(s) total &nbsp;|&nbsp; {{ $used }}/{{ $limit }} created this session</p>
            </div>
            <button @click="mode = 'create'; selected = null; showAdd = true;" class="btn-primary" {{ $used >= $limit ? 'disabled style=opacity:0.5;cursor:not-allowed' : '' }}>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Trip {{ $used >= $limit ? '(Limit Reached)' : "({$used}/{$limit})" }}
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="doms-table w-full text-left">
                <thead>
                    <tr>
                        <th>Route ID</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Market / Area</th>
                        <th>Date</th>
                        <th class="text-right">Load Value</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trips as $trip)
                    <tr>
                        <td class="mono-id">{{ $trip['route_id'] }}</td>
                        <td class="font-semibold text-slate-900">{{ $trip['deliveryman'] }}</td>
                        <td class="text-slate-500">{{ $trip['vehicle'] }}</td>
                        <td class="text-slate-500">{{ $trip['market_area'] }}</td>
                        <td class="font-semibold text-slate-700">{{ $trip['date'] }}</td>
                        <td class="text-right font-semibold">{{ pkr($trip['load_value']) }}</td>
                        <td><span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ $trip['status'] }}</span></td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('demo.trips.show', $trip['id']) }}" class="btn-row btn-view">View</a>
                                <button @click="mode = 'edit'; selected = {{ json_encode($trip) }}; showAdd = true;" class="btn-row btn-edit">Edit</button>
                                <button @click="mode = 'delete'; selected = {{ json_encode($trip) }}; showAdd = true;" class="btn-row btn-delete">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if(count($trips) === 0)
                    <tr><td colspan="8" class="text-center py-10 text-slate-400 font-medium">No trips in this demo session yet. Click "Add Trip" above.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add/Edit/Delete Trip Modal --}}
    <div x-show="showAdd" x-cloak class="modal-backdrop" @click.self="showAdd = false">
        <div class="modal-panel" style="max-width: 32rem; width: 100%; padding: 1.5rem;">
            {{-- CREATE MODE --}}
            <template x-if="mode === 'create'">
                <div>
                    <h3 class="text-base font-black text-slate-900 mb-5 flex items-center justify-between">
                        <span>New Demo Trip</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-200 text-gray-700 border border-gray-300">{{ $used }}/{{ $limit }} used</span>
                    </h3>
                    <form action="{{ route('demo.trips.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="modal-label">Trip Date</label>
                                <input type="date" name="trip_date" class="modal-input" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="modal-label">Deliveryman Name</label>
                                <input type="text" name="deliveryman_name" class="modal-input" placeholder="e.g. Ahmed Khan" required>
                            </div>
                            <div>
                                <label class="modal-label">Vehicle</label>
                                <input type="text" name="vehicle" class="modal-input" placeholder="e.g. Toyota Hilux" required>
                            </div>
                            <div>
                                <label class="modal-label">Market Area</label>
                                <input type="text" name="market_area" class="modal-input" placeholder="e.g. Gulshan" required>
                            </div>
                            <div>
                                <label class="modal-label">Load Value (PKR)</label>
                                <input type="number" name="load_value" class="modal-input" placeholder="50000" min="0" step="0.01" required>
                            </div>
                            <div>
                                <label class="modal-label">Expected Cash (PKR)</label>
                                <input type="number" name="expected_cash" class="modal-input" placeholder="48000" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-2">
                            <button type="button" @click="showAdd = false" class="btn-modal-cancel">Cancel</button>
                            <button type="submit" class="btn-modal-save">Create Demo Trip</button>
                        </div>
                    </form>
                </div>
            </template>

            {{-- EDIT MODE --}}
            <template x-if="mode === 'edit'">
                <div>
                    <h3 class="text-base font-black text-slate-900 mb-5">Edit Demo Trip</h3>
                    <form :action="'/demo/trips/' + selected?.id" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="modal-label">Trip Date</label>
                                <input type="date" name="trip_date" class="modal-input" :value="selected?.date" required>
                            </div>
                            <div>
                                <label class="modal-label">Deliveryman Name</label>
                                <input type="text" name="deliveryman_name" class="modal-input" :value="selected?.deliveryman" placeholder="e.g. Ahmed Khan" required>
                            </div>
                            <div>
                                <label class="modal-label">Vehicle</label>
                                <input type="text" name="vehicle" class="modal-input" :value="selected?.vehicle" placeholder="e.g. Toyota Hilux" required>
                            </div>
                            <div>
                                <label class="modal-label">Market Area</label>
                                <input type="text" name="market_area" class="modal-input" :value="selected?.market_area" placeholder="e.g. Gulshan" required>
                            </div>
                            <div>
                                <label class="modal-label">Load Value (PKR)</label>
                                <input type="number" name="load_value" class="modal-input" :value="selected?.load_value" placeholder="50000" min="0" step="0.01" required>
                            </div>
                            <div>
                                <label class="modal-label">Expected Cash (PKR)</label>
                                <input type="number" name="expected_cash" class="modal-input" :value="selected?.expected_cash" placeholder="48000" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-2">
                            <button type="button" @click="showAdd = false" class="btn-modal-cancel">Cancel</button>
                            <button type="submit" class="btn-modal-save">Save Changes</button>
                        </div>
                    </form>
                </div>
            </template>

            {{-- DELETE MODE --}}
            <template x-if="mode === 'delete'">
                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Delete Trip</h3>
                    <p class="text-xs text-slate-600 mb-4">Are you sure you want to delete trip <span class="font-bold text-slate-900" x-text="selected?.route_id"></span>?</p>
                    <form :action="'/demo/trips/' + selected?.id" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="showAdd = false" class="btn-modal-cancel">Cancel</button>
                            <button type="submit" class="btn-modal-delete">Confirm Delete</button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection
