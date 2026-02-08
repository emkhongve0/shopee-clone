<section class="mt-12">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wider">Sản phẩm tương tự</h2>
        <a href="/" class="text-sm text-orange-500 hover:underline">Xem tất cả</a>
    </div>

    {{-- Tận dụng luôn Product Grid đã có --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
        {{-- Ở đây bạn có thể dùng một vòng lặp giả hoặc truyền dữ liệu thực --}}
        @if (isset($relatedProducts))
            @foreach ($relatedProducts as $product)
                <x-ui.product-card :product="$product" />
            @endforeach
        @else
            <p class="col-span-full text-gray-400 text-sm py-4">Đang cập nhật các sản phẩm liên quan...</p>
        @endif
    </div>
</section>
