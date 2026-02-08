<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'bg_color', 'parent_id'];

    /**
     * Quan hệ lấy các danh mục con trực tiếp
     * Giúp tránh lỗi pluck() khi gọi từ Controller
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Quan hệ lấy danh mục cha
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Quan hệ lấy các danh mục cùng nhóm (Anh em)
     * Dùng cho Sidebar khi đang ở trang danh mục con
     */
    public function siblings()
    {
        return $this->hasMany(Category::class, 'parent_id', 'parent_id')
                    ->where('id', '!=', $this->id);
    }

    /**
     * Quan hệ lấy sản phẩm thuộc danh mục
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
