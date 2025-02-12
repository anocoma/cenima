<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Toàn bộ modal */
        .modal-voucher {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* Nội dung modal */
        .modal-voucher-content {
            background-color: #fff;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        /* Header */
        .fixed-header-voucher {
            background: linear-gradient(135deg, #ff7307, #ff9d48);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            font-weight: 600;
        }

        .fixed-header-voucher h3 {
            color: white;
            font-weight: 600;
            font-size: 20px;
        }


        /* Nút đóng */
        .close-btn {
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-btn:hover {
            color: #ccc;
        }

        /* Nội dung chính */
        .content {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Mã voucher */
        .voucher-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
            border-left: 5px solid #32c5ff;
        }

        .voucher-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Bên trái của voucher */
        .voucher-left {
            display: flex;
            align-items: center;
        }

        .voucher-tag {
            background-color: #32c5ff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            margin-right: 10px;
        }

        /* Thông tin voucher */
        .voucher-info h4 {
            font-size: 16px;
            margin: 0;
            font-weight: 700;
        }

        .voucher-info p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }

        /* Bên phải của voucher */
        .voucher-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .voucher-status {
            font-size: 12px;
            color: #28a745;
            margin-top: 8px;
        }

        /* Nút áp dụng */
        .apply-btn {
            background-color: #ff7307;
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .apply-btn:hover {
            background-color: #e86900;
        }

        /* Footer */
        .fixed-footer-voucher {
            padding: 10px;
            background-color: #f8f8f8;
        }
    </style>
</head>

<body>

    <!-- Modal -->
    <div id="myModalVoucher" class="modal-voucher">
        <div class="modal-voucher-content">
            <div class="fixed-header-voucher">
                <h3>Voucher Của Tôi</h3>
                <span class="close-btn" id="closeModalVoucher">&times;</span>
            </div>
            <div class="content" id="data-my-voucher">
                <!-- Mẫu mã voucher -->
                @include('client.modals.data-my-voucher',$vouchers)


                {{-- <div class="voucher-item">
                    <div class="voucher-left">
                        <div class="voucher-tag">FREE SHIP</div>
                        <div class="voucher-info">
                            <h4>Giảm tối đa ₫15k</h4>
                            <p>Đơn tối thiểu ₫40k</p>
                        </div>
                    </div>
                    <div class="voucher-right">
                        <button class="apply-btn">Áp dụng</button>
                        <p class="voucher-status">Còn 10 mã</p>
                    </div>
                </div> --}}

                <!-- Bạn có thể thêm nhiều mẫu mã khác nếu cần -->
            </div>
            <div class="fixed-footer-voucher"></div>
        </div>
    </div>

    <script>
        // Lấy phần tử modal và nút mở/đóng modal
        const modalVoucher = document.getElementById("myModalVoucher");
        const closeModalVoucher = document.getElementById("closeModalVoucher");

        // Đóng modal khi bấm vào nút đóng
        closeModalVoucher.onclick = function() {
            modalVoucher.style.display = "none";
            document.body.classList.remove('no-scroll');
        }

        // Đóng modal khi bấm ra ngoài modal
        window.addEventListener("click", function(event) {
            if (event.target === modalVoucher) {
                modalVoucher.style.display = "none";
                document.body.classList.remove('no-scroll');
            }
        });
    </script>

</body>

</html>
