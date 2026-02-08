<div class="space-y-6 pb-20" x-data="{
    isModalOpen: false,
    selectedRole: null,
    permissionLabels: {
        dashboard: 'Truy cập Dashboard',
        products: 'Quản lý Sản phẩm',
        orders: 'Quản lý Đơn hàng',
        customers: 'Quản lý Khách hàng',
        analytics: 'Phân tích & Báo cáo',
        settings: 'Cài đặt Hệ thống',
        reports: 'Xuất báo cáo',
        marketing: 'Công cụ Marketing'
    },
    roles: [
        { id: '1', name: 'Administrator', description: 'Toàn quyền truy cập hệ thống', userCount: 3, permissions: { dashboard: true, products: true, orders: true, customers: true, analytics: true, settings: true, reports: true, marketing: true } },
        { id: '2', name: 'Manager', description: 'Quản lý vận hành cửa hàng', userCount: 8, permissions: { dashboard: true, products: true, orders: true, customers: true, analytics: true, settings: false, reports: true, marketing: true } },
        { id: '3', name: 'Support Agent', description: 'Hỗ trợ khách hàng & đơn hàng', userCount: 15, permissions: { dashboard: true, products: false, orders: true, customers: true, analytics: false, settings: false, reports: false, marketing: false } },
        { id: '4', name: 'Content Editor', description: 'Quản lý nội dung sản phẩm', userCount: 5, permissions: { dashboard: true, products: true, orders: false, customers: false, analytics: false, settings: false, reports: false, marketing: true } }
    ],
    editRole(role) {
        this.selectedRole = JSON.parse(JSON.stringify(role));
        this.isModalOpen = true;
    },
    saveRole() {
        const index = this.roles.findIndex(r => r.id === this.selectedRole.id);
        if (index !== -1) {
            this.roles[index] = this.selectedRole;
            alert('Đã cập nhật quyền hạn cho ' + this.selectedRole.name);
        }
        this.isModalOpen = false;
    },
    deleteRole(id) {
        if (confirm('Bạn có chắc chắn muốn xóa vai trò này?')) {
            this.roles = this.roles.filter(r => r.id !== id);
        }
    }
}">

    {{-- 1. Header Card --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <i class="fas fa-user-shield text-blue-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight uppercase">Vai trò & Phân quyền</h2>
                    <p class="text-xs text-slate-500 mt-1">Quản lý các cấp bậc truy cập và quyền hạn của nhân viên.</p>
                </div>
            </div>
            <button @click="alert('Tính năng thêm vai trò đang được phát triển')"
                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                <i class="fas fa-plus-circle mr-2"></i> Thêm vai trò mới
            </button>
        </div>
    </div>

    {{-- 2. Roles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <template x-for="role in roles" :key="role.id">
            <div
                class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all group hover:border-blue-500/30">
                <div class="space-y-5">
                    {{-- Role Header --}}
                    <div class="flex items-start justify-between">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-black text-white tracking-tight uppercase" x-text="role.name">
                                </h3>
                                <span
                                    class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[9px] font-black uppercase tracking-widest"
                                    x-text="role.userCount + ' nhân viên'"></span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium" x-text="role.description"></p>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-blue-500">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>

                    {{-- Permissions Summary --}}
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Quyền hạn đã cấp</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(enabled, key) in role.permissions">
                                <template x-if="enabled">
                                    <span
                                        class="px-2 py-1 rounded-lg bg-emerald-500/5 text-emerald-500 border border-emerald-500/10 text-[9px] font-bold uppercase tracking-tight"
                                        x-text="permissionLabels[key]"></span>
                                </template>
                            </template>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 pt-2">
                        <button @click="editRole(role)"
                            class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-300 text-[10px] font-black uppercase tracking-widest hover:bg-slate-700 hover:text-white transition-all">
                            <i class="fas fa-edit mr-2"></i> Chỉnh sửa quyền
                        </button>
                        <template x-if="role.name !== 'Administrator'">
                            <button @click="deleteRole(role.id)"
                                class="px-4 py-2.5 rounded-xl border border-red-500/20 text-red-500/50 hover:text-red-400 hover:bg-red-500/10 transition-all">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- 3. Edit Role Modal --}}
    <div x-show="isModalOpen"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0">

        <div @click.away="isModalOpen = false"
            class="bg-slate-900 border border-slate-800 rounded-3xl max-w-xl w-full p-8 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            <div class="flex justify-between items-center mb-6">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-white uppercase tracking-tight">Chỉnh sửa vai trò</h3>
                    <p class="text-xs text-slate-500" x-text="'Cấu hình quyền hạn cho: ' + selectedRole?.name"></p>
                </div>
                <button @click="isModalOpen = false"
                    class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                <template x-if="selectedRole">
                    <div class="grid gap-3">
                        <template x-for="(label, key) in permissionLabels" :key="key">
                            <div @click="selectedRole.permissions[key] = !selectedRole.permissions[key]"
                                class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer group"
                                :class="selectedRole.permissions[key] ? 'bg-blue-500/5 border-blue-500/30' :
                                    'bg-slate-950/50 border-slate-800 hover:border-slate-700'">

                                <div class="flex items-center gap-4">
                                    {{-- Custom Checkbox --}}
                                    <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all"
                                        :class="selectedRole.permissions[key] ? 'bg-blue-600 border-blue-600' :
                                            'border-slate-700 bg-slate-900 group-hover:border-slate-500'">
                                        <i class="fas fa-check text-white text-[10px]"
                                            x-show="selectedRole.permissions[key]"></i>
                                    </div>
                                    <span class="text-sm font-bold uppercase tracking-tight transition-colors"
                                        :class="selectedRole.permissions[key] ? 'text-white' :
                                            'text-slate-500 group-hover:text-slate-400'"
                                        x-text="label"></span>
                                </div>

                                <template x-if="selectedRole.permissions[key]">
                                    <span
                                        class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[8px] font-black uppercase">Cho
                                        phép</span>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div class="flex gap-3 mt-8 pt-6 border-t border-slate-800">
                <button @click="isModalOpen = false"
                    class="flex-1 py-3 rounded-xl bg-slate-800 text-slate-300 font-black text-xs uppercase tracking-widest hover:bg-slate-700 transition-all">Hủy
                    bỏ</button>
                <button @click="saveRole()"
                    class="flex-1 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-900/20 active:scale-95 transition-all">Lưu
                    thay đổi</button>
            </div>
        </div>
    </div>
</div>
