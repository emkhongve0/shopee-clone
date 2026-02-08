<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPING = 'shipped';
    case COMPLETED = 'completed';
    case CANCELLED = 'canceled';
    case REFUNDED = 'refunded';

    /**
     * Trả về nhãn tiếng Việt hiển thị trên giao diện
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xử lý',
            self::PROCESSING => 'Đang xử lý',
            self::SHIPPING => 'Đang giao hàng',
            self::COMPLETED => 'Hoàn thành',
            self::CANCELLED => 'Đã hủy',
            self::REFUNDED => 'Hoàn tiền',
        };
    }

    /**
     * Trả về mã màu Hex đại diện cho trạng thái (Dùng cho biểu đồ Chart.js)
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => '#f97316',      // Orange
            self::PROCESSING => '#3b82f6',   // Blue
            self::SHIPPING => '#6366f1',     // Indigo
            self::COMPLETED => '#10b981',    // Emerald
            self::CANCELLED => '#ef4444',    // Red
            self::REFUNDED => '#64748b',     // Slate
        };
    }

    /**
     * Trả về Tailwind classes cho badge (Dùng cho hiển thị trạng thái trong bảng)
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
            self::PROCESSING => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
            self::SHIPPING => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
            self::COMPLETED => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
            self::CANCELLED => 'bg-red-500/10 text-red-500 border-red-500/20',
            self::REFUNDED => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
        };
    }
}
