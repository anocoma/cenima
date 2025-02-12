@extends('client.layouts.master')

@section('title')
    Login
@endsection

@section('content')
    <div class="content-login-3 row">

        <div class="bg-round-login col-md-12">

            <div class="col-md-6 fom-login">
                <h2 class="text-login">Đăng nhập</h2>

                <div class="st_profile_input float_left">
                    <input type="text" placeholder="Email">
                </div>
                <div class="st_profile__pass_input st_profile__pass_input_pop float_left">
                    <input type="password" placeholder="Mật khẩu">
                </div>
                <div class="st_form_pop_fp float_left">
                    <h3><a href="#">Quên mật khẩu?</a></h3>
                </div>
                <div class="float_left nutdn">
                    <a href="">Đăng nhập</a>
                </div>
                <div class="st_form_pop_or_btn float_left"></div>
                <div class="st_form_pop_facebook_btn float_left nutfb">
                    <a href="#"> Đăng nhập với Facebook</a>
                </div>
                <div class=" float_left nutgm">
                    <a href="#"> Đăng nhập với Google</a>
                </div>
                <div class="st_form_pop_signin_btn float_left">
                    <h4>Không có tài khoản? <a href="#">Đăng ký</a></h4>
                    <h5>Chấp nhận <a href="#">Điều Khoản &amp; Điều kiện</a> của chúng tôi!</h5>
                </div>
            </div>
            <div class="col-md-6 logo-login">
                <img src="{{ asset('theme/client/images/movie1.png') }}" alt="">
            </div>
        </div>


    </div>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/login.css') }}" />
@endsection
