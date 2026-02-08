<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Admin\OrderService;
use Spatie\Browsershot\Browsershot;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $rawOrders = $this->orderService->getFilteredOrders($request->all());
        $allOrders = $this->orderService->formatOrdersForFrontend($rawOrders);
        $stats = $this->orderService->getOrderStatistics();

        $allUsers = \App\Models\User::select('id', 'name', 'email', 'address')->get();
        $allProducts = \App\Models\Product::select('id', 'name', 'sku', 'price', 'image', 'stock')->get();

        return view('admin.orders.index', compact(
            'allOrders',
            'stats',
            'allUsers',
            'allProducts'
        ));
    }

    /**
     * Cập nhật Trạng thái đơn hàng (Logic cũ)
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|string']);

        $this->orderService->updateStatus($order, $request->status);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng #' . $order->order_code);
    }

    /**
     * BỔ SUNG: Cập nhật Tình trạng giao hàng (Cho đơn lẻ)
     */
    public function updateShippingStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['shipping_status' => 'required|string']);

        // Cập nhật trực tiếp vào cột shipping_status
        $order->update(['shipping_status' => $request->shipping_status]);

        return back()->with('success', 'Đã cập nhật tình trạng giao hàng cho đơn #' . $order->order_code);
    }

    public function export(Request $request)
    {
        $orders = $this->orderService->getFilteredOrders($request->all());
        $fileName = 'danh-sach-don-hang-' . now()->format('d-m-Y-His') . '.xlsx';
        return Excel::download(new OrdersExport($orders), $fileName);
    }

    /**
     * Thao tác hàng loạt (Cập nhật logic cũ để hỗ trợ Giao hàng)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'             => 'required|array',
            'action'          => 'required|string',
            'status'          => 'nullable|string',
            'shipping_status' => 'nullable|string', // Thêm validation cho shipping
        ]);

        $ids = $request->ids;
        $action = $request->action;

        // 1. Gọi Service thực hiện xử lý DB
        $this->orderService->executeBulkOrderAction($action, $ids, $request->all());

        // 2. GIỮ LOGIC CŨ: Switch case để tạo thông báo chuẩn
        switch ($action) {
            case 'update_status':
                $msg = "Đã cập nhật trạng thái đơn hàng cho " . count($ids) . " đơn.";
                break;
            case 'update_shipping':
                $msg = "Đã cập nhật tình trạng giao hàng cho " . count($ids) . " đơn hàng.";
                break;
            case 'cancel':
                $msg = "Đã hủy " . count($ids) . " đơn hàng thành công.";
                break;
            default:
                $msg = "Đã thực hiện thao tác hàng loạt thành công!";
        }

        return back()->with('success', $msg);
    }

    /**
 * Tự động tải xuống hóa đơn dưới dạng HÌNH ẢNH hoặc PDF pixel-perfect
 */
public function downloadInvoice($id)
{
    try {
        // 1. Lấy dữ liệu đơn hàng
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        // 2. Tạo PDF từ View
        // Lưu ý: DomPDF không hỗ trợ tốt Tailwind CSS, bạn nên dùng CSS thuần trong view
        $pdf = Pdf::loadView('components.admin.orders.print', compact('order'))
                  ->setPaper('a4', 'portrait'); // Định dạng khổ giấy A4 dọc

        // 3. Trả file về trình duyệt để tải xuống ngay lập tức
        return $pdf->download('hoa-don-' . $order->order_code . '.pdf');

    } catch (\Exception $e) {
        return back()->with('error', 'Lỗi khi tạo PDF: ' . $e->getMessage());
    }
}
}
