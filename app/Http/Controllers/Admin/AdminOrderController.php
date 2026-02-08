<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
// Import Service Đơn hàng
use App\Services\Admin\OrderService;

class AdminOrderController extends Controller
{
    protected $orderService;

    /**
     * Inject OrderService (Không phải ProductService)
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index(Request $request)
    {
        // 1. Lấy dữ liệu thô (Query Database)
        // LƯU Ý: Phải gọi hàm của OrderService, KHÔNG GỌI getProductPageData()
        $rawOrders = $this->orderService->getFilteredOrders($request->all());

        // 2. Lấy thống kê
        $stats = $this->orderService->getOrderStatistics();

        // 3. Format dữ liệu cho Alpine.js (Hàm này bạn đã viết trong OrderService)
        $allOrders = $this->orderService->formatOrdersForFrontend($rawOrders);

        // Trả về View Đơn hàng (orders.index) chứ không phải products.index
        return view('admin.orders.index', compact('allOrders', 'stats'));
    }

    /**
     * Cập nhật trạng thái
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|string'
        ]);

        $this->orderService->updateStatus($order, $request->status);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng #' . $order->order_code);
    }

    /**
     * Xuất Excel
     */
    public function export(Request $request)
    {
        $orders = $this->orderService->getFilteredOrders($request->all());
        $fileName = 'danh-sach-don-hang-' . now()->format('d-m-Y-His') . '.xlsx';

        return Excel::download(new OrdersExport($orders), $fileName);
    }
}
