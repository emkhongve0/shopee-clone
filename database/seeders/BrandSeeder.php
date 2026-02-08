<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Facades\Schema;
// THÊM DÒNG NÀY VÀO:
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Brand::truncate();
        Schema::enableForeignKeyConstraints();

        $brands = ['Apple', 'Samsung', 'Sony', 'Oppo', 'Xiaomi', 'Vivo', 'Realme', 'Asus', 'Dell', 'HP', 'Logitech', 'Canon'];

        foreach ($brands as $name) {
            Brand::create([
                'name' => $name,
                'slug' => Str::slug($name) // Bây giờ Str sẽ không còn bị lỗi "Undefined"
            ]);
        }
    }
}
