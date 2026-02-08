<div class="bg-[#1e293b] border border-slate-800 rounded-lg p-4 mb-6" x-data="{
    filters: {
        search: '',
        status: 'all',
        role: 'all',
        dateRange: 'all'
    },
    hasActiveFilters() {
        return this.filters.search !== '' ||
            this.filters.status !== 'all' ||
            this.filters.role !== 'all' ||
            this.filters.dateRange !== 'all';
    },
    clearFilters() {
        this.filters.search = '';
        this.filters.status = 'all';
        this.filters.role = 'all';
        this.filters.dateRange = 'all';
        // Gọi hàm submit form hoặc làm mới dữ liệu tại đây nếu cần
    }
}">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Ô tìm kiếm --}}
        <div class="lg:col-span-2 relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-500"></i>
            <input type="text" x-model="filters.search" placeholder="Tìm theo tên, email hoặc số điện thoại..."
                class="w-full pl-10 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-300 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-sm" />
        </div>

        {{-- Lọc trạng thái --}}
        <div class="relative">
            <select x-model="filters.status"
                class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer shadow-sm">
                <option value="all">Tất cả trạng thái</option>
                <option value="active">Đang hoạt động</option>
                <option value="inactive">Ngừng hoạt động</option>
                <option value="banned">Đã khóa</option>
            </select>
            <i
                class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-[10px]"></i>
        </div>

        {{-- Lọc vai trò --}}
        <div class="relative">
            <select x-model="filters.role"
                class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer shadow-sm">
                <option value="all">Tất cả vai trò</option>
                <option value="admin">Quản trị viên</option>
                <option value="staff">Nhân viên</option>
                <option value="user">Khách hàng</option>
            </select>
            <i
                class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-[10px]"></i>
        </div>

        {{-- Lọc thời gian --}}
        <div class="relative">
            <select x-model="filters.dateRange"
                class="w-full bg-slate-800 border border-slate-700 text-slate-300 rounded-lg py-2 px-3 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer shadow-sm">
                <option value="all">Tất cả thời gian</option>
                <option value="today">Hôm nay</option>
                <option value="week">Tuần này</option>
                <option value="month">Tháng này</option>
                <option value="year">Năm nay</option>
            </select>
            <i
                class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-[10px]"></i>
        </div>
    </div>

    {{-- Nút xóa bộ lọc (Chỉ hiện khi có bộ lọc đang hoạt động) --}}
    <div x-show="hasActiveFilters()" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        class="mt-4 flex items-center justify-between border-t border-slate-800 pt-4">
        <p class="text-sm text-slate-500 italic">
            <i class="fas fa-filter mr-1"></i> Đã áp dụng bộ lọc cho danh sách
        </p>
        <button type="button" @click="clearFilters()"
            class="flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white hover:bg-slate-700 rounded-md transition-all group">
            <i
                class="fas fa-times w-4 h-4 flex items-center justify-center group-hover:rotate-90 transition-transform"></i>
            Xóa bộ lọc
        </button>
    </div>
</div>
