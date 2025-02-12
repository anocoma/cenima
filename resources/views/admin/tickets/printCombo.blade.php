@extends('admin.layouts.master')

@section('title')
    In vé
@endsection

@section('style-libs')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        .ticket-container {
            width: 300px;
            background-color: #ffe5e5;
            padding: 15px;
            border-radius: 5px;
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .ticket-header h2 {
            font-size: 18px;
            font-weight: bold;
            color: #d63384;
        }

        .ticket-info {
            font-size: 14px;
        }

        .ticket-info p {
            margin-bottom: 5px;
        }

        .ticket-info strong {
            font-weight: bold;
        }

        .ticket-info .highlight {
            font-weight: bold;
            color: #d63384;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .ticket-container, .ticket-container * {
                visibility: visible;
            }

            .ticket-container {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%; /* Chiếm toàn bộ chiều rộng trang in */
                margin: 0; /* Loại bỏ lề */
                border: none; /* Loại bỏ viền */
                box-shadow: none; /* Loại bỏ bóng */
            }

            @page {
                size: auto; /*  Tự động điều chỉnh khổ giấy */
                margin: 10mm; /* Lề 10mm cho tất cả các cạnh */
            }

            .no-print {
                display: none;
            }
        }

    </style>
@endsection

@section('content')
    {{--    <div class="d-flex justify-content-around">--}}
    <div class="ticket-container">
        <div>
            <div class="flex-shrink-0 no-print">
                @if($oneTicket->status == 'Đã xuất vé')
                    <a href="#" class="btn btn-success btn-sm"
                       onclick="window.print()"><i
                            class="ri-download-2-fill align-middle me-1"></i> In hóa đơn</a>
                @endif
            </div>
            <div class="ticket-header">
                <h2>HÓA ĐƠN ĐỒ ĂN</h2>
            </div>
            <div class="ticket-info border-bottom-dashed">
                <p><strong>Chi nhánh công ty Poly Cinemas vietnam tại {{ $oneTicket->cinema->branch->name }}</strong></p>
                <p>Địa chỉ: 1 Quang Trung - {{ $oneTicket->cinema->name }} - {{ $oneTicket->cinema->branch->name }}</p>
                <p>mst: 012147901412</p>
            </div>
            <div class="ticket-info border-bottom-dashed mt-2">
                <p><strong>Poly Cinemas {{ $oneTicket->cinema->name }} - {{ $oneTicket->cinema->branch->name }}  </strong></p>
                <p>Thời gian: {{ $oneTicket->ticketCombos->first()->created_at }}</p>
            </div>
            <div class="ticket-info border-bottom-dashed mt-2">
                @foreach ($oneTicket->ticketCombos as $ticketCombo)
                    @php
                        $combo = $ticketCombo->combo;
                    @endphp

                    <p><b>{{ $combo->name }} x {{ $ticketCombo->quantity }} ( {{ number_format($combo->price * $ticketCombo->quantity) }}
                            vnđ )</b></p>

                    <ul>
                        @foreach ($combo->food as $food)
                            <li>{{ $food->name }} x {{ $food->pivot->quantity }}</li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
            <div class="ticket-info mt-2">
                <p><strong>Tổng cộng:</strong> {{ number_format($ticket->ticketCombos->sum(function ($ticketCombo) {
                return $ticketCombo->combo->price * $ticketCombo->quantity;
            }), 0, ',', '.') }} vnđ</p>

            </div>
            <div class="mt-4 border-top-double">
                <div class="d-flex justify-content-center mt-2">{!! $barcode !!}</div>
                <div class="d-flex justify-content-center">
                    <p><strong>{{ $oneTicket->code }}</strong></p>
                </div>
            </div>
        </div>
    </div>
    {{--    <div class="ticket-container">
            <div>
                <div class="flex-shrink-0">
                    @if($oneTicket->status == 'Đã xuất vé')
                        <a href="#" class="btn btn-success btn-sm"
                           onclick="window.print()"><i
                                class="ri-download-2-fill align-middle me-1"></i> In hóa đơn</a>
                    @endif
                </div>
                <div class="ticket-header">
                    <h2>VÉ XEM PHIM</h2>
                </div>
                <div class="ticket-info border-bottom-dashed">
                    <p><strong>Chi nhánh công ty Poly Cinemas vietnam tại {{ $oneTicket->cinema->branch->name }}</strong></p>
                    <p>Địa chỉ: 1 Quang Trung - {{ $oneTicket->cinema->name }} - {{ $oneTicket->cinema->branch->name }}</p>
                    <p>mst: 012147901412</p>
                </div>
                <div class="ticket-info border-bottom-dashed mt-2">
                    <p><strong>Poly Cinemas {{ $oneTicket->cinema->name }} - {{ $oneTicket->cinema->branch->name }}  </strong></p>
                    <p>Thời gian: 28/10/2024 15:05:00</p>
                </div>
                <div class="ticket-info border-bottom-dashed mt-2">
                    <p><strong>{{ $oneTicket->movie->name }} ({{ $oneTicket->movie->movieVersions->first()->name }})</strong></p>
                    @php
                        $rating = $oneTicket->movie->rating;
                        $description = null;

                        if ($rating == 'P') {
                            $description = 'Mọi độ tuổi';
                        } elseif ($rating == 'T13') {
                            $description = 'Dưới 13 tuổi và có người bảo hộ đi kèm';
                        } elseif ($rating == 'T16') {
                            $description = '13+';
                        } elseif ($rating == 'T18') {
                            $description = '16+';
                        } elseif ($rating == 'C18') {
                            $description = '18+';
                        }
                    @endphp
                    <p><strong>{{ $oneTicket->movie->rating }} @if ($description)
                                <span>({{ $description }})</span>
                            @endif</strong></p>


                    <p><strong>Phòng:</strong> {{ $oneTicket->room->name }}</p>
                    <p><strong>Ghế:</strong>
                        @foreach($oneTicket->ticketSeats as $seat)
                            {{ $seat->seat->name }},
                        @endforeach
                    </p>
                </div>
                <div class="ticket-info mt-2">
                    <div class="d-flex justify-content-between">
                    <span>
                        <strong>Giá vé:</strong>
                    </span>
                        <span><strong>{{ number_format($oneTicket->total_price, 0, ',', '.') }} VND</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Giảm giá:</strong> </span>
                        <span><strong>{{ number_format($oneTicket->voucher_discount, 0, ',', '.') }} VND</strong></span>
                    </div>

                </div>
                <div class="mt-4 border-top-double">
                    <div class="d-flex justify-content-center mt-2">{!! $barcode !!}</div>
                    <div class="d-flex justify-content-center">
                        <p><strong>{{ $oneTicket->code }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        </div>--}}
    <script>
        // window.print();
    </script>
@endsection
