<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Style cho modal */
        .modal-clause {
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

        .modal-clause-content {
            background-color: #fff;
            border-radius: 4px;
            width: 80%;
            max-width: 800px;
            text-align: left;
            color: #333;
            max-height: 100%;
            margin: 20px;
        }

        .close-btn {
            color: #ffffff;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #192041;
        }

        .modal-clause-content p {
            margin: 10px 0;
        }

        .fixed-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ff7307;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
            padding: 15px 20px;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        .content {
            padding: 10px 20px 0 20px;
            max-height: calc(95vh - 60px);
            /* Điều chỉnh theo chiều cao của header */
            overflow-y: auto;
            text-align: justify;
            margin-bottom: 5px;
        }

        .fixed-footer {
            background-color: #fff;
            color: #fff;
            border-radius: 8px;
            height: 7px;
        }

        .content h4 {
            font-size: 15px;
            font-weight: bold;
            line-height: 1.2;
            color: #101d5a;
        }

        .content p {
            font-size: 14px;
            padding-bottom: 15px;
        }

        .content li {
            font-size: 14px;
            padding-left: 29px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
                /* Giảm chiều rộng modal trên thiết bị nhỏ */
            }

            .close-btn {
                font-size: 24px;
                /* Giảm kích thước nút đóng */
            }
        }

        @media (max-width: 480px) {
            .modal-content {
                width: 95%;
                /* Giảm chiều rộng modal trên điện thoại nhỏ */
                font-size: 14px;
                /* Giảm kích thước font chữ */
            }

            .fixed-header h3 {
                font-size: 18px;
                /* Giảm kích thước tiêu đề */
            }

            .content {
                padding: 15px 15px 0 15px;
                /* Giảm padding trong nội dung modal */
            }
        }
    </style>
</head>

<body>

    <!-- Modal -->
    <div id="myModal" class="modal-clause">
        <div class="modal-clause-content">

            <div class="fixed-header">
                <h3 style="color: white; font-weight: 600; font-size: 20px">ĐIỀU KIỆN VÀ ĐIỀU KHOẢN KHI ĐẶT VÉ</h3>
                <span class="close-btn" id="closeModal">&times;</span>
            </div>

            <div class="content">
                <h4>Chào mừng Quý khách hàng đến với Hệ thống Bán Vé Online của chuỗi Rạp Chiếu Phim POLY CINEMAS!</h4>
                <p>Xin cảm ơn và chúc Quý khách hàng có những giây phút xem phim tuyệt vời tại POLY CINEMAS!</p>

                <h4>Sau đây là một số lưu ý trước khi thanh toán trực tuyến:</h4>

                <p>1. Thẻ phải được kích hoạt chức năng thanh toán trực tuyến, và có đủ hạn mức/ số dư để thanh toán.
                    Quý khách cần nhập chính xác thông tin thẻ (tên chủ thẻ, số thẻ, ngày hết hạn, số CVC, OTP,…).</p>

                <p>2. Vé và hàng hóa đã thanh toán thành công không thể hủy/đổi trả/hoàn tiền vì bất kỳ lý do gì. Poly
                    Cinemas chỉ thực hiện hoàn tiền trong trường hợp thẻ của Quý khách đã bị trừ tiền nhưng hệ thống của
                    Poly không ghi nhận việc đặt vé/đơn hàng của Quý khách, và Quý khách không nhận được xác nhận đặt
                    vé/đơn hàng thành công.</p>

                <p>3. Trong vòng 30 phút kể từ khi thanh toán thành công, Poly Cinemas sẽ gửi Quý khách
                    thông tin vé/đơn hàng qua email của Quý khách. Nếu Quý khách cần hỗ trợ hay thắc mắc, khiếu nại về
                    mã vé/đơn hàng thì vui lòng phản hồi về Fanpage Facebook Poly Cinemas trong vòng 60 phút kể
                    từ khi thanh toán vé thành công. Sau khoảng thời gian trên, Poly Cinemas sẽ không chấp nhận giải
                    quyết bất kỳ khiếu nại nào.</p>

                <p>4. Poly Cinemas không chịu trách nhiệm trong trường hợp thông tin địa chỉ email, số điện thoại Quý
                    khách nhập không chính xác dẫn đến không nhận được thư xác nhận. Vui lòng kiểm tra kỹ các thông tin
                    này trước khi thực hiện thanh toán. Poly Cinemas không hỗ trợ xử lý và không chịu trách nhiệm trong
                    trường hợp đã gửi mã vé/đơn hàng đến địa chỉ email của Quý khách nhưng vì lý do nào đó
                    mà Quý khách không thể đến xem phim.</p>

                <p>5. Vui lòng kiểm tra thông tin xác nhận vé cẩn thận và ghi nhớ mã đặt vé/đơn hàng. Khi đến nhận
                    vé/hàng hóa tại Quầy vé của Poly Cinemas, Quý khách cũng cần mang theo giấy tờ tùy thân như Căn cước
                    công dân/Chứng minh nhân dân, Thẻ học sinh, Thẻ sinh viên hoặc hộ chi</p>



                <p style="padding-bottom: 0px">6. Theo quy định của Cục Điện Ảnh, một số phim sẽ không dành cho khán giả
                    dưới 13, hoặc 16, hoặc 18
                    tuổi. Khi đến lấy vé tại quầy vé, nhân viên có thể yêu cầu Quý khách hàng xuất trình giấy tờ tùy
                    thân (Giấy khai sinh, Căn cước công dân, Thẻ học sinh, Thẻ sinh viên, Bằng lái xe hoặc các giấy tờ
                    tùy thân khác) để xác định độ tuổi chính xác của Quý khách hàng. Quý khách hàng vui lòng lưu ý và
                    cân nhắc trước khi mua vé.</p>
                <p style="padding-bottom: 0px">
                    Căn cứ vào Điều 9 Nghị định 131/2022/NĐ-CP quy định chi tiết một số điều của Luật Điện Ảnh, Poly
                    Cinemas áp dụng quy định về khung giờ chiếu phim cho trẻ em, áp dụng cho toàn bộ hệ thống rạp chiếu
                    phim như sau:</p>

                <li>Giờ chiếu phim cho trẻ em dưới 13 tuổi tại tất cả các cụm rạp Poly Cinemas kết thúc trước 22 giờ.
                </li>
                <li>Giờ chiếu phim cho trẻ em dưới 16 tuổi tại tất cả các cụm rạp Poly Cinemas kết thúc trước 23 giờ.
                </li>
                <p>
                    Lưu ý, Ban Quản Lý tại các cụm rạp chiếu phim Poly Cinemas có quyền kiểm tra và từ chối yêu cầu xem
                    phim của Quý khách hàng nếu Quý khách hàng không tuân thủ quy định về độ tuổi xem phim.</p>



                <p style="padding-bottom: 0px">7. Vì một số sự cố kỹ thuật bất khả kháng, suất chiếu có thể bị hủy để
                    đảm bảo an toàn tối đa cho
                    khách hàng, Poly Cinemas sẽ thực hiện hoàn trả số tiền giao dịch về tài khoản mà Quý khách đã thực
                    hiện mua vé. Poly Cinemas sẽ liên hệ với Quý khách qua các thông tin liên hệ trong Thông tin thành
                    viên để thông báo và xác nhận.</p>
                <p style="padding-bottom: 0px">Sau khi đã xác nhận, tùy theo từng loại tài khoản mà Quý khách sử dụng và
                    tùy theo chính sách của các ngân hàng mà việc hoàn tiền sẽ có thời gian khác nhau:</p>
                <li>Ví điện tử: 5-10 ngày làm việc</li>
                <li>Thẻ nội địa: 3-5 ngày làm việc</li>
            </div>

            <div class="fixed-footer"></div>
        </div>
    </div>

    <script>
        // Lấy phần tử modal và nút mở/đóng modal
        const modal = document.getElementById("myModal");
        const closeModal = document.getElementById("closeModal");

        // Đóng modal khi bấm vào nút đóng
        closeModal.onclick = function() {
            modal.style.display = "none";
            document.body.classList.remove('no-scroll');
        }

        // Đóng modal khi bấm ra ngoài modal
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
                document.body.classList.remove('no-scroll');
            }
        }
    </script>

</body>

</html>
