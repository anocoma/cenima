@extends('admin.layouts.master')

@section('title')
    Chi tiết phòng chiếu
@endsection

@section('content')
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Chi tiết phòng chiếu</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- thông tin -->
    {{-- <div class="row mb-2">
            <div class="col-md-12">
                @if (session()->has('error'))
                    <div class="alert alert-danger m-3">
                        {{ session()->get('error') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-9">
                <div class="card card-left">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thông tin phòng chiếu</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div class="row ">
                                        <div class="col-md-12 mb-3">

                                            <label for="name" class="form-label ">Tên phòng chiếu:</label>
                                            <input type="text" class="form-control" value="{{ $room->name }}" disabled>

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="branch" class="form-label">Chi nhánh:</label>
                                            <input type="text" class="form-control"
                                                value="{{ $room->cinema->branch->name }}" disabled>
                                        </div>

                                        <div class="col-md-4 mb-3">

                                            <label for="cinema" class="form-label">Rạp chiếu:</label>
                                            <input type="text" class="form-control" value="{{ $room->cinema->name }}"
                                                disabled>

                                        </div>
                                        <div class="col-md-4 mb-3">

                                            <label for="surcharge" class="form-label ">Loại phòng chiếu:</label>
                                            <select name="type_room_id" id="" class="form-select" disabled>
                                                @foreach ($typeRooms as $id => $name)
                                                    <option value="{{ $id }}" @selected($room->type_room_id == $id)>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                        </div>



                                    </div>
                                </div>
                            </div>

                            <!--end row-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label class="form-check-label" for="is_active">Is Active</label>
                                            <div class="form-check form-switch form-switch-default">
                                                <input class="form-check-input" type="checkbox" role=""
                                                    name="is_active" checked>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div> --}}


    <div class="row">

    </div>
    <div class="row">

        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card ">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Thông tin phòng chiếu</h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="row ">
                                            <div class="col-md-12 mb-2">
                                                <label for="name" class="form-label ">Tên phòng chiếu:</label>
                                                <input type="text" class="form-control" value="{{ $room->name }}"
                                                    disabled>
                                            </div>


                                            <div class="col-md-3 mb-2">
                                                <label for="branch" class="form-label">Chi nhánh:</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $room->cinema->branch->name }}" disabled>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="cinema" class="form-label">Rạp chiếu:</label>
                                                <input type="text" class="form-control" value="{{ $room->cinema->name }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="surcharge" class="form-label ">Loại phòng chiếu:</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $room->typeRoom->name }}" disabled>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="surcharge" class="form-label ">Ma trận ghế:</label>
                                                <input type="text" class="form-control" value="{{ $matrixSeat['name'] }}"
                                                    disabled>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <!--end row-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Sơ đồ ghế</h4>
                        </div><!-- end card header -->
                        <div class="card-body mb-3">
                            <div class="srceen w-75 mx-auto mb-4">
                                Màn Hình Chiếu
                            </div>
                            <table class="table-chart-chair table-none align-middle mx-auto text-center">
                                <tbody>
                                    @for ($row = 0; $row < $matrixSeat['max_row']; $row++)
                                        <tr>
                                            {{-- cột hàng ghế A,B,C --}}
                                            <td class="box-item">
                                                {{ chr(65 + $row) }}
                                            </td>
                                            @for ($col = 0; $col < $matrixSeat['max_col']; $col++)
                                                <td class="box-item">
                                                    @foreach ($seats->whereNull('deleted_at') as $seat)
                                                        @if ($seat->coordinates_x === $col + 1 && $seat->coordinates_y === chr(65 + $row))
                                                            @if ($seat->type_seat_id == 1)
                                                                <div class="seat-item ">
                                                                    <img src="{{ $seat->is_active ? asset('svg/seat-regular.svg') : asset('svg/seat-regular-broken.svg') }}"
                                                                        width="100%">
                                                                    <span class="seat-label">{{ $seat->name }}</span>

                                                                </div>
                                                            @else
                                                                <div class="seat-item ">
                                                                    <img src="{{ $seat->is_active ? asset('svg/seat-vip.svg') : asset('svg/seat-regular-vip.svg') }}"
                                                                        width="100%">
                                                                    <span class="seat-label">{{ $seat->name }}</span>

                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-3">
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-seat ">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Chi tiết</h4>
                        </div><!-- end card header -->
                        <div class="card-body ">
                            <div class="row ">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Trạng thái:</label>
                                    <span
                                        class="text-muted">{{ $room->is_publish == 1 ? 'Đã xuất bản' : 'Bản nháp' }}</span>
                                </div>
                                <div class="col-md-12 mb-3 d-flex ">
                                    <label class="form-label">Hoạt động:</label>
                                    <span class="text-muted mx-2">
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input switch-is-active channge-is-active-room"
                                                type="checkbox" role="switch" data-id="{{ $room->id }}"
                                                @checked($room->is_active) disabled>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class='text-end'>
                                <a href="{{ route('admin.rooms.index') }}" class='btn btn-primary mx-1'>Danh sách</a>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-seat ">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Chú thích</h4>
                    </div><!-- end card header -->
                    <div class="card-body ">
                        <table class="table table-borderless   align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td>Ghế hỏng</td>
                                    <td class="text-center"> <img src="{{ asset('svg/seat-regular-broken.svg') }}"
                                            height="30px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ghế thường</td>
                                    <td class="text-center"> <img src="{{ asset('svg/seat-regular.svg') }}"
                                            height="30px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ghế vip</td>
                                    <td class="text-center"> <img src="{{ asset('svg/seat-vip.svg') }}" height="30px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ghế đôi</td>
                                    <td class="text-center"> <img src="{{ asset('svg/seat-double.svg') }}"
                                            height="30px">
                                    </td>
                                </tr>

                                <tr class="table-active">
                                    <th colspan='2' class="text-center">Tổng
                                        {{ $room->seats->whereNull('deleted_at')->where('is_active', true)->count() }}
                                        /
                                        {{ $seats->whereNull('deleted_at')->count() }} chỗ ngồi</th>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/mainstyle.css') }}">
@endsection
