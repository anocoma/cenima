@extends('admin.layouts.master')

@section('title')
    Cập nhật phòng chiếu
@endsection

@section('content')
    <form id="seatForm" action="{{ route('admin.rooms.update', $room) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý phòng chiếu</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Cập nhật</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-9">
                <div class="row">
                    {{-- <div class="col-lg-12">
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
                                                    name='name'>
                                            </div>

                                            <div class="col-md-3 mb-2">
                                                <label for="branchId" class="form-label"><span class="text-danger">*</span>
                                                    Chi Nhánh</label>
                                                <select class="form-select" id="branchId" name="branch_id"
                                                    onchange="loadCinemas('branchId', 'cinemaId')" required>
                                                    <option value="" disabled selected>Chọn chi nhánh</option>
                                                    @foreach ($branches as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Chọn Rạp Chiếu -->
                                            <div class="col-md-3 mb-2">
                                                <label for="cinemaId" class="form-label"><span class="text-danger">*</span>
                                                    Rạp Chiếu</label>
                                                <select class="form-select" id="cinemaId" name="cinema_id">
                                                    <option value="" disabled selected>Chọn rạp chiếu</option>
                                                </select>

                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="type_room_id" class="form-label"><span
                                                        class="text-danger">*</span> Loại phòng chiếu</label>
                                                <select class="form-select" id="type_room_id" name="type_room_id" required>
                                                    @foreach ($typeRooms as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="seat_template_id" class="form-label"><span
                                                        class="text-danger">*</span> Mẫu sơ đồ ghế</label>
                                                <select class="form-select" id="seat_template_id" name="seat_template_id"
                                                    required>
                                                    @foreach ($seatTemplates as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
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
                </div> --}}
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
                                                    @php
                                                        $seat =
                                                            isset($seatMap[chr(65 + $row)]) &&
                                                            isset($seatMap[chr(65 + $row)][$col + 1])
                                                                ? $seatMap[chr(65 + $row)][$col + 1]
                                                                : null;

                                                    @endphp

                                                    @if ($seat && $seat->type_seat_id == 3)
                                                        <!-- Nếu là ghế đôi -->
                                                        <td class="box-item" colspan="2">
                                                            <div class="seat-item change-active">
                                                                <!-- 3 cho ghế đôi -->
                                                                <img src="{{ $seat->is_active == 1 ? asset('svg/seat-double.svg') : asset('svg/seat-double-broken.svg') }}"
                                                                    class='seat' width="100%">
                                                                <span
                                                                    class="seat-label-double">{{ chr(65 + $row) . ($col + 1) }}
                                                                    {{ chr(65 + $row) . ($col + 2) }}</span>
                                                                <input type="hidden" class='seat-active'
                                                                    name="seats[{{ $seat->id }}]"
                                                                    value="{{ $seat->is_active }}">
                                                            </div>
                                                        </td>
                                                        <td class="box-item" style="display: none;">
                                                            <div class="seat-item">
                                                                <img src="{{ asset('svg/seat-add.svg') }}" class='seat'
                                                                    width="60%">
                                                            </div>
                                                        </td>
                                                        @php $col++; @endphp
                                                    @else
                                                        <td class="box-item">
                                                            <div class="seat-item change-active">
                                                                @switch($seat->type_seat_id ?? "")
                                                                    @case(1)
                                                                        <img src="{{ $seat->is_active == 1 ? asset('svg/seat-regular.svg') : asset('svg/seat-regular-broken.svg') }}"
                                                                            class='seat' width="100%">
                                                                        <span
                                                                            class="seat-label">{{ chr(65 + $row) . $col + 1 }}</span>
                                                                        <input type="hidden" class='seat-active'
                                                                            name="seats[{{ $seat->id }}]"
                                                                            value="{{ $seat->is_active }}">
                                                                    @break

                                                                    @case(2)
                                                                        <img src="{{ $seat->is_active == 1 ? asset('svg/seat-vip.svg') : asset('svg/seat-vip-broken.svg') }}"
                                                                            class='seat' width="100%">
                                                                        <span
                                                                            class="seat-label">{{ chr(65 + $row) . $col + 1 }}</span>
                                                                        <input type="hidden" class='seat-active'
                                                                            name="seats[{{ $seat->id }}]"
                                                                            value="{{ $seat->is_active }}">
                                                                    @break
                                                                @endswitch

                                                            </div>
                                                        </td>
                                                    @endif
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
                        @if ($room->is_publish == 1)
                            <div class="card card-seat ">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Cập nhật</h4>
                                </div><!-- end card header -->
                                <div class="card-body ">
                                    <div class="row ">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Trạng thái:</label>
                                            <span class="text-muted">Đã xuất bản</span>
                                        </div>
                                        <div class="col-md-12 mb-3 d-flex ">
                                            <label class="form-label">Hoạt động:</label>
                                            <span class="text-muted mx-2">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input switch-is-active channge-is-active-room"
                                                        type="checkbox" role="switch" @checked($room->is_active)
                                                        name='is_active'>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    <div class='text-end'>
                                        <a href="{{ route('admin.rooms.index') }}" class='btn btn-light mx-1'>Quay
                                            lại</a>
                                        <button type="submit" class='btn btn-primary mx-1'>Cập
                                            nhật</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card card-seat ">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Xuất bản</h4>
                                </div><!-- end card header -->
                                <div class="card-body ">
                                    <div class="row ">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Trạng thái:</label>
                                            <span class="text-muted">Bản nháp</span>
                                        </div>
                                        <div class="col-md-12 mb-3 ">
                                            <label class="form-label">Hoạt động:</label>
                                            <span class="text-muted">Chưa hoạt động</span>
                                        </div>
                                    </div>
                                    <div class='text-end'>
                                        <button type="submit" name='action' value="draft" class='btn btn-light'>Lưu
                                            nháp</button>
                                        <button type="submit" name='action' value="publish"
                                            class='btn btn-primary mx-1'>Xuất bản</button>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                    @if ($room->is_publish)
                                        <tr>
                                            <td class="text-muted m-0 p-0" colspan='2'>
                                                **Khi thay đổi trạng thái ghế sẽ không ảnh hưởng đến suất chiếu trước đó.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ghế hỏng</td>
                                            <td class="text-center"> <img
                                                    src="{{ asset('svg/seat-regular-broken.svg') }}" height="30px">
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Ghế thường</td>
                                        <td class="text-center"> <img src="{{ asset('svg/seat-regular.svg') }}"
                                                height="30px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ghế vip</td>
                                        <td class="text-center"> <img src="{{ asset('svg/seat-vip.svg') }}"
                                                height="30px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ghế đôi</td>
                                        <td class="text-center"> <img src="{{ asset('svg/seat-double.svg') }}"
                                                height="30px">
                                        </td>
                                    </tr>

                                    <tr class="table-active">
                                        <th colspan='2' class="text-center">
                                            @if ($room->is_publish)
                                                Tổng
                                                {{ $totalSeat - $seatBroken }}
                                                /
                                                {{ $totalSeat }} chỗ ngồi
                                            @else
                                                Tổng {{ $totalSeat  }} chỗ ngồi
                                            @endif
                                        </th>

                                    </tr>

                                </tbody>


                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/mainstyle.css') }}">
@endsection


@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @if ($room->is_publish)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Lắng nghe sự kiện click vào các phần tử có class "change-active"
                document.querySelectorAll('.change-active').forEach(function(seatElement) {
                    seatElement.addEventListener('click', function() {
                        // Tìm phần tử img bên trong .change-active
                        var seatImage = seatElement.querySelector('img');
                        var seatInput = seatElement.querySelector('.seat-active');

                        // Đặt biến chứa đường dẫn của ảnh hiện tại
                        var currentImage = seatImage.src;
                        var regularSeat = "{{ asset('svg/seat-regular.svg') }}";
                        var brokenSeat = "{{ asset('svg/seat-regular-broken.svg') }}";
                        var vipSeat = "{{ asset('svg/seat-vip.svg') }}";
                        var vipBrokenSeat = "{{ asset('svg/seat-vip-broken.svg') }}";
                        var doubleSeat = "{{ asset('svg/seat-double.svg') }}";
                        var doubleBrokenSeat = "{{ asset('svg/seat-double-broken.svg') }}";

                        // Kiểm tra và thay đổi hình ảnh khi nhấn vào ghế thường
                        if (currentImage.includes('seat-regular.svg')) {
                            seatImage.src = brokenSeat;
                            seatInput.value = 0; // Cập nhật trạng thái là không hoạt động
                        } else if (currentImage.includes('seat-regular-broken.svg')) {
                            seatImage.src = regularSeat;
                            seatInput.value = 1; // Cập nhật trạng thái là hoạt động
                        }

                        // Kiểm tra và thay đổi hình ảnh khi nhấn vào ghế VIP
                        if (currentImage.includes('seat-vip.svg')) {
                            seatImage.src = vipBrokenSeat;
                            seatInput.value = 0; // Cập nhật trạng thái là không hoạt động
                        } else if (currentImage.includes('seat-vip-broken.svg')) {
                            seatImage.src = vipSeat;
                            seatInput.value = 1; // Cập nhật trạng thái là hoạt động
                        }

                        // Kiểm tra và thay đổi hình ảnh khi nhấn vào ghế đôi
                        if (currentImage.includes('seat-double.svg')) {
                            seatImage.src = doubleBrokenSeat;
                            seatInput.value = 0; // Cập nhật trạng thái là không hoạt động
                        } else if (currentImage.includes('seat-double-broken.svg')) {
                            seatImage.src = doubleSeat;
                            seatInput.value = 1; // Cập nhật trạng thái là hoạt động
                        }
                    });
                });
            });
        </script>
    @endif
@endsection
