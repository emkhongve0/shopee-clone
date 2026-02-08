@extends('layouts.app')

@php
    $hasFilter =
        request()->has('brands') ||
        request()->filled('min_price') ||
        request()->filled('max_price') ||
        request()->has('rating');

    $currentIndexCat = $relatedCategories->pluck('id')->search($category->id);
    $isCatExpanded = $currentIndexCat !== false && $currentIndexCat >= 5;
    $isBrandExpanded = request()->has('brands') && is_array(request('brands')) && count(request('brands')) > 0;
@endphp

@section('content')
    <div class="min-h-screen bg-[#f5f5f5]" x-data="{ showFilters: false }">
        <div class="max-w-[1400px] mx-auto px-4 py-6">
            <div class="flex gap-6">

                {{-- LEFT SIDEBAR --}}
                <aside class="hidden lg:block w-64 flex-shrink-0" x-data="{
                    expandedCat: {{ $isCatExpanded ? 'true' : 'false' }},
                    expandedBrand: {{ $isBrandExpanded ? 'true' : 'false' }}
                }">
                    <div class="sticky top-6 space-y-4">
                        {{-- NÚT XÓA TẤT CẢ BỘ LỌC --}}
                        @if ($hasFilter)
                            <div
                                class="flex items-center justify-between p-3 bg-orange-50 border border-orange-200 rounded-lg shadow-sm">
                                <span class="text-xs font-bold text-gray-700 uppercase italic">Đang lọc</span>
                                <a href="{{ route('category.show', $category->slug) }}"
                                    class="text-[11px] font-bold text-[#ee4d2d] hover:text-[#d73211] flex items-center gap-1 transition-colors">
                                    <i class="fas fa-trash-alt"></i> XÓA TẤT CẢ
                                </a>
                            </div>
                        @endif

                        {{-- 1. DANH MỤC LIÊN QUAN --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-list-ul text-[#ee4d2d]"></i>
                                <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider">Tất cả danh mục</h3>
                            </div>
                            <div class="p-2">
                                @foreach ($relatedCategories->take(10) as $index => $related)
                                    <div class="flex flex-col">
                                        <a href="{{ route('category.show', $related->slug) }}"
                                            class="block px-3 py-2 text-sm transition-all rounded-md {{ $category->slug == $related->slug ? 'text-[#ee4d2d] font-bold bg-orange-50' : 'text-gray-600 hover:text-[#ee4d2d] hover:bg-gray-50' }}">
                                            <div class="flex items-center justify-between">
                                                <span>{{ $related->name }}</span>
                                                @if ($category->slug == $related->slug)
                                                    <i class="fas fa-caret-right text-[10px]"></i>
                                                @endif
                                            </div>
                                        </a>
                                        @if ($category->slug == $related->slug && $category->children->count() > 0)
                                            <div class="ml-6 flex flex-col border-l-2 border-orange-100 mb-2">
                                                @foreach ($category->children as $child)
                                                    <a href="{{ route('category.show', $child->slug) }}"
                                                        class="py-1 px-3 text-xs text-gray-500 hover:text-[#ee4d2d] transition-colors">
                                                        {{ $child->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 2. THƯƠNG HIỆU --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-tag text-[#ee4d2d]"></i>
                                <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider">Thương hiệu</h3>
                            </div>
                            <div class="p-2">
                                <form action="{{ url()->current() }}" method="GET">
                                    @foreach (request()->except('brands', 'page') as $key => $value)
                                        @if (!is_array($value))
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endif
                                    @endforeach
                                    @foreach ($brands as $index => $brand)
                                        <div class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-50 group cursor-pointer"
                                            x-show="expandedBrand || {{ $index }} < 5" x-transition>
                                            <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                                id="brand-{{ $brand->id }}"
                                                {{ is_array(request('brands')) && in_array($brand->id, request('brands')) ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                                class="w-4 h-4 rounded border-gray-300 text-[#ee4d2d] focus:ring-[#ee4d2d] cursor-pointer">
                                            <label for="brand-{{ $brand->id }}"
                                                class="text-sm text-gray-600 cursor-pointer group-hover:text-[#ee4d2d] transition-colors flex-1">{{ $brand->name }}</label>
                                        </div>
                                    @endforeach
                                </form>
                                @if ($brands->count() > 5)
                                    <button @click="expandedBrand = !expandedBrand"
                                        class="w-full text-center py-2 mt-2 text-xs font-semibold text-[#ee4d2d] hover:bg-orange-50 rounded-md border-t border-dashed border-gray-100 flex items-center justify-center gap-2 transition-all">
                                        <span x-text="expandedBrand ? 'Thu gọn bớt' : 'Xem thêm thương hiệu'"></span>
                                        <i class="fas transition-transform duration-300"
                                            :class="expandedBrand ? 'fa-angle-up' : 'fa-angle-down'"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- 3. KHOẢNG GIÁ --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <h3 class="font-bold text-gray-800 text-xs uppercase mb-4 tracking-wider">Khoảng giá</h3>
                            <form action="{{ url()->current() }}" method="GET" class="space-y-4">
                                @if (request()->has('brands'))
                                    @foreach (request('brands') as $bId)
                                        <input type="hidden" name="brands[]" value="{{ $bId }}">
                                    @endforeach
                                @endif
                                <div class="flex items-center gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        placeholder="₫ TỪ"
                                        class="h-9 w-full text-xs border-gray-300 rounded-md focus:ring-[#ee4d2d]">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        placeholder="₫ ĐẾN"
                                        class="h-9 w-full text-xs border-gray-300 rounded-md focus:ring-[#ee4d2d]">
                                </div>
                                <button type="submit"
                                    class="w-full bg-[#ee4d2d] text-white py-2 rounded-md text-xs font-bold uppercase hover:bg-[#d73211] transition shadow-sm">ÁP
                                    DỤNG</button>
                            </form>
                        </div>

                        {{-- 4. ĐÁNH GIÁ --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <h3 class="font-bold text-gray-800 text-xs uppercase mb-4 tracking-wider">Đánh giá</h3>
                            @for ($i = 5; $i >= 1; $i--)
                                <a href="{{ request()->fullUrlWithQuery(['rating' => $i]) }}"
                                    class="flex items-center gap-2 py-1.5 px-1 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-0.5">
                                        @for ($j = 1; $j <= 5; $j++)
                                            <i
                                                class="fas fa-star text-xs {{ $j <= $i ? 'text-[#ffc107]' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $i < 5 ? 'trở lên' : '' }}</span>
                                </a>
                            @endfor
                        </div>
                    </div>
                </aside>

                {{-- MAIN CONTENT AREA --}}
                <main class="flex-1 min-w-0">
                    {{-- Toolbar --}}
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-4 overflow-x-auto no-scrollbar">
                            <span class="text-sm text-gray-500 whitespace-nowrap">Sắp xếp theo:</span>
                            <div class="flex gap-2">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                                    class="px-4 py-2 rounded-sm text-sm whitespace-nowrap {{ request('sort', 'newest') == 'newest' ? 'bg-[#ee4d2d] text-white' : 'bg-gray-50 hover:bg-gray-100' }}">Mới
                                    nhất</a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}"
                                    class="px-4 py-2 rounded-sm text-sm whitespace-nowrap {{ request('sort') == 'price_low' ? 'bg-[#ee4d2d] text-white' : 'bg-gray-50 hover:bg-gray-100' }}">Giá:
                                    Thấp đến Cao</a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}"
                                    class="px-4 py-2 rounded-sm text-sm whitespace-nowrap {{ request('sort') == 'price_high' ? 'bg-[#ee4d2d] text-white' : 'bg-gray-50 hover:bg-gray-100' }}">Giá:
                                    Cao đến Thấp</a>
                            </div>
                        </div>
                        <button @click="showFilters = true" class="lg:hidden p-2 text-gray-600 border rounded-md">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>

                    {{-- Product Grid --}}
                    {{-- GRID SẢN PHẨM CHÍNH --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                        @forelse($products as $product)
                            {{-- LOGIC HOT: Nếu ID chia hết cho 3 HOẶC đã bán > 500 thì hiện HOT --}}
                            @php $isHot = ($product->id % 3 == 0) || (($product->sold ?? 0) > 500); @endphp

                            <div
                                class="bg-white rounded-sm border border-transparent hover:border-[#ee4d2d] hover:shadow-lg transition-all duration-300 group relative flex flex-col h-full overflow-hidden">

                                {{-- CHỈ HIỆN BADGE NẾU LÀ SẢN PHẨM HOT --}}
                                @if ($isHot)
                                    <div
                                        class="absolute top-0 right-0 z-10 bg-yellow-400 text-[#ee4d2d] text-[10px] font-bold px-1.5 py-0.5 shadow-sm">
                                        HOT</div>
                                @endif

                                <a href="{{ route('product.detail', $product->slug) }}"
                                    class="block aspect-square bg-gray-50 overflow-hidden">
                                    <img src="{{ $product->image }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        alt="{{ $product->name }}">
                                </a>

                                <div class="p-2 flex-grow flex flex-col">
                                    <a href="{{ route('product.detail', $product->slug) }}" class="block mb-1">
                                        <h3
                                            class="text-xs text-gray-700 line-clamp-2 min-h-[32px] group-hover:text-[#ee4d2d] transition-colors leading-4">
                                            {{ $product->name }}</h3>
                                    </a>
                                    <div class="mt-auto">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-xs font-bold text-[#ee4d2d]">₫</span>
                                            <span
                                                class="text-base font-bold text-[#ee4d2d]">{{ number_format($product->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-[10px] text-gray-500 mt-1">
                                            <div class="flex text-yellow-400">
                                                @for ($star = 1; $star <= 5; $star++)
                                                    <i class="fas fa-star text-[8px]"></i>
                                                @endfor
                                            </div>
                                            <span class="border-l border-gray-300 h-2 mx-1"></span>
                                            <span>Đã bán {{ number_format($product->sold ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Nút Thêm vào giỏ (Đã fix lỗi rớt dòng) --}}
                                <div
                                    class="absolute inset-x-0 bottom-0 p-1.5 bg-white/95 translate-y-full group-hover:translate-y-0 transition-transform z-20 shadow-[0_-2px_10px_rgba(0,0,0,0.05)] border-t border-gray-100">
                                    <button @click="addToCart({{ $product->id }})"
                                        class="w-full bg-[#ee4d2d] text-white py-2 rounded-sm text-[11px] font-bold uppercase whitespace-nowrap">
                                        Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center bg-white rounded-lg">
                                <p class="text-gray-400">Không tìm thấy sản phẩm nào.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>

                    {{-- SECTION MỚI: SẢN PHẨM LIÊN QUAN / GỢI Ý --}}
                    @if (isset($suggestedProducts) && $suggestedProducts->count() > 0)
                        <div class="mt-10 pt-8 border-t-4 border-[#ee4d2d]">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg md:text-xl font-bold text-gray-800 uppercase">CÓ THỂ BẠN CŨNG THÍCH</h2>
                                <a href="#" class="text-sm text-[#ee4d2d] hover:underline">Xem tất cả ></a>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                                @foreach ($suggestedProducts as $sProduct)
                                    <div
                                        class="bg-white rounded-sm border border-gray-100 hover:border-[#ee4d2d] hover:shadow-lg transition-all duration-300 group relative flex flex-col h-full overflow-hidden">
                                        {{-- Ảnh --}}
                                        <a href="{{ route('product.detail', $sProduct->slug) }}"
                                            class="block aspect-square bg-gray-50 overflow-hidden">
                                            <img src="{{ $sProduct->image }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                alt="{{ $sProduct->name }}">
                                        </a>

                                        {{-- Nội dung --}}
                                        <div class="p-2 flex-grow flex flex-col">
                                            <a href="{{ route('product.detail', $sProduct->slug) }}" class="block mb-1">
                                                <h3
                                                    class="text-xs text-gray-700 line-clamp-2 min-h-[32px] group-hover:text-[#ee4d2d] transition-colors">
                                                    {{ $sProduct->name }}
                                                </h3>
                                            </a>
                                            <div class="mt-auto">
                                                <div class="flex items-baseline gap-1">
                                                    <span
                                                        class="text-sm font-bold text-[#ee4d2d]">₫{{ number_format($sProduct->price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- KẾT THÚC SECTION MỚI --}}

                </main>
            </div>
        </div>
    </div>
@endsection
