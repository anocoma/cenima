<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chi Tiết Giao Dịch</title>
  <style>
    /* Cấu trúc Modal */
    .modal-ticket-detail {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(25, 32, 65, 0.6); /* #192041 */
      justify-content: center;
      align-items: center;
      transition: all 0.3s ease;
    }

    .modal-content-ticket-detail {
      background-color: white;
      width: 90%; /* Tăng độ rộng để có không gian bên trái và phải */
      max-width: 1100px; /* Mở rộng chiều rộng tối đa */
      border-radius: 15px; /* Tăng bo góc để trông mềm mại hơn */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
      font-family: Arial, sans-serif;
      position: relative;
      padding: 20px; /* Tăng padding bên trong */
      overflow-y: auto;
      margin-left: 5%; /* Thêm khoảng cách từ trái */
      margin-right: 5%; /* Thêm khoảng cách từ phải */
    }

    /* Header Modal */
    .modal-header-ticket-detail {
      background-color: #434f89; /* #434f89 */
      color: white;
      padding: 15px 20px; /* Tăng padding */
      font-size: 20px; /* Tăng kích thước chữ */
      font-weight: bold;
      border-radius: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .close-btn-ticket-detail {
      color: white;
      font-size: 32px; /* Tăng kích thước chữ đóng */
      cursor: pointer;
    }

    .modal-body-ticket-detail {
      padding: 20px; /* Tăng padding */
    }

    /* Thông tin chính */
    .info-block-ticket-detail {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px; /* Tăng margin-bottom */
      font-size: 16px; /* Tăng font-size */
    }

    /* Mã vạch */
    .barcode-ticket-detail img {
      max-width: 90%; /* Giảm kích thước mã vạch */
      border-radius: 10px;
    }

    /* Cấu trúc bảng giao dịch */
    .transaction-details-ticket-detail table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px; /* Tăng khoảng cách trên bảng */
    }

    .transaction-details-ticket-detail th, .transaction-details-ticket-detail td {
      padding: 12px; /* Tăng padding */
      border: 1px solid #ddd;
      text-align: left;
    }

    .transaction-details-ticket-detail th {
      background-color: #ff7307; /* #ff7307 */
      color: white;
      font-weight: normal;
    }

    .transaction-details-ticket-detail td {
      font-size: 14px; /* Tăng font-size */
      background-color: #f9f9f9;
    }

    .transaction-details-ticket-detail tr:hover {
      background-color: #f1f1f1;
    }

    /* Tổng cộng */
    .payment-summary-ticket-detail {
      font-size: 18px; /* Tăng font-size */
      font-weight: bold;
      background-color: #f0f4f7;
      border-radius: 15px;
      margin-top: 20px;
      padding: 20px; /* Tăng padding */
    }

    .payment-summary-ticket-detail div {
      margin-bottom: 12px; /* Tăng margin-bottom */
    }

    .payment-summary-ticket-detail .amount {
      color: #ff7307; /* #ff7307 */
      font-weight: bold;
    }

    .payment-summary-ticket-detail .discounts {
      color: #151b39; /* #151b39 */
    }

    /* Responsive */
    @media (max-width: 768px) {
      .modal-content-ticket-detail {
        width: 90%;
        padding: 15px; /* Giảm padding */
      }

      .info-block-ticket-detail {
        flex-direction: column;
        align-items: flex-start;
        font-size: 14px; /* Giảm font-size */
      }

      .info-block-ticket-detail div {
        margin-bottom: 8px;
        width: 100%;
      }

      .modal-header-ticket-detail {
        font-size: 18px;
        padding: 12px 15px;
      }

      .transaction-details-ticket-detail table, .transaction-details-ticket-detail th, .transaction-details-ticket-detail td {
        font-size: 12px; /* Giảm font-size */
        padding: 8px; /* Giảm padding */
      }

      .payment-summary-ticket-detail {
        padding: 15px; /* Giảm padding */
      }

      .barcode-ticket-detail img {
        max-width: 80%;
        margin: 0 auto;
      }
    }

    /* Responsive cho các màn hình nhỏ hơn */
    @media (max-width: 480px) {
      .modal-header-ticket-detail {
        font-size: 16px;
        padding: 10px 12px;
      }

      .transaction-details-ticket-detail table, .transaction-details-ticket-detail th, .transaction-details-ticket-detail td {
        font-size: 10px; /* Giảm font-size */
        padding: 6px; /* Giảm padding */
      }

      .payment-summary-ticket-detail {
        padding: 12px; /* Giảm padding */
      }

      .payment-summary-ticket-detail .amount {
        font-size: 16px;
      }

      .payment-summary-ticket-detail .discounts {
        font-size: 14px;
      }
    }

  </style>
</head>
<body>

  <!-- Nút mở Modal -->
  <button class="open-modal-btn-ticket-detail" id="showTicketDetail">Xem chi tiết giao dịch</button>

  <!-- Modal -->
  <div id="transactionModal" class="modal-ticket-detail">
    <div class="modal-content-ticket-detail">
      <div class="modal-header-ticket-detail">
        <span>Chi tiết giao dịch #aO2PBFGR6Y</span>
        <span class="close-btn-ticket-detail" id="closeTicketDetail">&times;</span>
      </div>

      <div class="modal-body-ticket-detail">
        <!-- Thông tin giao dịch -->
        <div class="info-block-ticket-detail">
          <div>
            <strong>Mã giao dịch:</strong> #aO2PBFGR6Y<br>
            <strong>Ngày mua hàng:</strong> 18/06/2024 16:00
          </div>
          <div class="barcode-ticket-detail">
            <img src="https://dummyimage.com/300x80/000/fff.png&text=Barcode" alt="Barcode">
          </div>
        </div>

        <!-- Địa chỉ và phương thức thanh toán -->
        <div class="info-block-ticket-detail">
          <div>
            <strong>Địa chỉ thanh toán:</strong><br>
            System Admin<br>
            Bích Hòa, Thanh Oai, Hà Nội<br>
            admin@fpt.edu.vn<br>
            0332295555
          </div>
          <div>
            <strong>Phương thức thanh toán:</strong><br>
            Zalopay
          </div>
        </div>

        <!-- Bảng thông tin giao dịch -->
        <div class="transaction-details-ticket-detail">
          <h3 style="color: #434f89;">Thông tin giao dịch</h3>
          <table>
            <thead>
              <tr>
                <th>Phim</th>
                <th>Suất chiếu</th>
                <th>Ghế</th>
                <th>Combo</th>
                <th>Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Địa Đạo: Mặt Trời Trong Bóng Tối</td>
                <td>Mỹ Đình<br>P404<br>18/11/2024<br>14:37 - 16:24</td>
                <td>Ghế Vip: H6<br>83.000 đ</td>
                <td>
                  Combo Snack x 3<br>
                  Combo Mixed x 3<br>
                  Combo Drink x 5
                </td>
                <td>518.000 đ</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Tổng tiền và các khoản giảm giá -->
        <div class="payment-summary-ticket-detail">
          <div>
            <strong>Tổng tiền:</strong> 300.000 đ
          </div>
          <div class="discounts">
            <strong>Voucher:</strong> -20.000 đ<br>
            <strong>Điểm Poly:</strong> -10.000 đ
          </div>
          <div class="amount">
            <strong>Tổng tiền đã thanh toán:</strong> 270.000 đ
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const showModalTicketDetail = document.getElementById("showTicketDetail");
    const closeModalTicketDetail = document.getElementById("closeTicketDetail");
    showModalTicketDetail.addEventListener("click", function() {
        document.getElementById("transactionModal").style.display = "flex";
        });
        closeModalTicketDetail.addEventListener("click", function() {
        document.getElementById("transactionModal").style.display = "none";
        });

    function closeModal() {
      document.getElementById("transactionModal").style.display = "none";
    }
    // Đóng modal khi người dùng nhấn bên ngoài modal
    window.onclick = function(event) {
      if (event.target == document.getElementById("transactionModal")) {
        closeModal();
      }
    }
  </script>

</body>
</html>
