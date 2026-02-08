<div class="space-y-6 pb-20" x-data="{
    showApiKeys: {},
    paymentMethods: [
        { id: 'stripe', name: 'Stripe', description: 'Chấp nhận thẻ tín dụng và ví điện tử toàn cầu', enabled: true, icon: 'fa-cc-stripe', color: 'text-blue-400', requiresApiKey: true, apiKey: 'sk_test_••••••••••••••••1234', testMode: true },
        { id: 'paypal', name: 'PayPal', description: 'Thanh toán qua cổng PayPal và Express Checkout', enabled: true, icon: 'fa-cc-paypal', color: 'text-blue-500', requiresApiKey: true, apiKey: 'live_••••••••••••••••5678', testMode: false },
        { id: 'bank-transfer', name: 'Chuyển khoản', description: 'Thanh toán trực tiếp qua số tài khoản ngân hàng', enabled: true, icon: 'fa-university', color: 'text-emerald-400', requiresApiKey: false },
        { id: 'cod', name: 'COD', description: 'Thanh toán tiền mặt khi nhận hàng', enabled: true, icon: 'fa-money-bill-wave', color: 'text-emerald-500', requiresApiKey: false },
        { id: 'crypto', name: 'Tiền điện tử', description: 'Chấp nhận Bitcoin, Ethereum và các loại crypto khác', enabled: false, icon: 'fa-bitcoin', color: 'text-orange-400', requiresApiKey: true, testMode: false }
    ],
    toggleApiKey(id) {
        this.showApiKeys[id] = !this.showApiKeys[id];
    },
    notify(msg) { alert(msg); }
}">

    {{-- 1. Header Card --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-credit-card text-blue-400"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-white tracking-tight uppercase">Phương thức thanh toán</h2>
                <p class="text-xs text-slate-500 mt-1">Cấu hình các nhà cung cấp thanh toán và thông tin xác thực API.
                </p>
            </div>
        </div>
    </div>

    {{-- 2. Payment Methods List --}}
    <div class="grid gap-4">
        <template x-for="method in paymentMethods" :key="method.id">
            <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all">
                <div class="space-y-6">
                    {{-- Method Header --}}
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-3xl shadow-inner">
                                <i :class="'fab ' + method.icon + ' ' + method.color"
                                    x-show="method.icon.includes('fa-cc')"></i>
                                <i :class="'fas ' + method.icon + ' ' + method.color"
                                    x-show="!method.icon.includes('fa-cc')"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-black text-white tracking-tight" x-text="method.name"></h3>
                                    {{-- Status Badges --}}
                                    <template x-if="method.enabled">
                                        <span
                                            class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[8px] font-black uppercase tracking-widest">Hoạt
                                            động</span>
                                    </template>
                                    <template x-if="!method.enabled">
                                        <span
                                            class="px-2 py-0.5 rounded bg-slate-700/50 text-slate-500 border border-slate-700 text-[8px] font-black uppercase tracking-widest">Tắt</span>
                                    </template>
                                    <template x-if="method.testMode">
                                        <span
                                            class="px-2 py-0.5 rounded bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[8px] font-black uppercase tracking-widest">Test
                                            Mode</span>
                                    </template>
                                </div>
                                <p class="text-xs text-slate-500" x-text="method.description"></p>
                            </div>
                        </div>
                        {{-- Toggle Switch --}}
                        <button
                            @click="method.enabled = !method.enabled; notify(method.name + (method.enabled ? ' đã bật' : ' đã tắt'))"
                            :class="method.enabled ? 'bg-blue-600' : 'bg-slate-700'"
                            class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors focus:outline-none shadow-inner">
                            <span :class="method.enabled ? 'translate-x-6' : 'translate-x-1'"
                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                        </button>
                    </div>

                    {{-- API Configuration Section --}}
                    <div x-show="method.enabled && method.requiresApiKey"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        class="space-y-5 pt-6 border-t border-slate-800" x-cloak>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">API Key
                                / Secret</label>
                            <div class="flex gap-2">
                                <div class="flex-1 relative">
                                    <input :type="showApiKeys[method.id] ? 'text' : 'password'" :value="method.apiKey"
                                        readonly
                                        class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-4 pr-11 py-3 text-white font-mono text-xs focus:outline-none shadow-inner">
                                    <button @click="toggleApiKey(method.id)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors">
                                        <i class="fas"
                                            :class="showApiKeys[method.id] ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                <button @click="notify('Yêu cầu cấp lại mã cho ' + method.name)"
                                    class="px-4 bg-slate-900 border border-slate-700 text-slate-400 rounded-xl hover:text-white hover:bg-slate-800 transition-all">
                                    <i class="fas fa-sync-alt text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <template x-if="method.testMode !== undefined">
                            <div
                                class="flex items-center justify-between p-4 bg-slate-900/50 rounded-2xl border border-slate-800">
                                <div class="space-y-0.5">
                                    <p class="text-sm font-bold text-white">Chế độ kiểm thử (Test Mode)</p>
                                    <p class="text-[10px] text-slate-500">Sử dụng để thực hiện các giao dịch giả lập khi
                                        phát triển</p>
                                </div>
                                <button
                                    @click="method.testMode = !method.testMode; notify('Đã chuyển ' + method.name + ' sang ' + (method.testMode ? 'Test Mode' : 'Live Mode'))"
                                    :class="method.testMode ? 'bg-orange-500' : 'bg-slate-700'"
                                    class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors focus:outline-none">
                                    <span :class="method.testMode ? 'translate-x-5' : 'translate-x-1'"
                                        class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform"></span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- 3. General Payment Configuration --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <i class="fas fa-dollar-sign text-emerald-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Cấu hình chung</h2>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Giá trị đơn hàng
                        tối thiểu</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">$</span>
                        <input type="number" value="10.00"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Giá trị đơn hàng
                        tối đa</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">$</span>
                        <input type="number" value="10000.00"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white focus:border-blue-500 outline-none transition-all shadow-inner">
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                @foreach ([['title' => 'Tự động thanh toán (Auto-capture)', 'desc' => 'Tự động thu tiền ngay sau khi giao dịch được ủy quyền'], ['title' => 'Lưu phương thức thanh toán', 'desc' => 'Cho phép khách hàng lưu thông tin thẻ cho lần mua sau']] as $config)
                    <div
                        class="flex items-center justify-between p-4 bg-slate-900/50 rounded-2xl border border-slate-800">
                        <div class="space-y-0.5">
                            <p class="text-sm font-bold text-white">{{ $config['title'] }}</p>
                            <p class="text-[10px] text-slate-500">{{ $config['desc'] }}</p>
                        </div>
                        <button
                            class="bg-blue-600 relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors focus:outline-none">
                            <span
                                class="translate-x-5 inline-block h-3 w-3 transform rounded-full bg-white transition-transform"></span>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 4. Compliance Info --}}
    <div class="bg-gradient-to-br from-blue-600/10 to-purple-600/10 border border-blue-500/20 rounded-3xl p-6">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shield-check text-blue-400"></i>
            </div>
            <div class="space-y-1">
                <p class="text-sm font-black text-blue-300 uppercase tracking-widest">Yêu cầu tuân thủ PCI DSS</p>
                <p class="text-[10px] text-slate-500 leading-relaxed font-bold">
                    Đảm bảo việc tích hợp thanh toán của bạn đáp ứng các tiêu chuẩn PCI DSS để xử lý dữ liệu thẻ tín
                    dụng một cách an toàn. Vui lòng kiểm tra lại cấu hình Webhook và SSL.
                </p>
            </div>
        </div>
    </div>
</div>
