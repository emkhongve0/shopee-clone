@php
    $navigationItems = [
        ['id' => 'general', 'label' => 'Cài đặt chung', 'icon' => 'fa-cog'],
        ['id' => 'store', 'label' => 'Cửa hàng / Kinh doanh', 'icon' => 'fa-store'],
        ['id' => 'users', 'label' => 'Quản lý phân quyền', 'icon' => 'fa-user-shield'],
        ['id' => 'security', 'label' => 'Bảo mật & Xác thực', 'icon' => 'fa-shield-alt'],
        ['id' => 'notifications', 'label' => 'Thông báo', 'icon' => 'fa-bell'],
        ['id' => 'payments', 'label' => 'Thanh toán & Hóa đơn', 'icon' => 'fa-credit-card'],
        ['id' => 'shipping', 'label' => 'Vận chuyển & Logistics', 'icon' => 'fa-truck'],
        ['id' => 'integrations', 'label' => 'Tích hợp & API', 'icon' => 'fa-plug'],
        ['id' => 'appearance', 'label' => 'Giao diện & Thương hiệu', 'icon' => 'fa-palette'],
        ['id' => 'localization', 'label' => 'Bản địa hóa', 'icon' => 'fa-globe'],
        ['id' => 'backup', 'label' => 'Sao lưu & Hệ thống', 'icon' => 'fa-database'],
        ['id' => 'advanced', 'label' => 'Cài đặt nâng cao', 'icon' => 'fa-tools'],
    ];
@endphp

<div class="w-full lg:w-72 flex-shrink-0">
    <div class="bg-slate-800/40 backdrop-blur-md rounded-2xl border border-slate-700/50 p-2 sticky top-6 shadow-2xl">
        <nav class="space-y-1">
            @foreach ($navigationItems as $item)
                <button @click="activeCategory = '{{ $item['id'] }}'"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest transition-all duration-300 group relative overflow-hidden"
                    :class="activeCategory === '{{ $item['id'] }}'
                        ?
                        'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-500/20' :
                        'text-slate-400 hover:bg-slate-700/30 hover:text-white'">
                    {{-- Icon container --}}
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors border"
                        :class="activeCategory === '{{ $item['id'] }}'
                            ?
                            'bg-white/20 border-white/30 text-white' :
                            'bg-slate-900 border-slate-700 text-slate-500 group-hover:text-blue-400 group-hover:border-blue-500/30'">
                        <i class="fas {{ $item['icon'] }} text-xs"></i>
                    </div>

                    {{-- Label --}}
                    <span class="flex-1 text-left text-[10px]" x-text="'{{ $item['label'] }}'"></span>

                    {{-- Active Indicator --}}
                    <template x-if="activeCategory === '{{ $item['id'] }}'">
                        <div class="absolute right-0 top-0 h-full w-1 bg-white/30"></div>
                    </template>

                    <i class="fas fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1"
                        x-show="activeCategory !== '{{ $item['id'] }}'"></i>
                </button>
            @endforeach
        </nav>
    </div>
</div>
