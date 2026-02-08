@php
    // 1. Lấy danh sách ID từ session
    $recentIds = session()->get('recent_viewed', []);

    // 2. Khởi tạo một Collection trống để tránh lỗi "isEmpty() on array"
    $recentProducts = collect();

    if (!empty($recentIds)) {
        // 3. Truy vấn Database và giữ đúng thứ tự xem gần đây nhất lên đầu
        $recentProducts = \App\Models\Product::whereIn('id', $recentIds)
            ->where('status', 1)
            ->orderByRaw('FIELD(id, ' . implode(',', $recentIds) . ')')
            ->get();
    }
@endphp

{{-- Chỉ hiển thị vùng này nếu có sản phẩm trong danh sách --}}
@if ($recentProducts->isNotEmpty())
    <div class="mt-16 border-t border-gray-200 pt-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl md:text-2xl font-black text-gray-900 flex items-center gap-3">
                <i class="fas fa-history text-blue-500 animate-pulse"></i>
                SẢN PHẨM ĐÃ XEM GẦN ĐÂY
            </h2>
            <div class="hidden sm:block">
                <span class="text-sm text-gray-400 italic">Tối đa 20 sản phẩm xem gần nhất</span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($recentProducts as $item)
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-2 relative group hover:shadow-md transition-all duration-300">
                    <a href="{{ route('product.detail', $item->slug) }}" class="block">
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-50 mb-3 border border-gray-50">
                            <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                        </div>

                        <div class="px-1">
                            <h3
                                class="text-sm font-medium text-gray-800 line-clamp-2 h-10 mb-2 leading-tight group-hover:text-[#ee4d2d] transition-colors">
                                {{ $item->name }}
                            </h3>

                            <div class="flex items-center justify-between mt-2">
                                <div class="text-[#ee4d2d] font-black text-base sm:text-lg">
                                    {{ number_format($item->price, 0, ',', '.') }}đ
                                </div>

                                {{-- Badge nhỏ nếu sản phẩm đang giảm giá --}}
                                @if ($item->old_price > $item->price)
                                    <div class="text-[10px] bg-red-50 text-red-500 px-1 rounded border border-red-100">
                                        -{{ round((($item->old_price - $item->price) / $item->old_price) * 100) }}%
                                    </div>
                                @endif
                            </div>

                            <div class="mt-2 flex items-center gap-1">
                                <div class="flex text-yellow-400 text-[10px]">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span
                                    class="text-[10px] text-gray-400">({{ number_format($item->reviews_count ?? rand(10, 100)) }})</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
