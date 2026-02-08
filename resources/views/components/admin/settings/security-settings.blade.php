<div class="space-y-6 pb-20" x-data="{
    currentPassword: '',
    newPassword: '',
    confirmPassword: '',
    twoFactorEnabled: false,
    sessionTimeout: '30',
    showResetModal: false,
    showActivityModal: false,
    loginActivities: [
        { id: '1', timestamp: '2026-02-07 14:23:15', ip: '192.168.1.100', location: 'Hồ Chí Minh, VN', device: 'Chrome on Windows', success: true },
        { id: '2', timestamp: '2026-02-07 09:15:42', ip: '192.168.1.100', location: 'Hồ Chí Minh, VN', device: 'Chrome on Windows', success: true },
        { id: '3', timestamp: '2026-02-06 18:45:30', ip: '1.55.12.90', location: 'Hà Nội, VN', device: 'Safari on macOS', success: false },
        { id: '4', timestamp: '2026-02-06 11:20:18', ip: '192.168.1.100', location: 'Hồ Chí Minh, VN', device: 'Chrome on Windows', success: true }
    ],
    handleChangePassword() {
        if (this.newPassword !== this.confirmPassword) { alert('Mật khẩu xác nhận không khớp!'); return; }
        if (this.newPassword.length < 8) { alert('Mật khẩu phải có ít nhất 8 ký tự!'); return; }
        alert('Đã cập nhật mật khẩu thành công!');
        this.currentPassword = '';
        this.newPassword = '';
        this.confirmPassword = '';
    },
    notify(msg) { alert(msg); }
}">

    {{-- 1. Change Password --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-key text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Đổi mật khẩu</h2>
        </div>

        <div class="max-w-md space-y-5">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Mật khẩu hiện
                    tại</label>
                <input type="password" x-model="currentPassword"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner"
                    placeholder="••••••••">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Mật khẩu mới</label>
                <input type="password" x-model="newPassword"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner"
                    placeholder="Tối thiểu 8 ký tự">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Xác nhận mật khẩu
                    mới</label>
                <input type="password" x-model="confirmPassword"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner"
                    placeholder="Nhập lại mật khẩu mới">
            </div>
            <button @click="handleChangePassword()"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                Cập nhật mật khẩu
            </button>
        </div>
    </div>

    {{-- 2. Two-Factor Authentication --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <i class="fas fa-shield-alt text-emerald-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Xác thực 2 yếu tố (2FA)</h2>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-5 bg-slate-900/50 rounded-2xl border border-slate-800">
                <div class="space-y-1">
                    <p class="font-bold text-white text-sm">Kích hoạt 2FA</p>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Tăng cường bảo mật bằng cách yêu cầu mã xác
                        nhận khi đăng nhập</p>
                </div>
                <button
                    @click="twoFactorEnabled = !twoFactorEnabled; notify(twoFactorEnabled ? 'Đã bật xác thực 2 lớp' : 'Đã tắt xác thực 2 lớp')"
                    :class="twoFactorEnabled ? 'bg-emerald-600' : 'bg-slate-700'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                    <span :class="twoFactorEnabled ? 'translate-x-6' : 'translate-x-1'"
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </div>

            <div x-show="twoFactorEnabled"
                class="p-4 bg-emerald-500/5 border border-emerald-500/20 rounded-2xl flex gap-3" x-cloak>
                <i class="fas fa-check-circle text-emerald-400 mt-0.5"></i>
                <p class="text-[10px] text-emerald-500/80 font-bold leading-relaxed uppercase tracking-widest">
                    2FA đang hoạt động. Bạn sẽ cần nhập mã từ ứng dụng xác thực (Google Authenticator) để truy cập tài
                    khoản.
                </p>
            </div>
        </div>
    </div>

    {{-- 3. Session Timeout --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center">
                <i class="fas fa-hourglass-half text-orange-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Thời gian phiên làm việc</h2>
        </div>

        <div class="max-w-md space-y-4">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tự động đăng xuất
                    sau (phút)</label>
                <div class="relative">
                    <input type="number" x-model="sessionTimeout" min="5" max="120"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner font-mono">
                    <i class="fas fa-clock absolute right-4 top-1/2 -translate-y-1/2 text-slate-600 text-xs"></i>
                </div>
                <p class="text-[10px] text-slate-500 italic ml-1">Bạn sẽ bị đăng xuất tự động nếu không có hoạt động
                    trong khoảng thời gian này.</p>
            </div>
        </div>
    </div>

    {{-- 4. Login Activity --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-400"></i>
                </div>
                <h2 class="text-xl font-black text-white tracking-tight uppercase">Hoạt động đăng nhập</h2>
            </div>
            <button @click="showActivityModal = true"
                class="text-[10px] font-black uppercase tracking-widest text-blue-400 hover:text-blue-300 transition-colors">
                Xem tất cả nhật ký
            </button>
        </div>

        <div class="space-y-3">
            <template x-for="activity in loginActivities.slice(0, 3)" :key="activity.id">
                <div
                    class="flex items-center justify-between p-4 bg-slate-900/40 rounded-2xl border border-slate-800 hover:border-slate-700 transition-all">
                    <div class="flex items-center gap-4">
                        <div :class="activity.success ? 'text-emerald-400' : 'text-red-400'"
                            class="w-10 h-10 rounded-xl bg-slate-950 flex items-center justify-center border border-slate-800">
                            <i :class="activity.success ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-sm font-bold text-white uppercase tracking-tight" x-text="activity.device">
                            </p>
                            <p class="text-[10px] text-slate-500 font-bold"
                                x-text="activity.location + ' • ' + activity.ip"></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-500 font-mono" x-text="activity.timestamp"></p>
                        <span :class="activity.success ? 'text-emerald-500' : 'text-red-500'"
                            class="text-[8px] font-black uppercase tracking-widest"
                            x-text="activity.success ? 'Thành công' : 'Thất bại'"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- 5. IP Access Control --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center">
                <i class="fas fa-network-wired text-yellow-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Kiểm soát truy cập IP</h2>
        </div>

        <div class="space-y-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">IP Whitelist (Danh
                    sách trắng)</label>
                <textarea
                    class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 text-white font-mono text-xs focus:border-blue-500 outline-none shadow-inner"
                    rows="2" placeholder="192.168.1.1, 10.0.0.1"></textarea>
                <p class="text-[10px] text-slate-500">Chỉ cho phép truy cập từ các địa chỉ IP này (Để trống nếu cho phép
                    tất cả).</p>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">IP Blacklist (Danh
                    sách đen)</label>
                <textarea
                    class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 text-red-400 font-mono text-xs focus:border-red-500 outline-none shadow-inner"
                    rows="2" placeholder="1.2.3.4, 5.6.7.8"></textarea>
                <p class="text-[10px] text-slate-500 text-red-500/50">Chặn hoàn toàn truy cập từ các địa chỉ IP này.
                </p>
            </div>
        </div>
    </div>

    {{-- 6. Danger Zone - Reset --}}
    <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                <i class="fas fa-undo text-red-500"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Khôi phục bảo mật</h2>
        </div>

        <p class="text-[10px] text-slate-500 mb-6 uppercase font-bold tracking-widest leading-relaxed">
            Hành động này sẽ đưa toàn bộ thiết lập bảo mật (2FA, Timeout, IP) về trạng thái mặc định. Không thể hoàn
            tác.
        </p>
        <button @click="showResetModal = true"
            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-red-900/20 transition-all active:scale-95">
            Reset Security Settings
        </button>
    </div>

    {{-- Modal: Reset Confirmation --}}
    <div x-show="showResetModal"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
        <div @click.away="showResetModal = false"
            class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-8 shadow-2xl">
            <div class="w-16 h-16 bg-red-500/10 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
            </div>
            <h3 class="text-xl font-black text-white text-center mb-2 uppercase tracking-tight">Xác nhận Reset?</h3>
            <p class="text-slate-500 text-center text-[10px] font-bold uppercase tracking-widest mb-8">Tất cả cấu hình
                bảo mật sẽ bị xóa sạch.</p>
            <div class="flex gap-3">
                <button @click="showResetModal = false"
                    class="flex-1 py-3 rounded-xl bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition-all">Hủy</button>
                <button @click="showResetModal = false; notify('Bảo mật đã được reset')"
                    class="flex-1 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-all">Xác
                    nhận</button>
            </div>
        </div>
    </div>

    {{-- Modal: Activity Log --}}
    <div x-show="showActivityModal"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak
        x-transition>
        <div @click.away="showActivityModal = false"
            class="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full p-8 shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-white uppercase tracking-tight">Nhật ký đăng nhập</h3>
                <button @click="showActivityModal = false" class="text-slate-500 hover:text-white"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                <template x-for="activity in loginActivities" :key="activity.id">
                    <div
                        class="flex items-center justify-between p-4 bg-slate-800/40 rounded-2xl border border-slate-800">
                        <div class="flex items-center gap-4">
                            <div :class="activity.success ? 'text-emerald-400' : 'text-red-400'"
                                class="w-10 h-10 rounded-xl bg-slate-950 flex items-center justify-center border border-slate-800">
                                <i :class="activity.success ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white uppercase" x-text="activity.device"></p>
                                <p class="text-[10px] text-slate-500 font-bold"
                                    x-text="activity.location + ' • ' + activity.ip"></p>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-500 font-mono" x-text="activity.timestamp"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
