@props(['status'])
@php
$map = [
    'DRAFT'                 => ['bg'=>'#f1f5f9','color'=>'#64748b','dot'=>'#94a3b8'],
    'READY'                 => ['bg'=>'#efefef','color'=>'#222222','dot'=>'#555555'],
    'DISPATCHED'            => ['bg'=>'#ececec','color'=>'#444444','dot'=>'#777777'],
    'COMPLETED'             => ['bg'=>'#f0f0f0','color'=>'#333333','dot'=>'#777777'],
    'SETTLEMENT PENDING'    => ['bg'=>'#ececec','color'=>'#444444','dot'=>'#777777'],
    'SETTLED'               => ['bg'=>'#f0f0f0','color'=>'#222222','dot'=>'#777777'],
    'CLOSED'                => ['bg'=>'#f1f5f9','color'=>'#475569','dot'=>'#94a3b8'],
    'DELIVERED'             => ['bg'=>'#f0f0f0','color'=>'#222222','dot'=>'#777777'],
    'PARTIAL'               => ['bg'=>'#ececec','color'=>'#444444','dot'=>'#777777'],
    'NOT DELIVERED'         => ['bg'=>'#e8e8e8','color'=>'#222222','dot'=>'#444444'],
    'RESERVICE'             => ['bg'=>'#f0f0f0','color'=>'#333333','dot'=>'#777777'],
    'DELAYED'               => ['bg'=>'#ececec','color'=>'#444444','dot'=>'#777777'],
    'OTHER'                 => ['bg'=>'#f1f5f9','color'=>'#475569','dot'=>'#94a3b8'],
    'PENDING'               => ['bg'=>'#ececec','color'=>'#444444','dot'=>'#777777'],
    'RESTOCKED'             => ['bg'=>'#f0f0f0','color'=>'#222222','dot'=>'#777777'],
    'IN STOCK'              => ['bg'=>'#f0f0f0','color'=>'#222222','dot'=>'#777777'],
    'LOW STOCK'             => ['bg'=>'#ececec','color'=>'#444444','dot'=>'#777777'],
    'OUT OF STOCK'          => ['bg'=>'#e8e8e8','color'=>'#222222','dot'=>'#444444'],
    'MARKET SHORT'          => ['bg'=>'#efefef','color'=>'#111111','dot'=>'#555555'],
    'DELIVERYMAN SHORT'     => ['bg'=>'#e8e8e8','color'=>'#222222','dot'=>'#444444'],
    'APPROVED WRITE-OFF'    => ['bg'=>'#f1f5f9','color'=>'#475569','dot'=>'#94a3b8'],
    'PENDING INVESTIGATION' => ['bg'=>'#ececec','color'=>'#444444','dot'=>'#777777'],
];
$key = strtoupper($status ?? '');
$s = $map[$key] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','dot'=>'#94a3b8'];
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
      style="background: {{ $s['bg'] }}; color: {{ $s['color'] }};">
    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background: {{ $s['dot'] }};"></span>
    {{ $status ?? 'Unknown' }}
</span>
