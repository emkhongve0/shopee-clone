@props(['products', 'title' => 'Flash Sale Products'])

<section class="bg-white rounded-lg p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">{{ $title }}</h2>
        <a href="#"
            class="text-orange-500 hover:text-orange-600 text-sm font-medium transition-colors flex items-center gap-1">
            View All <i class="fas fa-chevron-right text-[10px]"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($products as $product)
            {{-- Gọi Product Card Blade đã tạo ở các bước trước --}}
            <x-ui.product-card :product="$product" />
        @empty
            <div class="col-span-full py-12 text-center text-gray-400">
                <i class="fas fa-box-open text-4xl mb-2"></i>
                <p>No products found in this sale.</p>
            </div>
        @endforelse
    </div>
</section>
