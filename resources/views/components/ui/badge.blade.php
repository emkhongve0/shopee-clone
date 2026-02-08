@props(['variant' => 'default'])

@php
    $variants = [
        'default' => 'bg-[#ee4d2d] text-white',
        'outline' => 'border border-gray-300 text-gray-600',
        'secondary' => 'bg-gray-100 text-gray-800',
        'destructive' => 'bg-red-600 text-white',
    ];
@endphp

<span
    {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none ' . $variants[$variant]]) }}>
    {{ $slot }}
</span>
