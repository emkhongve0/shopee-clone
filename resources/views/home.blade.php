@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <x-ui.hero-banner />

        {{-- BỘ SƯU TẬP MỚI --}}
        <x-ui.collection-banner />

        {{-- Thanh danh mục --}}
        <x-ui.category-scroll :categories="$categories" />

        {{-- BỔ SUNG FLASH SALE Ở ĐÂY --}}
        <x-ui.flash-sale :flashSaleProducts="$flashSaleProducts" />

        <div class="bg-white p-4 rounded-sm shadow-sm border-b-2 border-[#ee4d2d]">
            <h2 class="text-[#ee4d2d] font-medium uppercase" id="current-category-name">
                {{ isset($currentCategory) ? $currentCategory->name : 'Gợi ý hôm nay' }}
            </h2>
        </div>

        {{-- Khay chứa sản phẩm (Vùng này sẽ thay đổi khi bấm danh mục) --}}
        <div id="product-wrapper" class="transition-opacity duration-300">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
                @foreach ($products as $product)
                    <x-ui.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
