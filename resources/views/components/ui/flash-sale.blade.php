<section class="bg-white rounded-sm shadow-sm overflow-hidden" x-data="{}">
    {{-- Header Flash Sale --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-100">
        <div class="flex items-center gap-4">
            <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/fb563daed39babe5672e463d63af5974.png"
                alt="Flash Sale" class="h-8">
            {{-- Đồng hồ đếm ngược --}}
            <div class="flex items-center gap-1 font-bold">
                <span id="hour" class="bg-black text-white px-1.5 py-0.5 rounded text-sm">00</span>
                <span>:</span>
                <span id="minute" class="bg-black text-white px-1.5 py-0.5 rounded text-sm">00</span>
                <span>:</span>
                <span id="second" class="bg-black text-white px-1.5 py-0.5 rounded text-sm">00</span>
            </div>
        </div>
        <a href="#" class="text-[#ee4d2d] text-sm flex items-center">Xem tất cả <i
                class="fas fa-chevron-right ml-1 text-xs"></i></a>
    </div>

    {{-- Danh sách sản phẩm từ DB --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-0.5 bg-gray-100">
        @foreach ($flashSaleProducts as $item)
            {{-- Thêm onclick để điều hướng thẳng đến trang chi tiết bằng Slug --}}
            <div class="bg-white p-3 relative group cursor-pointer transition-all hover:z-10 hover:shadow-md"
                onclick="window.location.href='/san-pham/{{ $item->slug }}'">

                {{-- Tính toán % giảm giá tự động nếu DB không có sẵn --}}
                @php
                    $salePercent =
                        $item->old_price > 0 ? round((($item->old_price - $item->price) / $item->old_price) * 100) : 0;
                @endphp

                {{-- Badge Giảm giá --}}
                <div class="absolute top-0 right-0 z-10">
                    <div
                        class="bg-yellow-400 text-[#ee4d2d] text-[10px] font-bold px-1 py-0.5 flex flex-col items-center">
                        <span>{{ $salePercent }}%</span>
                        <span class="text-white uppercase">Giảm</span>
                    </div>
                </div>

                {{-- Badge HOT/LIMITED --}}
                @if ($item->badge)
                    <div
                        class="absolute top-2 left-2 z-10 {{ $item->badge == 'HOT' ? 'bg-red-600' : 'bg-orange-500' }} text-white text-[10px] px-1 rounded-sm font-bold">
                        {{ $item->badge }}
                    </div>
                @endif

                <div class="aspect-square mb-2 overflow-hidden">
                    <img src="{{ $item->image }}"
                        class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
                </div>

                <div class="text-center">
                    <div class="text-[#ee4d2d] text-lg font-medium">đ{{ number_format($item->price, 0, ',', '.') }}
                    </div>

                    @if ($item->old_price)
                        <div class="text-gray-400 text-sm line-through">
                            đ{{ number_format($item->old_price, 0, ',', '.') }}</div>
                    @endif

                    {{-- Thanh tiến trình đã bán --}}
                    <div class="mt-2 h-4 bg-orange-200 rounded-full relative overflow-hidden">
                        <div
                            class="absolute inset-0 flex items-center justify-center z-10 text-[10px] text-white font-bold uppercase">
                            @if ($item->sold_ratio > 90)
                                Vừa mới cháy hàng
                            @else
                                Đã bán {{ $item->sold_ratio }}%
                            @endif
                        </div>
                        <div class="bg-gradient-to-r from-orange-500 to-red-600 h-full transition-all duration-1000"
                            style="width: {{ $item->sold_ratio }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
