<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Thêm dòng này
use Illuminate\Support\Facades\Auth; // Thêm dòng này
use App\Models\Cart;                 // Thêm dòng này

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Logic: Chia sẻ biến $cartCount cho tất cả các View (*).
         * Dữ liệu này sẽ tự động cập nhật mỗi khi trang được tải lại.
         */
        View::composer('*', function ($view) {
            $cartCount = 0;

            // Kiểm tra nếu người dùng đã đăng nhập thì mới tính toán
            if (Auth::check()) {
                // Tính tổng số lượng (quantity) của tất cả sản phẩm trong giỏ
                $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            }

            // Gắn biến $cartCount vào view để sử dụng ở bất cứ đâu bằng cách gọi {{ $cartCount }}
            $view->with('cartCount', $cartCount);
        });
    }
}
