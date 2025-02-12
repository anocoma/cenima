@extends('admin.layouts.master')

@section('title')
    Thêm mới mã giảm giá
@endsection

@section('style-libs')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <form action="{{ route('admin.vouchers.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý mã giảm giá</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thông tin mã giảm giá</h4>
                    </div><!-- end card header -->

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-success">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row ">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <span class="text-danger">*</span>
                                        <label for="code" class="form-label ">Mã giảm giá:</label>

                                        <input type="text" class="form-control " id="code"
                                               name="code"  {{--value="{{ strtoupper(\Str::random(8)) }}"--}}value="{{ $uniqueCode }}" >
                                        @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <span class="text-danger">*</span>
                                        <label for="quantity" class="form-label ">Số lượng:</label>
                                        <input type="text" class="form-control " id="quantity"
                                               name="quantity" value="{{ old('quantity') }}"
                                               placeholder="Nhập số lượng...">
                                        @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <span class="text-danger">*</span>
                                        <label for="discount" class="form-label ">Giảm giá (vnđ):</label>
                                        <input type="text" class="form-control " id="discount"
                                               name="discount" value="{{ old('discount') }}"
                                               placeholder="Nhập số tiền...">
                                        @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <span class="text-danger">*</span>
                                        <label for="datetime">Chọn thời gian bắt đầu:</label>
                                        <input type="text" id="start_datetime" class="form-control"
                                               name="start_date_time" value="{{ old('start_date_time') }}"
                                               placeholder="Vui lòng chọn">
                                        @error('start_date_time')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <span class="text-danger">*</span>
                                        <label for="datetime">Chọn thời gian kết thúc:</label>
                                        <input type="text" id="end_datetime" class="form-control" name="end_date_time"
                                               value="{{ old('end_date_time') }}" placeholder="Vui lòng nhập">
                                        @error('end_date_time')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="limit" class="form-label">Giới hạn sử dụng:</label>
                                        <input type="text" value="1" name="limit" id="limit"
                                               class="form-control"
                                               placeholder="Mặc định 1, khác vui lòng nhập...">
                                        @error('limit')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <span class="text-danger">*</span>
                                        <label for="title" class="form-label ">Tiêu đề:</label>
                                        <input type="text" class="form-control " id="title"
                                               name="title" value="{{ old('title') }}">
                                        @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Mô tả ngắn:</label>
                                        <textarea class="form-control " rows="3" name="description"></textarea>
                                        @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!--end row-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    {{--<div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <label class="form-check-label" for="is_active">Is Active</label>
                                            <div class="form-check form-switch form-switch-default">
                                                <input class="form-check-input" type="checkbox"
                                                       name="is_active" checked value="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <label class="form-check-label" for="is_publish">Is publish</label>
                                            <div class="form-check form-switch form-switch-default">
                                                <input class="form-check-input" type="checkbox"
                                                       name="is_publish" checked value="1">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>--}}
                    {{--<div class="col-md-12">
                        <h5 class="mt-3 text-center">Tùy chọn người dùng</h5>
                        <div class="card">
                            <div class="card-body">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-success mb-3"
                                    role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab"
                                           href="#nav-border-justified-home" role="tab"
                                           aria-selected="false" tabindex="-1">
                                            Không chọn
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab"
                                           href="#nav-border-justified-profile" role="tab"
                                           aria-selected="true">
                                            Người dùng
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content text-muted">
                                    <div class="tab-pane" id="nav-border-justified-home"
                                         role="tabpanel">
                                        <h6>Không chọn người dùng nào...</h6>
                                    </div>
                                    <div class="tab-pane active show" id="nav-border-justified-profile"
                                         role="tabpanel">
                                        <h6>Người dùng:</h6>
                                        <select class="js-example-basic-multiple" name="user_ids[]"
                                                multiple="multiple">
                                            @foreach ($users as $user)
                                                <option
                                                    value="{{ $user->id }}" @selected(in_array($user->id, old('user_ids') ?? []))>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div><!-- end card-body -->
                        </div>
                    </div>--}}
                    <div class="col-md-12">
                        <div class="card card-seat ">
                            <div class="card-body ">
                                <div class="row mt-2">
                                    <div class="col-md-6 d-flex ">
                                        <label class="form-label">Hoạt động:</label>
                                        <span class="text-muted mx-2">
                                                                <div class="form-check form-switch form-switch-success">
                                                                    <input class="form-check-input switch-is-active"
                                                                           name="is_active"
                                                                           type="checkbox" role="switch" checked
                                                                           value="1">
                                                                </div>
                                                            </span>
                                    </div>
                                    <div class="col-md-6 d-flex ">
                                        <label class="form-label">Publish:</label>
                                        <span class="text-muted mx-2">
                                                                <div class="form-check form-switch form-switch-danger">
                                                                    <input class="form-check-input switch-is-active"
                                                                           name="is_publish"
                                                                           type="checkbox" role="switch" checked
                                                                           value="1">
                                                                </div>
                                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                    </div>
                </div>

            </div>
            <!--end col-->
        </div>
    </form>
@endsection

@section('style-libs')
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css"/>
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section('script-libs')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#start_datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
                time_24hr: true,
            });

            flatpickr("#end_datetime", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
                time_24hr: true,
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('theme/admin/assets/js/pages/select2.init.js') }}"></script>

    <script src="https:////cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
    <script>
        CKEDITOR.replace("content", {
            width: "100%",
            height: "750px"
        });
    </script>
@endsection
