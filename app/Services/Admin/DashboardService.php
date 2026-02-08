<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    /**
     * Phương thức chính lấy toàn bộ dữ liệu Dashboard
     */
    public function getDashboardData(): array
    {
        try {
            return [
                // 1. KPI (Đã bổ sung tính tăng trưởng cho Đơn hàng & Khách)
                'kpis'          => $this->getKpiStats(),

                // 2. Biểu đồ doanh thu
                'revenue'       => $this->getRevenueChartData(),

                // 3. [QUAN TRỌNG] Biểu đồ trạng thái (Đã update để khớp với Chart.js)
                'chartData'     => $this->getOrderStatusChartData(),

                // 4. Biểu đồ danh mục (Giữ nguyên logic chuẩn của bạn)
                'categorySales' => $this->getSalesByCategory(),

                // 5. Các bảng dữ liệu khác
                'topProducts'   => $this->getTopSellingProducts(),
                'lowStock'      => $this->getLowStockItems(),
                'recentOrders'  => $this->getRecentOrders(),

                // Dữ liệu thô trạng thái (nếu cần dùng ở chỗ khác)
                'orderStatusRaw'=> $this->getOrderStatusBreakdown(),
            ];
        } catch (\Exception $e) {
            Log::error("Dashboard Service Error: " . $e->getMessage());
            return $this->getFallbackData();
        }
    }

    /**
     * 1. Thống kê KPI & Tính tăng trưởng (Growth)
     */
    private function getKpiStats(): array
    {
        $now = Carbon::now();
        $thisMonth = $now->month;
        $thisYear  = $now->year;

        $lastMonthDate = $now->copy()->subMonth();
        $lastMonth = $lastMonthDate->month;
        $lastMonthYear = $lastMonthDate->year;

        // --- A. DOANH THU ---
        $revenueCurrent = Order::where('status', 'completed') // Hoặc dùng Enum value
            ->whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->sum('total_amount');
        $revenueLast = Order::where('status', 'completed')
            ->whereMonth('created_at', $lastMonth)->whereYear('created_at', $lastMonthYear)->sum('total_amount');

        // --- B. ĐƠN HÀNG ---
        $ordersCurrent = Order::whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->count();
        $ordersLast    = Order::whereMonth('created_at', $lastMonth)->whereYear('created_at', $lastMonthYear)->count();

        // --- C. KHÁCH HÀNG ---
        // Giả sử role 'customer' là khách hàng
        $customersCurrent = User::where('role', 'customer')->whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->count();
        $customersLast    = User::where('role', 'customer')->whereMonth('created_at', $lastMonth)->whereYear('created_at', $lastMonthYear)->count();

        // --- D. TỶ LỆ CHUYỂN ĐỔI (Đơn hàng / Tổng User mới * 100) ---
        $conversionRate = ($customersCurrent > 0) ? ($ordersCurrent / $customersCurrent) * 100 : 0;

        return [
            'total_revenue' => [
                'value'  => $revenueCurrent,
                'change' => $this->calculateGrowth($revenueCurrent, $revenueLast)
            ],
            'total_orders' => [
                'value'  => $ordersCurrent,
                'change' => $this->calculateGrowth($ordersCurrent, $ordersLast)
            ],
            'total_customers' => [
                'value'  => User::where('role', 'customer')->count(), // Tổng user toàn thời gian
                'change' => $this->calculateGrowth($customersCurrent, $customersLast) // Tăng trưởng so với tháng trước
            ],
            'conversion_rate' => [
                'value'  => number_format($conversionRate, 1) . '%',
                'change' => '0' // KPI này thường khó tính growth chính xác theo tháng
            ],
        ];
    }

    /**
     * Hàm phụ trợ tính phần trăm tăng trưởng
     */
    private function calculateGrowth($current, $last)
    {
        if ($last > 0) {
            $growth = (($current - $last) / $last) * 100;
        } else {
            $growth = ($current > 0) ? 100 : 0;
        }
        return ($growth >= 0 ? '+' : '') . number_format($growth, 1) . '%';
    }

    /**
     * 2. [UPDATE] Xử lý dữ liệu cho Biểu đồ tròn (Màu sắc & Label)
     * Hàm này thay thế cho logic cũ trong Controller
     */
    private function getOrderStatusChartData(): array
    {
        // Lấy số liệu thô
        $rawCounts = [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::whereIn('status', ['shipping', 'shipped'])->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::whereIn('status', ['canceled', 'cancelled'])->count(),
            'refunded'   => Order::where('status', 'refunded')->count(),
        ];

        // Cấu hình hiển thị (Màu sắc & Label tiếng Việt)
        $config = [
            'pending'    => ['label' => 'Chờ xử lý',    'color' => '#f97316'], // Cam
            'processing' => ['label' => 'Đang xử lý',   'color' => '#3b82f6'], // Xanh dương
            'shipped'    => ['label' => 'Đang giao',    'color' => '#6366f1'], // Tím
            'completed'  => ['label' => 'Hoàn thành',   'color' => '#10b981'], // Xanh lá
            'cancelled'  => ['label' => 'Đã hủy',       'color' => '#ef4444'], // Đỏ
            'refunded'   => ['label' => 'Hoàn tiền',    'color' => '#ec4899'], // Hồng
        ];

        $labels = [];
        $data = [];
        $backgroundColor = [];
        $details = [];
        $total = array_sum($rawCounts);

        foreach ($config as $key => $meta) {
            $count = $rawCounts[$key] ?? 0;
            if ($count > 0) {
                $labels[] = $meta['label'];
                $data[] = $count;
                $backgroundColor[] = $meta['color'];

                $details[] = [
                    'label'   => $meta['label'],
                    'count'   => $count,
                    'color'   => $meta['color'],
                    'percent' => $total > 0 ? round(($count / $total) * 100, 1) : 0
                ];
            }
        }

        // Nếu không có dữ liệu -> Trả về mảng rỗng để View hiển thị trạng thái "Chưa có đơn"
        if (empty($details)) return [];

        return [
            'labels' => $labels,
            'data' => $data,
            'backgroundColor' => $backgroundColor,
            'details' => $details
        ];
    }

    /**
     * 3. Biểu đồ doanh thu 7 ngày gần nhất
     */
    private function getRevenueChartData(): array
    {
        $days = collect(range(6, 0))->map(function($i) {
            return Carbon::now()->subDays($i)->format('Y-m-d');
        });

        $sales = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->pluck('revenue', 'date');

        $dailyData = $days->map(function($date) use ($sales) {
            return [
                'name'    => Carbon::parse($date)->format('d/m'), // Format ngày/tháng ngắn gọn
                'revenue' => (int) ($sales[$date] ?? 0)
            ];
        })->toArray();

        return [
            'daily'   => $dailyData,
            'weekly'  => [], // Placeholder cho tương lai
            'monthly' => []  // Placeholder cho tương lai
        ];
    }

    /**
     * 4. Sản phẩm bán chạy
     */
    private function getTopSellingProducts()
    {
        return OrderItem::select('product_id', DB::raw('SUM(quantity) as sales'), DB::raw('SUM(total) as revenue'))
            ->with(['product:id,name,image,category_id']) // Eager load nhẹ
            ->groupBy('product_id')
            ->orderByDesc('sales')
            ->take(5)
            ->get();
    }

    /**
     * 5. Cảnh báo kho thấp
     */
    private function getLowStockItems()
    {
        return Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
    }

    /**
     * 6. Đơn hàng mới nhất
     */
    private function getRecentOrders()
    {
        return Order::with('user:id,name,email')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * 7. Doanh thu theo danh mục (Logic chuẩn)
     */
    public function getSalesByCategory(): array
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where(function($q) {
                $q->where('orders.payment_status', 'paid')
                  ->orWhere('orders.status', 'completed');
            })
            ->select(
                'categories.name as category',
                DB::raw('SUM(order_items.total) as sales'),
                DB::raw('COUNT(DISTINCT orders.id) as orders')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('sales')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category,
                    'sales'    => (int) $item->sales,
                    'orders'   => (int) $item->orders,
                ];
            })
            ->toArray();
    }

    /**
     * Dữ liệu thô (nếu cần)
     */
    private function getOrderStatusBreakdown(): array
    {
        // Có thể giữ lại nếu bạn cần thống kê thô ở đâu đó
        // Nhưng Dashboard chính sẽ dùng getOrderStatusChartData
        return [];
    }

    /**
     * Dữ liệu dự phòng khi lỗi (Fallback)
     */
    public function getFallbackData(): array
    {
        return [
            'kpis' => [
                'total_revenue'   => ['value' => 0, 'change' => '0'],
                'total_orders'    => ['value' => 0, 'change' => '0'],
                'total_customers' => ['value' => 0, 'change' => '0'],
                'conversion_rate' => ['value' => '0%', 'change' => '0'],
            ],
            'revenue'       => ['daily' => []],
            'chartData'     => [],
            'categorySales' => [],
            'topProducts'   => [],
            'lowStock'      => [],
            'recentOrders'  => [],
            'orderStatusRaw'=> []
        ];
    }
}
