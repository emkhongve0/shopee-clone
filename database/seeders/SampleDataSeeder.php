<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Tạo 10 khách hàng test
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $users[] = User::create([
                'name' => "Khách hàng $i",
                'email' => "customer$i@example.com",
                'password' => bcrypt('password'),
                'address' => "$i Đường ABC, Quận 1, TP.HCM", // Cột address vừa thêm
            ]);
        }

        // 2. Tạo 20 sản phẩm test
        $products = [];
        for ($i = 1; $i <= 20; $i++) {
            $products[] = Product::create([
                'name' => "Sản phẩm Tech $i",
                'sku' => "SKU-" . strtoupper(Str::random(5)),
                'price' => rand(100000, 5000000),
                'stock' => rand(10, 100),
                'image' => "https://picsum.photos/200/200?random=$i",
            ]);
        }

        // 3. Tạo 50 đơn hàng giả lập với các trạng thái khác nhau
        $statuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];

        for ($i = 1; $i <= 50; $i++) {
            $user = $users[array_rand($users)];
            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'status' => $statuses[array_rand($statuses)],
                'payment_status' => rand(0, 1) ? 'paid' : 'pending',
                'payment_method' => 'bank_transfer',
                'shipping_address' => $user->address,
                'total_amount' => 0, // Sẽ cộng dồn sau
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Tạo từ 1 đến 3 sản phẩm cho mỗi đơn hàng
            $total = 0;
            $itemsCount = rand(1, 3);
            for ($j = 0; $j < $itemsCount; $j++) {
                $product = $products[array_rand($products)];
                $qty = rand(1, 5);
                $subtotal = $product->price * $qty;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'total' => $subtotal,
                ]);
            }
            $order->update(['total_amount' => $total]);
        }
    }
}
