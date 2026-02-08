@php
    // Danh sách 12 danh mục với màu sắc và icon tương ứng từ file React của bạn
    $categories = [
        ['icon' => 'fa-mobile-alt', 'name' => 'Electronics', 'color' => 'bg-blue-100 text-blue-600'],
        ['icon' => 'fa-tshirt', 'name' => 'Fashion', 'color' => 'bg-pink-100 text-pink-600'],
        ['icon' => 'fa-home', 'name' => 'Home & Living', 'color' => 'bg-green-100 text-green-600'],
        ['icon' => 'fa-sparkles', 'name' => 'Beauty', 'color' => 'bg-purple-100 text-purple-600'],
        ['icon' => 'fa-dumbbell', 'name' => 'Sports', 'color' => 'bg-red-100 text-red-600'],
        ['icon' => 'fa-book', 'name' => 'Books', 'color' => 'bg-yellow-100 text-yellow-600'],
        ['icon' => 'fa-gamepad', 'name' => 'Gaming', 'color' => 'bg-indigo-100 text-indigo-600'],
        ['icon' => 'fa-camera', 'name' => 'Cameras', 'color' => 'bg-cyan-100 text-cyan-600'],
        ['icon' => 'fa-clock', 'name' => 'Watches', 'color' => 'bg-orange-100 text-orange-600'],
        ['icon' => 'fa-headphones', 'name' => 'Audio', 'color' => 'bg-teal-100 text-teal-600'],
        ['icon' => 'fa-laptop', 'name' => 'Computers', 'color' => 'bg-gray-100 text-gray-600'],
        ['icon' => 'fa-shopping-bag', 'name' => 'Accessories', 'color' => 'bg-rose-100 text-rose-600'],
    ];
@endphp

<section class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
    {{-- Section Header: Đúng y hệt thiết kế React --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Categories</h2>
        <a href="#"
            class="text-[#ee4d2d] hover:text-[#d73211] text-sm font-medium transition-colors uppercase tracking-wide">
            View All
        </a>
    </div>

    {{-- Horizontal Scrollable List: Sử dụng x-ref để đồng bộ logic cuộn nếu cần --}}
    <div class="overflow-x-auto no-scrollbar -mx-2">
        <div class="flex gap-2 px-2 pb-2">
            @foreach ($categories as $category)
                <x-ui.category-item :icon="$category['icon']" :name="$category['name']" :color="$category['color']" />
            @endforeach
        </div>
    </div>
</section>

<style>
    /* Ẩn thanh cuộn vật lý để giao diện sạch như bản React */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
