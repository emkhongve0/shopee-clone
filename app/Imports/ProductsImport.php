<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // 1. Tìm sản phẩm dựa trên SKU trong file Excel
        $product = Product::where('sku', $row['ma_sku'])->first();

        if ($product) {
            // TRƯỜNG HỢP 1: Sản phẩm đã tồn tại -> CẬP NHẬT
            $product->update([
                'name'        => $row['ten_san_pham'],
                'category_id' => $row['id_danh_muc'],
                'price'       => (float) $row['gia_ban'],
                // TÙY CHỌN: Cộng dồn tồn kho cũ + mới
                'stock'       => $product->stock + (int) $row['ton_kho'],
                // Hoặc ghi đè hoàn toàn: 'stock' => (int) $row['ton_kho'],
                'is_active'   => (int) ($row['trang_thai'] ?? 1),
            ]);

            // Trả về null vì chúng ta đã tự update, không muốn Laravel-Excel tạo thêm dòng mới
            return null;
        }

        // TRƯỜNG HỢP 2: Chưa có SKU này -> TẠO MỚI HOÀN TOÀN
        return new Product([
            'sku'         => $row['ma_sku'],
            'name'        => $row['ten_san_pham'],
            'category_id' => $row['id_danh_muc'],
            'price'       => (float) $row['gia_ban'],
            'stock'       => (int) $row['ton_kho'],
            'is_active'   => (int) ($row['trang_thai'] ?? 1),
            'description' => $row['mo_ta'] ?? null,
            'image'       => $row['link_anh'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'ten_san_pham' => 'required|string',
            'ma_sku'       => 'required', // Bắt buộc phải có SKU để đối chiếu
            'id_danh_muc'  => 'required|exists:categories,id',
            'gia_ban'      => 'required|numeric',
            'ton_kho'      => 'required|integer',
        ];
    }
}
