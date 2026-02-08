<div class="grid grid-cols-2 md:grid-cols-6 gap-2">
    @forelse ($products as $product)
        <x-ui.product-card :product="$product" />
    @empty
        <div class="col-span-full py-20 text-center text-gray-500">
            Không có sản phẩm nào trong danh mục này.
        </div>
    @endforelse
</div>
