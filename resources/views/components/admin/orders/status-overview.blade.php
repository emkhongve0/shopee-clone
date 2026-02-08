@props(['stats'])

@php
    // Lấy status hiện tại trên URL để biết ô nào đang active
    $current = request('status', 'all');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">

    {{-- 1. Tổng đơn hàng --}}
    <x-admin.orders.status-card title="Tổng đơn hàng" :count="$stats['total']" icon="fa-box" bgColor="bg-slate-800"
        iconColor="text-slate-400" {{-- Thay vì @click, ta truyền Link --}} href="{{ route('admin.orders.index') }}" {{-- Truyền biến active để đổi màu viền --}}
        :active="$current === 'all'" />

    {{-- 2. Chờ xử lý --}}
    <x-admin.orders.status-card title="Chờ xử lý" :count="$stats['pending']" icon="fa-clock" bgColor="bg-orange-500/10"
        iconColor="text-orange-500" href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
        :active="$current === 'pending'" />

    {{-- 3. Đang xử lý --}}
    <x-admin.orders.status-card title="Đang xử lý" :count="$stats['processing']" icon="fa-sync fa-spin" bgColor="bg-blue-500/10"
        iconColor="text-blue-500" href="{{ route('admin.orders.index', ['status' => 'processing']) }}"
        :active="$current === 'processing'" />

    {{-- 4. Đang giao --}}
    <x-admin.orders.status-card title="Đang giao" :count="$stats['shipping']" icon="fa-truck" bgColor="bg-indigo-500/10"
        iconColor="text-indigo-500" {{-- SỬA LẠI DÒNG NÀY: 'shipping' -> 'shipped' --}}
        href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" :active="$current === 'shipped'" />

    {{-- 5. Hoàn thành --}}
    <x-admin.orders.status-card title="Hoàn thành" :count="$stats['completed']" icon="fa-check-circle" bgColor="bg-emerald-500/10"
        iconColor="text-emerald-500" href="{{ route('admin.orders.index', ['status' => 'completed']) }}"
        :active="$current === 'completed'" />

    {{-- 6. Đã hủy --}}
    <x-admin.orders.status-card title="Đã hủy" :count="$stats['cancelled']" icon="fa-times-circle" bgColor="bg-red-500/10"
        iconColor="text-red-500" {{-- SỬA LẠI DÒNG NÀY: Thử đổi thành 'canceled' nếu 'cancelled' không chạy --}}
        href="{{ route('admin.orders.index', ['status' => 'canceled']) }}" :active="$current === 'canceled'" />
</div>
