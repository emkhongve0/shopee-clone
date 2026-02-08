<div class="space-y-6 pb-20" x-data="{
    theme: 'dark',
    primaryColor: '#3b82f6',
    secondaryColor: '#8b5cf6',
    customCss: '',
    notify(msg) { alert(msg); } {{-- Thay bằng Toast component nếu có --}}
}">

    {{-- 1. Logo & Branding --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <i class="fas fa-image text-blue-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Logo & Thương hiệu</h2>
        </div>

        <div class="space-y-8">
            {{-- Logo Upload --}}
            <div class="space-y-4">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Logo Công ty</label>
                <div class="flex items-center gap-6">
                    <div
                        class="w-32 h-32 bg-slate-900/50 border-2 border-dashed border-slate-700 rounded-2xl flex flex-col items-center justify-center group hover:border-blue-500/50 transition-all cursor-pointer">
                        <i
                            class="fas fa-cloud-upload-alt text-3xl text-slate-600 group-hover:text-blue-500 transition-all"></i>
                    </div>
                    <div class="space-y-3">
                        <button @click="notify('Tính năng tải lên Logo đang được phát triển')"
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                            <i class="fas fa-upload mr-2"></i> Tải lên Logo
                        </button>
                        <p class="text-[10px] text-slate-500 leading-relaxed">
                            Khuyến nghị: 512x512px, định dạng PNG hoặc SVG.<br>Dung lượng tối đa: 2MB.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Favicon Upload --}}
            <div class="space-y-4">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Favicon (Biểu tượng
                    trình duyệt)</label>
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 bg-slate-900/50 border-2 border-dashed border-slate-700 rounded-2xl flex items-center justify-center group hover:border-blue-500/50 transition-all cursor-pointer">
                        <i class="fas fa-file-image text-slate-600 group-hover:text-blue-500"></i>
                    </div>
                    <div class="space-y-2">
                        <button @click="notify('Tính năng tải lên Favicon đang được phát triển')"
                            class="px-4 py-2 rounded-xl border border-slate-700 text-slate-300 text-xs font-bold hover:bg-slate-800 hover:text-white transition-all">
                            Tải lên Favicon
                        </button>
                        <p class="text-[10px] text-slate-500">
                            Khuyến nghị: 32x32px, định dạng ICO hoặc PNG.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Theme Selection --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                <i class="fas fa-desktop text-purple-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Chế độ hiển thị</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Light Mode Card --}}
            <button @click="theme = 'light'"
                :class="theme === 'light' ? 'border-blue-500 bg-blue-500/5 ring-1 ring-blue-500' :
                    'border-slate-800 bg-slate-900/40 hover:border-slate-700'"
                class="p-6 rounded-2xl border-2 transition-all group text-left relative overflow-hidden">
                <div class="flex flex-col items-center gap-4 relative z-10">
                    <div
                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-lg transition-transform group-hover:rotate-12">
                        <i class="fas fa-sun text-slate-900 text-xl"></i>
                    </div>
                    <span class="font-black text-white text-sm uppercase tracking-widest">Giao diện Sáng</span>
                    {{-- Mock UI Preview --}}
                    <div class="w-full h-20 bg-white rounded-xl border border-slate-200 p-2 space-y-2">
                        <div class="h-2 w-1/2 bg-slate-200 rounded"></div>
                        <div class="h-8 w-full bg-slate-100 rounded"></div>
                    </div>
                </div>
            </button>

            {{-- Dark Mode Card --}}
            <button @click="theme = 'dark'"
                :class="theme === 'dark' ? 'border-blue-500 bg-blue-500/5 ring-1 ring-blue-500' :
                    'border-slate-800 bg-slate-900/40 hover:border-slate-700'"
                class="p-6 rounded-2xl border-2 transition-all group text-left relative overflow-hidden">
                <div class="flex flex-col items-center gap-4 relative z-10">
                    <div
                        class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center shadow-lg transition-transform group-hover:-rotate-12">
                        <i class="fas fa-moon text-blue-400 text-xl"></i>
                    </div>
                    <span class="font-black text-white text-sm uppercase tracking-widest">Giao diện Tối</span>
                    {{-- Mock UI Preview --}}
                    <div class="w-full h-20 bg-slate-950 rounded-xl border border-slate-800 p-2 space-y-2">
                        <div class="h-2 w-1/2 bg-slate-800 rounded"></div>
                        <div class="h-8 w-full bg-slate-900 rounded"></div>
                    </div>
                </div>
            </button>
        </div>
    </div>

    {{-- 3. Color Palette --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-pink-500/10 flex items-center justify-center">
                <i class="fas fa-palette text-pink-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Bảng màu hệ thống</h2>
        </div>

        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Primary Color --}}
                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Màu chủ đạo
                        (Primary)</label>
                    <div class="flex gap-4">
                        <input type="color" x-model="primaryColor"
                            class="w-16 h-16 rounded-xl cursor-pointer bg-slate-900 border-2 border-slate-700 p-1">
                        <div class="flex-1 space-y-2">
                            <input type="text" x-model="primaryColor"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white font-mono text-sm focus:border-blue-500 outline-none">
                            <p class="text-[10px] text-slate-500">Dùng cho các nút bấm chính, liên kết và các điểm nhấn.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Secondary Color --}}
                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Màu phụ
                        (Secondary)</label>
                    <div class="flex gap-4">
                        <input type="color" x-model="secondaryColor"
                            class="w-16 h-16 rounded-xl cursor-pointer bg-slate-900 border-2 border-slate-700 p-1">
                        <div class="flex-1 space-y-2">
                            <input type="text" x-model="secondaryColor"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white font-mono text-sm focus:border-blue-500 outline-none">
                            <p class="text-[10px] text-slate-500">Dùng cho các thành phần bổ trợ và hiệu ứng highlight.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Color Preview --}}
            <div class="p-6 bg-slate-950/50 rounded-2xl border border-slate-800">
                <p class="text-xs font-bold text-slate-400 mb-4">Xem trước dải màu</p>
                <div class="flex gap-3 h-16">
                    <div class="flex-1 rounded-xl shadow-lg transition-colors"
                        :style="'background-color: ' + primaryColor"></div>
                    <div class="flex-1 rounded-xl shadow-lg transition-colors"
                        :style="'background-color: ' + secondaryColor"></div>
                    <div class="flex-[2] rounded-xl shadow-lg transition-all"
                        :style="'background: linear-gradient(135deg, ' + primaryColor + ', ' + secondaryColor + ')'">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Custom CSS --}}
    <div class="bg-slate-800/40 border border-slate-700/50 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center">
                <i class="fas fa-code text-orange-400"></i>
            </div>
            <h2 class="text-xl font-black text-white tracking-tight">Tùy chỉnh CSS</h2>
        </div>

        <div class="space-y-4">
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Mã CSS bổ sung (Tùy
                chọn)</label>
            <textarea x-model="customCss"
                class="w-full bg-slate-950 border border-slate-800 rounded-2xl p-5 text-blue-400 font-mono text-sm min-h-[200px] focus:border-blue-500 outline-none shadow-inner"
                placeholder="/* Nhập mã CSS của bạn tại đây */&#10;.custom-header {&#10;  background: rgba(0,0,0,0.5);&#10;}"></textarea>
            <div class="flex gap-2 p-3 bg-blue-500/5 border border-blue-500/10 rounded-xl">
                <i class="fas fa-info-circle text-blue-400 text-xs mt-0.5"></i>
                <p class="text-[10px] text-slate-500">Thêm CSS tùy chỉnh để ghi đè giao diện mặc định. Hãy thận trọng
                    khi sử dụng.</p>
            </div>
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end pt-4">
        <button @click="notify('Cài đặt giao diện đã được lưu thành công!')"
            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-95">
            Lưu thay đổi
        </button>
    </div>
</div>
