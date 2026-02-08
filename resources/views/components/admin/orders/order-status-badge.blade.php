@props(['status', 'type'])

@php
    // 1. Logic định dạng văn bản (VD: not_shipped -> Not Shipped)
    $formattedStatus = str_replace('_', ' ', $status);
    $formattedStatus = ucwords($formattedStatus);

    // 2. Logic lấy màu sắc dựa trên Type và Status (Sử dụng Match của PHP 8+)
    $statusClasses = match ($type) {
        'order' => match ($status) {
            'pending' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
            'processing', 'shipping' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
            'completed' => 'bg-green-500/10 text-green-500 border-green-500/20',
            'cancelled', 'refunded' => 'bg-red-500/10 text-red-500 border-red-500/20',
            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
        },
        'payment' => match ($status) {
            'pending' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
            'paid' => 'bg-green-500/10 text-green-500 border-green-500/20',
            'failed', 'refunded' => 'bg-red-500/10 text-red-500 border-red-500/20',
            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
        },
        'shipping' => match ($status) {
            'processing', 'shipped', 'in_transit' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
            'delivered' => 'bg-green-500/10 text-green-500 border-green-500/20',
            'not_shipped' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
        },
        default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
    };
@endphp

<span
    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusClasses }}">
    {{ $formattedStatus }}
</span>
