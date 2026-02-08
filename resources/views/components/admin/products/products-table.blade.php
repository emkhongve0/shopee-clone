@props(['products' => []])

<div class="bg-[#1e293b] border border-slate-800 rounded-xl overflow-hidden shadow-lg" x-data="{
    {{-- Giữ nguyên logic cũ --}}
    toggleSelectAll() {
            if (this.selectedProducts.length === this.paginatedProducts.length) {
                this.selectedProducts = [];
            } else {
                this.selectedProducts = this.paginatedProducts.map(p => p.id);
            }
        },
        getStockInfo(stock) {
            if (stock === 0) return { label: 'Hết hàng', class: 'bg-red-500/10 text-red-500 border-red-500/20' };
            if (stock < 20) return { label: 'Sắp hết', class: 'bg-orange-500/10 text-orange-500 border-orange-500/20' };
            return { label: 'Còn hàng', class: 'bg-green-500/10 text-green-500 border-green-500/20' };
        }
}">

    {{-- 1. Thanh thao tác hàng loạt (Kết nối với Modal Floating) --}}
    <div x-show="selectedProducts.length > 0" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-blue-600/10 border-b border-blue-500/20 px-6 py-3 flex items-center justify-between" x-cloak>

        <p class="text-blue-400 text-sm font-medium">
            <i class="fas fa-check-circle mr-2"></i>Đã chọn <span x-text="selectedProducts.length"></span> sản phẩm
        </p>

        <div class="flex items-center gap-2">
            {{-- Kích hoạt Modal Cập nhật trạng thái --}}
            <button @click="$dispatch('open-bulk-modal', 'update_status')"
                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all active:scale-95">
                Cập nhật trạng thái
            </button>

            {{-- Kích hoạt Modal Xóa hàng loạt --}}
            <button @click="$dispatch('open-bulk-modal', 'delete')"
                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-all active:scale-95">
                Xóa mục đã chọn
            </button>
        </div>
    </div>

    {{-- Bảng dữ liệu --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 bg-[#1e293b] z-10">
                <tr
                    class="border-b border-slate-800 bg-slate-900/50 text-slate-400 font-semibold text-xs uppercase tracking-wider">
                    <th class="py-4 px-6 w-12 text-center">
                        <input type="checkbox" @click="toggleSelectAll()"
                            :checked="selectedProducts.length === paginatedProducts.length && paginatedProducts.length > 0"
                            class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-600 focus:ring-offset-slate-900 cursor-pointer">
                    </th>
                    <th class="py-4 px-4">Sản phẩm</th>
                    <th class="py-4 px-4">Danh mục</th>
                    <th class="py-4 px-4 text-center">Giá</th>
                    <th class="py-4 px-4 text-center">Tồn kho</th>
                    <th class="py-4 px-4">Trạng thái</th>
                    <th class="py-4 px-4">Đánh giá</th>
                    <th class="py-4 px-6 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <template x-for="product in paginatedProducts" :key="product.id">
                    <tr class="hover:bg-slate-800/30 transition-all cursor-pointer group"
                        @@click="viewProduct(product)">

                        <td class="py-4 px-6 text-center" @@click.stop>
                            <input type="checkbox" :value="product.id" x-model="selectedProducts"
                                class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-600 focus:ring-offset-slate-900 cursor-pointer">
                        </td>

                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-lg bg-slate-800 border border-slate-700 overflow-hidden shrink-0 shadow-sm">
                                    <img :src="product.image"
                                        class="w-full h-full object-cover transition-transform group-hover:scale-110"
                                        @@error="$el.src='https://placehold.co/400x400/1e293b/475569?text=No+Image'">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white font-bold text-sm truncate" x-text="product.name"></p>
                                    <p class="text-slate-500 text-[10px] font-mono uppercase"
                                        x-text="'SKU: ' + product.sku"></p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-4">
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-500/10 text-purple-500 border border-purple-500/20"
                                x-text="product.category"></span>
                        </td>

                        {{-- Hiển thị tiền tệ VND chuẩn Backend --}}
                        <td class="py-4 px-4 text-white font-black text-sm text-center"
                            x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.price)">
                        </td>

                        <td class="py-4 px-4 text-center">
                            <span
                                class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border inline-block min-w-[110px]"
                                :class="getStockInfo(product.stock).class"
                                x-text="getStockInfo(product.stock).label"></span>
                        </td>

                        <td class="py-4 px-4">
                            <span
                                class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border inline-block"
                                :class="{
                                    'bg-green-500/10 text-green-500 border-green-500/20': product.status === 'active',
                                    'bg-orange-500/10 text-orange-500 border-orange-500/20': product
                                        .status === 'draft',
                                    'bg-slate-700/50 text-slate-400 border-slate-600': product.status === 'hidden'
                                }"
                                x-text="product.status === 'active' ? 'Đang bán' : (product.status === 'draft' ? 'Bản nháp' : 'Đã ẩn')"></span>
                        </td>

                        <td class="py-4 px-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-star text-yellow-500 text-[10px]"></i>
                                    <span class="text-white font-bold text-sm"
                                        x-text="product.rating.toFixed(1)"></span>
                                    <span class="text-slate-500 text-[11px]"
                                        x-text="'(' + product.reviews + ')'"></span>
                                </div>
                                <span class="text-slate-400 text-xs font-semibold whitespace-nowrap"
                                    x-text="product.stock + ' sản phẩm'"></span>
                            </div>
                        </td>

                        {{-- 2. Thao tác đơn lẻ (Kết nối Route Backend) --}}
                        <td class="py-4 px-6 text-right" @click.stop>
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @@click="open = !open"
                                    @@click.away="open = false"
                                    class="text-slate-400 hover:text-white p-2 hover:bg-slate-700 rounded-lg transition-all">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>

                                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-50 overflow-hidden">

                                    {{-- Xem chi tiết --}}
                                    <button @@click="viewProduct(product); open = false"
                                        class="flex items-center w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-left">
                                        <i class="far fa-eye w-5 text-blue-500"></i> Xem chi tiết
                                    </button>

                                    {{-- Sửa sản phẩm: Chuyển hướng trang --}}
                                    <button @@click="viewProduct(product); open = false"
                                        class="flex items-center w-full px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-all text-left border-t border-slate-700/50">
                                        <i class="far fa-edit w-5 text-green-500"></i> Sửa sản phẩm
                                    </button>

                                    {{-- Xóa đơn lẻ: Tận dụng Modal Bulk Action nhưng chỉ truyền 1 ID --}}
                                    <button
                                        @@click="selectedProducts = [product.id]; $dispatch('open-bulk-modal', 'delete'); open = false"
                                        class="flex items-center w-full px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-all text-left border-t border-slate-700/50">
                                        <i class="far fa-trash-alt w-5 text-red-500"></i> Xóa sản phẩm
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
