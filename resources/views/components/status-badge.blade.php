@props(['status'])
@php
$map = [
    'DRAFT'                 => ['bg'=>'#f1f5f9','color'=>'#64748b','dot'=>'#94a3b8'],
    'READY'                 => ['bg'=>'#eff6ff','color'=>'#2563eb','dot'=>'#3b82f6'],
    'DISPATCHED'            => ['bg'=>'#fff7ed','color'=>'#c2410c','dot'=>'#f97316'],
    'COMPLETED'             => ['bg'=>'#f0fdfa','color'=>'#0f766e','dot'=>'#14b8a6'],
    'SETTLEMENT PENDING'    => ['bg'=>'#fffbeb','color'=>'#b45309','dot'=>'#f59e0b'],
    'SETTLED'               => ['bg'=>'#f0fdf4','color'=>'#15803d','dot'=>'#22c55e'],
    'CLOSED'                => ['bg'=>'#f1f5f9','color'=>'#475569','dot'=>'#94a3b8'],
    'DELIVERED'             => ['bg'=>'#f0fdf4','color'=>'#15803d','dot'=>'#22c55e'],
    'PARTIAL'               => ['bg'=>'#fefce8','color'=>'#854d0e','dot'=>'#eab308'],
    'NOT DELIVERED'         => ['bg'=>'#fff1f2','color'=>'#b91c1c','dot'=>'#ef4444'],
    'RESERVICE'             => ['bg'=>'#faf5ff','color'=>'#7e22ce','dot'=>'#a855f7'],
    'DELAYED'               => ['bg'=>'#fff7ed','color'=>'#c2410c','dot'=>'#f97316'],
    'OTHER'                 => ['bg'=>'#f1f5f9','color'=>'#475569','dot'=>'#94a3b8'],
    'PENDING'               => ['bg'=>'#fffbeb','color'=>'#b45309','dot'=>'#f59e0b'],
    'RESTOCKED'             => ['bg'=>'#f0fdf4','color'=>'#15803d','dot'=>'#22c55e'],
    'IN STOCK'              => ['bg'=>'#f0fdf4','color'=>'#15803d','dot'=>'#22c55e'],
    'LOW STOCK'             => ['bg'=>'#fffbeb','color'=>'#b45309','dot'=>'#f59e0b'],
    'OUT OF STOCK'          => ['bg'=>'#fff1f2','color'=>'#b91c1c','dot'=>'#ef4444'],
    'MARKET SHORT'          => ['bg'=>'#eff6ff','color'=>'#1d4ed8','dot'=>'#3b82f6'],
    'DELIVERYMAN SHORT'     => ['bg'=>'#fff1f2','color'=>'#b91c1c','dot'=>'#ef4444'],
    'APPROVED WRITE-OFF'    => ['bg'=>'#f1f5f9','color'=>'#475569','dot'=>'#94a3b8'],
    'PENDING INVESTIGATION' => ['bg'=>'#fffbeb','color'=>'#b45309','dot'=>'#f59e0b'],
];
$key = strtoupper($status ?? '');
$s = $map[$key] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','dot'=>'#94a3b8'];
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
      style="background: {{ $s['bg'] }}; color: {{ $s['color'] }};">
    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background: {{ $s['dot'] }};"></span>
    {{ $status ?? 'Unknown' }}
</span>
