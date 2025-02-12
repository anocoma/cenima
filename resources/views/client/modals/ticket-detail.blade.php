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
            position: fixed !important;
            z-index: 1000 !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
            /* #192041 */
            justify-content: center !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
        }

        .t-black {
            color: black !important;
        }

        .modal-content-ticket-detail {
            background-color: white !important;
            width: 90% !important;
            /* Tăng độ rộng để có không gian bên trái và phải */
            max-width: 1100px !important;
            /* Mở rộng chiều rộng tối đa */
            border-radius: 15px !important;
            /* Tăng bo góc để trông mềm mại hơn */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3) !important;
            font-family: Arial, sans-serif !important;
            color: black;
            line-height: normal;
            position: relative !important;
            padding: 15px !important;
            /* Tăng padding bên trong */
            overflow-y: auto !important;
            margin-left: 5% !important;
            /* Thêm khoảng cách từ trái */
            margin-right: 5% !important;
            /* Thêm khoảng cách từ phải */
        }

        /* Header Modal */
        .modal-header-ticket-detail {
            background-color: #434f89 !important;
            /* #434f89 */
            color: white !important;
            padding: 15px 15px !important;
            /* Tăng padding */
            font-size: 20px !important;
            /* Tăng kích thước chữ */
            font-weight: bold !important;
            border-radius: 15px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        .close-btn-ticket-detail {
            color: white !important;
            font-size: 32px !important;
            /* Tăng kích thước chữ đóng */
            cursor: pointer !important;
        }

        .modal-body-ticket-detail {
            padding: 15px !important;
            /* Tăng padding */
        }

        /* Thông tin chính */
        .info-block-ticket-detail {
            display: flex !important;
            justify-content: space-between !important;
            margin-bottom: 15px !important;
            /* Tăng margin-bottom */
            font-size: 16px !important;
            /* Tăng font-size */
        }

        /* Mã vạch */
        .barcode-ticket-detail img {
            max-width: 100% !important;
            /* Giảm kích thước mã vạch */
            border-radius: 10px !important;
        }

        .transaction-details-ticket-detail h3 {
            display: block;
            font-size: 1.17em;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
            unicode-bidi: isolate;
            font-family: Arial, sans-serif;
        }

        /* Cấu trúc bảng giao dịch */
        .transaction-details-ticket-detail .table-ticket-detail {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 15px !important;
            /* Tăng khoảng cách trên bảng */
        }

        .transaction-details-ticket-detail .table-ticket-detail th,
        .transaction-details-ticket-detail .table-ticket-detail td {
            padding: 12px !important;
            /* Tăng padding */
            border: 1px solid #ddd !important;
            text-align: left !important;
        }

        .transaction-details-ticket-detail .table-ticket-detail th {
            background-color: #ff7307 !important;
            /* #ff7307 */
            color: white !important;
            font-weight: normal !important;
        }

        .transaction-details-ticket-detail .table-ticket-detail td {
            font-size: 14px !important;
            /* Tăng font-size */
            background-color: #f9f9f9 !important;
        }

        .transaction-details-ticket-detail .table-ticket-detail tr:hover {
            background-color: #f1f1f1 !important;
        }

        /* Tổng cộng */
        .payment-summary-ticket-detail {
            font-size: 18px !important;
            /* Tăng font-size */
            font-weight: bold !important;
            background-color: #f0f4f7 !important;
            border-radius: 15px !important;
            margin-top: 15px !important;
            padding: 15px !important;
            /* Tăng padding */
        }

        .payment-summary-ticket-detail div {
            /* Tăng margin-bottom */
        }

        .payment-summary-ticket-detail .amount {
            color: #ff7307 !important;
            /* #ff7307 */
            font-weight: bold !important;
            margin-top: 10px;
        }

        .payment-summary-ticket-detail .discounts {
            color: #151b39 !important;
            /* #151b39 */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content-ticket-detail {
                width: 90% !important;
                padding: 15px !important;
                /* Giảm padding */
            }

            .info-block-ticket-detail {
                flex-direction: column !important;
                align-items: flex-start !important;
                font-size: 14px !important;
                /* Giảm font-size */
            }

            .info-block-ticket-detail div {
                margin-bottom: 8px !important;
                width: 100% !important;
            }

            .modal-header-ticket-detail {
                font-size: 18px !important;
                padding: 12px 15px !important;
            }

            .transaction-details-ticket-detail .table-ticket-detail,
            .transaction-details-ticket-detail .table-ticket-detail th,
            .transaction-details-ticket-detail .table-ticket-detail td {
                font-size: 12px !important;
                /* Giảm font-size */
                padding: 5px !important;
                /* Giảm padding */
            }

            .payment-summary-ticket-detail {
                padding: 15px !important;
                /* Giảm padding */
            }

            .barcode-ticket-detail img {
                max-width: 80% !important;
                margin: 0 auto !important;
            }
        }

        /* Responsive cho các màn hình nhỏ hơn */
        @media (max-width: 480px) {
            .modal-header-ticket-detail {
                font-size: 16px !important;
                padding: 10px 12px !important;
            }

            .transaction-details-ticket-detail .table-ticket-detail,
            .transaction-details-ticket-detail .table-ticket-detail th,
            .transaction-details-ticket-detail .table-ticket-detail td {
                font-size: 10px !important;
                /* Giảm font-size */
                padding: 3px !important;
                /* Giảm padding */
            }

            .payment-summary-ticket-detail {
                padding: 12px !important;
                /* Giảm padding */
            }

            .payment-summary-ticket-detail .amount {
                font-size: 16px !important;
            }

            .payment-summary-ticket-detail .discounts {
                font-size: 14px !important;
            }
        }
    </style>
</head>

<body>



    <!-- Modal -->
    <div id="transactionModal_{{ $ticket->code }}" class="modal-ticket-detail">
        <div class="modal-content-ticket-detail">
            <div class="modal-header-ticket-detail">
                <span>Chi tiết giao dịch</span>
                <span class="close-btn-ticket-detail" onclick="closeTicketDetail('{{ $ticket->code }}')">&times;</span>
            </div>

            <div class="modal-body-ticket-detail">
                <!-- Thông tin giao dịch -->
                <div class="info-block-ticket-detail">
                    <div>
                        <strong class="t-black">Mã giao dịch:</strong> {{ $ticket->code }}<br>
                        <strong class="t-black">Ngày mua hàng:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }} <br>
                        <strong class="t-black">Trạng thái:</strong> <span class="badge
                        @switch($ticket->status)
                            @case(App\Models\Ticket::NOT_ISSUED)
                                badge-not-issued
                                @break
                            @case(App\Models\Ticket::ISSUED)
                                badge-issued
                                @break
                            @case(App\Models\Ticket::EXPIRED)
                                badge-expired
                                @break
                        @endswitch
                    ">
                        {{ $ticket->status }}
                    </span>
                    </div>
                    <div class="barcode-ticket-detail">
                        {!! DNS1D::getBarcodeHTML($ticket->code, 'C128', 1.5, 60) !!}
                    </div>
                </div>

                <!-- Địa chỉ và phương thức thanh toán -->
                <div class="info-block-ticket-detail">
                    <div>
                        <strong class="t-black">Thông tin thanh toán:</strong><br>
                        {{ $ticket->user->name }}<br>
                        {{ $ticket->user->address }}<br>
                        {{ $ticket->user->email }}<br>
                        {{ $ticket->user->phone }}
                    </div>
                    <div>
                        <strong class="t-black">Phương thức thanh toán:</strong><br>
                        {{ $ticket->payment_name }}
                    </div>
                </div>

                <!-- Bảng thông tin giao dịch -->
                <div class="transaction-details-ticket-detail">
                    <h3 style="color: #434f89;">Thông tin giao dịch</h3>
                    <table class='table-ticket-detail'>
                        <thead>
                            <tr>
                                <th>Phim</th>
                                <th>Suất chiếu</th>
                                <th>Ghế</th>
                                <th>Combo</th>
                                <th>Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> {{ $ticket->movie->name }}</td>
                                <td> {{ $ticket->cinema->name }}
                                    <br>{{ $ticket->room->name }}
                                    <br>{{ \Carbon\Carbon::parse($ticket->showtime->date)->format('d/m/Y') }}
                                    <br>{{ \Carbon\Carbon::parse($ticket->showtime->start_time)->format('H:i') }} ~
                                    {{ \Carbon\Carbon::parse($ticket->showtime->end_time)->format('H:i') }}
                                </td>
                                <td> {{ implode(', ', $ticket->ticketSeats->pluck('seat.name')->toArray()) }}
                                    @php
                                        $priceSeat = $ticket->ticketSeats->sum('price');
                                    @endphp
                                    <br> <strong>{{ number_format($priceSeat , 0, ',', '.') . 'đ' }}</strong>
                                </td>
                                <td>
                                    @php
                                        $priceCombo = 0;
                                    @endphp
                                    @foreach ($ticket->ticketCombos as $ticketCombo)
                                        <p>
                                            {{ $ticketCombo->combo->name }} x {{ $ticketCombo->quantity }}
                                            @php
                                                $priceCombo += $ticketCombo->combo->price_sale * $ticketCombo->quantity;
                                            @endphp
                                        </p>
                                    @endforeach
                                    <strong> {{ number_format($priceCombo, 0, ',', '.') . 'đ' }}</strong>
                                </td>
                                <td><strong>{{ number_format( $priceSeat + $priceCombo, 0, ',', '.') . 'đ' }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tổng tiền và các khoản giảm giá -->
                <div class="payment-summary-ticket-detail">
                    <div>
                        <strong class="t-black">Tổng tiền:</strong> {{ number_format($priceSeat + $priceCombo, 0, ',', '.') . 'đ' }}
                    </div>
                    <div class="discounts">
                        @if ($ticket->voucher_discount > 0)
                            <strong class="t-black">Voucher:</strong> - {{ number_format($ticket->voucher_discount, 0, ',', '.') . 'đ' }}<br>
                        @endif
                        @if ($ticket->point_discount > 0)
                            <strong class="t-black">Điểm Poly:</strong> - {{ number_format($ticket->point_discount, 0, ',', '.') . 'đ' }}
                        @endif

                    </div>
                    <div class="amount">
                        Tổng tiền đã thanh toán: {{ number_format($ticket->total_price, 0, ',', '.') . 'đ' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTicketDetail(code) {
            document.getElementById(`transactionModal_${code}`).style.display = "flex";
            document.body.classList.add("no-scroll");
        }


        function closeTicketDetail(code) {
            document.getElementById(`transactionModal_${code}`).style.display = "none";
            document.body.classList.remove("no-scroll");
        }




        // Đóng modal khi người dùng nhấn bên ngoài modal
        window.onclick = function(event) {
            document.querySelectorAll('.modal-ticket-detail').forEach(function(modal) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    document.body.classList.remove("no-scroll");
                }
            });
        };
    </script>

</body>

</html>
