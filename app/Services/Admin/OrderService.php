<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Collection;

class OrderService
{
    /**
     * Lấy danh sách đơn hàng theo bộ lọc
     */
    public function getFilteredOrders(array $filters)
    {
        // Eager load user và product để tránh N+1 Query
        $query = Order::with(['user', 'items.product'])->latest();

        // 1. Tìm kiếm (Mã đơn, Tên, Email, SĐT)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                           ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Lọc theo Trạng thái đơn hàng
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // 3. Lọc theo Trạng thái thanh toán
        if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'all') {
            $query->where('payment_status', $filters['payment_status']);
        }

        // 4. Lọc theo Phương thức thanh toán
        if (!empty($filters['payment_method']) && $filters['payment_method'] !== 'all') {
            $query->where('payment_method', $filters['payment_method']);
        }

        // 5. Lọc theo Tình trạng giao hàng
        if (!empty($filters['shipping_status']) && $filters['shipping_status'] !== 'all') {
            $query->where('shipping_status', $filters['shipping_status']);
        }

        return $query->get();
    }

    /**
     * Lấy thống kê số lượng đơn hàng
     */
    public function getOrderStatistics(): array
    {
        // Lưu ý: Đảm bảo các value của Enum khớp với DB của bạn
        return [
            'total'      => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(), // Hoặc dùng Enum::PENDING->value
            'processing' => Order::where('status', 'processing')->count(),
            'shipping'   => Order::whereIn('status', ['shipping', 'shipped'])->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::whereIn('status', ['canceled', 'cancelled'])->count(),
        ];
    }

    /**
     * Định dạng dữ liệu để gửi sang Frontend (Alpine.js)
     */
    public function formatOrdersForFrontend($orders): Collection
    {
        return $orders->map(function ($order) {
            return [
                'id'            => $order->id,
                'orderId'       => $order->order_code,
                'customer'      => [
                    'name'   => $order->user->name ?? 'Khách vãng lai',
                    'email'  => $order->user->email ?? 'N/A',
                    'phone'  => $order->user->phone ?? 'N/A',
                    'avatar' => null,
                ],
                'orderDate'     => $order->created_at->format('d/m/Y'),
                'orderTime'     => $order->created_at->format('H:i'),

                // Lấy value từ Enum nếu là Object, ngược lại lấy chính nó
                'orderStatus'   => $order->status instanceof OrderStatus ? $order->status->value : $order->status,

                'paymentStatus' => $order->payment_status,
                'paymentMethod' => $order->payment_method ?? 'N/A',
                'shippingStatus'=> $order->shipping_status ?? 'N/A',
                'total'         => $order->total_amount,

                'items'         => $order->items->map(function ($item) {
                    return [
                        'id'       => $item->id,
                        'name'     => $item->product->name ?? 'Sản phẩm đã xóa',
                        'price'    => $item->price,
                        'quantity' => $item->quantity,
                        'total'    => $item->total,
                        'image'    => $item->product->image ?? '',
                    ];
                }),

                'shippingAddress' => [
                    'street' => $order->shipping_address,
                    'city'   => 'Việt Nam',
                ],
            ];
        });
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Order $order, string $status): bool
    {
        return $order->update(['status' => $status]);
    }
}
