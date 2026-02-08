<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Cho phép nạp dữ liệu vào các trường này
    protected $fillable = [
        'name',
        'sku',
        'slug',
        'price',
        'image',
        'stock',
        'status',
        'old_price',
        'category_id',   // Thêm dòng này
        'badge',        // Thêm dòng này
        'sold_ratio',   // Thêm dòng này
        'is_flash_sale' // Thêm dòng này
    ];

    public function category()
    {
        // Laravel sẽ tự động tìm cột category_id trong bảng products
        return $this->belongsTo(Category::class);
    }


    public function likedByUsers() {
    return $this->belongsToMany(User::class, 'wishlists');
    }

    // Hàm kiểm tra xem user hiện tại có thích sản phẩm này không
    public function isLiked() {
    return $this->likedByUsers()->where('user_id', auth()->id())->exists();
    }

    // Accessor để tương thích code cũ: trả về trường image nếu có, hoặc image_url nếu còn sót
    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        // nếu tồn tại image_url (cũ) thì trả về nó
        return $this->attributes['image_url'] ?? null;
    }

    // Accessor để tương thích với 'is_active' boolean
    public function getIsActiveAttribute()
    {
        if (array_key_exists('is_active', $this->attributes)) {
            return (bool) $this->attributes['is_active'];
        }

        // fallback: sử dụng cột status (1 => active)
        return isset($this->attributes['status']) && (int)$this->attributes['status'] === 1;
    }
}
