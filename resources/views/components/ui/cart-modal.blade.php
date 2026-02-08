<div x-data="{
    localOpen: false,
    items: [],
    total: 0,
    loading: false,

    async fetchCart() {
        this.loading = true;
        try {
            const response = await fetch('{{ route('cart.index') }}');
            const result = await response.json();
            if (result.status === 'success') {
                this.items = result.items;
                this.total = result.total;
            }
        } finally { this.loading = false; }
    },

    // 1. Hàm xóa 1 sản phẩm - Đã thay SweetAlert2
    async removeItem(id) {
        // Kiểm tra id có tồn tại không
        if (!id) return;

        Swal.fire({
            title: 'Xác nhận xóa?',
            text: 'Bạn có muốn bỏ sản phẩm này khỏi giỏ hàng?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ee4d2d',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Đúng, xóa nó!',
            cancelButtonText: 'Hủy',
            reverseButtons: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    // Sử dụng nối chuỗi bằng dấu nháy đơn để tạo URL, tuyệt đối không dùng nháy kép
                    const url = '/cart/remove/' + id;

                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    // Kiểm tra nếu phản hồi từ server là hợp lệ
                    if (response.ok) {
                        const data = await response.json();

                        if (data.status === 'success') {
                            // 1. Cập nhật danh sách items ngay tại chỗ
                            this.items = this.items.filter(item => item.id !== id);

                            // 2. Tải lại giỏ hàng để cập nhật tổng tiền
                            this.fetchCart();

                            // 3. Cập nhật số lượng trên Header (Sự kiện toàn cục)
                            window.dispatchEvent(new CustomEvent('cart-updated', {
                                detail: { newCount: data.cart_count }
                            }));

                            // 4. Hiện thông báo thành công
                            this.$dispatch('notify', {
                                message: 'Đã xóa sản phẩm thành công',
                                type: 'success'
                            });
                        }
                    }
                } catch (error) {
                    console.error('Lỗi khi xóa:', error);
                }
            }
        });
    },

    // 2. Hàm xóa tất cả - Đã thay SweetAlert2
    async clearAll() {
        Swal.fire({
            title: 'Xóa toàn bộ giỏ hàng?',
            text: 'Hành động này không thể hoàn tác, bạn có chắc chắn không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ee4d2d',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Xóa tất cả',
            cancelButtonText: 'Quay lại',
            reverseButtons: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch('{{ route('cart.clear') }}', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();
                if (data.status === 'success') {
                    this.items = [];
                    this.total = 0;
                    window.dispatchEvent(new CustomEvent('cart-updated', {
                        detail: { newCount: 0 }
                    }));
                    this.$dispatch('notify', { message: 'Giỏ hàng đã được dọn trống', type: 'success' });
                }
            }
        });
    }

}" @open-cart.window="localOpen = true; fetchCart();" @cart-updated.window="fetchCart()"
    class="relative z-[999]">

    {{-- PHẦN HTML BÊN DƯỚI GIỮ NGUYÊN HOÀN TOÀN --}}
    <div x-show="localOpen" x-cloak class="fixed inset-0 bg-black/50 z-[1000]" @click="localOpen = false"></div>

    <div x-show="localOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl z-[1001] flex flex-col">

        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Giỏ hàng của tôi</h2>
            <button @click="localOpen = false" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-4">
            <template x-if="loading">
                <div class="flex justify-center py-10">
                    <i class="fas fa-spinner fa-spin text-2xl text-[#ee4d2d]"></i>
                </div>
            </template>

            <div class="space-y-4" x-show="!loading">
                <template x-for="item in items" :key="item.id">
                    <div class="flex gap-4 p-3 border rounded-lg hover:shadow-sm transition-shadow hover:bg-gray-50">
                        <img :src="item.product?.image"
                            @click="window.location.href = '/san-pham/' + item.product?.slug"
                            class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-80 transition-opacity">

                        <div class="flex-1 cursor-pointer"
                            @click="window.location.href = '/san-pham/' + item.product?.slug">
                            <h3 class="text-sm font-medium line-clamp-2 hover:text-[#ee4d2d] transition-colors"
                                x-text="item.product?.name"></h3>

                            <p class="text-[#ee4d2d] font-bold"
                                x-text="new Intl.NumberFormat('vi-VN').format(item.product?.price) + 'đ'"></p>

                            <p class="text-xs text-gray-500">Số lượng: <span x-text="item.quantity"></span></p>
                        </div>

                        <button @click.stop="removeItem(item.id)"
                            class="text-gray-300 hover:text-red-500 transition-colors p-2 self-start">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </template>

                <template x-if="items.length === 0 && !loading">
                    <div class="text-center py-20 text-gray-400">
                        <i class="fas fa-shopping-cart text-5xl mb-4 opacity-20"></i>
                        <p>Giỏ hàng trống</p>
                    </div>
                </template>
            </div>
        </div>

        <div class="p-6 border-t bg-gray-50" x-show="items.length > 0">
            <div class="flex justify-between items-center mb-4 text-lg">
                <span class="font-medium">Tổng tiền:</span>
                <span class="font-bold text-[#ee4d2d]"
                    x-text="new Intl.NumberFormat('vi-VN').format(total) + 'đ'"></span>
            </div>

            <div class="flex flex-col gap-2">
                <button class="w-full bg-[#ee4d2d] text-white py-3 rounded-lg font-bold hover:bg-[#d73211] shadow-lg">
                    MUA HÀNG
                </button>

                <button @click="clearAll()"
                    class="w-full text-sm text-gray-500 hover:text-red-600 py-2 transition-colors">
                    <i class="fas fa-trash-sweep mr-1"></i> Xóa tất cả giỏ hàng
                </button>
            </div>
        </div>
    </div>
</div>
