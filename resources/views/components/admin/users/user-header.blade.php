@props([
    'stats' => [
        'total' => 0,
        'active' => 0,
        'new_today' => 0,
        'new_this_month' => 0,
    ],
])

<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
        {{-- Phần tiêu đề và số liệu --}}
        <div>
            <h1 class="text-white text-3xl font-bold mb-2 tracking-tight">Người dùng</h1>
            <div class="flex items-center gap-2">
                <p class="text-slate-400 text-sm">
                    {{ number_format($stats['total']) }} người dùng đang hoạt động
                </p>
                {{-- Badge tăng trưởng --}}
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                    <i class="fas fa-arrow-up mr-1 text-[10px]"></i>
                    +{{ $stats['new_this_month'] }} trong tháng này
                </span>
            </div>
        </div>

        {{-- Các nút thao tác --}}
        <div class="flex items-center gap-3">
            {{-- Nút Xuất dữ liệu --}}
            <button type="button" @click="exportData()"
                class="flex items-center gap-2 px-4 py-2 bg-slate-800 text-slate-300 border border-slate-700 rounded-lg hover:bg-slate-700 hover:text-white transition-all font-medium text-sm shadow-sm active:scale-95">
                <i class="fas fa-download text-xs"></i>
                Xuất dữ liệu
            </button>

            {{-- Nút Thêm người dùng mới --}}
            <button type="button" @click="openCreatePanel()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all font-semibold text-sm shadow-lg shadow-blue-500/20 active:scale-95">
                <i class="fas fa-plus text-xs"></i>
                Thêm người dùng mới
            </button>
        </div>
    </div>
</div>
