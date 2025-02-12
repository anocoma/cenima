@extends('admin.layouts.master')

@section('title')
    Quản lý chi nhánh
@endsection
@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý chi nhánh</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="">Chi nhánh</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Thêm mới chi nhánh</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.branches.store') }}" method="post">
                            @csrf
                            <div class="row ">
                                <div class="mb-2">
                                    <label for="name" class="form-label">
                                        <span class="text-danger">*</span>Tên chi nhánh:
                                    </label>
                                    <input type="text" class='form-control' name="name" value="{{ old('name') }}"
                                        placeholder="Nhập tên chi nhánh">
                                    <div class="form-text">
                                        Tên chi nhánh của rạp chiếu phim.
                                    </div>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @can('Thêm chi nhánh')
                                    <div class="text-end mt-2">
                                        <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                                    </div>
                                @endcan
                            </div>
                        </form>

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
                            <h4 class="card-title mb-0 flex-grow-1">Danh sách chi nhánh </h4>
                        </div><!-- end card header -->

                        <div class="card-body ">
                            <table class="table table-bordered dt-responsive nowrap w-100" id="example">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên chi nhánh</th>
                                        <th>Hoạt động</th>
                                        <th>Ngày tạo</th>
                                        <th>Ngày cập nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branches as $index => $branch)
                                        <tr>
                                            <td>{{ $branch->id }}</td>
                                            <td>
                                                <div class='room-name'>
                                                    <div>{{ $branch->name }} {!! $branch->is_default ? '<span class="text-black-50 small">(Mặc định)</span>' : null !!}
                                                    </div>
                                                    <div>
                                                        @can('Sửa chi nhánh')
                                                            <a class="cursor-pointer text-info small openUpdateBranchModal"
                                                                data-branch-id="{{ $branch->id }}"
                                                                data-branch-name="{{ $branch->name }}">Sửa</a>
                                                        @endcan
                                                        @can('Xóa chi nhánh')
                                                            @if ($branch->cinemas()->count() == 0)
                                                                <a href="{{ route('admin.branches.destroy', $branch) }}"
                                                                    class="cursor-pointer  mx-1 text-danger small"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa ?')">Xóa</a>
                                                            @endif
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @can('Sửa chi nhánh')
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-branch-id="{{ $branch->id }}" @checked($branch->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            name="is_active" type="checkbox" disabled @readonly(true) role="switch"
                                                            data-branch-id="{{ $branch->id }}" @checked($branch->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @endcan
                                            </td>
                                            <td class="small">
                                                {{ $branch->created_at->format('d/m/Y') }}<br>{{ $branch->created_at->format('H:i:s') }}
                                            </td>
                                            <td class="small">
                                                {{ $branch->updated_at->format('d/m/Y') }}<br>{{ $branch->updated_at->format('H:i:s') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!-- Modal Cập nhật chi nhánh -->
    <div class="modal fade" id="updateBranchModal" tabindex="-1" aria-labelledby="updateBranchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateBranchModalLabel">Cập nhật chi nhánh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateBranchForm" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" id="updateBranchId" name="branch_id">
                            <div class="col-md-12 mb-3">
                                <label for="updateName" class="form-label"><span class="text-danger">*</span> Tên chi
                                    nhánh:</label>
                                <input type="text" class="form-control" id="updateName" name="name" required
                                    placeholder="Nhập tên chi nhánh">
                                <span class="text-danger mt-3" id="updateNameError"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateBranchBtn">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
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
        $(document).ready(function() {
            // Khởi tạo DataTable
            let table = $('#example').DataTable({
                order: [],
                language: {
                    search: "Tìm kiếm:",
                    paginate: {
                        next: "Tiếp theo",
                        previous: "Trước"
                    },
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp"
                },
            });
            // Xử lý sự kiện change cho checkbox .changeActive
            $(document).on('change', '.changeActive', function() {
                let branchId = $(this).data('branch-id');
                let is_active = $(this).is(':checked') ? 1 : 0;

                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false, // Không cho phép đóng ngoài khi đang xử lý
                    didOpen: () => {
                        Swal.showLoading(); // Hiển thị spinner loading
                    }
                });
                // Gửi yêu cầu AJAX để thay đổi trạng thái
                $.ajax({
                    url: '{{ route('branches.change-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: branchId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (response.success) {
                            let row = table.row($(`[data-branch-id="${branchId}"]`).closest(
                                'tr'));
                            console.log(row);

                            // Cập nhật cột trạng thái (cột thứ 2) trong dòng này
                            let statusHtml = response.data.is_active ?
                                `<div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input switch-is-active changeActive"
                                        type="checkbox" data-branch-id="${branchId}" checked   onclick="return confirm('Bạn có chắc muốn thay đổi ?')"> </div>` :
                                `<div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input switch-is-active changeActive"
                                        type="checkbox" data-branch-id="${branchId}"   onclick="return confirm('Bạn có chắc muốn thay đổi ?')"> </div>`;
                            row.cell(row.index(), 2).data(statusHtml).draw(false);
                            row.cell(row.index(), 4).data(
                                `${response.data.updated_date}<br>${response.data.updated_time}`
                            ).draw(false);



                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Trạng thái hoạt động đã được cập nhật.',
                                confirmButtonText: 'Đóng',
                                timer: 3000,
                                timerProgressBar: true,

                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi cập nhật trạng thái.',
                            confirmButtonText: 'Đóng',
                            timer: 3000,
                            showConfirmButton: true,
                        });

                        // Hoàn lại thao tác (set lại trạng thái ban đầu cho checkbox)
                        let checkbox = $(`[data-branch-id="${branchId}"]`).closest('tr').find(
                            '.changeActive');
                        checkbox.prop('checked', !is_active);
                    }
                });

                console.log('Đã thay đổi trạng thái active');
            });
        });
    </script>
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


        document.querySelectorAll('.openUpdateBranchModal').forEach(button => {
            button.addEventListener('click', function() {
                const branchId = this.getAttribute('data-branch-id'); // Lấy roomId từ data attribute
                const branchName = this.getAttribute('data-branch-name');

                document.getElementById('updateBranchId').value = branchId; // Gán giá trị roomId
                document.getElementById('updateName').value = branchName;

                // Reset các lỗi trước khi mở modal
                resetErrors('update');
                $('#updateBranchModal').modal('show');
            });
        });

        // Xử lý nút click
        document.getElementById('updateBranchBtn').addEventListener('click', function(event) {
            document.getElementById('updateBranchForm').dispatchEvent(new Event(
                'submit')); // Kích hoạt sự kiện submit của form
        });

        // Xử lý sự kiện submit của form
        document.getElementById('updateBranchForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn hành vi mặc định (reload trang)

            const form = document.getElementById('updateBranchForm');
            const formData = new FormData(form);
            const branchId = document.getElementById('updateBranchId').value; // Lấy ID phòng từ hidden input
            let hasErrors = false; // Biến để theo dõi có lỗi hay không

            const url = '{{ route('admin.branches.update', ':id') }}'.replace(':id', branchId);

            // Gửi request qua Fetch API
            fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            handleErrors(errorData.errors, 'update');
                            hasErrors = true;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!hasErrors) {
                        console.log(data);
                        $('#updateBranchModal').modal('hide'); // Đóng modal
                        form.reset(); // Reset form
                        location.reload(); // Reload trang
                    }
                })
                .catch(error => console.error('Error updating branch:', error));
        });


        function handleErrors(errors, prefix) {
            document.getElementById(`${prefix}NameError`).innerText = '';
            if (errors.name) {
                document.getElementById(`${prefix}NameError`).innerText = errors.name.join(', ');
            }
        }

        function resetErrors(prefix) {
            document.getElementById(`${prefix}NameError`).innerText = '';
        }
    </script>
@endsection
