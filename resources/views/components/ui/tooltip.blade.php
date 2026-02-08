@props([
    'text' => '',
    'position' => 'top', // top, bottom, left, right
])

@php
    // Định nghĩa vị trí hiển thị của Tooltip dựa trên props
    $positionClasses = [
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
    ];

    // Định nghĩa hướng của mũi tên nhỏ (arrow)
    $arrowClasses = [
        'top' => 'top-full left-1/2 -translate-x-1/2 border-t-slate-900',
        'bottom' => 'bottom-full left-1/2 -translate-x-1/2 border-b-slate-900',
        'left' => 'left-full top-1/2 -translate-y-1/2 border-l-slate-900',
        'right' => 'right-full top-1/2 -translate-y-1/2 border-r-slate-900',
    ];
@endphp

<div x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show = false" class="relative inline-block">
    {{-- Phần tử kích hoạt Tooltip --}}
    {{ $slot }}

    {{-- Nội dung Tooltip --}}
    <div x-show="show" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-[100] px-3 py-1.5 text-[11px] font-bold text-white bg-slate-900 rounded-md shadow-xl whitespace-nowrap pointer-events-none border border-slate-700/50 {{ $positionClasses[$position] ?? $positionClasses['top'] }}">
        {{ $text }}

        {{-- Mũi tên (Arrow) --}}
        <div
            class="absolute w-0 h-0 border-4 border-transparent {{ $arrowClasses[$position] ?? $arrowClasses['top'] }}">
        </div>
    </div>
</div>
