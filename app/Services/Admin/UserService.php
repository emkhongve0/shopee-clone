<?php

namespace App\Services\Admin;

use App\Models\User;
use Carbon\Carbon;

class UserService
{
    /**
     * Lấy danh sách người dùng đã qua bộ lọc
     */
    public function getFilteredCustomers(array $filters)
{
    $query = User::query();

    // 1. Sửa logic Role: Hiện tất cả nếu chọn 'all'
    if (!empty($filters['role']) && $filters['role'] !== 'all') {
        $query->where('role', $filters['role']);
    }

    if (!empty($filters['status']) && $filters['status'] !== 'all') {
        $query->where('status', $filters['status']);
    }

    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    if (!empty($filters['dateRange']) && $filters['dateRange'] !== 'all') {
        $this->applyDateFilter($query, $filters['dateRange']);
    }

    // 2. QUAN TRỌNG: Bỏ withCount và withSum để dùng cột tĩnh trong database
    return $query->latest()
                 ->get();
}



    /**
     * Format dữ liệu chuẩn để "đổ" vào Alpine.js
     */
    public function formatForFrontend($customers)
{
    return $customers->map(function ($user) {
        // Ép kiểu status và role về string value
        $statusLabel = $user->status instanceof \BackedEnum ? $user->status->value : $user->status;
        $roleLabel = $user->role instanceof \BackedEnum ? $user->role->value : ($user->role ?? 'user');

        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'role'       => $roleLabel,
            'status'     => $statusLabel, // Chắc chắn là 'active' hoặc 'banned'
            'orders'     => (int)($user->orders_count ?? 0),
            'totalSpent' => number_format($user->total_spent ?? 0, 0, ',', '.') . '₫',
            'phone'      => $user->phone ?? 'N/A',
            'createdAt'  => $user->created_at->format('d/m/Y'),
            'lastLogin'  => $user->last_login_at ? $user->last_login_at->format('d/m/Y') : now()->format('d/m/Y'),
        ];
    });
}

    /**
     * Thống kê cho User Header
     */
    public function getQuickStats()
    {
        return [
            // Chỉ đếm những người có role là 'user' để thống kê chính xác cho trang khách hàng
            'total'     => User::where('role', 'user')->count(),
            'active'    => User::where('role', 'user')->where('status', 'active')->count(),
            'new_today' => User::where('role', 'user')->whereDate('created_at', Carbon::today())->count(),
        ];
    }

    private function applyDateFilter($query, $range)
    {
        switch ($range) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }
    }

    public function toggleStatus($id)
{
    $user = User::findOrFail($id);

    // Lấy giá trị chuỗi của status nếu nó là Enum, nếu không thì lấy trực tiếp
    $currentStatus = $user->status instanceof \BackedEnum ? $user->status->value : $user->status;

    // So sánh giá trị chuỗi
    $user->status = ($currentStatus === 'active') ? 'banned' : 'active';
    $user->save();

    return $user;
}

public function updateUser($id, array $data)
{
    $user = User::findOrFail($id);

    // Cập nhật các thông tin cơ bản
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->role = $data['role'];
    $user->status = $data['status'];
    $user->phone = $data['phone'];

    // Lưu ý: Các trường sau thường là dữ liệu tổng hợp,
    // nhưng sẽ cập nhật trực tiếp nếu bạn lưu chúng trong bảng users.
    if (isset($data['orders'])) $user->orders_count = $data['orders'];
    if (isset($data['total_spent'])) $user->total_spent = $data['total_spent'];
    if (isset($data['createdAt'])) $user->created_at = \Carbon\Carbon::createFromFormat('d/m/Y', $data['createdAt']);

    $user->save();
    return $user;
}


public function resetPassword($id)
{
    $user = User::findOrFail($id);
    // Đặt mật khẩu mặc định (ví dụ: 123456) hoặc tạo ngẫu nhiên
    $user->password = bcrypt('123456');
    $user->save();

    return $user;
}
public function updateMember($id, array $data)
{
    $user = \App\Models\User::findOrFail($id);

    $user->name = $data['name'] ?? $user->name;
    $user->email = $data['email'] ?? $user->email;
    $user->phone = $data['phone'] ?? $user->phone;
    $user->role = $data['role'] ?? $user->role;
    $user->status = $data['status'] ?? $user->status;

    if (isset($data['totalSpent'])) {
        $cleanAmount = preg_replace('/[^0-9]/', '', (string)$data['totalSpent']);
        $user->total_spent = (float)$cleanAmount;
    }

    if (isset($data['orders'])) {
        $user->orders_count = (int)$data['orders'];
    }

    $user->save();

    // TRẢ VỀ ĐỐI TƯỢNG (OBJECT), KHÔNG TRẢ VỀ MẢNG
    return $user;
}


public function deleteUser($id)
{
    // Tìm người dùng, nếu không thấy sẽ trả về lỗi 404
    $user = \App\Models\User::findOrFail($id);

    // Thực hiện xóa khỏi Database
    return $user->delete();
}
}
