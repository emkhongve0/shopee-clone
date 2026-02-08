<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // 1. Lấy khách hàng và sản phẩm
        $customers = User::where('role', UserRole::CUSTOMER)->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->error('Lỗi: Cần có Customer và Product trước!');
            return;
        }

        $this->command->info('Đang bơm 50 đơn hàng ngẫu nhiên...');

        // 2. Tạo 50 đơn hàng
        for ($i = 0; $i < 50; $i++) {
            DB::transaction(function () use ($customers, $products) {
                $customer = $customers->random();

                // Rải ngày ngẫu nhiên trong 60 ngày qua để biểu đồ đẹp
                $createdAt = Carbon::now()->subDays(rand(0, 60))->subHours(rand(0, 23));

                // Lấy ngẫu nhiên 1 trạng thái từ Enum (Đã khớp PENDING, SHIPPED, CANCELED...)
                $status = collect(OrderStatus::cases())->random();

                $order = Order::create([
                    'order_code'       => 'ORD-' . strtoupper(bin2hex(random_bytes(4))),
                    'user_id'          => $customer->id,
                    'total_amount'     => 0, // Cập nhật sau
                    'status'           => $status->value,
                    'payment_status'   => rand(0, 1) ? 'paid' : 'pending',
                    'shipping_address' => 'Địa chỉ khách ' . $customer->name . ', Quận ' . rand(1, 12) . ', TP.HCM',
                    'created_at'       => $createdAt,
                    'updated_at'       => $createdAt,
                ]);

                $orderTotal = 0;
                $itemCount = rand(1, 4); // Mỗi đơn có 1-4 món

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $subTotal = $price * $quantity;

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $quantity,
                        'price'      => $price,
                        'total'      => $subTotal,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    $orderTotal += $subTotal;
                }

                // Chốt tổng tiền
                $order->update(['total_amount' => $orderTotal]);
            });
        }

        $this->command->info('✅ Đã tạo xong dữ liệu thật! Hãy kiểm tra Dashboard.');
    }
}
