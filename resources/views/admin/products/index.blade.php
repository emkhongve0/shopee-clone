@extends('layouts.admin')

@section('title', 'Quản lý kho hàng')

@section('content')

    <div class="space-y-6" x-data="{
        {{-- 1. DỮ LIỆU GỐC --}}
        allProducts: {{ json_encode($allProducts) }},
            categoryTabs: {{ json_encode($categoryTabs) }},

            {{-- 2. TRẠNG THÁI HIỂN THỊ --}}
        filteredProducts: [],
            paginatedProducts: [],
            selectedProducts: [],
            selectedProduct: null,
            isPanelOpen: false,
            isLoading: false, {{-- Trạng thái chờ xử lý --}}
        viewMode: 'table',
            activeCategory: 'all',
            currentPage: 1,
            itemsPerPage: 12,

            {{-- 3. BỘ LỌC --}}
        filters: { search: '', priceRange: 'all', stockStatus: 'all', productStatus: 'all', rating: 'all' },

            {{-- 4. THAO TÁC HÀNG LOẠT --}}
        showBulkModal: false,
            bulkAction: '',

            init() {
                this.filteredProducts = this.allProducts;
                this.applyFilters();

                this.$watch('filters', () => {
                    this.currentPage = 1;
                    this.applyFilters();
                }, { deep: true });

                this.$watch('activeCategory', () => {
                    this.currentPage = 1;
                    this.applyFilters();
                });

                this.$watch('viewMode', () => { this.applyPagination(); });
            },

            handleBulkTrigger(actionType) {
                if (this.selectedProducts.length === 0) {
                    alert('Vui lòng chọn ít nhất một sản phẩm để thực hiện thao tác này!');
                    return;
                }
                this.bulkAction = actionType;
                this.showBulkModal = true;
            },

            applyFilters() {
                this.filteredProducts = this.allProducts.filter(p => {
                    const searchKeyword = this.filters.search.toLowerCase();
                    const matchesSearch = searchKeyword === '' ||
                        p.name.toLowerCase().includes(searchKeyword) ||
                        p.sku.toLowerCase().includes(searchKeyword);

                    const productCategoryName = p.category || 'Chưa phân loại';
                    const productCategorySlug = p.category_slug || this.slugify(productCategoryName);
                    const matchesCategory = this.activeCategory === 'all' || productCategorySlug === this.activeCategory;

                    // Logic lọc trạng thái (Đã thêm draft và sửa lỗi cú pháp)
                    const matchesStatus = this.filters.productStatus === 'all' ||
                        (this.filters.productStatus === 'active' && (p.status === 'active' || p.status === 1)) ||
                        (this.filters.productStatus === 'hidden' && (p.status === 'hidden' || p.status === 0)) ||
                        (this.filters.productStatus === 'draft' && (p.status === 'draft' || p.status === 2));

                    return matchesSearch &&
                        matchesCategory &&
                        this.checkPrice(p.price) &&
                        this.checkStock(p.stock) &&
                        matchesStatus && // Thêm dấu nối ở đây
                        this.checkRating(p.rating);
                });
                this.applyPagination();
            },

            slugify(text) {
                if (!text) return 'chua-phan-loai';
                return text.toString().toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') {{-- Khử dấu tiếng Việt --}}
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            },

            checkPrice(price) {
                if (this.filters.priceRange === 'all') return true;
                if (this.filters.priceRange === '0-500000') return price <= 500000;
                if (this.filters.priceRange === '500000-2000000') return price > 500000 && price <= 2000000;
                if (this.filters.priceRange === '2000000+') return price > 2000000;
                return true;
            },

            checkStock(stock) {
                if (this.filters.stockStatus === 'all') return true;
                if (this.filters.stockStatus === 'in-stock') return stock >= 20;
                if (this.filters.stockStatus === 'low-stock') return stock > 0 && stock < 20;
                if (this.filters.stockStatus === 'out-of-stock') return stock === 0;
                return true;
            },

            checkRating(rating) {
                if (this.filters.rating === 'all') return true;
                return rating >= parseFloat(this.filters.rating.replace('+', ''));
            },

            applyPagination() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                this.paginatedProducts = this.filteredProducts.slice(start, start + this.itemsPerPage);
            },

            viewProduct(product) {
                if (!product) return;
                this.selectedProduct = JSON.parse(JSON.stringify(product));
                this.isPanelOpen = true;
            },
            openAddModal() {
                this.selectedProduct = {
                    id: null, // ID null để đánh dấu đây là tạo mới
                    name: '',
                    sku: '',
                    category_id: '',
                    price: 0,
                    stock: 0,
                    status: 'active',
                    description: '',
                    image: 'https://placehold.co/400x400?text=San+pham+moi'
                };
                this.isPanelOpen = true;
            },

            {{-- ĐÃ KẾT NỐI BACKEND --}}
        async saveProduct(product) {
            this.isLoading = true;
            const isNew = !product.id;
            const url = isNew ? '/admin/products' : `/admin/products/${product.id}`;

            // 1. Khởi tạo FormData
            const formData = new FormData();

            // 2. Đưa thông tin sản phẩm vào FormData
            Object.keys(product).forEach(key => {
                /**
                 * FIX LỖI VALIDATION:
                 * Chúng ta loại bỏ 'image' (chuỗi URL cũ) và 'imageFile' (biến tạm).
                 * Chỉ những trường dữ liệu thực sự cần thiết cho Database mới được gửi.
                 */
                if (key !== 'image' && key !== 'imageFile' && product[key] !== null) {
                    formData.append(key, product[key]);
                }
            });

            // 3. CHỈ gửi file ảnh nếu người dùng có chọn ảnh mới
            // Chúng ta đặt tên key là 'image' để khớp với Validation trong Controller
            if (product.imageFile) {
                formData.append('image', product.imageFile);
            }

            // 4. Logic spoofing method của Laravel cho yêu cầu PUT
            if (!isNew) {
                formData.append('_method', 'PUT');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST', // Giữ nguyên POST cho FormData
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // --- CẬP NHẬT GIAO DIỆN ---
                    if (isNew) {
                        this.allProducts.unshift(result.product);
                    } else {
                        const index = this.allProducts.findIndex(p => p.id === product.id);
                        if (index !== -1) {
                            this.allProducts[index] = result.product;
                        }
                    }
                    this.applyFilters();
                    this.isPanelOpen = false;

                    // Giải phóng bộ nhớ và xóa file tạm
                    product.imageFile = null;
                } else {
                    // Xử lý khi có lỗi (ví dụ lỗi Validation 422)
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        alert('Dữ liệu không hợp lệ:\n' + errorMessages);
                    } else {
                        alert(result.message || 'Có lỗi xảy ra khi lưu.');
                    }
                }
            } catch (e) {
                console.error(e);
                alert('Lỗi kết nối hệ thống');
            } finally {
                this.isLoading = false;
            }
        }
    }" @filter-changed.window="filters = $event.detail"
        @category-changed.window="activeCategory = $event.detail" @view-mode-changed.window="viewMode = $event.detail"
        @open-bulk-modal.window="handleBulkTrigger($event.detail)" @open-add-modal.window="openAddModal()" x-cloak>

        <x-admin.products.products-header :categories="$categories" :totalProducts="$totalProducts" />
        <x-admin.common.category-tabs :categories="$categoryTabs" active="all" />
        <x-admin.products.products-filters />

        <div x-show="viewMode === 'grid'" x-transition:enter="duration-300">
            <x-admin.products.products-grid />
        </div>

        <div x-show="viewMode === 'table'" x-transition:enter="duration-300">
            <x-admin.products.products-table />
        </div>

        <x-admin.common.pagination />

        {{-- CHI TIẾT SẢN PHẨM --}}
        <x-admin.products.product-details-panel :categories="$categories" />

        {{-- MODAL THAO TÁC HÀNG LOẠT --}}
        <div x-show="showBulkModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="showBulkModal = false">
            </div>

            <div
                class="relative w-full max-w-md bg-[#1e293b] border border-slate-700 rounded-2xl shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-layer-group text-blue-500 text-sm"></i>
                        <span
                            x-text="
                            bulkAction === 'delete' ? 'Xác nhận xóa hàng loạt' :
                            (bulkAction === 'update_status' ? 'Cập nhật trạng thái' :
                            (bulkAction === 'update_category' ? 'Gán danh mục nhanh' : 'Điều chỉnh giá bán'))
                        "></span>
                    </h3>
                    <button @click="showBulkModal = false" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.products.bulk_action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" :value="bulkAction">
                    <template x-for="id in selectedProducts" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>

                    <div class="space-y-5">
                        <div
                            class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center gap-2 text-xs text-blue-400">
                            <i class="fas fa-info-circle"></i>
                            <span>Đã chọn <strong class="text-white" x-text="selectedProducts.length"></strong> sản
                                phẩm.</span>
                        </div>

                        {{-- Input fields cho Modal (Giữ nguyên logic của bạn) --}}
                        <div x-show="bulkAction === 'update_status'">
                            <label class="block text-slate-400 text-[10px] font-bold uppercase mb-2">Trạng thái mới</label>
                            <select name="value"
                                class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="1">Hiển thị (Active)</option>
                                <option value="0">Ẩn (Hidden)</option>
                            </select>
                        </div>

                        <div x-show="bulkAction === 'update_category'">
                            <label class="block text-slate-400 text-[10px] font-bold uppercase mb-2">Danh mục đích</label>
                            <select name="category_id"
                                class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 outline-none">
                                <option value="" disabled selected>-- Chọn danh mục --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="bulkAction === 'update_price'" class="space-y-4">
                            <div>
                                <label class="block text-slate-400 text-[10px] font-bold uppercase mb-2">Loại thay
                                    đổi</label>
                                <select name="price_type"
                                    class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 outline-none">
                                    <option value="fixed_set">Thiết lập giá mới</option>
                                    <option value="fixed">Cộng/Trừ tiền (VND)</option>
                                    <option value="percent">% (Phần trăm)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-slate-400 text-[10px] font-bold uppercase mb-2">Giá trị</label>
                                <input type="number" name="price_value" placeholder="Nhập giá trị..."
                                    class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 outline-none">
                            </div>
                        </div>

                        <div x-show="bulkAction === 'delete'"
                            class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex gap-3">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                            <p class="text-sm text-red-400"><strong>Cảnh báo:</strong> Hành động này không thể hoàn tác.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <button type="button" @click="showBulkModal = false"
                            class="px-5 py-2 text-slate-400 hover:text-white font-medium">Hủy bỏ</button>
                        <button type="submit" :class="bulkAction === 'delete' ? 'bg-red-600' : 'bg-blue-600'"
                            class="px-6 py-2.5 text-white rounded-xl font-bold shadow-lg transition-all active:scale-95 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Xác nhận
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
