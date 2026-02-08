<footer class="bg-white border-t border-gray-200 mt-12 font-['Inter']">
    <div class="max-w-[1440px] mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 mb-8">

            <div class="md:col-span-2">
                <h3 class="text-2xl font-bold text-[#ee4d2d] mb-4">ShopMart</h3>
                <p class="text-sm text-gray-600 mb-6 leading-relaxed max-w-md">
                    Đối tác thương mại điện tử tin cậy của bạn, cung cấp sản phẩm chất lượng với mức giá tốt nhất.
                    Mua sắm an tâm và tận hưởng dịch vụ giao hàng siêu tốc.
                </p>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-phone-alt w-4 h-4 text-[#ee4d2d] flex-shrink-0"></i>
                        <span>+84 (123) 456-789</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-envelope w-4 h-4 text-[#ee4d2d] flex-shrink-0"></i>
                        <span>support@shopmart.com</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 h-4 text-[#ee4d2d] flex-shrink-0"></i>
                        <span>123 Đường Thương Mại, Quận 1, TP.HCM</span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Chăm sóc khách hàng</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Trung tâm
                            trợ giúp</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Theo dõi
                            đơn hàng</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Chính
                            sách đổi trả</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Vận
                            chuyển & Giao nhận</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Liên hệ
                            với chúng tôi</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Về chúng tôi</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Giới
                            thiệu về ShopMart</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Tuyển
                            dụng</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Báo
                            chí</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-[#ee4d2d] transition-colors">Điều
                            khoản dịch vụ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Theo dõi chúng tôi</h4>

                <div class="flex gap-3 mb-6">
                    <a href="#"
                        class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-[#ee4d2d] hover:text-white transition-all transform hover:-translate-y-1"
                        aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-[#ee4d2d] hover:text-white transition-all transform hover:-translate-y-1"
                        aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-[#ee4d2d] hover:text-white transition-all transform hover:-translate-y-1"
                        aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-[#ee4d2d] hover:text-white transition-all transform hover:-translate-y-1"
                        aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>

                <div x-data="{ email: '', subscribed: false }">
                    <p class="text-sm font-semibold text-gray-900 mb-3">Bản tin khuyến mãi</p>
                    <form
                        @submit.prevent="subscribed = true; $dispatch('notify', {message: 'Cảm ơn bạn đã đăng ký!', type: 'success'})"
                        class="flex gap-2">
                        <input type="email" x-model="email" placeholder="Email của bạn" required
                            class="flex-1 min-w-0 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#ee4d2d] focus:border-[#ee4d2d] transition-all" />
                        <button type="submit"
                            class="bg-[#ee4d2d] hover:bg-[#d73211] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex-shrink-0 shadow-sm">
                            Đăng ký
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div
            class="border-t border-gray-200 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <p>© 2026 ShopMart. Tất cả quyền được bảo lưu.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-[#ee4d2d] transition-colors">Chính sách bảo mật</a>
                <a href="#" class="hover:text-[#ee4d2d] transition-colors">Điều khoản & Điều kiện</a>
                <a href="#" class="hover:text-[#ee4d2d] transition-colors">Chính sách Cookie</a>
            </div>
        </div>
    </div>
</footer>
