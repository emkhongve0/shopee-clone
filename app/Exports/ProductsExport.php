<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->sku,
            $product->name,
            $product->category->name ?? 'Chưa phân loại',
            number_format($product->price) . ' VNĐ',
            $product->stock,
            $product->is_active ? 'Đang bán' : 'Đã ẩn', // Hoặc dùng logic status của bạn
            $product->created_at->format('d/m/Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mã SKU',
            'Tên sản phẩm',
            'Danh mục',
            'Giá bán',
            'Tồn kho',
            'Trạng thái',
            'Ngày tạo',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
