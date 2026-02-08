<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique()->nullable();
        $table->string('image')->nullable();
        $table->decimal('price', 15, 0); // Giá hiện tại
        $table->decimal('original_price', 15, 0)->nullable(); // Giá gốc để hiển thị giảm giá
        $table->integer('discount')->default(0); // Phần trăm giảm giá
        $table->text('description')->nullable();

        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
        // --- THÊM CÁC CỘT NÀY ĐỂ FIX LỖI ---
        $table->decimal('old_price', 15, 2)->nullable(); // Giá cũ
        $table->string('badge')->nullable();             // Nhãn (HOT, LIMITED)
        $table->integer('sold_ratio')->default(0);       // % đã bán
        $table->boolean('is_flash_sale')->default(false);// Đánh dấu Flash Sale
        // ------------------------------------

        $table->integer('sold')->default(0);
        $table->decimal('rating', 3, 1)->default(0); // Đánh giá sao (VD: 4.8)
        $table->integer('stock')->default(100);
        $table->integer('status')->default(1); // Trạng thái sản phẩm (1: Hoạt động) [cite: 79]
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
