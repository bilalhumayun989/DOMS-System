@extends('layouts.app')
@php $pageTitle = 'Return Claims'; @endphp

@section('content')
<div x-data="{ createOpen: false }">
    <div class="mb-5 flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-widest text-gray-800">AAA Traders</p><h2 class="mt-1 text-2xl font-black text-slate-900">Return Claims</h2><p class="mt-1 text-sm text-slate-500">Daily returns, expiry claims, damage reviews, and distributor credit notes.</p></div><a href="{{ route('returns.create') }}" class="btn-primary">+ New Return Claim</a></div>

    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([['Total Pending Returns Today',14500,'#111111','#e8e8e8'],['Total Expiry Claims (This Month)',85200,'#444444','#ececec'],['Total Damaged Goods (This Month)',24100,'#333333','#ebebeb'],['Distributor Approved Claims (Cr Note Received)',65000,'#222222','#f0f0f0']] as [$label,$value,$color,$background])
        <div class="min-h-32 rounded-xl border border-slate-200 p-5 shadow-sm" style="background:{{ $background }}"><p class="text-xs font-bold uppercase tracking-wide" style="color:{{ $color }};opacity:.75">{{ $label }}</p><p class="mt-2 text-2xl font-black" style="color:{{ $color }}">{{ pkr($value) }}</p></div>
        @endforeach
    </div>

    <section class="page-card mb-5">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Returns Main Table</h3>
                <p class="page-card-sub">Every return is tracked as a claim with distributor settlement status.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full doms-table min-w-[75rem]">
                <thead>
                    <tr>
                        <th class="text-left">Return ID / Claim No</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Market / Shop Name</th>
                        <th class="text-left">Distributor Name</th>
                        <th class="text-left">Driver / Salesman Name</th>
                        <th class="text-left">Return Type</th>
                        <th class="text-left">Total Cartons / Units Returned</th>
                        <th class="text-right">Total Return Value (PKR)</th>
                        <th class="text-left">Return Status</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                    <tr class="">
                        <td class="font-mono text-xs font-black text-gray-900">{{ $return['return_ref'] }}</td>
                        <td class="whitespace-nowrap">{{ $return['date'] }}</td>
                        <td><p class="font-semibold text-slate-800">{{ $return['shop'] }}</p><p class="text-xs text-slate-500">{{ $return['market'] }}</p></td>
                        <td>{{ $return['distributor'] }}</td>
                        <td>{{ $return['deliveryman'] }}</td>
                        <td>{{ $return['return_type'] }}</td>
                        <td>{{ $return['units'] }}</td>
                        <td class="text-right font-black text-slate-800">{{ pkr($return['value']) }}</td>
                        <td><x-status-badge :status="$return['status']" /></td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('returns.show', $return['id']) }}" class="btn-row btn-view">View</a>
                                <a href="{{ route('returns.edit', $return['id']) }}" class="btn-row btn-edit">Edit</a>
                                <form method="POST" action="{{ route('returns.destroy', $return['id']) }}" onsubmit="return confirm('Delete this return claim?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-row btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @if(false)
    <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" @click.self="close()"><div class="max-h-[92vh] w-full max-w-6xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl" @click.stop><div class="flex items-start justify-between border-b border-slate-100 pb-4"><div><p class="text-xs font-bold uppercase tracking-widest text-gray-800">Return Claim Detail</p><h3 class="mt-1 text-xl font-black text-slate-900" x-text="selected?.return_ref"></h3></div><button type="button" @click="close()" class="text-2xl text-slate-400">&times;</button></div>
        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2"><section class="rounded-xl border border-slate-200 p-5"><h4 class="font-black text-slate-800">A. Return Header &amp; Audit Info</h4><div class="mt-4 grid grid-cols-2 gap-4 text-sm"><div><span class="text-xs text-slate-400">Return Reference</span><p class="font-bold" x-text="selected?.return_ref"></p></div><div><span class="text-xs text-slate-400">Date &amp; Time</span><p class="font-bold" x-text="selected?.date"></p></div><div><span class="text-xs text-slate-400">Trip / Invoice Reference</span><p class="font-bold" x-text="(selected?.trip_display ?? '') + ' / ' + (selected?.invoice_ref ?? '')"></p></div><div><span class="text-xs text-slate-400">Shopkeeper / Market</span><p class="font-bold" x-text="(selected?.shop ?? '') + ' (' + (selected?.market ?? '') + ')' "></p></div><div><span class="text-xs text-slate-400">Deliveryman / Salesman</span><p class="font-bold" x-text="selected?.deliveryman"></p></div><div><span class="text-xs text-slate-400">Distributor</span><p class="font-bold" x-text="selected?.distributor"></p></div></div></section>
        <section class="rounded-xl border border-slate-200 p-5"><h4 class="font-black text-slate-800">C. Reason &amp; Classification Analysis</h4><div class="mt-4 space-y-3 text-sm"><p><span class="text-slate-400">Main Reason Category</span><strong class="ml-2" x-text="selected?.main_reason"></strong></p><p><span class="text-slate-400">Detailed Remarks / Notes</span><strong class="ml-2" x-text="selected?.remarks"></strong></p><p><span class="text-slate-400">Physical Condition</span><strong class="ml-2" x-text="selected?.condition"></strong></p></div></section></div>
        <section class="mt-5 rounded-xl border border-slate-200 p-5"><h4 class="mb-4 font-black text-slate-800">B. SKU-Wise Returned Items Breakdown</h4><div class="overflow-x-auto"><table class="w-full min-w-[48rem] text-sm"><thead><tr class="border-b border-slate-100 text-left text-xs font-bold uppercase tracking-wide text-slate-400"><th class="pb-3">SKU / Item Name</th><th class="pb-3">Batch No. / Expiry</th><th class="pb-3">Returned Qty</th><th class="pb-3 text-right">Unit Rate (PKR)</th><th class="pb-3 text-right">Line Total Value (PKR)</th><th class="pb-3">Primary Return Reason</th></tr></thead><tbody><template x-for="item in (selected?.items ?? [])" :key="item.sku"><tr class="border-b border-slate-50"><td class="py-3 font-semibold" x-text="item.sku"></td><td class="py-3 font-mono text-xs" x-text="item.batch"></td><td class="py-3" x-text="item.quantity"></td><td class="py-3 text-right" x-text="'PKR ' + Number(item.rate).toLocaleString('en-US', {minimumFractionDigits: 2})"></td><td class="py-3 text-right font-bold" x-text="'PKR ' + Number(item.line_total).toLocaleString('en-US', {minimumFractionDigits: 2})"></td><td class="py-3" x-text="item.reason"></td></tr></template></tbody></table></div></section>
        <section class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-5"><h4 class="mb-4 font-black text-slate-800">D. Financial &amp; Settlement Status</h4><div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2 lg:grid-cols-4"><div><span class="text-xs text-slate-400">Gross Returned Amount</span><p class="font-black text-gray-900" x-text="'PKR ' + Number(selected?.value ?? 0).toLocaleString('en-US', {minimumFractionDigits: 2})"></p></div><div><span class="text-xs text-slate-400">Adjusted Against Invoice / Credit Note No</span><p class="font-bold" x-text="selected?.credit_note"></p></div><div><span class="text-xs text-slate-400">Impact on Salesman Cash</span><p class="font-bold" x-text="selected?.impact"></p></div><div><span class="text-xs text-slate-400">Distributor Claim Status</span><p class="font-bold" x-text="selected?.claim_status"></p></div></div></section>
    </div></div>
</div>
    @endif

<div x-show="createOpen" x-cloak x-transition class="modal-backdrop flex items-center justify-center p-4" @click.self="createOpen = false">
    <div class="modal-panel max-h-[92vh] w-full max-w-4xl overflow-y-auto p-6" @click.stop>
        <div class="flex items-center justify-between border-b border-slate-100 pb-4"><div><h3 class="text-xl font-black text-slate-900">New Return Claim</h3><p class="mt-1 text-xs text-slate-500">Enter the complete return and distributor claim record.</p></div><button type="button" @click="createOpen = false" class="text-2xl text-slate-400">&times;</button></div>
        <form class="mt-5 space-y-6" @submit.prevent="createOpen = false">
            <section><h4 class="mb-3 text-sm font-black text-slate-800">A. Return Header &amp; Audit Info</h4><div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="modal-label">Return Reference *</label><input required placeholder="RET-2026-09-004" class="modal-input"></div><div><label class="modal-label">Date *</label><input type="date" required class="modal-input"></div><div><label class="modal-label">Trip Reference *</label><input required placeholder="TR-2026-09-02-001" class="modal-input"></div><div><label class="modal-label">Invoice Reference *</label><input required placeholder="INV-8892" class="modal-input"></div><div><label class="modal-label">Shopkeeper / Market *</label><input required placeholder="Al-Noor General Store (Gulshan-e-Iqbal)" class="modal-input"></div><div><label class="modal-label">Deliveryman / Salesman *</label><input required placeholder="Ahmed Khan" class="modal-input"></div><div><label class="modal-label">Distributor *</label><input required value="AAA Traders" class="modal-input"></div><div><label class="modal-label">Return Type *</label><select required class="modal-input"><option>Market Return</option><option>Expiry Claim</option><option>Damage In Transit</option><option>Factory Defect</option></select></div>
            </div></section>
            <section><h4 class="mb-3 text-sm font-black text-slate-800">B. SKU-Wise Returned Items Breakdown</h4><div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="modal-label">SKU / Item Name *</label><select required class="modal-input"><option>Sooper FP</option><option>Rio Chocolate</option><option>Gluco Family</option><option>Pepsi 1.5L</option></select></div><div><label class="modal-label">Batch No. / Expiry *</label><input required placeholder="BATCH-2026-042" class="modal-input"></div><div><label class="modal-label">Returned Qty (Cartons/Packs) *</label><input required placeholder="2 Cartons" class="modal-input"></div><div><label class="modal-label">Unit Rate (PKR) *</label><input type="number" min="0" step="0.01" required placeholder="2400.00" class="modal-input"></div><div><label class="modal-label">Line Total Value (PKR) *</label><input type="number" min="0" step="0.01" required placeholder="4800.00" class="modal-input"></div><div><label class="modal-label">Primary Return Reason *</label><select required class="modal-input"><option>Expired Product</option><option>Damaged Packaging</option><option>Wrong Item Delivered</option><option>Market Refusal</option></select></div>
            </div></section>
            <section><h4 class="mb-3 text-sm font-black text-slate-800">C. Reason &amp; Classification Analysis</h4><div class="grid grid-cols-1 gap-4 md:grid-cols-2"><div><label class="modal-label">Main Reason Category *</label><select required class="modal-input"><option>Expiry</option><option>Damage</option><option>Market Refusal</option><option>Quality Defect</option></select></div><div><label class="modal-label">Physical Condition *</label><select required class="modal-input"><option>Good Condition [Re-stockable]</option><option>Damaged [Send to Distributor Claim]</option><option>Scrap</option></select></div><div class="md:col-span-2"><label class="modal-label">Detailed Remarks / Notes</label><textarea rows="3" placeholder="Explain the return condition and reason" class="modal-input"></textarea></div></div></section>
            <section><h4 class="mb-3 text-sm font-black text-slate-800">D. Financial &amp; Settlement Status</h4><div class="grid grid-cols-1 gap-4 md:grid-cols-2"><div><label class="modal-label">Gross Returned Amount (PKR) *</label><input type="number" min="0" step="0.01" required placeholder="14500.00" class="modal-input"></div><div><label class="modal-label">Adjusted Against Invoice / Credit Note No</label><input placeholder="CN-2026-102" class="modal-input"></div><div><label class="modal-label">Impact on Salesman Cash *</label><select required class="modal-input"><option>Deducted from sales</option><option>Adjusted in shortage balance</option></select></div><div><label class="modal-label">Distributor Claim Status *</label><select required class="modal-input"><option>Pending Claim Submission to AAA Traders</option><option>Submitted to AAA Traders</option><option>Credit note received from AAA Traders</option></select></div></div></section>
            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" @click="createOpen = false" class="btn-modal-cancel">Cancel</button>
                <button class="btn-modal-save">Save Return Claim</button>
            </div>
        </form>
    </div>
</div>
@endsection
