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
        Schema::table('orders', function (Blueprint $table) {
        // Thêm các cột nếu chưa có
        if (!Schema::hasColumn('orders', 'payment_status')) {
            $table->string('payment_status')->default('pending')->after('status');
        }
        if (!Schema::hasColumn('orders', 'shipping_status')) {
            $table->string('shipping_status')->default('pending')->after('payment_status');
        }
        if (!Schema::hasColumn('orders', 'payment_method')) {
            $table->string('payment_method')->default('cod')->after('shipping_status');
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
