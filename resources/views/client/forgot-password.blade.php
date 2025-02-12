@extends('client.layouts.master')

@section('title')
    Forgot password
@endsection

@section('content')

    <div class="content-forgot row">
        <div class="bg-round-forgot col-md-12">
            <h2 style="text-align: center">Quên mật khẩu</h2>
            <div class="col-md-3"></div>
            <div class="col-md-6 forgot-fom">
                <div class="st_profile_input float_left">
                    <label>Email</label>
                    <input type="text">
                </div>
                <div class="rs-pw">
                    <a href="">Gửi email</a>
                </div>
                <div class="st_form_pop_signin_btn float_left">
                    <h4>Đã có tài khoản? <a href="#">Đăng nhập</a></h4>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>

    </div>

@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/login.css') }}"/>
@endsection
