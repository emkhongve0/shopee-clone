<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Chạy migration: Thêm cột vào Database
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
        // Kiểm tra xem cột 'phone' đã tồn tại chưa
        if (!Schema::hasColumn('users', 'phone')) {
            $table->string('phone')->nullable()->after('email');
        }
        // Kiểm tra xem cột 'gender' đã tồn tại chưa
        if (!Schema::hasColumn('users', 'gender')) {
            $table->string('gender')->nullable()->after('phone');
        }
        });
    }

    /**
     * Hoàn tác migration: Xóa cột nếu lỡ tay làm sai
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'gender']);
        });
    }
};
