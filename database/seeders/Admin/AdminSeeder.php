<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // 1. Khởi tạo Role Admin thông qua Spatie (Nếu chưa tồn tại)
            // Việc này giúp hệ thống phân quyền của bạn hoạt động đồng bộ
            $adminRole = Role::firstOrCreate(['name' => UserRole::ADMIN->value]);

            // 2. Khởi tạo tài khoản Admin tối cao
            // Dùng updateOrCreate để tránh lỗi trùng lặp khi chạy seed nhiều lần
            $admin = User::updateOrCreate(
                ['email' => 'admin@shopee-clone.com'], // Email định danh
                [
                    'name'     => 'Quản Trị Viên Hệ Thống',
                    'password' => Hash::make('Admin@2026'), // Mật khẩu cực kỳ bảo mật
                    'role'     => UserRole::ADMIN,         // Gán vào cột role (Enum)
                    'status'   => UserStatus::ACTIVE,      // Gán vào cột status (Enum)
                    'phone'    => '0988888888',
                    'gender'   => 'other',
                ]
            );

            // 3. Gán Role Spatie cho User
            // Điều này cho phép Middleware role:admin hoạt động chính xác
            if (!$admin->hasRole(UserRole::ADMIN->value)) {
                $admin->assignRole($adminRole);
            }

            $this->command->info('✅ Tài khoản Admin đã được tạo: admin@shopee-clone.com / Admin@2026');

        } catch (\Exception $e) {
            // Logging lỗi để debug nhanh (Tuân thủ nguyên tắc Logging của bạn)
            Log::error("Lỗi Seed Admin: " . $e->getMessage());
            $this->command->error('❌ Thất bại khi tạo tài khoản Admin. Xem Log để biết chi tiết.');
        }
    }
}
