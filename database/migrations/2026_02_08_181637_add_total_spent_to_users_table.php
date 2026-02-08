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
        Schema::table('users', function (Blueprint $table) {
        // Chỉ thêm nếu chưa có cột total_spent
        if (!Schema::hasColumn('users', 'total_spent')) {
            $table->decimal('total_spent', 15, 2)->default(0)->after('status');
        }

        // Chỉ thêm nếu chưa có cột orders_count
        if (!Schema::hasColumn('users', 'orders_count')) {
            $table->integer('orders_count')->default(0)->after('total_spent');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
