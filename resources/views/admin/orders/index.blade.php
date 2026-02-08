@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
    {{-- Container chính chứa toàn bộ logic Alpine.js --}}
    <div class="space-y-6 pb-20" x-data="{
        {{-- 1. Nhận dữ liệu đã được Lọc từ Controller --}}
        allOrders: {{ $allOrders->toJson() }},

            {{-- 2. Biến cho phân trang & hiển thị --}}
        paginatedProducts: [],
            selectedOrders: [],
            selectedOrder: null,

            isPanelOpen: false,
            showFilters: false,

            currentPage: 1,
            itemsPerPage: 10,
            totalPages: 0,
            totalItems: 0,

            {{-- 3. Khởi tạo bộ lọc với giá trị từ URL (Server-side persistence) --}}
        filters: {
                search: '{{ request('search') }}',
                orderStatus: '{{ request('status', 'all') }}',
                paymentStatus: '{{ request('payment_status', 'all') }}',
                shippingStatus: 'all', // Chưa có logic backend
                paymentMethod: 'all' // Chưa có logic backend
            },

            init() {
                {{-- Vì dữ liệu đã được lọc ở Server, ta chỉ cần phân trang để hiển thị --}}
                this.totalItems = this.allOrders.length;
                this.applyPagination();

                {{-- Khi đổi trang thì tính lại phân trang --}}
                this.$watch('currentPage', () => this.applyPagination());
                this.$watch('itemsPerPage', () => {
                    this.currentPage = 1;
                    this.applyPagination();
                });
            },

            {{-- Hàm phân trang (Cắt mảng JSON để hiển thị từng trang) --}}
        applyPagination() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                this.paginatedProducts = this.allOrders.slice(start, start + this.itemsPerPage);
                this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
            },

            {{-- Logic xem chi tiết (Giữ nguyên) --}}
        viewOrder(order) {
                this.selectedOrder = JSON.parse(JSON.stringify(order));
                this.isPanelOpen = true;
            },

            {{-- Logic chọn tất cả (Giữ nguyên) --}}
        toggleSelectAll() {
                if (this.selectedOrders.length === this.paginatedProducts.length) {
                    this.selectedOrders = [];
                } else {
                    this.selectedOrders = this.paginatedProducts.map(o => o.id);
                }
            },

            {{-- Xóa bộ lọc: Chuyển hướng về trang gốc --}}
        clearFilters() {
                window.location.href = '{{ route('admin.orders.index') }}';
            },

            {{-- LOGIC XUẤT EXCEL MỚI --}}
        exportOrders() {
                // 1. Lấy URL gốc
                let url = '{{ route('admin.orders.export') }}';

                // 2. Tạo query string từ các bộ lọc hiện tại
                const params = new URLSearchParams();

                if (this.filters.search) params.append('search', this.filters.search);
                if (this.filters.orderStatus !== 'all') params.append('status', this.filters.orderStatus);
                if (this.filters.paymentStatus !== 'all') params.append('payment_status', this.filters.paymentStatus);

                // 3. Chuyển hướng trình duyệt để tải file
                window.location.href = url + '?' + params.toString();
            },

            createNewOrder() { alert('Chức năng tạo đơn mới đang phát triển...'); }
    }">

        {{-- 1. Header: Hiển thị tổng số đơn từ biến Collection --}}
        <x-admin.orders.orders-header :totalOrders="$allOrders->count()" />

        {{-- 2. Thống kê nhanh: Truyền biến $stats đã tính ở Controller vào đây --}}
        {{-- QUAN TRỌNG: Đây là chỗ sửa để hiển thị 6 ô thống kê đúng --}}
        <x-admin.orders.status-overview :stats="$stats" />

        {{-- 3. Bộ lọc nâng cao --}}
        <x-admin.orders.orders-filters />

        {{-- 4. Bảng danh sách đơn hàng --}}
        <x-admin.orders.orders-table />

        {{-- 5. Phân trang --}}
        <x-admin.pagination />

        {{-- 6. Panel chi tiết (Slide-over) --}}
        <x-admin.orders.order-details-panel />

    </div>
@endsection
