@extends('layouts.admin')

@section('title', 'Tổng quan hệ thống')

@section('content')
    <div class="space-y-6 pb-12">
        {{-- Hàng 1: KPI Cards - Đã thêm phòng thủ chống lỗi Undefined Key --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Card 1: Doanh thu --}}
            <x-admin.dashboard.kpi-card title="Total Revenue" :value="number_format($kpis['total_revenue']['value'] ?? 0) . '₫'" :change="$kpis['total_revenue']['change'] ?? '0'" icon="fa-dollar-sign"
                iconBgColor="bg-blue-500/10" iconColor="text-blue-500" />

            {{-- Card 2: Đơn hàng --}}
            <x-admin.dashboard.kpi-card title="Total Orders" :value="number_format($kpis['total_orders']['value'] ?? 0)" :change="$kpis['total_orders']['change'] ?? '0'" icon="fa-shopping-cart"
                iconBgColor="bg-emerald-500/10" iconColor="text-emerald-500" />

            {{-- Card 3: Khách hàng --}}
            <x-admin.dashboard.kpi-card title="Total Customers" :value="number_format($kpis['total_customers']['value'] ?? 0)" :change="$kpis['total_customers']['change'] ?? '0'" icon="fa-users"
                iconBgColor="bg-purple-500/10" iconColor="text-purple-500" />

            {{-- Card 4: Tỷ lệ chuyển đổi --}}
            <x-admin.dashboard.kpi-card title="Conversion Rate" :value="$kpis['conversion_rate']['value'] ?? '0%'" :change="$kpis['conversion_rate']['change'] ?? '0'" icon="fa-chart-line"
                iconBgColor="bg-orange-500/10" iconColor="text-orange-500" />
        </div>

        {{-- Hàng 2: Revenue Analytics --}}
        <div class="w-full">
            <x-admin.dashboard.revenue-chart :daily="$revenue['daily'] ?? []" :weekly="$revenue['weekly'] ?? []" :monthly="$revenue['monthly'] ?? []" />
        </div>

        {{-- Hàng 3: Biểu đồ trạng thái & Danh mục --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-admin.dashboard.order-status-chart :data="$chartData ?? []" />
            <x-admin.dashboard.category-sales-chart :data="$categorySales ?? []" />
        </div>

        {{-- Hàng 4: Sản phẩm & Kho --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-admin.dashboard.top-selling-products :products="$topProducts ?? []" />
            <x-admin.dashboard.low-stock-alert :items="$lowStock ?? []" />
        </div>

        {{-- Hàng 5: Recent Orders --}}
        <div class="w-full">
            <x-admin.dashboard.recent-orders-table :orders="$recentOrders ?? collect([])" />
        </div>
    </div>
@endsection
