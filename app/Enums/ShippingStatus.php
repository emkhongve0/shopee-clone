<?php

namespace App\Enums;

enum ShippingStatus: string
{
    case PENDING = 'pending';
    case READY_TO_SHIP = 'ready_to_ship'; // Chờ lấy hàng
    case PICKED_UP     = 'picked_up';     // Đã lấy hàng
    case SHIPPING      = 'shipping';      // Đang giao/Đang vận chuyển
    case DELIVERED     = 'delivered';     // Đã giao
    case RETURNED      = 'returned';      // Hoàn trả
    case CANCELLED     = 'cancelled';     // Hủy giao hàng

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xử lý',
            self::READY_TO_SHIP => 'Chờ lấy hàng',
            self::PICKED_UP     => 'Đã lấy hàng',
            self::SHIPPING      => 'Đang giao hàng',
            self::DELIVERED     => 'Đã giao thành công',
            self::RETURNED      => 'Đã hoàn trả',
            self::CANCELLED     => 'Đã hủy giao',
        };
    }
}
