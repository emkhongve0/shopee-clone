@props([
    'categories' => [], // Nhận danh mục nếu cần hiển thị thêm
])

{{--
    LƯU Ý: Đã xóa x-data.
    Component này sẽ dùng chung dữ liệu filters và viewMode từ x-data của file index.blade.php
--}}
<div class="bg-[#1e293b] border border-slate-800 rounded-lg p-4 mb-6">

    {{-- Grid lọc --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-4">
        {{-- Tìm kiếm --}}
        <div class="lg:col-span-2 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
            <input type="text" x-model="filters.search" placeholder="Tìm tên, SKU hoặc mã ID..."
                class="w-full pl-10 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-300 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" />
        </div>

        {{-- Lọc Khoảng giá --}}
        <div class="relative">
            <select x-model="filters.priceRange"
                class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:border-blue-500 cursor-pointer text-sm">
                <option value="all">Tất cả mức giá</option>
                <option value="0-500000">Dưới 500k</option>
                <option value="500000-2000000">500k - 2Tr</option>
                <option value="2000000+">Trên 2Tr</option>
            </select>
            <i
                class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
        </div>

        {{-- Tình trạng kho --}}
        <div class="relative">
            <select x-model="filters.stockStatus"
                class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:border-blue-500 cursor-pointer text-sm">
                <option value="all">Tình trạng kho </option>
                <option value="in-stock">Còn hàng </option>
                <option value="low-stock">Sắp hết </option>
                <option value="out-of-stock">Hết hàng</option>
            </select>
            <i
                class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
        </div>

        {{-- Trạng thái hiển thị --}}
        <div class="relative">
            <select x-model="filters.productStatus"
                class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:border-blue-500 cursor-pointer text-sm">
                <option value="all">Trạng thái</option>
                <option value="active">Đang bán</option>
                <option value="hidden">Đã ẩn</option>
            </select>
            <i
                class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
        </div>

        {{-- Đánh giá --}}
        <div class="relative">
            <select x-model="filters.rating"
                class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:border-blue-500 cursor-pointer text-sm">
                <option value="all">Đánh giá</option>
                <option value="4">4 sao trở lên</option>
                <option value="3">3 sao trở lên</option>
                <option value="2">2 sao trở lên</option>
                <option value="1">1 sao trở lên</option>
            </select>
            <i
                class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
        </div>
    </div>

    {{-- Dòng điều khiển nút bấm --}}
    <div class="flex items-center justify-between">
        <div>
            {{-- Nút xóa bộ lọc: Kiểm tra trực tiếp biến filters từ cha --}}
            <button
                x-show="filters.search !== '' || filters.priceRange !== 'all' || filters.stockStatus !== 'all' || filters.productStatus !== 'all' || filters.rating !== 'all'"
                @click="filters = { search: '', priceRange: 'all', stockStatus: 'all', productStatus: 'all', rating: 'all' }"
                class="flex items-center gap-2 text-sm text-slate-400 hover:text-white hover:bg-slate-700 px-3 py-1.5 rounded-lg transition-all"
                x-cloak>
                <i class="fas fa-times-circle"></i>
                Xóa bộ lọc
            </button>
        </div>

        {{-- Chuyển đổi chế độ xem (View Toggle) --}}
        <div class="flex items-center gap-2 bg-slate-800/50 rounded-xl p-1 border border-slate-700">
            <button @click="viewMode = 'table'"
                class="px-4 py-1.5 rounded-lg flex items-center gap-2 text-sm font-bold transition-all"
                :class="viewMode === 'table' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'">
                <i class="fas fa-list-ul"></i>
                Bảng
            </button>
            <button @click="viewMode = 'grid'"
                class="px-4 py-1.5 rounded-lg flex items-center gap-2 text-sm font-bold transition-all"
                :class="viewMode === 'grid' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'">
                <i class="fas fa-th-large"></i>
                Lưới
            </button>
        </div>
    </div>
</div>
