<?php

namespace App\Services\Admin;

use App\Models\User;
use Carbon\Carbon;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserService
{
    /**
     * Lấy danh sách người dùng đã qua bộ lọc
     */
    // app/Services/Admin/UserService.php

public function getFilteredCustomers(array $filters)
    {
        $query = User::query();

        // LOGIC CHUẨN: Đếm đơn hoàn thành và Tổng tiền thực chi
        $query->withCount(['orders as orders_count' => function ($q) {
            $q->where('status', 'completed');
        }])->withSum(['orders as total_spent' => function ($q) {
            $q->where('status', 'completed');
        }], 'total_amount');

        // Lọc Status
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // Lọc Role
        if (!empty($filters['role']) && $filters['role'] !== 'all') {
            $query->where('role', $filters['role']);
        }

        // Tìm kiếm
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc Thời gian
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
            $statusValue = $user->status instanceof \BackedEnum ? $user->status->value : $user->status;
            $roleValue = $user->role instanceof \BackedEnum ? $user->role->value : ($user->role ?? 'user');

            $roleDisplay = match($roleValue) {
                'admin' => 'Quản trị viên',
                'staff' => 'Nhân viên',
                default => 'Khách hàng'
            };

            return [
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'role'           => $roleValue,
                'role_display'   => $roleDisplay,
                'status'         => $statusValue,
                'status_label'   => $statusValue === 'active' ? 'Đang hoạt động' : 'Đã khóa',

                // DATA CHUẨN: Lấy từ kết quả withCount/withSum ở trên
                'orders'         => (int)($user->orders_count ?? 0),
                'totalSpent'     => number_format($user->total_spent ?? 0, 0, ',', '.') . '₫',
                'total_spent_raw'=> (float)($user->total_spent ?? 0),

                'phone'          => $user->phone ?? 'Chưa cập nhật',
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
        $now = Carbon::now();
        // Chỉ thống kê những người thực sự là khách hàng (role user)
        $baseQuery = User::where('role', 'user');

        return [
            'total'          => (clone $baseQuery)->count(),
            'active'         => (clone $baseQuery)->where('status', 'active')->count(),
            'new_today'      => (clone $baseQuery)->whereDate('created_at', Carbon::today())->count(),
            'new_this_month' => (clone $baseQuery)->whereMonth('created_at', $now->month)
                                                  ->whereYear('created_at', $now->year)->count(),
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
// app/Services/Admin/UserService.php

public function updateMember($id, array $data)
    {
        $user = User::findOrFail($id);

        $user->name = $data['name'] ?? $user->name;
        $user->email = $data['email'] ?? $user->email;
        $user->phone = (!empty($data['phone'])) ? $data['phone'] : ($user->phone ?? 'Chưa cập nhật');
        $user->role = $data['role'] ?? $user->role;
        $user->status = $data['status'] ?? $user->status;

        $user->save();

        // Đồng bộ Role Spatie (nếu có)
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$user->role]);
        }

        // Quan trọng: Load lại thống kê đơn hàng để Panel cập nhật ngay
        return $user->loadCount(['orders as orders_count' => function ($q) {
            $q->where('status', 'completed');
        }])->loadSum(['orders as total_spent' => function ($q) {
            $q->where('status', 'completed');
        }], 'total_amount');
    }

public function createMember(array $data)
{
    // 1. Khởi tạo đối tượng mới
    $user = new \App\Models\User();

    // 2. Gán các thông tin cơ bản (Logic cũ)
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->phone = $data['phone'] ?? 'Chưa cập nhật';
    $user->password = \Illuminate\Support\Facades\Hash::make('123456');

    // 3. Gán Role và Status (Laravel tự convert String sang Enum nếu Model có cast)
    $user->role = $data['role'] ?? 'user';
    $user->status = $data['status'] ?? 'active';

    // 4. Giữ nguyên logic xử lý dữ liệu số (Logic cũ)
    if (isset($data['totalSpent'])) {
        $cleanAmount = preg_replace('/[^0-9]/', '', (string)$data['totalSpent']);
        $user->total_spent = (float)$cleanAmount;
    }

    if (isset($data['orders'])) {
        $user->orders_count = (int)$data['orders'];
    }

    // 5. QUAN TRỌNG: Lưu User vào Database trước để lấy ID
    $user->save();

    // 6. Sau khi đã lưu (có ID), mới gán quyền cho Spatie Middleware (Sửa lỗi 403)
    if (method_exists($user, 'assignRole')) {
        // Gán đúng vai trò được chọn từ form (admin, staff, user,...)
        $user->assignRole($data['role']);
    }

    return $user;
}

public function exportUsersExcel(array $filters)
{
    $users = $this->getFilteredCustomers($filters);
    $fileName = 'Danh_sach_nguoi_dung_' . now()->format('Ymd_His') . '.xlsx';

    // Trả về đối tượng download của thư viện
    return Excel::download(new UsersExport($users), $fileName);
}

    public function deleteUser($id)
{
    // Tìm người dùng, nếu không thấy sẽ trả về lỗi 404
    $user = \App\Models\User::findOrFail($id);

    // Thực hiện xóa khỏi Database
    return $user->delete();
}
}
