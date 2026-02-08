{{-- File: resources/views/components/admin/pagination.blade.php --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-6 border-t border-slate-800 pt-6"
    x-show="totalPages > 0" x-cloak>

    {{-- 1. Hiển thị thông tin số lượng --}}
    <div class="text-sm text-slate-400">
        Hiển thị
        <span class="font-bold text-white" x-text="(currentPage - 1) * itemsPerPage + 1"></span>
        đến
        <span class="font-bold text-white" x-text="Math.min(currentPage * itemsPerPage, totalItems)"></span>
        của
        <span class="font-bold text-white" x-text="totalItems"></span>
        kết quả
    </div>

    {{-- 2. Các nút điều hướng --}}
    <div class="flex items-center gap-2">

        {{-- Nút Previous --}}
        <button @click="currentPage > 1 ? currentPage-- : null" :disabled="currentPage === 1"
            class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-700 hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-slate-400 hover:text-white">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>

        {{-- Danh sách trang --}}
        <div class="flex items-center gap-1">
            <template x-for="page in totalPages" :key="page">
                <button
                    x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
                    @click="currentPage = page"
                    :class="currentPage === page ?
                        'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-500/30' :
                        'border-slate-700 text-slate-400 hover:bg-slate-800 hover:text-white'"
                    class="w-9 h-9 flex items-center justify-center rounded-lg border text-sm font-bold transition-all"
                    x-text="page">
                </button>
            </template>
        </div>

        {{-- Nút Next --}}
        <button @click="currentPage < totalPages ? currentPage++ : null" :disabled="currentPage === totalPages"
            class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-700 hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-slate-400 hover:text-white">
            <i class="fas fa-chevron-right text-xs"></i>
        </button>
    </div>
</div>
