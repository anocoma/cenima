@extends('client.layouts.master')

@section('title')
    Register
@endsection

@section('content')

    <div class="content-login-3 row">
        <div class="bg-round-regis col-md-12">
            <div class="col-md-6 fom-regis">
                <h2 class="text-login">Đăng ký</h2>

                <div class="st_profile_input float_left">
                    <input type="text" placeholder="Họ và tên">
                </div>

                <div class="st_profile_input st_profile__pass_input_pop float_left">
                    <input type="text" placeholder="Email">
                </div>
                <div class="st_profile__pass_input st_profile__pass_input_pop float_left">
                    <input type="password" placeholder="Mật khẩu">
                </div>
                <div class="st_profile__pass_input st_profile__pass_input_pop float_left">
                    <input type="password" placeholder="Nhập lại mật khẩu">
                </div>

                <div class="float_left nutdn" style="margin-top: 15px;"><a href="">Đăng ký</a>
                </div>

                <div class="st_form_pop_facebook_btn float_left nutfb"><a href="#"> Đăng ký với Facebook</a>
                </div>
                <div class="st_form_pop_gmail_btn float_left nutgm"><a href="#"> Đăng ký với Google</a>
                </div>
                <div class="st_form_pop_signin_btn float_left">
                    <h4>Đã có tài khoản? <a href="#">Đăng nhập</a></h4>
                </div>
            </div>
            <div class="col-md-6 logo-register">

                <img src="{{ asset('theme/client/images/movie1.png') }}" alt="">

            </div>
        </div>
    </div>

@endsection


@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/login.css') }}"/>
@endsection

