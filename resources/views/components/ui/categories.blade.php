@php
    $categories = [
        ['icon' => 'fa-mobile-alt', 'name' => 'Phones', 'color' => 'bg-blue-100 text-blue-600'],
        ['icon' => 'fa-headphones', 'name' => 'Audio', 'color' => 'bg-purple-100 text-purple-600'],
        ['icon' => 'fa-clock', 'name' => 'Watches', 'color' => 'bg-emerald-100 text-emerald-600'],
        ['icon' => 'fa-laptop', 'name' => 'Computers', 'color' => 'bg-orange-100 text-orange-600'],
        // ... các danh mục khác
    ];
@endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Shop by Category</h2>
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-2">
        @foreach ($categories as $cat)
            <x-ui.category-item :icon="$cat['icon']" :name="$cat['name']" :color="$cat['color']" />
        @endforeach
    </div>
</div>
