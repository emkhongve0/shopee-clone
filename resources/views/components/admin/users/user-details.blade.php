{{-- Linh kiện Panel chi tiết người dùng --}}
<div x-show="isPanelOpen" class="fixed inset-0 z-[100] overflow-hidden" x-cloak>
    {{-- Lớp nền mờ (Overlay) --}}
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="isPanelOpen = false"
        x-show="isPanelOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div class="w-screen max-w-md transform transition duration-500 ease-in-out shadow-2xl" x-show="isPanelOpen"
            x-transition:enter="transform transition ease-in-out duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div
                class="flex h-full flex-col bg-[#1e293b] border-l border-slate-800 shadow-2xl overflow-y-auto custom-scrollbar">

                {{-- Phần đầu (Header) --}}
                <div
                    class="sticky top-0 bg-[#1e293b] border-b border-slate-800 p-6 flex items-center justify-between z-10">
                    <h2 class="text-white text-xl font-bold tracking-tight">Chi tiết người dùng</h2>
                    <button @click="isPanelOpen = false"
                        class="text-slate-400 hover:text-white transition-all p-2 hover:bg-slate-800 rounded-xl">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                {{-- Nội dung (Content) - SỬ DỤNG X-IF ĐỂ TRÁNH LỖI NULL --}}
                <template x-if="selectedUser">
                    <div class="p-6 space-y-6">

                        {{-- Hồ sơ người dùng (User Profile) --}}
                        <div class="text-center">
                            <div
                                class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-4 border-4 border-slate-800 shadow-xl">
                                <span class="text-white font-extrabold text-3xl"
                                    x-text="selectedUser.name ? selectedUser.name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase() : 'U'">
                                </span>
                            </div>
                            <h3 class="text-white text-2xl font-bold mb-2" x-text="selectedUser.name"></h3>

                            <div class="flex items-center justify-center gap-2 mb-3">
                                {{-- Badge Vai trò --}}
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border"
                                    :class="{
                                        'bg-purple-500/10 text-purple-500 border-purple-500/20': selectedUser
                                            .role === 'admin',
                                        'bg-blue-500/10 text-blue-500 border-blue-500/20': selectedUser
                                            .role === 'staff',
                                        'bg-slate-700 text-slate-400 border-slate-600': selectedUser.role === 'user'
                                    }"
                                    x-text="selectedUser.role_display || selectedUser.role"></span>

                                {{-- Badge Trạng thái --}}
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border"
                                    :class="{
                                        'bg-green-500/10 text-green-500 border-green-500/20': selectedUser
                                            .status === 'active',
                                        'bg-orange-500/10 text-orange-500 border-orange-500/20': selectedUser
                                            .status === 'inactive',
                                        'bg-red-500/10 text-red-500 border-red-500/20': selectedUser
                                            .status === 'banned'
                                    }"
                                    x-text="selectedUser.status_label || selectedUser.status"></span>
                            </div>
                        </div>

                        {{-- Thông tin liên hệ --}}
                        <div class="bg-slate-800/40 rounded-2xl p-5 space-y-4 border border-slate-800/50">
                            <h4 class="text-white font-bold text-sm flex items-center gap-2">
                                <i class="fas fa-address-book text-blue-500 text-xs"></i> Thông tin liên hệ
                            </h4>

                            <div class="flex items-start gap-4">
                                <div
                                    class="w-9 h-9 bg-blue-500/10 rounded-xl flex items-center justify-center shrink-0 border border-blue-500/10">
                                    <i class="fas fa-envelope text-blue-500 text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-slate-500 text-[11px] uppercase font-bold tracking-widest mb-0.5">
                                        Email</p>
                                    <p class="text-white text-sm font-medium truncate" x-text="selectedUser.email"></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="w-9 h-9 bg-green-500/10 rounded-xl flex items-center justify-center shrink-0 border border-green-500/10">
                                    <i class="fas fa-phone text-green-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 text-[11px] uppercase font-bold tracking-widest mb-0.5">Số
                                        điện thoại</p>
                                    <p class="text-white text-sm font-medium"
                                        x-text="selectedUser.phone || 'Chưa cập nhật'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Thông tin tài khoản --}}
                        <div class="bg-slate-800/40 rounded-2xl p-5 space-y-4 border border-slate-800/50">
                            <h4 class="text-white font-bold text-sm flex items-center gap-2">
                                <i class="fas fa-shield-alt text-purple-500 text-xs"></i> Thông tin tài khoản
                            </h4>

                            <div class="flex items-start gap-4">
                                <div
                                    class="w-9 h-9 bg-purple-500/10 rounded-xl flex items-center justify-center shrink-0 border border-purple-500/10">
                                    <i class="fas fa-calendar-check text-purple-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 text-[11px] uppercase font-bold tracking-widest mb-0.5">
                                        Thành viên từ</p>
                                    <p class="text-white text-sm font-medium" x-text="selectedUser.createdAt"></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="w-9 h-9 bg-orange-500/10 rounded-xl flex items-center justify-center shrink-0 border border-orange-500/10">
                                    <i class="fas fa-history text-orange-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-slate-500 text-[11px] uppercase font-bold tracking-widest mb-0.5">Lần
                                        đăng nhập cuối</p>
                                    <p class="text-white text-sm font-medium" x-text="selectedUser.lastLogin"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Hoạt động & Thống kê --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="bg-blue-600/5 rounded-2xl p-5 border border-blue-500/10 transition-all hover:bg-blue-600/10">
                                <div class="w-8 h-8 bg-blue-500/10 rounded-xl flex items-center justify-center mb-3">
                                    <i class="fas fa-shopping-cart text-blue-500 text-xs"></i>
                                </div>
                                <p class="text-white text-2xl font-black mb-0.5" x-text="selectedUser.orders"></p>
                                <p class="text-slate-500 text-[10px] uppercase font-bold tracking-wider">Tổng đơn hàng
                                </p>
                            </div>

                            <div
                                class="bg-emerald-600/5 rounded-2xl p-5 border border-emerald-500/10 transition-all hover:bg-emerald-600/10">
                                <div class="w-8 h-8 bg-emerald-500/10 rounded-xl flex items-center justify-center mb-3">
                                    <i class="fas fa-dollar-sign text-emerald-500 text-xs"></i>
                                </div>
                                <p class="text-white text-2xl font-black mb-0.5" x-text="selectedUser.totalSpent"></p>
                                <p class="text-slate-500 text-[10px] uppercase font-bold tracking-wider">Tổng chi tiêu
                                </p>
                            </div>
                        </div>

                        {{-- Các nút thao tác --}}
                        <div class="space-y-3 pt-6 border-t border-slate-800">
                            <button @click="isEditPanelOpen = true"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                                <i class="fas fa-user-edit text-sm"></i>
                                Chỉnh sửa thành viên
                            </button>

                            <button @click="resetUserPassword(selectedUser)"
                                class="w-full bg-slate-800 text-slate-300 border border-slate-700 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all hover:bg-slate-700 hover:text-white active:scale-95">
                                <i class="fas fa-key text-sm"></i>
                                Đặt lại mật khẩu
                            </button>

                            <button @click="toggleUserStatus(selectedUser)"
                                class="w-full py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all border active:scale-95"
                                :class="selectedUser.status === 'active' ?
                                    'bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500/20' :
                                    'bg-green-500/10 text-green-500 border-green-500/20 hover:bg-green-500/20'">
                                <i class="fas text-sm"
                                    :class="selectedUser.status === 'active' ? 'fa-user-slash' : 'fa-user-check'"></i>
                                <span
                                    x-text="selectedUser.status === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản'"></span>
                            </button>
                        </div>
                    </div>
                </template>
                {{-- KẾT THÚC TEMPLATE --}}

            </div>
        </div>
    </div>
</div>
