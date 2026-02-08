<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Hóa đơn #{{ $order->order_code }}</title>
    <style>
        /* Sử dụng font DejaVu Sans để hiển thị tốt tiếng Việt */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #334155;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .invoice-paper {
            padding: 40px;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .shopee-color {
            color: #EE4D2D;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .bg-orange-50 {
            background-color: #fff7ed;
        }

        /* Header Section */
        .logo-box {
            width: 48px;
            height: 48px;
            background: linear-gradient(to bottom right, #EE4D2D, #ff6b46);
            border-radius: 12px;
            color: white;
            text-align: center;
            line-height: 48px;
            font-size: 24px;
            display: inline-block;
        }

        .header-title {
            font-size: 24px;
            color: #1e293b;
            margin: 0;
        }

        /* Information Boxes */
        .info-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            vertical-align: top;
        }

        .info-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 8px;
        }

        /* Table Details */
        .items-table th {
            background-color: #f1f5f9;
            border-top: 2px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
            padding: 10px;
            font-size: 10px;
            color: #475569;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .category-badge {
            font-size: 9px;
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
            color: #64748b;
        }

        /* Summary Section */
        .summary-box {
            width: 300px;
            float: right;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .total-amount {
            font-size: 20px;
            color: #EE4D2D;
            border-top: 2px solid #cbd5e1;
            padding-top: 10px;
            margin-top: 10px;
        }

        /* QR Code */
        .qr-placeholder {
            border: 2px solid #e2e8f0;
            padding: 5px;
            border-radius: 8px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="invoice-paper">
        <table class="mb-20">
            <tr>
                <td width="60%">
                    <table>
                        <tr>
                            <td width="60" style="vertical-align: middle;">
                                <div class="logo-box">S</div>
                            </td>
                            <td style="vertical-align: middle;">
                                <h1 class="header-title">ShopMart</h1>
                                <p style="margin: 0; font-size: 10px; color: #64748b;">Smart Shopping, Smart Living</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%" class="text-right">
                    <h2 style="margin: 0; font-size: 20px;">HÓA ĐƠN</h2>
                    <h2 class="shopee-color" style="margin: 0; font-size: 18px;">INVOICE</h2>
                </td>
            </tr>
        </table>

        <table class="mb-20" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 20px;">
            <tr>
                <td style="line-height: 1.8;">
                    <span style="color: #64748b;">Invoice No:</span> <span
                        class="font-bold">{{ $order->order_code }}</span><br>
                    <span style="color: #64748b;">Order Date:</span> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    <span style="color: #64748b;">Payment:</span> {{ strtoupper($order->payment_method) }}<br>
                    <span style="color: #64748b;">Status:</span>
                    <span
                        style="color: #166534; background: #dcfce7; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; border: 1px solid #bbf7d0;">
                        PAID
                    </span>
                </td>
                <td class="text-right">
                    <div class="qr-placeholder">
                        {{-- Sử dụng API công cộng để tạo QR --}}
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode(url('/order/' . $order->order_code)) }}"
                            width="70" height="70">
                    </div>
                </td>
            </tr>
        </table>

        <table class="mb-20" style="width: 100%;">
            <tr>
                <td width="48%" class="info-card bg-slate-50">
                    <div class="info-title">Store Information</div>
                    <p class="font-bold" style="margin: 0 0 5px 0;">ShopMart</p>
                    <p style="margin: 0; font-size: 10px;">
                        123 Nguyen Hue Street, District 1, Ho Chi Minh City<br>
                        Phone: 1900-xxxx<br>
                        Email: support@shopmart.vn
                    </p>
                </td>
                <td width="4%"></td>
                <td width="48%" class="info-card bg-orange-50" style="border-color: #fed7aa;">
                    <div class="info-title" style="color: #9a3412;">Customer Information</div>
                    <p class="font-bold" style="margin: 0 0 5px 0;">{{ $order->user->name ?? 'Khách lẻ' }}</p>
                    <p style="margin: 0; font-size: 10px;">
                        Phone: {{ $order->user->phone ?? 'N/A' }}<br>
                        Email: {{ $order->user->email ?? '' }}<br>
                        Address: {{ $order->shipping_address }}
                    </p>
                </td>
            </tr>
        </table>

        <table class="items-table mb-20">
            <thead>
                <tr>
                    <th align="left">Product</th>
                    <th align="center" width="50">Qty</th>
                    <th align="right" width="80">Price</th>
                    <th align="right" width="80">Discount</th>
                    <th align="right" width="100">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <div class="font-bold">{{ $item->product->name ?? 'Sản phẩm' }}</div>
                            <span class="category-badge">Electronics</span>
                        </td>
                        <td align="center">{{ $item->quantity }}</td>
                        <td align="right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                        <td align="right" style="color: #dc2626;">
                            -{{ number_format($item->discount ?? 0, 0, ',', '.') }}₫</td>
                        <td align="right" class="font-bold">
                            {{ number_format($item->price * $item->quantity - ($item->discount ?? 0), 0, ',', '.') }}₫
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-box">
            <table style="font-size: 12px;">
                <tr>
                    <td style="color: #64748b;">Subtotal:</td>
                    <td align="right" class="font-bold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                </tr>
                <tr>
                    <td style="color: #64748b;">Shipping Fee:</td>
                    <td align="right" style="color: #16a34a;" class="font-bold">Free</td>
                </tr>
                <tr>
                    <td style="color: #64748b;">Voucher:</td>
                    <td align="right" style="color: #dc2626;" class="font-bold">-0₫</td>
                </tr>
                <tr class="total-amount">
                    <td class="font-bold">Total Amount:</td>
                    <td align="right" class="font-bold shopee-color">
                        {{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <div class="footer text-center">
            <p class="font-bold" style="color: #1e293b; font-size: 14px; margin-bottom: 5px;">Thank you for shopping
                with ShopMart! 🎉</p>
            <p style="margin: 0;">We appreciate your trust and look forward to serving you again.</p>

            <div
                style="background: #fff7ed; padding: 10px; border-radius: 8px; margin: 20px 0; border: 1px solid #fed7aa; color: #475569; font-size: 10px;">
                <span class="font-bold">Return & Refund Policy:</span> Items can be returned within 7 days of delivery.
                For assistance, contact our customer service.
            </div>

            <p style="font-size: 9px; color: #94a3b8; margin-top: 20px;">Powered by ShopMart © 2026. All rights
                reserved.</p>
        </div>
    </div>
</body>

</html>
