<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price', // Lưu giá tại thời điểm mua (quan trọng!)
        'total'
    ];

    /**
     * Quan hệ với Đơn hàng tổng
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ với Sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
