@extends('layouts.app')

@section('title', 'Tham gia cùng chúng tôi')

@section('content')
    {{-- Giữ nguyên x-data cũ để không làm hỏng tính năng ẩn hiện mật khẩu --}}
    <div class="min-h-screen flex flex-col lg:flex-row bg-white" x-data="{ showPass: false, showConfirm: false }">

        <div class="flex-1 flex items-center justify-center p-6 lg:p-16">
            <div class="w-full max-w-md space-y-8">
                {{-- Logo & Brand --}}
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-[#ee4d2d] to-[#ff7337] rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" />
                            <path d="M2 17L12 22L22 17" />
                            <path d="M2 12L12 17L22 12" />
                        </svg>
                    </div>
                    <span class="text-xl font-black text-gray-900 tracking-tighter">ShopMart</span>
                </div>

                <div class="space-y-2">
                    <h1 class="text-3xl font-black text-gray-900">Bắt đầu mua sắm ngay</h1>
                    <p class="text-gray-500">Tham gia cùng hàng nghìn người dùng mỗi ngày</p>
                </div>

                {{-- SOCIAL LOGIN - ĐÃ CẬP NHẬT GOOGLE --}}
                <div class="grid grid-cols-2 gap-4">
                    {{-- Nút Google đã được gắn link --}}
                    <a href="{{ route('auth.google') }}  "
                        class="flex items-center justify-center gap-2 h-11 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all font-medium text-sm">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5"> Google
                    </a>
                </div>

                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-gray-100"></span>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase"><span
                            class="bg-white px-2 text-gray-400">Hoặc dùng Email</span></div>
                </div>

                {{-- FORM ĐĂNG KÝ - GIỮ NGUYÊN LOGIC VALIDATION CŨ --}}
                <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                    @csrf

                    @if (session('error'))
                        <div class="p-3 rounded-lg bg-red-50 text-red-500 text-sm font-medium border border-red-100">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                        </div>
                    @endif

                    {{-- 1. HỌ TÊN --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase ml-1">Họ tên của bạn</label>
                        <div class="relative">
                            <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}"
                                class="w-full h-12 pl-11 bg-gray-50 border {{ $errors->has('name') ? 'border-red-500 bg-red-50' : 'border-gray-200' }} rounded-xl focus:border-[#ee4d2d] focus:ring-2 focus:ring-orange-100 transition-all outline-none">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-[11px] ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 2. EMAIL --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase ml-1">Địa chỉ Email</label>
                        <div class="relative">
                            <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="email" name="email" placeholder="john@example.com" value="{{ old('email') }}"
                                class="w-full h-12 pl-11 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500 bg-red-50' : 'border-gray-200' }} rounded-xl focus:border-[#ee4d2d] focus:ring-2 focus:ring-orange-100 transition-all outline-none">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-[11px] ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 3. MẬT KHẨU --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase ml-1">Mật khẩu</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input :type="showPass ? 'text' : 'password'" name="password" placeholder="••••••••"
                                class="w-full h-12 pl-11 pr-11 bg-gray-50 border {{ $errors->has('password') ? 'border-red-500 bg-red-50' : 'border-gray-200' }} rounded-xl focus:border-[#ee4d2d] focus:ring-2 focus:ring-orange-100 transition-all outline-none">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 outline-none">
                                <i class="far" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[11px] ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 4. NHẬP LẠI MẬT KHẨU --}}
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase ml-1">Xác nhận mật khẩu</label>
                        <div class="relative">
                            <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                                placeholder="••••••••"
                                class="w-full h-12 pl-11 pr-11 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#ee4d2d] focus:ring-2 focus:ring-orange-100 transition-all outline-none">
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 outline-none">
                                <i class="far" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <p class="text-[11px] text-gray-500">
                        Bằng cách nhấn Đăng ký, bạn đồng ý với <a href="#" class="text-[#ee4d2d] hover:underline">Điều
                            khoản</a> và <a href="#" class="text-[#ee4d2d] hover:underline">Chính sách bảo mật</a> của
                        chúng tôi.
                    </p>

                    <button type="submit"
                        class="w-full h-12 bg-[#ee4d2d] text-white rounded-xl font-bold shadow-lg shadow-orange-100 hover:bg-[#d73211] transform active:scale-[0.98] transition-all">
                        Đăng ký ngay
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500">
                    Đã có tài khoản? <a href="{{ route('login') }}" class="text-[#ee4d2d] font-bold hover:underline">Đăng
                        nhập</a>
                </p>
            </div>
        </div>

        {{-- Phần Banner bên phải GIỮ NGUYÊN --}}
        <div class="hidden lg:flex flex-1 relative bg-[#ee4d2d] overflow-hidden">
            <img src="https://images.unsplash.com/photo-1579546929662-711aa81148cf?q=80&w=1080"
                class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
            <div class="relative z-10 flex flex-col justify-center items-center p-20 text-center">
                <div
                    class="max-w-lg space-y-6 backdrop-blur-md bg-white/10 p-12 rounded-[40px] border border-white/20 shadow-2xl">
                    <div class="inline-flex p-5 bg-white/20 rounded-3xl">
                        <i class="fas fa-bolt text-4xl text-white"></i>
                    </div>
                    <h2 class="text-4xl font-black text-white">Khám phá hành trình mới</h2>
                    <p class="text-lg text-white/80 leading-relaxed">
                        Tham gia cộng đồng mua sắm thông minh. Nhận ưu đãi độc quyền và quản lý đơn hàng dễ dàng hơn bao giờ
                        hết.
                    </p>
                    <div class="flex items-center justify-center gap-10 pt-6">
                        <div class="text-center">
                            <div class="text-3xl font-black text-white">10k+</div>
                            <div class="text-xs text-white/60 uppercase">Khách hàng</div>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <div class="text-3xl font-black text-white">50+</div>
                            <div class="text-xs text-white/60 uppercase">Đối tác</div>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <div class="text-3xl font-black text-white">99%</div>
                            <div class="text-xs text-white/60 uppercase">Hài lòng</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
