<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Phương thức index hiển thị giao diện cài đặt tổng hợp
     */
    public function index()
    {
        // Trả về file index.blade.php nằm trong thư mục admin/settings
        return view('admin.settings.index');
    }

    /**
     * Phương thức xử lý lưu dữ liệu (POST) cho tất cả các tab
     */
    public function update(Request $request)
    {
        // Logic xử lý lưu trữ cấu hình vào Database hoặc file config
        return back()->with('success', 'Cập nhật cài đặt thành công!');
    }
}
