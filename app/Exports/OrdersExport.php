<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $orders;

    // Nhận dữ liệu từ Controller truyền sang
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    // Trả về dữ liệu gốc
    public function collection()
    {
        return $this->orders;
    }

    // Định dạng từng dòng dữ liệu (Mapping)
    public function map($order): array
    {
        return [
            $order->order_code, // Cột A
            $order->user->name ?? 'Khách vãng lai', // Cột B
            $order->user->email ?? 'N/A', // Cột C
            $order->user->phone ?? 'N/A', // Cột D
            number_format($order->total_amount) . ' VNĐ', // Cột E (Format tiền)

            // Cột F: Xử lý Enum status
            $order->status instanceof \App\Enums\OrderStatus ? $order->status->label() : $order->status,

            $order->payment_status, // Cột G
            $order->shipping_address, // Cột H
            $order->created_at->format('d/m/Y H:i'), // Cột I
        ];
    }

    // Đặt tên tiêu đề cho các cột (Headings)
    public function headings(): array
    {
        return [
            'Mã đơn hàng',
            'Tên khách hàng',
            'Email',
            'Số điện thoại',
            'Tổng tiền',
            'Trạng thái',
            'Thanh toán',
            'Địa chỉ giao hàng',
            'Ngày tạo',
        ];
    }

    // Style: In đậm dòng đầu tiên (Header)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
