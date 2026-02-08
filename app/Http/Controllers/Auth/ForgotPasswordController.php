<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TempPasswordMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter; // Sử dụng RateLimiter chuyên nghiệp hơn Session

class ForgotPasswordController extends Controller
{
    public function sendTempPassword(Request $request)
    {
        // 1. Validate chặt chẽ: Dùng 'rfc,dns' để kiểm tra email có thật hay không
        $request->validate([
            'email' => 'required|email:rfc,dns|max:255'
        ]);

        // Làm sạch dữ liệu: Xóa khoảng trắng và chuyển về chữ thường
        $email = strtolower(trim($request->email));

        // Tạo khóa định danh cho người dùng dựa trên IP và Email để chặn Spam
        $throttleKey = 'forgot-password:' . $request->ip() . '|' . $email;

        // --- 1. LOGIC CHẶN SPAM (Sử dụng RateLimiter của Laravel) ---
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $secondsLeft = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'status' => 'error',
                'message' => "Hệ thống đang bận xử lý yêu cầu của bạn. Vui lòng quay lại sau {$secondsLeft} giây."
            ], 429);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // Trường hợp có User: Thực hiện tác vụ nặng
            // Tăng độ dài lên 10 ký tự để khó bẻ khóa hơn
            $plainPassword = Str::random(10);

            $user->update([
                'temp_password' => Hash::make($plainPassword),
                'temp_password_expires_at' => Carbon::now()->addMinutes(15),
            ]);

            try {
                Mail::to($user->email)->send(new TempPasswordMail($plainPassword));
            } catch (\Exception $e) {
                Log::error("Mail Error: " . $e->getMessage());
            }
        } else {
            // --- 2. CHỐNG TIMING ATTACK ---
            // Luôn băm một chuỗi giả để hacker không nhận ra sự khác biệt về tốc độ CPU
            Hash::make(Str::random(10));

            // Giả lập độ trễ mạng ngẫu nhiên (400ms - 800ms)
            usleep(rand(400000, 800000));
        }

        // Đánh dấu đã gửi yêu cầu: Khóa trong 60 giây (1 phút)
        RateLimiter::hit($throttleKey, 60);

        return response()->json([
            'status' => 'success',
            'message' => 'Yêu cầu đã được ghi nhận. Nếu email khớp với tài khoản, mật khẩu tạm thời đã được gửi đi.'
        ]);
    }
}
