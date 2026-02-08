<div class="space-y-6 pb-20" x-data="{
    settings: {
        language: 'vi',
        region: 'VN',
        currency: 'VND',
        currencyFormat: 'symbol',
        numberFormat: 'comma',
        measurementUnit: 'metric',
        timeZone: 'Asia/Ho_Chi_Minh',
        firstDayOfWeek: 'monday'
    },
    notify(msg) { alert(msg); }
}">

    {{-- 1. Language & Region --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-globe text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Ngôn ngữ & Khu vực</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Ngôn ngữ mặc
                    định</label>
                <div class="relative">
                    <select x-model="settings.language"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="vi">Tiếng Việt</option>
                        <option value="en">English (US)</option>
                        <option value="en-GB">English (UK)</option>
                        <option value="fr">French</option>
                        <option value="zh">Chinese</option>
                        <option value="ja">Japanese</option>
                        <option value="ko">Korean</option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Quốc gia / Khu
                    vực</label>
                <div class="relative">
                    <select x-model="settings.region"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="VN">Việt Nam</option>
                        <option value="US">United States</option>
                        <option value="CA">Canada</option>
                        <option value="GB">United Kingdom</option>
                        <option value="JP">Japan</option>
                        <option value="CN">China</option>
                    </select>
                    <i
                        class="fas fa-map-marker-alt absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Currency Settings --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <h2 class="text-xl font-black text-white tracking-tight mb-8">Định dạng Tiền tệ</h2>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Đơn vị tiền
                        tệ</label>
                    <div class="relative">
                        <select x-model="settings.currency"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="VND">VND - Việt Nam Đồng (₫)</option>
                            <option value="USD">USD - US Dollar ($)</option>
                            <option value="EUR">EUR - Euro (€)</option>
                            <option value="GBP">GBP - British Pound (£)</option>
                            <option value="JPY">JPY - Japanese Yen (¥)</option>
                        </select>
                        <i
                            class="fas fa-money-bill-wave absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Hiển thị tiền
                        tệ</label>
                    <div class="relative">
                        <select x-model="settings.currencyFormat"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="symbol">Ký hiệu (₫100.000)</option>
                            <option value="code">Mã (VND 100.000)</option>
                            <option value="name">Tên đầy đủ (100.000 Việt Nam Đồng)</option>
                        </select>
                        <i
                            class="fas fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Currency Preview --}}
            <div class="p-5 bg-slate-950/50 rounded-2xl border border-slate-800 shadow-inner">
                <p class="text-[10px] font-bold text-slate-500 mb-2 uppercase tracking-widest">Xem trước hiển thị:</p>
                <p class="text-2xl font-black text-white tracking-tight">
                    <template x-if="settings.currencyFormat === 'symbol'"><span>₫1,234,567</span></template>
                    <template x-if="settings.currencyFormat === 'code'"><span>VND 1,234,567</span></template>
                    <template x-if="settings.currencyFormat === 'name'"><span>1,234,567 Việt Nam Đồng</span></template>
                </p>
            </div>
        </div>
    </div>

    {{-- 3. Number Format --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <h2 class="text-xl font-black text-white tracking-tight mb-8">Định dạng Số</h2>

        <div class="space-y-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Dấu phân cách hàng
                    nghìn</label>
                <div class="relative">
                    <select x-model="settings.numberFormat"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="comma">Dấu phẩy (1,234,567.89)</option>
                        <option value="period">Dấu chấm (1.234.567,89)</option>
                        <option value="space">Khoảng trắng (1 234 567.89)</option>
                        <option value="none">Không có (1234567.89)</option>
                    </select>
                    <i
                        class="fas fa-calculator absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
            </div>

            {{-- Number Preview --}}
            <div class="p-5 bg-slate-950/50 rounded-2xl border border-slate-800 shadow-inner">
                <p class="text-[10px] font-bold text-slate-500 mb-2 uppercase tracking-widest">Ví dụ con số:</p>
                <p class="text-2xl font-black text-white tracking-tight font-mono">
                    <span x-show="settings.numberFormat === 'comma'">1,234,567.89</span>
                    <span x-show="settings.numberFormat === 'period'">1.234.567,89</span>
                    <span x-show="settings.numberFormat === 'space'">1 234 567.89</span>
                    <span x-show="settings.numberFormat === 'none'">1234567.89</span>
                </p>
            </div>
        </div>
    </div>

    {{-- 4. Measurement & Date/Time --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Measurement --}}
        <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
            <h2 class="text-lg font-black text-white tracking-tight mb-6">Hệ thống Đo lường</h2>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Đơn vị đo</label>
                <div class="relative">
                    <select x-model="settings.measurementUnit"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="metric">Hệ mét (cm, kg, km)</option>
                        <option value="imperial">Hệ Anh/Mỹ (inch, pound, mile)</option>
                    </select>
                    <i
                        class="fas fa-ruler-combined absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>

        {{-- Date Preferences --}}
        <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
            <h2 class="text-lg font-black text-white tracking-tight mb-6">Ngày & Thời gian</h2>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Ngày đầu
                    tuần</label>
                <div class="relative">
                    <select x-model="settings.firstDayOfWeek"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="monday">Thứ Hai</option>
                        <option value="sunday">Chủ Nhật</option>
                        <option value="saturday">Thứ Bảy</option>
                    </select>
                    <i
                        class="fas fa-calendar-day absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end pt-4">
        <button @click="notify('Cài đặt bản địa hóa đã được lưu!')"
            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-95">
            <i class="fas fa-save mr-2"></i> Lưu thay đổi
        </button>
    </div>
</div>
