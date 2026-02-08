@props(['orders' => []])

@php
    // Helper: Lấy chữ cái đầu của tên khách hàng
    $getInitials = function ($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= mb_substr($w, 0, 1);
        }
        return mb_strtoupper($initials);
    };

    // Helper: Định dạng phương thức thanh toán
    $formatPaymentMethod = function ($method) {
        $methods = [
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'bank_transfer' => 'Bank Transfer',
        ];
        return $methods[$method] ?? ucfirst(str_replace('_', ' ', $method));
    };
@endphp

{{-- Container chính: Đổi overflow-hidden thành overflow-visible để hiện dropdown --}}
<div class="bg-[#1e293b] border border-slate-800 rounded-2xl shadow-sm overflow-visible" x-data="{
    allSelected: false,
    {{-- Logic xử lý màu sắc Badge (Có thể gọi component OrderStatusBadge nếu bạn đã tạo) --}}
    getStatusClass(status) {
        const classes = {
            'completed': 'bg-green-500/10 text-green-500 border-green-500/20',
            'paid': 'bg-green-500/10 text-green-500 border-green-500/20',
            'delivered': 'bg-green-500/10 text-green-500 border-green-500/20',
            'pending': 'bg-amber-500/10 text-amber-500 border-amber-500/20',
            'processing': 'bg-blue-500/10 text-blue-500 border-blue-500/20',
            'shipping': 'bg-purple-500/10 text-purple-500 border-purple-500/20',
            'cancelled': 'bg-red-500/10 text-red-500 border-red-500/20',
            'failed': 'bg-red-500/10 text-red-500 border-red-500/20'
        };
        return classes[status] || 'bg-slate-500/10 text-slate-500 border-slate-500/20';
    }
}">

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-900/50 border-b border-slate-800 text-slate-400 font-black text-[10px] uppercase tracking-widest">
                    <th class="py-4 px-6 w-12 text-center">
                        <input type="checkbox" @click="toggleSelectAll()"
                            :checked="selectedOrders.length === paginatedProducts.length && paginatedProducts.length > 0"
                            class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-600 focus:ring-offset-slate-900 cursor-pointer">
                    </th>
                    <th class="py-4 px-4">Mã đơn hàng</th>
                    <th class="py-4 px-4">Khách hàng</th>
                    <th class="py-4 px-4">Ngày đặt</th>
                    <th class="py-4 px-4 text-center">Thanh toán</th>
                    <th class="py-4 px-4 text-center">Giao hàng</th>
                    <th class="py-4 px-4 text-center">Trạng thái</th>
                    <th class="py-4 px-4 text-center">Tổng tiền</th>
                    <th class="py-4 px-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                {{-- Empty State --}}
                <template x-if="paginatedProducts.length === 0">
                    <tr>
                        <td colspan="9" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fas fa-box-open text-4xl text-slate-700"></i>
                                <p class="text-slate-500 font-medium">Không tìm thấy đơn hàng nào.</p>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Order Rows --}}
                <template x-for="order in paginatedProducts" :key="order.id">
                    <tr class="hover:bg-slate-800/40 transition-all cursor-pointer group"
                        :class="selectedOrders.includes(order.id) ? 'bg-blue-600/5' : ''" @click="viewOrder(order)">

                        {{-- Checkbox --}}
                        <td class="py-4 px-6 text-center" @click.stop>
                            <input type="checkbox" :value="order.id" x-model="selectedOrders"
                                class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-600 focus:ring-offset-slate-900 cursor-pointer">
                        </td>

                        {{-- Order ID --}}
                        <td class="py-4 px-4 font-mono text-xs font-bold text-white" x-text="order.orderId"></td>

                        {{-- Customer --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-[10px] font-black text-white shadow-lg overflow-hidden shrink-0">
                                    <template x-if="order.customer.avatar">
                                        <img :src="order.customer.avatar" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!order.customer.avatar">
                                        <span x-text="order.customer.name.charAt(0)"></span>
                                    </template>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white font-bold text-sm truncate" x-text="order.customer.name"></p>
                                    <p class="text-slate-500 text-[10px] truncate" x-text="order.customer.email"></p>
                                </div>
                            </div>
                        </td>

                        {{-- Date --}}
                        <td class="py-4 px-4">
                            <div class="text-white text-xs font-bold" x-text="order.orderDate"></div>
                            <div class="text-slate-500 text-[10px] uppercase font-mono"
                                x-text="order.orderTime || '10:30 AM'"></div>
                        </td>

                        {{-- Status Badges --}}
                        <td class="py-4 px-4 text-center">
                            <span :class="getStatusClass(order.paymentStatus)"
                                class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase border tracking-wider"
                                x-text="order.paymentStatus"></span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span :class="getStatusClass(order.shippingStatus)"
                                class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase border tracking-wider"
                                x-text="order.shippingStatus"></span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span :class="getStatusClass(order.orderStatus)"
                                class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase border tracking-wider"
                                x-text="order.orderStatus"></span>
                        </td>

                        {{-- Total --}}
                        <td class="py-4 px-4 text-center font-black text-white text-sm"
                            x-text="'$' + order.total.toLocaleString()"></td>

                        {{-- Actions Dropdown --}}
                        <td class="py-4 px-6 text-right" @click.stop>
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open" @click.away="open = false"
                                    class="text-slate-500 hover:text-white p-2 hover:bg-slate-700 rounded-lg transition-all">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>

                                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100" {{-- z-index cao và shadow cực mạnh --}}
                                    class="absolute right-0 mt-2 w-48 bg-[#1e293b] border border-slate-700 rounded-xl shadow-2xl z-[100] overflow-hidden">

                                    <button @click="viewOrder(order); open = false"
                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-blue-600 hover:text-white transition-all text-left">
                                        <i class="fas fa-eye w-4 text-blue-500"></i> Xem chi tiết
                                    </button>
                                    <button
                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-blue-600 hover:text-white transition-all text-left border-t border-slate-800/50">
                                        <i class="fas fa-print w-4 text-slate-400"></i> In hóa đơn
                                    </button>
                                    <div class="border-t border-slate-800/50"></div>
                                    <button
                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-blue-600 hover:text-white transition-all text-left">
                                        <i class="fas fa-dollar-sign w-4 text-green-500"></i> Hoàn tiền
                                    </button>
                                    <button
                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-all text-left border-t border-slate-800/50">
                                        <i class="fas fa-ban w-4 text-red-500"></i> Hủy đơn hàng
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
