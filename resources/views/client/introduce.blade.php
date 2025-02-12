@extends('client.layouts.master')

@section('title')
    Giới thiệu
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
                                    <h3>Giới thiệu về {{ $settings->site_name }} </h3>
                                    <div class="row text-policy">
                                        <div class="col-md-7">
                                            <div class="mt-0 note-policy">

                                                <p>{!! $settings->introduction !!}</p>
                                                
                                                <h4 style="margin:10px 0px"><b>Thông tin liên hệ</b></h4> 
                                                <p><strong>Email:</strong> {{ $settings->email }}</p>
                                                <p><strong>Số điện thoại:</strong> {{ $settings->phone }}</p>
                                                <p><strong>Giờ làm việc:</strong> {{ $settings->working_hours }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="img_policy">
                                                {{-- <img width="100%"
                                                    src="{{ asset('theme/client/images/ảnh_ban_sáng_lập_ra_Poly_Cinemas.jpg') }}"
                                                    alt=""> --}}
                                                    @if($settings->introduction_image)
                                                    {{-- Kiểm tra xem có phải là ảnh mặc định hay không --}}
                                                    @if(Str::startsWith($settings->introduction_image, 'theme/client/images/'))
                                                        <img width="100%" src="{{ asset($settings->introduction_image) }}" alt="introduction_image" class="introduce-logo">
                                                    @else
                                                        <img width="100%" src="{{ Storage::url($settings->introduction_image) }}" alt="introduction_image" class="introduce-logo">
                                                    @endif
                                                    @else
                                                        {{-- Hiển thị ảnh mặc định nếu không có ảnh nào --}}
                                                        <img width="100%" src="{{ asset('theme/client/images/header/P.svg') }}" alt="introduction_image" class="introduce-logo">
                                                    @endif
                                                    <div align='center'>
                                                        <i>Hình ảnh Rạp Poly Cinemas Hà Đông</i>
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
