@props(['totalProducts' => 0])

<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
        {{-- Tiêu đề --}}
        <div>
            <h1 class="text-white text-3xl font-bold mb-2 tracking-tight">Quản lý sản phẩm</h1>
            <p class="text-slate-400 text-sm">
                Quản lý <span class="text-white font-bold">{{ number_format($totalProducts) }}</span> sản phẩm trong tất
                cả danh mục
            </p>
        </div>

        {{-- Các nút thao tác --}}
        <div class="flex flex-wrap items-center gap-3">

            {{-- Dropdown: Thao tác hàng loạt --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center gap-2 px-4 py-2 bg-slate-800 text-slate-300 border border-slate-700 rounded-lg hover:bg-slate-700 hover:text-white transition-all font-medium text-sm shadow-sm active:scale-95">
                    <i class="fas fa-tasks text-xs"></i>
                    Thao tác hàng loạt
                    <i class="fas fa-chevron-down text-[10px] ml-1 transition-transform"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>

                {{-- Nội dung Dropdown --}}
                {{-- Dropdown Thao tác hàng loạt --}}
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-56 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-50 overflow-hidden">

                    {{-- 1. Cập nhật trạng thái --}}
                    <button @click="open = false; $dispatch('open-bulk-modal', 'update_status')"
                        class="flex items-center w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-left">
                        <i class="fas fa-sync-alt w-5 text-blue-500"></i> Cập nhật trạng thái
                    </button>

                    {{-- 2. Gán danh mục nhanh --}}
                    <button @click="open = false; $dispatch('open-bulk-modal', 'update_category')"
                        class="flex items-center w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-left border-t border-slate-700/50">
                        <i class="fas fa-tags w-5 text-purple-500"></i> Gán danh mục nhanh
                    </button>

                    {{-- 3. Điều chỉnh giá loạt --}}
                    <button @click="open = false; $dispatch('open-bulk-modal', 'update_price')"
                        class="flex items-center w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-left border-t border-slate-700/50">
                        <i class="fas fa-dollar-sign w-5 text-green-500"></i> Điều chỉnh giá loạt
                    </button>

                    {{-- 4. Xóa mục đã chọn --}}
                    <button @click="open = false; $dispatch('open-bulk-modal', 'delete')"
                        class="flex items-center w-full px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-all text-left border-t border-slate-700/50">
                        <i class="fas fa-trash-alt w-5 text-red-500"></i> Xóa mục đã chọn
                    </button>
                </div>
            </div>

            {{-- Các nút khác giữ nguyên --}}
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data"
                id="importForm">
                @csrf
                {{-- Input file ẩn --}}
                <input type="file" name="file" id="importInput" class="hidden"
                    @change="document.getElementById('importForm').submit()">

                <button type="button" onclick="document.getElementById('importInput').click()"
                    class="flex items-center gap-2 px-4 py-2 bg-slate-800 text-slate-300 border border-slate-700 rounded-lg hover:bg-slate-700 hover:text-white transition-all font-medium text-sm shadow-sm active:scale-95">
                    <i class="fas fa-upload text-xs"></i>
                    Nhập file
                </button>
            </form>

            <button type="button"
                @click="window.location.href = '{{ route('admin.products.export') }}?' + new URLSearchParams(filters).toString()"
                class="flex items-center gap-2 px-4 py-2 bg-slate-800 text-slate-300 border border-slate-700 rounded-lg hover:bg-slate-700 hover:text-white transition-all font-medium text-sm shadow-sm active:scale-95">
                <i class="fas fa-download text-xs"></i>
                Xuất file
            </button>

            <button type="button" @click="$dispatch('open-add-modal')"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all font-bold text-sm shadow-lg shadow-blue-500/20 active:scale-95">
                <i class="fas fa-plus text-xs"></i> Thêm sản phẩm mới
            </button>
        </div>
    </div>
</div>
