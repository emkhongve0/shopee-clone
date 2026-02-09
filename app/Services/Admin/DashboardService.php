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
    /**
 * 1. Thống kê KPI - Logic chuẩn Buyer (Người mua thực)
 * Tính toán dựa trên hành vi mua hàng và vai trò 'user'
 */
private function getKpiStats(): array
{
    $now = Carbon::now();
    $thisMonth = $now->month;
    $thisYear  = $now->year;

    $lastMonthDate = $now->copy()->subMonth();
    $lastMonth = $lastMonthDate->month;
    $lastMonthYear = $lastMonthDate->year;

    // --- A. DOANH THU (Chỉ tính các đơn đã hoàn thành) ---
    $revenueCurrent = Order::where('status', 'completed')
        ->whereMonth('created_at', $thisMonth)
        ->whereYear('created_at', $thisYear)
        ->sum('total_amount');

    $revenueLast = Order::where('status', 'completed')
        ->whereMonth('created_at', $lastMonth)
        ->whereYear('created_at', $lastMonthYear)
        ->sum('total_amount');

    // --- B. ĐƠN HÀNG (Tổng số đơn phát sinh trong tháng) ---
    $ordersCurrent = Order::whereMonth('created_at', $thisMonth)
        ->whereYear('created_at', $thisYear)
        ->count();

    $ordersLast = Order::whereMonth('created_at', $lastMonth)
        ->whereYear('created_at', $lastMonthYear)
        ->count();

    // --- C. KHÁCH HÀNG (Logic: Người dùng có vai trò 'user' và đã từng mua hàng) ---
    // 1. Tổng số "Người mua thực" từ trước đến nay
    $totalRealBuyers = User::where('role', 'user')
        ->whereHas('orders', function($q) {
            $q->where('status', 'completed');
        })->count();

    // 2. Số người thực hiện đơn hàng thành công đầu tiên trong tháng này
    $buyersThisMonth = User::where('role', 'user')
        ->whereHas('orders', function($q) use ($thisMonth, $thisYear) {
            $q->whereMonth('created_at', $thisMonth)
              ->whereYear('created_at', $thisYear)
              ->where('status', 'completed');
        })->count();

    // 3. Số người thực hiện đơn hàng thành công đầu tiên trong tháng trước
    $buyersLastMonth = User::where('role', 'user')
        ->whereHas('orders', function($q) use ($lastMonth, $lastMonthYear) {
            $q->whereMonth('created_at', $lastMonth)
              ->whereYear('created_at', $lastMonthYear)
              ->where('status', 'completed');
        })->count();

    // --- D. TỶ LỆ CHUYỂN ĐỔI (Chuẩn sàn lớn: Buyer mới / Người đăng ký mới) ---
    // Lấy tổng số người đăng ký mới (role 'user') trong tháng
    $newRegistersThisMonth = User::where('role', 'user')
        ->whereMonth('created_at', $thisMonth)
        ->whereYear('created_at', $thisYear)
        ->count();

    // Tỷ lệ chuyển đổi = (Số người mua mới / Số người đăng ký mới) * 100
    $conversionRate = ($newRegistersThisMonth > 0)
        ? ($buyersThisMonth / $newRegistersThisMonth) * 100
        : 0;

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
            'value'  => $totalRealBuyers,
            'change' => $this->calculateGrowth($buyersThisMonth, $buyersLastMonth)
        ],
        'conversion_rate' => [
            'value'  => number_format($conversionRate, 1) . '%',
            'change' => $this->calculateGrowth($buyersThisMonth, $buyersLastMonth)
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
        return OrderItem::select('product_id',
                DB::raw('SUM(quantity) as total_sales'),
                DB::raw('SUM(total) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_sales') // Ưu tiên sản phẩm bán được nhiều nhất
            ->take(5)
            ->with(['product' => function($q) {
                // Lấy thông tin sản phẩm và danh mục liên quan
                $q->select('id', 'name', 'image', 'category_id')
                  ->with('category:id,name');
            }])
            ->get()
            ->map(function($item) {
                $product = $item->product;

                return [
                    'name'     => $product->name ?? 'Sản phẩm không tồn tại',
                    // Kiểm tra và lấy đường dẫn ảnh sản phẩm
                    'image'    => $product ? ($product->image_url ?? $product->image) : null,
                    'category' => $product->category->name ?? 'Chưa phân loại',
                    // Định dạng hiển thị số lượng và doanh thu
                    'sales'    => number_format($item->total_sales),
                    'revenue'  => number_format($item->total_revenue, 0, ',', '.') . '₫',
                ];
            })
            ->toArray(); // Chuyển sang mảng để Blade Component dễ dàng truy cập $product['key']
    }

    /**
     * 5. Cảnh báo kho thấp
     */
    private function getLowStockItems()
    {
        // Ngưỡng cảnh báo (có thể chỉnh sửa tùy nhu cầu)
        $threshold = 10;

        return Product::where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc') // Ưu tiên hiện sản phẩm còn ít nhất lên đầu
            ->take(5)
            ->get()
            ->map(function($product) use ($threshold) {
                return [
                    'name'      => $product->name,
                    'sku'       => $product->sku ?? 'N/A', // Đảm bảo có SKU để hiển thị
                    'stock'     => (int) $product->stock,
                    'threshold' => $threshold, // Truyền ngưỡng để hiển thị "X of 10"
                ];
            })
            ->toArray();
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
