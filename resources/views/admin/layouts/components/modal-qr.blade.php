<div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="width: 680px;">
            <div class="modal-body text-center">
                <div id="camera" style="width: 640px; height: 360px; border: 1px solid gray; margin: 0 auto;"></div>
                <div class="mt-4">
                    <div id="barcode-result" style="color: red; margin-top: 35px;"></div>
                    <div id="error-message" style="color: red; margin-top: 10px;"></div>
                    <div class="hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-warning" id="scanAnotherBtn">Quét lại</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scanModal = document.getElementById('scanModal');
        const scanAnotherBtn = document.getElementById("scanAnotherBtn");
        const errorMessage = document.getElementById("error-message");
        const barcodeResult = document.getElementById("barcode-result");

        let sourcePage = null; // Để lưu trang nào mở modal

        // Xử lý khi modal được mở
        scanModal.addEventListener('show.bs.modal', function(event) {
            console.log('Modal đang được mở.');
            const button = event.relatedTarget; // Nút đã kích hoạt modal
            sourcePage = button.getAttribute('data-source'); // Lấy giá trị data-source

            if (sourcePage === 'index') {
                startScanner();
                // console.log("Modal được mở từ Trang Index");
            } else if (sourcePage === 'header') {
                startScanner();
                // console.log("Modal được mở từ Header");
            }


        });
        scanModal.addEventListener('hidden.bs.modal', function() {
            stopScanner();
            clearBarcodeResult();
            sourcePage = null;
        });

        // Hàm khởi động Quagga
        function startScanner() {
            Quagga.init({
                inputStream: {
                    type: "LiveStream",
                    target: document.querySelector("#camera"),
                    constraints: {
                        width: 640,
                        height: 380,
                        facingMode: "user" // Thay đổi thành "environment" neeus sử dụng camera sau
                    }
                },
                decoder: {
                    readers: ["code_128_reader"]
                }
            }, function(err) {
                if (err) {
                    console.log("Lỗi: ", err);
                    errorMessage.innerText =
                        "Không thể truy cập camera, vui lòng kiểm tra quyền truy cập!";
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(detectedCode);
        }

        // Hàm dừng Quagga
        function stopScanner() {
            Quagga.stop();
            Quagga.offDetected(detectedCode);
        }

        // Hàm xử lý mã quét được
        function detectedCode(result) {
            const code = result.codeResult.code;
            barcodeResult.innerText = code; // Hiển thị mã quét được
            stopScanner();

            Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false, // Không cho phép đóng ngoài khi đang xử lý
                    didOpen: () => {
                        Swal.showLoading(); // Hiển thị spinner loading
                    }
                });
            fetch('{{ route('admin.tickets.processScan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: code
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.redirect_url) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: 'Tìm kiếm hóa đơn thành công.',
                            confirmButtonText: 'Đóng',
                            timer: 3000,
                            timerProgressBar: true,

                        });
                        window.open(data.redirect_url, '_blank');

                        // Khởi động lại scanner
                        barcodeResult.innerText = "";
                        startScanner();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Không tìm thấy mã hóa đơn, vui lòng thử lại.',
                            confirmButtonText: 'Đóng',
                            timer: 3000,
                            showConfirmButton: true,
                        });
                    }
                })
                .catch(error => {
                    console.error("Lỗi:", error);

                    errorMessage.innerText = 'Lỗi kết nối, vui lòng thử lại sau!';
                });
        }

        // Hàm xóa mã vạch và thông báo lỗi
        function clearBarcodeResult() {
            barcodeResult.innerText = ""; // Xóa mã vạch quét được khi mở lại modal
            errorMessage.innerText = ""; // Xóa thông báo lỗi nếu có
        }

        // Xử lý khi bấm nút "Quét lại mã khác"
        scanAnotherBtn.addEventListener("click", function() {
            barcodeResult.innerText = ""; // Xóa mã quét được trước đó
            errorMessage.innerText = ""; // Xóa thông báo lỗi
            startScanner(); // Bắt đầu quét lại
        });
    });
</script>


{{-- <div class="ms-1 header-item d-none d-sm-flex">
    <div>
        <!-- center modal -->
        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="modal"
                data-bs-target="#scanModal2"><i class='ri-qr-code-line fs-22'></i>
        </button>
        <div class="modal fade" id="scanModal2" tabindex="-1"
             aria-labelledby="" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="width: 680px; ">
                    <div class="modal-body text-center">
                        <div id="camera"
                             style="width: 640px; height: 360px; border: 1px solid gray; margin: 0 auto;"></div>
                        <div class="mt-4">

                            --}}{{-- <div id="message-result" style="color: #26ee26; margin-top: 10px;"></div> --}}{{--
                            <div id="barcode-result" style="color: red; margin-top: 35px;"></div>
                            <div id="error-message" style="color: red; margin-top: 10px;"></div>
                            <div class="hstack gap-2 justify-content-center">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng
                                </button>
                                <button type="button" class="btn btn-warning" id="scanAnotherBtn">Quét lại
                                </button>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</div> --}}

{{-- <script>
    /*modal quét qr*/
    document.addEventListener("DOMContentLoaded", function () {
        const scanModal2 = document.getElementById('scanModal2');
        const scanAnotherBtn2 = document.getElementById("scanAnotherBtn2");
        const errorMessage2 = document.getElementById("error-message2");
        const barcodeResult2 = document.getElementById("barcode-result2");

        // Khởi tạo sự kiện mở modal
        scanModal2.addEventListener('shown.bs.modal', startScanner);
        scanModal2.addEventListener('hidden.bs.modal', function () {
            stopScanner();
            clearBarcodeResult();
        });

        // Hàm khởi động Quagga
        function startScanner() {
            Quagga.init({
                inputStream: {
                    type: "LiveStream",
                    target: document.querySelector("#camera"),
                    constraints: {
                        width: 640,
                        height: 380,
                        facingMode: "user" // Thay đổi thành "environment" neeus sử dụng camera sau
                    }
                },
                decoder: {
                    readers: ["code_128_reader"]
                }
            }, function (err) {
                if (err) {
                    console.log("Lỗi: ", err);
                    errorMessage2.innerText = "Không thể truy cập camera, vui lòng kiểm tra quyền truy cập!";
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(detectedCode);
        }

        // Hàm dừng Quagga
        function stopScanner() {
            Quagga.stop();
            Quagga.offDetected(detectedCode);
        }

        // Hàm xử lý mã quét được
        function detectedCode(result) {
            const code = result.codeResult.code;
            barcodeResult2.innerText = code; // Hiển thị mã vạch quét được
            stopScanner(); // Dừng scanner sau khi đọc được mã

            // Gửi mã code qua AJAX
            fetch('{{ route("admin.tickets.processScan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({code: code})
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(error => {
                    console.error("Lỗi:", error);
                    errorMessage2.innerText = 'Lỗi kết nối, vui lòng thử lại sau!';
                });
        }

        // Hàm xóa mã vạch và thông báo lỗi
        function clearBarcodeResult() {
            barcodeResult2.innerText = ""; // Xóa mã vạch quét được khi mở lại modal
            errorMessage2.innerText = ""; // Xóa thông báo lỗi nếu có
        }

        // Xử lý khi bấm nút "Quét lại mã khác"
        scanAnotherBtn2.addEventListener("click", function () {
            barcodeResult2.innerText = ""; // Xóa mã quét được trước đó
            errorMessage2.innerText = ""; // Xóa thông báo lỗi
            startScanner(); // Bắt đầu quét lại
        });
    });
</script> --}}
