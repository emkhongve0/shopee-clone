@extends('layouts.app')

@section('content')
    {{-- Đưa toàn bộ vào trong div container để mọi thứ thẳng hàng --}}
    <div class="max-w-[1440px] mx-auto px-4 py-8" x-data="{
        clearAll() {
                this.$dispatch('open-global-modal', {
                    title: 'Xóa toàn bộ?',
                    message: 'Tất cả sản phẩm yêu thích sẽ bị xóa. Bạn có chắc chắn không?',
                    type: 'danger',
                    onConfirm: () => {
                        fetch('{{ route('wishlist.clear') }}', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            })
                            .then(() => window.location.reload());
                    }
                });
            },
            removeItem(productId) {
                fetch('{{ route('wishlist.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                    .then(() => window.location.reload());
            }
    }">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <h2 class="text-xl md:text-2xl font-black text-gray-900 flex items-center gap-2">
                <i class="fas fa-heart text-red-500"></i> Danh sách yêu thích của bạn
            </h2>

            @if (!$products->isEmpty())
                <button @click="clearAll()"
                    class="text-sm font-medium text-gray-500 hover:text-red-500 transition-colors flex items-center gap-2 group">
                    <i class="far fa-trash-alt group-hover:shake"></i>
                    Xóa tất cả
                </button>
            @endif
        </div>

        {{-- Danh sách yêu thích --}}
        @if ($products->isEmpty())
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed">
                <p class="text-gray-400 font-medium">Danh sách yêu thích đang trống</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($products as $product)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-2 relative group">
                        {{-- Nút xóa --}}
                        <button @click="removeItem({{ $product->id }})"
                            class="absolute top-3 right-3 z-10 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full shadow-sm text-gray-400 hover:text-red-500 hover:bg-white transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <i class="fas fa-times text-xs"></i>
                        </button>

                        <a href="{{ route('product.detail', $product->slug) }}" class="block">
                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-50 mb-3">
                                <img src="{{ $product->image }}"
                                    class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="px-1">
                                <h3 class="text-sm font-medium text-gray-800 line-clamp-2 h-10 mb-2 leading-tight">
                                    {{ $product->name }}
                                </h3>
                                <div class="text-[#ee4d2d] font-black text-base sm:text-lg">
                                    đ{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- PHẦN CHÈN MỚI: Dải phân cách và Sản phẩm đã xem --}}
        <hr class="my-16 border-gray-100">

        {{-- Bây giờ component này sẽ nằm trong Container và thẳng hàng tuyệt đối --}}
        <x-ui.recently-viewed />
    </div>

    <style>
        @keyframes shake {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(10deg);
            }

            50% {
                transform: rotate(-10deg);
            }

            75% {
                transform: rotate(10deg);
            }
        }

        .group:hover .group-hover\:shake {
            animation: shake 0.3s ease-in-out infinite;
        }
    </style>
@endsection
