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
    <!-- Button to trigger modal -->


    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý phòng chiếu
                    @if (Auth::user()->cinema_id != '')
                        - {{ Auth::user()->cinema->name }}
                    @endif
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Phòng chiếu</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="orderList">
                <div class="card-header border-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">Danh sách phòng chiếu</h5>
                        </div>
                        @can('Thêm phòng chiếu')
                            <div class="col-sm-auto">
                                <div class="d-flex gap-1 flex-wrap">
                                    <button class="btn btn-primary mb-3 " data-bs-toggle="modal"
                                        data-bs-target="#createRoomModal">Thêm
                                        mới</button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
                {{-- giao diện bộ lọc, bộ tìm kiếm  --}}


                <div class="card-body pt-0">

                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                        @if (Auth::user()->hasRole('System Admin'))
                            <li class="nav-item">
                                <a class="nav-link  All py-3 {{ session('rooms.selected_tab') === 'all' ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#allRoom" role="tab" aria-selected="true"
                                    data-tab-key='all'>
                                    Tất cả
                                    <span
                                        class="badge bg-dark align-middle ms-1">{{ $rooms->when(Auth::user()->cinema_id, function ($query) {
                                                return $query->where('cinema_id', Auth::user()->cinema_id);
                                            })->count() }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link py-3 isPublish {{ session('rooms.selected_tab') === 'publish' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#isPublish" role="tab" aria-selected="false"
                                data-tab-key='publish'>
                                Đã xuất bản
                                <span
                                    class="badge bg-success align-middle ms-1">{{ $rooms->where('is_publish', true)->when(Auth::user()->cinema_id, function ($query) {
                                            return $query->where('cinema_id', Auth::user()->cinema_id);
                                        })->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 isDraft {{ session('rooms.selected_tab') === 'draft' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#isDraft" role="tab" aria-selected="false"
                                data-tab-key='draft'>
                                Bản nháp<span
                                    class="badge bg-warning align-middle ms-1">{{ $rooms->where('is_publish', false)->when(Auth::user()->cinema_id, function ($query) {
                                            return $query->where('cinema_id', Auth::user()->cinema_id);
                                        })->count() }}</span>
                            </a>
                        </li>
                        @foreach ($cinemas as $cinema)
                            <li class="nav-item">
                                <a class="nav-link py-3 {{ session('rooms.selected_tab') === 'cinema_' . $cinema->id ? 'active' : '' }}"
                                    data-bs-toggle="tab" href="#cinemaID{{ $cinema->id }}" role="tab"
                                    aria-selected="false" data-tab-key='cinema_{{ $cinema->id }}'>
                                    {{ $cinema->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>


                    <div class="card-body tab-content ">
                        {{-- Tất cả ok rồi --}}
                        <div class="tab-pane {{ session('rooms.selected_tab') === 'all' ? 'active' : '' }} " id="allRoom"
                            role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap align-middle w-100" id="tableAllRoom">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th>Phòng chiếu</th>
                                        <th>Rạp chiếu</th>
                                        <th>Loại Phòng</th>
                                        <th>Sức chứa</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rooms as $index => $room)
                                        <tr>
                                            <td>{{ $room->id }}</td>
                                            <td>
                                                <div class='room-name'>
                                                    <div class='mb-1 fs-6'> {{ $room->name }}</div>
                                                    <div>
                                                        <a class=" cursor-pointer text-primary small "
                                                            href="{{ route('admin.rooms.edit', $room) }}">Sơ đồ
                                                            ghế</a>
                                                        @can('Sửa phòng chiếu')
                                                            <a class="cursor-pointer text-info small mx-1 openUpdateRoomModal"
                                                                data-room-id="{{ $room->id }}"
                                                                data-room-name="{{ $room->name }}"
                                                                data-branch-id="{{ $room->branch_id }}"
                                                                data-cinema-id="{{ $room->cinema_id }}"
                                                                data-type-room-id="{{ $room->type_room_id }}"
                                                                data-seat-template-id="{{ $room->seat_template_id }}"
                                                                data-is-publish={{ $room->is_publish }}>Sửa</a>
                                                        @endcan




                                                        {{-- @if (!$room->is_publish || $room->showtimes()->doesntExist())
                                                            @can('Xóa phòng chiếu')
                                                                <a class="cursor-pointer text-danger small"
                                                                    href="{{ route('admin.rooms.destroy', $room) }}"
                                                                    onclick="return confirm('Sau khi xóa sẽ không thể khôi phục, bạn có chắc chắn ?')">Xóa</a>
                                                            @endcan
                                                        @endif --}}


                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $room->cinema->name }}</td>
                                            <td>{{ $room->typeRoom->name }}</td>
                                            @php
                                                $seatActive = App\Models\Seat::getTotalSeat($room->id);
                                                $seatBroken = App\Models\Seat::getTotalSeat($room->id, 0);
                                            @endphp
                                            <td>{{ $seatActive - $seatBroken }}
                                                / {{ $seatActive }} chỗ ngồi</td>
                                            <td>
                                                {!! $room->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input switch-is-active changeActive"
                                                        name="is_active" type="checkbox" role="switch"
                                                        data-id="{{ $room->id }}" @checked($room->is_active)
                                                        onclick="return confirm('Bạn có chắc muốn thay đổi ?')"
                                                        @disabled(!$room->is_publish)>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        {{-- Đã xuất bản --}}
                        <div class="tab-pane {{ session('rooms.selected_tab') === 'publish' ? 'active' : '' }} "
                            id="isPublish" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap align-middle w-100" id="tableIsPublish">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th>Phòng chiếu</th>
                                        <th>Rạp chiếu</th>
                                        <th>Loại Phòng</th>
                                        <th>Sức chứa</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rooms->where('is_publish', true)->when(Auth::user()->cinema_id, function ($query) {
            return $query->where('cinema_id', Auth::user()->cinema_id);
        }) as $index => $room)
                                        <tr>
                                            <td>{{ $room->id }}</td>
                                            <td>
                                                <div class='room-name'>
                                                    <div class='mb-1 fs-6'> {{ $room->name }}</div>
                                                    <div>
                                                        <a class=" cursor-pointer text-primary small "
                                                            href="{{ route('admin.rooms.edit', $room) }}">Sơ đồ
                                                            ghế</a>
                                                        @can('Sửa phòng chiếu')
                                                            <a class="cursor-pointer text-info small mx-1 openUpdateRoomModal"
                                                                data-room-id="{{ $room->id }}"
                                                                data-room-name="{{ $room->name }}"
                                                                data-branch-id="{{ $room->branch_id }}"
                                                                data-cinema-id="{{ $room->cinema_id }}"
                                                                data-type-room-id="{{ $room->type_room_id }}"
                                                                data-seat-template-id="{{ $room->seat_template_id }}"
                                                                data-is-publish={{ $room->is_publish }}>Sửa</a>
                                                        @endcan

                                                        {{-- @if (!$room->is_publish || $room->showtimes()->doesntExist())
                                                            @can('Xóa phòng chiếu')
                                                                <a class="cursor-pointer text-danger small"
                                                                    href="{{ route('admin.rooms.destroy', $room) }}"
                                                                    onclick="return confirm('Sau khi xóa sẽ không thể khôi phục, bạn có chắc chắn ?')">Xóa</a>
                                                            @endcan
                                                        @endif --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $room->cinema->name }}</td>
                                            <td>{{ $room->typeRoom->name }}</td>

                                            @php
                                                $totalSeat = App\Models\Seat::getTotalSeat($room->id);
                                                $seatBroken = App\Models\Seat::getTotalSeat($room->id, 0);
                                            @endphp
                                            <td>{{ $totalSeat - $seatBroken }}
                                                / {{ $totalSeat }} chỗ ngồi</td>
                                            <td>
                                                {!! $room->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input switch-is-active changeActive"
                                                        name="is_active" type="checkbox" role="switch"
                                                        data-id="{{ $room->id }}" @checked($room->is_active)
                                                        onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        {{-- Bản nháp --}}
                        <div class="tab-pane {{ session('rooms.selected_tab') === 'draft' ? 'active' : '' }} "
                            id="isDraft" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap align-middle w-100" id="tableIsDraft">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th>Phòng chiếu</th>
                                        <th>Rạp chiếu</th>
                                        <th>Loại Phòng</th>
                                        <th>Sức chứa</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rooms->where('is_publish', false)->when(Auth::user()->cinema_id, function ($query) {
            return $query->where('cinema_id', Auth::user()->cinema_id);
        }) as $index => $room)
                                        <tr>
                                            <td>{{ $room->id }}</td>
                                            <td>
                                                <div class='room-name'>
                                                    <div class='mb-1 fs-6'> {{ $room->name }}</div>
                                                    <div>
                                                        <a class=" cursor-pointer text-primary small "
                                                            href="{{ route('admin.rooms.edit', $room) }}">Sơ đồ
                                                            ghế</a>
                                                        @can('Sửa phòng chiếu')
                                                            <a class="cursor-pointer text-info small mx-1 openUpdateRoomModal"
                                                                data-room-id="{{ $room->id }}"
                                                                data-room-name="{{ $room->name }}"
                                                                data-branch-id="{{ $room->branch_id }}"
                                                                data-cinema-id="{{ $room->cinema_id }}"
                                                                data-type-room-id="{{ $room->type_room_id }}"
                                                                data-seat-template-id="{{ $room->seat_template_id }}"
                                                                data-is-publish={{ $room->is_publish }}>Sửa</a>
                                                        @endcan

                                                        @if (!$room->is_publish || $room->showtimes()->doesntExist())
                                                            @can('Xóa phòng chiếu')
                                                                <a class="cursor-pointer text-danger small"
                                                                    href="{{ route('admin.rooms.destroy', $room) }}"
                                                                    onclick="return confirm('Sau khi xóa sẽ không thể khôi phục, bạn có chắc chắn ?')">Xóa</a>
                                                            @endcan
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $room->cinema->name }}</td>
                                            <td>{{ $room->typeRoom->name }}</td>
                                            @php
                                                $seatActive = App\Models\Seat::getTotalSeat($room->id);
                                                $seatBroken = App\Models\Seat::getTotalSeat($room->id, 0);
                                            @endphp
                                            <td>{{ $seatActive - $seatBroken }}
                                                / {{ $seatActive }} chỗ ngồi</td>
                                            <td>
                                                {!! $room->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input switch-is-active changeActive"
                                                        name="is_active" type="checkbox" role="switch"
                                                        @checked($room->is_active) disabled>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        {{-- Các Rạp khác ok rồi --}}
                        @foreach ($cinemas as $cinema)
                            <div class="tab-pane {{ session('rooms.selected_tab') === 'cinema_' . $cinema->id ? 'active' : '' }} "
                                id="cinemaID{{ $cinema->id }}" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap align-middle w-100"
                                    id="tableCinemaID{{ $cinema->id }}">
                                    <thead class='table-light'>
                                        <tr>
                                            <th>#</th>
                                            <th>Phòng chiếu</th>
                                            <th>Rạp chiếu</th>
                                            <th>Loại Phòng</th>
                                            <th>Sức chứa</th>
                                            <th>Trạng thái</th>
                                            <th>Hoạt động</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cinema->rooms as $index => $room)
                                            <tr>
                                                <td>{{ $room->id }}</td>
                                                <td>
                                                    <div class='room-name'>
                                                        <div class='mb-1 fs-6'> {{ $room->name }}</div>
                                                        <div>
                                                            <a class=" cursor-pointer text-primary small "
                                                                href="{{ route('admin.rooms.edit', $room) }}">Sơ đồ
                                                                ghế</a>
                                                            @can('Sửa phòng chiếu')
                                                                <a class="cursor-pointer text-info small mx-1 openUpdateRoomModal"
                                                                    data-room-id="{{ $room->id }}"
                                                                    data-room-name="{{ $room->name }}"
                                                                    data-branch-id="{{ $room->branch_id }}"
                                                                    data-cinema-id="{{ $room->cinema_id }}"
                                                                    data-type-room-id="{{ $room->type_room_id }}"
                                                                    data-seat-template-id="{{ $room->seat_template_id }}"
                                                                    data-is-publish={{ $room->is_publish }}>Sửa</a>
                                                            @endcan

                                                            @if (!$room->is_publish)
                                                                @can('Xóa phòng chiếu')
                                                                    <a class="cursor-pointer text-danger small"
                                                                        href="{{ route('admin.rooms.destroy', $room) }}"
                                                                        onclick="return confirm('Sau khi xóa sẽ không thể khôi phục, bạn có chắc chắn ?')">Xóa</a>
                                                                @endcan
                                                            @endif

                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $room->cinema->name }}</td>
                                                <td>{{ $room->typeRoom->name }}</td>
                                                @php
                                                    $seatActive = App\Models\Seat::getTotalSeat($room->id);
                                                    $seatBroken = App\Models\Seat::getTotalSeat($room->id, 0);
                                                @endphp
                                                <td>{{ $seatActive - $seatBroken }}
                                                    / {{ $seatActive }} chỗ ngồi</td>
                                                <td>
                                                    {!! $room->is_publish == 1
                                                        ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                        : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-id="{{ $room->id }}" @checked($room->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')"
                                                            @disabled(!$room->is_publish)>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>


                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--Modal thêm mới phòng chiếu-->
    <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Thêm Phòng Chiếu Mới @if (Auth::user()->cinema_id != '')
                            - {{ Auth::user()->cinema->name }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createRoomForm">
                        @csrf
                        <div class="row">
                            <!-- Tên phòng chiếu -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label"><span class="text-danger">*</span> Tên
                                    Phòng</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Poly 202">
                                <span class="text-danger mt-3" id="createNameError"></span> <!-- Thêm thông báo lỗi -->
                            </div>

                            @if (Auth::user()->hasRole('System Admin'))
                                <div class="col-md-5 mb-3">
                                    <label for="branchId" class="form-label"><span class="text-danger">*</span> Chi
                                        Nhánh</label>
                                    <select class="form-select" id="branchId" name="branch_id"
                                        onchange="loadCinemas('branchId', 'cinemaId')" required>
                                        <option value="" disabled selected>Chọn chi nhánh</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger mt-3" id="createBranchError"></span>
                                    <!-- Thêm thông báo lỗi -->
                                </div>

                                <!-- Chọn Rạp Chiếu -->
                                <div class="col-md-7 mb-3">
                                    <label for="cinemaId" class="form-label"><span class="text-danger">*</span> Rạp
                                        Chiếu</label>
                                    <select class="form-select" id="cinemaId" name="cinema_id" required>
                                        <option value="" disabled selected>Chọn rạp chiếu</option>
                                    </select>
                                    <span class="text-danger mt-3" id="createCinemaError"></span>
                                </div>
                            @endif


                            <div class="col-md-6 mb-3">
                                <label for="type_room_id" class="form-label"><span class="text-danger">*</span> Loại
                                    phòng chiếu</label>
                                <select class="form-select" id="type_room_id" name="type_room_id" required>
                                    @foreach ($typeRooms as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="createTypeRoomError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="seat_template_id" class="form-label"><span class="text-danger">*</span> Mẫu
                                    sơ đồ
                                    ghế</label>
                                <select class="form-select" id="seat_template_id" name="seat_template_id" required>
                                    @foreach ($seatTemplates as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="createSeatTemplateError"></span>
                            </div>
                            <!-- Chọn Chi Nhánh -->

                            <input type="hidden" name="capacity" value='5'> <!-- Giá trị cố định cho capacity -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="createRoomBtn">Thêm mới</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal cập nhật phòng chiếu -->
    <div class="modal fade" id="updateRoomModal" tabindex="-1" aria-labelledby="updateRoomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateRoomModalLabel">Cập Nhật Phòng Chiếu

                        @if (Auth::user()->cinema_id != '')
                            - {{ Auth::user()->cinema->name }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateRoomForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" id="updateRoomId" name="room_id">
                            <div class="col-md-12 mb-3">
                                <label for="updateName" class="form-label"><span class="text-danger">*</span> Tên
                                    Phòng</label>
                                <input type="text" class="form-control" id="updateName" name="name" required
                                    placeholder="Poly 202">
                                <span class="text-danger mt-3" id="updateNameError"></span>
                            </div>
                            @if (Auth::user()->hasRole('System Admin'))
                                <div class="col-md-5 mb-3">
                                    <label for="updateBranchId" class="form-label"><span class="text-danger">*</span> Chi
                                        Nhánh</label>
                                    <select class="form-select" id="updateBranchId" name="branch_id"
                                        onchange="loadCinemas('updateBranchId', 'updateCinemaId')" required>
                                        <option value="" disabled selected>Chọn chi nhánh</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger mt-3" id="updateBranchError"></span>
                                </div>

                                <div class="col-md-7 mb-3">
                                    <label for="updateCinemaId" class="form-label"><span class="text-danger">*</span> Rạp
                                        Chiếu</label>
                                    <select class="form-select" id="updateCinemaId" name="cinema_id" required>
                                        <option value="" disabled selected>Chọn rạp chiếu</option>
                                    </select>
                                    <span class="text-danger mt-3" id="updateCinemaError"></span>
                                </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <label for="updateTypeRoomId" class="form-label"><span class="text-danger">*</span> Loại
                                    phòng chiếu</label>
                                <select class="form-select" id="updateTypeRoomId" name="type_room_id" required>
                                    @foreach ($typeRooms as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="updateTypeRoomError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updateSeatTemplateId" class="form-label"><span class="text-danger">*</span>
                                    Mẫu sơ đồ
                                    ghế</label>
                                <select class="form-select" id="updateSeatTemplateId" name="seat_template_id" required>
                                    @foreach ($seatTemplates as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="updateSeatTemplateError"></span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateRoomBtn">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script-libs')
    <script>
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabKey = this.getAttribute('data-tab-key');
                fetch('{{ route('admin.rooms.selected-tab') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            tab_key: tabKey
                        })
                    }).then(response => response.json())
                    .then(data => console.log('Tab saved:', data));
            });
        });
    </script>
    {{-- Hàm load các rạp chiếu khi chọn chi nhánh & modal create rạp chiếu --}}
    <script>
        function loadCinemas(branchIdElementId, cinemaSelectElementId, selectedCinemaId = null) {
            const branchId = document.getElementById(branchIdElementId).value;
            const cinemaSelect = document.getElementById(cinemaSelectElementId);
            cinemaSelect.innerHTML = '<option value="" disabled selected>Chọn rạp chiếu</option>'; // Reset options

            if (branchId) {
                const url = APP_URL + `/api/cinemas/${branchId}`;
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.length > 0) {
                            data.forEach(cinema => {
                                const option = document.createElement('option');
                                option.value = cinema.id;
                                option.textContent = cinema.name;
                                cinemaSelect.appendChild(option);
                            });

                            // Nếu có cinemaId đã chọn, chọn nó trong danh sách
                            if (selectedCinemaId) {
                                cinemaSelect.value = selectedCinemaId;
                            }
                        } else {
                            cinemaSelect.innerHTML +=
                                '<option value="" disabled selected>Không có rạp chiếu nào</option>';
                        }
                    })
                    .catch(error => console.error('Error loading cinemas:', error));
            } else {
                cinemaSelect.innerHTML = '<option value="" disabled selected>Chọn rạp chiếu</option>';
            }
        }

        document.getElementById('createRoomBtn').addEventListener('click', function(event) {
            const form = document.getElementById('createRoomForm');
            const formData = new FormData(form);
            let hasErrors = false; // Biến để theo dõi có lỗi hay không
            const url = APP_URL + `/api/rooms`
            fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        // Nếu có lỗi (400, 422, 500, ...), chuyển đến phần xử lý lỗi
                        return response.json().then(errorData => {
                            handleErrors(errorData.error, 'create'); // Gọi hàm xử lý lỗi
                            hasErrors = true; // Đánh dấu có lỗi
                        });
                    }
                    return response.json(); // Chuyển đổi phản hồi thành JSON
                })
                .then(data => {
                    if (!hasErrors) { // Chỉ đóng modal và reset form khi không có lỗi
                        console.log(data);
                        $('#createRoomModal').modal('hide');
                        form.reset();
                        window.location.href =
                            `${APP_URL}/admin/rooms/edit/${data.room.id}`; // Sử dụng room.id vừa thêm
                    }
                })
                .catch(error => console.error('Error adding room:', error));
        });
        // Hàm để mở modal phòng chiếu
        document.querySelectorAll('.openUpdateRoomModal').forEach(button => {
            button.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id'); // Lấy roomId từ data attribute
                const roomName = this.getAttribute('data-room-name');
                @if (Auth::user()->hasRole('System Admin'))
                    const branchId = this.getAttribute('data-branch-id');
                    const cinemaId = this.getAttribute('data-cinema-id');
                    document.getElementById('updateBranchId').value = branchId;

                    // Tải danh sách rạp chiếu và chọn rạp đã chọn
                    loadCinemas('updateBranchId', 'updateCinemaId', cinemaId);
                @endif

                const typeRoomId = this.getAttribute('data-type-room-id');
                const seatTemplateId = this.getAttribute('data-seat-template-id');
                const isPublish = this.getAttribute('data-is-publish');

                // Điền dữ liệu vào modal
                document.getElementById('updateRoomId').value = roomId; // Gán giá trị roomId
                document.getElementById('updateName').value = roomName;


                document.getElementById('updateTypeRoomId').value = typeRoomId;
                document.getElementById('updateSeatTemplateId').value = seatTemplateId;
                if (isPublish == 1) {
                    // Chỉ cho phép nhập tên, các trường khác disabled
                    @if (Auth::user()->hasRole('System Admin'))
                    document.getElementById('updateBranchId').disabled = true;
                    document.getElementById('updateCinemaId').disabled = true;
                    @endif
                    document.getElementById('updateTypeRoomId').disabled = true;
                    document.getElementById('updateSeatTemplateId').disabled = true;
                } else {
                    // Nếu chưa publish, cho phép chỉnh sửa tất cả
                    @if (Auth::user()->hasRole('System Admin'))
                    document.getElementById('updateBranchId').disabled = false;
                    document.getElementById('updateCinemaId').disabled = false;
                    @endif
                    document.getElementById('updateTypeRoomId').disabled = false;
                    document.getElementById('updateSeatTemplateId').disabled = false;
                }

                // Mở moda
                $('#updateRoomModal').modal('show');
            });
        });

        // Hàm để cập nhật thông tin phòng chiếu
        document.getElementById('updateRoomBtn').addEventListener('click', function(event) {
            const form = document.getElementById('updateRoomForm');
            const formData = new FormData(form);
            console.log([...formData]);
            const roomId = document.getElementById('updateRoomId').value; // Lấy ID phòng từ hidden input
            let hasErrors = false; // Biến để theo dõi có lỗi hay không
            const url = APP_URL + `/api/rooms/${roomId}`; // URL cập nhật phòng chiếu

            fetch(url, {
                    method: 'POST',
                    body: formData,

                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            handleErrors(errorData.error, 'update'); // Gọi hàm xử lý lỗi
                            hasErrors = true; // Đánh dấu có lỗi
                        });
                    }
                    return response.json(); // Chuyển đổi phản hồi thành JSON
                })
                .then(data => {
                    if (!hasErrors) {
                        console.log(data);
                        $('#updateRoomModal').modal('hide');
                        form.reset();
                        location.reload();
                    }

                })
                .catch(error => console.error('Error updating room:', error));
        });


        // Hàm để hiển thị lỗi xác thực
        function handleErrors(errors, prefix) {
            // Reset thông báo lỗi trước đó
            document.getElementById(`${prefix}NameError`).innerText = '';
            @if (Auth::user()->hasRole('System Admin'))
                document.getElementById(`${prefix}BranchError`).innerText = '';
                document.getElementById(`${prefix}CinemaError`).innerText = '';
                if (errors.branch_id) {
                    document.getElementById(`${prefix}BranchError`).innerText = errors.branch_id.join(', ');
                }
                if (errors.cinema_id) {
                    document.getElementById(`${prefix}CinemaError`).innerText = errors.cinema_id.join(', ');
                }
            @endif
            document.getElementById(`${prefix}SeatTemplateError`).innerText = '';
            document.getElementById(`${prefix}TypeRoomError`).innerText = '';

            // Kiểm tra và hiển thị lỗi cho từng trường
            if (errors.name) {
                document.getElementById(`${prefix}NameError`).innerText = errors.name.join(', ');
            }

            if (errors.seat_template_id) {
                document.getElementById(`${prefix}SeatTemplateError`).innerText = errors.seat_template_id.join(', ');
            }
            if (errors.type_room_id) {
                document.getElementById(`${prefix}TypeRoomError`).innerText = errors.type_room_id.join(', ');
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- cập nhật active phòng chiếu --}}


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
            // Khởi tạo DataTable cho các bảng cố định
            let tableAllRoom = new DataTable("#tableAllRoom", {
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

            let tableIsPublish = new DataTable("#tableIsPublish", {
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

            let tableIsDraft = new DataTable("#tableIsDraft", {
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

            // Khởi tạo đối tượng cinemaTables để chứa các DataTable của các cinema
            let cinemaTables = {};

            // Lặp qua tất cả các cinema và khởi tạo DataTable cho mỗi cinema
            @foreach ($cinemas as $cinema)
                cinemaTables["tableCinemaID{{ $cinema->id }}"] = new DataTable(
                    "#tableCinemaID{{ $cinema->id }}", {
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
            @endforeach

            // Xử lý sự kiện change cho checkbox .changeActive
            $(document).on('change', '.changeActive', function() {
                let roomId = $(this).data('id');
                let is_active = $(this).is(':checked') ? 1 : 0;
                let tableId = $(this).closest('table').attr('id'); // Lấy ID của bảng


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
                    url: '{{ route('rooms.update-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: roomId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (response.success) {
                            let table;

                            // Kiểm tra bảng cố định
                            if (tableId === 'tableAllRoom') {
                                table = tableAllRoom;
                            } else if (tableId === 'tableIsPublish') {
                                table = tableIsPublish;
                            } else if (tableId === 'tableIsDraft') {
                                table = tableIsDraft;
                            }
                            // Kiểm tra bảng động cho cinema
                            else if (cinemaTables[tableId]) {
                                table = cinemaTables[tableId];
                            }

                            // Nếu tìm được table, tiến hành cập nhật trạng thái
                            if (table) {
                                let statusHtml = response.data.is_active ?
                                    `<div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input switch-is-active changeActive"
                                            type="checkbox" data-id="${roomId}" checked onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                    </div>` :
                                    `<div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input switch-is-active changeActive"
                                            type="checkbox" data-id="${roomId}" onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                    </div>`;

                                updateStatusInTable(table, roomId, statusHtml);

                                // Cập nhật trạng thái cho các bảng còn lại
                                if (tableId !== 'tableAllRoom') {
                                    updateStatusInTable(tableAllRoom, roomId, statusHtml);
                                }
                                if (tableId !== 'tableIsPublish') {
                                    updateStatusInTable(tableIsPublish, roomId, statusHtml);
                                }
                                if (tableId !== 'tableIsDraft') {
                                    updateStatusInTable(tableIsDraft, roomId, statusHtml);
                                }

                                // Cập nhật các bảng cho các cinema trong vòng lặp
                                @foreach ($cinemas as $cinema)
                                    if (tableId !== 'tableCinemaID{{ $cinema->id }}') {
                                        updateStatusInTable(cinemaTables[
                                                "tableCinemaID{{ $cinema->id }}"],
                                            roomId, statusHtml);
                                    }
                                @endforeach

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: 'Trạng thái hoạt động đã được cập nhật.',
                                    confirmButtonText: 'Đóng',
                                    timer: 3000,
                                    timerProgressBar: true,

                                });
                            }
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

                        let checkbox = $(`[data-id="${roomId}"]`).closest('tr').find(
                            '.changeActive');
                        checkbox.prop('checked', !is_active);
                    }
                });

            });

            // Hàm cập nhật trạng thái trong bảng
            function updateStatusInTable(table, roomId, statusHtml) {
                // Cập nhật trạng thái trong bảng
                table.rows().every(function() {
                    let row = this.node();
                    let rowId = $(row).find('.changeActive').data('id');
                    if (rowId === roomId) {
                        table.cell(row, 6).data(statusHtml).draw(false);
                    }
                });
            }
        });
    </script>
@endsection
