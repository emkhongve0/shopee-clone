<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class CategoryProductSeeder extends Seeder
{
    public function run()
    {
        // Xóa dữ liệu cũ để tránh trùng lặp
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Brand::truncate();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. TẠO THƯƠNG HIỆU (Brands)
        $brandNames = ['Apple', 'Samsung', 'Oppo', 'Xiaomi', 'Sony', 'LG', 'Asus'];
        $brandIds = [];
        foreach ($brandNames as $name) {
            $brand = Brand::create(['name' => $name, 'slug' => Str::slug($name)]);
            $brandIds[] = $brand->id;
        }

        // 2. TẠO 12 DANH MỤC CHÍNH
        $mainCategories = [
            ['name' => 'Điện tử', 'slug' => 'dien-tu', 'icon' => 'fa-mobile-alt', 'bg_color' => 'bg-blue-100 text-blue-600'],
            ['name' => 'Thời trang', 'slug' => 'thoi-trang', 'icon' => 'fa-tshirt', 'bg_color' => 'bg-pink-100 text-pink-600'],
            ['name' => 'Nhà cửa', 'slug' => 'nha-cua', 'icon' => 'fa-home', 'bg_color' => 'bg-green-100 text-green-600'],
            ['name' => 'Sắc đẹp', 'slug' => 'sac-dep', 'icon' => 'fa-sparkles', 'bg_color' => 'bg-purple-100 text-purple-600'],
            ['name' => 'Thể thao', 'slug' => 'the-thao', 'icon' => 'fa-dumbbell', 'bg_color' => 'bg-red-100 text-red-600'],
            ['name' => 'Sách & Quà', 'slug' => 'sach-va-qua', 'icon' => 'fa-book', 'bg_color' => 'bg-yellow-100 text-yellow-600'],
            ['name' => 'Trò chơi', 'slug' => 'tro-choi', 'icon' => 'fa-gamepad', 'bg_color' => 'bg-indigo-100 text-indigo-600'],
            ['name' => 'Máy ảnh', 'slug' => 'may-anh', 'icon' => 'fa-camera', 'bg_color' => 'bg-cyan-100 text-cyan-600'],
            ['name' => 'Đồng hồ', 'slug' => 'dong-ho', 'icon' => 'fa-clock', 'bg_color' => 'bg-orange-100 text-orange-600'],
            ['name' => 'Âm thanh', 'slug' => 'am-thanh', 'icon' => 'fa-headphones', 'bg_color' => 'bg-teal-100 text-teal-600'],
            ['name' => 'Máy tính', 'slug' => 'may-tinh', 'icon' => 'fa-laptop', 'bg_color' => 'bg-gray-100 text-gray-600'],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'icon' => 'fa-shopping-bag', 'bg_color' => 'bg-rose-100 text-rose-600'],
        ];

        foreach ($mainCategories as $catData) {
            $category = Category::create($catData);

            // Tạo sản phẩm cho danh mục chính
            for ($i = 1; $i <= 5; $i++) {
                $this->createProduct($category->id, $brandIds, $category->name);
            }
        }

        // 4. TẠO 10 DANH MỤC CON (Phải nằm trong hàm run)
        $subCategoriesData = [
            ['name' => 'iPhone 15 Pro', 'parent_slug' => 'dien-tu'],
            ['name' => 'Laptop Gaming', 'parent_slug' => 'may-tinh'],
            ['name' => 'Váy Mùa Hè', 'parent_slug' => 'thoi-trang'],
            ['name' => 'Loa Bluetooth', 'parent_slug' => 'am-thanh'],
            ['name' => 'Đồng Hồ Nam', 'parent_slug' => 'dong-ho'],
            ['name' => 'Máy Ảnh Sony', 'parent_slug' => 'may-anh'],
            ['name' => 'Ghế Công Thái Học', 'parent_slug' => 'nha-cua'],
            ['name' => 'Sách Kỹ Năng', 'parent_slug' => 'sach-va-qua'],
            ['name' => 'Dụng Cụ Gym', 'parent_slug' => 'the-thao'],
            ['name' => 'Kem Chống Nắng', 'parent_slug' => 'sac-dep'],
        ];

        foreach ($subCategoriesData as $sub) {
            $parent = Category::where('slug', $sub['parent_slug'])->first();
            if ($parent) {
                $subCategory = Category::create([
                    'name' => $sub['name'],
                    'slug' => Str::slug($sub['name']) . '-' . rand(100, 999),
                    'parent_id' => $parent->id,
                    'icon' => null,
                    'bg_color' => null
                ]);

                // Tạo thêm sản phẩm cho chính danh mục con này
                for ($j = 1; $j <= 5; $j++) {
                    $this->createProduct($subCategory->id, $brandIds, $subCategory->name);
                }
            }
        }
    }

    // Hàm phụ để tạo sản phẩm nhanh
    private function createProduct($categoryId, $brandIds, $prefix)
    {
        Product::create([
            'category_id' => $categoryId,
            'brand_id' => $brandIds[array_rand($brandIds)],
            'name' => $prefix . ' - ' . Str::random(5),
            'slug' => Str::slug($prefix . ' ' . Str::random(10)),
            'image' => 'https://picsum.photos/400/400?random=' . rand(1, 1000),
            'price' => rand(500000, 20000000),
            'rating' => rand(3, 5),
            'status' => 1,
            'description' => 'Mô tả chi tiết cho ' . $prefix,
            'stock' => rand(10, 100)
        ]);
    }
}
