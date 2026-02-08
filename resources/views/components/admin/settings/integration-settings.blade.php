<div class="space-y-6 pb-20" x-data="{
    apiKey: "{{ config('services.stripe.key') }}",
apiSecret: "{{ config('services.stripe.secret') }}",
    showSecret: false,
    integrations: [
        { id: 'google-analytics', name: 'Google Analytics', description: 'Theo dõi lưu lượng truy cập và hành vi người dùng', enabled: true, icon: 'fa-chart-bar', color: 'text-orange-400' },
        { id: 'mailchimp', name: 'Mailchimp', description: 'Tiếp thị email và tự động hóa chiến dịch', enabled: true, icon: 'fa-envelope-open-text', color: 'text-yellow-400' },
        { id: 'slack', name: 'Slack', description: 'Thông báo hệ thống và giao tiếp nội bộ', enabled: true, icon: 'fa-comments', color: 'text-purple-400' },
        { id: 'aws-s3', name: 'AWS S3', description: 'Lưu trữ đám mây cho các tệp đa phương tiện', enabled: false, icon: 'fa-cloud', color: 'text-blue-400' }
    ],
    webhooks: [
        { id: '1', event: 'order.created', url: 'https://api.example.com/webhooks/orders', enabled: true },
        { id: '2', event: 'payment.succeeded', url: 'https://api.example.com/webhooks/payments', enabled: true }
    ],
    copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        alert('Đã sao chép vào bộ nhớ tạm!');
    },
    notify(msg) { alert(msg); }
}">

    {{-- 1. API Keys --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-key text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">API Keys</h2>
        </div>

        <div class="space-y-6">
            {{-- Public Key --}}
            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Public API
                    Key</label>
                <div class="flex gap-2">
                    <input type="text" :value="apiKey" readonly
                        class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white font-mono text-xs focus:outline-none shadow-inner">
                    <button @click="copyToClipboard(apiKey)"
                        class="px-4 bg-slate-900 border border-slate-700 text-slate-400 rounded-xl hover:text-white hover:bg-slate-800 transition-all">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            {{-- Secret Key --}}
            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Secret API
                    Key</label>
                <div class="flex gap-2">
                    <input :type="showSecret ? 'text' : 'password'" :value="apiSecret" readonly
                        class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white font-mono text-xs focus:outline-none shadow-inner">
                    <button @click="showSecret = !showSecret"
                        class="px-4 bg-slate-900 border border-slate-700 text-slate-400 rounded-xl hover:text-white hover:bg-slate-800 transition-all text-xs font-bold uppercase tracking-widest">
                        <span x-text="showSecret ? 'Ẩn' : 'Hiện'"></span>
                    </button>
                    <button @click="copyToClipboard(apiSecret)"
                        class="px-4 bg-slate-900 border border-slate-700 text-slate-400 rounded-xl hover:text-white hover:bg-slate-800 transition-all">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div class="flex justify-between items-center pt-2">
                <div class="flex items-center gap-2 text-orange-400/80">
                    <i class="fas fa-exclamation-triangle text-xs"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest">Giữ bí mật Secret Key của bạn</p>
                </div>
                <button @click="notify('API Keys đã được cấp mới thành công!')"
                    class="text-[10px] font-black uppercase tracking-widest text-orange-500 hover:text-orange-400 transition-colors">
                    <i class="fas fa-sync-alt mr-1"></i> Cấp lại mã mới
                </button>
            </div>
        </div>
    </div>

    {{-- 2. Third-Party Integrations --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                <i class="fas fa-plug text-purple-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Dịch vụ bên thứ ba</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-for="int in integrations" :key="int.id">
                <div
                    class="p-5 bg-slate-900/40 rounded-2xl border border-slate-800 hover:border-slate-700 transition-all group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-slate-950 flex items-center justify-center text-xl shadow-inner border border-slate-800">
                                <i :class="'fas ' + int.icon + ' ' + int.color"></i>
                            </div>
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-white text-sm" x-text="int.name"></h3>
                                    <template x-if="int.enabled">
                                        <span
                                            class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[8px] font-black uppercase tracking-widest">Đã
                                            kết nối</span>
                                    </template>
                                </div>
                                <p class="text-[10px] text-slate-500 leading-relaxed truncate md:whitespace-normal"
                                    x-text="int.description"></p>
                            </div>
                        </div>
                        {{-- Custom Switch --}}
                        <button
                            @click="int.enabled = !int.enabled; notify(int.name + (int.enabled ? ' đã kết nối' : ' đã ngắt kết nối'))"
                            :class="int.enabled ? 'bg-emerald-500' : 'bg-slate-700'"
                            class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors focus:outline-none">
                            <span :class="int.enabled ? 'translate-x-5' : 'translate-x-1'"
                                class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform"></span>
                        </button>
                    </div>
                    <button x-show="int.enabled"
                        class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        <i class="fas fa-cog mr-2"></i> Cấu hình dịch vụ
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- 3. Webhooks --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <i class="fas fa-network-wired text-emerald-400"></i>
                </div>
                <h2 class="text-xl font-black text-white tracking-tight">Webhooks</h2>
            </div>
            <button @click="notify('Tính năng thêm Webhook đang được phát triển')"
                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/20">
                Thêm Webhook
            </button>
        </div>

        <div class="space-y-3">
            <template x-for="webhook in webhooks" :key="webhook.id">
                <div class="flex items-center justify-between p-4 bg-slate-900/40 rounded-2xl border border-slate-800">
                    <div class="space-y-1">
                        <div class="flex items-center gap-3">
                            <p class="text-xs font-black text-blue-400 font-mono" x-text="webhook.event"></p>
                            <span
                                :class="webhook.enabled ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' :
                                    'bg-slate-700/50 text-slate-500 border-slate-700'"
                                class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border"
                                x-text="webhook.enabled ? 'Hoạt động' : 'Tắt'"></span>
                        </div>
                        <p class="text-[10px] text-slate-600 font-mono" x-text="webhook.url"></p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="px-3 py-1.5 rounded-lg border border-slate-700 text-[10px] font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition-all">Test</button>
                        <button
                            class="px-3 py-1.5 rounded-lg border border-slate-700 text-[10px] font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition-all">Sửa</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- 4. API Documentation --}}
    <div
        class="bg-gradient-to-br from-blue-600/10 to-purple-600/10 border border-blue-500/20 rounded-3xl p-8 relative overflow-hidden group">
        {{-- Background Decoration --}}
        <div
            class="absolute -right-10 -bottom-10 opacity-5 group-hover:opacity-10 transition-all transform group-hover:scale-110">
            <i class="fas fa-code text-9xl text-white"></i>
        </div>

        <div class="flex items-start gap-6 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/20 flex items-center justify-center text-2xl">
                <i class="fas fa-book text-blue-400"></i>
            </div>
            <div class="space-y-3 flex-1">
                <h3 class="text-lg font-black text-blue-300 uppercase tracking-widest">Tài liệu API đã sẵn sàng</h3>
                <p class="text-sm text-slate-400 leading-relaxed max-w-xl">
                    Tích hợp hệ thống của bạn với các ứng dụng khác thông qua giao diện API mạnh mẽ. Xem hướng dẫn chi
                    tiết về các điểm cuối (endpoints) và tham số yêu cầu.
                </p>
                <button
                    class="mt-4 px-6 py-3 border border-blue-500/30 text-blue-400 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-500/10 transition-all flex items-center gap-2">
                    <i class="fas fa-external-link-alt"></i> Xem tài liệu hướng dẫn
                </button>
            </div>
        </div>
    </div>
</div>
