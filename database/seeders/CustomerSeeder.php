<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // Tạo 10 khách hàng mẫu
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name'     => "Khách hàng $i",
                'email'    => "customer$i@gmail.com",
                'password' => Hash::make('password'),
                'role'     => UserRole::CUSTOMER,
                'status'   => UserStatus::ACTIVE,
                'phone'    => '090000000' . $i,
            ]);
        }
        $this->command->info('✅ Đã tạo 10 khách hàng mẫu.');
    }
}
