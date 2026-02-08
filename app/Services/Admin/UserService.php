<?php

namespace App\Services\Admin;

use App\Models\User;
use Carbon\Carbon;

class UserService
{
    /**
     * Lấy danh sách người dùng đã qua bộ lọc
     */
    // app/Services/Admin/UserService.php

public function getFilteredCustomers(array $filters)
{
    $query = \App\Models\User::query();

    // Chỉ lọc status nếu không phải 'all' và giá trị hợp lệ
    if (!empty($filters['status']) && $filters['status'] !== 'all') {
        // Convert string thành Enum trước khi query (Laravel sẽ tự match)
        $query->where('status', $filters['status']);
    }

    // 1. Lọc theo Vai trò (Role) - nếu không phải 'all'
    if (!empty($filters['role']) && $filters['role'] !== 'all') {
        $query->where('role', $filters['role']);
    }

    // 2. Lọc theo Tìm kiếm (Search)
    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // 3. Lọc theo Thời gian (Date Range)
    if (!empty($filters['dateRange']) && $filters['dateRange'] !== 'all') {
    $this->applyDateFilter($query, $filters['dateRange']);
}

    return $query->latest()->get();
}




    /**
     * Format dữ liệu chuẩn để "đổ" vào Alpine.js
     */
    public function formatForFrontend($customers)
{
    return $customers->map(function ($user) {
        // 1. Xử lý Status: Lấy cả Value (cho logic màu sắc) và Label (cho hiển thị)
        $statusValue = $user->status instanceof \BackedEnum ? $user->status->value : $user->status;

        // Nếu dùng Enum mới có hàm label(), gọi nó. Nếu không thì dùng giá trị mặc định.
        $statusLabel = $user->status instanceof \App\Enums\UserStatus
            ? $user->status->label()
            : ($statusValue === 'active' ? 'Đang hoạt động' : 'Đã khóa');

        // 2. Xử lý Role
        $roleValue = $user->role instanceof \BackedEnum ? $user->role->value : ($user->role ?? 'user');

        // Map tên role sang tiếng Việt (nếu cần hiển thị đẹp)
        $roleDisplay = match($roleValue) {
            'admin' => 'Quản trị viên',
            'staff' => 'Nhân viên',
            default => 'Khách hàng'
        };

        return [
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,

            // Role
            'role'           => $roleValue,      // Dùng để lọc: 'admin'
            'role_display'   => $roleDisplay,    // Dùng để hiện: 'Quản trị viên'

            // Status
            'status'         => $statusValue,    // Dùng để check logic/màu sắc: 'active'
            'status_label'   => $statusLabel,    // Dùng để hiện chữ: 'Đang hoạt động'

            // Đơn hàng
            'orders'         => (int)($user->orders_count ?? 0),

            // Tiền: Gửi 2 định dạng
            'totalSpent'     => number_format($user->total_spent ?? 0, 0, ',', '.') . '₫', // Để hiển thị
            'total_spent_raw'=> (float)($user->total_spent ?? 0), // Để sắp xếp (Sort) bên JS

            'phone'          => $user->phone ?? 'Chưa cập nhật',

            // Ngày tháng: Dùng optional để tránh lỗi null
            'createdAt'      => optional($user->created_at)->format('d/m/Y') ?? 'N/A',
            'lastLogin'      => optional($user->last_login_at)->format('d/m/Y') ?? 'Chưa đăng nhập',
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
    $now = Carbon::now(); // Dùng biến now để đồng bộ thời gian
    switch ($range) {
        case 'today':
            $query->whereDate('created_at', $now->today());
            break;
        case 'week':
            // startOfWeek() mặc định là Thứ 2, cẩn thận tùy config server
            $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
            break;
        case 'month':
            $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            break;
        case 'year':
            $query->whereYear('created_at', $now->year);
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
