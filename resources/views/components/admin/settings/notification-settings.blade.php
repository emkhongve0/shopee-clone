<div class="space-y-6 pb-20" x-data="{
    notifications: [
        { id: 'new-orders', label: 'Đơn hàng mới', description: 'Thông báo khi có đơn hàng mới được đặt', email: true, sms: true, push: true, icon: 'fa-shopping-cart' },
        { id: 'order-status', label: 'Cập nhật đơn hàng', description: 'Thông báo khi trạng thái đơn hàng thay đổi', email: true, sms: false, push: true, icon: 'fa-box-open' },
        { id: 'low-stock', label: 'Cảnh báo kho hàng', description: 'Thông báo khi sản phẩm sắp hết hàng', email: true, sms: false, push: true, icon: 'fa-exclamation-triangle' },
        { id: 'new-customers', label: 'Khách hàng mới', description: 'Thông báo khi có người dùng đăng ký mới', email: true, sms: false, push: false, icon: 'fa-user-plus' },
        { id: 'payment-received', label: 'Thanh toán', description: 'Thông báo khi giao dịch được xử lý thành công', email: true, sms: false, push: true, icon: 'fa-file-invoice-dollar' },
        { id: 'customer-reviews', label: 'Đánh giá khách hàng', description: 'Thông báo về các nhận xét sản phẩm mới', email: true, sms: false, push: false, icon: 'fa-comment-alt' },
        { id: 'system-alerts', label: 'Cảnh báo hệ thống', description: 'Thông báo bảo mật và vận hành quan trọng', email: true, sms: true, push: true, icon: 'fa-shield-alt' }
    ],
    notify(msg) { alert(msg); }
}">

    {{-- 1. Header Card --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-bell text-blue-400"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-white tracking-tight uppercase">Cấu hình thông báo</h2>
                <p class="text-xs text-slate-500 mt-1">Chọn cách bạn muốn nhận thông tin về các sự kiện quan trọng trong
                    hệ thống.</p>
            </div>
        </div>
    </div>

    {{-- 2. Notification Matrix Table --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-900/50 border-b border-slate-800 text-slate-400 font-black text-[10px] uppercase tracking-widest">
                        <th class="py-5 px-6">Loại thông báo</th>
                        <th class="py-5 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <i class="fas fa-envelope text-blue-400"></i> Email
                            </div>
                        </th>
                        <th class="py-5 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <i class="fas fa-comment-alt text-emerald-400"></i> SMS
                            </div>
                        </th>
                        <th class="py-5 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <i class="fas fa-bell text-purple-400"></i> Push
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <template x-for="notif in notifications" :key="notif.id">
                        <tr class="hover:bg-slate-900/30 transition-colors group">
                            <td class="py-5 px-6">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-500 group-hover:border-blue-500/50 group-hover:text-blue-400 transition-all">
                                        <i :class="'fas ' + notif.icon"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white uppercase tracking-tight"
                                            x-text="notif.label"></p>
                                        <p class="text-[10px] text-slate-500 mt-1 leading-relaxed"
                                            x-text="notif.description"></p>
                                    </div>
                                </div>
                            </td>

                            {{-- Email Toggle --}}
                            <td class="py-5 px-6 text-center">
                                <div class="flex justify-center">
                                    <button
                                        @click="notif.email = !notif.email; notify('Cập nhật Email cho ' + notif.label)"
                                        :class="notif.email ? 'bg-blue-600' : 'bg-slate-700'"
                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none">
                                        <span :class="notif.email ? 'translate-x-5' : 'translate-x-1'"
                                            class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform shadow-sm"></span>
                                    </button>
                                </div>
                            </td>

                            {{-- SMS Toggle --}}
                            <td class="py-5 px-6 text-center">
                                <div class="flex justify-center">
                                    <button @click="notif.sms = !notif.sms; notify('Cập nhật SMS cho ' + notif.label)"
                                        :class="notif.sms ? 'bg-emerald-600' : 'bg-slate-700'"
                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none">
                                        <span :class="notif.sms ? 'translate-x-5' : 'translate-x-1'"
                                            class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform shadow-sm"></span>
                                    </button>
                                </div>
                            </td>

                            {{-- Push Toggle --}}
                            <td class="py-5 px-6 text-center">
                                <div class="flex justify-center">
                                    <button
                                        @click="notif.push = !notif.push; notify('Cập nhật Push cho ' + notif.label)"
                                        :class="notif.push ? 'bg-purple-600' : 'bg-slate-700'"
                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none">
                                        <span :class="notif.push ? 'translate-x-5' : 'translate-x-1'"
                                            class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform shadow-sm"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. Info Alert --}}
    <div class="bg-gradient-to-r from-blue-600/10 to-purple-600/10 border border-blue-500/20 rounded-2xl p-5">
        <div class="flex items-start gap-4">
            <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-black text-blue-300 uppercase tracking-widest">Yêu cầu cấu hình SMS</p>
                <p class="text-[10px] text-slate-500 leading-relaxed font-bold">
                    Để nhận thông báo qua SMS, vui lòng đảm bảo bạn đã cấu hình nhà cung cấp dịch vụ (Twilio, Vonage...)
                    trong tab <span class="text-blue-400">Tích hợp</span>.
                </p>
            </div>
        </div>
    </div>
</div>
