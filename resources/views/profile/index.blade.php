@extends('layouts.app')

@section('title', 'Hồ sơ của tôi')

@section('content')
    <div class="min-h-screen bg-[#f5f5f5] py-8">
        <div class="max-w-[1000px] mx-auto px-4"> {{-- Thu nhỏ chiều rộng lại một chút để tập trung hơn --}}
            <div class="space-y-6"> {{-- Tạo khoảng cách đều giữa các Card --}}

                {{-- KHỐI 1: HỒ SƠ CỦA TÔI --}}
                <main class="bg-white rounded-sm shadow-sm p-8 border border-gray-200">
                    <div class="border-b border-gray-100 pb-5 mb-8">
                        <h1 class="text-lg font-medium text-gray-900 uppercase">Hồ sơ của tôi</h1>
                        <p class="text-sm text-gray-500 mt-1">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                    </div>

                    <div class="flex flex-col-reverse md:flex-row gap-12">
                        {{-- Form Thông tin (Bên trái) --}}
                        <div class="flex-1">
                            @if (session('success_info'))
                                <div
                                    class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 rounded-sm text-sm flex items-center shadow-sm">
                                    <i class="fas fa-check-circle mr-2"></i>{{ session('success_info') }}
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                                @csrf

                                {{-- Email (Tên đăng nhập) --}}
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-4 text-right text-sm text-gray-500 capitalize">Email</div>
                                    <div class="col-span-8 text-sm text-gray-900 font-medium">{{ $user->email }}</div>
                                </div>

                                {{-- Họ và tên --}}
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <label class="col-span-4 text-right text-sm text-gray-500 capitalize">Họ và tên</label>
                                    <div class="col-span-8">
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                            class="w-full h-10 px-3 border border-gray-300 rounded-sm focus:border-gray-500 outline-none text-sm transition-all @error('name') border-red-500 @enderror"
                                            required>
                                        @error('name')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Số điện thoại --}}
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <label class="col-span-4 text-right text-sm text-gray-500 capitalize">Số điện
                                        thoại</label>
                                    <div class="col-span-8">
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                            placeholder="090xxxxxxxx"
                                            class="w-full h-10 px-3 border border-gray-300 rounded-sm focus:border-gray-500 outline-none text-sm transition-all @error('phone') border-red-500 @enderror">
                                        @error('phone')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Giới tính --}}
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <label class="col-span-4 text-right text-sm text-gray-500 capitalize">Giới tính</label>
                                    <div class="col-span-8 flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="gender" value="male"
                                                class="w-4 h-4 accent-[#ee4d2d]"
                                                {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">Nam</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="gender" value="female"
                                                class="w-4 h-4 accent-[#ee4d2d]"
                                                {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">Nữ</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="gender" value="other"
                                                class="w-4 h-4 accent-[#ee4d2d]"
                                                {{ old('gender', $user->gender) == 'other' ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700">Khác</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Nút Lưu --}}
                                <div class="grid grid-cols-12 gap-4 pt-4">
                                    <div class="col-span-4"></div>
                                    <div class="col-span-8">
                                        <button type="submit"
                                            class="px-6 py-2 bg-[#ee4d2d] text-white text-sm rounded-sm shadow-sm hover:bg-[#d73211] transition-all min-w-[100px]">
                                            Lưu
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Phần Ảnh Đại Diện (Bên phải) --}}
                        <div
                            class="w-full md:w-72 flex flex-col items-center justify-start md:border-l border-gray-100 px-4">
                            <div
                                class="w-24 h-24 rounded-full bg-gray-100 border border-gray-200 overflow-hidden mb-4 shadow-inner">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=128"
                                    class="w-full h-full object-cover">
                            </div>
                            <button
                                class="px-4 py-2 border border-gray-300 text-sm text-gray-600 rounded-sm hover:bg-gray-50 transition-colors shadow-sm">
                                Chọn ảnh
                            </button>
                            <div class="text-xs text-gray-400 mt-4 text-center leading-loose">
                                Dụng lượng file tối đa 1 MB<br>Định dạng: .JPEG, .PNG
                            </div>
                        </div>
                    </div>
                </main>

                {{-- KHỐI 2: BẢO MẬT & MẬT KHẨU --}}
                <section class="bg-white rounded-sm shadow-sm p-8 border border-gray-200">
                    <div class="border-b border-gray-100 pb-5 mb-8">
                        <h2 class="text-lg font-medium text-gray-900 uppercase flex items-center gap-2">
                            Mật khẩu
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho
                            người khác</p>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <div class="max-w-[70%]"> {{-- Giới hạn độ rộng của form mật khẩu để thẳng hàng với form trên --}}
                            <div class="space-y-6">
                                {{-- Mật khẩu mới --}}
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <label class="col-span-4 text-right text-sm text-gray-500 capitalize">Mật khẩu
                                        mới</label>
                                    <div class="col-span-8">
                                        <input type="password" name="password"
                                            class="w-full h-10 px-3 border border-gray-300 rounded-sm focus:border-gray-500 outline-none text-sm transition-all @error('password') border-red-500 @enderror"
                                            placeholder="Nhập mật khẩu mới" required>
                                        @error('password')
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Xác nhận mật khẩu mới --}}
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <label class="col-span-4 text-right text-sm text-gray-500 capitalize">Xác nhận mật
                                        khẩu</label>
                                    <div class="col-span-8">
                                        <input type="password" name="password_confirmation"
                                            class="w-full h-10 px-3 border border-gray-300 rounded-sm focus:border-gray-500 outline-none text-sm transition-all"
                                            placeholder="Nhập lại mật khẩu mới" required>
                                    </div>
                                </div>

                                {{-- Nút cập nhật mật khẩu --}}
                                <div class="grid grid-cols-12 gap-4 pt-4">
                                    <div class="col-span-4"></div>
                                    <div class="col-span-8">
                                        <button type="submit"
                                            class="px-6 py-2 bg-[#ee4d2d] text-white text-sm rounded-sm shadow-sm hover:bg-[#d73211] transition-all">
                                            Thay đổi mật khẩu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
