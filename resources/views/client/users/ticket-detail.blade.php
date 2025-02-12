@extends('client.layouts.master')

@section('title')
    Chi tiết giao dịch
@endsection

@section('content')

    <body>
        <div class="container" style="margin-top: 40px; margin-bottom: 100px;">
            {{-- <div class="my-account-tabs">
                <a href="#my-account" aria-controls="best" role="tab" data-toggle="tab">
                    <div class="my-account-tab" role="presentation">THÔNG TIN TÀI KHOẢN</div>
                </a>
                <a href="#">
                    <div class="my-account-tab">THẺ THÀNH VIÊN</div>
                </a>
                <a href="#cinema-journey" aria-controls="trand" role="tab" data-toggle="tab">
                    <div class="my-account-tab" role="presentation">LỊCH SỬ GIAO DỊCH</div>
                </a>
                <a href="#">
                    <div class="my-account-tab">ĐIỂM POLY</div>
                </a>
                <a href="#">
                    <div class="my-account-tab">VOUCHER</div>
                </a>
            </div> --}}

            <div class="col-md-12">
                <div class="tab-content">
                    {{-- Chi tiết giao dịch --}}
                    @foreach ($ticketSeat as $ticket)
                        <div id="detail-ticket" class="tab-pane active">
                            <div class="row mb-3 title-detail">
                                <h3>Chi tiết giao dịch #{{ $ticket->code }} </h3>

                            </div>
                            <div class="content-detail">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="code-qr">
                                            <p class="text-img bold">Mã quét</p>

                                            <p><b>Ngày mua hàng:</b>
                                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                                            </p> <br>
                                            {{-- {!! $barcode !!} --}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- ảnh mã qr --}}
                                        {{-- <img width="150px" src="{{ asset('theme/client/images/img-qr.png') }}"
                                            alt=""> --}}
                                        {!! $qrCode !!}
                                    </div>
                                </div> <br>

                                <div class="box-address">
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="address-detail">
                                                <b>Địa chỉ thanh toán</b>
                                                <p>{{ $ticket->user->name }}</p>
                                                <p>{{ $ticket->user->address }}</p>
                                                <p>{{ $ticket->user->email }}</p>
                                                <p>{{ $ticket->user->phone }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="payment-checkout-detail">
                                                <b>Phương thức thanh toán</b>
                                                <p>{{ $ticket->payment_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row info-detail">
                                    <div class="col-md-12">
                                        <div>
                                            <b> Thông tin giao dịch</b>
                                        </div>
                                        {{-- <div>

                                            <button onclick="window.print()">In hóa đơn</button>
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="row box-detail-history">
                                    <div class="row">
                                        <div class="col-md-12" align='center'>
                                            CHI TIẾT GIAO DỊCH
                                        </div>
                                    </div>

                                </div>

                                <div class="table-history">
                                    <div class="row">
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
                                                @php
                                                    // Nhóm các ticketMovie theo movie_id
                                                    $ticketSeatsByMovie = $ticket->ticketSeats->groupBy('movie_id');
                                                @endphp

                                                @foreach ($ticketSeatsByMovie as $ticketSeats)
                                                    <tr>

                                                        <td>{{ $ticket->movie->name }}</td>
                                                        <td>
                                                            <b> {{ $ticket->cinema->name }}
                                                            </b> <br>
                                                            <p> {{ $ticket->room->name }}</p>
                                                            <p> {{ Carbon\Carbon::parse($ticket->showtime->date)->format('d/m/Y') }}
                                                            </p>
                                                            <p> {{ Carbon\Carbon::parse($ticket->showtime->start_time)->format('H:i') }}
                                                                -
                                                                {{ Carbon\Carbon::parse($ticket->showtime->end_time)->format('H:i') }}
                                                            </p>
                                                        </td>

                                                        {{-- Tên ghế và giá ghế --}}
                                                        <td>
                                                            <p>
                                                                @foreach ($ticketSeats as $item)
                                                                    <b>{{ $item->seat->typeSeat->name }}</b>:
                                                                    {{ $item->seat->name }} <br>
                                                                    {{-- {{ number_format($item->seat->typeSeat->price, 0, ',', '.') }}đ <br> --}}
                                                                @endforeach
                                                            </p>
                                                            {{-- Giá ghế --}}
                                                            <p>
                                                                @php
                                                                    // tổng giá trên cột price trong ticketSeat
                                                                    $totalPriceSeats = $ticketSeats->sum('price');
                                                                    echo number_format($totalPriceSeats, 0, ',', '.');
                                                                @endphp
                                                                đ
                                                            </p>
                                                        </td>


                                                        {{-- Tên combo và giá --}}
                                                        @php
                                                            $totalComboPrice = 0;
                                                        @endphp
                                                        <td>
                                                            @foreach ($ticket->ticketCombos as $ticketCombo)
                                                                <b>{{ $ticketCombo->combo->name }} x
                                                                    {{ $ticketCombo->quantity }}</b>

                                                                <p>
                                                                    {{ number_format($ticketCombo->price, 0, ',', '.') }}đ
                                                                </p>
                                                                @php
                                                                    $totalComboPrice +=
                                                                        $ticketCombo->combo->price *
                                                                        $ticketCombo->quantity;
                                                                    // echo number_format($totalComboPrice, 0, ',', '.')
                                                                @endphp
                                                            @endforeach
                                                        </td>


                                                        {{-- Thành tiền --}}
                                                        <td>
                                                            {{-- Thành tiền: Tổng giá ghế + Tổng giá combo --}}
                                                            <p>
                                                                @php
                                                                    // Tính tổng giá ghế
                                                                    $totalPriceSeats = $ticket->ticketSeats->sum(
                                                                        'price',
                                                                    );

                                                                    // Tính tổng giá combo (nếu có)
                                                                    $totalPriceCombos = $ticket->ticketCombos
                                                                        ? $ticket->ticketCombos->sum('price')
                                                                        : 0;

                                                                    // Tổng thành tiền
                                                                    $totalPrice = $totalPriceSeats + $totalPriceCombos;

                                                                    echo number_format($totalPrice, 0, ',', '.');
                                                                @endphp
                                                                đ
                                                            </p>
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <th colspan="5" class="total-detail xanh-fpt" align="right">


                                                            @if ($ticket->voucher_discount)
                                                            Voucher: - {{ number_format($ticket->voucher_discount, 0, ',', '.') }}đ
                                                                <br>
                                                            @endif

                                                            Tổng
                                                            cộng:
                                                            {{ number_format($ticket->total_price, 0, ',', '.') }}đ
                                                        </th>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            {{-- <tfoot>

                                        </tfoot> --}}
                                        </table>
                                        <div class="back-list-history">
                                            <a href="{{ route('my-account.edit') }}" >
                                                << Trở về</a>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </body>
    <!-- Modal Đổi mật khẩu -->


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    </body>
@endsection
