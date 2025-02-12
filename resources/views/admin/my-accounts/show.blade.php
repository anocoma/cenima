@extends('admin.layouts.master')

@section('title')
    Hồ sơ cá nhân
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
                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
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
                                <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                    <i class="fas fa-home"></i> Hồ sơ cá nhân
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                    <i class="far fa-user"></i> Thay đổi mật khẩu
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                <form action="">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Họ và tên</label>
                                                <input type="text" class="form-control" placeholder="Họ và tên"
                                                    name="name" value="{{ $user->name }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label">Ngày sinh</label>
                                                <input type="date" class="form-control" name="birthday"
                                                    value="{{ $user->birthday }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Giới tính</label>
                                                <input type="text" value="{{ $user->gender }}" disabled
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" placeholder="user123@gmail.com"
                                                    name="email" value="{{ $user->email }}" disabled>

                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Số điện thoại</label>
                                                <input type="text" class="form-control" placeholder="0965263725"
                                                    name="phone" value="{{ $user->phone }}" disabled>

                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Địa chỉ</label>
                                                <input type="text" class="form-control" placeholder="0965263725"
                                                    name="phone" value="{{ $user->address }}" disabled>

                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-end">
                                                <a href="{{ route('admin.my-account.edit') }}" class="btn btn-primary">Thay
                                                    đổi thông tin </a>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            
                            <div class="tab-pane" id="changePassword" role="tabpanel">
                                <form id="changePasswordForm">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col-lg-12 mb-2">
                                            <div>
                                                <span class='text-danger'>*</span>
                                                <label for="old_password" class="form-label">Mật khẩu hiện tại</label>
                                                <input type="password" class="form-control" name="old_password"
                                                    id="old_password" placeholder="Nhập mật khẩu hiện tại">



                                                <div class='mt-1'>
                                                    <span id='error_old_password'
                                                        class="text-danger message_error"></span>
                                                </div>



                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-12 mb-2">
                                            <div>
                                                <span class='text-danger'>*</span>
                                                <label for="password" class="form-label">Mật khẩu mới</label>
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Nhập mật khẩu mới">

                                                <div class='mt-1'>
                                                    <span class="text-danger message_error" id='error_password'> </span>
                                                </div>

                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-12 mb-2">
                                            <div>
                                                <span class='text-danger'>*</span>
                                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu
                                                    mới</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" placeholder="Nhập xác nhận mật khẩu mới">
                                            </div>

                                            <div class='mt-1'>
                                                <span class="text-danger message_error"
                                                    id="error_password_confirmation"></span>
                                            </div>

                                        </div>
                                        <div id="response-message"></div>
                                        <!--end col-->
                                        {{-- <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <a href="javascript:void(0);" class="link-primary text-decoration-underline">Forgot Password ?</a>
                                                </div>
                                            </div> --}}
                                        <!--end col-->
                                        <div class="col-lg-12 mb-2">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-success">Xác nhận </button>
                                            </div>
                                        </div>
                                        <div id="success-message" class="alert alert-success" style="display: none;">
                                            Đổi mật khẩu thành công!
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault(); // Ngăn chặn form submit theo cách thông thường

            // Lấy dữ liệu form
            var formData = {
                old_password: $('#old_password').val(),
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
                _token: $('input[name="_token"]').val()
            };

            // Gửi yêu cầu AJAX
            $.ajax({
                url: "{{ route('admin.my-account.change-password') }}", // Route xử lý
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Nếu thành công, reload lại trang để hiển thị session thông báo thành công
                        location.reload();
                    }
                },
                error: function(response) {
                    // Xóa các thông báo lỗi cũ
                    $('.message_error').text('');

                    // Hiển thị lỗi cho các trường
                    if (response.status === 422) {
                        var errors = response.responseJSON.errors;
                        console.log(errors);
                        $.each(errors, function(key, value) {
                            $('#error_' + key).text(value[
                            0]); // Hiển thị lỗi dưới các trường tương ứng
                        });
                    }
                    $('#password').val('');
                    $('#password_confirmation').val('');
                }
            });
        });
    </script>
@endsection
