@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
    {{-- Container chính chứa toàn bộ logic Alpine.js --}}
    <div class="space-y-6 pb-20" x-data="{
        {{-- 1. DỮ LIỆU GỐC (Đảm bảo Controller truyền $allUsers và $allProducts) --}}
        allOrders: {{ $allOrders->toJson() }},
            allUsers: {{ $allUsers->toJson() }}, {{-- Danh sách khách hàng để tìm kiếm --}}
        allProductsList: {{ $allProducts->toJson() }}, {{-- Danh sách sản phẩm để thêm vào giỏ --}}

        paginatedOrders: [],
            selectedOrders: [],
            selectedOrder: null,

            {{-- 2. TRẠNG THÁI HIỂN THỊ --}}
        isPanelOpen: false,
            showFilters: false,
            currentPage: 1,
            itemsPerPage: 10,
            totalPages: 0,
            totalItems: 0,

            {{-- 3. THAO TÁC HÀNG LOẠT --}}
        showBulkModal: false,
            bulkAction: '',
            bulkStatus: 'pending',
            bulkShippingStatus: 'ready_to_ship',

            {{-- 4. LOGIC TẠO ĐƠN HÀNG MỚI (Đã cập nhật Search & Cart) --}}
        isCreatePanelOpen: false,
            customerSearch: '',
            productSearch: '',
            filteredCustomers: [],
            filteredProducts: [],
            newOrder: {
                customer_id: '',
                customer_name: '',
                address: '',
                payment_method: 'bank_transfer',
                notes: '',
                items: []
            },

            {{-- 5. BỘ LỌC DANH SÁCH --}}
        filters: {
                search: '{{ request('search') }}',
                orderStatus: '{{ request('status', 'all') }}',
                paymentStatus: '{{ request('payment_status', 'all') }}',
                shippingStatus: 'all',
                paymentMethod: 'all'
            },

            init() {
                this.totalItems = this.allOrders.length;
                this.applyPagination();
                this.$watch('currentPage', () => this.applyPagination());
                this.$watch('itemsPerPage', () => {
                    this.currentPage = 1;
                    this.applyPagination();
                });
            },

            {{-- --- LOGIC TÌM KIẾM (MỚI) --- --}}

        // Tìm khách hàng theo tên hoặc email
        searchCustomers() {
                if (this.customerSearch.length < 2) {
                    this.filteredCustomers = [];
                    return;
                }
                this.filteredCustomers = this.allUsers.filter(u =>
                    u.name.toLowerCase().includes(this.customerSearch.toLowerCase()) ||
                    u.email.toLowerCase().includes(this.customerSearch.toLowerCase())
                ).slice(0, 5); // Giới hạn 5 kết quả cho gọn
            },

            selectCustomer(user) {
                this.newOrder.customer_id = user.id;
                this.newOrder.customer_name = user.name;
                this.newOrder.address = user.address || ''; // Tự động điền địa chỉ nếu có
                this.customerSearch = '';
                this.filteredCustomers = [];
            },

            // Tìm sản phẩm theo tên hoặc SKU
            searchProducts() {
                if (this.productSearch.length < 2) {
                    this.filteredProducts = [];
                    return;
                }
                this.filteredProducts = this.allProductsList.filter(p =>
                    p.name.toLowerCase().includes(this.productSearch.toLowerCase()) ||
                    (p.sku && p.sku.toLowerCase().includes(this.productSearch.toLowerCase()))
                ).slice(0, 5);
            },

            {{-- --- LOGIC GIỎ HÀNG (MỚI) --- --}}

        addItem(product) {
                const existing = this.newOrder.items.find(i => i.product_id === product.id);
                if (existing) {
                    existing.quantity++;
                    existing.total = existing.quantity * existing.price;
                } else {
                    this.newOrder.items.push({
                        product_id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        quantity: 1,
                        image: product.image,
                        total: parseFloat(product.price)
                    });
                }
                this.productSearch = '';
                this.filteredProducts = [];
            },

            removeItem(index) {
                this.newOrder.items.splice(index, 1);
            },

            // Reset form khi bấm nút Tạo mới
            createNewOrder() {
                this.newOrder = {
                    customer_id: '',
                    customer_name: '',
                    address: '',
                    payment_method: 'bank_transfer',
                    notes: '',
                    items: []
                };
                this.isCreatePanelOpen = true;
            },

            get newOrderTotal() {
                return this.newOrder.items.reduce((sum, item) => sum + item.total, 0);
            },

            {{-- --- CÁC HÀM HỆ THỐNG CŨ (GIỮ NGUYÊN) --- --}}

        applyPagination() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                this.paginatedOrders = this.allOrders.slice(start, start + this.itemsPerPage);
                this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
            },

            handleBulkAction(actionType) {
                if (this.selectedOrders.length === 0) {
                    alert('Vui lòng chọn ít nhất một đơn hàng!');
                    return;
                }
                this.bulkAction = actionType;
                this.showBulkModal = true;
            },

            exportSelectedOrders() {
                if (this.selectedOrders.length === 0) {
                    alert('Hãy chọn đơn hàng muốn xuất!');
                    return;
                }
                window.location.href = '{{ route('admin.orders.export') }}?ids=' + this.selectedOrders.join(',');
            },

            viewOrder(order) {
                this.selectedOrder = JSON.parse(JSON.stringify(order));
                if (order.user) {
                    this.selectedOrder.customer_name = order.user.name;
                    this.selectedOrder.customer_email = order.user.email;
                }
                this.isPanelOpen = true;
            },

            toggleSelectAll() {
                if (this.selectedOrders.length === this.paginatedOrders.length) {
                    this.selectedOrders = [];
                } else {
                    this.selectedOrders = this.paginatedOrders.map(o => o.id);
                }
            },

            clearFilters() { window.location.href = '{{ route('admin.orders.index') }}'; },

            exportOrders() {
                let url = '{{ route('admin.orders.export') }}';
                const params = new URLSearchParams();
                if (this.filters.search) params.append('search', this.filters.search);
                if (this.filters.orderStatus !== 'all') params.append('status', this.filters.orderStatus);
                if (this.filters.paymentStatus !== 'all') params.append('payment_status', this.filters.paymentStatus);
                window.location.href = url + '?' + params.toString();
            }
    }">

        <x-admin.orders.orders-header :totalOrders="$allOrders->count()" />
        <x-admin.orders.status-overview :stats="$stats" />
        <x-admin.orders.orders-filters />
        <x-admin.orders.orders-table />
        <x-admin.pagination />

        {{-- Panel Chi tiết đơn hàng --}}
        <x-admin.orders.order-details-panel />

        {{-- PANEL TẠO ĐƠN HÀNG MỚI --}}
        <div x-show="isCreatePanelOpen" class="fixed inset-0 z-[100] overflow-hidden" x-cloak>
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" x-show="isCreatePanelOpen"
                x-transition:enter="duration-300" x-transition:leave="duration-200" @click="isCreatePanelOpen = false"></div>

            <div
                class="absolute inset-y-0 right-0 max-w-2xl w-full bg-[#0f172a] shadow-2xl flex flex-col border-l border-slate-800">
                {{-- Header --}}
                <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/80">
                    <div>
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter">Tạo đơn hàng mới</h2>
                        <p class="text-slate-500 text-xs">Điền đầy đủ thông tin để khởi tạo đơn hàng thủ công</p>
                    </div>
                    <button @click="isCreatePanelOpen = false"
                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-800 text-slate-400 hover:text-white transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-8 custom-scrollbar">
                    {{-- Bước 1: Khách hàng --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2 text-blue-500">
                            <i class="fas fa-user-circle text-sm"></i>
                            <h3 class="text-[11px] font-black uppercase tracking-widest">Thông tin khách hàng</h3>
                        </div>

                        <div class="relative">
                            <template x-if="!newOrder.customer_id">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                    <input type="text" x-model="customerSearch" @input.debounce.300ms="searchCustomers()"
                                        placeholder="Tìm theo tên hoặc email khách hàng..."
                                        class="w-full bg-slate-900/50 border border-slate-700 rounded-2xl pl-11 pr-4 py-3.5 text-white outline-none focus:border-blue-500 transition-all">

                                    {{-- Dropdown kết quả khách hàng --}}
                                    <div x-show="filteredCustomers.length > 0"
                                        class="absolute w-full mt-2 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-10 max-h-48 overflow-y-auto">
                                        <template x-for="user in filteredCustomers" :key="user.id">
                                            <div @click="selectCustomer(user)"
                                                class="p-3 hover:bg-slate-700 cursor-pointer border-b border-slate-700/50 last:border-0">
                                                <p class="text-sm font-bold text-white" x-text="user.name"></p>
                                                <p class="text-[10px] text-slate-400" x-text="user.email"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="newOrder.customer_id">
                                <div
                                    class="flex items-center justify-between bg-blue-500/10 border border-blue-500/20 p-4 rounded-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold"
                                            x-text="newOrder.customer_name.charAt(0)"></div>
                                        <div>
                                            <p class="text-white font-bold" x-text="newOrder.customer_name"></p>
                                            <p class="text-blue-400 text-[10px] uppercase font-black">Khách hàng đã chọn</p>
                                        </div>
                                    </div>
                                    <button @click="newOrder.customer_id = ''; newOrder.customer_name = ''"
                                        class="text-slate-500 hover:text-red-500"><i
                                            class="fas fa-times-circle"></i></button>
                                </div>
                            </template>
                        </div>
                    </section>

                    <hr class="border-slate-800/50">

                    {{-- Bước 2: Sản phẩm --}}
                    <section class="space-y-4">
                        <div class="flex items-center gap-2 text-green-500">
                            <i class="fas fa-shopping-basket text-sm"></i>
                            <h3 class="text-[11px] font-black uppercase tracking-widest">Sản phẩm & Số lượng</h3>
                        </div>

                        <div class="relative">
                            <i class="fas fa-plus absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                            <input type="text" x-model="productSearch" @input.debounce.300ms="searchProducts()"
                                placeholder="Nhập tên hoặc mã SKU sản phẩm..."
                                class="w-full bg-slate-900/50 border border-slate-700 rounded-2xl pl-11 pr-4 py-3.5 text-white outline-none focus:border-green-500 transition-all">

                            {{-- Dropdown kết quả sản phẩm --}}
                            <div x-show="filteredProducts.length > 0"
                                class="absolute w-full mt-2 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-10 max-h-60 overflow-y-auto">
                                <template x-for="p in filteredProducts" :key="p.id">
                                    <div @click="addItem(p); filteredProducts = []; productSearch = ''"
                                        class="p-3 hover:bg-slate-700 cursor-pointer flex justify-between items-center border-b border-slate-700/50">
                                        <div>
                                            <p class="text-sm font-bold text-white" x-text="p.name"></p>
                                            <p class="text-[10px] text-slate-400"
                                                x-text="'SKU: ' + p.sku + ' | Kho: ' + p.stock"></p>
                                        </div>
                                        <span class="text-green-500 font-black text-xs"
                                            x-text="new Intl.NumberFormat('vi-VN').format(p.price) + '₫'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Giỏ hàng tạm thời --}}
                        <div class="space-y-3">
                            <template x-for="(item, index) in newOrder.items" :key="index">
                                <div
                                    class="bg-slate-900/30 border border-slate-800 p-4 rounded-2xl flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center overflow-hidden">
                                            <img :src="item.image || 'https://placehold.co/100'"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-white font-bold text-sm" x-text="item.name"></p>
                                            <p class="text-slate-500 text-xs"
                                                x-text="new Intl.NumberFormat('vi-VN').format(item.price) + '₫'"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex items-center bg-slate-900 rounded-lg border border-slate-700 overflow-hidden">
                                            <button
                                                @click="if(item.quantity > 1) { item.quantity--; item.total = item.quantity * item.price }"
                                                class="px-2 py-1 hover:bg-slate-800 text-slate-400">-</button>
                                            <input type="number" x-model.number="item.quantity"
                                                @input="item.total = item.quantity * item.price"
                                                class="w-10 bg-transparent text-center text-xs text-white outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button @click="item.quantity++; item.total = item.quantity * item.price"
                                                class="px-2 py-1 hover:bg-slate-800 text-slate-400">+</button>
                                        </div>
                                        <button @click="removeItem(index)"
                                            class="text-slate-600 hover:text-red-500 transition-colors"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </section>

                    <hr class="border-slate-800/50">

                    {{-- Bước 3: Giao hàng & Thanh toán --}}
                    <section class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Địa chỉ giao hàng thực
                                tế</label>
                            <textarea x-model="newOrder.address" rows="2"
                                class="w-full bg-slate-900/50 border border-slate-700 rounded-2xl px-4 py-3 text-white text-sm outline-none focus:border-blue-500"
                                placeholder="Số nhà, tên đường, phường/xã..."></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Thanh toán</label>
                            <select x-model="newOrder.payment_method"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm outline-none">
                                <option value="bank_transfer">Chuyển khoản</option>
                                <option value="cod">Tiền mặt (COD)</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Ghi chú đơn</label>
                            <input type="text" x-model="newOrder.notes"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm outline-none"
                                placeholder="Ví dụ: Giao giờ hành chính">
                        </div>
                    </section>
                </div>

                {{-- Footer Tổng kết & Submit --}}
                <div class="p-6 border-t border-slate-800 bg-slate-900/80">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Tổng thanh toán
                            </p>
                            <p class="text-3xl font-black text-white"
                                x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(newOrderTotal)">
                            </p>
                        </div>
                        <div class="text-right text-slate-500 text-xs">
                            <p x-text="newOrder.items.length + ' sản phẩm'"></p>
                            <p>Miễn phí vận chuyển</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_data" :value="JSON.stringify(newOrder)">
                        <button type="submit" :disabled="newOrder.items.length === 0 || !newOrder.address"
                            class="w-full py-4 bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 disabled:text-slate-600 disabled:cursor-not-allowed text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-blue-500/10 transition-all active:scale-[0.98]">
                            Xác nhận khởi tạo đơn hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- --- MODAL THAO TÁC HÀNG LOẠT (GIỮ NGUYÊN) --- --}}
        <div x-show="showBulkModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" x-show="showBulkModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" @click="showBulkModal = false"></div>

            <div class="relative w-full max-w-md bg-[#1e293b] border border-slate-700 rounded-2xl shadow-2xl p-6 overflow-hidden"
                x-show="showBulkModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-layer-group text-blue-500"></i>
                        <span
                            x-text="bulkAction === 'update_status' ? 'Cập nhật trạng thái' : 'Xác nhận hủy đơn hàng'"></span>
                    </h3>
                    <button @click="showBulkModal = false" class="text-slate-400 hover:text-white"><i
                            class="fas fa-times"></i></button>
                </div>

                <form action="{{ route('admin.orders.bulk_action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" :value="bulkAction">
                    <template x-for="id in selectedOrders" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>

                    <div class="space-y-5">
                        <div
                            class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center gap-2 text-xs text-blue-400">
                            <i class="fas fa-info-circle"></i>
                            <span>Bạn đã chọn <strong class="text-white" x-text="selectedOrders.length"></strong> đơn
                                hàng.</span>
                        </div>
                        <div x-show="bulkAction === 'update_status'">
                            <label class="block text-slate-400 text-[10px] font-bold uppercase mb-2 tracking-widest">Trạng
                                thái mới</label>
                            <select name="status" x-model="bulkStatus"
                                class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 outline-none">
                                <option value="pending">Chờ xác nhận</option>
                                <option value="processing">Đang xử lý</option>
                                <option value="shipping">Đang giao hàng</option>
                                <option value="completed">Đã hoàn thành</option>
                            </select>
                        </div>
                        <div x-show="bulkAction === 'cancel'"
                            class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex gap-3">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                            <p class="text-sm text-red-400 font-medium">Lưu ý: Thao tác này sẽ hủy các đơn hàng đã chọn và
                                không thể khôi phục.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <button type="button" @click="showBulkModal = false"
                            class="px-5 py-2 text-slate-400 hover:text-white font-medium">Hủy bỏ</button>
                        <button type="submit" :class="bulkAction === 'cancel' ? 'bg-red-600' : 'bg-blue-600'"
                            class="px-6 py-2.5 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20">Xác
                            nhận</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- --- MODAL THAO TÁC HÀNG LOẠT --- --}}
        <div x-show="showBulkModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" x-show="showBulkModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" @click="showBulkModal = false"></div>

            <div class="relative w-full max-w-md bg-[#1e293b] border border-slate-700 rounded-2xl shadow-2xl p-6 overflow-hidden"
                x-show="showBulkModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-layer-group text-blue-500"></i>
                        <span
                            x-text="bulkAction === 'update_status' ? 'Cập nhật trạng thái' :
                            (bulkAction === 'update_shipping' ? 'Cập nhật giao hàng' : 'Xác nhận hủy đơn hàng')"></span>
                    </h3>
                    <button @click="showBulkModal = false" class="text-slate-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.orders.bulk_action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" :value="bulkAction">
                    <template x-for="id in selectedOrders" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>

                    <div class="space-y-5">
                        <div
                            class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center gap-2 text-xs text-blue-400">
                            <i class="fas fa-info-circle"></i>
                            <span>Bạn đã chọn <strong class="text-white" x-text="selectedOrders.length"></strong> đơn
                                hàng.</span>
                        </div>

                        {{-- Khối 1: Cập nhật Trạng thái đơn hàng --}}
                        <div x-show="bulkAction === 'update_status'">
                            <label class="block text-slate-400 text-[10px] font-bold uppercase mb-2 tracking-widest">Trạng
                                thái đơn mới</label>
                            <select name="status" x-model="bulkStatus"
                                class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach (\App\Enums\OrderStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Khối 2: Cập nhật Tình trạng giao hàng (MỚI THÊM) --}}
                        <div x-show="bulkAction === 'update_shipping'">
                            <label class="block text-slate-400 text-[10px] font-bold uppercase mb-2 tracking-widest">Tình
                                trạng giao hàng mới</label>
                            <select name="shipping_status" x-model="bulkShippingStatus"
                                class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach (\App\Enums\ShippingStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Khối 3: Hủy đơn --}}
                        <div x-show="bulkAction === 'cancel'"
                            class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex gap-3">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                            <p class="text-sm text-red-400 font-medium">Lưu ý: Thao tác này sẽ hủy các đơn hàng đã chọn và
                                không thể khôi phục.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <button type="button" @click="showBulkModal = false"
                            class="px-5 py-2 text-slate-400 hover:text-white font-medium">Hủy bỏ</button>
                        <button type="submit"
                            :class="bulkAction === 'cancel' ? 'bg-red-600' : (bulkAction === 'update_shipping' ?
                                'bg-indigo-600' : 'bg-blue-600')"
                            class="px-6 py-2.5 text-white rounded-xl font-bold shadow-lg transition-all active:scale-95">
                            Xác nhận
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
