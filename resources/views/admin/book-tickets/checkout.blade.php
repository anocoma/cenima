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
                        <li class="breadcrumb-item">Chọn ghế</li>
                        <li class="breadcrumb-item active">Thanh toán</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>


    <div class="row">

        <div class="col-lg-8">
            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h3 class="card-title mb-0 flex-grow-1">Thông tin Thanh toán</h3>
                        </div><!-- end card header -->
                        <div class="card-body mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="example"
                                        class="table table-bordered mb-3 dt-responsive nowrap table-striped align-middle"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Họ tên</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Nhân viên bán hàng Đạt tuổi</td>
                                                <td>dattuoi@gmail.com</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    @for ($i = 0; $i < 2; $i++)
                                        <div class="info-seat-checkout m-2 d-flex justify-content-between my-2">
                                            <div>
                                                <b>GHẾ THƯỜNG</b> {{-- Ghế thường/ Ghế Vip/Ghế đôi   --}}
                                            </div>
                                            <div class="text-danger">
                                                <span>2 x 45.000</span> <span> = 90.000 Vnđ</span>
                                            </div>

                                        </div>
                                    @endfor



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
                                                    @for ($i = 0; $i < 3; $i++)
                                                        <tr>
                                                            <td><img src="" alt=""> Null</td>
                                                            <td>Combo Mixed - 320,000Vnđ</td>
                                                            <td>

                                                                Nước Uống: Nước có gaz (22oz) x (3) <br>
                                                                Đồ Ăn: Bắp (69oz) x (4) <br>
                                                                Khác: Ly Vảy cá kèm nước x (4) <br>
                                                            </td>
                                                            <td>
                                                                {{-- <div class="quantity-container">
                                                                    <button class="quantity-btn decrease">-</button>
                                                                    <input type="text" class="quantity-input"
                                                                        name="combo" value="0" min="1"
                                                                        max="10" readonly>
                                                                    <button class="quantity-btn increase">+</button>
                                                                </div> --}}
                                                                <div class="input-step step-primary">
                                                                    <button type="button"
                                                                        class="quantity-btn decrease">-</button>
                                                                    <input type="number"
                                                                        class="product-quantity quantity-input"
                                                                        value="0" min="0" max="100"
                                                                        readonly="">
                                                                    <button type="button"
                                                                        class="quantity-btn increase">+</button>
                                                                </div>

                                                            </td>
                                                    @endfor

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Voucher giảm giá --}}
                                    <div class="box-voucher mt-3">
                                        <h4 class="p-3 mb-2  bg-light text-dark">Giảm giá</h4>
                                        <div class="info-voucher-checkout">

                                            <div class="voucher-section mt-4">
                                                <div class="voucher-title mx-2">
                                                    <h5>Poly Voucher</h5>
                                                </div>
                                                <form class="voucher-form" id="voucher-form" method="POST">
                                                    @csrf

                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <input type="text" name="code" class="form-control"
                                                                id="voucher_code" required placeholder="Nhập mã voucher"
                                                                @guest disabled @endguest>

                                                        </div>

                                                        <div class="col-md-2">
                                                            <button class="btn btn-primary" type="submit"
                                                                id="apply-voucher-btn" @guest disabled @endguest>Xác nhận
                                                            </button>
                                                        </div>


                                                    </div>

                                                </form>
                                                <div id="voucher-response"></div>
                                            </div>


                                            {{-- diem --}}
                                            <div class="points-section mt-4">
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
                                                                        name="point_use" placeholder="Nhập điểm"></td>
                                                                <td>= 0 Vnđ</td>
                                                                <td align="right">
                                                                    <button type="submit" class="btn btn-primary">Đổi
                                                                        điểm</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- tong tien --}}
                                    <div class="total-price-checkout">
                                        <div class="d-flex justify-content-end">
                                            <p>Tổng tiền:</p>
                                            <p class="text-danger total-price-checkout px-2">
                                                105.000 Vnđ
                                            </p>
                                            <input type="text" name="total-price" id="total-price" value="" hidden
                                                readonly>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <p>Số tiền được giảm:</p>
                                            <p class="text-danger total-discount  px-2">0 Vnđ</p>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <p>Số tiền cần thanh toán:</p>
                                            <p class="text-danger total-price-payment  px-2">
                                                105.000 Vnđ</p>
                                        </div>
                                    </div>


                                    {{-- phuong thuc thanh toan --}}
                                    <div class="box-payment-checkout">
                                        <div class="text-info-checkout">
                                            <div>

                                                <span class="ic--baseline-payment"></span>
                                            </div>
                                            <div>
                                                <h4 class="p-3 mb-2  bg-light text-dark">Phương thức thanh toán</h4>
                                            </div>
                                        </div>
                                        <div class="payment-checkout">
                                            <div class="mx-3">
                                                <p>Chọn thẻ thanh toán</p>
                                            </div>
                                            <hr>
                                            <div class="img-payment-checkout">
                                                <form action="" class="d-flex justify-content-between ">
                                                    <div>
                                                        <input type="radio" name="payment-checkout"
                                                            id="payment-checkout-1">
                                                        <img src="{{ asset('theme/client/images/index_III/the-noi-dia.png') }}"
                                                            alt="">
                                                        <label for="payment-checkout-1">Thẻ nội địa</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" name="payment-checkout"
                                                            id="payment-checkout-2">
                                                        <img src="{{ asset('theme/client/images/index_III/the-quoc-te.png') }}"
                                                            alt="">
                                                        <label for="payment-checkout-2">Thẻ quốc tế</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" name="payment-checkout"
                                                            id="payment-checkout-3">
                                                        <img src="{{ asset('theme/client/images/index_III/vi-shopee-pay.png') }}"
                                                            alt="">
                                                        <label for="payment-checkout-3">Ví Shoppe Pay</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" name="payment-checkout"
                                                            id="payment-checkout-4">
                                                        <img src="{{ asset('theme/client/images/index_III/vi-momo.ico') }}"
                                                            alt="">
                                                        <label for="payment-checkout-4">Ví MoMo</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" name="payment-checkout"
                                                            id="payment-checkout-5">
                                                        <img src="{{ asset('theme/client/images/index_III/vi-zalo-pay.png') }}"
                                                            alt="">
                                                        <label for="payment-checkout-5">Ví ZaloPay</label>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- realtime 10p --}}
                                    <div class="giu-cho-checkout bg-light mt-5">
                                        <div class="row p-3 pb-0">
                                            <div class="col-md-8">
                                                <p>Vui lòng kiểm tra thông tin đầy đủ trước khi qua bước tiếp theo. <br>
                                                    *Vé mua rồi không hoàn trả lại dưới mọi hình thức.</p>
                                            </div>
                                            <div class="col-md-4 d-flex">
                                                <p>Thời gian còn lại:</p>
                                                <h5 id="timer" class="text-danger px-2">9:56</h5>
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
                        <img src="https://files.betacorp.vn/media%2fimages%2f2024%2f08%2f16%2f400x633%2D5%2D161700%2D160824%2D33.jpg"
                            width="100%">
                    </div>
                    <div class='name-movie mx-3 '>
                        <h3 class='text-primary my-2'>Làm giàu với ma</h3>
                        <div class="fs-5 mt-2">
                            <span>2D Phụ Đề</span>
                        </div>
                    </div>
                </div>
                <div class='card-header border-bottom-dashed border-2'>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td>Thể loại:</td>
                                    <td class="text-end fw-bold">Kinh dị</td>
                                </tr>
                                <tr>
                                    <td>Thời lượng: </td>
                                    <td class="text-end fw-bold">138 phút</td>
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
                                    <td>Rạp chiếu: </td>
                                    <td class="text-end fw-bold">Poly Mỹ Đình</td>
                                </tr>
                                <tr>
                                    <td>Ngày chiếu</td>
                                    <td class="text-end fw-bold">14/10/2024</td>
                                </tr>
                                <tr>
                                    <td>Giờ chiếu:</td>
                                    <td class="text-end fw-bold" id="cart-shipping">20:00 ~ 21:38</td>
                                </tr>
                                <tr>
                                    <td>Phòng Chiếu: </td>
                                    <td class="text-end fw-bold" id="cart-tax">P201</td>
                                </tr>
                                <tr>
                                    <td>Ghế ngồi: </td>
                                    <td class="text-end fw-bold" id="cart-tax">L8,L9 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="text-end my-3">
                <button class="btn btn-success btn-label right ms-auto"><i
                        class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i> Tiếp tục</button>
            </div>

        </div>

    </div>
    <style>

    </style>
@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/mainstyle.css') }}">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseBtns = document.querySelectorAll('.quantity-btn.decrease');
            const increaseBtns = document.querySelectorAll('.quantity-btn.increase');
            const quantityInputs = document.querySelectorAll('.quantity-input');

            decreaseBtns.forEach((decreaseBtn, index) => {
                decreaseBtn.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInputs[index].value);
                    if (currentValue > 0) {
                        quantityInputs[index].value = currentValue - 1;
                    }
                });
            });

            increaseBtns.forEach((increaseBtn, index) => {
                increaseBtn.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInputs[index].value);
                    if (currentValue < 10) {
                        quantityInputs[index].value = currentValue + 1;
                    }
                });
            });
        });
    </script>
@endsection
