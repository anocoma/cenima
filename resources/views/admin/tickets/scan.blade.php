@extends('admin.layouts.master')

@section('title')
    scan
@endsection

@section('style-libs')
    <style>
        #camera {
            width: 300px;
            height: 300px;
            margin: 20px auto;
            border: 1px solid gray;
        }

        #barcode-result {
            text-align: center;
            margin-top: 10px;
        }

        #error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')

    <h1>Quét mã vạch</h1>
    <div id="camera"></div>
    <p id="result">Kết quả: <span id="barcode-result"></span></p>
    <div id="error-message"></div>

    <div id="options" style="display: none;">
        <button id="backToListBtn">Trở lại trang danh sách</button>
        <button id="scanAnotherBtn">Quét lại mã khác</button>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"
            integrity="sha512-bCsBoYoW6zE0aja5xcIyoCDPfT27+cGr7AOCqelttLVRGay6EKGQbR6wm6SUcUGOMGXJpj+jrIpMS6i80+kZPw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            startScanner();

            // Bắt đầu quét
            function startScanner() {
                Quagga.init({
                    inputStream: {
                        type: "LiveStream",
                        target: document.querySelector("#camera"),
                        constraints: {
                            width: 640,
                            height: 480,
                            facingMode: "user"
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader"]
                    }
                }, function (err) {
                    if (err) {
                        console.log('lỗi: ', err);
                        return;
                    }
                    // console.log("QuaggaJS đã được khởi tạo thành công");
                    Quagga.start();
                });

                // Xử lý mã đã quét được
                Quagga.onDetected(detectedCode);
            }

            // Dừng và xóa các sự kiện khi quét từng cái
            function stopScanner() {
                Quagga.stop();
                Quagga.offDetected(detectedCode);  // Xóa sự kiện đã đăng ký
            }

            // Xử lý kết quả mã quét được
            function detectedCode(result) {
                const code = result.codeResult.code;
                document.getElementById("barcode-result").innerText = code;
                stopScanner(); // Dừng quét khi mã đã được quét

                // Gửi mã code qua AJAX
                fetch('{{ route("admin.tickets.processScan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: code })
                })
                    .then(response => response.json())
                    .then(data => {

                        //quét từng cái và trả ra kết quả
                        alert(data.message);
                        if (data.success && data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else if (data.options) {
                            document.getElementById("options").style.display = "block"; // Hiển thị tùy chọn
                        }

                        //quét liên tục
                        /*if (data.success) {
                            if (data.redirect_url) {
                                alert(data.message);
                                window.location.href = data.redirect_url;
                            }
                        } else {
                            document.getElementById("options").style.display = "block";
                            document.getElementById("error-message").innerText = data.message;
                        }*/

                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        document.getElementById("error-message").innerText = 'Lỗi kết nối, vui lòng thử lại sau!';
                    });
            }

            // xử lý button
            document.getElementById("backToListBtn").addEventListener("click", function () {
                window.location.href = '{{ route("admin.tickets.index") }}';
            });

            document.getElementById("scanAnotherBtn").addEventListener("click", function () {
                document.getElementById("options").style.display = "none";
                document.getElementById("barcode-result").innerText = "";
                document.getElementById("error-message").innerText = "";
                startScanner();
            });
        });
    </script>
@endsection

