@php
    $isEdit = isset($claim);
    $field = fn (string $name, mixed $default = '') => old($name, $claim[$name] ?? $default);
    $item = $claim['items'][0] ?? [];
@endphp

<form method="POST" action="{{ $isEdit ? route('returns.update', $claim['id']) : route('returns.store') }}" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <section><h3 class="mb-4 text-lg font-black text-slate-900">Return Header &amp; Audit Info</h3><div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        @foreach([['return_ref','Return Reference','RET-2026-09-004'],['date','Date',''],['trip_display','Trip Reference','TR-2026-09-02-001'],['invoice_ref','Invoice Reference','INV-8892'],['shop','Shop Name','Al-Noor General Store'],['market','Market','Gulshan-e-Iqbal'],['deliveryman','Deliveryman / Salesman','Ahmed Khan'],['distributor','Distributor','AAA Traders']] as [$name,$label,$placeholder])
            <div><label class="claim-label">{{ $label }} *</label><input name="{{ $name }}" type="{{ $name === 'date' ? 'date' : 'text' }}" value="{{ $name === 'date' ? old('date', $isEdit ? \Carbon\Carbon::parse($claim['date'])->format('Y-m-d') : now()->format('Y-m-d')) : $field($name) }}" placeholder="{{ $placeholder }}" {{ $name === 'return_ref' ? '' : 'required' }} class="claim-field"></div>
        @endforeach
        <div><label class="claim-label">Return Type *</label><select name="return_type" required class="claim-field">@foreach(['Market Return','Expiry Claim','Damage In Transit','Factory Defect'] as $option)<option @selected($field('return_type', 'Market Return') === $option)>{{ $option }}</option>@endforeach</select></div>
    </div></section>

    <section><h3 class="mb-4 text-lg font-black text-slate-900">Returned Item</h3><div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div><label class="claim-label">SKU / Item Name *</label><input name="sku" required value="{{ old('sku', $item['sku'] ?? '') }}" placeholder="Pepsi 1.5L" class="claim-field"></div>
        <div><label class="claim-label">Batch No. / Expiry *</label><input name="batch" required value="{{ old('batch', $item['batch'] ?? '') }}" placeholder="BATCH-2026-042" class="claim-field"></div>
        <div><label class="claim-label">Returned Quantity *</label><input name="quantity" required value="{{ old('quantity', $item['quantity'] ?? '') }}" placeholder="2 Cartons" class="claim-field"></div>
        <div><label class="claim-label">Unit Rate (PKR) *</label><input name="rate" type="number" min="0" step="0.01" required value="{{ old('rate', $item['rate'] ?? '') }}" class="claim-field"></div>
        <div><label class="claim-label">Line Total Value (PKR) *</label><input name="line_total" type="number" min="0" step="0.01" required value="{{ old('line_total', $item['line_total'] ?? '') }}" class="claim-field"></div>
        <div><label class="claim-label">Primary Return Reason *</label><input name="item_reason" required value="{{ old('item_reason', $item['reason'] ?? '') }}" placeholder="Damaged Packaging" class="claim-field"></div>
    </div></section>

    <section><h3 class="mb-4 text-lg font-black text-slate-900">Reason &amp; Settlement</h3><div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        @foreach([['main_reason','Main Reason Category','Expiry'],['condition','Physical Condition','Good Condition [Re-stockable]'],['remarks','Detailed Remarks / Notes',''],['credit_note','Credit Note Number','']] as [$name,$label,$placeholder])
            <div class="{{ in_array($name, ['remarks']) ? 'md:col-span-2' : '' }}"><label class="claim-label">{{ $label }}{{ in_array($name, ['remarks','credit_note']) ? '' : ' *' }}</label>@if($name === 'remarks')<textarea name="{{ $name }}" rows="3" placeholder="{{ $placeholder }}" class="claim-field">{{ $field($name) }}</textarea>@else<input name="{{ $name }}" value="{{ $field($name, $placeholder) }}" placeholder="{{ $placeholder }}" {{ in_array($name, ['main_reason','condition']) ? 'required' : '' }} class="claim-field">@endif</div>
        @endforeach
        <div><label class="claim-label">Return Status *</label><input name="status" required value="{{ $field('status', 'Pending Verification') }}" class="claim-field"></div>
        <div><label class="claim-label">Units Returned *</label><input name="units" required value="{{ $field('units') }}" placeholder="2 Cartons" class="claim-field"></div>
        <div><label class="claim-label">Gross Returned Amount (PKR) *</label><input name="value" type="number" min="0" step="0.01" required value="{{ $field('value') }}" class="claim-field"></div>
        <div><label class="claim-label">Impact on Salesman Cash *</label><input name="impact" required value="{{ $field('impact', 'Adjusted in shortage balance') }}" class="claim-field"></div>
        <div class="md:col-span-2"><label class="claim-label">Distributor Claim Status *</label><input name="claim_status" required value="{{ $field('claim_status', 'Pending Claim Submission to AAA Traders') }}" class="claim-field"></div>
    </div></section>

    <div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><a href="{{ $isEdit ? route('returns.show', $claim['id']) : route('returns.index') }}" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-600">Cancel</a><button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white">{{ $isEdit ? 'Update Return Claim' : 'Save Return Claim' }}</button></div>
</form>
<style>.claim-label{display:block;margin-bottom:.25rem;font-size:.75rem;font-weight:700;color:#475569}.claim-field{width:100%;border:1px solid #cbd5e1;border-radius:.5rem;background:#fff;padding:.625rem .75rem;font-size:.875rem}</style>
