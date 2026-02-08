@props(['totalOrders' => 0])

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
        {{-- Tiêu đề và thông tin phụ --}}
        <div>
            <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Quản lý đơn hàng</h1>
            <p class="text-slate-400 text-sm">Xem, theo dõi và quản lý toàn bộ đơn hàng của khách hàng</p>
        </div>

        {{-- Nhóm các nút hành động --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Nút Bộ lọc --}}
            <button @click="showFilters = !showFilters"
                :class="showFilters ? 'ring-2 ring-blue-500 bg-slate-800 text-white' : 'bg-slate-900 text-slate-300'"
                class="flex items-center gap-2 px-4 py-2.5 border border-slate-700 rounded-xl hover:bg-slate-800 hover:text-white transition-all font-bold text-sm shadow-sm">
                <i class="fas fa-filter text-xs"></i>
                Bộ lọc
            </button>

            {{-- Nút Xuất dữ liệu --}}
            <button @click="exportOrders()"
                class="flex items-center gap-2 px-4 py-2.5 bg-slate-900 border border-slate-700 text-slate-300 rounded-xl hover:bg-slate-800 hover:text-white transition-all font-bold text-sm shadow-sm">
                <i class="fas fa-download text-xs"></i>
                Xuất file
            </button>

            {{-- Dropdown Thao tác hàng loạt --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center gap-2 px-4 py-2.5 bg-slate-900 border border-slate-700 text-slate-300 rounded-xl hover:bg-slate-800 hover:text-white transition-all font-bold text-sm shadow-sm">
                    <i class="fas fa-ellipsis-v text-xs"></i>
                    Thao tác loạt
                </button>

                {{-- Menu Dropdown --}}
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-56 bg-slate-900 border border-slate-700 rounded-xl shadow-2xl z-50 overflow-hidden">
                    <button
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-all text-left">
                        <i class="fas fa-edit w-4 text-blue-500"></i> Cập nhật trạng thái
                    </button>
                    <button
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-all text-left border-t border-slate-800/50">
                        <i class="fas fa-file-export w-4 text-green-500"></i> Xuất mục đã chọn
                    </button>
                    <button
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 transition-all text-left border-t border-slate-800/50">
                        <i class="fas fa-trash-alt w-4 text-red-500"></i> Hủy đơn hàng loạt
                    </button>
                </div>
            </div>

            {{-- Nút Tạo đơn hàng --}}
            <button @click="createNewOrder()"
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-900/20 active:scale-95">
                <i class="fas fa-plus text-xs"></i>
                Tạo đơn hàng
            </button>
        </div>
    </div>
</div>
