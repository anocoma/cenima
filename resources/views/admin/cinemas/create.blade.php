@extends('admin.layouts.master')

@section('title')
    Thêm mới rạp chiếu
@endsection

@section('content')
    <form action="{{ route('admin.cinemas.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý rạp chiếu</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.cinemas.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">Thêm thông tin Rạp</h4>
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
                            <div class="row gy-4">
                                <div class="col-md-12 ">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label "> <span class="text-danger">*</span>Tên
                                                rạp:
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" placeholder="Nhập tên rạp">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="city_id" class="form-label "> <span
                                                    class="text-danger">*</span>Chi nhánh:
                                            </label>
                                            <select name="branch_id" id="branch_id" class="form-control">
                                                @foreach ($branches as $id => $name)
                                                    <option value="{{ $id}}">{{ $name}}</option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label "> <span class="text-danger">*</span>Địa
                                                chỉ:
                                            </label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="{{ old('address') }}" placeholder="Nhập địa chỉ">
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label"> <span class="text-danger">*</span>Mô tả:
                                            </label>
                                            <textarea class="form-control" rows="3" name="description" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
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
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-seat ">
                            <div class="card-body ">
                                <div class="row ">
                                    <div class="col-md-12 mb-3 d-flex ">
                                        <label class="form-label">Trạng thái:</label>
                                        <span class="text-muted mx-2">
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input switch-is-active" name="is_active"
                                                    type="checkbox" role="switch" checked value="1">
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
                        <a href="{{ route('admin.cinemas.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </form>
@endsection
