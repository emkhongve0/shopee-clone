<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // 1. Lấy ID mặc định để tránh lỗi "Field doesn't have a default value"
        $categoryId = Category::first()->id ?? 1;
        $brandId = Brand::first()->id ?? 1;

        // 2. Định nghĩa danh sách sản phẩm với bộ khung chuẩn
        $products = [
            [
                'name' => 'Apple Watch Series 9 Chính Hãng',
                'price' => 2500000,
                'old_price' => 3500000,
                'badge' => 'HOT',
                'sold_ratio' => 85,
                'is_flash_sale' => true,
                'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=300',
            ],
            [
                'name' => 'Tai nghe không dây Premium Wireless Bluetooth 5.0',
                'price' => 850000,
                'old_price' => 1200000,
                'badge' => 'LIMITED',
                'sold_ratio' => 45,
                'is_flash_sale' => true,
                'image' => 'https://images.unsplash.com/photo-1578517581165-61ec5ab27a19?q=80&w=300',
            ],
            [
                'name' => 'Giày Chạy Bộ Nam Classic Sneakers 2024',
                'price' => 550000,
                'old_price' => 750000,
                'badge' => 'HOT',
                'sold_ratio' => 92,
                'is_flash_sale' => true,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=300',
            ],
            [
                'name' => 'Bàn Phím Cơ Gaming RGB Mechanical Keyboard',
                'price' => 790000,
                'old_price' => 990000,
                'badge' => 'LIMITED',
                'sold_ratio' => 15,
                'is_flash_sale' => true,
                'image' => 'https://images.unsplash.com/photo-1618384881928-343849003a11?q=80&w=300',
            ],
            // Sản phẩm thường (Gợi ý hôm nay) - is_flash_sale => false
            [
                'name' => 'Loa Bluetooth Mini Bass Cực Mạnh',
                'price' => 300000,
                'old_price' => 450000,
                'badge' => null,
                'sold_ratio' => 10,
                'is_flash_sale' => false,
                'image' => 'https://images.unsplash.com/photo-1608156639585-34052e81c99f?q=80&w=300',
            ],
        ];

        // 3. Vòng lặp tạo dữ liệu, tự động tạo slug và gắn ID ngoại
        foreach ($products as $p) {
            Product::create([
                'name'          => $p['name'],
                'slug'          => Str::slug($p['name']) . '-' . Str::random(5), // Tạo slug duy nhất
                'price'         => $p['price'],
                'old_price'     => $p['old_price'],
                'badge'         => $p['badge'],
                'sold_ratio'    => $p['sold_ratio'],
                'is_flash_sale' => $p['is_flash_sale'],
                'image'         => $p['image'],
                'category_id'   => $categoryId,
                'brand_id'      => $brandId,
                'status'        => 1, // Để hiện lên trang chủ
                'description'   => 'Mô tả sản phẩm chất lượng cao cho ' . $p['name'],
            ]);
        }
    }
}
