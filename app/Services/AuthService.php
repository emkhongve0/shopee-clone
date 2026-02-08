<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthService
{
    /**
     * Xử lý đăng ký thành viên
     */
    public function register(array $data)
    {
        // Log rõ ràng request nào, data gì (trừ password) [cite: 2]
        Log::info('Attempting to register user', ['email' => $data['email']]);

        try {
            // Logic tạo user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']), // Bảo mật password
                'role' => 'user', // Mặc định là user [cite: 2]
                'status' => 'active',
            ]);

            Log::info('User registered successfully', ['id' => $user->id]);

            return $user;

        } catch (Exception $e) {
            // Log lỗi stacktrace để debug [cite: 2]
            Log::error('Error registering user: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Ném lỗi ra để Controller bắt
        }
    }
}
