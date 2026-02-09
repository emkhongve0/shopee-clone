@props(['products' => []])

<div class="bg-[#1e293b] border border-slate-800 rounded-xl shadow-sm">
    <div class="p-6 border-b border-slate-800">
        <h3 class="text-white text-lg font-semibold">Sản phẩm bán chạy</h3>
    </div>
    <div class="p-6 space-y-4">
        @foreach ($products as $product)
            <div
                class="flex items-center gap-4 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-800 transition-colors group">
                {{-- Container cho ảnh sản phẩm --}}
                <div
                    class="w-14 h-14 rounded-lg overflow-hidden bg-slate-700 shrink-0 border border-slate-600 flex items-center justify-center relative">
                    @if (!empty($product['image']))
                        <img src="{{ $product['image'] }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform"
                            {{-- Fix lỗi vòng lặp ở đây --}}
                            onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @endif

                    {{-- Khối hiển thị khi ảnh lỗi hoặc không có ảnh --}}
                    <div style="{{ !empty($product['image']) ? 'display:none;' : 'display:flex;' }}"
                        class="w-full h-full items-center justify-center bg-slate-800 text-slate-500">
                        <i class="fas fa-image text-lg"></i>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <h4 class="text-white font-medium text-sm truncate">{{ $product['name'] }}</h4>
                    <p class="text-slate-400 text-xs">{{ $product['category'] }}</p>
                </div>

                <div class="text-right shrink-0">
                    <p class="text-white font-semibold text-sm">{{ $product['revenue'] }}</p>
                    <div class="flex items-center gap-1 justify-end text-green-500 text-[10px] mt-1 italic">
                        <i class="fas fa-trending-up"></i> <span>{{ $product['sales'] }} sales</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
