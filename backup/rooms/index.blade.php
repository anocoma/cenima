@extends('admin.layouts.master')

@section('title')
    Danh sách phòng chiếu
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
                <h4 class="mb-sm-0">Danh sách phòng chiếu</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Danh sách</a></li>
                        <li class="breadcrumb-item active">phòng chiếu</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    @foreach ($branches as $branch)
        @php
            $rooms = App\Models\Room::with('seats')->where('branch_id', $branch->id)->get();
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Chi nhánh {{ $branch->name }}</h5>
                        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary mb-3 ">Thêm mới</a>
                    </div>
                    @if (session()->has('success'))
                        <div class="alert alert-success m-3">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-warning m-3">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <div class="card-body">
                        <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên phòng chiếu</th>
                                    <th>Rạp chiếu</th>
                                    <th>Loại phòng chiếu</th>
                                    <th>Sức chứa</th>
                                    <th>Hoạt động</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as  $room)
                                    <tr>
                                        <td>{{ $room->id }}</td>
                                        <td>{{ $room->name }}</td>
                                        <td>{{ $room->cinema->name }}</td>
                                        <td>{{ $room->typeRoom->name }}</td>
                                        <td>{{ $room->seats->count() }} chỗ ngồi</td>
                                        <td>
                                            {!! $room->is_active == 1
                                                ? '<span class="badge bg-success-subtle text-success text-uppercase">Yes</span>'
                                                : '<span class="badge bg-danger-subtle text-danger text-uppercase">No</span>' !!}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.rooms.show', $room) }}">
                                                <button title="xem" class="btn btn-success btn-sm" type="button"><i
                                                        class="fas fa-eye"></i></button>
                                            </a>
                                            <a href="{{ route('admin.rooms.edit', $room) }}">
                                                <button title="xem" class="btn btn-warning btn-sm " type="button"><i
                                                        class="fas fa-edit"></i></button>
                                            </a>

                                            {{-- <a href="{{ route('admin.rooms.destroy', $room) }}">
                                                <button title="xem" class="btn btn-danger btn-sm " type="button"><i
                                                        class="ri-delete-bin-7-fill"></i></button>
                                            </a> --}}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div><!--end col-->
        </div>
    @endforeach
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
        new DataTable("#example", {
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
    </script>
@endsection
