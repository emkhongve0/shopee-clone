@props(['title', 'value', 'change', 'icon', 'iconBgColor', 'iconColor'])

@php
    $isPositive = (float) $change >= 0;
@endphp

<div
    {{ $attributes->merge(['class' => 'bg-[#1e293b] border border-slate-800 rounded-xl hover:border-slate-700 transition-colors shadow-sm']) }}>
    <div class="p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                {{-- Tiêu đề --}}
                <p class="text-slate-400 text-sm font-medium mb-2">{{ $title }}</p>

                {{-- Giá trị chính --}}
                <h3 class="text-white text-3xl font-bold mb-3">{{ $value }}</h3>

                {{-- Chỉ số tăng/giảm --}}
                <div class="flex items-center gap-1">
                    @if ($isPositive)
                        <i class="fas fa-trending-up text-sm text-green-500"></i>
                    @else
                        <i class="fas fa-trending-down text-sm text-red-500"></i>
                    @endif

                    <span class="text-sm font-medium {{ $isPositive ? 'text-green-500' : 'text-red-500' }}">
                        {{ $isPositive ? '+' : '' }}{{ $change }}%
                    </span>
                    <span class="text-slate-500 text-sm ml-1">so với tháng trước</span>
                </div>
            </div>

            {{-- Icon hiển thị bên phải --}}
            <div
                class="w-12 h-12 {{ $iconBgColor }} rounded-xl flex items-center justify-center shrink-0 shadow-inner">
                <i class="fas {{ $icon }} {{ $iconColor }} text-xl"></i>
            </div>
        </div>
    </div>
</div>
