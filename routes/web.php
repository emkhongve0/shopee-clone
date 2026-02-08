<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Dành cho tất cả khách vãng lai)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('product.search');
Route::get('/danh-muc/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/san-pham/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/collection/{slug}', [HomeController::class, 'collection'])->name('collection.show');
Route::get('/change-password', function() {
    return redirect()->route('profile.index');
});
/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Chỉ dành cho người chưa đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Đăng ký
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Đăng nhập
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Quên mật khẩu
    Route::post('/forgot-password/send', [ForgotPasswordController::class, 'sendTempPassword'])
        ->middleware('throttle:1,1')
        ->name('password.temp');

    // Google Auth
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

/*
|--------------------------------------------------------------------------
| 3. AUTH ROUTES (Dành cho tất cả người dùng đã đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

// Trang hiển thị Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    // Route xử lý cập nhật thông tin (Họ tên, SĐT, Giới tính)
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Route xử lý đổi mật khẩu (Chúng ta đã làm ở các bước trước)
    Route::post('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('password.update');

    // Đăng xuất
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    // Giỏ hàng
    Route::prefix('cart')->group(function () {
        Route::get('/list', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::get('/count', [CartController::class, 'count'])->name('cart.count');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    // Sản phẩm yêu thích
    Route::get('/san-pham-yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
});

/*
|--------------------------------------------------------------------------
| 4. ADMIN ROUTES (Bảo mật - Chia rõ - Chuẩn REST)
|--------------------------------------------------------------------------
*/

// Sử dụng Middleware 'auth' và 'role:admin' (của Spatie bạn đang dùng)
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.') // Đã có 'admin.' ở đây rồi
    ->group(function () {

    // Dashboard chính - Đã sửa lại đường dẫn và trỏ đúng Controller
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');

    // Đặt route này TRƯỚC resource products để tránh bị trùng với show {id}
    Route::post('/products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk_action');
    Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');
    Route::post('products/import', [AdminProductController::class, 'import'])->name('products.import');
    // Resource này đã bao gồm: index, create, store, show, edit, update, destroy
    Route::resource('products', AdminProductController::class);

    // Quản lý người dùng
    Route::get('/users', function () {
        return view('admin.customers');
    })->name('admin.users.index');

    // Cài đặt hệ thống
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
});
