@extends('layouts.admin')

@section('title', 'Quản lý khách hàng')

@section('content')
    @php
        // GIỮ NGUYÊN LOGIC GỐC: Dữ liệu mẫu (Mock Data)
        $names = [
            'John Williams',
            'Emma Thompson',
            'Michael Chen',
            'Sarah Johnson',
            'David Martinez',
            'Lisa Anderson',
            'James Wilson',
            'Maria Garcia',
            'Robert Taylor',
            'Jennifer Brown',
            'William Davis',
            'Jessica Miller',
            'Richard Moore',
            'Ashley Jackson',
            'Thomas White',
            'Amanda Harris',
            'Daniel Martin',
            'Stephanie Thompson',
            'Christopher Garcia',
            'Michelle Robinson',
        ];

        $roles = ['admin', 'staff', 'customer'];
        $statuses = ['active', 'inactive', 'banned'];

        $users = collect($names)->map(function ($name, $index) use ($roles, $statuses) {
            return [
                'id' => 'user-' . ($index + 1),
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'role' => $roles[$index % 3],
                'status' => $statuses[$index % 3],
                'orders' => rand(1, 50),
                'totalSpent' => '$' . number_format(rand(500, 10000)),
                'phone' => '(+84) ' . rand(100, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'createdAt' => now()->subDays(rand(1, 365))->format('d/m/Y'),
                'lastLogin' => '07/02/2026',
            ];
        });
    @endphp

    {{-- GIỮ NGUYÊN LOGIC GỐC: Alpine.js điều khiển trạng thái --}}
    <div class="space-y-6 pb-12" x-data="{
        allUsers: {{ $users->toJson() }},
        filteredUsers: [],
        paginatedUsers: [],
        selectedUsers: [],
        selectedUser: null,
        isPanelOpen: false,

        filters: { search: '', status: 'all', role: 'all', dateRange: 'all' },
        currentPage: 1,
        itemsPerPage: 10,

        init() {
            this.applyFilters();
            this.$watch('filters', () => {
                this.currentPage = 1;
                this.applyFilters();
            }, { deep: true });
            this.$watch('currentPage', () => this.applyPagination());
            this.$watch('itemsPerPage', () => {
                this.currentPage = 1;
                this.applyPagination();
            });
        },

        applyFilters() {
            this.filteredUsers = this.allUsers.filter(user => {
                const matchesSearch = this.filters.search === '' ||
                    user.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                    user.phone.includes(this.filters.search);
                const matchesStatus = this.filters.status === 'all' || user.status === this.filters.status;
                const matchesRole = this.filters.role === 'all' || user.role === this.filters.role;
                return matchesSearch && matchesStatus && matchesRole;
            });
            this.applyPagination();
        },

        applyPagination() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            this.paginatedUsers = this.filteredUsers.slice(start, start + this.itemsPerPage);
            this.selectedUsers = [];
        },

        toggleSelectAll() {
            if (this.selectedUsers.length === this.paginatedUsers.length) {
                this.selectedUsers = [];
            } else {
                this.selectedUsers = this.paginatedUsers.map(u => u.id);
            }
        },

        viewUser(user) {
            this.selectedUser = user;
            this.isPanelOpen = true;
        },

        clearFilters() {
            this.filters = { search: '', status: 'all', role: 'all', dateRange: 'all' };
        }
    }">

        {{-- ĐƯỜNG DẪN MỚI: Gọi từ thư mục components/admin/customers/ --}}
        <x-admin.customers.customers-header :totalUsers="$users->count()" />

        <x-admin.customers.customers-filters />

        {{-- Linh kiện Table (Nhớ fix overflow-visible trong file này) --}}
        <x-admin.customers.customers-table />

        {{-- Phân trang dùng chung nằm trong folder common --}}
        <x-admin.common.pagination />

        {{-- Panel chi tiết khách hàng --}}
        <x-admin.customers.customers-details />

    </div>
@endsection
