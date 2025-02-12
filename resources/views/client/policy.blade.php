@extends('client.layouts.master')

@section('title')
    Chính sách
@endsection



@section('content')

    @php
        // Truy vấn dữ liệu từ bảng SiteSetting
        use App\Models\SiteSetting;
        $settings = SiteSetting::first();
    @endphp
    <div class="st_slider_index_sidebar_main_wrapper st_slider_index_sidebar_main_wrapper_md float_left">
        <div class="container container-policy">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="st_indx_slider_main_container float_left">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row box-policy">
                                    <h3>Chính sách thanh toán</h3>
                                    <div class="row text-policy">
                                        <div class="col-md-8">
                                            <div class="mt-0 note-policy">

                                                <p>{!! $settings->privacy_policy !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="img_policy">
                                                {{-- <img width="100%"
                                                    src="{{ asset('theme/client/images/ảnh_ban_sáng_lập_ra_Poly_Cinemas.jpg') }}"
                                                    alt=""> --}}
                                                @if ($settings->privacy_policy_image)
                                                    {{-- Kiểm tra xem có phải là ảnh mặc định hay không --}}
                                                    @if (Str::startsWith($settings->privacy_policy_image, 'theme/client/images/'))
                                                        <img width="100%"
                                                            src="{{ asset($settings->privacy_policy_image) }}"
                                                            alt="privacy_policy_image" class="policy-logo">
                                                    @else
                                                        <img width="100%"
                                                            src="{{ Storage::url($settings->privacy_policy_image) }}"
                                                            alt="privacy_policy_image" class="policy-logo">
                                                    @endif
                                                @else
                                                    {{-- Hiển thị ảnh mặc định nếu không có ảnh nào --}}
                                                    <img width="100%"
                                                        src="{{ asset('theme/client/images/header/P.svg') }}"
                                                        alt="privacy_policy_image" class="policy-logo">
                                                @endif
                                                <div align='center'>
                                                    <i>Ảnh khách hàng xem tại Rạp Poly Hà Đông</i>
                                                </div>

                                                <div style="margin-top:50px">
                                                    <img width="100%"
                                                    src="{{ asset('theme/client/images/z6051700724615_a08b293aa6185cf00143480033ccd489.jpg') }}"
                                                    alt="">
                                                    <div align='center'>
                                                        <i>Ảnh phòng 4DMax Rạp Poly Hà Đông</i>
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
    </div>


    {{-- @include('client.showtime') --}}
    <!-- st slider sidebar wrapper End -->
@endsection
