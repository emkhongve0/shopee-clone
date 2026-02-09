@props(['items' => []])

<div {{ $attributes->merge(['class' => 'bg-[#1e293b] border border-slate-800 rounded-xl shadow-sm']) }}>
    <div class="p-6 pb-4 border-b border-slate-800 flex items-center justify-between">
        <h3 class="text-white text-lg font-semibold flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-orange-500"></i>
            Hàng tồn kho thấp
        </h3>
        {{-- Hiển thị số lượng item từ mảng [cite: 94] --}}
        <span
            class="bg-orange-500/10 text-orange-500 px-2.5 py-0.5 rounded-full text-xs font-bold border border-orange-500/20">
            {{ count($items) }} Items
        </span>
    </div>

    <div class="p-6">
        <div class="space-y-3">
            @forelse($items as $item)
                <div
                    class="flex items-center gap-4 p-4 rounded-lg bg-slate-800/50 border border-orange-500/20 hover:border-orange-500/40 transition-colors group">
                    <div
                        class="w-10 h-10 bg-orange-500/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas fa-box text-orange-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-white font-medium text-sm truncate">{{ $item['name'] }}</h4>
                        <p class="text-slate-400 text-xs uppercase">SKU: {{ $item['sku'] }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-orange-500 font-bold text-lg leading-none">{{ $item['stock'] }}</p>
                        <p class="text-slate-500 text-[10px] mt-1 italic">of {{ $item['threshold'] }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center py-4 text-slate-500 italic">Kho hàng hiện tại ổn định.</p>
            @endforelse
        </div>
        <button
            class="w-full mt-6 py-2 px-4 bg-orange-500/10 hover:bg-orange-500/20 text-orange-500 rounded-lg font-medium text-sm transition-colors border border-orange-500/20">
            Bổ sung lại các mặt hàng
        </button>
    </div>
</div>
