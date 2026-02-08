<div class="space-y-6 pb-20" x-data="{
    zones: [
        { id: '1', name: 'Nội địa (Việt Nam)', countries: ['Vietnam'], rate: 30000, estimatedDays: '1-3' },
        { id: '2', name: 'Đông Nam Á', countries: ['Thailand', 'Singapore', 'Malaysia', 'Indonesia'], rate: 150000, estimatedDays: '3-7' },
        { id: '3', name: 'Châu Âu', countries: ['UK', 'Germany', 'France', 'Spain'], rate: 450000, estimatedDays: '7-14' },
        { id: '4', name: 'Bắc Mỹ', countries: ['USA', 'Canada'], rate: 550000, estimatedDays: '10-21' }
    ],
    carriers: [
        { id: 'ghtk', name: 'Giao Hàng Tiết Kiệm', enabled: true },
        { id: 'ghn', name: 'Giao Hàng Nhanh', enabled: true },
        { id: 'viettel', name: 'Viettel Post', enabled: true },
        { id: 'dhl', name: 'DHL Express', enabled: false }
    ],
    deleteZone(id) {
        this.zones = this.zones.filter(z => z.id !== id);
        this.notify('Đã xóa vùng vận chuyển thành công');
    },
    notify(msg) { alert(msg); }
}">

    {{-- 1. Header Card --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <i class="fas fa-truck text-blue-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight uppercase">Vùng vận chuyển</h2>
                    <p class="text-xs text-slate-500 mt-1">Quản lý khu vực giao hàng và biểu phí tương ứng.</p>
                </div>
            </div>
            <button @click="notify('Tính năng thêm vùng vận chuyển đang được phát triển')"
                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                <i class="fas fa-plus-circle mr-2"></i> Thêm vùng mới
            </button>
        </div>
    </div>

    {{-- 2. Shipping Zones List --}}
    <div class="grid gap-4">
        <template x-for="zone in zones" :key="zone.id">
            <div
                class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all group hover:border-blue-500/30">
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-blue-400">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                </div>
                                <h3 class="text-lg font-black text-white tracking-tight" x-text="zone.name"></h3>
                            </div>
                            {{-- Country Badges --}}
                            <div class="flex flex-wrap gap-2">
                                <template x-for="country in zone.countries" :key="country">
                                    <span
                                        class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[9px] font-black uppercase tracking-widest"
                                        x-text="country"></span>
                                </template>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <button @click="notify('Sửa vùng: ' + zone.name)"
                                class="w-8 h-8 rounded-lg border border-slate-700 text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button @click="deleteZone(zone.id)"
                                class="w-8 h-8 rounded-lg border border-red-500/20 text-red-500/50 hover:text-red-400 hover:bg-red-500/10 transition-all">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Zone Details --}}
                    <div class="grid grid-cols-2 gap-4 pt-5 border-t border-slate-800">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-600">Phí vận chuyển
                            </p>
                            <p class="text-xl font-black text-white tracking-tight"
                                x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(zone.rate)">
                            </p>
                        </div>
                        <div class="space-y-1 text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-600">Thời gian dự kiến
                            </p>
                            <p class="text-xl font-black text-white tracking-tight"
                                x-text="zone.estimatedDays + ' Ngày'"></p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- 3. Carrier Integrations --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <h2 class="text-xl font-black text-white tracking-tight uppercase mb-6">Đối tác vận chuyển</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-for="carrier in carriers" :key="carrier.id">
                <div
                    class="flex items-center justify-between p-5 bg-slate-900/50 rounded-2xl border border-slate-800 group hover:border-slate-700 transition-all">
                    <div class="space-y-2">
                        <p class="text-sm font-black text-white uppercase tracking-tight" x-text="carrier.name"></p>
                        <span
                            :class="carrier.enabled ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' :
                                'bg-slate-700/50 text-slate-500 border-slate-700'"
                            class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border"
                            x-text="carrier.enabled ? 'Đã kết nối' : 'Chưa kết nối'"></span>
                    </div>
                    <button
                        class="px-4 py-2 rounded-xl border border-slate-700 text-slate-400 text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 hover:text-white transition-all">
                        <span x-text="carrier.enabled ? 'Cấu hình' : 'Kết nối'"></span>
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- 4. Default Shipping Rules --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <h2 class="text-xl font-black text-white tracking-tight uppercase mb-8">Quy tắc mặc định</h2>

        <div class="space-y-6">
            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Ngưỡng miễn phí vận
                    chuyển</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-bold">₫</span>
                    <input type="number" value="500000"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-white font-mono text-sm focus:border-blue-500 outline-none transition-all shadow-inner">
                </div>
                <p class="text-[10px] text-slate-500 italic ml-1">Các đơn hàng trên mức này sẽ được miễn phí giao hàng.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Đơn vị trọng
                        lượng</label>
                    <div class="relative">
                        <select
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="kg">Kilogram (kg)</option>
                            <option value="g">Gram (g)</option>
                            <option value="lb">Pounds (lb)</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Đơn vị kích
                        thước</label>
                    <div class="relative">
                        <select
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="cm">Centimeters (cm)</option>
                            <option value="mm">Millimeters (mm)</option>
                            <option value="in">Inches (in)</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
