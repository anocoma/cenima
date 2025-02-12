@extends('client.layouts.master')

@section('title')
    Liên hệ
@endsection

@section('content')
{{-- <div class="prs_title_main_sec_wrapper">
    <div class="prs_title_img_overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="prs_title_heading_wrapper">
                    <ul>
                        <li><a href="{{route('home')}}">Trang chủ</a>
                        </li>
                        <li>&nbsp;&nbsp; >&nbsp;&nbsp; Liên hệ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- prs title wrapper End -->
<!-- prs contact form wrapper Start -->
@php
    $settings = App\Models\SiteSetting::first();
@endphp
<div class="prs_contact_form_main_wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="prs_contact_left_wrapper">
                    <h2>Liên hệ với chúng tôi</h2>
                </div>
                <div class="row">
                    <form action="{{ route('contact.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @if (session()->has('error'))
                            <div class="alert alert-danger m-3">
                                {{ session()->get('error') }}
                            </div>
                        @elseif (session()->has('success'))
                            <div class="alert alert-success m-3">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="prs_contact_input_wrapper">
                                <label for="user_contact" class="form-label">Họ và tên:</label>
                                <input type="text" class="form-control" id="user_contact" name="user_contact" placeholder="Nhập họ và tên">
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="prs_contact_input_wrapper">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="prs_contact_input_wrapper">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại">
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="prs_contact_input_wrapper">
                                <label for="title" class="form-label">Tiêu đề:</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề">
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="prs_contact_input_wrapper">
                                <label for="content" class="form-label">Nội dung:</label>
                                <textarea class="form-control " rows="3" id="content" name="content" placeholder="Nhập nội dung"></textarea>
                                 <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="response"></div>
                            <div class="prs_contact_input_wrapper prs_contact_input_wrapper2">
                                <ul>
                                    <li>
                                        <input type="hidden" name="form_type" value="contact" />
                                        <button type="submit" class="submitForm">Gửi</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="prs_contact_right_section_wrapper">
                    <ul>
                        <li><a href="{{ $settings->facebook_link }}" target="_blank"><i class="fa fa-facebook"></i>
                                &nbsp;&nbsp;&nbsp;facebook.com</a>
                        </li>
                        {{-- <li><a href="#"><i class="fa fa-twitter"></i> &nbsp;&nbsp;&nbsp;twitter.com/presenter</a>
                        </li>
                        <li><a href="#"><i class="fa fa-vimeo"></i> &nbsp;&nbsp;&nbsp;vimeo.com/presenter</a>
                        </li> --}}
                        <li><a href="{{ $settings->youtube_link }}" target="_blank"><i class="fa fa-youtube-play"></i>
                            &nbsp;&nbsp;&nbsp;youtube.com</a>
                        </li>
                        <li><a href="{{ $settings->instagram_link }}" target="_blank"><i class="fa fa-instagram"></i>
                                &nbsp;&nbsp;&nbsp;instagram.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <style>
        .prs_contact_input_wrapper {
            position: relative;
            margin-bottom: 30px;
        }

        /* Căn chỉnh thông báo lỗi bên dưới input */
        .prs_contact_input_wrapper .text-danger {
            position: absolute;
            bottom: -20px; /* Điều chỉnh khoảng cách giữa input và thông báo lỗi */
            left: 0;
            font-size: 14px;
            color: #ff5b5b;
            margin: 0;
        }

        /* Đảm bảo các input có kích thước đồng đều */
        .prs_contact_input_wrapper input,
        .prs_contact_input_wrapper textarea {
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('form').on('submit', function (e) {
                e.preventDefault(); // Ngăn tải lại trang

                let formData = new FormData(this);
                $('.text-danger').html(''); // Xóa lỗi cũ nếu có

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            // Tải lại trang khi gửi yêu cầu thành công
                            location.reload();
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            // Hiển thị lỗi cho từng trường input
                            $.each(errors, function (key, value) {
                                $(`#${key}`).next('.text-danger').html(value[0]);
                            });
                        } else {
                            $('.response').html(`<div class="alert alert-danger">${xhr.responseJSON.message}</div>`);
                        }
                    }
                });
            });
        });
    </script>
@endsection
