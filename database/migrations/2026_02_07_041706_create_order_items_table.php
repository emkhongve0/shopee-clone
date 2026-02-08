<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        // Liên kết với bảng orders (Xóa đơn thì xóa luôn item)
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        // Liên kết với bảng products
        $table->foreignId('product_id')->constrained()->onDelete('cascade');

        $table->integer('quantity');
        $table->decimal('price', 15, 2); // Giá tại thời điểm mua
        $table->decimal('total', 15, 2); // Tổng tiền của item đó (price * quantity)
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
