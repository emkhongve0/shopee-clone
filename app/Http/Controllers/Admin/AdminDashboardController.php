<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        try {
            // 1. Mọi logic lấy dữ liệu (KPI, Biểu đồ, Danh sách) đều nằm trong Service
            $data = $this->dashboardService->getDashboardData();

            return view('admin.dashboard', $data);

        } catch (Exception $e) {
            // Log lỗi chi tiết
            Log::error("Dashboard Error: " . $e->getMessage(), [
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => auth()->id()
            ]);

            // Lấy dữ liệu dự phòng TỪ SERVICE (không định nghĩa ở controller nữa)
            $fallbackData = $this->dashboardService->getFallbackData();

            return view('admin.dashboard', $fallbackData)
                ->with('error', 'Hệ thống đang gặp sự cố khi tải dữ liệu thực tế.');
        }
    }
}
