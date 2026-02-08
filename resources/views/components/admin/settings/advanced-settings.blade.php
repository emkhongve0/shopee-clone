<div class="space-y-6 pb-20" x-data="{
    debugMode: false,
    maintenanceMode: false,
    showResetModal: false,
    notify(msg) {
        alert(msg);
        {{-- Thay bằng toast component nếu bạn có --}}
    }
}">

    {{-- 1. Debug Mode --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6 transition-all">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                <i class="fas fa-bug text-purple-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Chế độ Debug</h2>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-2xl border border-slate-800">
                <div class="space-y-1">
                    <p class="font-bold text-white text-sm">Bật Debug Mode</p>
                    <p class="text-xs text-slate-500">Hiển thị thông báo lỗi chi tiết và nhật ký hệ thống</p>
                </div>
                {{-- Custom Switch --}}
                <button @click="debugMode = !debugMode; if(debugMode) notify('Đã bật chế độ Debug')"
                    :class="debugMode ? 'bg-purple-500' : 'bg-slate-700'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                    <span :class="debugMode ? 'translate-x-6' : 'translate-x-1'"
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </div>

            <div x-show="debugMode" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                class="p-4 bg-orange-500/10 border border-orange-500/20 rounded-2xl flex gap-3" x-cloak>
                <i class="fas fa-exclamation-triangle text-orange-400 mt-1"></i>
                <p class="text-xs text-orange-400 leading-relaxed">
                    <strong>Cảnh báo:</strong> Chế độ debug đang hoạt động. Thông tin lỗi chi tiết sẽ hiển thị cho người
                    dùng. Chỉ nên sử dụng trong môi trường phát triển (Development).
                </p>
            </div>
        </div>
    </div>

    {{-- 2. Cache Management --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center">
                <i class="fas fa-bolt text-yellow-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Quản lý Bộ nhớ đệm (Cache)</h2>
        </div>

        <div class="space-y-6">
            <p class="text-xs text-slate-500">Xóa dữ liệu đệm để giải phóng bộ nhớ và khắc phục các sự cố hiển thị. Tốc
                độ tải trang có thể chậm hơn một chút sau khi xóa.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([['label' => 'Application Cache', 'size' => '45.2 MB', 'color' => 'blue'], ['label' => 'Database Cache', 'size' => '128 MB', 'color' => 'emerald'], ['label' => 'Image Cache', 'size' => '234 MB', 'color' => 'purple']] as $cache)
                    <div
                        class="p-5 bg-slate-900/50 rounded-2xl border border-slate-800 group hover:border-slate-700 transition-all">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-600 mb-1">
                            {{ $cache['label'] }}</p>
                        <p class="text-2xl font-black text-white mb-4">{{ $cache['size'] }}</p>
                        <button @click="notify('Đã xóa {{ $cache['label'] }}')"
                            class="w-full py-2 rounded-xl border border-slate-700 text-slate-400 text-xs font-bold hover:bg-slate-800 hover:text-white transition-all">
                            Dọn dẹp
                        </button>
                    </div>
                @endforeach
            </div>

            <button @click="notify('Toàn bộ bộ nhớ đệm đã được làm sạch')"
                class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-black text-sm shadow-lg shadow-blue-500/20 transition-all active:scale-[0.98]">
                <i class="fas fa-trash-alt mr-2"></i> XÓA TẤT CẢ CACHE
            </button>
        </div>
    </div>

    {{-- 3. Maintenance Mode --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-power-off text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Chế độ Bảo trì</h2>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-2xl border border-slate-800">
                <div class="space-y-1">
                    <p class="font-bold text-white text-sm">Kích hoạt bảo trì</p>
                    <p class="text-xs text-slate-500">Tạm thời đóng cửa hàng để nâng cấp</p>
                </div>
                <button
                    @click="maintenanceMode = !maintenanceMode; notify(maintenanceMode ? 'Cửa hàng đã ngoại tuyến' : 'Cửa hàng đã hoạt động trở lại')"
                    :class="maintenanceMode ? 'bg-blue-500' : 'bg-slate-700'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                    <span :class="maintenanceMode ? 'translate-x-6' : 'translate-x-1'"
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </div>

            <div x-show="maintenanceMode" class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex gap-3"
                x-cloak>
                <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                <p class="text-xs text-blue-300">Khách hàng sẽ thấy trang thông báo bảo trì. Bạn vẫn có quyền truy cập
                    vào bảng quản trị.</p>
            </div>
        </div>
    </div>

    {{-- 4. Danger Zone --}}
    <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Danger Zone</h2>
        </div>

        <div class="space-y-6">
            <p class="text-xs text-slate-500 leading-relaxed">Khôi phục tất cả cài đặt hệ thống về trạng thái ban đầu.
                Thao tác này <strong>KHÔNG</strong> xóa dữ liệu (sản phẩm, đơn hàng), nhưng sẽ reset toàn bộ cấu hình
                thiết lập của bạn.</p>

            <button @click="showResetModal = true"
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-red-900/20 active:scale-95">
                <i class="fas fa-undo-alt mr-2"></i> Khôi phục cài đặt gốc
            </button>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div x-show="showResetModal"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
        <div @click.away="showResetModal = false"
            class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-8 shadow-2xl">
            <div class="w-16 h-16 bg-red-500/10 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                <i class="fas fa-exclamation-circle text-3xl text-red-500"></i>
            </div>
            <h3 class="text-xl font-black text-white text-center mb-2">Bạn có chắc chắn?</h3>
            <p class="text-slate-500 text-center text-sm mb-8">Hành động này không thể hoàn tác. Mọi cấu hình hệ thống
                sẽ bị xóa sạch.</p>

            <div class="flex gap-3">
                <button @click="showResetModal = false"
                    class="flex-1 py-3 rounded-xl bg-slate-800 text-slate-300 font-bold hover:bg-slate-700 transition-all">
                    Hủy bỏ
                </button>
                <button @click="showResetModal = false; notify('Hệ thống đang được thiết lập lại...')"
                    class="flex-1 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-900/20">
                    Xác nhận Reset
                </button>
            </div>
        </div>
    </div>
</div>
