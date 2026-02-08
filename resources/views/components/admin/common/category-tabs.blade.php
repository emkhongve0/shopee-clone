@props([
    'categories' => [],
    'active' => 'all',
])

<div class="mb-6 overflow-x-auto custom-scrollbar" x-data="{ activeCategory: '{{ $active }}' }">
    <div class="flex items-center gap-2 min-w-max pb-2">
        @foreach ($categories as $category)
            <button type="button"
                @click="activeCategory = '{{ $category['id'] }}'; $dispatch('category-changed', '{{ $category['id'] }}')"
                class="px-4 py-2.5 rounded-lg font-bold text-sm transition-all flex items-center gap-2 border group active:scale-95"
                :class="activeCategory === '{{ $category['id'] }}'
                    ?
                    'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-500/30' :
                    'bg-slate-800 text-slate-400 border-slate-700 hover:bg-slate-700 hover:text-white hover:border-slate-600'">
                {{-- Tên danh mục --}}
                <span>{{ $category['name'] }}</span>

                {{-- Huy hiệu số lượng (Badge) --}}
                <span
                    class="inline-flex items-center justify-center px-2 py-0.5 rounded-md text-[10px] font-black transition-colors"
                    :class="activeCategory === '{{ $category['id'] }}'
                        ?
                        'bg-white/20 text-white' :
                        'bg-slate-700 text-slate-400 group-hover:bg-slate-600 group-hover:text-white'">
                    {{ number_format($category['count']) }}
                </span>
            </button>
        @endforeach
    </div>
</div>
