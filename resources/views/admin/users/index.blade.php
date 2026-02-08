@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="space-y-6 pb-12"
        x-data='{
            {{-- 1. KHỞI TẠO DỮ LIỆU --}}
            allUsers: @json($users),
            filteredUsers: [],
            paginatedUsers: [],
            selectedUsers: [],
            selectedUser: null,
            isPanelOpen: false,
            isEditPanelOpen: false,
            isEditMode: false,

            {{-- Nhận filters từ Backend --}}
            filters: @json($filters),

            currentPage: 1,
            itemsPerPage: 10,
            searchTimeout: null,

            {{-- 2. KHỞI TẠO & WATCHERS --}}
            init() {
                {{-- FIX LỖI "NULL": Nếu search là null hoặc "null", ép về chuỗi rỗng ngay --}}
                if (!this.filters.search || this.filters.search === "null") {
                    this.filters.search = "";
                }

                this.applyFilters();

                {{-- Theo dõi thay đổi Dropdown để reload trang --}}
                this.$watch("filters.status", () => this.triggerBackendFilter());
                this.$watch("filters.role", () => this.triggerBackendFilter());
                this.$watch("filters.dateRange", () => this.triggerBackendFilter());

                {{-- Theo dõi tìm kiếm để lọc tại chỗ (Debounce) --}}
                this.$watch("filters.search", () => {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.currentPage = 1;
                        this.applyFilters();
                    }, 300);
                });

                this.$watch("currentPage", () => this.applyPagination());
            },

            {{-- 3. LOGIC BACKEND (Load lại trang) --}}
            triggerBackendFilter() {
                const params = new URLSearchParams(this.filters).toString();
                window.location.href = "{{ route('admin.users.index') }}?" + params;
            },

            {{-- 4. LOGIC FRONTEND (Lọc tìm kiếm an toàn) --}}
            applyFilters() {
                this.filteredUsers = this.allUsers.filter(user => {
                    {{-- Chuyển từ khóa tìm kiếm về chữ thường, xử lý null --}}
                    const search = (this.filters.search || "").toString().toLowerCase();

                    {{-- Nếu không tìm gì thì hiện hết --}}
                    if (search === "") return true;

                    {{-- Xử lý dữ liệu user an toàn trước khi so sánh --}}
                    const name = (user.name || "").toString().toLowerCase();
                    const email = (user.email || "").toString().toLowerCase();
                    const phone = (user.phone || "").toString().toLowerCase();

                    return name.includes(search) ||
                           email.includes(search) ||
                           phone.includes(search);
                });

                {{-- Phân trang lại sau khi lọc --}}
                this.applyPagination();
            },

            applyPagination() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                this.paginatedUsers = this.filteredUsers.slice(start, start + this.itemsPerPage);
                this.selectedUsers = [];
            },

            {{-- 5. CÁC HÀM TIỆN ÍCH --}}
            hasActiveFilters() {
                return this.filters.search !== "" ||
                    this.filters.status !== "all" ||
                    this.filters.role !== "all" ||
                    this.filters.dateRange !== "all";
            },

            clearFilters() {
                this.filters.search = "";
                this.filters.status = "all";
                this.filters.role = "all";
                this.filters.dateRange = "all";
                this.triggerBackendFilter();
            },

            {{-- 6. CÁC HÀM THAO TÁC (Chọn, Xem, Sửa, Xóa) --}}
            toggleSelectAll() {
                if (this.selectedUsers.length === this.paginatedUsers.length && this.paginatedUsers.length > 0) {
                    this.selectedUsers = [];
                } else {
                    this.selectedUsers = this.paginatedUsers.map(u => u.id);
                }
            },

            viewUser(user) {
                this.selectedUser = JSON.parse(JSON.stringify(user));
                this.isEditMode = false;
                this.isEditPanelOpen = false;
                this.isPanelOpen = true;
            },

            async toggleUserStatus(user) {
                if (!user) return;
                const action = user.status === "active" ? "khóa" : "mở khóa";
                if (!confirm("Bạn có chắc muốn " + action + " tài khoản này?")) return;

                const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
                try {
                    const response = await fetch("/admin/users/" + user.id + "/toggle-status", {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": token, "Content-Type": "application/json", "Accept": "application/json" }
                    });
                    const result = await response.json();
                    if (result.success) {
                        this.allUsers = this.allUsers.map(u => u.id === user.id ? {...u, status: result.status} : u);
                        if (this.selectedUser && this.selectedUser.id === user.id) this.selectedUser.status = result.status;
                        this.applyFilters();
                        this.$dispatch("notify", { message: result.message, type: "success" });
                    }
                } catch (error) { this.$dispatch("notify", { message: "Lỗi kết nối", type: "error" }); }
            },

            async resetUserPassword(user) {
                if (!user || !confirm("Đặt lại mật khẩu cho " + user.name + " về 123456?")) return;
                const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
                try {
                    const response = await fetch("/admin/users/" + user.id + "/reset-password", {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": token, "Accept": "application/json" }
                    });
                    const result = await response.json();
                    if (result.success) this.$dispatch("notify", { message: result.message, type: "success" });
                } catch (error) { this.$dispatch("notify", { message: "Lỗi hệ thống", type: "error" }); }
            },

            async updateUser() {
                if (!this.selectedUser) return;
                const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
                try {
                    const response = await fetch("/admin/users/" + this.selectedUser.id, {
                        method: "PUT",
                        headers: { "X-CSRF-TOKEN": token, "Content-Type": "application/json", "Accept": "application/json" },
                        body: JSON.stringify(this.selectedUser)
                    });
                    const result = await response.json();
                    if (result.success) {
                        const updatedData = result.user;
                        this.allUsers = this.allUsers.map(u => u.id === updatedData.id ? { ...u, ...updatedData } : u);
                        this.selectedUser = { ...this.selectedUser, ...updatedData };
                        this.applyFilters();
                        this.isEditPanelOpen = false;
                        this.$dispatch("notify", { message: result.message, type: "success" });
                    }
                } catch (error) { this.$dispatch("notify", { message: "Lỗi khi lưu", type: "error" }); }
            },

            async deleteUser(user) {
                if (!user || !confirm("Bạn có chắc chắn muốn xóa \"" + user.name + "\"?")) return;
                const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
                try {
                    const response = await fetch("/admin/users/" + user.id, {
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": token, "Accept": "application/json" }
                    });
                    const result = await response.json();
                    if (result.success) {
                        this.allUsers = this.allUsers.filter(u => u.id !== user.id);
                        this.applyFilters();
                        this.isPanelOpen = false;
                        this.$dispatch("notify", { message: result.message, type: "success" });
                    }
                } catch (error) { this.$dispatch("notify", { message: "Lỗi khi xóa", type: "error" }); }
            },

            {{-- Bulk Delete --}}
            async deleteSelectedUsers() {
                if (this.selectedUsers.length === 0) return;
                if (!confirm("Xóa " + this.selectedUsers.length + " người dùng đã chọn?")) return;
                {{-- Logic gọi API bulk delete ở đây... --}}
            }
        }'>

        <x-admin.users.user-header :stats="$stats" />
        <x-admin.users.user-filters />
        <x-admin.users.user-table />
        <x-admin.common.pagination />
        <x-admin.users.user-details />
        <x-admin.users.member-edit-panel />

    </div>

    {{-- Component Thông báo Toast --}}
    <div x-data="{
        messages: [],
        remove(id) {
            this.messages = this.messages.filter(m => m.id !== id);
        }
    }"
        @notify.window="
            const id = Date.now();
            messages.push({ id, message: $event.detail.message, type: $event.detail.type });
            setTimeout(() => remove(id), 4000);
        "
        class="fixed bottom-6 right-6 z-[999] flex flex-col gap-3 w-full max-w-xs">

        <template x-for="msg in messages" :key="msg.id">
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-10"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="relative overflow-hidden rounded-2xl border backdrop-blur-xl p-4 shadow-2xl"
                :class="{
                    'bg-slate-900/90 border-emerald-500/50 text-emerald-400': msg.type === 'success',
                    'bg-slate-900/90 border-red-500/50 text-red-400': msg.type === 'error'
                }">

                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <template x-if="msg.type === 'success'">
                            <i class="fas fa-check-circle text-lg"></i>
                        </template>
                        <template x-if="msg.type === 'error'">
                            <i class="fas fa-exclamation-triangle text-lg"></i>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white"
                            x-text="msg.type === 'success' ? 'Hoàn tất' : 'Thông báo lỗi'"></p>
                        <p class="text-xs mt-0.5 text-slate-300 leading-relaxed" x-text="msg.message"></p>
                    </div>
                    <button @click="remove(msg.id)" class="text-slate-500 hover:text-white transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <div class="absolute bottom-0 left-0 h-1 bg-current opacity-20" x-init="setTimeout(() => $el.style.width = '100%', 50)"
                    style="width: 0%; transition: width 4s linear;">
                </div>
            </div>
        </template>
    </div>
@endsection
