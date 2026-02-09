<div x-show="isEditPanelOpen" class="fixed inset-0 z-[110] overflow-hidden" x-cloak>
    {{-- Lớp nền mờ --}}
    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-md transition-opacity" @click="isEditPanelOpen = false"
        x-show="isEditPanelOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div class="w-screen max-w-md transform transition duration-500 shadow-2xl" x-show="isEditPanelOpen"
            x-transition:enter="transform transition ease-in-out duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div
                class="flex h-full flex-col bg-[#0f172a] border-l border-blue-500/20 shadow-2xl overflow-y-auto custom-scrollbar">

                {{-- Header --}}
                <div
                    class="sticky top-0 bg-[#0f172a]/80 backdrop-blur-md border-b border-slate-800 p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-white text-xl font-black tracking-tight">Chỉnh sửa thành viên</h2>
                        <p class="text-blue-400 text-[10px] uppercase font-bold tracking-widest mt-1">ID người dùng:
                            <span x-text="selectedUser ? selectedUser.id : '...'"></span>
                        </p>
                    </div>
                    <button @click="isEditPanelOpen = false"
                        class="text-slate-400 hover:text-white transition-all p-2 hover:bg-slate-800 rounded-xl">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                {{-- Form Content - SỬ DỤNG X-IF ĐỂ TRÁNH LỖI NULL --}}
                <template x-if="selectedUser">
                    <div class="p-6 space-y-6">

                        {{-- Avatar & Name Section --}}
                        <div class="bg-slate-900/50 p-5 rounded-3xl border border-slate-800 space-y-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg border border-white/10">
                                    <i class="fas fa-user-edit text-white text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Tên hiển
                                        thị</label>
                                    <input type="text" x-model="selectedUser.name"
                                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white text-sm focus:border-blue-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Thông tin tài khoản --}}
                        <div class="space-y-4">
                            <h4 class="text-slate-400 text-xs font-bold uppercase tracking-widest px-2">Thông tin liên
                                lạc</h4>

                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                                    <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Địa chỉ
                                        Email</label>
                                    <input type="email" x-model="selectedUser.email"
                                        class="w-full bg-transparent border-b border-slate-700 text-white text-sm py-1 focus:border-blue-500 outline-none transition-all">
                                </div>

                                <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                                    <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Số điện
                                        thoại</label>
                                    <input type="text" x-model="selectedUser.phone"
                                        class="w-full bg-transparent border-b border-slate-700 text-white text-sm py-1 focus:border-blue-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Vai trò & Trạng thái --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                                <label class="text-slate-500 text-[10px] uppercase font-black mb-2 block">Vai
                                    trò</label>
                                <select x-model="selectedUser.role"
                                    class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs outline-none focus:border-purple-500">
                                    <option value="admin">Quản trị viên</option>
                                    <option value="manager">Nhân viên</option>
                                    <option value="user">Khách hàng</option>
                                </select>
                            </div>

                            <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                                <label class="text-slate-500 text-[10px] uppercase font-black mb-2 block">Trạng
                                    thái</label>
                                <select x-model="selectedUser.status"
                                    class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs outline-none focus:border-emerald-500">
                                    <option value="active">Hoạt động</option>
                                    <option value="banned">Khóa tài khoản</option>
                                    <option value="inactive">Chờ kích hoạt</option>
                                </select>
                            </div>
                        </div>

                        {{-- Thống kê kinh doanh (Chỉ đọc hoặc cho phép sửa cẩn thận) --}}
                        <div class="bg-blue-600/5 p-5 rounded-3xl border border-blue-500/10 space-y-4">
                            <h4
                                class="text-blue-400 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-chart-line"></i> Dữ liệu giao dịch (Admin Only)
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Tổng đơn
                                        hàng</label>
                                    <input type="number" x-model="selectedUser.orders"
                                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-white font-bold text-sm focus:border-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Tổng chi
                                        tiêu</label>
                                    <input type="text" x-model="selectedUser.totalSpent"
                                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-emerald-400 font-bold text-sm focus:border-emerald-500 outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- Ngày gia nhập (Read-only recommended but editable here) --}}
                        <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                            <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Ngày gia nhập hệ
                                thống</label>
                            <input type="text" x-model="selectedUser.createdAt" placeholder="Ví dụ: 09/02/2026 10:00"
                                class="w-full bg-transparent border-b border-slate-700 text-slate-400 text-sm py-1 focus:border-blue-500 outline-none transition-all">
                        </div>

                        {{-- Nút hành động --}}
                        <div class="pt-6 border-t border-slate-800 flex gap-3">
                            <button @click="updateUser()"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-2xl font-black text-sm transition-all shadow-xl shadow-blue-500/20 active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> CẬP NHẬT NGAY
                            </button>
                            <button @click="isEditPanelOpen = false"
                                class="px-6 bg-slate-800 text-slate-400 py-3.5 rounded-2xl font-bold text-sm hover:bg-slate-700 transition-all">
                                HỦY
                            </button>
                        </div>
                    </div>
                </template>
                {{-- KẾT THÚC TEMPLATE --}}
            </div>
        </div>
    </div>
</div>
