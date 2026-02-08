@extends('layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')
    <div class="flex justify-center min-h-screen bg-white md:bg-gray-50 md:py-10">
        <div class="w-full max-w-[360px] bg-white flex flex-col md:rounded-2xl md:shadow-xl md:border md:border-gray-100 overflow-hidden"
            x-data="{ fullName: '', email: '', password: '', confirmPassword: '' }">

            <div class="px-6 pt-12 pb-6">
                <div class="mb-8">
                    <div
                        class="w-12 h-12 bg-[#ee4d2d] rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                </div>

                <div class="space-y-1">
                    <h1 class="text-[22px] font-bold text-gray-900 leading-tight">Tạo tài khoản mới</h1>
                    <p class="text-[14px] text-gray-500 leading-relaxed">Nhập thông tin của bạn để bắt đầu</p>
                </div>
            </div>

            <div class="px-6 pb-12 flex-1">
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[13px] font-semibold text-gray-700 ml-1">Họ và tên</label>
                        <input type="text" name="name" x-model="fullName" placeholder="Nguyễn Văn A"
                            class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm focus:border-[#ee4d2d] focus:ring-0 transition-all"
                            required />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-semibold text-gray-700 ml-1">Email</label>
                        <input type="email" name="email" x-model="email" placeholder="example@gmail.com"
                            class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm focus:border-[#ee4d2d] focus:ring-0 transition-all"
                            required />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-semibold text-gray-700 ml-1">Mật khẩu</label>
                        <input type="password" name="password" x-model="password" placeholder="••••••••"
                            class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm focus:border-[#ee4d2d] focus:ring-0 transition-all"
                            required />
                    </div>

                    <button type="submit"
                        class="w-full h-12 bg-[#ee4d2d] text-white hover:bg-[#d73211] rounded-xl font-bold text-sm mt-6 shadow-lg shadow-orange-200 transition-all transform active:scale-95">
                        Tạo tài khoản
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-[14px] text-gray-500">
                        Bạn đã có tài khoản?
                        <a href="{{ route('login') }}" class="text-[#ee4d2d] font-bold hover:underline">Đăng nhập</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
