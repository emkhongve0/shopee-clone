<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function getHomeProducts($limit = 12)
    {
        try {
            // Lấy sản phẩm đang hoạt động, mới nhất
            return Product::where('status', 1)
                ->latest()
                ->take($limit)
                ->get();
        } catch (\Exception $e) {
            Log::error("ProductService Error: " . $e->getMessage());
            return collect(); // Trả về collection trống để giao diện không bị lỗi trắng trang
        }
    }
}
