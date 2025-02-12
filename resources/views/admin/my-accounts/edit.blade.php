@extends('admin.layouts.master')

@section('title')
    Thay đổi thông tin
@endsection


@section('content')
<style>
    .profile-setting-img2{
    position: relative;
    height: 260px;
}
.profile-wid-bg2 .profile-wid-img {
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
}
</style>
    <div class="container-fluid">

        <div class="position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg2 profile-setting-img2">
                <img src="{{ asset('theme/admin/assets/images/bg-my-account.jpg') }}" class="profile-wid-img" alt="">
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-3">
                <div class="card mt-n5">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                @php
                                    $url = $user->img_thumbnail ?? '';

                                    if (!\Str::contains($url, 'http')) {
                                        $url = Storage::url($url);
                                    }

                                @endphp
                                @if (!empty($user->img_thumbnail))
                                    <img src="{{ $url }}"
                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image1"
                                        alt="user-profile-image">
                                @else
                                    <img src="{{ asset('theme/admin/assets/images/users/user-dummy-img.jpg') }}"
                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                        alt="user-profile-image">
                                @endif

                            </div>
                            <h5 class="fs-16 mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">
                                {{ $user->type == App\Models\User::TYPE_ADMIN ? 'Quản trị viên' : 'Khách hàng' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-xxl-9">
                <div class="card mt-xxl-n5">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0">
                            <li class="nav-item">
                                <a class="nav-link active">
                                    <i class="fas fa-home"></i> Thông tin cá nhân
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div>
                                <form action="{{ route('admin.my-account.update') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="text-center">
                                            <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                @php
                                                    $url = $user->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }

                                                @endphp
                                                @if (!empty($user->img_thumbnail))
                                                    <img src="{{ $url }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                        alt="user-profile-image">
                                                @else
                                                    <img src="{{ asset('theme/admin/assets/images/users/user-dummy-img.jpg') }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                        alt="user-profile-image">
                                                @endif
                                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                    <input id="profile-img-file-input" type="file"
                                                        class="profile-img-file-input" accept="image/png, image/jpeg"
                                                        name="img_thumbnail">
                                                    <label for="profile-img-file-input"
                                                        class="profile-photo-edit avatar-xs">
                                                        <span class="avatar-title rounded-circle bg-light text-body">
                                                            <i class="ri-camera-fill"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <h5 class="fs-14">Hình ảnh</h5>

                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="mb-3">
                                                <span class='text-danger'>*</span>
                                                <label class="form-label">Họ và tên</label>
                                                <input type="text" class="form-control" placeholder="Họ và tên"
                                                    name="name" value="{{ $user->name }}">
                                                @error('name')
                                                    <div class='mt-1'>
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="mb-3">
                                                <span class='text-danger'>*</span>
                                                <label class="form-label">Ngày sinh</label>
                                                <input type="date" class="form-control" name="birthday"
                                                    value="{{ $user->birthday }}">
                                                @error('birthday')
                                                    <div class='mt-1'>
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <div class="mb-3">
                                                <span class='text-danger'>*</span>
                                                <label class="form-label">Giới tính</label>
                                                <select name="gender" id="" class="form-select">
                                                    @foreach ($genders as $gender)
                                                        <option value="{{ $gender }}" @selected($user->gender == $gender)>
                                                            {{ $gender }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('gender')
                                                    <div class='mt-1'>
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-3">
                                                <span class='text-danger'>*</span>
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" placeholder="user123@gmail.com"
                                                    name="email" value="{{ $user->email }}">
                                                @error('email')
                                                    <div class='mt-1'>
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-3">
                                                <span class='text-danger'>*</span>
                                                <label class="form-label">Số điện thoại</label>
                                                <input type="text" class="form-control" placeholder="0965263725"
                                                    name="phone" value="{{ $user->phone }}">
                                                @error('phone')
                                                    <div class='mt-1'>
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Địa chỉ</label>
                                                <input type="text" class="form-control" placeholder="" name="adress"
                                                    value="{{ $user->address }}">
                                                @error('adress')
                                                    <div class='mt-1'>
                                                        <span class="text-danger">{{ $message }}</span>
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-end">
                                                <a href="{{ route('admin.my-account') }}"
                                                    class="btn btn-soft-success">Quay lại</a>
                                                <button type="submit" class="btn btn-primary">Xác nhận</button>

                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

    </div>
@endsection
@section('script-libs')
    <script src="{{ asset('theme/admin/assets/js/pages/form-wizard.init.js') }}"></script>
@endsection
