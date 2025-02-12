@extends('admin.layouts.master')

@section('title')
    Quản lý slide show
@endsection

@section('style-libs')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- dropzone css -->
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/libs/dropzone/dropzone.css') }}" type="text/css"/>
    <!-- Filepond css -->
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/libs/filepond/filepond.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">

    <style>

    </style>
@endsection

@section('content')
    <form action="{{ route('admin.slideshows.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Thêm slide show</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.slideshows.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <!-- end page title -->

            <div class="row">
                {{--<div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Chọn ảnh</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <p class="text-muted">Vui lòng chọn một hoặc nhiều hình ảnh.</p>

                            <div class="dropzone">
                                <div class="fallback">
                                    <input name="img_thumbnail" type="file" multiple="multiple">
                                </div>
                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                    </div>

                                    <h4>Kéo thả hoặc chọn ảnh.</h4>
                                </div>
                            </div>

                            <ul class="list-unstyled mb-0" id="dropzone-preview">
                                <li class="mt-2" id="dropzone-preview-list">
                                    <!-- This is used as the file preview template -->
                                    <div class="border rounded">
                                        <div class="d-flex p-2">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded">
                                                    <img data-dz-thumbnail class="img-fluid rounded d-block" src="{{ asset('theme/admin/assets/images/new-document.png') }}" alt="Dropzone-Image"/>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="pt-1">
                                                    <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                                    <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ms-3">
                                                <button data-dz-remove class="btn btn-sm btn-danger">Xóa</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- end dropzon-preview -->
                            @error('img_thumbnail')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div> <!-- end col -->--}}

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Chọn ảnh</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="d-flex mb-1 justify-content-between">
                                <p class="text-muted">Vui lòng chọn một hoặc nhiều hình ảnh.</p>
                                <p class="btn btn-sm btn-primary fw-bold" id="add-row" style="cursor: pointer">Thêm ảnh </p>
                            </div>
                            <div class="my-3">

                                <table style="width: 100%;">
                                    <tbody id="img-table">
                                    <tr>
                                        <td class="d-flex align-items-center justify-content-around">
                                            <div style="width: 100%;">
                                                <div class="border rounded">
                                                    <div class="d-flex p-2">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar-sm bg-light rounded">
                                                                <img id="preview_0" src="" style="width: 45px; height: 45px">
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="pt-1" style="width: 73%;">
                                                                <input type="file" id="img_thumbnail" name="img_thumbnail[id_0]"
                                                                       class="form-control @error('img_thumbnail') is-invalid @enderror" onchange="previewImg(this, 0)">
                                                                @error('img_thumbnail.0')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        {{--<div class="flex-shrink-0 ms-3">
                                                            <button class="btn btn-sm btn-danger">Xóa</button>
                                                        </div>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" rows="7" name="description"></textarea>
                            @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <!-- end card body -->

                        <input type="hidden" name="is_active" value="0">
                    </div>

                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <a href="{{ route('admin.slideshows.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>

    </form>
@endsection

@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- dropzone min -->
    <script src="{{ asset('theme/admin/assets/libs/dropzone/dropzone-min.js') }}"></script>
    <!-- filepond js -->
    <script src="{{ asset('theme/admin/assets/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('theme/admin/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>

    <script src="{{ asset('theme/admin/assets/js/pages/form-file-upload.init.js') }}"></script>

    <script src="https:////cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
    <script>
        CKEDITOR.replace("content", {
            width: "100%",
            height: "750px"
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var rowCount = 1;
            document.getElementById('add-row').addEventListener('click', function () {
                var tableBody = document.getElementById('img-table');

                var newRow = document.createElement('tr');

                newRow.innerHTML = `
                                         <td class="d-flex align-items-center justify-content-around">
                                            <div class="mt-2" style="width: 100%;">
                                                <div class="border rounded">
                                                    <div class="d-flex p-2">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar-sm bg-light rounded">
                                                                <img id="preview_${rowCount}" src="" style="width: 45px; height: 45px">
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="pt-1" style="width: 80%;">
                                                                <input type="file" id="img_thumbnail" name="img_thumbnail[id_${rowCount}]"
                                                                       class="form-control" onchange="previewImg(this, ${rowCount})">
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-3">
                                                            <button class="btn btn-sm btn-danger" onclick="removeRow(this)">Xóa</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                `;

                tableBody.appendChild(newRow);
                rowCount++;
            })
        });

        function previewImg(input, rowindex) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById(`preview_${rowindex}`).setAttribute('src', e.target.result)
                }

                reader.readAsDataURL(input.files[0])
            }
        }

        function removeRow(item) {
            var row = item.closest('tr');
            row.remove();
        }

    </script>
@endsection
