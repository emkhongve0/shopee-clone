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
        // Kiểm tra: Nếu CHƯA CÓ cột shipping_status thì mới chạy lệnh add
        if (!Schema::hasColumn('orders', 'shipping_status')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_status')->default('ready_to_ship')->after('status');
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'shipping_status')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_status');
        });
    }
    }
};
