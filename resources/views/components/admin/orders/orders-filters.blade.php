@props(['showFilters' => false])

{{--
    FORM LỌC SERVER-SIDE
    - Method: GET (để tham số hiện lên URL, dễ share link)
    - Action: Route index của Admin Order
--}}
<form action="{{ route('admin.orders.index') }}" method="GET" class="mb-6">

    {{-- 1. THANH TÌM KIẾM CHÍNH --}}
    <div class="flex items-center gap-4 mb-4">
        <div class="flex-1 relative group">
            <div
                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-blue-500 transition-colors">
                <i class="fas fa-search text-sm"></i>
            </div>

            {{-- Input Search: name="search" để Controller bắt được --}}
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm theo Mã đơn (ORD-...), Tên khách, Email hoặc SĐT..."
                class="w-full pl-11 pr-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white placeholder:text-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner" />

            {{-- Button submit ẩn (để khi nhấn Enter trong ô input sẽ tự submit form) --}}
            <button type="submit" class="hidden"></button>

            {{-- Nếu đang tìm kiếm thì hiện nút X nhỏ để xóa nhanh --}}
            @if (request('search'))
                <a href="{{ route('admin.orders.index', request()->except('search')) }}"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                    <i class="fas fa-times-circle"></i>
                </a>
            @endif
        </div>
    </div>

    {{-- 2. BỘ LỌC NÂNG CAO (Toggle bằng Alpine.js từ trang cha) --}}
    <div x-show="showFilters" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="bg-[#1e293b] border border-slate-800 rounded-2xl p-5 shadow-xl" x-cloak>

        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-bold flex items-center gap-2">
                <i class="fas fa-sliders-h text-blue-500 text-xs"></i>
                Bộ lọc nâng cao
            </h3>

            {{-- Nút Xóa bộ lọc: Reset về trang gốc --}}
            <a href="{{ route('admin.orders.index') }}"
                class="flex items-center gap-2 px-3 py-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all text-xs font-bold uppercase tracking-wider">
                <i class="fas fa-sync-alt text-[10px]"></i>
                Xóa bộ lọc
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- A. Lọc Trạng Thái Đơn Hàng --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Trạng thái đơn
                    hàng</label>
                <div class="relative">
                    <select name="status" @change="$el.form.submit()"
                        class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl py-2.5 px-4 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer text-sm hover:bg-slate-800">
                        <option value="all">Tất cả trạng thái</option>
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}"
                                {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none text-[10px]"></i>
                </div>
            </div>

            {{-- C. Tình trạng giao hàng (ĐÃ MỞ KHÓA) --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tình trạng giao
                    hàng</label>
                <div class="relative">
                    <select name="shipping_status" @change="$el.form.submit()"
                        class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl py-2.5 px-4 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer text-sm hover:bg-slate-800">
                        <option value="all">Tất cả</option>
                        <option value="ready_to_ship"
                            {{ request('shipping_status') == 'ready_to_ship' ? 'selected' : '' }}>Chờ lấy hàng</option>
                        <option value="shipping" {{ request('shipping_status') == 'shipping' ? 'selected' : '' }}>Đang
                            giao</option>
                        <option value="delivered" {{ request('shipping_status') == 'delivered' ? 'selected' : '' }}>Đã
                            giao</option>
                        <option value="returned" {{ request('shipping_status') == 'returned' ? 'selected' : '' }}>Đã
                            hoàn trả</option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none text-[10px]"></i>
                </div>
            </div>

            {{-- D. Phương thức thanh toán (ĐÃ MỞ KHÓA) --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Phương thức thanh
                    toán</label>
                <div class="relative">
                    <select name="payment_method" @change="$el.form.submit()"
                        class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl py-2.5 px-4 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer text-sm hover:bg-slate-800">
                        <option value="all">Tất cả</option>
                        {{-- Các giá trị value này phải khớp với DB --}}
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Thanh toán
                            khi nhận hàng (COD)</option>
                        <option value="vnpay" {{ request('payment_method') == 'vnpay' ? 'selected' : '' }}>VNPAY
                        </option>
                        <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>Ví MoMo
                        </option>
                        <option value="banking" {{ request('payment_method') == 'banking' ? 'selected' : '' }}>Chuyển
                            khoản ngân hàng</option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none text-[10px]"></i>
                </div>
            </div>
        </div>
    </div>
</form>
