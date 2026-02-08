@props(['product'])

@php
    // Logic mới: Tự động tạo link đến trang chi tiết dựa trên ID sản phẩm
    $href = route('product.detail', $product->slug);
@endphp

<div
    class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg hover:border-[#ee4d2d] transition-all duration-300 group flex flex-col h-full relative">

    <a href="{{ $href }}" class="relative aspect-square overflow-hidden bg-gray-100 block">
        <img src="{{ $product->image ?? 'https://placehold.jp/24/cccccc/ffffff/400x400.png?text=No+Image' }}"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            onerror="this.onerror=null;this.src='https://placehold.jp/24/cccccc/ffffff/400x400.png?text=Error';" />

        @if (isset($product->discount) && $product->discount > 0)
            <div
                class="absolute top-2 left-2 bg-[#ee4d2d] text-white px-2 py-1 rounded-md text-xs font-bold shadow-sm z-10">
                -{{ $product->discount }}%
            </div>
        @endif


    </a>

    <div class="p-3 flex flex-col flex-1">
        <a href="{{ $href }}" class="block mb-2">
            <h3
                class="text-sm font-medium text-gray-800 line-clamp-2 h-10 leading-5 group-hover:text-[#ee4d2d] transition-colors">
                {{ $product->name }}
            </h3>
        </a>

        <div class="flex items-center gap-1.5 mb-3">
            <div class="flex items-center text-yellow-400 text-[10px]">
                @for ($i = 0; $i < 5; $i++)
                    <i class="fas fa-star {{ $i < ($product->rating ?? 5) ? '' : 'text-gray-200' }}"></i>
                @endfor
            </div>
            <span class="text-xs text-gray-400">|</span>
            <span class="text-[11px] text-gray-500">Đã bán {{ number_format($product->sold ?? 0) }}</span>
        </div>

        <div class="flex items-baseline gap-2 mb-4">
            <span class="text-lg font-bold text-[#ee4d2d]">
                ${{ number_format($product->price, 2) }}
            </span>
            @if (isset($product->original_price) && $product->original_price > $product->price)
                <span class="text-xs text-gray-400 line-through">
                    ${{ number_format($product->original_price, 2) }}
                </span>
            @endif
        </div>

        <button
            @click.stop.prevent="addToCart({
                id: {{ $product->id }},
                name: '{{ addslashes($product->name) }}',
                price: {{ $product->price }},
                image: '{{ $product->image }}'
            })"
            class="w-full bg-white border border-[#ee4d2d] text-[#ee4d2d] group-hover:bg-[#ee4d2d] group-hover:text-white py-2 rounded-md text-sm font-bold transition-all mt-auto flex items-center justify-center gap-2">
            <i class="fas fa-cart-plus"></i>
            Thêm vào giỏ
        </button>
    </div>
</div>
