<div class="space-y-6 pb-20" x-data="{
    autoBackup: true,
    backupFrequency: 'daily',
    backupHistory: [
        { id: '1', date: '2026-02-07 03:00 AM', size: '245 MB', status: 'completed' },
        { id: '2', date: '2026-02-06 03:00 AM', size: '243 MB', status: 'completed' },
        { id: '3', date: '2026-02-05 03:00 AM', size: '241 MB', status: 'completed' },
        { id: '4', date: '2026-02-04 03:00 AM', size: '240 MB', status: 'failed' }
    ],
    notify(msg) { alert(msg); }
}">

    {{-- 1. Automatic Backup --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-database text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Sao lưu tự động</h2>
        </div>

        <div class="space-y-6">
            <div class="flex items-center justify-between p-5 bg-slate-900/50 rounded-2xl border border-slate-800">
                <div class="space-y-1">
                    <p class="font-bold text-white text-sm">Kích hoạt sao lưu tự động</p>
                    <p class="text-xs text-slate-500">Tự động sao lưu dữ liệu theo khoảng thời gian đã định sẵn</p>
                </div>
                {{-- Custom Switch --}}
                <button
                    @click="autoBackup = !autoBackup; notify(autoBackup ? 'Đã bật sao lưu tự động' : 'Đã tắt sao lưu tự động')"
                    :class="autoBackup ? 'bg-blue-600' : 'bg-slate-700'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none shadow-inner">
                    <span :class="autoBackup ? 'translate-x-6' : 'translate-x-1'"
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </div>

            <div x-show="autoBackup" x-transition class="space-y-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tần suất sao
                    lưu</label>
                <div class="relative">
                    <select x-model="backupFrequency"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="hourly">Mỗi giờ</option>
                        <option value="daily">Hàng ngày (03:00 AM)</option>
                        <option value="weekly">Hàng tuần (Chủ nhật 03:00 AM)</option>
                        <option value="monthly">Hàng tháng (Ngày 1 đầu tháng)</option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Manual Backup --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <i class="fas fa-download text-emerald-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Sao lưu thủ công</h2>
        </div>

        <div class="space-y-4">
            <p class="text-xs text-slate-500 leading-relaxed">Tạo bản sao lưu ngay lập tức cho toàn bộ dữ liệu bao gồm
                sản phẩm, đơn hàng, khách hàng và cài đặt hệ thống.</p>
            <button @click="notify('Quá trình sao lưu đã bắt đầu. Vui lòng chờ trong giây lát...')"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                <i class="fas fa-cloud-download-alt mr-2"></i> Tạo bản sao lưu ngay
            </button>
        </div>
    </div>

    {{-- 3. Backup History --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                <i class="fas fa-history text-purple-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Lịch sử sao lưu</h2>
        </div>

        <div class="space-y-3">
            <template x-for="backup in backupHistory" :key="backup.id">
                <div
                    class="flex items-center justify-between p-4 bg-slate-900/40 rounded-2xl border border-slate-800 hover:border-slate-700 transition-all group">
                    <div class="flex items-center gap-4">
                        <div :class="backup.status === 'completed' ? 'bg-emerald-500/10 border-emerald-500/20' :
                            'bg-red-500/10 border-red-500/20'"
                            class="w-10 h-10 rounded-xl border flex items-center justify-center">
                            <i :class="backup.status === 'completed' ? 'fas fa-check-circle text-emerald-400' :
                                'fas fa-times-circle text-red-400'"
                                class="text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-bold text-white" x-text="backup.date"></p>
                            <div class="flex items-center gap-2">
                                <span
                                    :class="backup.status === 'completed' ?
                                        'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' :
                                        'bg-red-500/10 text-red-500 border-red-500/20'"
                                    class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border"
                                    x-text="backup.status === 'completed' ? 'Thành công' : 'Thất bại'"></span>
                                <span class="text-[10px] font-mono text-slate-500" x-text="backup.size"></span>
                            </div>
                        </div>
                    </div>
                    <button x-show="backup.status === 'completed'"
                        @click="notify('Đang chuẩn bị tải bản sao lưu ' + backup.date)"
                        class="p-2.5 rounded-xl border border-slate-700 text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                        <i class="fas fa-download text-xs"></i>
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- 4. System Status --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center">
                <i class="fas fa-hdd text-orange-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Trạng thái hệ thống</h2>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([['label' => 'Dung lượng DB', 'val' => '1.2 GB'], ['label' => 'Tệp đa phương tiện', 'val' => '3.8 GB'], ['label' => 'Tổng bản sao', 'val' => '24'], ['label' => 'Bộ nhớ đã dùng', 'val' => '5.8 GB']] as $stat)
                    <div class="p-4 bg-slate-900/50 rounded-2xl border border-slate-800">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-600 mb-1">
                            {{ $stat['label'] }}</p>
                        <p class="text-xl font-black text-white tracking-tight">{{ $stat['val'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="p-4 bg-emerald-500/5 border border-emerald-500/10 rounded-2xl flex gap-3">
                <i class="fas fa-check-shield text-emerald-500 text-sm mt-0.5"></i>
                <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Mọi hệ thống đang hoạt động
                    bình thường. Bản sao lưu cuối cùng đã sẵn sàng.</p>
            </div>
        </div>
    </div>

    {{-- 5. Retention Policy --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <h2 class="text-xl font-black text-white tracking-tight mb-6">Chính sách lưu giữ</h2>

        <div class="space-y-4">
            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Thời gian lưu trữ
                    bản sao lưu</label>
                <div class="relative">
                    <select
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="7">7 Ngày</option>
                        <option value="14">14 Ngày</option>
                        <option value="30" selected>30 Ngày</option>
                        <option value="60">60 Ngày</option>
                        <option value="90">90 Ngày</option>
                        <option value="forever">Vĩnh viễn</option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
                <p class="text-[10px] text-slate-500 italic ml-1">Các bản sao lưu cũ hơn sẽ tự động bị xóa để tiết kiệm
                    dung lượng lưu trữ.</p>
            </div>
        </div>
    </div>
</div>
