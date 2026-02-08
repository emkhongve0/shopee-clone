@extends('layouts.app')

@section('title', 'Đăng nhập hệ thống')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12" x-data="{ showPass: false }">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-2xl mb-4">
                    <i class="fas fa-lock text-[#ee4d2d] text-2xl"></i>
                </div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Chào mừng trở lại</h2>
                <p class="mt-2 text-sm text-gray-500">Vui lòng đăng nhập để tiếp tục mua sắm</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 text-green-700 text-sm rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700 text-sm rounded">
                    Thông tin đăng nhập không chính xác.
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label for="email" class="text-xs font-bold text-gray-700 uppercase ml-1">Địa chỉ Email</label>
                        <div class="relative">
                            <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="email" name="email" type="email" required
                                class="w-full h-12 pl-11 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#ee4d2d] focus:ring-2 focus:ring-orange-100 transition-all outline-none text-sm"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between items-center ml-1">
                            <label for="password" class="text-xs font-bold text-gray-700 uppercase">Mật khẩu</label>
                        </div>
                        <div class="relative">
                            <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input :type="showPass ? 'text' : 'password'" id="password" name="password" required
                                class="w-full h-12 pl-11 pr-11 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#ee4d2d] focus:ring-2 focus:ring-orange-100 transition-all outline-none text-sm"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="far" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-2 text-right">
                        <button type="button"
                            @click="
                                Swal.fire({
                                    title: 'Quên mật khẩu?',
                                    text: 'Nhập email của bạn để nhận mật khẩu tạm thời',
                                    input: 'email',
                                    inputPlaceholder: 'email@example.com',
                                    showCancelButton: true,
                                    confirmButtonText: 'Gửi mật khẩu',
                                    confirmButtonColor: '#ee4d2d',
                                    cancelButtonText: 'Hủy',
                                    showLoaderOnConfirm: true,
                                    preConfirm: (email) => {
    return fetch('{{ route('password.temp') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json' // Ép Laravel trả về JSON thay vì HTML lỗi
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => {
        if (response.status === 429) {
            // Nếu bị chặn spam, ném ra thông báo thân thiện
            throw new Error('Bạn thao tác quá nhanh! Vui lòng đợi 1 phút rồi thử lại.');
        }
        if (!response.ok) {
            return response.json().then(err => { throw new Error(err.message || 'Lỗi server') });
        }
        return response.json();
    })
    .catch(error => {
        // Hiển thị dòng thông báo màu đỏ ngay trên popup
        Swal.showValidationMessage(error.message);
    });
},
                                    allowOutsideClick: () => !Swal.isLoading()
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.dispatchEvent(new CustomEvent('notify', {
                                            detail: {
                                                message: result.value.message,
                                                type: 'success'
                                            }
                                        }));

                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Thành công!',
                                            text: 'Mật khẩu mới đã được gửi. Bạn hãy kiểm tra hộp thư đến hoặc thư rác (Spam) nhé!',
                                            confirmButtonColor: '#ee4d2d',
                                        });
                                    }
                                })
                            "
                            class="text-sm text-gray-500 hover:text-[#ee4d2d] transition-colors font-medium">
                            Quên mật khẩu?
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-[#ee4d2d] focus:ring-[#ee4d2d] border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 font-medium">Ghi nhớ đăng nhập</label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full h-12 bg-[#ee4d2d] text-white rounded-xl font-bold shadow-lg shadow-orange-100 hover:bg-[#d73211] transform active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập
                    </button>
                </div>
            </form>



            {{-- PHẦN ĐĂNG NHẬP GOOGLE MỚI THÊM --}}
            <div class="space-y-4">
                <div class="relative flex items-center justify-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                    <span class="relative bg-white px-4 text-xs text-gray-400 uppercase">Hoặc đăng nhập bằng</span>
                </div>

                <a href="{{ route('auth.google') }}"
                    class="w-full h-12 border border-gray-200 rounded-xl flex items-center justify-center gap-3 hover:bg-gray-50 transition-all transform active:scale-[0.98] shadow-sm">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                    <span class="text-sm font-bold text-gray-700">Tiếp tục với Google</span>
                </a>
            </div>

            <div class="text-center pt-2">
                <p class="text-sm text-gray-500">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-[#ee4d2d] font-bold hover:underline">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
@endsection
