@extends('admin.layouts.master')

@section('title')
    Quản lý slide show
@endsection

@section('content')
    <form action="{{ route('admin.slideshows.update', $slide->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Cập nhật slideshow</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.slideshows.index') }}">Danh sách</a>
                            </li>
                            <li class="breadcrumb-item active">Cập nhật</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sửa ảnh</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="d-flex mb-1 justify-content-between">
                            <p class="text-muted">Vui lòng chọn một hoặc nhiều hình ảnh để chỉnh sửa.</p>
                            <p class="btn btn-sm btn-primary fw-bold" id="add-row" style="cursor: pointer">Thêm ảnh </p>
                        </div>
                        <div class="my-3">

                            <table style="width: 100%;">
                                <tbody id="img-table">
                                @if (!empty($slide->img_thumbnail) && is_array($slide->img_thumbnail))
                                    @foreach ($slide->img_thumbnail as $key => $imgPath)
                                        <tr>
                                            <td class="d-flex align-items-center justify-content-around">
                                                <div style="width: 100%;">
                                                    <div class="border rounded">
                                                        <div class="d-flex p-2">
                                                            <div class="flex-shrink-0 me-3">
                                                                <div class="avatar-sm bg-light rounded">
                                                                    <img id="preview_{{ $key }}"
                                                                         src="{{ Storage::url($imgPath) }}"
                                                                         style="width: 45px; height: 45px; object-fit: cover;">
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="pt-1" style="width: 73%;">
                                                                    <input type="file"
                                                                           id="img_thumbnail_{{ $key }}"
                                                                           name="img_thumbnail[id_{{ $key }}]"
                                                                           class="form-control @error('img_thumbnail.' . $key) is-invalid @enderror"
                                                                           onchange="previewImg(this, {{ $key }})">
                                                                    <input type="hidden" name="existing_images[id_{{ $key }}]" value="{{ $imgPath }}">
                                                                    @error('img_thumbnail.' . $key)
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="flex-shrink-0 ms-3">
                                                                <button class="btn btn-sm btn-danger" type="button" onclick="removeRow(this)">Xóa</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- end dropzon-preview -->
                        @error('img_thumbnail')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div> <!-- end col -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Mô tả</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <textarea class="form-control @error('description') is-invalid @enderror" rows="7"
                                  name="description">{{ old('description', $slide->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <!-- end card body -->
                </div>

            </div> <!-- end col -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <a href="{{ route('admin.slideshows.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Lưu thay đổi</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let rowCount = document.querySelectorAll('#img-table tr').length;

            document.getElementById('add-row').addEventListener('click', function () {
                const tableBody = document.getElementById('img-table');

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
        <td class="d-flex align-items-center justify-content-around">
            <div class="mt-2" style="width: 100%;">
                <div class="border rounded">
                    <div class="d-flex p-2">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm bg-light rounded">
                                <img id="preview_${rowCount}" src="" style="width: 45px; height: 45px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="pt-1" style="width: 73%;">
                                <input type="file" id="img_thumbnail_${rowCount}" name="img_thumbnail[new_${rowCount}]"
                                       class="form-control" onchange="previewImg(this, ${rowCount})">
                            </div>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <button class="btn btn-sm btn-danger" type="button" onclick="removeRow(this)">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>`;
                tableBody.appendChild(newRow);
                rowCount++;
            });

            window.previewImg = function (input, index) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById(`preview_${index}`).src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };

            window.removeRow = function (item) {
                const row = item.closest('tr');
                row.remove();
            };
        });
    </script>

@endsection
