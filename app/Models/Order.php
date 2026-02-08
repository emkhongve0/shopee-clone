<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus; // Import Enum để dùng casting

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'total_amount',
        'status',
        'shipping_status',
        'payment_status',
        'shipping_address',
        'note'
    ];

    /**
     * TỰ ĐỘNG CHUYỂN ĐỔI DỮ LIỆU (Casting)
     * Giúp hệ thống hiểu 'status' là một Enum object thay vì chuỗi thuần túy
     */
    protected $casts = [
        'status' => OrderStatus::class,
        'total_amount' => 'decimal:2',
        'shipping_status' => \App\Enums\ShippingStatus::class,
    ];

    /**
     * Mối quan hệ với người mua (User)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Mối quan hệ với chi tiết đơn hàng (Items)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
