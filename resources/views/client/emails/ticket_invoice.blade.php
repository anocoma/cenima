<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Poly Cinemas</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #444;
        }

        .container {
            max-width: 550px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px 0px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            margin-top: 20px;
            border: 2px solid #dadada;
        }

        .barcode {
            text-align: center;
            margin: 20px 0;
        }

        .barcode p {
            text-align: center;
            margin: 5px 0;
        }

        .hihi{
            margin-top: 15px; 
            font-size: 15px
        }

        h2 {
            font-size: 15px;
            color: #1c2551;
            margin: 0px;
        }

        .info-section {
            padding:0 40px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table th,
        .info-table td {
            padding: 5px 0px;
            font-size: 14px;
            color: #373737;
        }

        .info-table th {
            text-align: left;
            font-weight: 200;
        }

        .info-table td {
            text-align: right;
        }

        .total-row {
            font-size: 20px;
            font-weight: bold;
            color: #e50914;
        }

        .footer {
            /* text-align: center; */
            font-size: 12px;
            color: #ffffff;
            background-color: #192041;
            margin:0 40px;
            padding: 20px 30px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .highlight {
            font-weight: bold;
        }

        /* Media Queries cho màn hình nhỏ */
        @media screen and (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 10px 15px;
            }

            .hihi{
                margin-top: 10px; 
                font-size: 12px
            }

            .info-section {
                padding: 0 20px;
            }

            .footer {
                margin: 0 20px;
                padding: 15px 20px;
            }

            .info-table th,
            .info-table td {
                font-size: 12px;
                padding: 4px 0;
            }

            h2 {
                font-size: 14px;
            }

            .barcode img {
                max-width: 100%;
                height: auto;
            }

            .footer p {
                font-size: 10px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                margin: 5px;
                padding: 10px;
            }

            .info-section {
                padding: 0 10px;
            }

            .hihi{
                margin-top: 5px; 
                font-size: 10px
            }

            .footer {
                margin: 0 10px;
                padding: 10px 15px;
            }

            .info-table th,
            .info-table td {
                font-size: 10px;
                padding: 3px 0;
            }

            h2 {
                font-size: 12px;
            }

            .footer p {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @php
            $fileName = $ticket->code . '.png';
            $directory = public_path('storage/barcodes');
            $filePath = $directory . '/' . $fileName;

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if (!file_exists($filePath)) {
                try {
                    $barcodeImage = DNS1D::getBarcodePNG($ticket->code, 'C128', 1.5, 50, [0, 0, 0]);
                    file_put_contents($filePath, base64_decode($barcodeImage));
                } catch (\Exception $e) {
                    \Log::error('Không thể tạo mã vạch: ' . $e->getMessage());
                }
            }
        @endphp 
        
        <div class="barcode">
            {{-- Hiển thị mã vạch đã lưu --}}
            <img src="{{ $message->embed($filePath) }}" alt="Barcode">
            <p style="margin: 0; padding: 0; font-size: 14px ;">{{ $ticket->code }}</p>
            <p class="hihi">Vui lòng đưa mã số này đến quầy vé Poly Cinemas để nhận vé của bạn!</p>
        </div>

        <div class="info-section">
            <h2>THÔNG TIN VÉ</h2>
            <table class="info-table">
                <tr>
                    <th>Mã vé</th>
                    <td>{{ $ticket->code }}</td>
                </tr>
                <tr>
                    <th>Tên phim</th>
                    <td>{{ $ticket->showtime->movie->name }}</td>
                </tr>
                <tr>
                    <th>Rạp</th>
                    <td>{{ $ticket->showtime->cinema->name }}</td>
                </tr>
                <tr>
                    <th>Phòng chiếu</th>
                    <td>{{ $ticket->showtime->room->name }}</td>
                </tr>
                <tr>
                    <th>Suất chiếu</th>
                    <td>{{ \Carbon\Carbon::parse($ticket->showtime->start_time)->format('d/m/Y H:i') }}</td>

                </tr>
                <tr>
                    <th>Ghế</th>
                    <td>
                        @foreach ($ticket->ticketSeats as $ticketSeat)
                            {{ $ticketSeat->seat->name }}@if (!$loop->last),
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Tổng giá ghế</th>
                    <td>
                        @php
                            $totalPrice = 0;
                            foreach ($ticket->ticketSeats as $ticketSeat) {
                                $totalPrice += $ticketSeat->price;
                            }
                        @endphp
                        {{ number_format($totalPrice, 0, ',', '.') }}đ
                    </td>
                </tr>
            </table>
            <hr>
        </div>


        @if ($ticket->ticketCombos->isNotEmpty())
            <div class="info-section">
                <h2>THÔNG TIN COMBO</h2>
                <table class="info-table">
                    @foreach ($ticket->ticketCombos as $ticketCombo)
                        <tr>
                            <th>{{ $ticketCombo->combo->name }}</th>
                            <td>{{ $ticketCombo->quantity}} x {{ number_format($ticketCombo->combo->price_sale, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </table>
                <hr>
            </div>
        @endif

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <th>Khuyễn mãi</th>
                    <td>{{ number_format($ticket->voucher_discount, 0, ',', '.') }}đ</td>
                </tr>
                <tr>
                    <th>Điểm Poly</th>
                    <td>{{ number_format($ticket->point_discount, 0, ',', '.') }}đ</td>
                </tr>
            </table>
            <hr>
        </div>

        <div class="info-section" style="margin-bottom: 5px">
            <table class="info-table">
                <tr>
                    <th>Tổng cộng</th>
                    <td>{{ number_format($ticket->total_price, 0, ',', '.') }}đ</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p><span class="highlight">Poly Cinemas Việt Nam</span></p>
            <p>Tòa nhà FPT Polytechnic, Phố Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
            <p>Email: polycinemas@poly.cenimas</p>
            <p>Hotline: 0123456789</p>
        </div>
    </div>
</body>

</html>
