<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopMart - Mua sắm online')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* 1. Hiệu ứng rung icon giỏ hàng (Bounce) */
        @keyframes cart-bounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.4);
            }
        }

        .animate-cart-bounce {
            animation: cart-bounce 0.5s ease-in-out;
            color: #ee4d2d !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #ee4d2d;
            border-radius: 10px;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#f5f5f5] antialiased" x-data="globalAppState"
    @add-to-cart.window="addToCart($event.detail, $event.detail.quantity || 1)"
    @open-global-modal.window="openModal($event.detail)"> {{-- Lắng nghe sự kiện mở modal toàn cục --}}

    <div class="flex flex-col min-h-screen">
        @include('components.header')
        <main class="flex-grow container mx-auto px-4 pb-12">
            @yield('content')
        </main>
        @include('components.footer')
    </div>

    {{-- 1. GIỮ NGUYÊN: Notification Toast --}}
    <div class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-3 w-full max-w-[320px]">
        <template x-for="note in notifications" :key="note.id">
            <div x-show="true" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="bg-white border-l-4 shadow-xl p-4 flex gap-3 rounded-r-lg items-center"
                :class="note.type === 'success' ? 'border-green-500' : 'border-red-500'">
                <i class="fas"
                    :class="note.type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500'"></i>
                <span x-text="note.message" class="text-sm font-medium text-gray-700"></span>
            </div>
        </template>
    </div>

    {{-- 2. THÊM MỚI: Global Modal Component --}}
    <div x-show="modal.open" class="relative z-[10000]" x-cloak>
        <div x-show="modal.open" x-transition:opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="modal.open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="relative w-full max-w-md bg-white rounded-2xl p-6 text-center shadow-2xl border border-gray-100">

                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full"
                        :class="{
                            'bg-blue-100 text-blue-600': modal.type === 'info',
                            'bg-red-100 text-red-600': modal.type === 'danger',
                            'bg-orange-100 text-orange-600': modal.type === 'warning'
                        }">
                        <i class="fas text-xl"
                            :class="{
                                'fa-info-circle': modal.type === 'info',
                                'fa-trash-alt': modal.type === 'danger',
                                'fa-exclamation-triangle': modal.type === 'warning'
                            }"></i>
                    </div>

                    <h3 class="text-lg font-black text-gray-900 mb-2" x-text="modal.title"></h3>
                    <p class="text-sm text-gray-500 mb-6" x-text="modal.message"></p>

                    <div class="flex gap-3">
                        <button @click="modal.open = false"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition-all text-sm">Quay
                            lại</button>
                        <button @click="confirmModal()"
                            class="flex-1 px-4 py-2.5 rounded-xl text-white font-bold transition-all text-sm shadow-lg"
                            :class="{
                                'bg-blue-500 shadow-blue-100': modal.type === 'info',
                                'bg-red-500 shadow-red-100': modal.type === 'danger',
                                'bg-[#ee4d2d] shadow-orange-100': modal.type === 'warning'
                            }">Xác
                            nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-cloak><x-ui.cart-modal /></div>
    @stack('scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalAppState', () => ({
                notifications: [],
                // Khởi tạo state cho Modal
                modal: {
                    open: false,
                    title: '',
                    message: '',
                    type: 'info',
                    onConfirm: null
                },

                init() {
                    @if (session('success'))
                        this.pushNotification("{{ session('success') }}",
                        'success');
                    @endif
                    @if (session('error'))
                        this.pushNotification("{{ session('error') }}",
                        'error');
                    @endif
                },

                // Logic mở Modal
                openModal(detail) {
                    this.modal.title = detail.title || 'Thông báo';
                    this.modal.message = detail.message || '';
                    this.modal.type = detail.type || 'info';
                    this.modal.onConfirm = detail.onConfirm || null;
                    this.modal.open = true;
                },

                // Logic xác nhận Modal
                confirmModal() {
                    if (this.modal.onConfirm) this.modal.onConfirm();
                    this.modal.open = false;
                },

                pushNotification(message, type = 'success') {
                    const id = Date.now();
                    this.notifications.push({
                        id,
                        message,
                        type
                    });
                    setTimeout(() => {
                        this.notifications = this.notifications.filter(n => n.id !== id)
                    }, 3500);
                },

                async addToCart(productData, quantity = 1) {
                    const productId = productData.id;
                    const productName = productData.name || 'sản phẩm';

                    try {
                        const response = await fetch('{{ route('cart.add') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: quantity
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.status === 'success') {
                            this.pushNotification(`Đã thêm "${productName}" vào giỏ hàng!`,
                                'success');
                            window.dispatchEvent(new CustomEvent('cart-updated', {
                                detail: {
                                    newCount: data.cart_count
                                }
                            }));
                        } else if (response.status === 401) {
                            // THAY THẾ SWAL BẰNG MODAL MỚI CỦA BẠN (NẾU MUỐN)
                            this.openModal({
                                title: 'Bạn chưa đăng nhập!',
                                message: 'Vui lòng đăng nhập để có thể thêm sản phẩm này vào giỏ hàng.',
                                type: 'warning',
                                onConfirm: () => {
                                    window.location.href = '{{ route('login') }}'
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Lỗi thêm giỏ hàng:', error);
                    }
                }
            }))
        })
    </script>
</body>

</html>
