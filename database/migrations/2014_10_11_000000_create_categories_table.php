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
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique()->nullable(); // Cần cho URL thân thiện [cite: 68]
        $table->unsignedBigInteger('parent_id')->nullable(); // Cho danh mục đa cấp [cite: 55]
        $table->string('icon')->nullable(); // Icon hiển thị [cite: 77]
        $table->string('bg_color')->nullable(); // Màu nền UI [cite: 77]
        $table->string('image')->nullable(); // Cột đang bị thiếu dẫn đến lỗi hiện tại
        $table->text('description')->nullable();
        $table->timestamps();

        // Khóa ngoại cho parent_id
        $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
