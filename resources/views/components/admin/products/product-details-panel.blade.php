@props(['product' => null, 'categories' => []])

{{-- Linh kiện Panel chỉnh sửa sản phẩm --}}
<div x-show="isPanelOpen" class="fixed inset-0 z-[100] overflow-hidden" x-cloak>
    {{-- Lớp nền mờ (Overlay) --}}
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="isPanelOpen = false"
        x-show="isPanelOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        {{-- Bảng trượt (Side Panel) --}}
        <div class="w-screen max-w-2xl transform transition duration-500 ease-in-out shadow-2xl" x-show="isPanelOpen"
            x-transition:enter="transform transition ease-in-out duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div class="flex h-full flex-col bg-[#1e293b] border-l border-slate-800 shadow-2xl">
                {{-- Phần đầu (Header) --}}
                <div
                    class="sticky top-0 bg-[#1e293b] border-b border-slate-800 p-6 flex items-center justify-between z-10">
                    <h2 class="text-white text-xl font-bold tracking-tight">Chi tiết sản phẩm</h2>
                    <button @click="isPanelOpen = false"
                        class="text-slate-400 hover:text-white transition-all p-2 hover:bg-slate-800 rounded-xl">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                    {{-- Sử dụng template x-if để bảo vệ khi selectedProduct chưa có dữ liệu --}}
                    <template x-if="selectedProduct">
                        <div class="space-y-6">
                            {{-- Hình ảnh sản phẩm --}}
                            <div x-data="{
                                previewUrl: null,
                                handleImageChange(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        this.previewUrl = URL.createObjectURL(file);
                                        // Gán file vào một thuộc tính tạm thời để hàm saveProduct sử dụng
                                        selectedProduct.imageFile = file;
                                    }
                                }
                            }">
                                <label
                                    class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-2 block">Hình
                                    ảnh sản phẩm</label>

                                {{-- Khung ảnh cho phép click --}}
                                <div @click="$refs.fileInput.click()"
                                    class="w-full aspect-video bg-slate-900 rounded-2xl overflow-hidden border border-slate-800 shadow-inner group relative cursor-pointer">

                                    {{-- Ảnh hiển thị: Ưu tiên ảnh preview mới chọn, sau đó đến ảnh cũ từ server --}}
                                    <img :src="previewUrl || selectedProduct.image" :alt="selectedProduct.name"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        @@error="$el.src='https://placehold.co/600x400/1e293b/475569?text=No+Image'">

                                    {{-- Lớp phủ khi hover --}}
                                    <div
                                        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="text-white text-center">
                                            <i class="fas fa-camera text-2xl mb-2"></i>
                                            <p class="text-xs font-bold uppercase tracking-wider">Thay đổi hình ảnh</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Input file ẩn --}}
                                <input type="file" x-ref="fileInput" class="hidden" accept="image/*"
                                    @change="handleImageChange">

                                <p class="mt-2 text-[10px] text-slate-500 italic">* Nhấp vào khung ảnh để tải lên ảnh
                                    mới</p>
                            </div>

                            {{-- Tên sản phẩm --}}
                            <div>
                                <label class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-2 block">Tên
                                    sản phẩm</label>
                                <input type="text" x-model="selectedProduct.name"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition-all" />
                            </div>

                            {{-- SKU --}}
                            <div>
                                <label class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-2 block">Mã
                                    sản phẩm (SKU)</label>
                                <input type="text" x-model="selectedProduct.sku"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white font-mono focus:outline-none focus:border-blue-500 transition-all" />
                            </div>

                            {{-- Danh mục & Trạng thái --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-2 block">Danh
                                        mục</label>
                                    <select x-model="selectedProduct.category_id"
                                        class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl py-3 px-4 appearance-none focus:outline-none focus:border-blue-500 transition-all">
                                        <option value="" disabled selected>-- Chọn danh mục --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-2 block">Trạng
                                        thái</label>
                                    <div class="relative">
                                        <select x-model="selectedProduct.status"
                                            :class="{
                                                'border-green-500 text-green-400': selectedProduct
                                                    .status === 'active',
                                                'border-yellow-500 text-yellow-400': selectedProduct
                                                    .status === 'draft',
                                                'border-red-500 text-red-400': selectedProduct.status === 'hidden'
                                            }"
                                            class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl py-3 px-4 appearance-none focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                            <option value="active">Đang bán</option>
                                            <option value="draft">Bản nháp</option>
                                            <option value="hidden">Đã ẩn</option>
                                        </select>

                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Giá & Tồn kho --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-2 block">Giá
                                        bán (VNĐ)</label>
                                    <input type="number" x-model.number="selectedProduct.price"
                                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition-all" />
                                </div>

                                <div>
                                    <label
                                        class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-2 block">Số
                                        lượng tồn kho</label>
                                    <input type="number" x-model.number="selectedProduct.stock"
                                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white focus:outline-none focus:border-blue-500 transition-all" />
                                </div>
                            </div>

                            {{-- Thống kê nhanh --}}
                            <div class="bg-slate-900/50 rounded-2xl p-5 space-y-4 border border-slate-800">
                                <div class="flex items-center justify-between border-b border-slate-800/50 pb-3">
                                    <span class="text-slate-500 text-sm">Đánh giá</span>
                                    <div class="flex items-center gap-1.5 text-white font-bold">
                                        <i class="fas fa-star text-yellow-500"></i>
                                        <span x-text="selectedProduct.rating"></span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-b border-slate-800/50 pb-3">
                                    <span class="text-slate-500 text-sm">Số lượng tồn kho</span>
                                    <span class="text-white text-sm font-bold px-3 py-1 bg-blue-600 rounded-lg"
                                        x-text="selectedProduct.stock + ' sản phẩm'"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500 text-sm">Ngày tạo</span>
                                    <span class="text-white text-sm font-semibold"
                                        x-text="selectedProduct.createdAt"></span>
                                </div>
                            </div>

                            {{-- Nút bấm --}}
                            <div class="flex items-center gap-4 pt-6 border-t border-slate-800">
                                <button @click="saveProduct(selectedProduct); isPanelOpen = false"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl font-bold transition-all active:scale-95 shadow-lg shadow-blue-500/20">
                                    Lưu thay đổi
                                </button>
                                <button @click="isPanelOpen = false"
                                    class="flex-1 bg-slate-800 text-slate-300 border border-slate-700 py-3.5 rounded-xl font-bold hover:bg-slate-700 transition-all">
                                    Hủy bỏ
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
