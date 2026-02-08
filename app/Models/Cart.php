<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // Liên kết với Sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Liên kết với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
