<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
{
    // 1. Validate: Chỉ cần mật khẩu mới và xác nhận mật khẩu mới
    $request->validate([
        'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
    ], [
        'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.'
    ]);

    $user = User::find(auth()->id());

    // 2. Cập nhật thẳng mật khẩu mới vào cột password chính thức
    $user->update([
        'password' => Hash::make($request->password),
        // Sau khi đổi xong, đảm bảo các trường tạm thời phải trống
        'temp_password' => null,
        'temp_password_expires_at' => null,
    ]);

    // 3. Xóa cờ force_password_change trong session (nếu còn)
    $request->session()->forget('force_password_change');

    return redirect()->route('profile.index')->with('success', 'Mật khẩu chính thức đã được thiết lập thành công!');
}
}
