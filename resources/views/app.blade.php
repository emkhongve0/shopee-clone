<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shopee Clone - Mua sắm online')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #ee4d2d;
            border-radius: 10px;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#f5f5f5] text-gray-800 antialiased" x-data="globalAppState"
    @add-to-cart.window="addToCart($event.detail)" @notify.window="pushNotification($event.detail)">

    <x-header />

    <main class="container mx-auto min-h-screen pb-12">
        @yield('content')
    </main>

    @if (view()->exists('components.footer'))
        @include('components.footer')
    @else
        <x-footer />
    @endif

    {{-- Notification Toast --}}
    <div class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-3 w-full max-w-[320px]">
        <template x-for="note in notifications" :key="note.id">
            <div x-show="true" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 translate-x-10"
                class="bg-white border-l-4 shadow-xl p-4 flex items-start gap-3 rounded-r-lg"
                :class="note.type === 'success' ? 'border-green-500' : 'border-red-500'">

                <div class="flex-shrink-0 mt-0.5">
                    <i class="fas"
                        :class="note.type === 'success' ? 'fa-check-circle text-green-500' :
                            'fa-exclamation-circle text-red-500'"></i>
                </div>
                <div class="flex-1 text-left">
                    <p class="text-sm font-semibold text-gray-900"
                        x-text="note.type === 'success' ? 'Thành công' : 'Thông báo'"></p>
                    <p class="text-xs text-gray-500 mt-1" x-text="note.message"></p>
                </div>
                <button @click="removeNotification(note.id)" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </template>
    </div>

    <div x-cloak>
        <x-ui.cart-modal />
        @stack('modals')
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalAppState', () => ({
                notifications: [],
                cartItems: [],

                init() {
                    // Khởi tạo app
                },

                pushNotification(detail) {
                    const id = Date.now();
                    // Hỗ trợ cả string và object
                    const message = typeof detail === 'string' ? detail : detail.message;
                    const type = detail.type || 'success';

                    this.notifications.push({
                        id,
                        message,
                        type
                    });

                    // Tự động xóa thông báo sau 3.5s
                    setTimeout(() => this.removeNotification(id), 3500);
                },

                removeNotification(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },

                async addToCart(product) {
                    try {
                        const response = await fetch("{{ route('cart.add') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                product_id: product.id,
                                quantity: product.quantity || 1
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.status === 'success') {
                            // FIX LỖI 1: Hiện thông báo Toast ngay lập tức
                            this.pushNotification({
                                message: `Đã thêm thành công "${product.name}" vào giỏ hàng!`,
                                type: 'success'
                            });

                            // FIX LỖI 2: Phát tín hiệu cập nhật số lượng cho Header (không load lại trang)
                            window.dispatchEvent(new CustomEvent('cart-updated', {
                                detail: {
                                    newCount: data.cart_count
                                }
                            }));

                        } else {
                            // Trường hợp lỗi (ví dụ: chưa đăng nhập)
                            this.pushNotification({
                                message: data.message || 'Có lỗi xảy ra!',
                                type: 'error'
                            });
                            if (response.status === 401) {
                                setTimeout(() => window.location.href = "{{ route('login') }}",
                                    1500);
                            }
                        }
                    } catch (error) {
                        console.error('Lỗi:', error);
                        this.pushNotification({
                            message: 'Lỗi kết nối Server!',
                            type: 'error'
                        });
                    }
                }
            }))
        })
    </script>
</body>

</html>
