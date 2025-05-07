@extends('client.layouts.master')

@section('title')
    Checkout
@endsection

@section('content')
    <form action="{{ route('payment') }}" method="post" id="payment-form">
        @csrf
        <div class="st_dtts_wrapper float_left">
            <div class="container container-choose-seat">
                <div class="row">
                    <div class="mb-3 title-choose-seat">
                        <a href="/">Trang chủ ></a> <a href="#">Đặt vé ></a> <a
                            href="">{{ $showtime->movie->name }}</a>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 box-checkout">
                        <div class="st_dtts_left_main_wrapper float_left">
                            <div class="row">
                                <div class="col-md-12 box-list-status-seat">
                                    <div class="text-info-checkout">
                                        <div>
                                            <span class="ei--user"></span>
                                        </div>
                                        <div>
                                            <h4>Thông tin thanh toán</h4>
                                        </div>
                                    </div>
                                    {{-- thong tin --}}
                                    <div class="">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Họ tên:</th>
                                                    {{-- <th>Số điện thoại</th> --}}
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ Auth::user()->name }}</td>
                                                    {{-- <td>0987654321</td> --}}
                                                    <td>{{ Auth::user()->email }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <hr>
                                    <div>
                                        @php
                                            $seatGroups = $seats->groupBy('typeSeat.name'); // Nhóm ghế theo loại ghế
                                        @endphp

                                        @foreach ($seatGroups as $typeSeat => $seatsByType)
                                            @php
                                                $quantity = count($seatsByType); // Số lượng ghế cùng loại
                                                $price = $seatsByType->first()->showtimes->first()->pivot->price; // Lấy giá của 1 ghế
                                                $total = $quantity * $price; // Tính tổng tiền
                                            @endphp
                                            <div class="info-seat-checkout">
                                                <p>{{ $typeSeat }}</p>
                                                <span>{{ $quantity }} x {{ number_format($price, 0, ',', '.') }} =
                                                    {{ number_format($total, 0, ',', '.') }} Vnđ</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    {{-- combo, voucher, diem --}}
                                    <div class="box-combo-uu-dai">
                                        <div class="text-info-checkout">
                                            <div>
                                                {{-- <span class="ei--user"></span> --}}
                                                <span class="map--food"></span>
                                            </div>
                                            <div>
                                                <h4>Combo Ưu Đãi</h4>
                                            </div>
                                        </div>
                                        <div class="combo-uu-dai">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Ảnh minh họa</th>
                                                        <th>Tên combo</th>
                                                        <th>Mô tả</th>
                                                        <th>Số lượng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data as $item)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                    $url = $item->img_thumbnail;

                                                                    if (!\Str::contains($url, 'http')) {
                                                                        $url = Storage::url($url);
                                                                    }
                                                                @endphp
                                                                @if (!empty($item->img_thumbnail))
                                                                    <img src="{{ $url }}" alt=""
                                                                        width="100px" height="60px">
                                                                @endif
                                                            </td>
                                                            {{-- <td>{{ $item->name }} - {{ number_format($item->price_sale) }} Vnđ
                                                    </td> --}}
                                                            <td>{{ $item->name }} - <span class="combo-price"
                                                                    data-price="{{ $item->price_sale }}">{{ number_format($item->price_sale) }}</span>Vnđ
                                                            </td>

                                                            <td>
                                                                @foreach ($item->comboFood as $value)
                                                                    @foreach ($foods as $food)
                                                                        @if ($value->food_id == $food->id)
                                                                            <ul class="nav nav-sm flex-column">
                                                                                <li class="nav-item mb-2">
                                                                                    <span
                                                                                        class="fw-semibold">{{ $food->type }}:
                                                                                    </span>
                                                                                    {{ $food->name }} x
                                                                                    ({{ $value->quantity }})
                                                                                </li>
                                                                            </ul>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </td>

                                                            <td>
                                                                <div class="quantity-container">
                                                                    <button type="button"
                                                                        class="quantity-btn decrease">-</button>
                                                                    <input type="text" class="quantity-input"
                                                                        name="combo[{{ $item->id }}]" value="0"
                                                                        min="1" max="10" readonly>
                                                                    <button type="button"
                                                                        class="quantity-btn increase">+</button>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="box-voucher">
                                            <div class="text-info-checkout">
                                                <div>
                                                    {{-- <span class="ei--user"></span> --}}
                                                    <span class="mdi--voucher"></span>
                                                </div>
                                                <div>
                                                    <h4>Giảm giá</h4>
                                                </div>
                                            </div>
                                            <div class="info-voucher-checkout">
                                                <div class="voucher-section">
                                                    <div class="voucher-title">Poly Voucher</div>
                                                    <form id="voucher-form" method="POST">
                                                        <div class="voucher-form">
                                                            @csrf
                                                            <label for="voucher_code">Vui lòng nhập mã voucher vào ô trống
                                                                phía
                                                                dưới để được giảm giá!</label> <br>
                                                            <div class="form-row">
                                                                <input type="text" name="voucher_code" id="voucher_code"
                                                                    placeholder="Nhập mã voucher" @guest disabled @endguest>

                                                                <button type="submit" id="apply-voucher-btn"
                                                                    @guest disabled @endguest>Xác nhận
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div id="voucher-response"></div>
                                                </div>



                                                {{-- diem --}}
                                                <div class="points-section">
                                                    <div class="points-title">Điểm Poly</div>
                                                    <form class="points-form" action="">
                                                        <table class="points-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Điểm hiện có</th>
                                                                    <th>Nhập điểm</th>
                                                                    <th>Số tiền được giảm</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1900</td>
                                                                    <td><input type="text" name="point_use"
                                                                            placeholder="Nhập điểm"></td>
                                                                    <td>= 0 Vnđ</td>
                                                                    <td>
                                                                        <button type="submit">Đổi điểm</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    {{-- tong tien --}}
                                    <div class="total-price-checkout">
                                        <div>
                                            <p>Tổng tiền:</p>
                                            <p class="text-danger total-price-display">
                                                {{ number_format($checkoutData['total_price'], 0, ',', '.') }} Vnđ
                                            </p>
                                            <input type="hidden" name="tong_tien_ban_dau" id="total-price"
                                                value="{{ $checkoutData['total_price'] }}">
                                        </div>
                                        <div>
                                            <p>Số tiền được giảm:</p>
                                            <p class="text-danger total-discount">0 Vnđ</p>
                                        </div>
                                        <div>
                                            <p>Số tiền cần thanh toán:</p>
                                            <p class="text-danger total-price-payment">
                                                {{ number_format($checkoutData['total_price'], 0, ',', '.') }} Vnđ
                                            </p>
                                        </div>
                                    </div>
                                    {{-- phuong thuc thanh toan --}}
                                    <div class="box-payment-checkout">
                                        <div class="text-info-checkout">
                                            <div>
                                                {{-- <span class="ei--user"></span> --}}
                                                <span class="ic--baseline-payment"></span>
                                            </div>
                                            <div>
                                                <h4>Phương thức thanh toán</h4>
                                            </div>
                                        </div>
                                        <div class="payment-checkout">
                                            <div>
                                                <p>Chọn thẻ thanh toán</p>
                                            </div>
                                            <hr>
                                            <div class="img-payment-checkout">
                                        
                                                <div>
                                                    <input type="radio" name="payment_name" value="momo" checked>
                                                    <img src="{{ asset('theme/client/images/index_III/vi-momo.ico') }}"
                                                        alt="">
                                                    <label for="">Ví MoMo</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="payment_name" value="zalopay">
                                                    <img src="{{ asset('theme/client/images/index_III/vi-zalo-pay.png') }}"
                                                        alt="">
                                                    <label for="">Ví ZaloPay</label>
                                                </div>
                                                {{-- <div>
                                                    <input type="radio" name="payment_name" value="vnpay">
                                                    <img src="{{ asset('theme/client/images/index_III/vi-momo.ico') }}"
                                                        alt="">
                                                    <label for="">Ví VnPay</label>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- 10p --}}
                                    <div class="giu-cho-checkout">
                                        <div>
                                            <p>Vui lòng kiểm tra thông tin đầy đủ trước khi qua bước tiếp theo. <br>
                                                *Vé mua rồi không hoàn trả lại dưới mọi hình thức.</p>
                                        </div>
                                        <div>
                                            <p>Thời gian còn lại:</p>
                                            <p id="timer" class="bold">10:00</p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="st_dtts_bs_wrapper float_left info-movie">
                                    <div class="st_dtts_bs_heading float_left">
                                        <p>Thông tin phim</p>
                                    </div>
                                    <div class=" float_left">

                                        <ul>
                                            <li>
                                                <div>
                                                    @php
                                                        $url = $showtime->movie->img_thumbnail;

                                                        if (!\Str::contains($url, 'http')) {
                                                            $url = Storage::url($url);
                                                        }
                                                    @endphp
                                                    <img width="150px" src="{{ $url }}" alt="">
                                                </div>
                                                <div>
                                                    <h3>{{ $showtime->movie->name }}</h3>
                                                    <br>
                                                    <p>{{ $showtime->format }}</p>
                                                </div>

                                            </li>
                                            <li>Thể loại: <span class="bold">{{ $showtime->movie->category }}</span>
                                            </li>
                                            <li> Thời lượng: <span class="bold">{{ $showtime->movie->duration }}
                                                    phút</span>
                                            </li>
                                            <hr>
                                            <li> Rạp chiếu: <span
                                                    class="bold">{{ $showtime->room->cinema->name }}</span>
                                            </li>
                                            <li> Ngày chiếu: <span
                                                    class="bold">{{ \Carbon\Carbon::parse($showtime->movie->release_date)->format('d/m/Y') }}</span>
                                            </li>
                                            <li> Giờ chiếu: <span
                                                    class="bold">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                                            </li>
                                            <li> Phòng chiếu: <span class="bold">{{ $showtime->room->name }}</span></li>
                                            <li>Ghế ngồi: <span id="selected-seats"
                                                    class="bold">{{ $checkoutData['selected_seats_name'] }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="total-price-choose-seat float_left">
                                        <div class="total-price-choose-seat float_left">
                                            {{--  ticket --}}
                                            <input type="hidden" name="code" value="{{ strtoupper(\Str::random(10)) }}">
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                            <input type="hidden" name="voucher_discount" id="total-discount">
                                            <input type="hidden" name="total_price" id="total-price-payment">

                                            {{--ticketseat--}}
                                            <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                                            @foreach ($checkoutData['seat_ids'] as $seatId)
                                                <input type="hidden" name="seat_id[]" value="{{ $seatId }}">
                                            @endforeach

                                            <a href="{{ route('choose-seat', $showtime->id) }}">Quay lại</a>
                                            <button type="submit" id="btnPayment">Tiếp tục</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('theme/client/css/checkout.css') }}">
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var routeUrl = "{{ route('applyVoucher') }}";
        var csrfToken = "{{ csrf_token() }}";

        let sessionTotalPrice = parseInt("{{ session('total_price', 0) }}");
        let discountAmount = 0; // Số tiền được giảm

        const totalDiscountElement = document.querySelector('.total-discount');
    </script>
@endsection
