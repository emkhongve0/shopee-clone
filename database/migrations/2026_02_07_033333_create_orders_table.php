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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_code')->unique();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->decimal('total_amount', 15, 2);
        // Trạng thái dùng string để khớp với Enum (pending, completed, etc.)
        $table->string('status')->default('pending');
        $table->string('payment_status')->default('unpaid');
        $table->text('shipping_address');
        $table->timestamps();

        // Index để tăng tốc độ truy vấn cho Dashboard (Bảo mật & Hiệu năng)
        $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
