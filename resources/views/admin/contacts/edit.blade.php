@extends('admin.layouts.master')

@section('title')
    Sửa trạng thái liên hệ
@endsection

@section('content')
    <form action="{{route('admin.contacts.update', $contact)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Sửa trạng thái liên hệ</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Forms</a></li>
                            <li class="breadcrumb-item active">Sửa trạng thái liên hệ</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">
            <div class="col-md-12">
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
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thông tin liên hệ</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="user_contact" class="form-label">Họ và tên:</label>
                                            <input type="text" class="form-control" id="user_contact" name="user_contact" placeholder="Nhập họ và tên" value="{{$contact->user_contact}}" disabled>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email" class="form-label">Email:</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" value="{{$contact->email}}" disabled>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="phone" class="form-label">Số điện thoại:</label>
                                            <input type="number" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" value="{{$contact->phone}}" disabled>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="title" class="form-label">Tiêu đề:</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề" value="{{$contact->title}}" disabled>    
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="content" class="form-label">Nội dung:</label>
                                            <textarea class="form-control" rows="3" id="content" name="content" placeholder="Nhập nội dung" value="{{$contact->content}}" disabled>{{ $contact->content }}</textarea>
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
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="mb-2">
                                            <label for="status" class="form-label">Trạng thái:</label>
                                            <select name="status" id="" class="form-select">
                                                @foreach ($status as $key => $value)
                                                    <option value="{{ $key }}" @selected(old('status', $contact->status) == $key)>{{ $value }}</option>
                                                @endforeach
                                            </select>
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
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Cập nhật</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </form>
@endsection

