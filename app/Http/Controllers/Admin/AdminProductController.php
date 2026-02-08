<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\ProductService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

class AdminProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
{
    // 1. Lấy toàn bộ tham số lọc từ Request
    $filters = $request->all();

    // 2. Lấy dữ liệu từ Service
    $data = $this->productService->getProductPageData($request->all());
    $data['categories'] = \App\Models\Category::select('id', 'name')->get();
        $data['totalProducts'] = \App\Models\Product::count();
        $data['selectedFilters'] = $request->all();

    // --- SỬA TẠI ĐÂY ---
    // Chúng ta cần "dịch" giá trị status từ 1/0 sang 'active'/'hidden' cho TẤT CẢ sản phẩm
    // để Alpine.js ở giao diện hiểu được.
    if (isset($data['allProducts'])) {
        $data['allProducts'] = collect($data['allProducts'])->map(function ($product) {
            // Sử dụng chính hàm mapProductForFrontend bạn đã có trong Controller
            return $this->productService->mapProductForFrontend($product);
        })->toArray();
    }
    // ------------------

    // 3. Lấy thêm dữ liệu bổ trợ cho giao diện
    $data['categories'] = \App\Models\Category::select('id', 'name')->get();
    $data['totalProducts'] = \App\Models\Product::count();

    // Gửi lại filters hiện tại
    $data['selectedFilters'] = $filters;

    return view('admin.products.index', $data);
}

    public function export(Request $request)
    {
    // Lấy danh sách sản phẩm theo bộ lọc hiện tại (không phân trang để xuất hết)
    $products = $this->productService->getFilteredProducts($request->all(), 9999);

    $fileName = 'danh-sach-san-pham-' . now()->format('d-m-Y-His') . '.xlsx';

    return Excel::download(new ProductsExport($products), $fileName);
    }

    public function import(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:5120', // Tối đa 5MB
    ]);

    try {
        Excel::import(new ProductsImport, $request->file('file'));
        return back()->with('success', 'Đã nhập danh sách sản phẩm thành công!');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        return back()->with('error', 'Lỗi dữ liệu tại dòng ' . $failures[0]->row() . ': ' . $failures[0]->errors()[0]);
    } catch (\Exception $e) {
        return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
    }
    }

    // ... (Giữ nguyên các hàm bulkAction, destroy, store, update...)
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:products,id',
            'action' => 'required|string',
        ]);

        $this->productService->executeBulkAction(
            $request->action,
            $request->ids,
            $request->all()
        );

        return back()->with('success', 'Đã thực hiện hành động thành công.');
    }

    public function destroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $this->productService->deleteProduct($product);
        return back()->with('success', 'Đã xóa sản phẩm thành công');
    }

    public function store(Request $request)
{
    // 1. Kiểm tra dữ liệu (Nếu lỗi, Laravel tự trả về 422 kèm tin nhắn lỗi cụ thể)
    $validatedData = $request->validate([
        'name'        => 'required|string|max:255',
        'category_id' => 'required|integer', // Đảm bảo Frontend gửi category_id là số
        'price'       => 'required|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'status'      => 'nullable|string',
    ]);

    try {
        // 2. Gọi Service tạo sản phẩm
        $product = $this->productService->createProduct($request->all());

        // 3. Trả về đúng định dạng JSON
        return response()->json([
            'message' => 'Thêm sản phẩm thành công!',
            'product' => $this->productService->mapProductForFrontend($product)
            ], 201);

    } catch (\Exception $e) {
        return response()->json(['message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
    }
}

public function update(Request $request, $id)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'category_id' => 'required|integer',
        'price'       => 'required|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'status'      => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

    ]);

    try {
        $product = \App\Models\Product::findOrFail($id);

        // Cập nhật thông qua Service
        $updatedProduct = $this->productService->updateProduct($product, $request->all());

        return response()->json([
            'message' => 'Cập nhật thành công!',
            'product' => $this->productService->mapProductForFrontend($updatedProduct)
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Lỗi cập nhật: ' . $e->getMessage()], 500);
    }
}


}
