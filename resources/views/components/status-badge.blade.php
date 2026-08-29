@props(['status'])
@php
$classes = match(strtoupper($status ?? '')) {
    'DRAFT'                 => 'bg-gray-100 text-gray-600 border border-gray-200',
    'READY'                 => 'bg-blue-100 text-blue-700 border border-blue-200',
    'DISPATCHED'            => 'bg-orange-100 text-orange-700 border border-orange-200',
    'COMPLETED'             => 'bg-teal-100 text-teal-700 border border-teal-200',
    'SETTLEMENT PENDING'    => 'bg-amber-100 text-amber-700 border border-amber-200',
    'SETTLED'               => 'bg-green-100 text-green-700 border border-green-200',
    'CLOSED'                => 'bg-gray-200 text-gray-700 border border-gray-300',
    'DELIVERED'             => 'bg-green-100 text-green-700 border border-green-200',
    'PARTIAL'               => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
    'NOT DELIVERED'         => 'bg-red-100 text-red-700 border border-red-200',
    'RESERVICE'             => 'bg-purple-100 text-purple-700 border border-purple-200',
    'PENDING'               => 'bg-amber-100 text-amber-700 border border-amber-200',
    'RESTOCKED'             => 'bg-green-100 text-green-700 border border-green-200',
    'IN STOCK'              => 'bg-green-100 text-green-700 border border-green-200',
    'LOW STOCK'             => 'bg-amber-100 text-amber-700 border border-amber-200',
    'OUT OF STOCK'          => 'bg-red-100 text-red-700 border border-red-200',
    'MARKET SHORT'          => 'bg-blue-100 text-blue-700 border border-blue-200',
    'DELIVERYMAN SHORT'     => 'bg-red-100 text-red-700 border border-red-200',
    'APPROVED WRITE-OFF'    => 'bg-gray-100 text-gray-600 border border-gray-200',
    'PENDING INVESTIGATION' => 'bg-amber-100 text-amber-700 border border-amber-200',
    default                 => 'bg-gray-100 text-gray-600 border border-gray-200',
};
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $status ?? 'Unknown' }}
</span>
