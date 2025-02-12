@extends('client.layouts.master')

@section('title')
    Xác thực và thanh toán
@endsection

@section('content')
    @if (Auth::user()->type == 'member')
        <form action="{{ route('payment') }}" method="post" id="payment-form">
            @csrf
            <div class="st_dtts_wrapper float_left">
                <div class="container container-choose-seat">
                    <div class="row">
                        <div class="mb-3 title-choose-seat">
                            <a href="/" class="cam">Trang chủ </a> <strong>></strong> <a href="#"
                                class="cam">Đặt vé
                            </a> <strong>></strong> <a href="/movies/{{ $showtime->movie->slug }}"
                                class="cam">{{ $showtime->movie->name }}</a>
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
                                                <h4 class='text-transform'>Thông tin thanh toán</h4>
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
                                                    <h4 class='text-transform'>Combo Ưu Đãi</h4>
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
                                                                    @foreach ($item->food as $itemFood)
                                                                        <ul class="nav nav-sm flex-column">
                                                                            <li class="nav-item mb-2">
                                                                                {{ $itemFood->name }} x
                                                                                ({{ $itemFood->pivot->quantity }})
                                                                        </ul>
                                                                    @endforeach
                                                                </td>

                                                                <td>
                                                                    <div class="quantity-container">
                                                                        <button type="button"
                                                                            class="quantity-btn decrease">-</button>
                                                                        <input type="text" class="quantity-input"
                                                                            name="combo[{{ $item->id }}]"
                                                                            value="0" min="1" max="10"
                                                                            readonly>
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
                                                        <h4 class='text-transform'>Giảm giá</h4>
                                                    </div>
                                                </div>
                                                <div class="info-voucher-checkout">
                                                    <div class="voucher-section">
                                                        <div class="voucher-title">Poly Voucher</div>

                                                        <div class="voucher-form">

                                                            <div
                                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                                <label for="voucher_code">Vui lòng nhập mã voucher để được
                                                                    giảm giá!</label>
                                                                {{-- <span id="showModalVoucher"
                                                                    style="color: #ff7307; cursor: pointer; margin-bottom: 5px">Voucher
                                                                    đang có</span> --}}
                                                            </div>
                                                            <div class="form-row">
                                                                <input type="text" name="voucher_code" id="voucher_code"
                                                                    placeholder="Nhập mã voucher">

                                                                <button type="button" id="apply-voucher-btn">Xác nhận
                                                                </button>
                                                            </div>

                                                            <div id="voucher-response">

                                                            </div>
                                                            {{-- @include('client.modals.modal-voucher') --}}
                                                        </div>
                                                    </div>



                                                    {{-- diem --}}
                                                    <div class="points-section">
                                                        <div class="points-title">Điểm Poly</div>
                                                        <div class="points-form" action="" id='form-point'>
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
                                                                        <td id='points-membership'>
                                                                            {{ number_format(auth()->user()->membership->points, 0, ',', '.') }}
                                                                        </td>
                                                                        <td><input type="text" name="use_points"
                                                                                id='use_points' placeholder="Nhập điểm">
                                                                        </td>
                                                                        <td>= <b><span id='point_discount'>0</span></b> Vnđ
                                                                        </td>
                                                                        <td style="width: 20%">
                                                                            <button type="button" id="apply-point">Đổi
                                                                                điểm</button>
                                                                            <button type="button" id="cancel-point"
                                                                                class='btn'
                                                                                style="display:none">Hủy</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

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
                                                    <h4 class='text-transform'>Phương thức thanh toán</h4>
                                                </div>
                                            </div>
                                            <div class="payment-checkout">
                                                <div>
                                                    <p class="bold">Chọn thẻ thanh toán</p>
                                                </div>
                                                <hr>
                                                <div class="img-payment-checkout">
                                                    <div>
                                                        <input type="radio" id="radio1" name="payment_name"
                                                            value="vnpay" checked>
                                                        <label for="radio1" style="margin-right: 5px"><img
                                                                src="{{ asset('theme/client/images/index_III/vi-vnpay.webp') }}"
                                                                alt="">Ví VnPay</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" id="radio2" name="payment_name"
                                                            value="momo">
                                                        <label for="radio2"><img
                                                                src="{{ asset('theme/client/images/index_III/vi-momo.ico') }}"
                                                                alt=""> Ví MoMo</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 10p --}}
                                        <div class="giu-cho-checkout">
                                            <div>
                                                <div>
                                                    <label for="checkbox" style="margin: 0; font-weight: 100">
                                                        <input type="checkbox" id="checkbox">
                                                        Tôi đồng ý với điều khoản sử dụng và mua vé.
                                                    </label>
                                                    <p><span class="text-danger">* </span>Xem chi tiết điều khoản sử dụng
                                                        và mua vé,
                                                        <span id="showModal" style="color: #ff7307; cursor: pointer;">
                                                            tại đây.</span>
                                                    </p>

                                                    @include('client.modals.modal-clause')

                                                </div>
                                            </div>
                                            {{-- <div style="display: flex">
                                                <p style="margin-right: 5px">Thời gian còn lại: </p>
                                                <p id="timer" class="bold">
                                                    {{ gmdate('i:s', $checkoutData['remainingSeconds']) }}</p>
                                            </div> --}}

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                            <div class="row" style="margin-bottom: 15px">
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
                                                <li>
                                                    <span><i class="fa fa-tags icons"></i> Thể loại</span>
                                                    <span class="bold">{{ $showtime->movie->category }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa fa-clock-o icons"></i> Thời lượng</span>
                                                    <span class="bold">{{ $showtime->movie->duration }} phút</span>
                                                </li>
                                                <hr style="border: 0; border-top: 2px dashed #7f7d7d; margin: 20px 0; ">
                                                <li>
                                                    <span><i class="fa-solid fa-landmark"></i> Rạp chiếu</span>
                                                    <span class="bold">{{ $showtime->room->cinema->name }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa-regular fa-calendar-days"></i> Ngày chiếu</span>
                                                    <span
                                                        class="bold">{{ \Carbon\Carbon::parse($showtime->date)->format('d/m/Y') }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa fa-clock-o icons"></i> Giờ chiếu</span>
                                                    <span
                                                        class="bold">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa-solid fa-desktop"></i> Phòng chiếu</span>
                                                    <span class="bold">{{ $showtime->room->name }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa-solid fa-cubes"></i> Ghế ngồi</span>
                                                    <span id="selected-seats"
                                                        class="bold">{{ $checkoutData['selected_seats_name'] }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="total-price-choose-seat">
                                            {{--  ticket --}}
                                            <input type="hidden" name="code"
                                                value="{{ strtoupper(\Str::random(10)) }}">
                                            <input type="hidden" name="user_id" id="userId"
                                                value="{{ Auth::user()->id }}">

                                            <input type="hidden" name="price_seat" id="price-seat"
                                                value="{{ $checkoutData['total_price'] }}">
                                            <input type="hidden" name="price_combo" id="price-combo">
                                            <input type="hidden" name="point_discount" id="point-discount">
                                            <input type="hidden" name="total_discount" id="total-discount">
                                            <input type="hidden" name="voucher_discount" id="voucher-discount">
                                            <input type="hidden" name="total_price" id="total-price-payment">

                                            {{-- ticketseat --}}
                                            <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                                            @foreach ($checkoutData['seat_ids'] as $seatId)
                                                <input type="hidden" name="seat_id[]" value="{{ $seatId }}">
                                            @endforeach

                                            <a href="{{ route('choose-seat', $showtime->slug) }}">Quay lại</a>

                                            <button type="submit" id="btnPayment">Tiếp tục</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="st_dtts_bs_wrapper float_left info-movie time-remaining">
                                        <p class="text-time">Thời gian còn lại</p>
                                        <p id="timer" class="bold">
                                            {{ gmdate('i:s', $checkoutData['remainingSeconds']) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <form action="{{ route('payment-admin') }}" method="post" id="payment-form">
            @csrf
            <div class="st_dtts_wrapper float_left">
                <div class="container container-choose-seat">
                    <div class="row">
                        <div class="mb-3 title-choose-seat">
                            <a href="/" class="cam">Trang chủ </a> <strong>></strong> <a href="#"
                                class="cam">Đặt vé
                            </a> <strong>></strong> <a href="/movies/{{ $showtime->movie->slug }}"
                                class="cam">{{ $showtime->movie->name }}</a>
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
                                                <h4 class='text-transform'>Thông tin thanh toán</h4>
                                            </div>
                                        </div>
                                        {{-- thong tin --}}
                                        <div class="">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div>
                                                        <div>
                                                            <span class="bold">Thông tin người đặt:</span>
                                                        </div>
                                                        <div class='info-checkout'>
                                                            <ul class="text-dark">
                                                                <li>Mã: {{ auth()->user()->id }}</li>
                                                                <li>Họ tên: {{ auth()->user()->name }}</li>
                                                                <li>Email: {{ auth()->user()->email }} </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div id="card-member">
                                                        <div>
                                                            <span class="bold">Thông tin khách hàng: </span>
                                                        </div>
                                                        <div class='info-checkout'>
                                                            <div id='info-membership'>
                                                                <!-- Thông tin khách hàng sẽ được hiển thị ở đây sau khi tìm kiếm -->
                                                            </div>
                                                            <div class="form-membership" id='form-membership'>
                                                                <input type="text" id="data_membership"
                                                                    placeholder="Thẻ thành viên">
                                                                <button type="button" id='submit-membership'>Xác
                                                                    nhận</button>
                                                            </div>
                                                            <div id='error-membership'></div>
                                                            <div id="change-membership"
                                                                style="text-align: right; margin-top: 10px; display:none">
                                                                <button id="change-user" type="button"
                                                                    class='btn'>Thay đổi</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

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
                                                    <h4 class='text-transform'>Combo Ưu Đãi</h4>
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
                                                                    @foreach ($item->food as $itemFood)
                                                                        <ul class="nav nav-sm flex-column">
                                                                            <li class="nav-item mb-2">
                                                                                {{ $itemFood->name }} x
                                                                                ({{ $itemFood->pivot->quantity }})
                                                                            </li>
                                                                        </ul>
                                                                    @endforeach
                                                                </td>

                                                                <td>
                                                                    <div class="quantity-container">
                                                                        <button type="button"
                                                                            class="quantity-btn decrease">-</button>
                                                                        <input type="text" class="quantity-input"
                                                                            name="combo[{{ $item->id }}]"
                                                                            value="0" min="1" max="10"
                                                                            readonly>
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
                                                        <h4 class='text-transform'>Giảm giá</h4>
                                                    </div>
                                                </div>
                                                <div class="info-voucher-checkout">
                                                    <div class="voucher-section">
                                                        <div class="voucher-title">Poly Voucher</div>

                                                        <div class="voucher-form">

                                                            <div
                                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                                <label for="voucher_code">Vui lòng nhập mã voucher để được
                                                                    giảm giá!</label>
                                                                {{-- <span id="showModalVoucher"
                                                                    style="color: #ff7307; cursor: pointer; margin-bottom: 5px">Voucher
                                                                    đang có</span> --}}
                                                            </div>
                                                            <div class="form-row">
                                                                <input type="text" name="voucher_code"
                                                                    id="voucher_code" placeholder="Nhập mã voucher">

                                                                <button type="button" id="apply-voucher-btn"
                                                                    class='btn btn-danger'>Xác nhận
                                                                </button>
                                                            </div>
                                                            <div id="voucher-response">

                                                            </div>
                                                            {{-- @php
                                                               $vouchers = App\Http\Controllers\Client\UserController::getVoucher(1);
                                                            @endphp --}}
                                                            <div id="list-my-voucher">
                                                                @include('client.modals.modal-voucher',$vouchers)
                                                            </div>

                                                        </div>
                                                    </div>



                                                    {{-- diem --}}
                                                    <div class="points-section">
                                                        <div class="points-title">Điểm Poly</div>
                                                        <div class="points-form" action="" id='form-point'>
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
                                                                        <td id='points-membership'>
                                                                            {{ number_format(auth()->user()->membership->points, 0, ',', '.') }}
                                                                        </td>
                                                                        <td><input type="text" name="use_points"
                                                                                id='use_points' placeholder="Nhập điểm">
                                                                        </td>
                                                                        <td>= <b><span id='point_discount'>0</span></b> Vnđ
                                                                        </td>
                                                                        <td style="width: 20%">
                                                                            <button type="button" class='btn btn-danger'
                                                                                id="apply-point">Đổi
                                                                                điểm</button>
                                                                            <button type="button" id="cancel-point"
                                                                                class='btn'
                                                                                style="display:none">Hủy</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
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

                                        <div class="text-info-checkout">
                                            <div>
                                                {{-- <span class="ei--user"></span> --}}
                                                <span class="ic--baseline-payment"></span>
                                            </div>
                                            <div>
                                                <h4 class='text-transform'>Phương thức thanh toán</h4>
                                            </div>
                                        </div>
                                        <div class="payment-checkout">
                                            <div>
                                                <span class="bold">Chọn loại thanh toán:</span>
                                            </div>
                                            <hr>
                                            <div class="img-payment-checkout">
                                                {{-- <div>
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
                                                    <div>
                                                        <input type="radio" name="payment_name" value="vnpay">
                                                        <img src="{{ asset('theme/client/images/index_III/vi-momo.ico') }}"
                                                            alt="">
                                                        <label for="">hiihi test</label>
                                                    </div> --}}
                                                <div>
                                                    <input type="radio" name="payment_name" value="cash"
                                                        id="payment-checkout-1" checked="">
                                                    <label for="payment-checkout-1"><img
                                                            src="{{ asset('svg/cod.svg') }}"> Thanh toán tiền
                                                        mặt</label>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 10p --}}
                                        <div class="giu-cho-checkout">
                                            <div>
                                                <div>
                                                    <label for="checkbox" style="margin: 0; font-weight: 100">
                                                        <input type="checkbox" id="checkbox">
                                                        Tôi đồng ý với điều khoản sử dụng và mua vé.
                                                    </label>
                                                    <p><span class="text-danger">* </span>Xem chi tiết điều khoản sử dụng
                                                        và mua vé,
                                                        <span id="showModal" style="color: #ff7307; cursor: pointer;">
                                                            tại đây.</span>
                                                    </p>

                                                    @include('client.modals.modal-clause')

                                                </div>
                                            </div>
                                            {{-- <div style="display: flex">
                                                <p style="margin-right: 5px">Thời gian còn lại: </p>
                                                <p id="timer" class="bold">
                                                    {{ gmdate('i:s', $checkoutData['remainingSeconds']) }}</p>

                                            </div> --}}

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                            <div class="row" style="margin-bottom: 15px">
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
                                                <li>
                                                    <span><i class="fa fa-tags icons"></i> Thể loại</span>
                                                    <span class="bold">{{ $showtime->movie->category }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa fa-clock-o icons"></i> Thời lượng</span>
                                                    <span class="bold">{{ $showtime->movie->duration }} phút</span>
                                                </li>
                                                <hr style="border: 0; border-top: 2px dashed #7f7d7d; margin: 20px 0; ">
                                                <li>
                                                    <span><i class="fa-solid fa-landmark"></i> Rạp chiếu</span>
                                                    <span class="bold">{{ $showtime->room->cinema->name }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa-regular fa-calendar-days"></i> Ngày chiếu</span>
                                                    <span
                                                        class="bold">{{ \Carbon\Carbon::parse($showtime->date)->format('d/m/Y') }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa fa-clock-o icons"></i> Giờ chiếu</span>
                                                    <span
                                                        class="bold">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa-solid fa-desktop"></i> Phòng chiếu</span>
                                                    <span class="bold">{{ $showtime->room->name }}</span>
                                                </li>
                                                <li>
                                                    <span><i class="fa-solid fa-cubes"></i> Ghế ngồi</span>
                                                    <span id="selected-seats"
                                                        class="bold">{{ $checkoutData['selected_seats_name'] }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="total-price-choose-seat ">
                                            {{--  ticket --}}
                                            <input type="hidden" name="code"
                                                value="{{ strtoupper(\Str::random(10)) }}">
                                            <input type="hidden" name="user_id" id="userId"
                                                value="{{ Auth::user()->id }}">
                                            <input type="hidden" name="price_seat" id="price-seat"
                                                value="{{ $checkoutData['total_price'] }}">
                                            <input type="hidden" name="price_combo" id="price-combo">
                                            <input type="hidden" name="voucher_discount" id="voucher-discount">
                                            <input type="hidden" name="point_discount" id="point-discount">
                                            <input type="hidden" name="total_discount" id="total-discount">
                                            <input type="hidden" name="total_price" id="total-price-payment">


                                            {{-- ticketseat --}}
                                            <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                                            @foreach ($checkoutData['seat_ids'] as $seatId)
                                                <input type="hidden" name="seat_id[]" value="{{ $seatId }}">
                                            @endforeach

                                            <a href="{{ route('choose-seat', $showtime->slug) }}">Quay lại</a>

                                            <button type="submit" id="btnPayment">Tiếp tục</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="st_dtts_bs_wrapper float_left info-movie time-remaining">
                                        <p class="text-time">Thời gian còn lại</p>

                                        <p id="timer" class="bold">
                                            {{ gmdate('i:s', $checkoutData['remainingSeconds']) }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('theme/client/css/checkout.css') }}">
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function showAlertMessage(message, type = 'error') {
            Swal.fire({
                text: message,
                icon: type,
                timer: 5000,
                timerProgressBar: true,
                confirmButtonText: 'Đóng'
            });
        }
        $(document).ready(function() {
            function loadVouchers(userId) {
                $.ajax({
                    url: "get-my-voucher", // Đường dẫn đến route xử lý
                    type: "POST", // Phương thức POST
                    data: {
                        userId: userId, // Dữ liệu gửi lên server
                        _token: "{{ csrf_token() }}" // CSRF token để bảo mật
                    },
                    success: function(html) {
                        $('#data-my-voucher').empty();
                        $('#data-my-voucher').html(html);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
            const quantityInputs = document.querySelectorAll('.quantity-input');

            // Hàm hiển thị thông báo


            // Tính toán giá combo và tổng giá
            function calculateComboPrice() {
                let priceCombo = 0;

                // Tính tổng giá cho mỗi combo
                quantityInputs.forEach(input => {
                    const quantity = Math.max(0, parseInt(input.value, 10) || 0);
                    const pricePerCombo = parseInt(input.closest('tr').querySelector('.combo-price').dataset
                        .price, 10) || 0;
                    priceCombo += quantity * pricePerCombo; // Tính giá tổng
                });

                // Cập nhật giá combo vào input
                document.getElementById('price-combo').value = priceCombo;
                console.log("Total Combo Price: ", priceCombo); // Debug tổng giá combo

                calculateTotal(); // Cập nhật tổng khi giá combo thay đổi
            }

            // Cập nhật giá trị điểm và voucher sau khi tính toán tổng tiền
            function calculateTotal() {
                const priceSeat = {{ $checkoutData['total_price'] }};
                const priceCombo = parseInt(document.getElementById("price-combo").value) || 0;
                const pointDiscount = parseInt(document.getElementById("point-discount").value) || 0;
                const voucherDiscount = parseInt(document.getElementById("voucher-discount").value) || 0;

                const totalPrice = priceSeat + priceCombo; // Tính tổng giá
                const totalDiscount = pointDiscount + voucherDiscount; // Tính tổng giảm giá
                const totalPayment = Math.max(totalPrice - totalDiscount, 10000); // Tính tổng thanh toán

                // Cập nhật giá trị vào input
                document.getElementById("total-price").value = totalPrice;
                document.getElementById("total-discount").value = totalDiscount;
                document.getElementById("total-price-payment").value = totalPayment;

                // Cập nhật hiển thị tổng tiền
                document.querySelector('.total-price-payment').textContent = totalPayment.toLocaleString() + ' Vnđ';
                document.querySelector('.total-discount').textContent = totalDiscount.toLocaleString() + ' Vnđ';
                document.querySelector('.total-price-display').textContent = totalPrice.toLocaleString() + ' Vnđ';
            }

            // Xử lý sự kiện tăng/giảm số lượng
            function setupQuantityButtons() {
                document.querySelectorAll('.quantity-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const input = this.closest('.quantity-container').querySelector(
                            '.quantity-input');
                        let currentValue = parseInt(input.value);
                        const max = parseInt(input.getAttribute('max')) || Infinity;

                        if (this.classList.contains('increase') && currentValue < max) {
                            input.value = currentValue + 1;
                        } else if (this.classList.contains('decrease') && currentValue > 0) {
                            input.value = currentValue - 1;
                        }
                        calculateComboPrice(); // Cập nhật giá combo
                    });
                });
            }

            // Xử lý hủy sử dụng điểm
            function handleCancelPoint() {
                $.post({
                    url: '{{ route('cancel-point') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#apply-point').show();
                        $('#use_points').prop('readonly', false).val('');
                        $('#point-discount').val(0);
                        $('#point_discount').text('');
                        $('#cancel-point').hide();
                        calculateTotal();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.errors);
                    }
                });
            }

            // Sự kiện áp dụng điểm
            $('#apply-point').on('click', function() {
                const usePoints = parseInt($('#use_points').val().trim()) || 0;
                if (usePoints > 0) {
                    $.post({
                        url: '{{ route('apply-point') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            use_points: usePoints,
                        },
                        success: function(response) {
                            $('#point-discount').val(response.point_discount);
                            $('#point_discount').text(response.point_discount.toLocaleString(
                                'vi-VN'));
                            $('#apply-point').hide();
                            $('#use_points').prop('readonly', true);
                            $('#cancel-point').show();
                            calculateTotal();
                        },
                        error: function(xhr) {
                            let errorText = xhr.responseJSON.message || 'Lỗi không xác định';
                            if (xhr.status === 422) errorText = xhr.responseJSON.error
                                .use_points[0];
                            showAlertMessage(errorText, 'error');
                        }
                    });
                } else {
                    showAlertMessage(`Vui lòng nhập số điểm cần đổi hợp lệ.`, 'error');
                }
            });

            $('#cancel-point').on('click', handleCancelPoint);

            // Sự kiện submit membership
            $('#submit-membership').on('click', function() {
                const dataMembership = $('#data_membership').val().trim();

                if (dataMembership) {
                    $.post({
                        url: '{{ route('get-membership') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            data_membership: dataMembership
                        },
                        success: function(response) {
                            handleCancelPoint();
                            if (response.error) {
                                $('#error-membership').html(
                                    `<p class="text-danger">${response.error}</p>`);
                            } else {
                                const data = response.data;
                                $('#userId').val(data.user_id);
                                $('#info-membership').html(`
                                    <ul>
                                        <li>Họ tên: ${data.name}</li>
                                        <li>Email: ${data.email || 'Không có'}</li>
                                        <li>Số điện thoại: ${data.phone}</li>
                                        <li>Thẻ thành viên: ${data.membership_code}</li>
                                        <li>Điểm tích lũy: ${data.points} điểm</li>
                                    </ul>
                                `);
                                $('#points-membership').text(data.points);
                                $('#form-membership').hide();
                                $('#change-membership').show();
                                loadVouchers(data.customer);

                            }
                            $('#error-membership').empty();
                        },
                        error: function() {
                            $('#error-membership').html(
                                '<p class="text-danger">Không tìm thấy thông tin người dùng.</p>'
                            );
                        }
                    });
                } else {
                    $('#error-membership').html(
                        '<p class="text-danger">Vui lòng nhập mã thẻ thành viên hoặc email.</p>');
                }
            });

            // Sự kiện thay đổi membership
            $('#change-membership').on('click', function() {
                // Gọi API xóa session
                console.log('xóa session');

                $.ajax({
                    url: '{{ route('cancel-membership') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'DELETE', // Phương thức DELETE
                    success: function(response) {
                        console.log(response); // Xử lý phản hồi thành công
                        handleCancelPoint();
                        $('#form-membership').show();
                        $('#info-membership').empty();
                        $('#error-membership').empty();
                        $('#data_membership').val('');
                        $('#points-membership').text(
                            '{{ auth()->user()->membership->points }}');
                        $('#userId').val({{ auth()->user()->id }});
                        $('#change-membership').hide(); // Ẩn nút đổi thành viên
                        cancelVoucher();
                        // loadVouchers({{  auth()->user()->id }});
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON.message); // Xử lý lỗi
                        $('#error-membership').text(xhr.responseJSON
                            .message); // Hiển thị thông báo lỗi
                    }
                });
            });

            // Sử dụng voucher
            $('#apply-voucher-btn').on('click', function() {
                $.post({
                    url: '{{ route('applyVoucher') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        code: $('#voucher_code').val().trim()
                    },
                    success: function(response) {
                        $('#voucher-discount').val(response.discount);
                        calculateTotal();
                        $('#apply-voucher-btn, #voucher_code').prop('readonly', true);
                        $('#voucher-response').html(`
                            <div class="t-success">${response.success}</div>
                            <div class="show-text">
                                <span>Voucher: <b>${response.voucher_code}</b></span>
                                <span>Giảm giá: <b>${response.discount.toLocaleString()}</b> Vnđ</span>
                                <button type="button" id="cancel-voucher-btn" data-voucher-id="${response.id}">Hủy</button>
                            </div>
                        `);
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON.error || 'Voucher không hợp lệ';
                        showAlertMessage(errorText, 'error');
                    }
                });
            });

            // Sự kiện hủy voucher
            $(document).on('click', '#cancel-voucher-btn', function() {
                cancelVoucher();
            });

            function cancelVoucher() {
                $.ajax({
                    url: '{{ route('cancelVoucher') }}', // Đường dẫn đến API của bạn
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Xóa các giá trị trên giao diện khi hủy voucher thành công
                        $('#voucher_code').val('');
                        $('#voucher-response').empty();
                        $('#voucher-discount').val(0);
                        calculateTotal();
                        $('#apply-voucher-btn, #voucher_code').prop('readonly', false);
                    },
                    error: function(xhr, status, error) {
                        // Xử lý khi có lỗi
                        console.log('Error clearing voucher session: ', error);
                    }
                });
            }
            cancelVoucher();
            setupQuantityButtons(); // Thiết lập sự kiện cho nút tăng/giảm
            calculateComboPrice(); // Tính toán giá combo khi trang được tải
        });





        document.querySelectorAll('#payment-form input').forEach(input => {
            input.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Ngăn gửi form khi nhấn Enter
                }
            });
        });


        ///Ngăn chặn gửi form thông thường
        document.getElementById('payment-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Ngừng hành động mặc định (gửi form)
        });
        // Chỉ gửi form khi bấm vào nút có id="btnPayment"
        document.getElementById('btnPayment').addEventListener('click', function() {
            // Kiểm tra xem checkbox đã được chọn hay chưa
            if (!document.getElementById('checkbox').checked) {
                showAlertMessage('Bạn chưa đồng ý với điều kiện và điều khoản của chúng tôi.', 'warning')
                // Swal.fire({
                //     text: 'Bạn chưa đồng ý với điều kiện và điều khoản của chúng tôi.',
                //     icon:'warning',
                //     timer: 5000,
                // timerProgressBar: true,
                //     confirmButtonText: 'Đóng',
                // });
                //                 Swal.fire({
                //     title: "Đang đếm ngược",
                //     html: '<b id="time-line">10:00</b> phút còn lại...',  // Thay đổi id thành "time-line"
                //     icon: "info",
                //     timer: 600000,  // Thời gian tự động đóng sau 10 phút (600,000 ms)
                //     timerProgressBar: true,  // Hiển thị thanh tiến trình
                //     didOpen: () => {
                //         // Lấy phần tử với id "time-line" từ HTML
                //         const timeLineElement = document.getElementById('time-line');

                //         // Kiểm tra phần tử tồn tại hay không
                //         if (!timeLineElement) {
                //             console.error('Không thể tìm thấy phần tử với id "time-line"');
                //             return;
                //         }

                //         let countdown = 600;  // Khởi tạo thời gian đếm ngược (10 phút = 600 giây)

                //         const interval = setInterval(() => {
                //             countdown--;  // Giảm 1 giây mỗi lần
                //             let minutes = Math.floor(countdown / 60);  // Tính số phút còn lại
                //             let seconds = countdown % 60;  // Tính số giây còn lại
                //             seconds = seconds < 10 ? '0' + seconds : seconds;  // Đảm bảo giây luôn có 2 chữ số

                //             // Cập nhật phần tử time-line trong SweetAlert
                //             timeLineElement.textContent = `${minutes}:${seconds}`;

                //             if (countdown <= 0) {
                //                 clearInterval(interval);  //
                //             }
                //         }, 1000);  // Đếm ngược mỗi giây
                //     }
                // });
                // alert('Bạn chưa đồng ý với điều kiện và điều khoản của chúng tôi.');
            } else {
                // Gửi form khi checkbox đã được chọn
                document.getElementById('payment-form').submit();
            }
        });
    </script>


    <script>
        var routeUrl = "{{ route('applyVoucher') }}";
        var csrfToken = "{{ csrf_token() }}";

        let sessionTotalPrice = parseInt("{{ session('total_price', 0) }}");
        let discountAmount = 0; // Số tiền được giảm

        const totalDiscountElement = document.querySelector('.total-discount');



        // Lấy giá trị thời gian còn lại từ server-side PHP và gán vào biến JavaScript
        let timeLeft = {{ $checkoutData['remainingSeconds'] }}; // Thời gian còn lại tính bằng giây
        const timerElement = document.getElementById('timer');

        // Hàm đếm ngược thời gian
        const countdown = setInterval(() => {
            // Tính số phút và giây còn lại
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            // Hiển thị thời gian còn lại ở định dạng mm:ss
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

            // Giảm thời gian còn lại
            timeLeft--;

            // Khi thời gian kết thúc (hết 0 giây)
            if (timeLeft < 0) {
                clearInterval(countdown); // Dừng đếm ngược

                // Hiển thị thông báo và quay về trang chủ
                showAlertMessage('Hết thời gian! Bạn sẽ được chuyển về trang chủ.', 'warning')
                window.location.href = '/'; // Điều hướng về trang chủ ("/")
            }
        }, 1000); // Cập nhật mỗi giây
    </script>


    <script>
        // modal điều khoản mua và đặt vé
        const showModal = document.getElementById("showModal");
        // Hiển thị modal khi bấm vào "tại đây"
        showModal.onclick = function() {
            modal.style.display = "flex";
            document.body.classList.add('no-scroll');
        }


        // modal voucher của tôi
        // const showModalVoucher = document.getElementById("showModalVoucher");
        // // Hiển thị modal khi bấm vào "tại đây"
        // showModalVoucher.onclick = function() {
        //     modalVoucher.style.display = "flex";
        //     document.body.classList.add('no-scroll');
        // }
    </script>
@endsection
