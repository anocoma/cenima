@extends('admin.layouts.master')

@section('title')
    Quản lý tài khoản
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
                <h4 class="mb-sm-0">Quản lý tài khoản</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tài khoản</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
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
                    <h5 class="card-title mb-0">Danh sách tài khoản</h5>

                    @can('Thêm tài khoản')
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3 ">Thêm mới</a>
                    @endcan
                </div>
                @if (session()->has('success'))
                    <div class="alert alert-success m-3">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link py-3 active isPublish" data-bs-toggle="tab" href="#admin" role="tab"
                                aria-selected="false">
                                Quản trị viên

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 isDraft" data-bs-toggle="tab" href="#users" role="tab"
                                aria-selected="false">
                                Khách hàng

                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link py-3 isDraft" data-bs-toggle="tab" href="#usersLock" role="tab"
                                aria-selected="false">
                                Tài khoản bị khóa

                            </a>
                        </li> --}}

                    </ul>

                    <div class="card-body tab-content ">
                
                        <div class="tab-pane active" id="admin" role="tabpanel">

                            <table class="table table-bordered dt-responsive nowrap align-middle w-100" id="tableAdmin">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th>Họ và tên</th>
                                        <th>Hình ảnh</th>
                                        <th>Email</th>
                                        {{-- <th>Số điện thoại</th> --}}
                                        {{-- <th>Ngày sinh</th> --}}

                                        {{-- <th>Chức danh</th> --}}
                                        <th>Vai trò</th>
                                        <th>Tại</th>
                                        <th>Chức năng</th>
                                    </tr>
                                <tbody>
                                    @foreach ($admin as $i => $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center">
                                                @php
                                                    $url = $item->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }

                                                @endphp

                                                @if (!empty($item->img_thumbnail))
                                                    <img src="{{ $url }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image "
                                                        alt="user-profile-image">
                                                @else
                                                    <img class="avatar-sm rounded"
                                                        src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/user-dummy-img.jpg"
                                                        alt="Header Avatar">
                                                @endif

                                            </td>
                                            <td>
                                                {{ $item->email }}
                                            </td>
                                            {{-- <td>{{ $item->phone }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->birthday)->format('d/m/Y') ?? 'null' }}</td> --}}

                                            {{-- <td>
                                                @if ($item->type == App\Models\User::TYPE_ADMIN)
                                                    <span class="badge badge-gradient-success">Quản trị viên</span>
                                                @else
                                                    <span class="badge rounded-pill bg-primary-subtle text-primary">Khách
                                                        hàng</span>
                                                @endif
                                            </td> --}}
                                            <td>
                                                @if ($item->roles->isNotEmpty())
                                                    @foreach ($item->roles as $role)
                                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                @endif
                                                
                                            </td>
                                            <td>
                                                @if ($item->cinema != '')
                                                    {{ $item->cinema->name }}
                                                @else
                                                    Tất cả
                                                @endif
                                            </td>


                                            <td>
                                                <div class="d-flex ">
                                                    @can('Xem chi tiết tài khoản')
                                                        <a href="{{ route('admin.users.show', $item) }}">
                                                            <button title="xem" class="btn btn-success btn-sm "
                                                                type="button"><i class="fas fa-eye"></i></button></a>
                                                    @endcan

                                                    @if ($item->name != 'System Admin')
                                                        <a href="{{ route('admin.users.edit', $item) }}">
                                                            <button title="xem" class="btn btn-warning btn-sm mx-1 "
                                                                type="button"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                        {{-- <form action="{{ route('admin.users.destroy', $item) }}"
                                                            method="POST" class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có muốn xóa không')">
                                                                <i class="ri-delete-bin-7-fill"></i>
                                                            </button>
                                                        </form> --}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>

                        {{-- table khách hàng --}}
                        <div class="tab-pane " id="users" role="tabpanel">

                            <table class="table table-bordered dt-responsive nowrap align-middle w-100" id="tableUsers">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th>Họ và tên</th>
                                        <th>Hình ảnh</th>
                                        <th>Email</th>
                                        {{-- <th>Số điện thoại</th> --}}
                                        {{-- <th>Ngày sinh</th> --}}

                                        <th>Chức danh</th>

                                        <th>Chức năng</th>
                                    </tr>
                                <tbody>
                                    @foreach ($users as $i => $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td class="text-center">
                                                @php
                                                    $url = $user->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }

                                                @endphp

                                                @if (!empty($user->img_thumbnail))
                                                    <img src="{{ $url }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image "
                                                        alt="user-profile-image">
                                                @else
                                                    <img class="avatar-sm rounded"
                                                        src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/user-dummy-img.jpg"
                                                        alt="Header Avatar">
                                                @endif

                                            </td>
                                            <td>{{ $user->email }}
                                            </td>
                                            {{-- <td>{{ $user->phone }}</td>
                                    <td>{{ Carbon\Carbon::parse($user->birthday)->format('d/m/Y') ?? 'null' }}</td> --}}

                                            <td>
                                                @if ($user->type == App\Models\User::TYPE_ADMIN)
                                                    <span class="badge badge-gradient-success">Quản trị viên</span>
                                                @else
                                                    <span class="badge rounded-pill bg-primary-subtle text-primary">Khách
                                                        hàng</span>
                                                @endif
                                            </td>



                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('admin.users.show', $user) }}">
                                                        <button title="xem" class="btn btn-success btn-sm "
                                                            type="button"><i class="fas fa-eye"></i></button></a>
                                                    {{-- @if ($user->name != 'System Admin')
                                                        <a href="{{ route('admin.users.edit', $user) }}">
                                                            <button title="xem" class="btn btn-warning btn-sm mx-1 "
                                                                type="button"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                                            method="POST" class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có muốn xóa không')">
                                                                <i class="ri-delete-bin-7-fill"></i>
                                                            </button>
                                                        </form>
                                                    @endif --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
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
        new DataTable("#tableAdmin", {
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

        new DataTable("#tableUsers", {
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
