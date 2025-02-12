{{-- @extends('client.layouts.master')

@section('title')
    Chính sách
@endsection

@section('content')
    <div class="st_slider_index_sidebar_main_wrapper st_slider_index_sidebar_main_wrapper_md float_left">
        <div class="container container-policy">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="st_indx_slider_main_container float_left">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row box-policy">
                                    <h3>Chính sách thanh toán, đổi trả - hoàn vé</h3>
                                    <div class="row text-policy">
                                        <div class="col-md-8">
                                            <div>
                                                <b>Chào mừng Quý khách hàng đến với Hệ thống Bán Vé Online của chuỗi Rạp
                                                    Chiếu Phim
                                                    POLY CINEMAS!</b>
                                            </div>
                                            <div>
                                                <p>Xin cảm ơn và chúc Quý khách hàng có những giây phút xem phim tuyệt vời
                                                    tại POLY CINEMAS!</p>
                                            </div>
                                            <div class="mt-5 note-policy">
                                                <b>Sau đây là một số lưu ý trước khi thanh toán trực tuyến:</b> <br>
                                                <ul class="main-menu-policy">
                                                    <li>1. Thẻ phải được kích hoạt chức năng thanh toán trực tuyến, và có đủ
                                                        hạn
                                                        mức/ số dư để thanh toán. Quý khách cần nhập chính xác thông tin thẻ
                                                        (tên
                                                        chủ thẻ, số thẻ, ngày hết hạn, số CVC, OTP,...).</li>
                                                    <li>2. Vé và hàng hóa đã thanh toán thành công không thể hủy/đổi
                                                        trả/hoàn tiền
                                                        vì bất kỳ lý do gì. POLY CINEMAS chỉ thực hiện hoàn tiền trong
                                                        trường hợp
                                                        thẻ của Quý khách đã bị trừ tiền nhưng hệ thống của Beta không ghi
                                                        nhận việc
                                                        đặt vé/đơn hàng của Quý khách, và Quý khách không nhận được xác nhận
                                                        đặt
                                                        vé/đơn hàng thành công.</li>
                                                    <li>3. Trong vòng 30 phút kể từ khi thanh toán thành công, POLY CINEMAS
                                                        sẽ gửi
                                                        Quý khách mã xác nhận thông tin vé/đơn hàng qua email của Quý khách.
                                                        Nếu Quý
                                                        khách cần hỗ trợ hay thắc mắc, khiếu nại về xác nhận mã vé/đơn hàng
                                                        thì vui
                                                        lòng phản hồi về Fanpage Facebook POLY CINEMAS trong vòng 60 phút kể
                                                        từ khi
                                                        thanh toán vé thành công. Sau khoảng thời gian trên, POLY CINEMAS sẽ
                                                        không
                                                        chấp nhận giải quyết bất kỳ khiếu nại nào.</li>
                                                    <li>4. POLY CINEMAS không chịu trách nhiệm trong trường hợp thông tin
                                                        địa chỉ
                                                        email, số điện thoại Quý khách nhập không chính xác dẫn đến không
                                                        nhận được
                                                        thư xác nhận. Vui lòng kiểm tra kỹ các thông tin này trước khi thực
                                                        hiện
                                                        thanh toán. POLY CINEMAS không hỗ trợ xử lý và không chịu trách
                                                        nhiệm trong
                                                        trường hợp đã gửi thư xác nhận mã vé/đơn hàng đến địa chỉ email của
                                                        Quý
                                                        khách nhưng vì một lý do nào đó mà Quý khách không thể đến xem phim.
                                                    </li>
                                                    <li>5. Vui lòng kiểm tra thông tin xác nhận vé cẩn thận và ghi nhớ mã
                                                        đặt vé/đơn
                                                        hàng. Khi đến nhận vé/hàng hóa tại Quầy vé của POLY CINEMAS, Quý
                                                        khách cũng
                                                        cần mang theo giấy tờ tùy thân như Căn cước công dân/Chứng minh nhân
                                                        dân,
                                                        Thẻ học sinh, Thẻ sinh viên hoặc hộ chiếu.</li>
                                                    <li>7. Vì một số sự cố kỹ thuật bất khả kháng, suất chiếu có thể bị huỷ
                                                        để đảm
                                                        bảo an toàn tối đa cho khách hàng, POLY CINEMAS sẽ thực hiện hoàn
                                                        trả số
                                                        tiền giao dịch về tài khoản mà Quý khách đã thực hiện mua vé. Beta
                                                        Cinemas
                                                        sẽ liên hệ với Quý khách qua các thông tin liên hệ trong mục Thông
                                                        tin thành
                                                        viên để thông báo và xác nhận.</li>
                                                    <li></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="img_policy">
                                                <img width="100%"
                                                    src="{{ asset('theme/client/images/ảnh_ban_sáng_lập_ra_Poly_Cinemas.jpg') }}"
                                                    alt="">
                                                <div align='center'>
                                                    <i>Ảnh ban sáng lập Poly Cinemas</i>
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


    @include('client.showtime')
    <!-- st slider sidebar wrapper End -->
@endsection --}}

@extends('client.layouts.master')

@section('title')
    Chính sách bảo mật
@endsection

@section('content')
@php
    // Truy vấn dữ liệu từ bảng SiteSetting
    use App\Models\SiteSetting;
    $settings = SiteSetting::first();
@endphp

<div class="policy-wrapper">
    <div class="policy-container">
        <div class="policy-main-content">
            <!-- Ảnh đại diện và nội dung chính sách -->
                @if($settings->privacy_policy_image)
                {{-- Kiểm tra xem có phải là ảnh mặc định hay không --}}
                @if(Str::startsWith($settings->privacy_policy_image, 'theme/client/images/'))
                    <img src="{{ asset($settings->privacy_policy_image) }}" alt="privacy_policy_image" class="policy-logo">
                @else
                    <img src="{{ Storage::url($settings->privacy_policy_image) }}" alt="privacy_policy_image" class="policy-logo">
                @endif
                @else
                    {{-- Hiển thị ảnh mặc định nếu không có ảnh nào --}}
                    <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="privacy_policy_image" class="policy-logo">
                @endif
            <p>{!! $settings->privacy_policy !!}</p>


            <!-- Thông tin liên hệ hỗ trợ -->
            <h4>Liên hệ hỗ trợ</h4>
            <p><strong>Email hỗ trợ:</strong> {{ $settings->email}}</p>
            <p><strong>Hotline:</strong> {{ $settings->phone}}</p>
            <p><strong>Giờ làm việc:</strong> {{ $settings->working_hours}}</p>
        </div>
    </div>
</div>

<style>
    .policy-wrapper { margin-top: 120px; margin-bottom: 20px; }
    .policy-container { width: 80%; margin: 0 auto; background-color: #fff; border: 1px solid #ddd; padding: 20px; }
    .policy-main-content img.policy-logo { width: 100%; height: 400px; display: block; margin-bottom: 20px; }
    .policy-main-content h3 { margin-bottom: 15px; }
    .policy-main-content p { margin-bottom: 15px; line-height: 1.6; }
</style>
@endsection
