@extends('layouts.admin')

@section('title', 'Quản lý kho hàng')

@section('content')
    @php
        // GIỮ NGUYÊN LOGIC 1: Tạo dữ liệu mẫu (Mock Data) chuẩn PHP
        $categories = [
            'Electronics',
            'Fashion',
            'Home & Living',
            'Beauty',
            'Sports',
            'Books',
            'Games',
            'Cameras',
            'Audio',
            'Computers',
        ];
        $statuses = ['active', 'draft', 'hidden'];
        $productNames = [
            'Tai nghe Wireless Premium',
            'Smart TV 4K Ultra HD',
            'Laptop Gaming Pro',
            'Máy ảnh DSLR Professional',
            'Đồng hồ Fitness Thông minh',
            'Bàn phím Cơ Gaming',
            'Loa Bluetooth Di động',
            'Chuột Không dây',
            'Hub Chuyển đổi USB-C',
            'Ổ cứng SSD 1TB',
            'Tai nghe Chống ồn',
            'Đèn bàn LED',
            'Ghế Công sở Ergonomic',
            'Thảm tập Yoga Premium',
            'Giày Chạy bộ',
            'Balo Laptop',
            'Bình nước Thép không gỉ',
            'Ốp lưng Bảo vệ',
        ];
        $images = [
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400',
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=400',
            'https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=400',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400',
        ];

        $allProducts = collect($productNames)->map(function ($name, $index) use ($categories, $statuses, $images) {
            return [
                'id' => 'product-' . ($index + 1),
                'name' => $name,
                'image' => $images[$index % count($images)],
                'category' => $categories[$index % count($categories)],
                'price' => round(rand(50, 1000) + rand(0, 99) / 100, 2),
                'stock' => rand(0, 150),
                'status' => $statuses[$index % count($statuses)],
                'rating' => round(rand(30, 50) / 10, 1),
                'reviews' => rand(10, 500),
                'sku' => 'SKU-' . str_pad($index + 1000, 5, '0', STR_PAD_LEFT),
                'createdAt' => now()->subDays(rand(1, 365))->format('d/m/Y'),
                'updatedAt' => now()->format('d/m/Y'),
            ];
        });

        // GIỮ NGUYÊN LOGIC 2: Tính toán danh mục (Tabs)
        $categoryTabs = $allProducts
            ->groupBy('category')
            ->map(function ($items, $name) {
                return ['id' => strtolower(str_replace(' ', '-', $name)), 'name' => $name, 'count' => $items->count()];
            })
            ->values()
            ->toArray();
        array_unshift($categoryTabs, ['id' => 'all', 'name' => 'Tất cả sản phẩm', 'count' => $allProducts->count()]);
    @endphp

    {{-- GIỮ NGUYÊN LOGIC 3: Toàn bộ State và Methods của Alpine.js --}}
    <div class="space-y-6" x-data="{
        allProducts: {{ $allProducts->toJson() }},
        filteredProducts: [],
        paginatedProducts: [],
        selectedProducts: [],
        selectedProduct: null,
        isPanelOpen: false,
        viewMode: 'grid',
        activeCategory: 'all',
        currentPage: 1,
        itemsPerPage: 12,
        filters: { search: '', priceRange: 'all', stockStatus: 'all', productStatus: 'all', rating: 'all' },

        init() {
            this.applyFilters();
            this.$watch('filters', () => { this.currentPage = 1;
                this.applyFilters(); }, { deep: true });
            this.$watch('activeCategory', () => { this.currentPage = 1;
                this.applyFilters(); });
            this.$watch('viewMode', () => { this.applyPagination(); });
        },

        applyFilters() {
            this.filteredProducts = this.allProducts.filter(p => {
                const matchesSearch = this.filters.search === '' ||
                    p.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                    p.sku.toLowerCase().includes(this.filters.search.toLowerCase());
                const matchesCategory = this.activeCategory === 'all' ||
                    p.category.toLowerCase().replace(/\s+/g, '-') === this.activeCategory;
                return matchesSearch && matchesCategory && this.checkPrice(p.price) &&
                    this.checkStock(p.stock) && (this.filters.productStatus === 'all' || p.status === this.filters.productStatus) &&
                    this.checkRating(p.rating);
            });
            this.applyPagination();
        },

        checkPrice(price) {
            if (this.filters.priceRange === 'all') return true;
            if (this.filters.priceRange === '0-50') return price <= 50;
            if (this.filters.priceRange === '50-100') return price > 50 && price <= 100;
            if (this.filters.priceRange === '100-500') return price > 100 && price <= 500;
            if (this.filters.priceRange === '500+') return price > 500;
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
            {{-- Đã sửa nhẹ lỗi paginatedUsers thành paginatedProducts để bảng nhận dữ liệu --}}
            this.paginatedProducts = this.filteredProducts.slice(start, start + this.itemsPerPage);
        },

        viewProduct(product) {
            this.selectedProduct = JSON.parse(JSON.stringify(product));
            this.isPanelOpen = true;
        },

        saveProduct(updatedProduct) {
            const index = this.allProducts.findIndex(p => p.id === updatedProduct.id);
            if (index !== -1) {
                this.allProducts[index] = updatedProduct;
                this.applyFilters();
            }
        }
    }" @filter-changed.window="filters = $event.detail"
        @category-changed.window="activeCategory = $event.detail" @view-mode-changed.window="viewMode = $event.detail">

        {{-- CẬP NHẬT 1: Đường dẫn folder products --}}
        <x-admin.products.products-header :totalProducts="$allProducts->count()" />

        {{-- CẬP NHẬT 2: Đường dẫn folder common --}}
        <x-admin.common.category-tabs :categories="$categoryTabs" active="all" />

        {{-- CẬP NHẬT 3: Đường dẫn folder products --}}
        <x-admin.products.products-filters />

        <div x-show="viewMode === 'grid'" x-transition:enter="duration-300" x-cloak>
            <x-admin.products.products-grid />
        </div>

        <div x-show="viewMode === 'table'" x-transition:enter="duration-300" x-cloak>
            {{-- QUAN TRỌNG: Thay Table cứng bằng linh kiện để fix lỗi menu bị chìm --}}
            <x-admin.products.products-table />
        </div>

        {{-- CẬP NHẬT 4: Đường dẫn folder common --}}
        <x-admin.common.pagination />

        {{-- CẬP NHẬT 5: Đường dẫn folder products --}}
        <x-admin.products.product-details-panel />

    </div>
@endsection
