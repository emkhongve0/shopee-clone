<?php

namespace App\Enums;

enum UserStatus: string {
    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';
    case BANNED   = 'banned';

    // Hàm lấy tên hiển thị tiếng Việt
    public function label(): string
    {
        return match($this) {
            self::ACTIVE   => 'Đang hoạt động',
            self::INACTIVE => 'Ngừng hoạt động',
            self::BANNED   => 'Đã khóa',
        };
    }

    // Hàm lấy màu sắc cho Badge (dùng cho Tailwind/Bootstrap)
    public function color(): string
    {
        return match($this) {
            self::ACTIVE   => 'success', // Hoặc 'green'
            self::INACTIVE => 'secondary', // Hoặc 'gray'
            self::BANNED   => 'danger', // Hoặc 'red'
        };
    }
}
