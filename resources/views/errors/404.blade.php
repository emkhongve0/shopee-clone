@extends('layouts.app')

@section('title', 'Trang không tồn tại')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center bg-white px-4">
        <div class="text-center">
            {{-- Phần Minh Họa (Icon Con Vật) --}}
            <div class="relative mb-8">
                <div class="text-[120px] md:text-[180px] font-bold text-gray-100 select-none">
                    404
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    {{-- SVG Chú Mèo (Có thể thay bằng hình ảnh bất kỳ) --}}
                    <svg class="w-24 h-24 md:w-32 md:h-32 text-[#ee4d2d] animate-bounce" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        <path d="M8 10a2 2 0 100-4 2 2 0 000 4zM16 10a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path d="M12 13s-1.5 1-3 1-3-1-3-1"></path>
                    </svg>
                </div>
            </div>

            {{-- Thông điệp --}}
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                Ối! Có vẻ bạn đang đi lạc?
            </h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Trang bạn đang tìm kiếm không tồn tại hoặc đã được chuyển sang một "vùng đất" khác. Hãy để chúng mình đưa
                bạn về nhà nhé!
            </p>

            {{-- Nút điều hướng --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}"
                    class="px-8 py-3 bg-[#ee4d2d] text-white font-medium rounded-sm shadow-md hover:bg-[#d73211] transition-all transform active:scale-95">
                    Về trang chủ
                </a>
                <button onclick="window.history.back()"
                    class="px-8 py-3 border border-gray-300 text-gray-600 font-medium rounded-sm hover:bg-gray-50 transition-all">
                    Quay lại trang trước
                </button>
            </div>

            {{-- Trang trí thêm --}}
            <div class="mt-12">
                <p class="text-xs text-gray-400 uppercase tracking-widest">Mã lỗi: 404 - Not Found</p>
            </div>
        </div>
    </div>
@endsection
