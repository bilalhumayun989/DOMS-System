@extends('layouts.app')
@php $pageTitle = $trip['trip_id']; @endphp

@section('content')
@if(session('success'))
<div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
    <p class="font-bold">Please fix this:</p>
    <ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

<div class="mb-5 rounded-2xl border border-slate-200 bg-white p-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-3">
                <span class="font-mono text-xl font-bold text-slate-900">{{ $trip['trip_id'] }}</span>
                <x-status-badge :status="$trip['status']"/>
            </div>
            <p class="text-sm text-slate-500">{{ $trip['deliveryman']['name'] }} · {{ $trip['market_area'] }} · {{ $trip['vehicle'] }}</p>
            <p class="mt-1 text-xs text-slate-400">DLF: {{ $trip['source_dlf'] ?: 'Not provided' }} · {{ $trip['date'] }}</p>
        </div>
        <a href="{{ route('trips.index', ['filter' => 'open']) }}" class="rounded-lg bg-slate-100 px-4 py-2 text-xs font-bold text-slate-600">← Open Trips</a>
    </div>
</div>

{{-- Always-visible money position --}}
<div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-5">
    @foreach([
        ['Load Value', $trip['load_value'], 'text-slate-800', 'bg-white'],
        ['Expected Cash', $settlement['expected_cash'], 'text-blue-700', 'bg-blue-50'],
        ['Cash Collected', $settlement['collected_amount'], 'text-green-700', 'bg-green-50'],
        ['Trip Expenses', $settlement['expense_amount'], 'text-orange-700', 'bg-orange-50'],
        ['Remaining / Difference', $settlement['shortage_amount'], $settlement['shortage_amount'] == 0 ? 'text-green-700' : 'text-red-700', $settlement['shortage_amount'] == 0 ? 'bg-green-50' : 'bg-red-50'],
    ] as [$label, $amount, $color, $background])
    <div class="rounded-2xl border border-slate-200 p-4 {{ $background }}">
        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $label }}</p>
        <p class="mt-2 text-lg font-black {{ $color }}">{{ pkr($amount) }}</p>
    </div>
    @endforeach
</div>

@if($trip['status'] !== 'CLOSED')
<div id="trip-entry" x-data="{ entry: window.location.hash === '#trip-entry' ? 'collection' : null, payment: 'Cash', result: @js($trip['delivery_result'] ?? ''), withExpense: false }" class="mb-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div>
            <h2 class="text-lg font-black text-slate-900">Trip Entry</h2>
            <p class="mt-1 text-sm text-slate-500">Record delivery, money, and expenses here. The trip stays open until you press Close Trip.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" @click="entry = entry === 'collection' ? null : 'collection'" :class="entry === 'collection' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700'" class="rounded-lg px-4 py-2 text-sm font-bold">+ Collection</button>
            <button type="button" @click="entry = entry === 'expense' ? null : 'expense'" :class="entry === 'expense' ? 'bg-orange-500 text-white' : 'bg-orange-50 text-orange-700'" class="rounded-lg px-4 py-2 text-sm font-bold">+ Expense</button>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Delivery result --}}
        <form method="POST" action="{{ route('trips.delivery-result.update', $trip['id']) }}" class="rounded-xl border border-blue-100 bg-blue-50/40 p-5">
            @csrf @method('PUT')
            <h3 class="font-bold text-slate-800">Delivery Result</h3>
            <p class="mb-4 mt-1 text-xs text-slate-500">Mention what happened in the market.</p>
            <label class="mb-1 block text-xs font-bold text-slate-600">Result <span class="text-red-500">*</span></label>
            <select name="delivery_result" x-model="result" required class="w-full rounded-lg border-slate-200 text-sm">
                <option value="">Select result</option>
                @foreach(['DELIVERED' => 'Delivered', 'PARTIAL' => 'Partially Delivered', 'DELAYED' => 'Delayed', 'NOT DELIVERED' => 'Not Delivered', 'RESERVICE' => 'Reservice / Try Again', 'OTHER' => 'Other'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <div x-show="result === 'DELAYED' || result === 'RESERVICE'" x-cloak class="mt-3">
                <label class="mb-1 block text-xs font-bold text-slate-600">Follow-up date <span class="text-red-500">*</span></label>
                <input type="date" name="follow_up_date" value="{{ $trip['follow_up_date'] }}" class="w-full rounded-lg border-slate-200 text-sm">
            </div>
            <div class="mt-3">
                <label class="mb-1 block text-xs font-bold text-slate-600">Remarks / reason</label>
                <textarea name="delivery_notes" rows="3" placeholder="Delay reason, partial delivery details, shop closed, or other remarks..." class="w-full rounded-lg border-slate-200 text-sm">{{ $trip['delivery_notes'] }}</textarea>
            </div>
            <button class="mt-3 w-full rounded-lg bg-blue-600 py-2.5 text-sm font-bold text-white">Save Delivery Result</button>
        </form>

        {{-- Simple guidance / current result --}}
        <div class="rounded-xl border border-slate-200 p-5">
            <h3 class="font-bold text-slate-800">Current Trip Position</h3>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Delivery result</span><span class="font-bold text-slate-800">{{ $trip['delivery_result'] ?: 'Not entered' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Collections</span><span class="font-bold text-green-700">{{ count($collections) }} entries</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Expenses</span><span class="font-bold text-orange-700">{{ count($expenses) }} entries</span></div>
                <div class="rounded-lg bg-amber-50 p-3 text-xs leading-5 text-amber-800">Adding a collection or expense never closes the trip. Review the remaining amount, then use the separate Close Trip section below.</div>
            </div>
        </div>
    </div>

    {{-- Collection entry --}}
    <div x-show="entry === 'collection'" x-cloak x-transition class="mt-5 rounded-xl border-2 border-green-200 bg-green-50/30 p-5">
        <div class="mb-4"><h3 class="font-black text-green-800">Add Collection</h3><p class="text-xs text-slate-500">Record who paid, against which invoice, and how payment was received.</p></div>
        <form method="POST" action="{{ route('trips.collections.store', $trip['id']) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Customer / Market *</label><input name="customer" value="{{ old('customer') }}" required placeholder="e.g. Al-Noor General Store" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Invoice Number *</label><input name="invoice_number" value="{{ old('invoice_number') }}" required placeholder="e.g. INV-001" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Amount Collected (PKR) *</label><input type="number" name="amount" value="{{ old('amount') }}" min="0.01" step="0.01" required placeholder="0.00" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Payment Method *</label><select name="method" x-model="payment" required class="w-full rounded-lg border-slate-200 text-sm"><option>Cash</option><option>Cheque</option><option>Transfer</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Received At *</label><input type="datetime-local" name="collected_at" value="{{ old('collected_at', now()->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div x-show="payment === 'Cheque'" x-cloak><label class="mb-1 block text-xs font-bold text-slate-600">Cheque Number *</label><input name="cheque_number" value="{{ old('cheque_number') }}" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div x-show="payment === 'Cheque'" x-cloak><label class="mb-1 block text-xs font-bold text-slate-600">Bank Name *</label><input name="bank_name" value="{{ old('bank_name') }}" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div x-show="payment === 'Cheque'" x-cloak><label class="mb-1 block text-xs font-bold text-slate-600">Cheque Date *</label><input type="date" name="instrument_date" value="{{ old('instrument_date') }}" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div x-show="payment === 'Transfer'" x-cloak><label class="mb-1 block text-xs font-bold text-slate-600">Bank Reference *</label><input name="bank_reference" value="{{ old('bank_reference') }}" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs font-bold text-slate-600">Collection Notes</label><textarea name="notes" rows="2" placeholder="Optional payment remarks" class="w-full rounded-lg border-slate-200 text-sm">{{ old('notes') }}</textarea></div>

            <div class="md:col-span-2 rounded-xl border border-orange-200 bg-orange-50 p-4">
                <label class="flex cursor-pointer items-center justify-between gap-4">
                    <span><span class="block text-sm font-black text-orange-800">Also add an expense with this collection</span><span class="block text-xs text-orange-700">Use this when the deliveryman returns cash together with fuel, toll, or another expense.</span></span>
                    <input type="checkbox" x-model="withExpense" class="h-5 w-5 rounded border-orange-300 text-orange-500 focus:ring-orange-400">
                </label>
                <div x-show="withExpense" x-cloak x-transition class="mt-4 grid grid-cols-1 gap-3 border-t border-orange-200 pt-4 md:grid-cols-2">
                    <div><label class="mb-1 block text-xs font-bold text-slate-600">Expense Type *</label><select name="expense_category" class="w-full rounded-lg border-slate-200 bg-white text-sm">@foreach(['Fuel','Toll','Parking','Loading','Driver Allowance','Vehicle Repair','Other'] as $category)<option>{{ $category }}</option>@endforeach</select></div>
                    <div><label class="mb-1 block text-xs font-bold text-slate-600">Actual Expense (PKR) *</label><input type="number" name="expense_amount" min="0.01" step="0.01" placeholder="Enter actual amount" class="w-full rounded-lg border-slate-200 bg-white text-sm"></div>
                    <div><label class="mb-1 block text-xs font-bold text-slate-600">Expense Date *</label><input type="date" name="expense_date" value="{{ now()->toDateString() }}" class="w-full rounded-lg border-slate-200 bg-white text-sm"></div>
                    <div><label class="mb-1 block text-xs font-bold text-slate-600">Receipt / Description</label><input name="expense_description" placeholder="e.g. Fuel receipt #123" class="w-full rounded-lg border-slate-200 bg-white text-sm"></div>
                    <p class="text-xs text-orange-700 md:col-span-2">If the expense changes later, use the visible <strong>Edit expense</strong> action in Expense History below.</p>
                </div>
            </div>
            <div class="flex justify-end gap-2 md:col-span-2"><button type="button" @click="entry = null" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-600">Cancel</button><button class="rounded-lg bg-green-600 px-6 py-2.5 text-sm font-bold text-white">Save Collection</button></div>
        </form>
    </div>

    {{-- Expense entry --}}
    <div x-show="entry === 'expense'" x-cloak x-transition class="mt-5 rounded-xl border-2 border-orange-200 bg-orange-50/30 p-5">
        <div class="mb-4"><h3 class="font-black text-orange-800">Add Trip Expense</h3><p class="text-xs text-slate-500">Add fuel, toll, parking, loading, or another trip cost.</p></div>
        <form method="POST" action="{{ route('trips.expenses.store', $trip['id']) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Expense Type *</label><select name="category" required class="w-full rounded-lg border-slate-200 text-sm">@foreach(['Fuel','Toll','Parking','Loading','Driver Allowance','Vehicle Repair','Other'] as $category)<option>{{ $category }}</option>@endforeach</select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Amount (PKR) *</label><input type="number" name="amount" value="{{ old('amount') }}" min="0.01" step="0.01" required placeholder="0.00" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Expense Date *</label><input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" required class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Description / Receipt</label><input name="description" value="{{ old('description') }}" placeholder="e.g. Fuel receipt #123" class="w-full rounded-lg border-slate-200 text-sm"></div>
            <div class="flex justify-end gap-2 md:col-span-2"><button type="button" @click="entry = null" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-600">Cancel</button><button class="rounded-lg bg-orange-500 px-6 py-2.5 text-sm font-bold text-white">Save Expense</button></div>
        </form>
    </div>
</div>
@else
<div class="mb-5 rounded-xl border border-slate-200 bg-slate-100 px-5 py-4 text-sm font-semibold text-slate-600">This trip is CLOSED. Its delivery result, collections, expenses, and settlement are locked.</div>
@endif

<div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><h3 class="font-black text-slate-800">Collections</h3><span class="text-xs font-bold text-green-700">{{ pkr($settlement['collected_amount']) }}</span></div>
        <div class="divide-y divide-slate-100">
            @forelse($collections as $col)
            <div class="p-4">
                <div class="flex justify-between gap-3"><div><p class="text-sm font-bold text-slate-800">{{ $col['customer'] }}</p><p class="text-xs text-slate-400">{{ $col['invoice_number'] }} · {{ $col['method'] }} · {{ $col['collected_at'] }}</p></div><p class="font-black text-green-700">{{ pkr($col['amount']) }}</p></div>
                @if($trip['status'] !== 'CLOSED')<details class="mt-2"><summary class="cursor-pointer text-xs font-bold text-blue-600">Edit entry</summary><form method="POST" action="{{ route('trips.collections.update', [$trip['id'], $col['id']]) }}" class="mt-3 grid grid-cols-2 gap-2">@csrf @method('PUT')<input name="customer" value="{{ $col['customer'] }}" required class="rounded border-slate-200 text-xs"><input name="invoice_number" value="{{ $col['invoice_number'] }}" required class="rounded border-slate-200 text-xs"><input type="number" name="amount" min=".01" step=".01" value="{{ $col['amount'] }}" required class="rounded border-slate-200 text-xs"><select name="method" class="rounded border-slate-200 text-xs">@foreach(['Cash','Cheque','Transfer'] as $method)<option @selected($col['method']===$method)>{{ $method }}</option>@endforeach</select><input type="datetime-local" name="collected_at" value="{{ str_replace(' ', 'T', $col['collected_at']) }}" required class="rounded border-slate-200 text-xs"><button class="rounded bg-blue-600 text-xs font-bold text-white">Save Changes</button></form></details>@endif
            </div>
            @empty<div class="p-8 text-center text-sm text-slate-400">No collection recorded yet.</div>@endforelse
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><h3 class="font-black text-slate-800">Expenses</h3><span class="text-xs font-bold text-orange-700">{{ pkr($settlement['expense_amount']) }}</span></div>
        <div class="divide-y divide-slate-100">
            @forelse($expenses as $expense)
            <div class="p-4"><div class="flex justify-between gap-3"><div><p class="text-sm font-bold text-slate-800">{{ $expense['category'] }}</p><p class="text-xs text-slate-400">{{ $expense['expense_date'] }} · {{ $expense['description'] ?: 'No description' }}</p></div><p class="font-black text-orange-700">{{ pkr($expense['amount']) }}</p></div>
                @if($trip['status'] !== 'CLOSED')<details class="mt-3 rounded-lg border border-blue-100 bg-blue-50 p-2"><summary class="cursor-pointer text-xs font-black text-blue-700">Edit expense amount or details</summary><form method="POST" action="{{ route('trips.expenses.update', [$trip['id'], $expense['id']]) }}" class="mt-3 grid grid-cols-2 gap-2">@csrf @method('PUT')<select name="category" class="rounded border-slate-200 bg-white text-xs">@foreach(['Fuel','Toll','Parking','Loading','Driver Allowance','Vehicle Repair','Other'] as $category)<option @selected($expense['category']===$category)>{{ $category }}</option>@endforeach</select><input type="number" name="amount" min=".01" step=".01" value="{{ $expense['amount'] }}" class="rounded border-slate-200 bg-white text-xs"><input type="date" name="expense_date" value="{{ $expense['expense_date'] }}" class="rounded border-slate-200 bg-white text-xs"><input name="description" value="{{ $expense['description'] }}" class="rounded border-slate-200 bg-white text-xs"><button class="col-span-2 rounded bg-blue-600 py-2 text-xs font-bold text-white">Update Expense</button></form></details>@endif
            </div>
            @empty<div class="p-8 text-center text-sm text-slate-400">No expense recorded yet.</div>@endforelse
        </div>
    </div>
</div>

{{-- Explicit closure --}}
<div class="rounded-2xl border {{ $trip['status'] === 'CLOSED' ? 'border-green-200 bg-green-50' : 'border-slate-300 bg-white' }} p-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div><h3 class="text-lg font-black text-slate-900">{{ $trip['status'] === 'CLOSED' ? 'Trip Closed' : 'Close Trip' }}</h3><p class="mt-1 text-sm text-slate-500">{{ $trip['status'] === 'CLOSED' ? 'Settlement is complete and all entries are locked.' : 'Review collection, expenses, and remaining amount before final closure.' }}</p></div>
        @if($trip['status'] !== 'CLOSED' && count($collections) === 0)<span class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-bold text-amber-800">Add at least one collection to enable closing</span>@endif
    </div>
    @if($trip['status'] !== 'CLOSED' && count($collections) > 0)
    <form method="POST" action="{{ route('trips.close', $trip['id']) }}" class="mt-5 grid grid-cols-1 gap-4 border-t border-slate-100 pt-5 md:grid-cols-2">
        @csrf
        <div><label class="mb-1 block text-xs font-bold text-slate-600">Difference Classification @if(abs($settlement['shortage_amount']) > 0.009)<span class="text-red-500">*</span>@endif</label><select name="shortage_classification" class="w-full rounded-lg border-slate-200 text-sm"><option value="">No difference / select reason</option><option>MARKET SHORT</option><option>DELIVERYMAN SHORT</option><option>APPROVED WRITE-OFF</option><option>PENDING INVESTIGATION</option></select></div>
        <div><label class="mb-1 block text-xs font-bold text-slate-600">Final Settlement Notes</label><input name="notes" placeholder="Explain shortage, overage, or final confirmation" class="w-full rounded-lg border-slate-200 text-sm"></div>
        <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3 md:col-span-2"><p class="text-sm text-slate-600">Remaining / difference: <strong class="text-red-700">{{ pkr($settlement['shortage_amount']) }}</strong></p><button class="rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-black text-white" onclick="return confirm('Close and lock this trip?')">Close Trip</button></div>
    </form>
    @endif
</div>
@endsection
