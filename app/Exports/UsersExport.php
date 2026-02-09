<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $users;
    protected $stt = 1;

    public function __construct($users)
    {
        $this->users = $users;
    }

    // 1. Lấy dữ liệu
    public function collection()
    {
        return $this->users;
    }

    // 2. Định nghĩa tiêu đề cột
    public function headings(): array
    {
        return [
            'STT',
            'ID người dùng',
            'Họ và Tên',
            'Địa chỉ Email',
            'Số điện thoại',
            'Vai trò',
            'Trạng thái hoạt động',
            'Ngày tham gia hệ thống'
        ];
    }

    // 3. Định dạng dữ liệu từng dòng (Việt hóa)
    public function map($user): array
    {
        $roleRaw = $user->role instanceof \BackedEnum ? $user->role->value : (string)$user->role;
        $statusRaw = $user->status instanceof \BackedEnum ? $user->status->value : (string)$user->status;

        $roleLabel = match($roleRaw) {
            'admin'   => 'Quản trị viên',
            'staff'   => 'Nhân viên',
            'manager' => 'Quản lý',
            default   => 'Khách hàng',
        };

        $statusLabel = match($statusRaw) {
            'active'   => 'Đang hoạt động',
            'inactive' => 'Chờ kích hoạt',
            'banned'   => 'Đã khóa',
            default    => $statusRaw,
        };

        return [
            $this->stt++,
            $user->id,
            mb_convert_case($user->name, MB_CASE_TITLE, "UTF-8"),
            $user->email,
            $user->phone ?? 'N/A',
            $roleLabel,
            $statusLabel,
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A'
        ];
    }

    // 4. Độ rộng cột tự động hoặc tùy chỉnh
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 30,
            'D' => 30,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 25,
        ];
    }

    // 5. Trang trí: In đậm tiêu đề, màu nền, căn giữa
    public function styles(Worksheet $sheet)
    {
        return [
            // Dòng 1 (Tiêu đề)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD']
                ],
                'alignment' => ['horizontal' => 'center']
            ],
            // Căn giữa các cột STT, ID, Vai trò, Trạng thái
            'A' => ['alignment' => ['horizontal' => 'center']],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
            'G' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
