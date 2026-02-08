@props([
    'icon' => 'fa-box',
    'name' => 'Category',
    'color' => 'bg-orange-100 text-orange-500',
    'href' => '#',
])

<a href="{{ $href }}"
    class="flex-shrink-0 flex flex-col items-center gap-3 p-4 rounded-lg hover:bg-gray-50 transition-colors group min-w-[100px]">

    <div
        class="w-14 h-14 {{ $color }} rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
        <i class="fas {{ $icon }} text-xl"></i>
    </div>

    <span
        class="text-sm font-medium text-center text-gray-700 group-hover:text-[#ee4d2d] transition-colors line-clamp-1">
        {{ $name }}
    </span>
</a>
