@extends('admin.layouts.master')

@section('title')
    Cập nhật mã giảm giá
@endsection

@section('content')
    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý mã giảm giá</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Cập nhật</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">

            <div class="col-lg-9">
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Mã giảm giá: </label>
                                        <input type="text" class="form-control " id="code"
                                               name="code" value="{{ $voucher->code }}">
                                        @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label ">Số lượng: </label>
                                        <input type="text" class="form-control " id="quantity"
                                               name="quantity" value="{{ $voucher->quantity }}">
                                        @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Giới hạn sử dụng</label>
                                        <input type="text" name="limit" id="" class="form-control" value="{{ $voucher->limit }}">
                                        @error('limit')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label ">Thời gian bắt đầu:</label>
                                        <input type="date" class="form-control" id="start_time"
                                               name="start_date_time" value="{{ date('Y-m-d', strtotime($voucher->start_date_time)) }}">
                                        @error('start_date_time')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label ">Thời gian kết thúc:</label>
                                        <input type="date" class="form-control" id="start_time"
                                               name="end_date_time" value="{{ date('Y-m-d', strtotime($voucher->end_date_time)) }}">
                                        @error('end_date_time')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="discount" class="form-label ">Giảm giá:</label>
                                        <input type="text" class="form-control " id="discount"
                                               name="discount" value="{{ $voucher->discount }}">
                                        @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title" class="form-label ">Tiêu đề:</label>
                                        <input type="text" class="form-control " id="title"
                                               name="title" value="{{ $voucher->title }}">
                                        @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Mô tả ngắn:</label>
                                        <textarea class="form-control " rows="3" name="description">{{ $voucher->description }}</textarea>
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
            <div class="col-lg-3">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 d-flex">
                                        <label class="form-check-label" for="is_active">Is Active</label>
                                        <div class="form-check form-switch form-switch-default">
                                            <input class="form-check-input" type="checkbox" role=""
                                                   name="is_active" @checked($voucher->is_active) value="1">
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex ">
                                        <label class="form-label">Publish:</label>
                                        <span class="text-muted mx-2">
                                            <div class="form-check form-switch form-switch-danger">
                                                <input class="form-check-input switch-is-active" name="is_publish"
                                                       type="checkbox" role="switch" @checked($voucher->is_publish) value="1">
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
                        <button type="submit" class="btn btn-primary mx-1">Cập nhật</button>
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
