@extends('admin.layouts.master')

@section('title')
    Cập nhật đồ ăn: {{ $food->name }}
@endsection

@section('content')
    <form action="{{ route('admin.food.update', $food) }}" method="post" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý đồ ăn</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.food.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Cập nhật</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">Thông tin đồ ăn</h4>
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
                                <div class="col-md-12">
                                    <div class="row p-3">
                                        <div class="col-md-4 mb-3">
                                            <label for="name" class="form-label "> <span class="text-danger">*</span>Tên
                                                Đồ ăn
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $food->name }}" placeholder="Nhập tên đồ ăn">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="price" class="form-label ">Loại đồ ăn</label>
                                            <select name="type" id="type" class="form-select">
                                                @foreach ($types as $type)
                                                    <option value="{{ $type }}" @selected($food->type == $type)>
                                                        {{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="price" class="form-label "> <span class="text-danger">*</span>Giá
                                            </label>
                                            <input type="number" class="form-control" id="price" name="price"
                                                value="{{ $food->price }}" placeholder="Nhập giá">
                                            @error('price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label"> <span
                                                    class="text-danger">*</span>Mô tả
                                                ngắn</label>
                                            <textarea class="form-control" rows="3" name="description" placeholder="Nhập mô tả">{{ $food->description }}</textarea>
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
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-2">
                                    <label for="img_thumbnail" class="form-label"> <span class="text-danger">*</span>Hình
                                        ảnh</label>
                                    <input type="file" name="img_thumbnail" id="img_thumbnail" class="form-control">

                                    @if ($food->img_thumbnail && \Storage::exists($food->img_thumbnail))
                                        <div class="text-center">
                                            <img src="{{ Storage::url($food->img_thumbnail) }}" alt="" class="mt-3"
                                            width="150px" height="90px">
                                        </div>
                                    @else
                                        No image !
                                    @endif

                                    @error('img_thumbnail')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label class="form-check-label" for="is_active">Is active:</label>
                                            <div class="form-check form-switch form-switch-default">
                                                <input class="form-check-input" type="checkbox" role=""
                                                    name="is_active" @checked($food->is_active) value="1">
                                            </div>
                                        </div>
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
                        <a href="{{ route('admin.food.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Cập nhật</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </form>
@endsection
