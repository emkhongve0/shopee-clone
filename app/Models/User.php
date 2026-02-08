<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',
    'phone',
    'gender',
    'role',
    'status',
    'temp_password',
    'temp_password_expires_at',
    'total_spent',
    'orders_count',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // CẬP NHẬT MỚI: Tự động convert data sang Enum object
    protected $casts = [
        'role'   => UserRole::class,
        'status' => UserStatus::class,
        'temp_password_expires_at' => 'datetime',
    ];

    // CẬP NHẬT MỚI: Helper kiểm tra quyền nhanh cho Backend
    public function isSystemAdmin(): bool {
        // Ưu tiên check qua Spatie (HasRoles) nhưng cũng check qua cột role để bảo mật kép
        return $this->hasRole(UserRole::ADMIN->value) || $this->role === UserRole::ADMIN;
    }

    // Bắt buộc cho JWT
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function wishlists() {
    return $this->belongsToMany(Product::class, 'wishlists');
    }

    /**
     * Một khách hàng có nhiều đơn hàng
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
