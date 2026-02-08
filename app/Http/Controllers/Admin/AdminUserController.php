<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService; // Sử dụng tên mới của bạn
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
{
    // 1. Lấy các tham số lọc từ Request (Sticky Filters)
    // Việc này giúp chúng ta biết người dùng đang tìm gì để hiển thị lại trên ô Input
    $filters = [
        'search'    => $request->input('search', ''),
        'status'    => $request->input('status', 'all'),
        'role'      => $request->input('role', 'all'),
        'dateRange' => $request->input('dateRange', 'all'),
    ];

    // 2. Gọi Service với mảng filters đã bóc tách
    $rawUsers = $this->userService->getFilteredCustomers($filters);

    // 3. Định dạng dữ liệu hiển thị (giữ nguyên logic của bạn)
    $users = $this->userService->formatForFrontend($rawUsers);

    // 4. Lấy thống kê (giữ nguyên logic cũ)
    $stats = $this->userService->getQuickStats();
    
    return view('admin.users.index', [
        'users' => $users,
        'totalCount' => $stats['total'],
        'stats' => $stats,
        // QUAN TRỌNG: Gửi filters hiện tại về View để Alpine.js nhận diện
        'filters' => $filters
    ]);
}

    public function toggleStatus($id)
{
    try {
        $user = $this->userService->toggleStatus($id);
        return response()->json([
            'success' => true,
            'status' => $user->status instanceof \BackedEnum ? $user->status->value : $user->status,
            'message' => 'Đã cập nhật trạng thái thành công!'
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function resetPassword($id)
{
    try {
        $this->userService->resetPassword($id);
        return response()->json([
            'success' => true,
            'message' => 'Đã đặt lại mật khẩu về mặc định (123456)!'
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function update(Request $request, $id) {
    try {
        $user = $this->userService->updateMember($id, $request->all());
        // Trả về dữ liệu format chuẩn
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành viên thành công!',
            'user' => $this->userService->formatForFrontend(collect([$user]))->first()
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function destroy($id)
{
    try {
        // Gọi Service thực hiện việc xóa
        $this->userService->deleteUser($id);

        return response()->json([
            'success' => true,
            'message' => 'Người dùng đã bị xóa khỏi hệ thống!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi: ' . $e->getMessage()
        ], 500);
    }
}
}
