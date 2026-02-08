<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str; // Quan trọng: Để dùng hàm tạo slug

class CategorySeeder extends Seeder
{
    public function run()
    {
        // 1. Dọn dẹp bảng cũ
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // --- NHÓM 1: ĐIỆN THOẠI & PHỤ KIỆN ---
        $rootPhone = Category::create([
            'name' => 'Điện thoại & Phụ kiện',
            'slug' => Str::slug('Điện thoại & Phụ kiện'), // Tạo: dien-thoai-phu-kien
            'parent_id' => null,
            'icon' => 'fa-mobile-alt',
            'bg_color' => 'bg-blue-100 text-blue-600',
            'image' => 'https://down-cvs-vn.img.susercontent.com/vn-11134207-7r98o-lznbp628m3b7a5.webp'
        ]);

        $phoneGroup = ['Điện thoại', 'Máy tính bảng', 'Sạc & Cáp', 'Tai nghe', 'Pin dự phòng'];
        foreach ($phoneGroup as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'parent_id' => $rootPhone->id,
                'icon' => 'fa-chevron-right',
                'bg_color' => 'bg-gray-50 text-gray-500',
                'image' => 'https://picsum.photos/200' // Ảnh giả lập
            ]);
        }

        // --- NHÓM 2: THỜI TRANG (Để fix lỗi 404 của bạn) ---
        $rootFashion = Category::create([
            'name' => 'Thời trang',
            'slug' => Str::slug('Thời trang'), // Tạo slug: thoi-trang
            'parent_id' => null,
            'icon' => 'fa-tshirt',
            'bg_color' => 'bg-red-100 text-red-600',
            'image' => 'https://down-cvs-vn.img.susercontent.com/vn-11134207-7r98o-lznf0l6j6j6j6j.webp'
        ]);

        $fashionGroup = ['Thời trang Nam', 'Thời trang Nữ', 'Đồng hồ', 'Giày dép'];
        foreach ($fashionGroup as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'parent_id' => $rootFashion->id,
                'icon' => 'fa-chevron-right',
                'bg_color' => 'bg-gray-50 text-gray-500',
                'image' => 'https://picsum.photos/200'
            ]);
        }
    }
}
