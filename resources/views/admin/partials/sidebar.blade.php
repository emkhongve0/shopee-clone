@php
    $navItems = [
        [
            'icon' => 'fa-tachometer-alt',
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
        ],
        [
            'icon' => 'fa-shopping-cart',
            'label' => 'Quản lý đơn hàng',
            'route' => 'admin.orders.index',
            'active' => 'admin.orders*',
        ],
        // --- SỬA CÁC DÒNG DƯỚI ĐÂY ---
        [
            'icon' => 'fa-box',
            'label' => 'Quản lý sản phẩm',
            'route' => 'admin.products.index', // Trỏ tạm về Dashboard để không lỗi
            'active' => 'admin.products*',
        ],
        [
            'icon' => 'fa-users',
            'label' => 'Khách hàng',
            'route' => 'admin.users.index', // Trỏ về route mới
            'active' => 'admin.users*',
        ],
        [
            'icon' => 'fa-cog',
            'label' => 'Cài đặt',
            'route' => 'admin.dashboard', // Trỏ tạm về Dashboard
            'active' => 'admin.settings*',
        ],
    ];
@endphp

<aside :class="sidebarCollapsed ? 'w-20' : 'w-64'"
    class="relative h-screen bg-slate-950 border-r border-slate-800 transition-all duration-300 flex flex-col z-50 shadow-2xl">

    {{-- Logo Header --}}
    <div class="h-16 flex items-center px-6 border-b border-slate-800 shrink-0 transition-all"
        :class="sidebarCollapsed ? 'justify-center px-0' : ''">
        <div class="flex items-center gap-3">
            {{-- Nút điều hướng về trang chủ Shop --}}
            <a href="{{ url('/') }}" title="Về trang chủ shop"
                class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/20 group cursor-pointer hover:bg-blue-700 transition-all active:scale-95">
                <i class="fas fa-home text-white text-sm group-hover:scale-110 transition-transform"></i>
            </a>
            <span x-show="!sidebarCollapsed" x-cloak x-transition.opacity.duration.300ms
                class="text-white font-extrabold text-lg truncate uppercase tracking-tighter">Shop<span
                    class="text-blue-500">Mart</span></span>
        </div>
    </div>

    {{-- Menu Items --}}
    <nav class="flex-1 overflow-y-auto py-6 px-3 custom-scrollbar">
        <ul class="space-y-2">
            @foreach ($navItems as $item)
                @php
                    // Check active thông minh hơn (bao gồm cả trang con)
                    $isActive = request()->routeIs($item['active']);
                @endphp

                <li>
                    {{-- SỬA LỖI: Bỏ x-show ở Tooltip wrapper để tránh mất menu --}}
                    {{-- Giả sử component tooltip của bạn chỉ hiện title khi hover --}}
                    <div class="relative group/tooltip">

                        <a href="{{ route($item['route']) }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group relative
                           {{ $isActive
                               ? 'bg-blue-600/10 text-blue-500 font-semibold'
                               : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}"
                            :class="sidebarCollapsed ? 'justify-center' : ''">

                            <i
                                class="fas {{ $item['icon'] }} w-5 h-5 flex items-center justify-center text-base transition-colors
                                {{ $isActive ? 'text-blue-500' : 'group-hover:text-white' }}"></i>

                            <span x-show="!sidebarCollapsed" x-cloak
                                class="text-sm truncate uppercase tracking-wide">{{ $item['label'] }}</span>

                            {{-- Active Indicator --}}
                            @if ($isActive)
                                <span
                                    class="absolute left-0 w-1 h-5 bg-blue-600 rounded-r-full shadow-[0_0_10px_rgba(37,99,235,0.8)]"></span>
                            @endif
                        </a>

                        {{-- Tooltip thủ công (hiện khi sidebar đóng) --}}
                        <div x-show="sidebarCollapsed" x-cloak
                            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded shadow-lg opacity-0 group-hover/tooltip:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                            {{ $item['label'] }}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- Footer --}}
    <div class="p-4 border-t border-slate-800 bg-slate-950/50">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all group"
                :class="sidebarCollapsed ? 'justify-center' : ''">

                <i class="fas fa-sign-out-alt w-5 h-5 flex items-center justify-center text-sm"></i>

                <span x-show="!sidebarCollapsed" x-cloak class="text-sm font-medium uppercase tracking-wide">Đăng
                    xuất</span>
            </button>
        </form>
    </div>

    {{-- Toggle Button --}}
    <button @click="sidebarCollapsed = !sidebarCollapsed"
        class="absolute -right-3 top-20 w-6 h-6 bg-slate-800 border border-slate-700 rounded-full flex items-center justify-center shadow-md hover:bg-blue-600 hover:border-blue-500 transition-all group z-50">
        <i class="fas text-[10px] text-slate-400 group-hover:text-white transition-colors"
            :class="sidebarCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
    </button>
</aside>
