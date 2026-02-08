<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TempPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    // BƯỚC 1: Khai báo thuộc tính public (Cực kỳ quan trọng)
    // Thuộc tính public sẽ tự động được truyền vào View
    public $password;

    /**
     * BƯỚC 2: Nhận giá trị từ Controller và gán vào thuộc tính
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * BƯỚC 3: Xây dựng nội dung Email
     */
    public function build()
    {
        return $this->subject('Mật khẩu tạm thời của bạn - ShopMart')
                    ->view('emails.temp_password');
                    // Không cần dùng ->with() nếu thuộc tính đã là public
    }
}
