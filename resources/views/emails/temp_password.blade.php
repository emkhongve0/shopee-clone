<!DOCTYPE html>
<html>

<head>
    <style>
        .container {
            font-family: sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            color: #ee4d2d;
            font-size: 24px;
            font-weight: bold;
        }

        .otp {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 15px 30px;
            background: #fff5f1;
            border: 2px dashed #ee4d2d;
            color: #ee4d2d;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
        }

        .footer {
            font-size: 12px;
            color: #888;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">ShopMart</div>
        <p>Chào bạn,</p>
        <p>Chúng tôi đã nhận được yêu cầu cấp mật khẩu tạm thời cho tài khoản của bạn. Dưới đây là mã đăng nhập của bạn:
        </p>

        <div class="otp">{{ $password }}</div>

        <p><strong>Lưu ý:</strong></p>
        <ul>
            <li>Mật khẩu này chỉ có hiệu lực trong <b>15 phút</b>.</li>
            <li>Mã này chỉ sử dụng được <b>1 lần duy nhất</b>.</li>
            <li>Sau khi đăng nhập, vui lòng đổi mật khẩu ngay để bảo mật tài khoản.</li>
        </ul>
        <p>Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.</p>

        <div class="footer">
            &copy; 2026 ShopMart - Hệ thống mua sắm trực tuyến hàng đầu.
        </div>
    </div>
</body>

</html>
