<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Có id riêng, không dùng email làm PK [cite: 2]
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();

            // Phân quyền & Trạng thái ngay từ đầu [cite: 2]
            $table->string('role')->default('user'); // admin, user, moderator
            $table->string('status')->default('active'); // active, inactive, banned
            $table->decimal('old_price', 15, 2)->nullable(); // Giá trước khi giảm
            $table->string('badge')->nullable();             // Nhãn: HOT, LIMITED...
            $table->integer('sold_ratio')->default(0);       // Tỉ lệ đã bán (0-100)
            $table->boolean('is_flash_sale')->default(false); // Đánh dấu sản phẩm Flash Sale

            $table->timestamp('email_verified_at')->nullable();
            $table->string('google_id')->nullable();
            $table->rememberToken();
            $table->timestamps(); // Có created_at, updated_at [cite: 2]
            $table->softDeletes(); // Soft delete để không xóa cứng dữ liệu
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
