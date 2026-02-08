<header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{
    userOpen: false,
    cartCount: {{ $cartCount ?? 0 }},
    animateCart: false {{-- Thêm biến này để quản lý hiệu ứng rung --}}
}"
    @cart-updated.window="
        cartCount = $event.detail.newCount;
        animateCart = true; {{-- Kích hoạt rung --}}
        setTimeout(() => animateCart = false, 500); {{-- Tắt rung sau 0.5s --}}
    ">

    <div class="max-w-[1440px] mx-auto">
        {{-- TOP BAR --}}
        <div class="flex items-center justify-between px-6 py-2 text-xs border-b border-gray-100">
            <div class="flex items-center gap-6">
                <a href="#" class="text-gray-600 hover:text-[#ee4d2d] transition-colors">Kênh người bán</a>
                <a href="#"
                    class="text-gray-600 hover:text-[#ee4d2d] transition-colors border-l border-gray-200 pl-6">Tải ứng
                    dụng</a>
                <div class="flex items-center gap-3 border-l border-gray-200 pl-6 text-gray-600">
                    <span>Kết nối</span>
                    <a href="#" class="hover:text-[#ee4d2d]"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-[#ee4d2d]"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <a href="#" class="text-gray-600 hover:text-[#ee4d2d] transition-colors flex items-center gap-1">
                    <i class="far fa-bell"></i> Thông báo
                </a>
                <a href="#" class="text-gray-600 hover:text-[#ee4d2d] transition-colors flex items-center gap-1">
                    <i class="far fa-question-circle"></i> Hỗ trợ
                </a>

                {{-- LOGIC AUTH Ở TOP BAR --}}
                @guest
                    <a href="{{ route('register') }}"
                        class="text-gray-600 hover:text-[#ee4d2d] transition-colors font-medium">Đăng ký</a>
                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-[#ee4d2d] transition-colors font-medium border-l border-gray-200 pl-4">Đăng
                        nhập</a>
                @else
                    <span class="text-gray-500">Xin chào, {{ Auth::user()->name }}</span>
                @endguest
            </div>
        </div>

        {{-- MAIN HEADER --}}
        <div class="flex items-center justify-between gap-8 px-6 py-4">
            {{-- LOGO --}}
            <a href="/" class="flex-shrink-0 group">
                <h1
                    class="text-2xl font-black text-[#ee4d2d] tracking-tighter group-hover:opacity-80 transition-opacity">
                    ShopMart
                </h1>
            </a>

            {{-- SEARCH BAR --}}
            <div class="flex-1 max-w-3xl">
                <form action="{{ route('product.search') }}" method="GET" class="relative flex"
                    x-data="{ search: '' }">
                    <input type="text" name="keyword" x-model="search"
                        placeholder="Tìm kiếm sản phẩm, thương hiệu và shop..."
                        class="w-full px-4 py-2.5 border-2 border-[#ee4d2d] rounded-lg focus:outline-none focus:border-[#d73211] transition-colors text-sm" />
                    <button type="submit"
                        class="absolute right-0 top-0 bottom-0 px-6 bg-[#ee4d2d] hover:bg-[#d73211] rounded-r-md transition-colors flex items-center justify-center text-white"
                        aria-label="Search">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                </form>
            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center gap-6 flex-shrink-0">
                {{-- GIỎ HÀNG --}}
                <button class="relative text-gray-700 hover:text-[#ee4d2d] transition-colors group py-2"
                    @click="$dispatch('open-cart')">

                    {{-- THÊM HIỆU ỨNG RUNG Ở ĐÂY --}}
                    <i class="fas fa-shopping-cart text-2xl transition-all duration-300"
                        :class="{ 'animate-cart-bounce text-[#ee4d2d]': animateCart }"></i>

                    {{-- Badge số lượng --}}
                    <template x-if="cartCount > 0">
                        <span x-text="cartCount"
                            class="absolute -top-2 -right-2 bg-[#ee4d2d] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold border-2 border-white shadow-sm">
                        </span>
                    </template>
                </button>

                {{-- TÀI KHOẢN --}}
                <div class="relative">
                    @guest
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-2 text-gray-700 hover:text-[#ee4d2d] transition-colors py-2">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span class="text-sm font-medium">Tài khoản</span>
                        </a>
                    @else
                        <button class="flex items-center gap-2 text-gray-700 hover:text-[#ee4d2d] transition-colors py-2"
                            @click="userOpen = !userOpen" @click.away="userOpen = false">

                            <div class="w-8 h-8 rounded-full border border-gray-200 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&size=64"
                                    class="w-full h-full object-cover">
                            </div>

                            <span class="text-sm font-bold max-w-[150px] truncate">{{ Auth::user()->name }}</span>

                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"
                                :class="userOpen ? 'rotate-180' : ''"></i>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="userOpen" x-cloak x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 top-full mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 py-2 z-[60]">

                            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                <p class="text-xs text-gray-500">Đăng nhập là</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-orange-50 hover:text-[#ee4d2d] transition-colors text-sm text-gray-700">
                                <i class="far fa-user w-4 text-center"></i> <span>Thông tin cá nhân</span>
                            </a>

                            {{-- Sửa lại điều kiện @if của bạn như sau --}}
                            @if (Auth::check() && Auth::user()->role->value === 'admin')
                                <a href="{{ url('/admin/dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 bg-indigo-50/50 hover:bg-indigo-50 hover:text-indigo-600 transition-colors text-sm text-gray-700 border-l-4 border-indigo-500">
                                    <i class="fas fa-user-shield w-4 text-center text-indigo-600"></i>
                                    <span class="font-bold">Quản trị hệ thống</span>
                                </a>
                            @endif
                            {{-- KẾT THÚC: NÚT QUẢN TRỊ CHO ADMIN --}}
                            <a href="{{ route('wishlist.index') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-orange-50 hover:text-[#ee4d2d] transition-all">
                                <i class="fas fa-heart w-4 text-red-500"></i>
                                Sản phẩm yêu thích
                            </a>
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-orange-50 hover:text-[#ee4d2d] transition-colors text-sm text-gray-700">
                                <i class="fas fa-box w-4 text-center"></i> <span>Đơn mua</span>
                            </a>

                            <div class="border-t border-gray-100 my-2"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 hover:text-red-600 transition-colors text-sm text-gray-600 font-medium">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> <span>Đăng xuất</span>
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</header>
