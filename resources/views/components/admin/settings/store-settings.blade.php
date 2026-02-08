<div class="space-y-6 pb-20" x-data="{
    formData: {
        storeName: 'My E-Commerce Store',
        businessAddress: '123 Business Street, Suite 100',
        city: 'New York',
        state: 'NY',
        zipCode: '10001',
        country: 'US',
        phone: '+1 (555) 123-4567',
        email: 'contact@mystore.com',
        currency: 'USD',
        taxRate: '8.5',
        invoicePrefix: 'INV',
        invoiceNumber: '1000'
    },
    notify(msg) { alert(msg); }
}">

    {{-- 1. Store Information --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-store text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Thông tin cửa hàng</h2>
        </div>

        <div class="space-y-6">
            {{-- Store Name --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tên cửa hàng</label>
                <input type="text" x-model="formData.storeName"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder:text-slate-600 focus:border-blue-500 outline-none transition-all shadow-inner"
                    placeholder="Nhập tên cửa hàng...">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Contact Email --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Email liên
                        hệ</label>
                    <input type="email" x-model="formData.email"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner"
                        placeholder="contact@mystore.com">
                </div>

                {{-- Phone Number --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Số điện
                        thoại</label>
                    <input type="text" x-model="formData.phone"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner"
                        placeholder="+84 ...">
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Business Address --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                <i class="fas fa-map-marked-alt text-purple-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Địa chỉ kinh doanh</h2>
        </div>

        <div class="space-y-6">
            {{-- Street Address --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Địa chỉ chi
                    tiết</label>
                <textarea x-model="formData.businessAddress" rows="2"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Thành
                        phố</label>
                    <input type="text" x-model="formData.city"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tỉnh /
                        Bang</label>
                    <input type="text" x-model="formData.state"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Mã Bưu
                        điện</label>
                    <input type="text" x-model="formData.zipCode"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Quốc gia</label>
                    <div class="relative">
                        <select x-model="formData.country"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="VN">Vietnam</option>
                            <option value="JP">Japan</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Currency & Tax --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-emerald-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Tiền tệ & Thuế</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tiền tệ mặc
                    định</label>
                <div class="relative">
                    <select x-model="formData.currency"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer">
                        <option value="USD">USD - US Dollar</option>
                        <option value="VND">VND - Việt Nam Đồng</option>
                        <option value="EUR">EUR - Euro</option>
                    </select>
                    <i
                        class="fas fa-money-bill-wave absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none text-xs"></i>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Thuế suất mặc định
                    (%)</label>
                <input type="number" step="0.1" x-model="formData.taxRate"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner font-mono">
            </div>
        </div>
    </div>

    {{-- 4. Invoice Settings --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center">
                <i class="fas fa-file-invoice text-orange-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Cài đặt hóa đơn</h2>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Tiền tố hóa
                        đơn</label>
                    <input type="text" x-model="formData.invoicePrefix"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Số bắt
                        đầu</label>
                    <input type="number" x-model="formData.invoiceNumber"
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner font-mono">
                </div>
            </div>

            <div
                class="p-5 bg-slate-950/50 rounded-2xl border border-slate-800 flex items-center justify-between group">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Mẫu số hóa đơn:</p>
                <p class="text-xl font-black text-blue-400 tracking-tighter font-mono"
                    x-text="formData.invoicePrefix + '-' + formData.invoiceNumber"></p>
            </div>
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end pt-4">
        <button @click="notify('Cài đặt cửa hàng đã được cập nhật thành công!')"
            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-95">
            <i class="fas fa-save mr-2"></i> Lưu thay đổi
        </button>
    </div>
</div>
