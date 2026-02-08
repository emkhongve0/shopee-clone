<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run()
    {
        // 1. Xóa sạch dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Tạo Danh mục (Chỉ dùng name và image theo đúng bảng đã tạo)
        $categories = [
            [
                'name' => 'Điện thoại & Phụ kiện',
                'image' => 'https://down-cvs-vn.img.susercontent.com/vn-11134207-7r98o-lznbp628m3b7a5.webp'
            ],
            [
                'name' => 'Thời Trang Nam',
                'image' => 'https://down-cvs-vn.img.susercontent.com/vn-11134207-7r98o-lznbp628k02r57.webp'
            ],
            [
                'name' => 'Máy Tính & Laptop',
                'image' => 'https://down-cvs-vn.img.susercontent.com/vn-11134207-7r98o-lznbp628myvv9d.webp'
            ],
            [
                'name' => 'Mỹ Phẩm',
                'image' => 'https://down-cvs-vn.img.susercontent.com/vn-11134207-7r98o-lznbp628ibjje1.webp'
            ],
            [
                'name' => 'Giày Dép',
                'image' => 'https://down-cvs-vn.img.susercontent.com/vn-11134207-7r98o-lznbp628j5t38c.webp'
            ],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat['name'],
                // Tạo slug tự động từ tên (Ví dụ: "Điện thoại" -> "dien-thoai")
                'slug' => Str::slug($cat['name']),
                'image' => $cat['image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Tạo Sản phẩm mẫu
        $productNames = [
            'iPhone 15 Pro Max', 'Samsung Galaxy S24 Ultra', 'MacBook Air M2',
            'Chuột Logitech G502', 'Bàn phím cơ Keychron', 'Son Black Rouge',
            'Kem chống nắng Anessa', 'Giày Nike Air Force 1', 'Áo thun Polo Teelab',
            'Sạc dự phòng Anker'
        ];

        for ($i = 1; $i <= 20; $i++) {
            $name = $productNames[array_rand($productNames)] . ' - Mẫu ' . $i;

            DB::table('products')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'price' => rand(100, 5000) * 1000,
                'description' => 'Mô tả chi tiết sản phẩm. Hàng chính hãng 100%, bảo hành 12 tháng.',
                'image' => 'https://picsum.photos/300/300?random=' . $i,
                'category_id' => rand(1, 5), // Random danh mục từ 1-5
                'sold' => rand(10, 2000),
                'stock' => rand(50, 200),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
