@props(['title', 'count', 'icon', 'bgColor', 'iconColor', 'href' => '#', 'active' => false])

{{-- Chuyển từ div sang a để bấm được link --}}
<a href="{{ $href }}"
    class="relative p-4 rounded-2xl border transition-all duration-200 group hover:-translate-y-1 hover:shadow-lg cursor-pointer flex flex-col justify-between h-full {{ $bgColor }}
   {{-- Logic Active: Nếu active thì viền sáng theo màu icon, ngược lại viền tối --}}
   {{ $active
       ? 'border-' . str_replace('text-', '', $iconColor) . ' ring-1 ring-' . str_replace('text-', '', $iconColor)
       : 'border-slate-800 hover:border-slate-700' }}">

    <div class="flex items-start justify-between mb-3">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 truncate pr-2">
            {{ $title }}
        </span>
        <div
            class="w-8 h-8 rounded-lg flex items-center justify-center border shrink-0 border-white/5 bg-white/5 {{ $iconColor }}">
            <i class="fas {{ $icon }} text-sm"></i>
        </div>
    </div>

    <div class="flex items-end gap-2 mt-auto">
        <span class="text-2xl font-black text-white group-hover:scale-105 transition-transform">
            {{ number_format($count) }}
        </span>
        <span class="text-xs font-medium text-slate-500 mb-1.5">đơn</span>
    </div>

    @if ($active)
        <div class="absolute inset-x-4 bottom-0 h-0.5 rounded-t-full bg-current opacity-50 {{ $iconColor }}"></div>
    @endif
</a>
