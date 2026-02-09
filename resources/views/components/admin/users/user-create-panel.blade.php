<div x-show="isCreatePanelOpen" class="fixed inset-0 z-[110] overflow-hidden" x-cloak>
    {{-- Lớp nền mờ --}}
    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-md transition-opacity" @click="isCreatePanelOpen = false"
        x-show="isCreatePanelOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div class="w-screen max-w-md transform transition duration-500 shadow-2xl" x-show="isCreatePanelOpen"
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
                        <h2 class="text-white text-xl font-black tracking-tight">Thêm người dùng mới</h2>
                        <p class="text-blue-400 text-[10px] uppercase font-bold tracking-widest mt-1">Khởi tạo tài khoản
                            hệ thống</p>
                    </div>
                    <button @click="isCreatePanelOpen = false"
                        class="text-slate-400 hover:text-white transition-all p-2 hover:bg-slate-800 rounded-xl">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                {{-- Form Content --}}
                <div class="p-6 space-y-6">

                    {{-- Name Section --}}
                    <div class="bg-slate-900/50 p-5 rounded-3xl border border-slate-800 space-y-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg border border-white/10">
                                <i class="fas fa-user-plus text-white text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Tên hiển
                                    thị</label>
                                <input type="text" x-model="newUser.name" placeholder="Nhập họ tên..."
                                    class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white text-sm focus:border-blue-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin liên lạc --}}
                    <div class="space-y-4">
                        <h4 class="text-slate-400 text-xs font-bold uppercase tracking-widest px-2">Thông tin liên lạc
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                                <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Địa chỉ
                                    Email</label>
                                <input type="email" x-model="newUser.email" placeholder="example@gmail.com"
                                    class="w-full bg-transparent border-b border-slate-700 text-white text-sm py-1 focus:border-blue-500 outline-none transition-all">
                            </div>

                            <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                                <label class="text-slate-500 text-[10px] uppercase font-black mb-1 block">Số điện
                                    thoại</label>
                                <input type="text" x-model="newUser.phone" placeholder="090..."
                                    class="w-full bg-transparent border-b border-slate-700 text-white text-sm py-1 focus:border-blue-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Vai trò & Trạng thái --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                            <label class="text-slate-500 text-[10px] uppercase font-black mb-2 block">Vai trò</label>
                            <select x-model="newUser.role"
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs outline-none focus:border-purple-500">
                                <option value="user">Khách hàng</option>
                                <option value="staff">Nhân viên</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>

                        <div class="bg-slate-900/30 p-4 rounded-2xl border border-slate-800/50">
                            <label class="text-slate-500 text-[10px] uppercase font-black mb-2 block">Trạng thái</label>
                            <select x-model="newUser.status"
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white text-xs outline-none focus:border-emerald-500">
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Chờ kích hoạt</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-2xl">
                        <p class="text-[11px] text-blue-300">
                            <i class="fas fa-info-circle mr-1"></i> Mật khẩu mặc định cho tài khoản mới sẽ là <span
                                class="font-bold text-white">123456</span>. Người dùng có thể đổi lại sau khi đăng nhập.
                        </p>
                    </div>

                    {{-- Nút hành động --}}
                    <div class="pt-6 border-t border-slate-800 flex gap-3">
                        <button @click="saveNewUser()"
                            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-2xl font-black text-sm transition-all shadow-xl shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i> XÁC NHẬN THÊM MỚI
                        </button>
                        <button @click="isCreatePanelOpen = false"
                            class="px-6 bg-slate-800 text-slate-400 py-3.5 rounded-2xl font-bold text-sm hover:bg-slate-700 transition-all">
                            HỦY
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
