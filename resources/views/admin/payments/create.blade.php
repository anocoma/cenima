@extends('admin.layouts.master')

@section('title')
    Thêm mới phương thức thanh toán
@endsection

@section('content')
<form action="{{route('admin.payments.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thêm mới Thanh Toán</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- thông tin -->
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('error'))
                <div class="alert alert-danger m-3">
                    {{ session()->get('error') }}
                </div>
            @endif
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Thông tin phương thức thanh toán</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <div class="row gy-4">
                            <div class="col-md-12">
                                <!-- Hàng đầu tiên chứa tên phương thức thanh toán -->
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="name" class="form-label">Tên phương thức thanh toán:</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên phương thức thanh toán">
                                        @error("name")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Hàng thứ hai chứa mô tả -->
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="description" class="form-label">Mô tả:</label>
                                        <textarea class="form-control" rows="3" name="description" placeholder="Nhập mô tả thanh toán"></textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-info">Danh sách</a>
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

     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

     <!-- Layout config Js -->
     <script src="assets/js/layout.js"></script>
     <!-- Bootstrap Css -->
     <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
     <!-- Icons Css -->
     <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
     <!-- App Css-->
     <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
     <!-- custom Css-->
     <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('theme/admin/assets/js/pages/select2.init.js') }}"></script>

    <script src="https:////cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
    <script>
        CKEDITOR.replace("content",{
            width: "100%",
            height: "750px"
        });
    </script>
@endsection
