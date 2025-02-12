@extends('admin.layouts.master')

@section('title')
    Quản lý mã giảm giá
@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection



@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý mã giảm giá</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mã giảm giá</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Cài đặt giảm giá Voucher Sinh Nhật</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.vouchers.update-discount') }}" method="post">
                            @csrf
                            <div class="row ">
                                <div class="mb-2">
                                    <label for="name" class="form-label">
                                        Số tiền giảm giá (VND)
                                    </label>
                                    <input type="number" name="discount" class="form-control"
                                        value="{{ \App\Models\VoucherConfig::getValue('birthday_voucher', 50000) }}"
                                        min="1000" required>
                                    <div class="form-text">
                                        *Khuyến nghị: Nhập số tiền giảm giá từ 1,000 đến 500,000 VND
                                    </div>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>

                            </div>
                        </form>

                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="card-title mb-0"> Danh sách vouchers </h5>
                        {{-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#discountModal">
                            Cài đặt giảm giá Voucher Sinh Nhật
                        </button>
                        <div class="modal fade" id="discountModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.vouchers.update-discount') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cài đặt giảm giá Voucher Sinh Nhật</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Số tiền giảm giá (VND)</label>
                                                <input
                                                    type="number"
                                                    name="discount"
                                                    class="form-control"
                                                    value="{{ \App\Models\VoucherConfig::getValue('birthday_voucher', 50000) }}"
                                                    min="1000"
                                                    required
                                                >
                                                <small class="form-text text-muted">
                                                    *Khuyến nghị: Nhập số tiền giảm giá từ 1,000 đến 500,000 VND
                                                </small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    @can('Thêm vouchers')
                        <div>
                            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">Thêm mới</a>
                        </div>
                    @endcan



                </div>

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
                    <table id="example" class="table table-bordered dt-responsive nowrap align-middle w-100">
                        <thead class='table-light'>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Mã voucher</th>
                                {{-- <th>Thông tin voucher</th> --}}
                                <th>Tiêu đề</th>
                                <th>Thời gian sử dụng</th>
                                {{-- <th>Thời gian kết thúc</th> --}}
                                <th>Giảm giá</th>
                                <th>Số lượng</th>
                                <th>Giới hạn</th>
                                {{-- <th>Thể loại</th> --}}
                                {{-- <th>Mô tả</th> --}}
                                <th>Hoạt động</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    {{-- <td>{{ $item->id }}</td> --}}
                                    <td>{{ $item->code }}</td>
                                    <td>{{ Str::limit($item->title, 30) }}</td>
                                    {{-- <td class="nav nav-sm flex-column">
                                    --}}{{-- <li class="nav-item mb-2"><span
                                            class="fw-semibold">Mã code:</span> {{ $item->code }}</li> --}}{{--
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Tiêu đề:</span> {{ $item->title }}</li>
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Giảm giá:</span> {{ $item->discount }}</li>
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Số lượng:</span> {{ $item->quantity }}</li>
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Giới hạn:</span> {{ $item->limit }}</li>
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Bắt đầu:</span> {{ $item->start_date_time }}</li>
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Kết thúc:</span> {{ $item->end_date_time }}</li>
                                </td> --}}
                                    {{-- <td class="nav nav-sm flex-column">
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Bắt đầu:</span> {{ $item->start_date_time }}</li>
                                    <li class="nav-item mb-2"><span
                                            class="fw-semibold">Kết thúc:</span> {{ $item->end_date_time }}</li>
                                    </td> --}}
                                    <td class="nav nav-sm flex-column">
                                        <li class="nav-item mb-2">
                                            <span class="fw-semibold">Từ:</span>
                                            {{ \Carbon\Carbon::parse($item->start_date_time)->format('H:i, d/m/Y') }}
                                        </li>
                                        <li class="nav-item mb-2">
                                            <span class="fw-semibold">Đến:</span>
                                            {{ \Carbon\Carbon::parse($item->end_date_time)->format('H:i, d/m/Y') }}
                                        </li>
                                    </td>
                                    <td>{{ $item->discount }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->limit }}</td>
                                    {{-- <td>
                                            @if ($item->type == 1)
                                                Toàn hệ thống
                                            @elseif ($item->type == 2)
                                                    Người dùng
                                            @endif
                                    </td> --}}
                                    {{-- <td>{{ $item->description }}</td> --}}
                                    <td>
                                        @can('Sửa vouchers')
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input switch-is-active changeActive" name="is_active"
                                                    type="checkbox" role="switch" data-voucher-id="{{ $item->id }}"
                                                    @checked($item->is_active)
                                                    onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                            </div>
                                        @else
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input switch-is-active changeActive" name="is_active"
                                                    type="checkbox" disabled readonly role="switch"
                                                    data-voucher-id="{{ $item->id }}" @checked($item->is_active)
                                                    onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                            </div>
                                        @endcan
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('admin.vouchers.show', $item) }}">
                                        <button title="xem" class="btn btn-success btn-sm " type="button"><i
                                                class="fas fa-eye"></i></button>
                                    </a> --}}
                                        @can('Sửa vouchers')
                                            <a href="{{ route('admin.vouchers.edit', $item) }}">
                                                <button title="xem" class="btn btn-warning btn-sm " type="button"><i
                                                        class="fas fa-edit"></i></button>
                                            </a>
                                        @endcan

                                         <form action="{{route('admin.vouchers.destroy', $item)}}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có muốn xóa không')">
                                                <i class="ri-delete-bin-7-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
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
                let voucherId = $(this).data('voucher-id');
                let is_active = $(this).is(':checked') ? 1 : 0;

                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Gửi yêu cầu AJAX để thay đổi trạng thái
                $.ajax({
                    url: '{{ route('vouchers.change-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: voucherId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (response.success) {
                            let row = table.row($(`[data-voucher-id="${voucherId}"]`).closest(
                                'tr'));
                            console.log(row);

                            // Cập nhật cột trạng thái (cột thứ 2) trong dòng này
                            let statusHtml = response.data.is_active ?
                                `<div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input switch-is-active changeActive"
                                        type="checkbox" data-voucher-id="${voucherId}" checked   onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                </div>` :
                                `<div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input switch-is-active changeActive"
                                        type="checkbox" data-voucher-id="${voucherId}"   onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                </div>`;
                            row.cell(row.index(), 6).data(statusHtml).draw(false);
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

                        let checkbox = $(`[data-voucher-id="${voucherId}"]`).closest('tr').find(
                            '.changeActive');
                        checkbox.prop('checked', !is_active);
                    }
                });

                console.log('Đã thay đổi trạng thái active');
            });
        });
    </script>
@endsection
