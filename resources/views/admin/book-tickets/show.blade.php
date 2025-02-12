@extends('admin.layouts.master')

@section('title')
    Đặt vé tại quầy
@endsection

@section('content')
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Đặt vé tại quầy</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Lịch chiếu</a></li>
                        <li class="breadcrumb-item active">Chọn ghế</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <form id="proceedPayment" action="{{ route('payment', $showtime) }}" method="POST">
        @csrf
        <div class="row">

            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-12" id="chooseSeat">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Sơ đồ ghế</h4>
                            </div><!-- end card header -->
                            <div class="card-body mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="list-seats my-3">
                                            <div class="list-seat">
                                                <span class='seat-vip-svg '></span>
                                                <span class="status-seat">Ghế trống</span>
                                            </div>
                                            <div class="list-seat"> <span class='seat-vip-svg seat-selected'></span>
                                                <span class="status-seat">Ghế đang chọn</span>
                                            </div>
                                            <div class="list-seat"><span class='seat-vip-svg seat-hold'></span>
                                                <span class="status-seat">Ghế đang được giữ</span>
                                            </div>
                                            <div class="list-seat"><span class='seat-vip-svg seat-sold'></span>
                                                <span class="status-seat">Ghế đã bán</span>
                                            </div>
                                        </div>
                                        <div class="srceen mb-4">
                                            Màn Hình Chiếu
                                        </div>
                                    </div>
                                </div>
                                <table class="table-chart-chair table-none align-middle mx-auto text-center">
                                    <tbody>
                                        @for ($row = 0; $row < $matrix['max_row']; $row++)
                                            <tr>
                                                <td class="box-item-pro">{{ chr(65 + $row) }}</td>
                                                @for ($col = 0; $col < $matrix['max_col']; $col++)
                                                    @php
                                                        // Kiểm tra xem ô hiện tại có trong seatMap không
                                                        $seat =
                                                            isset($seatMap[chr(65 + $row)]) &&
                                                            isset($seatMap[chr(65 + $row)][$col + 1])
                                                                ? $seatMap[chr(65 + $row)][$col + 1]
                                                                : null;
                                                        if (!function_exists('getSeatClassStatus')) {
                                                            function getSeatClassStatus($status, $user_id)
                                                            {
                                                                // Lấy user_id của người dùng đang xác thực
                                                                $authUserId = auth()->id();

                                                                switch ($status) {
                                                                    case 'hold':
                                                                        return $user_id == $authUserId
                                                                            ? 'seat-selected'
                                                                            : 'seat-hold';
                                                                    case 'paying':
                                                                        return 'seat-hold';
                                                                    case 'sold':
                                                                        return 'seat-sold';
                                                                    default:
                                                                        return '';
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($seat && $seat->type_seat_id == 3)
                                                        <!-- Nếu là ghế đôi -->
                                                        <td class="box-item-pro" colspan="2"
                                                            data-seat-status="{{ $seat->pivot->status ?? '' }}"
                                                            data-seat-name="{{ $seat->name ?? '' }}"
                                                            data-seat-price="{{ $seat->pivot->price ?? '' }}"
                                                            data-type-seat-id="{{ $seat->type_seat_id ?? '' }}"
                                                            data-seat-id="{{ $seat->id ?? '' }}"
                                                            data-user-id="{{ $seat->pivot->user_id ?? '' }}">
                                                            <div class="seat-item">
                                                                <!-- 3 cho ghế đôi -->
                                                                <span id="box-{{ $seat->id }}" class="seat-double-svg-pro seat {{ getSeatClassStatus($seat->pivot->status ?? '', $seat->pivot->user_id ?? '') }}"></span>
                                                                <span
                                                                    class="seat-label-double-pro">{{ chr(65 + $row) . ($col + 1) }}
                                                                    {{ chr(65 + $row) . ($col + 2) }}</span>
                                                            </div>
                                                        </td>
                                                        @php $col++; @endphp
                                                    @else
                                                        <td class="box-item-pro"
                                                            data-seat-status="{{ $seat->pivot->status ?? '' }}"
                                                            data-seat-name="{{ $seat->name ?? '' }}"
                                                            data-seat-price="{{ $seat->pivot->price ?? '' }}"
                                                            data-type-seat-id="{{ $seat->type_seat_id ?? '' }}"
                                                            data-seat-id="{{ $seat->id ?? '' }}"
                                                            data-user-id="{{ $seat->pivot->user_id ?? '' }}">
                                                            <div class="seat-item">
                                                                @switch($seat->type_seat_id ?? "")
                                                                    @case(1)
                                                                        <span id="box-{{ $seat->id }}"
                                                                            class="seat-regular-svg-pro seat {{ getSeatClassStatus($seat->pivot->status ?? '', $seat->pivot->user_id ?? '') }}"></span>
                                                                        <span
                                                                            class="seat-label-pro">{{ chr(65 + $row) . $col + 1 }}</span>
                                                                    @break

                                                                    @case(2)
                                                                        <span id="box-{{ $seat->id }}"
                                                                            class="seat-vip-svg-pro  seat {{ getSeatClassStatus($seat->pivot->status ?? '', $seat->pivot->user_id ?? '') }}"></span>
                                                                        <span
                                                                            class="seat-label-pro">{{ chr(65 + $row) . $col + 1 }}</span>
                                                                    @break
                                                                @endswitch

                                                            </div>
                                                        </td>
                                                    @endif
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                                <div class="legend">

                                    <div><span class="seat-regular-svg"></span> Ghế Thường
                                    </div>
                                    <div><span class="seat-vip-svg"></span> Ghế Vip</div>
                                    <div><span class="seat-double-svg"></span> Ghế Đôi</div>
                                    <div>
                                        <p>Tổng tiền:</p>
                                        <p id="total-price" class="fw-bold">0 VNĐ</p>
                                        <input type="hidden" name="totalPriceSeat" id="inputTotalPriceSeat">
                                    </div>
                                    <div>
                                        <p>Thời gian còn lại:</p>
                                        <p class="fw-bold countdown-timer" data-time="{{ $remainingSeconds }}"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="selected-seats-session"
                            value="{{ json_encode(session('book_tickets.' . $showtime->id, [])) }}">
                    </div>
                    <div class="col-lg-12 " id="checkOut">
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h3 class="card-title mb-0 flex-grow-1">Thông tin Thanh toán</h3>
                                    </div><!-- end card header -->
                                    <div class="card-body mb-3">
                                        <div class="row">
                                            <div class="col-md-12">

                                                @php
                                                    $orderer = Auth::user();
                                                @endphp

                                                <table id="example"
                                                    class="table table-bordered mb-3 dt-responsive nowrap  align-middle"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th><span>Người đặt</span></th>
                                                            <th><span>Email</span></th>
                                                            <th><span>Số điện thoại</span></th>
                                                        </tr>
                                                    </thead>
                                                    {{-- <tbody>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                </tbody> --}}
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $orderer->name }}</td>
                                                            <td>{{ $orderer->email }}</td>
                                                            <td>{{ $orderer->phone }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                {{-- <div class="my-4">
                                                    <label class="form-label">Thẻ thành viên <span class="text-muted">(nếu
                                                            có)</span>:</label>
                                                    <input type="text" name="card" class="form-control"
                                                        placeholder="3047-1414-2012-3534">
                                                </div> --}}

                                                <div id="seatDetails" class="my-3">
                                                    <div class="info-seat-checkout m-2 d-flex justify-content-between my-2">
                                                        <div>
                                                            <b>GHẾ THƯỜNG</b> {{-- Ghế thường/ Ghế Vip/Ghế đôi   --}}
                                                        </div>
                                                        <div class="text-danger">
                                                            <span>2 x 45.000</span> <span> = 90.000 Vnđ</span>
                                                        </div>

                                                    </div>
                                                </div>





                                                <div class="combo-checkout mt-3">
                                                    <h4 class="p-3 mb-2  bg-light text-dark">Combo Ưu đãi</h4>

                                                    <div class="">
                                                        <table id="example"
                                                            class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                                            style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Ảnh</th>
                                                                    <th>Tên combo</th>
                                                                    <th>Mô tả</th>
                                                                    <th>Số lượng</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($combos as $combo)
                                                                    <tr>
                                                                        @php
                                                                            $url = $combo->img_thumbnail;

                                                                            if (!\Str::contains($url, 'http')) {
                                                                                $url = Storage::url($url);
                                                                            }
                                                                        @endphp

                                                                        <td>
                                                                            @if (!empty($combo->img_thumbnail))
                                                                                <img src="{{ $url }}"
                                                                                    alt=""
                                                                                    class="rounded-circle avatar-md">
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $combo->name }} - {{ $combo->price_sale }}
                                                                            VNĐ
                                                                        </td>
                                                                        <td>
                                                                            @foreach ($combo->food as $item)
                                                                                <ul class="nav nav-sm flex-column">
                                                                                    <li class="nav-item mb-2">
                                                                                        <span
                                                                                            class="fw-semibold">{{ $item->type }}:
                                                                                        </span>
                                                                                        {{ $item->name }} x
                                                                                        ({{ $item->pivot->quantity }})
                                                                                    </li>
                                                                                </ul>
                                                                            @endforeach
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-step step-primary">
                                                                                <button type="button"
                                                                                    class="quantity-btn decrease">-</button>
                                                                                <input type="number" readonly
                                                                                    name="quantity_combo[{{ $combo->id }}]"
                                                                                    class="product-quantity quantity-input"
                                                                                    data-combo-id="{{ $combo->id }}"
                                                                                    data-price-sale="{{ $combo->price_sale }}"
                                                                                    value="0" min="0"
                                                                                    max="10">
                                                                                <button type="button"
                                                                                    class="quantity-btn increase">+</button>
                                                                            </div>

                                                                        </td>
                                                                @endforeach
                                                                <input type="hidden" id="inputTotalPriceCombo" />

                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                {{-- Voucher giảm giá --}}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="box-voucher my-5">
                                                            <h4 class="p-3 mb-2  bg-light text-dark">Giảm giá</h4>
                                                            <div class="info-voucher-checkout">

                                                                <div class="voucher-section mt-4">
                                                                    <div class="voucher-title">
                                                                        <h5>Poly Voucher</h5>
                                                                    </div>
                                                                    <form class="voucher-form" id="voucher-form"
                                                                        method="POST">
                                                                        @csrf

                                                                        <div class="row">
                                                                            <div class="col-md-10">
                                                                                <input type="text" name="code"
                                                                                    class="form-control" id="voucher_code"
                                                                                    required placeholder="Nhập mã voucher">

                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <button class="btn btn-primary w-100"
                                                                                    type="submit"
                                                                                    id="apply-voucher-btn">Xác nhận
                                                                                </button>
                                                                            </div>


                                                                        </div>

                                                                    </form>
                                                                    <div id="voucher-response"></div>
                                                                </div>



                                                                {{-- <div class="points-section mt-4">
                                                                    <div class="points-title mx-2">
                                                                        <h5>Điểm Poly</h5>
                                                                    </div>
                                                                    <form class="points-form" action="">
                                                                        <table
                                                                            class="points-table table table-bordered dt-responsive nowrap table-striped align-middle"
                                                                            style="width:100%">
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
                                                                                    <td><input type="text" class="form-control"
                                                                                            name="point_use" placeholder="Nhập điểm">
                                                                                    </td>
                                                                                    <td>= 0 Vnđ</td>
                                                                                    <td align="right">
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary">Đổi
                                                                                            điểm</button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </form>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- tong tien --}}
                                                <div class="total-price-checkout" id="getPriceOrder">
                                                    <div class="d-flex justify-content-end">
                                                        <p>Tổng tiền:</p>
                                                        <p class="text-danger total-price-checkout px-2" id="totalPrice">
                                                            105.000 VNĐ
                                                        </p>
                                                        <input type="hidden" id='inputTotalPriceOrder'>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <p>Số tiền được giảm:</p>
                                                        <p class="text-danger total-discount  px-2"
                                                            id="totalPriceReduced">0
                                                            VNĐ</p>
                                                        <input type="hidden" id='inputTotalPriceDiscount'>
                                                        <input type="hidden" id='inputTotalPricePoint'>
                                                        <input type="hidden" id='inputTotalPriceReduced'>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <p>Số tiền cần thanh toán:</p>
                                                        <p class="text-danger total-price-payment  px-2"
                                                            id="totalPricePaid">
                                                            105.000 VNĐ
                                                        </p>
                                                        <input type="hidden" id='inputTotalPricePaid'>
                                                    </div>
                                                </div>


                                                {{-- phuong thuc thanh toan --}}
                                                <div class="box-payment-checkout">
                                                    <div class="text-info-checkout">
                                                        <div>

                                                            <span class="ic--baseline-payment"></span>
                                                        </div>
                                                        <div>
                                                            <h4 class="p-3 mb-2  bg-light text-dark">Phương thức thanh toán
                                                            </h4>
                                                        </div>
                                                    </div>

                                                    <div class="payment-checkout mt-3">
                                                        <div class="img-payment-checkout d-flex ">

                                                            <div class=' mx-3'>
                                                                <input type="radio" name="payment_method"
                                                                    value='cash' id="payment-checkout-1" checked>

                                                                <label for="payment-checkout-1"><img
                                                                        src="{{ asset('svg/cod.svg') }}"> Thanh toán tiền
                                                                    mặt</label>
                                                            </div>
                                                            <div class=' mx-3'>
                                                                <input type="radio" name="payment_method"
                                                                    id="payment-checkout-5" value='vnpay'>
                                                                <label for="payment-checkout-5"> <img
                                                                        src="{{ asset('images/vn-pay.jpg') }}">
                                                                    VNPAY</label>
                                                            </div>
                                                            <div class=' mx-3'>
                                                                <input type="radio" name="payment_method"
                                                                    id="payment-checkout-4" value='momo'>

                                                                <label for="payment-checkout-4">
                                                                    <img
                                                                        src="{{ asset('theme/client/images/index_III/vi-momo.ico') }}">
                                                                    Ví MoMo</label>
                                                            </div>


                                                        </div>
                                                    </div>


                                                </div>
                                                {{-- realtime 10p --}}
                                                <div class="giu-cho-checkout bg-light mt-5">
                                                    <div class="row p-3 pb-0">
                                                        <div class="col-md-8">
                                                            <p>Vui lòng kiểm tra thông tin đầy đủ trước khi qua bước tiếp
                                                                theo.
                                                                <br>
                                                                *Vé mua rồi không hoàn trả lại dưới mọi hình thức.
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4 d-flex">
                                                            <p>Thời gian còn lại:</p>
                                                            <h5 class="text-danger px-2 countdown-timer" data-time="600">
                                                                9:56</h5>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 mb-4">

                <div class="card">
                    <div class="card-header border bg-opacity-75 bg-info-subtle ">
                        <h5 class="card-title mb-0 text-center">Thông tin phim</h5>
                    </div>
                    <div class="movie-info mt-3 d-flex">
                        <div class='img-movie'>
                            @php
                                $url = $showtime->movie->img_thumbnail;

                                if (!\Str::contains($url, 'http')) {
                                    $url = Storage::url($url);
                                }

                            @endphp
                            @if (!empty($showtime->movie->img_thumbnail))
                                <img src="{{ $url }}" width="100%">
                            @else
                                No image !
                            @endif

                        </div>
                        <div class='name-movie mx-3 '>
                            <h3 class='text-primary my-2'>{{ $showtime->movie->name }}</h3>
                            <div class="fs-5 mt-2">
                                <span>{{ $showtime->format }}</span>
                            </div>
                        </div>
                    </div>
                    <div class='card-header border-bottom-dashed border-2'>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="my-2">Thể loại:</td>
                                        <td class="text-end fw-bold my-2">{{ $showtime->movie->category }}</td>
                                    </tr>
                                    <tr>
                                        <td class="my-2">Thời lượng: </td>
                                        <td class="text-end fw-bold my-2">{{ $showtime->movie->duration }} phút</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="my-2">Rạp chiếu: </td>
                                        <td class="text-end fw-bold my-2">{{ $showtime->cinema->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="my-2">Ngày chiếu</td>
                                        <td class="text-end fw-bold my-2">
                                            {{ \Carbon\Carbon::parse($showtime->date)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="my-2">Giờ chiếu:</td>
                                        @php
                                            $startTime = \Carbon\Carbon::parse($showtime->start_time);
                                            $endTime = $startTime->copy()->addMinutes($showtime->movie->duration);
                                        @endphp
                                        <td class="text-end fw-bold my-2">
                                            {{ $startTime->format('H:i') }} ~ {{ $endTime->format('H:i') }}
                                    </tr>
                                    <tr>
                                        <td class="my-2">Phòng Chiếu: </td>
                                        <td class="text-end fw-bold my-2" id="cart-tax">P201</td>
                                    </tr>
                                    <tr>
                                        <td class="my-2">Ghế ngồi: </td>
                                        <td class="text-end fw-bold my-2" id="selected-seats"> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="d-flex align-items-start gap-3 my-3" id="buttonAction">
                    <button type="button" id="nextChooseSeat"
                        class="btn btn-success btn-label right ms-auto nexttab nexttab w-50"><i
                            class="ri-arrow-right-line label-icon align-middle fs-16 ms-2 w-"></i>Tiếp Tục</button>
                </div>



            </div>

        </div>
    </form>


@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/mainstyle.css') }}">
@endsection

@section('script-libs')
    {{-- @vite('resources/js/choose-seat.js') --}}
    <script>
        const showtimeId = {{ $showtime->id }}
    </script>

    @vite('resources/js/book-ticket.js')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const prices = {
            totalPriceOrder: 0,
            totalPriceSeat: 0,
            totalPriceCombo: 0,
            totalPriceDiscount: 0,
            totalPricePoint: 0,
            totalPriceReduced: 0,
            totalPricePaid: 0
        };
    </script>












    <script>
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`; // Thêm dấu `` để định dạng chuỗi
        }

        // Hàm đếm ngược cho từng phần tử
        function startCountdown(element) {
            let timeRemaining = parseInt(element.getAttribute('data-time')); // Lấy thời gian từ thuộc tính data-time

            const interval = setInterval(() => {
                timeRemaining--;
                element.textContent = formatTime(timeRemaining);

                if (timeRemaining <= 0) {
                    clearInterval(interval);
                    element.textContent = "Thời gian đã hết!"; // Cập nhật thông báo khi thời gian kết thúc
                    // window.location.href = 'https://example.com'; // Điều hướng sang trang khác (nếu cần)
                }
            }, 1000);
        }

        // Khởi động countdown cho tất cả phần tử có class 'countdown-timer'
        document.querySelectorAll('.countdown-timer').forEach(startCountdown);
    </script>
@endsection
