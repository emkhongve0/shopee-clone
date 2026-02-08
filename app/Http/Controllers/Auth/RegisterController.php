<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // Hiển thị giao diện SignUpPage
    public function showRegistrationForm()
    {
        return view('auth.signup-page');
    }

    // Xử lý logic đăng ký
    public function register(Request $request)
    {
        // 1. Chống Đăng ký hàng loạt (Rate Limiting)
        // Giới hạn mỗi địa chỉ IP chỉ được đăng ký tối đa 3 tài khoản trong 1 giờ
        $throttleKey = 'register-attempt:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $hours = ceil(RateLimiter::availableIn($throttleKey) / 3600);
            return back()->withErrors([
                'email' => "Bạn đã tạo quá nhiều tài khoản. Vui lòng thử lại sau {$hours} giờ.",
            ])->onlyInput('name', 'email');
        }

        // 2. Validate chặt chẽ
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u'], // Chỉ cho phép chữ cái và khoảng trắng
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:100'], // Giới hạn max 100 để tránh tấn công Bcrypt DoS
        ], [
            'name.regex' => 'Họ tên không được chứa ký tự đặc biệt hoặc số.',
            'email.unique' => 'Email này đã được sử dụng trên hệ thống.',
        ]);

        // 3. Làm sạch dữ liệu (Sanitization)
        $email = strtolower(trim($request->email));
        $name = strip_tags(trim($request->name)); // Loại bỏ các thẻ HTML nếu có

        // 4. Tạo người dùng mới
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
        ]);

        // Đánh dấu 1 lần đăng ký thành công cho IP này
        RateLimiter::hit($throttleKey, 3600);

        // 5. Chuyển hướng với thông báo
        return redirect()->route('login')->with('success', 'Đăng ký thành công! Hãy đăng nhập để bắt đầu mua sắm.');
    }
}
