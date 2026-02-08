<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Enums\OrderStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Lấy danh sách đơn hàng theo bộ lọc
     */
    public function getFilteredOrders(array $filters)
{
    $query = Order::with(['user', 'items.product'])->latest();

    // 1. Lọc theo IDs (Giữ nguyên)
    if (!empty($filters['ids'])) {
        $idArray = is_array($filters['ids']) ? $filters['ids'] : explode(',', $filters['ids']);
        $query->whereIn('id', $idArray);
        return $query->get();
    }

    // 2. Tìm kiếm (Giữ nguyên)
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

    // 3. LOGIC LỌC TRẠNG THÁI (Sửa tại đây)
    // Ưu tiên shipping_status nếu có, nếu không thì mới dùng status chung
    $statusFilter = null;
    if (!empty($filters['shipping_status']) && $filters['shipping_status'] !== 'all') {
        $statusFilter = $filters['shipping_status'];
    } elseif (!empty($filters['status']) && $filters['status'] !== 'all') {
        $statusFilter = $filters['status'];
    }

    if ($statusFilter) {
        $query->where('status', $statusFilter);
    }

    // 4. Lọc theo Trạng thái thanh toán & Phương thức (Giữ nguyên)
    if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'all') {
        $query->where('payment_status', $filters['payment_status']);
    }
    if (!empty($filters['payment_method']) && $filters['payment_method'] !== 'all') {
        $query->where('payment_method', $filters['payment_method']);
    }

    // 2. Lọc theo Tình trạng Giao hàng (Cột shipping_status - MỚI)
    if (!empty($filters['shipping_status']) && $filters['shipping_status'] !== 'all') {
        $query->where('shipping_status', $filters['shipping_status']);
    }

    return $query->get();
}

    /**
     * Lấy thống kê số lượng đơn hàng - GIỮ NGUYÊN LOGIC CŨ
     */
    public function getOrderStatistics(): array
    {
        return [
            'total'      => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipping'   => Order::where('status', 'shipped')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'canceled')->count(),
            'returned'   => Order::where('status', 'refunded')->count(),
        ];
    }

    /**
     * Định dạng dữ liệu để gửi sang Frontend (Alpine.js) - GIỮ NGUYÊN LOGIC CŨ
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

    // tạo đơn hàng mới
    public function storeOrder(array $data)
{
    return DB::transaction(function () use ($data) {
        // 1. Tạo đơn hàng chính
        $order = Order::create([
            'order_code'       => 'ORD-' . strtoupper(Str::random(8)),
            'user_id'          => $data['customer_id'] ?? null,
            'status'           => 'pending',
            'payment_status'   => 'pending',
            'payment_method'   => $data['payment_method'] ?? 'cod',
            'shipping_address' => $data['address'],
            'total_amount'     => 0, // Sẽ cập nhật sau khi tính item
            'notes'            => $data['notes'] ?? null,
        ]);

        $totalAmount = 0;

        // 2. Xử lý các sản phẩm trong đơn
        foreach ($data['items'] as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $lineTotal = $product->price * $item['quantity'];
            $totalAmount += $lineTotal;

            $order->items()->create([
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
                'total'      => $lineTotal,
            ]);

            // 3. Trừ tồn kho (Stock)
            $product->decrement('stock', $item['quantity']);
        }

        // 4. Cập nhật lại tổng tiền cuối cùng
        $order->update(['total_amount' => $totalAmount]);

        return $order;
    });
}

    /**
     * Cập nhật trạng thái đơn hàng - GIỮ NGUYÊN LOGIC CŨ
     */
    public function updateStatus(Order $order, string $status): bool
    {
        return $order->update(['status' => $status]);
    }

    /**
     * Thao tác hàng loạt - GIỮ NGUYÊN LOGIC CŨ
     */
    public function executeBulkOrderAction($action, array $ids, array $data = [])
{
    return DB::transaction(function () use ($action, $ids, $data) {
        // 1. Cập nhật TRẠNG THÁI ĐƠN HÀNG (Ví dụ: Chờ xử lý -> Hoàn thành)
        if ($action === 'update_status' && !empty($data['status'])) {
            // Laravel sẽ tự động chuyển đổi từ string sang Enum nếu giá trị khớp
            return Order::whereIn('id', $ids)->update(['status' => $data['status']]);
        }

        // 2. Cập nhật TÌNH TRẠNG GIAO HÀNG (Ví dụ: Chờ lấy hàng -> Đang giao -> Đã giao)
        if ($action === 'update_shipping' && !empty($data['shipping_status'])) {
            return Order::whereIn('id', $ids)->update([
                'shipping_status' => $data['shipping_status']
            ]);
        }

        // 3. Xử lý hủy đơn
        if ($action === 'cancel') {
            $orders = Order::whereIn('id', $ids)->get();
            foreach ($orders as $order) {
                // SỬA TẠI ĐÂY: Sử dụng Enum thay vì viết chuỗi "cancelled"
                // Giả sử case của bạn tên là CANCELLED hoặc CANCELED
                if ($order->status !== OrderStatus::CANCELLED) {
                    $order->update(['status' => OrderStatus::CANCELLED]);
                }
            }
            return true;
        }
        return false;
    });
}
}
