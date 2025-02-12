<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn</title>
    <!-- Thêm Print.js từ CDN -->
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/order.css') }}">
</head>

<body>
    <button class="print-button" onclick="printInvoice()">In hóa đơn</button>

        <div class="invoice-content" id="invoice">
            <h2 class="invoice-title">Hóa đơn chi tiết</h2>

            <div class="invoice-details">
                <strong>Chi nhánh công ty Poly Cinemas vietnam tại Hà nội</strong><br>
                Địa chỉ: 1 Quang Trung - Hà Đông - Hà nội<br>
                mst: 012147901412
                <hr>
                <strong>Poly Cinemas Hà Đông - Hà nội</strong><br>
                Thời gian: 2024-11-09 17:31:00
                <hr>
                <strong>Ivory Bruen (Lồng Tiếng)</strong><br>
                K (18+)<br>
                <strong>Phòng:</strong> L202<br>
                <strong>Ghế:</strong> G7, G6
                <hr>
            </div>

            <div class="invoice-summary">
                <div><span>Giá vé:</span><span>150.000 VND</span></div>
                <div><span>Giá combo:</span><span>0 VND</span></div>
                <div><span>Giảm giá:</span><span>0 VND</span></div>
                <div><strong>Thành tiền:</strong><strong>250.000 VND</strong></div>
            </div>

            <div class="barcode">

            </div>
            <div class="invoice-code">HD071124-0001</div>
        </div>



    <script>
        function printInvoice() {
            printJS({
                printable: 'invoice', // ID hoặc phần tử bạn muốn in
                type: 'html',
                css: '{{ asset('theme/admin/assets/css/order.css') }}'
            });
        }
    </script>

</body>

</html>
