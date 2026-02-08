@php
    $categories = [
        [
            'id' => 1,
            'slug' => 'dien-tu',
            'icon' => 'fa-mobile-alt',
            'name' => 'Điện tử',
            'bg_color' => 'bg-blue-100 text-blue-600',
        ],
        [
            'id' => 2,
            'slug' => 'thoi-trang',
            'icon' => 'fa-tshirt',
            'name' => 'Thời trang',
            'bg_color' => 'bg-pink-100 text-pink-600',
        ],
        [
            'id' => 3,
            'slug' => 'nha-cua',
            'icon' => 'fa-home',
            'name' => 'Nhà cửa',
            'bg_color' => 'bg-green-100 text-green-600',
        ],
        [
            'id' => 4,
            'slug' => 'sac-dep',
            'icon' => 'fa-sparkles',
            'name' => 'Sắc đẹp',
            'bg_color' => 'bg-purple-100 text-purple-600',
        ],
        [
            'id' => 5,
            'slug' => 'the-thao',
            'icon' => 'fa-dumbbell',
            'name' => 'Thể thao',
            'bg_color' => 'bg-red-100 text-red-600',
        ],
        [
            'id' => 6,
            'slug' => 'sach-va-qua',
            'icon' => 'fa-book',
            'name' => 'Sách & Quà',
            'bg_color' => 'bg-yellow-100 text-yellow-600',
        ],
        [
            'id' => 7,
            'slug' => 'tro-choi',
            'icon' => 'fa-gamepad',
            'name' => 'Trò chơi',
            'bg_color' => 'bg-indigo-100 text-indigo-600',
        ],
        [
            'id' => 8,
            'slug' => 'may-anh',
            'icon' => 'fa-camera',
            'name' => 'Máy ảnh',
            'bg_color' => 'bg-cyan-100 text-cyan-600',
        ],
        [
            'id' => 9,
            'slug' => 'dong-ho',
            'icon' => 'fa-clock',
            'name' => 'Đồng hồ',
            'bg_color' => 'bg-orange-100 text-orange-600',
        ],
        [
            'id' => 10,
            'slug' => 'am-thanh',
            'icon' => 'fa-headphones',
            'name' => 'Âm thanh',
            'bg_color' => 'bg-teal-100 text-teal-600',
        ],
        [
            'id' => 11,
            'slug' => 'may-tinh',
            'icon' => 'fa-laptop',
            'name' => 'Máy tính',
            'bg_color' => 'bg-gray-100 text-gray-600',
        ],
        [
            'id' => 12,
            'slug' => 'phu-kien',
            'icon' => 'fa-shopping-bag',
            'name' => 'Phụ kiện',
            'bg_color' => 'bg-rose-100 text-rose-600',
        ],
    ];
@endphp

<section class="bg-white rounded-lg p-3 sm:p-6 shadow-sm border border-gray-100">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base sm:text-xl font-bold text-gray-900 uppercase tracking-tight">Danh mục</h2>
        <a href="/" class="text-[#ee4d2d] hover:underline text-xs sm:text-sm font-medium transition-colors">
            Xem tất cả
        </a>
    </div>

    {{-- Vùng cuộn ngang --}}
    <div class="overflow-x-auto no-scrollbar">
        <div class="flex gap-2 sm:gap-4 pb-2">
            @foreach ($categories as $category)
                @php
                    // Ép kiểu dữ liệu để sử dụng dấu ->
                    $item = (object) $category;
                @endphp

                {{-- Thẻ link truyền thống: Chỉ chuyển hướng trang, không chạy Javascript/AJAX --}}
                <a href="{{ route('category.show', $item->slug) }}"
                    class="flex-shrink-0 flex flex-col items-center gap-2 p-2 sm:p-4 rounded-lg hover:bg-gray-50 transition-all group min-w-[85px] sm:min-w-[110px]">

                    {{-- Container Icon --}}
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 {{ $item->bg_color }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                        <i class="fas {{ $item->icon }} text-lg sm:text-xl"></i>
                    </div>

                    {{-- Tên danh mục --}}
                    <span
                        class="text-[11px] sm:text-sm font-semibold text-center text-gray-700 group-hover:text-[#ee4d2d] line-clamp-2 h-8 sm:h-10 leading-tight">
                        {{ $item->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
