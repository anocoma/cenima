@extends('admin.layouts.master')

@section('title')
    Danh sách Vai trò
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
                <h4 class="mb-sm-0">Vai trò</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Danh sách</a></li>
                        <li class="breadcrumb-item active">Vai trò</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Danh sách Vai trò</h5>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3 ">Thêm mới</a>
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
                    <table id="example" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class='table-light'>
                            <tr>
                                <th>ID</th>
                                <th>Tên vai trò</th>
                                <th>Quyền hạn</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @if ($item->permissions->isNotEmpty())
                                            @foreach ($item->permissions->take(3) as $permission)
                                                <span
                                                    class="badge bg-success-subtle text-success text-uppercase">{{ $permission->name }}</span>
                                            @endforeach
                                            @if ($item->permissions->count() > 3)
                                                <span
                                                    class="badge bg-info-subtle text-info text-uppercase">+{{ $item->permissions->count() - 3 }}
                                                    quyền khác</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger-subtle text-danger text-uppercase">Không có
                                                quyền</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->name != 'System Admin')
                                            <div class="d-flex">

                                                <a class="mx-1" href="{{ route('admin.roles.edit', $item) }}">
                                                    <button title="" class="btn btn-warning btn-sm " type="button"><i
                                                            class="fas fa-edit"></i></button>
                                                </a>
                                                {{-- Xóa --}}

                                                <form action="{{ route('admin.roles.destroy', $item) }}" method="post"
                                                    class="d-inline-block">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn chắc chắn muốn xóa không?')"><i
                                                            class="ri-delete-bin-7-fill"></i></button>
                                                </form>
                                            </div>
                                        @endif
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
