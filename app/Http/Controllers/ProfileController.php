<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        // Lấy thông tin user đang đăng nhập
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    //Bước 3: Logic xử lý cập nhật thông tin
    public function updateProfile(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào (Validation)
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'phone' => ['nullable', 'numeric', 'digits_between:10,11'],
            'gender' => ['nullable', 'in:male,female,other'],
        ], [
            'name.regex' => 'Họ tên không được chứa số hoặc ký tự đặc biệt.',
            'phone.numeric' => 'Số điện thoại phải là chữ số.',
            'phone.digits_between' => 'Số điện thoại phải có 10 hoặc 11 chữ số.',
        ]);

        // 2. Tìm User đang đăng nhập trong Database
        $user = User::find(Auth::id());

        // 3. Thực hiện cập nhật
        if ($user) {
            $user->update([
                'name' => strip_tags($request->name), // Loại bỏ thẻ HTML để bảo mật
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);
        }

        // 4. Quay lại trang trước kèm thông báo thành công
        return back()->with('success_info', 'Thông tin cá nhân của bạn đã được cập nhật thành công!');
    }
}
