<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        try {
            // Thứ tự này được sắp xếp để đảm bảo tính toàn vẹn dữ liệu
            $this->call([
            // 0. System Core (Nền tảng hệ thống)
            \Database\Seeders\Admin\AdminSeeder::class,

            // 1. Tài khoản Người dùng (Cần có khách hàng để mua hàng)
            CustomerSeeder::class,   // Tạo 10-20 khách hàng mẫu

            // 2. Các bảng "Cha" (Dữ liệu danh mục và đối tác)
            CategorySeeder::class,   // Danh mục sản phẩm
            ShopSeeder::class,       // Cửa hàng
            BrandSeeder::class,      // Thương hiệu

            // 3. Các bảng "Con" (Phụ thuộc vào các bảng trên)
            ProductSeeder::class,    // Sản phẩm (cần Shop, Brand, Category)

            // 4. Các bảng trung gian & Giao dịch (Chạy cuối cùng)
            CategoryProductSeeder::class, // Gán n-n sản phẩm và danh mục
            OrderSeeder::class,           // ĐƠN HÀNG (Cần có Customer và Product)
            ]);

            $this->command->info('Toàn bộ dữ liệu mẫu đã được khởi tạo thành công!');

        } catch (\Exception $e) {
            // Bắt lỗi toàn cục để không làm "sập" tiến trình migration nếu 1 file seeder lỗi
            Log::error("DatabaseSeeder Error: " . $e->getMessage());
            $this->command->error('Phát hiện lỗi trong quá trình Seeding. Vui lòng kiểm tra Log.');
        }
    }
}
