<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    // Dependency Injection Service vào Controller
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        // Global error handler: Dùng try-catch để không để lỗi rơi ra ngoài (panic) [cite: 2]
        try {
            // Gọi Service để xử lý logic, truyền vào dữ liệu đã validate [cite: 1]
            $user = $this->authService->register($request->validated());

            // Auto login sau khi đăng ký
            Auth::login($user);

            // Response đồng nhất: Flash message [cite: 1]
            return redirect()->route('profile.index')->with('success', 'Chào mừng bạn đến với ShopMart!');

        } catch (\Exception $e) {
            // Trả về lỗi thân thiện với người dùng, không lộ stacktrace ra view [cite: 3]
            return back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    }
}
