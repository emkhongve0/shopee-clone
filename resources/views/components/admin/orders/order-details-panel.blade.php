{{-- Overlay mờ --}}
<div x-show="isPanelOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="isPanelOpen = false"
    class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[100]" x-cloak></div>

{{-- Panel trượt --}}
<div x-show="isPanelOpen" x-transition:enter="transform transition ease-in-out duration-500"
    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    class="fixed inset-y-0 right-0 w-full max-w-2xl bg-[#1e293b] border-l border-slate-800 shadow-2xl overflow-hidden z-[110]"
    x-cloak>

    <template x-if="selectedOrder">
        <div class="flex flex-col h-full">
            {{-- 1. Header --}}
            <div class="sticky top-0 bg-[#1e293b] border-b border-slate-800 p-6 z-10">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-1">Chi tiết đơn hàng</h2>
                        <p class="text-slate-400 font-mono text-sm" x-text="selectedOrder.orderId"></p>
                    </div>
                    <button @click="isPanelOpen = false"
                        class="text-slate-400 hover:text-white p-2 hover:bg-slate-800 rounded-xl transition-all">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Status Badges (Logic màu sắc khớp với Enum value) --}}
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border"
                        :class="{
                            'bg-orange-500/10 text-orange-500 border-orange-500/20': selectedOrder
                                .orderStatus === 'pending',
                            'bg-blue-500/10 text-blue-500 border-blue-500/20': selectedOrder
                                .orderStatus === 'processing',
                            'bg-indigo-500/10 text-indigo-500 border-indigo-500/20': selectedOrder
                                .orderStatus === 'shipped',
                            'bg-emerald-500/10 text-emerald-500 border-emerald-500/20': selectedOrder
                                .orderStatus === 'completed',
                            'bg-red-500/10 text-red-500 border-red-500/20': selectedOrder.orderStatus === 'canceled'
                        }"
                        x-text="selectedOrder.orderStatus">
                    </span>

                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider"
                        :class="selectedOrder.paymentStatus === 'paid' ?
                            'bg-green-500/10 text-green-400 border-green-500/20' :
                            'bg-slate-500/10 text-slate-400 border-slate-500/20'"
                        x-text="selectedOrder.paymentStatus === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'">
                    </span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">

                {{-- 2. Action Buttons: Form cập nhật trạng thái --}}
                <div class="bg-slate-900/50 p-4 rounded-xl border border-slate-700">
                    <h4 class="text-white font-bold mb-3 text-sm">Cập nhật trạng thái đơn hàng</h4>

                    {{-- Form gửi dữ liệu về Route update status --}}
                    {{-- Action trỏ về: /admin/orders/{id}/status --}}
                    <form :action="'/admin/orders/' + selectedOrder.id + '/status'" method="POST"
                        class="grid grid-cols-3 gap-3">
                        @csrf
                        @method('PATCH')

                        <select name="status"
                            class="col-span-2 bg-slate-800 text-white text-sm rounded-lg border border-slate-600 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            {{-- Loop qua Enum để tạo Option --}}
                            @foreach (\App\Enums\OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{-- Logic selected của Alpine.js --}}
                                    :selected="selectedOrder.orderStatus === '{{ $status->value }}'">
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-all shadow-lg shadow-blue-900/20 active:scale-95">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a :href="'/admin/orders/' + (selectedOrder ? selectedOrder.id : '') + '/download'"
                        class="flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm transition-all shadow-lg active:scale-95">
                        <i class="fas fa-download"></i>
                        Tải hóa đơn (PDF)
                    </a>
                    {{-- Form Hủy đơn nhanh (Ẩn input status = canceled) --}}
                    <form :action="'/admin/orders/' + selectedOrder.id + '/status'" method="POST" class="contents">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="canceled">
                        <button type="submit" onclick="return confirm('Bạn chắc chắn muốn hủy đơn này?')"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-red-500/10 text-red-400 rounded-xl font-bold text-sm transition-all border border-slate-700 active:scale-95">
                            <i class="fas fa-ban"></i> Hủy đơn ngay
                        </button>
                    </form>
                </div>

                <hr class="border-slate-800">

                {{-- 3. Order Summary --}}
                <div class="bg-slate-900/50 rounded-2xl p-5 border border-slate-800">
                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-box text-blue-500"></i> Tóm tắt đơn hàng
                    </h3>
                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="text-slate-500 mb-1">Ngày đặt hàng</p>
                            <p class="text-white font-semibold" x-text="selectedOrder.orderDate"></p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Tổng thanh toán</p>
                            {{-- Format tiền Việt Nam --}}
                            <p class="text-blue-400 font-black text-lg"
                                x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(selectedOrder.total)">
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Phương thức thanh toán</p>
                            <p class="text-white font-semibold uppercase">Chuyển khoản / COD</p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Số lượng</p>
                            <p class="text-white font-semibold" x-text="selectedOrder.items.length + ' sản phẩm'"></p>
                        </div>
                    </div>
                </div>

                {{-- 4. Customer Information --}}
                <div class="bg-slate-900/50 rounded-2xl p-5 border border-slate-800">
                    <h3 class="text-white font-bold mb-4">Thông tin khách hàng</h3>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-blue-500/20 overflow-hidden">
                            {{-- Lấy chữ cái đầu tiên của tên --}}
                            <span x-text="selectedOrder.customer.name.charAt(0)"></span>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-bold text-base" x-text="selectedOrder.customer.name"></p>
                            <p class="text-slate-400 text-xs" x-text="selectedOrder.customer.email"></p>
                            <p class="text-slate-400 text-xs" x-text="selectedOrder.customer.phone"></p>
                        </div>
                    </div>
                </div>

                {{-- 5. Addresses --}}
                <div class="bg-slate-900/50 rounded-2xl p-5 border border-slate-800">
                    <h3 class="text-white font-bold mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-truck text-slate-500"></i> Địa chỉ giao hàng
                    </h3>
                    <div class="text-xs text-slate-400 space-y-1 leading-relaxed">
                        {{-- Hiển thị địa chỉ từ DB --}}
                        <p x-text="selectedOrder.shippingAddress.street"></p>
                        <p class="text-slate-500 uppercase font-bold text-[10px] mt-2">Việt Nam</p>
                    </div>
                </div>

                {{-- 6. Order Items --}}
                <div class="bg-slate-900/50 rounded-2xl p-5 border border-slate-800">
                    <h3 class="text-white font-bold mb-4">Sản phẩm đã đặt</h3>
                    <div class="space-y-4">
                        <template x-for="item in selectedOrder.items" :key="item.id">
                            <div
                                class="flex items-center gap-4 pb-4 border-b border-slate-800 last:border-0 last:pb-0">
                                {{-- Placeholder ảnh nếu không có --}}
                                <div
                                    class="w-16 h-16 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-600">
                                    <template x-if="item.image">
                                        <img :src="item.image" class="w-full h-full object-cover rounded-xl">
                                    </template>
                                    <template x-if="!item.image">
                                        <i class="fas fa-image"></i>
                                    </template>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-bold text-sm truncate" x-text="item.name"></p>
                                    <p class="text-slate-500 text-xs mt-1">
                                        SL: <span class="text-white" x-text="item.quantity"></span> ×
                                        <span class="text-white"
                                            x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.price)"></span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-white font-black text-sm"
                                        x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.total)">
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Footer Tổng tiền --}}
                    <div class="mt-6 pt-4 border-t border-slate-800 flex justify-between items-center">
                        <span class="text-white font-black uppercase text-xs tracking-widest">Tổng cộng</span>
                        <span class="text-blue-500 font-black text-xl"
                            x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(selectedOrder.total)"></span>
                    </div>
                </div>

                {{-- 7. Timeline --}}
                <div class="bg-slate-900/50 rounded-2xl p-5 border border-slate-800">
                    <h3 class="text-white font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-history text-slate-500"></i> Lịch sử đơn hàng
                    </h3>
                    <div class="space-y-6">
                        <div class="flex gap-4 relative">
                            <div
                                class="relative z-10 w-6 h-6 rounded-full bg-slate-800 border-2 border-slate-700 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]">
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="text-xs font-black uppercase tracking-widest text-blue-400">Đơn hàng
                                        được tạo</span>
                                    <span class="text-slate-600 text-[10px]"
                                        x-text="selectedOrder.orderDate + ' ' + selectedOrder.orderTime"></span>
                                </div>
                                <p class="text-slate-400 text-sm">Khách hàng đã đặt hàng thành công.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
