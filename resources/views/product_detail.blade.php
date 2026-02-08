@extends('layouts.app')

@section('title', ($product->name ?? 'Chi tiết sản phẩm') . ' - ShopMart')

@section('content')
    <div class="min-h-screen bg-gray-50 font-['Inter']" x-data="{
        selectedImage: 0,
        quantity: 1,
        activeTab: 'description',
        stock: {{ $product->stock ?? 10 }},
    
        // Logic yêu thích rút gọn
        isLiked: {{ $product->isLiked() ? 'true' : 'false' }},
    
        toggleWishlist() {
            fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: {{ $product->id }} })
                })
                .then(res => {
                    if (res.status === 401) {
                        // Chỉ hiện Modal khi chưa đăng nhập
                        this.$dispatch('open-global-modal', {
                            title: 'Chào bạn!',
                            message: 'Vui lòng đăng nhập để lưu sản phẩm vào danh sách yêu thích.',
                            type: 'warning',
                            onConfirm: () => { window.location.href = '{{ route('login') }}' }
                        });
                        return;
                    }
                    return res.json();
                })
                .then(data => {
                    if (data && data.status === 'success') {
                        this.isLiked = data.isLiked;
    
                        // Hiển thị thông báo Toast nhanh (Sử dụng hàm pushNotification từ globalAppState)
                        this.pushNotification(
                            data.isLiked ? 'Đã thêm vào yêu thích!' : 'Đã xóa khỏi yêu thích!',
                            'success'
                        );
                    }
                })
                .catch(err => console.error('Lỗi:', err));
        }
    }">

        <main class="max-w-[1440px] mx-auto px-3 sm:px-4 md:px-6 py-3 sm:py-4 md:py-6">
            <div class="space-y-3 sm:space-y-4 md:space-y-6">

                <nav class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm overflow-x-auto scrollbar-hide py-1">
                    <a href="/" class="text-gray-600 hover:text-orange-500 transition-colors whitespace-nowrap">Trang
                        chủ</a>
                    <i class="fas fa-chevron-right text-[10px] text-gray-400 flex-shrink-0"></i>
                    <a href="#"
                        class="text-gray-600 hover:text-orange-500 transition-colors whitespace-nowrap">{{ $product->category->name ?? 'Điện tử' }}</a>
                    <i class="fas fa-chevron-right text-[10px] text-gray-400 flex-shrink-0"></i>
                    <span class="text-gray-900 font-medium line-clamp-1 whitespace-nowrap">{{ $product->name }}</span>
                </nav>

                <div class="bg-white rounded-lg p-3 sm:p-4 md:p-6 shadow-sm border border-gray-100">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-6">

                        <div class="lg:col-span-5">
                            <div class="space-y-3 sm:space-y-4">

                                {{-- THÊM: Bao bọc vùng ảnh chính bằng x-data="imageZoom" --}}
                                <div x-data="imageZoom" class="relative z-20">

                                    {{-- SỬA: Thêm sự kiện chuột và class group vào thẻ div chứa ảnh gốc --}}
                                    <div @mousemove="handleMouseMove($event)" @mouseleave="handleMouseLeave()"
                                        @mouseenter="handleMouseMove($event)" {{-- Cập nhật ngay khi chuột vừa vào --}}
                                        class="aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-inner relative group cursor-crosshair">

                                        @php
                                            // ... (giữ nguyên đoạn xử lý $images cũ của bạn) ...
                                            $images = is_array($product->images)
                                                ? $product->images
                                                : json_decode($product->images, true) ?? [];
                                            if (empty($images)) {
                                                $images = [$product->image ?? ''];
                                            }
                                        @endphp
                                        @foreach ($images as $index => $img)
                                            <img x-show="selectedImage === {{ $index }}"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 scale-95" src="{{ $img }}"
                                                class="w-full h-full object-cover">
                                        @endforeach

                                        {{-- THÊM (Tùy chọn): Icon kính lúp nhỏ để gợi ý người dùng --}}
                                        <div
                                            class="absolute bottom-2 right-2 bg-white/80 p-1.5 rounded-full text-gray-500 opacity-50 group-hover:opacity-0 transition-opacity pointer-events-none">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>

                                    {{-- THÊM MỚI: KHUNG KẾT QUẢ ZOOM --}}
                                    {{-- Chỉ hiện trên màn hình lớn (lg:block) vì mobile không có chỗ --}}
                                    <div x-show="showZoom" x-cloak x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="hidden lg:block absolute top-0 left-[105%] z-50 w-[500px] h-[500px] bg-white border border-gray-200 shadow-2xl rounded-lg overflow-hidden pointer-events-none"
                                        :style="`background-image: url('${zoomImageSrc}');
                                                                  background-position: ${bgPosX}% ${bgPosY}%;
                                                                  background-size: ${zoomLevel * 100}%;
                                                                  background-repeat: no-repeat;`">
                                    </div>
                                </div>

                                <div class="grid grid-cols-4 gap-2 sm:gap-3">
                                    @foreach ($images as $index => $img)
                                        <button @click="selectedImage = {{ $index }}"
                                            class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 transition-all active:scale-95"
                                            :class="selectedImage === {{ $index }} ?
                                                'border-orange-500 ring-2 ring-orange-100' :
                                                'border-gray-100 hover:border-orange-300'">
                                            <img src="{{ $img }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-7">
                            <div class="space-y-3 sm:space-y-4 md:space-y-5">
                                <h1
                                    class="text-base sm:text-lg md:text-xl lg:text-2xl font-bold text-gray-900 leading-snug">
                                    {{ $product->name }}
                                </h1>

                                <div
                                    class="flex flex-wrap items-center gap-2 sm:gap-4 pb-3 sm:pb-5 border-b border-gray-100">
                                    <div class="flex items-center gap-1.5">
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-star text-yellow-400 text-sm sm:text-lg"></i>
                                            <span
                                                class="text-base sm:text-lg font-bold text-gray-900">{{ $product->rating ?? '4.8' }}</span>
                                        </div>
                                        <span class="text-xs sm:text-sm text-gray-500">
                                            ({{ number_format($product->reviews_count ?? 2847) }} đánh giá)
                                        </span>
                                    </div>
                                    <div class="hidden sm:block h-4 w-px bg-gray-300"></div>
                                    <div class="text-xs sm:text-sm text-gray-600">
                                        Đã bán <span
                                            class="font-bold text-gray-900">{{ number_format($product->sold ?? 5234) }}</span>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 sm:p-5 border border-gray-100">
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                                        @if ($product->discount)
                                            <div
                                                class="bg-orange-500 text-white px-2 py-0.5 rounded-md text-[10px] sm:text-xs font-black shadow-sm uppercase">
                                                Giảm {{ $product->discount }}%
                                            </div>
                                        @endif
                                        <span class="text-2xl sm:text-3xl md:text-4xl font-black text-orange-500">
                                            {{ number_format($product->price, 0, ',', '.') }}đ
                                        </span>
                                        @if ($product->original_price)
                                            <span class="text-sm sm:text-lg text-gray-400 line-through">
                                                {{ number_format($product->original_price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="text-xs sm:text-sm text-gray-500">Kho hàng:</span>
                                    <span class="text-xs sm:text-sm font-bold text-gray-900">
                                        {{ $product->stock }} sản phẩm có sẵn
                                    </span>
                                </div>
                                <div x-data="{ qty: 1 }">
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs sm:text-sm font-bold text-gray-900 uppercase tracking-wider">Số
                                            lượng</label>
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                            <div
                                                class="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden bg-white">
                                                <button @click="quantity > 1 ? quantity-- : 1"
                                                    class="w-12 h-12 flex items-center justify-center hover:bg-gray-50 text-gray-500 border-r border-gray-200 active:bg-gray-100">
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                                <input type="text" x-model="quantity" readonly
                                                    class="w-14 text-center text-sm font-black border-none focus:ring-0 bg-transparent">
                                                <button @click="quantity < stock ? quantity++ : stock"
                                                    class="w-12 h-12 flex items-center justify-center hover:bg-gray-50 text-gray-500 border-l border-gray-200 active:bg-gray-100">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </div>
                                            <span class="text-xs text-gray-400">Tối đa: {{ $product->stock }} sản
                                                phẩm</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-3">
                                        <button {{-- QUAN TRỌNG: Truyền thêm biến qty vào tham số thứ 2 --}}
                                            @click="addToCart({
                                                id: {{ $product->id }},
                                                name: '{{ addslashes($product->name) }}'
                                            }, qty)"
                                            class="flex items-center justify-center gap-2 px-6 py-3 border border-[#ee4d2d] text-[#ee4d2d] bg-[#ffeee8] hover:bg-[#fff5f1] transition-colors rounded-sm shadow-sm">
                                            <i class="fas fa-cart-plus"></i>
                                            Thêm vào giỏ hàng
                                        </button>
                                        <button
                                            class="flex-1 bg-orange-500 hover:bg-orange-600 text-white h-12 sm:h-14 rounded-lg text-sm font-bold transition-all shadow-lg shadow-orange-100 active:scale-95">
                                            Mua ngay
                                        </button>
                                        {{-- Thay thế nút trái tim cũ bằng nút này --}}
                                        <button @click="toggleWishlist()"
                                            class="h-12 w-12 sm:w-14 border-2 rounded-lg flex items-center justify-center transition-all active:scale-95 shadow-sm"
                                            :class="isLiked ? 'border-red-500 bg-red-50 text-red-500' :
                                                'border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-500'">

                                            {{-- Đổi icon dựa trên trạng thái --}}
                                            <i :class="isLiked ? 'fas fa-heart text-xl scale-110' : 'far fa-heart text-xl'"
                                                class="transition-transform duration-300"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                        <div class="border-b border-gray-200 overflow-x-auto scrollbar-hide">
                            <div class="flex min-w-max sm:min-w-0">
                                @foreach (['description' => 'Mô tả', 'specs' => 'Thông số', 'reviews' => 'Đánh giá'] as $key => $label)
                                    <button @click="activeTab = '{{ $key }}'"
                                        class="flex-1 px-5 sm:px-6 py-4 text-xs sm:text-sm font-bold uppercase tracking-widest transition-all border-b-2 whitespace-nowrap"
                                        :class="activeTab === '{{ $key }}' ?
                                            'text-orange-500 border-orange-500 bg-white' :
                                            'text-gray-400 border-transparent hover:text-gray-900'">
                                        {{ $label }}
                                        @if ($key === 'reviews')
                                            ({{ number_format($product->reviews_count ?? 2847) }})
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div x-show="activeTab === 'description'" x-transition class="space-y-4">
                                <h3 class="text-sm sm:text-lg font-bold text-gray-900 border-l-4 border-orange-500 pl-3">Mô
                                    tả
                                    sản phẩm</h3>
                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                                    {{ $product->description }}</p>
                            </div>

                            <div x-show="activeTab === 'specs'" x-transition>
                                <h3
                                    class="text-sm sm:text-lg font-bold text-gray-900 mb-4 border-l-4 border-orange-500 pl-3">
                                    Thông số kỹ thuật</h3>
                                <div class="border border-gray-100 rounded-lg overflow-hidden">
                                    <table class="w-full text-xs sm:text-sm">
                                        <tbody>
                                            @php
                                                $specs = is_array($product->specifications)
                                                    ? $product->specifications
                                                    : json_decode($product->specifications, true) ?? [];
                                            @endphp
                                            @foreach ($specs as $key => $val)
                                                <tr class="{{ $loop->index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                                                    <td
                                                        class="px-4 py-3 font-bold text-gray-900 w-1/3 border-b border-gray-100">
                                                        {{ $key }}</td>
                                                    <td class="px-4 py-3 text-gray-700 border-b border-gray-100">
                                                        {{ $val }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @includeIf('components.related-products')
                </div>
        </main>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('imageZoom', () => ({
                    showZoom: false,
                    zoomImageSrc: '',
                    bgPosX: 0,
                    bgPosY: 0,
                    zoomLevel: 2.5, // Độ phóng đại (2.5 lần)

                    // Hàm xử lý khi di chuyển chuột trên ảnh gốc
                    handleMouseMove(e) {
                        const container = e.currentTarget;
                        // Lấy tọa độ và kích thước của khung chứa ảnh gốc
                        const rect = container.getBoundingClientRect();

                        // Tính toán vị trí con trỏ chuột so với góc trên bên trái của ảnh
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;

                        // Chuyển đổi sang tỷ lệ phần trăm (0% -> 100%)
                        // Math.max và Math.min để đảm bảo không bị trượt ra ngoài lề một chút
                        const xPercent = Math.max(0, Math.min(100, (x / container.offsetWidth) * 100));
                        const yPercent = Math.max(0, Math.min(100, (y / container.offsetHeight) * 100));

                        // Cập nhật vị trí background cho khung zoom
                        this.bgPosX = xPercent;
                        this.bgPosY = yPercent;

                        // Lấy nguồn ảnh đang hiển thị để làm nền cho khung zoom
                        // (Phòng trường hợp người dùng đang chọn ảnh khác trong album)
                        this.zoomImageSrc = container.querySelector('img[x-show="true"]')?.src || container
                            .querySelector('img').src;
                        this.showZoom = true;
                    },

                    // Ẩn khung zoom khi chuột rời đi
                    handleMouseLeave() {
                        this.showZoom = false;
                    }
                }));
            });
        </script>
    @endpush
@endsection
