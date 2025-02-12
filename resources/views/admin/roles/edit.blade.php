@extends('admin.layouts.master')

@section('title')
    Cập nhật vai trò
@endsection

@section('content')
    <form action="{{ route('admin.roles.update', $role) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Cập nhật vai trò</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Cập nhật</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thêm thông tin vai trò</h4>
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
                                        <div class="col-md-12 mb-3">
                                            <label for="name" class="form-label "> <span class="text-danger">*</span>Tên
                                                vai trò:
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $role->name }}" required
                                                placeholder="Quản lý Rạp, Người viết bài,...">
                                            <div class="mt-2">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Thêm quyền hạn cho vai trò</h4>
                            </div><!-- end card header -->

                            <div class="card-body box-permission-role">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label col-12">
                                            <span class="text-danger">*</span> Chọn Quyền
                                        </label>

                                        @error('permissions')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <input type="checkbox" name="checkboxAll" class="checkboxAll"
                                                    id="checkboxAll">
                                                <label for="checkboxAll">Chọn tất cả</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 box-search-role">
                                        <div>
                                            <input type="text" class="form-control input-search-role"
                                                id="search-permission" placeholder="Tìm tên quyền...">
                                        </div>
                                        <div>
                                            <button class="btn btn-primary btn-search-role" type="button"
                                                onclick="filterPermissions()">Tìm</button>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12  px-5">
                                        @foreach ($permissions as $permission)
                                            <div class="form-check mb-2 border py-2 px-5 ">
                                                <input type="checkbox" class="form-check-input" id="{{ $permission->id }}"
                                                    name="permissions[]" value="{{ $permission->name }}"
                                                    @if (in_array($permission->name, $rolePermissions)) checked @endif>
                                                <label for="{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        @endforeach

                                        @error('permissions')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Cập nhật</button>
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

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script>
        function filterPermissions() {
            const searchValue = document.getElementById("search-permission").value.toLowerCase();
            const permissionItems = document.querySelectorAll(".form-check");

            permissionItems.forEach(item => {
                const label = item.querySelector("label").textContent.toLowerCase();
                if (label.includes(searchValue)) {
                    item.style.display = "block"; // Hiển thị mục nếu khớp
                } else {
                    item.style.display = "none"; // Ẩn mục nếu không khớp
                }
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            const selectAllCheckbox = document.querySelector("#checkboxAll");
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');

            selectAllCheckbox.addEventListener("click", function() {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });


            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener("click", function() {
                    // Kiểm tra nếu tất cả checkbox con được chọn thì chọn "Chọn tất cả"
                    selectAllCheckbox.checked = [...checkboxes].every(
                        (checkbox) => checkbox.checked
                    );
                });
            });
        });
    </script>
@endsection
