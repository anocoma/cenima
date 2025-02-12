@extends('auth.layouts.master')

@section('title')
    Đăng nhập
@endsection

@section('content')
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="/" class="d-inline-block auth-logo">
                                <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="" height="60">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Đăng Nhập !</h5>

                            </div>
                            <div class="p-2 mt-4">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            id="email" value="{{ old('email') }}" placeholder="Nhập email"
                                            autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        {{-- <div class="float-end">
                                            <a href="auth-pass-reset-basic.html" class="text-muted">Forgot
                                                password?</a>
                                        </div> --}}
                                        <label class="form-label" for="password-input">Mật khẩu</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            {{-- <input type="password" class="form-control pe-5 password-input"
                                                placeholder="Enter password" id="password-input"> --}}

                                            <input id="password password-input" type="password"
                                                class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                                name="password" autocomplete="current-password" placeholder="Nhập mật khẩu">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                type="button" id="password-addon"><i
                                                    class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                            id="auth-remember-check" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="auth-remember-check">Nhớ tôi nhé!</label>

                                        <div class="float-end">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="text-muted">Quên mật
                                                    khẩu?</a>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">Đăng nhập</button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title">Đăng nhập bằng</h5>
                                        </div>
                                        <div>
                                            {{-- <a href="{{ route('auth.facebook') }}"
                                                class="btn btn-primary btn-icon waves-effect waves-light"><i
                                                    class="ri-facebook-fill fs-16"></i></a> --}}
                                            <a href="{{ route('auth.google') }}"
                                                class="btn btn-danger btn-icon waves-effect waves-light"><i
                                                    class="ri-google-fill fs-16"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    <div class="mt-4 text-center">
                        <p class="mb-0">Không có tài khoản ? <a href="{{ route('register') }}"
                                class="fw-semibold text-primary text-decoration-underline"> Đăng ký </a> </p>
                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
@endsection
@section('scripts')
    <!-- password-addon init -->
    <script src="{{ asset('theme/admin/assets/js/pages/password-addon.init.js') }}"></script>
@endsection
