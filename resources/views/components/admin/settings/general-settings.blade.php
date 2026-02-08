<div class="space-y-6 pb-20" x-data="{
    formData: {
        siteName: 'E-Commerce Admin Pro',
        adminEmail: 'admin@ecommerce.com',
        timezone: 'IST',
        {{-- Mặc định theo múi giờ Việt Nam/Asia --}}
        language: 'vi',
        dateFormat: 'DD/MM/YYYY',
        timeFormat: '24h'
    },
    notify(msg) { alert(msg); }
}">

    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-cog text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Cài đặt chung</h2>
        </div>

        <div class="space-y-6">
            {{-- Site Name --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tên Website</label>
                <input type="text" x-model="formData.siteName"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder:text-slate-600 focus:border-blue-500 outline-none transition-all shadow-inner"
                    placeholder="Nhập tên website của bạn...">
            </div>

            {{-- Admin Email --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Email Quản
                    trị</label>
                <div class="relative group">
                    <div
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-blue-500 transition-colors">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" x-model="formData.adminEmail"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner"
                        placeholder="admin@example.com">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Timezone --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Múi giờ hệ
                        thống</label>
                    <div class="relative">
                        <select x-model="formData.timezone"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="UTC">UTC (Universal Time)</option>
                            <option value="IST">ICT (Indochina Time - VN)</option>
                            <option value="GMT">GMT (Greenwich Mean Time)</option>
                            <option value="JST">JST (Japan Standard Time)</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>

                {{-- Default Language --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Ngôn ngữ mặc
                        định</label>
                    <div class="relative">
                        <select x-model="formData.language"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="vi">Tiếng Việt</option>
                            <option value="en">English (US)</option>
                            <option value="ja">Japanese</option>
                            <option value="zh">Chinese</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Date Format --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Định dạng
                        Ngày</label>
                    <div class="relative">
                        <select x-model="formData.dateFormat"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer font-mono">
                            <option value="DD/MM/YYYY">DD/MM/YYYY (31/12/2026)</option>
                            <option value="MM/DD/YYYY">MM/DD/YYYY (12/31/2026)</option>
                            <option value="YYYY-MM-DD">YYYY-MM-DD (2026-12-31)</option>
                            <option value="DD.MM.YYYY">DD.MM.YYYY (31.12.2026)</option>
                        </select>
                        <i
                            class="fas fa-calendar-alt absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>

                {{-- Time Format --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Định dạng
                        Giờ</label>
                    <div class="relative flex gap-2">
                        <button @click="formData.timeFormat = '12h'"
                            :class="formData.timeFormat === '12h' ? 'bg-blue-600 text-white border-blue-500' :
                                'bg-slate-900 text-slate-500 border-slate-700'"
                            class="flex-1 py-3 rounded-xl border font-bold text-xs uppercase tracking-widest transition-all">
                            12 Giờ (AM/PM)
                        </button>
                        <button @click="formData.timeFormat = '24h'"
                            :class="formData.timeFormat === '24h' ? 'bg-blue-600 text-white border-blue-500' :
                                'bg-slate-900 text-slate-500 border-slate-700'"
                            class="flex-1 py-3 rounded-xl border font-bold text-xs uppercase tracking-widest transition-all">
                            24 Giờ
                        </button>
                    </div>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="pt-6 border-t border-slate-800">
                <button @click="notify('Cài đặt chung đã được lưu thành công!')"
                    class="w-full md:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-95">
                    <i class="fas fa-save mr-2"></i> LƯU THAY ĐỔI
                </button>
            </div>
        </div>
    </div>
</div>
