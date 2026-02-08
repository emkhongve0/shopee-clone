@props(['products' => []])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <template x-for="product in paginatedProducts" :key="product.id">
        <div
            class="bg-[#1e293b] border border-slate-800 rounded-2xl overflow-hidden hover:border-blue-500/50 transition-all hover:shadow-2xl hover:shadow-blue-500/10 group relative flex flex-col">

            {{-- Phần hình ảnh & Overlays --}}
            <div class="relative aspect-square bg-slate-900 overflow-hidden">
                <img :src="product.image" :alt="product.name"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    @@error="$el.src='https://placehold.co/400x400/1e293b/475569?text=No+Image'">

                {{-- Checkbox chọn sản phẩm --}}
                <div class="absolute top-3 left-3 z-20">
                    <label class="relative flex items-center cursor-pointer">
                        <input type="checkbox" :value="product.id" x-model="selectedProducts"
                            class="w-5 h-5 rounded border-slate-700 bg-slate-900/80 backdrop-blur-md text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-900 transition-all">
                    </label>
                </div>

                {{-- Huy hiệu Trạng thái --}}
                <div class="absolute top-3 right-3 z-20">
                    <span
                        class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border backdrop-blur-md"
                        :class="{
                            'bg-green-500/10 text-green-500 border-green-500/20': product.status === 'active',
                            'bg-orange-500/10 text-orange-500 border-orange-500/20': product.status === 'draft',
                            'bg-slate-500/10 text-slate-400 border-slate-500/20': product.status === 'hidden'
                        }"
                        x-text="product.status === 'active' ? 'Đang bán' : (product.status === 'draft' ? 'Bản nháp' : 'Đã ẩn')"></span>
                </div>

                {{-- Thanh thao tác nhanh (Hiện khi hover) --}}
                <div
                    class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-t from-slate-950/80 to-transparent flex justify-center gap-2 z-30">
                    <button @@click="viewProduct(product)"
                        class="w-9 h-9 flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg transition-all active:scale-90"
                        title="Xem chi tiết">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                    <button @@click="viewProduct(product)"
                        class="w-9 h-9 flex items-center justify-center bg-slate-800 hover:bg-slate-700 text-white rounded-xl shadow-lg transition-all active:scale-90"
                        title="Chỉnh sửa">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button
                        class="w-9 h-9 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-lg transition-all active:scale-90"
                        title="Xóa">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- Thông tin sản phẩm --}}
            <div class="p-4 flex-1 flex flex-col cursor-pointer" @@click="viewProduct(product)">
                {{-- Danh mục --}}
                <div class="mb-2">
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded-md border border-purple-500/10"
                        x-text="product.category"></span>
                </div>

                {{-- Tên sản phẩm --}}
                <h3 class="text-white font-bold text-sm mb-1 line-clamp-2 min-h-[40px] group-hover:text-blue-400 transition-colors"
                    x-text="product.name"></h3>

                {{-- SKU --}}
                <p class="text-slate-500 text-[11px] font-mono mb-4">Mã: <span x-text="product.sku"></span></p>

                {{-- Giá & Tình trạng kho --}}
                <div class="flex items-center justify-between mb-3 mt-auto">
                    <div class="text-white font-black text-lg" x-text="'$' + product.price.toFixed(2)"></div>

                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border"
                        :class="{
                            'bg-red-500/10 text-red-500 border-red-500/20': product.stock === 0,
                            'bg-orange-500/10 text-orange-500 border-orange-500/20': product.stock > 0 && product
                                .stock < 20,
                            'bg-green-500/10 text-green-500 border-green-500/20': product.stock >= 20
                        }"
                        x-text="product.stock === 0 ? 'Hết hàng' : (product.stock < 20 ? 'Sắp hết' : 'Còn hàng')"></span>
                </div>


                <div class="flex items-center justify-between gap-1.5 border-t border-slate-800/50 pt-3">
                    <div class="flex items-center gap-0.5">
                        <i class="fas fa-star text-yellow-500 text-[10px]"></i>
                        <span class="text-white font-bold text-xs" x-text="product.rating.toFixed(1)"></span>
                        <span class="text-slate-500 text-[11px]" x-text="'(' + product.reviews + ' đánh giá)'"></span>
                    </div>
                    <span class="text-slate-400 text-xs font-semibold" x-text="product.stock + ' sản phẩm'"></span>
                </div>
            </div>
        </div>
    </template>
</div>
