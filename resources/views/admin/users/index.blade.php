@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="space-y-6 pb-12"
        x-data='{
    {{-- 1. Khởi tạo trạng thái --}}
    allUsers: @json($users),
    filteredUsers: [],
    paginatedUsers: [],
    selectedUsers: [],
    selectedUser: null,
    isPanelOpen: false,
    isEditPanelOpen: false,
    isEditMode: false,
    filters: { search: "", status: "all", role: "all", dateRange: "all" },
    currentPage: 1,
    itemsPerPage: 10,

    {{-- 2. Khởi tạo và Watchers --}}
    init() {
        this.applyFilters();
        this.$watch("filters", () => {
            this.currentPage = 1;
            this.applyFilters();
        }, { deep: true });
        this.$watch("currentPage", () => this.applyPagination());
    },

    {{-- 3. Logic Lọc và Phân trang --}}
    applyFilters() {
        this.filteredUsers = this.allUsers.filter(user => {
            const search = this.filters.search.toLowerCase();
            const matchesSearch = search === "" ||
                user.name.toLowerCase().includes(search) ||
                user.email.toLowerCase().includes(search);
            const matchesStatus = this.filters.status === "all" || user.status === this.filters.status;
            const matchesRole = this.filters.role === "all" || user.role === this.filters.role;
            return matchesSearch && matchesStatus && matchesRole;
        });
        this.applyPagination();
    },

    applyPagination() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        this.paginatedUsers = this.filteredUsers.slice(start, start + this.itemsPerPage);
        this.selectedUsers = [];
    },

    {{-- 4. Thao tác chọn --}}
    toggleSelectAll() {
        if (this.selectedUsers.length === this.paginatedUsers.length) {
            this.selectedUsers = [];
        } else {
            this.selectedUsers = this.paginatedUsers.map(u => u.id);
        }
    },

    {{-- 5. Xem chi tiết --}}
    viewUser(user) {
        {{-- Copy sâu để tránh tham chiếu trực tiếp khi đang sửa --}}
        this.selectedUser = JSON.parse(JSON.stringify(user));
        this.isEditMode = false;
        this.isEditPanelOpen = false;
        this.isPanelOpen = true;
    },

    {{-- 6. Khóa/Mở khóa tài khoản --}}
    async toggleUserStatus(user) {
        if (!user) return;
        const action = user.status === "active" ? "khóa" : "mở khóa";
        if (!confirm("Bạn có chắc muốn " + action + " tài khoản này?")) return;

        const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
        try {
            const response = await fetch("/admin/users/" + user.id + "/toggle-status", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            });
            const result = await response.json();
            if (result.success) {
                {{-- Cập nhật trực tiếp vào mảng dữ liệu --}}
                this.allUsers = this.allUsers.map(u => u.id === user.id ? {...u, status: result.status} : u);
                if (this.selectedUser && this.selectedUser.id === user.id) {
                    this.selectedUser.status = result.status;
                }
                this.applyFilters();
                this.$dispatch("notify", { message: result.message, type: "success" });
            } else {
                this.$dispatch("notify", { message: result.message, type: "error" });
            }
        } catch (error) {
            this.$dispatch("notify", { message: "Lỗi kết nối máy chủ", type: "error" });
        }
    },

    {{-- 7. Đặt lại mật khẩu --}}
    async resetUserPassword(user) {
        if (!user || !confirm("Đặt lại mật khẩu cho " + user.name + " về 123456?")) return;

        const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");
        try {
            const response = await fetch("/admin/users/" + user.id + "/reset-password", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                }
            });
            const result = await response.json();
            if (result.success) {
                this.$dispatch("notify", { message: result.message, type: "success" });
            } else {
                this.$dispatch("notify", { message: "Không thể thực hiện", type: "error" });
            }
        } catch (error) {
            this.$dispatch("notify", { message: "Lỗi hệ thống", type: "error" });
        }
    },

    {{-- 8. Cập nhật thông tin thành viên --}}
    async updateUser() {
        if (!this.selectedUser) return;
        const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");

        try {
            const response = await fetch("/admin/users/" + this.selectedUser.id, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(this.selectedUser)
            });

            const result = await response.json();

            if (result.success) {
                const updatedData = result.user;

                {{-- Cập nhật danh sách tổng (Dùng spread để merge dữ liệu cũ + mới) --}}
                this.allUsers = this.allUsers.map(u => {
                    if (u.id === updatedData.id) {
                        return { ...u, ...updatedData };
                    }
                    return u;
                });

                {{-- Cập nhật Panel chi tiết đang mở --}}
                this.selectedUser = { ...this.selectedUser, ...updatedData };

                {{-- Re-render giao diện --}}
                this.applyFilters();

                {{-- Đóng panel chỉnh sửa --}}
                this.isEditPanelOpen = false;
                this.isEditMode = false;

                this.$dispatch("notify", { message: result.message, type: "success" });
            } else {
                this.$dispatch("notify", { message: result.message, type: "error" });
            }
        } catch (error) {
            this.$dispatch("notify", { message: "Lỗi hệ thống khi lưu", type: "error" });
        }
    },

    async deleteUser(user) {
        if (!user || !confirm("Bạn có chắc chắn muốn xóa người dùng \"" + user.name + "\"? Hành động này không thể hoàn tác!")) return;

        const token = document.querySelector("meta[name=csrf-token]").getAttribute("content");

        try {
            const response = await fetch("/admin/users/" + user.id, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                }
            });

            const result = await response.json();

            if (result.success) {
                // Remove user from data and refresh view
                this.allUsers = this.allUsers.filter(u => u.id !== user.id);
                this.applyFilters();
                this.isPanelOpen = false;
                this.$dispatch("notify", { message: result.message, type: "success" });
            } else {
                this.$dispatch("notify", { message: result.message, type: "error" });
            }
        } catch (error) {
            this.$dispatch("notify", { message: "Lỗi hệ thống khi xóa", type: "error" });
        }
    },

}'>

        <x-admin.users.user-header :stats="$stats" />
        <x-admin.users.user-filters />
        <x-admin.users.user-table />
        <x-admin.common.pagination />
        <x-admin.users.user-details />
        <x-admin.users.member-edit-panel />

    </div>

    {{-- Component Thông báo Toast (Đã tối ưu hiệu ứng) --}}
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
