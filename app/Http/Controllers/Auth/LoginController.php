<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter; // Thêm để chặn Brute Force
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validate chặt chẽ: Chống DoS bằng cách giới hạn độ dài ký tự
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:100'],
        ]);

        // Làm sạch dữ liệu (Sanitize email)
        $email = strtolower(trim($request->email));
        $throttleKey = Str::lower($email) . '|' . $request->ip();

        // 2. Chống Brute Force: Nếu sai quá 5 lần trong 1 phút, khóa ngay
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Bạn đã thử quá nhiều lần. Vui lòng đợi {$seconds} giây.",
            ]);
        }

        $user = User::where('email', $email)->first();

        // 3. Logic kiểm tra Mật khẩu tạm thời
        if ($user && $user->temp_password && $user->temp_password_expires_at) {
            $now = Carbon::now();

            if ($now->isBefore($user->temp_password_expires_at) &&
                Hash::check($request->password, $user->temp_password)) {

                Auth::login($user, $request->remember);

                // Bảo mật: Xóa dấu vết OTP ngay lập tức
                $user->update([
                    'temp_password' => null,
                    'temp_password_expires_at' => null
                ]);

                return $this->handleSuccessResponse($request);
            }
        }

        // 4. Logic cũ: Đăng nhập bằng mật khẩu chính
        if (Auth::attempt(['email' => $email, 'password' => $request->password], $request->remember)) {
            return $this->handleSuccessResponse($request);
        }

        // Nếu thất bại: Tăng số lần thử sai để kích hoạt Rate Limit
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác hoặc mã đã hết hạn.',
        ])->onlyInput('email');
    }

    /**
     * Xử lý sau khi đăng nhập thành công
     */
    protected function handleSuccessResponse(Request $request)
    {
        RateLimiter::clear(Str::lower($request->email) . '|' . $request->ip());

        $request->session()->regenerate(); // Chống Session Fixation

        return redirect()->intended('/')->with('success', 'Chào mừng bạn đã quay lại!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
