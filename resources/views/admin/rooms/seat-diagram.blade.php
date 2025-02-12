@extends('admin.layouts.master')

@section('title')
    Cập nhật phòng chiếu
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
                <h4 class="mb-sm-0">Quản lý phòng chiếu</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active">Sơ đồ ghế</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-9">
            <div class="card card-left">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Sơ đồ ghế</h4>
                </div><!-- end card header -->
                <div class="card-body mb-3">

                    @if ($room->is_publish == true)
                        <div class="srceen w-75 mx-auto mb-4">
                            Màn Hình Chiếu
                        </div>
                        <form id="seatForm" action="{{ route('admin.rooms.seat-diagram.update', $room) }}" method="POST">
                            @csrf
                            @method('PUT')
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
                                                                <div class="seat-item change-active">
                                                                    <img src="{{ $seat->is_active ? asset('svg/seat-regular.svg') : asset('svg/seat-regular-broken.svg') }}"
                                                                        class="seat" width="100%">
                                                                    <span class="seat-label">{{ $seat->name }}</span>
                                                                    <input type="hidden" class='seat-active'
                                                                        name="seats[{{ $seat->id }}]"
                                                                        value="{{ $seat->is_active }}">
                                                                </div>
                                                            @else
                                                                <div class="seat-item change-active">
                                                                    <img src="{{ $seat->is_active ? asset('svg/seat-vip.svg') : asset('svg/seat-vip-broken.svg') }}"
                                                                        class="seat" width="100%">
                                                                    <span class="seat-label">{{ $seat->name }}</span>
                                                                    <input type="hidden" class='seat-active'
                                                                        name="seats[{{ $seat->id }}]"
                                                                        value="{{ $seat->is_active }}">
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
                        </form>
                    @else
                        @php
                            $scopeRegular = App\Models\Room::SCOPE_REGULAR;
                        @endphp
                        <table class="table-chart-chair table-none align-middle mx-auto text-center mb-5">
                            <thead>
                                <tr></tr>
                            </thead>
                            <tbody>
                                @for ($row = 0; $row < $matrixSeat['max_row']; $row++)
                                    @php
                                        $isAllVip = true;
                                        $isAllRegular = true;
                                        $isAllDouble = true;
                                    @endphp
                                    <tr>
                                        {{-- cột hàng ghế A,B,C --}}
                                        <td class="box-item">
                                            {{ chr(65 + $row) }}
                                        </td>
                                        @for ($col = 0; $col < $matrixSeat['max_col']; $col++)
                                            @foreach ($seats as $seat)
                                                @if ($seat->coordinates_x === $col + 1 && $seat->coordinates_y === chr(65 + $row))
                                                    <td
                                                        class="box-item border-1 {{ $seat->type_seat_id == 1 ? 'light-orange' : 'light-blue' }}">
                                                        <div class="box-item-seat" data-seat-id="{{ $seat->id }}"
                                                            data-seat-row="{{ chr(65 + $row) }}"
                                                            data-seat-type-id="{{ $seat->type_seat_id }}">
                                                            @if ($seat->trashed())
                                                                <img src="{{ asset('svg/seat-add.svg') }}" class='seat'
                                                                    width="60%">
                                                            @else
                                                                @if ($seat->type_seat_id == 1)
                                                                    <img src="{{ asset('svg/seat-regular.svg') }}"
                                                                        class='seat' width="100%">
                                                                @else
                                                                    <img src="{{ asset('svg/seat-vip.svg') }}"
                                                                        class='seat' width="100%">
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                    @php
                                                        if ($seat->type_seat_id != 1) {
                                                            $isAllRegular = false;
                                                        }
                                                        if ($seat->type_seat_id != 2) {
                                                            $isAllVip = false;
                                                        }
                                                        if ($seat->type_seat_id != 3) {
                                                            $isAllDouble = false;
                                                        }
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endfor
                                        <td class='box-item border-1'>
                                            <span data-bs-toggle="offcanvas" data-bs-target="#rowSeat{{ chr(65 + $row) }}">
                                                <i class="fas fa-edit "></i>
                                            </span>

                                            <div class="offcanvas offcanvas-start" tabindex="-1"
                                                id="rowSeat{{ chr(65 + $row) }}">
                                                <div class="offcanvas-header border-bottom">
                                                    <h5 class="offcanvas-title">Chỉnh sửa hàng ghế {{ chr(65 + $row) }}
                                                    </h5>
                                                    <button type="button"
                                                        class="btn-close text-reset"data-bs-dismiss="offcanvas"></button>
                                                </div>
                                                <div class="offcanvas-body">
                                                    <div class="row">
                                                        <!-- Custom Radio Color -->
                                                        <div class="col-md-12 mb-3">
                                                            @if ($row < $scopeRegular['max'])
                                                                {{-- hiển thị input ghế thường ở 1 hàng ghế kế tiếp --}}
                                                                <div class="form-check form-radio-primary mb-3">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="typeSeatRow{{ chr(65 + $row) }}"
                                                                        value="1" @checked($isAllRegular)
                                                                        data-row="{{ chr(65 + $row) }}"
                                                                        @disabled($row < $scopeRegular['min'])>
                                                                    <label class="form-check-label">Ghế thường</label>
                                                                </div>
                                                            @endif
                                                            @if ($row >= $scopeRegular['min'])
                                                                <div class="form-check form-radio-primary mb-3">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="typeSeatRow{{ chr(65 + $row) }}"
                                                                        value="2" @checked($isAllVip)
                                                                        data-row="{{ chr(65 + $row) }}"
                                                                        @disabled($row >= $scopeRegular['max'])>
                                                                    <label class="form-check-label">Ghế vip</label>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 text-center">
                                                            <button class="btn btn-danger btn-remove-all mx-1"
                                                                data-row="{{ chr(65 + $row) }}"><i
                                                                    class="mdi mdi-trash-can-outline me-1"></i>Bỏ tất
                                                                cả</button>
                                                            <button class="btn btn-info btn-restore-all mx-1"
                                                                data-row="{{ chr(65 + $row) }}"><i
                                                                    class="ri-add-line align-bottom me-1"></i>Chọn tất
                                                                cả</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    @endif

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
                                                    type="checkbox" role="switch" data-id="{{ $room->id }}"
                                                    @checked($room->is_active)
                                                    onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái hoạt động ?')">
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class='text-end'>
                                    <a href="{{ route('admin.rooms.index') }}" class='btn btn-light mx-1'>Quay
                                        lại</a>
                                    <button type="button" id="submitFormSeatDiagram" class='btn btn-primary mx-1'>Cập
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
                                <form action="{{ route('admin.rooms.publish', $room) }}" method="post">
                                    @csrf
                                    @method('PUT')
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
                                        <a href="{{ route('admin.rooms.index') }}" class='btn btn-light mx-1'>Lưu
                                            nháp</a>
                                        <button type="submit" class='btn btn-primary mx-1'>Xuất bản</button>
                                    </div>
                                </form>
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
                            @if ($room->is_publish == true)
                                <tbody>
                                    <tr>
                                        <td class="text-muted m-0 p-0" colspan='2'>
                                            **Khi thay đổi trạng thái ghế sẽ không ảnh hưởng đến suất chiếu trước đó.
                                        </td>
                                    </tr>
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
                                        <th colspan='2' class="text-center">Tổng
                                            {{ $room->seats->whereNull('deleted_at')->where('is_active', true)->count() }}
                                            /
                                            {{ $seats->whereNull('deleted_at')->count() }} chỗ ngồi</th>

                                    </tr>

                                </tbody>
                            @else
                                <tbody>
                                    <tr>
                                        <td>Hàng ghế thường</td>
                                        <td class="text-center">
                                            <div class='box-item border light-orange'></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hàng ghế vip</td>
                                        <td class="text-center">
                                            <div class='box-item border light-blue'></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hàng ghế đôi</td>
                                        <td class="text-center">
                                            <div class='box-item border light-pink'></div>
                                        </td>

                                </tbody>
                            @endif

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


@section('script-libs')
    @if ($room->is_publish)
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.channge-is-active-room ').on('change', function() {
                    // Lấy ID của room từ thuộc tính 'data-id'
                    let roomId = $(this).data('id');
                    // Lấy trạng thái hiện tại của checkbox
                    let isActive = $(this).is(':checked') ? 1 : 0;

                    // Gửi yêu cầu AJAX
                    $.ajax({
                        url: '{{ route('rooms.update-active') }}', // URL để cập nhật trạng thái (sẽ tạo sau)
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Bảo vệ CSRF
                            id: roomId,
                            is_active: isActive
                        },
                        success: function(response) {
                            // Hiển thị thông báo thành công
                            if (!response.success) {
                                alert('Có lỗi xảy ra, vui lòng thử lại.');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Xử lý lỗi khi yêu cầu không thành công
                            alert('Lỗi kết nối hoặc server không phản hồi.');
                            console.error(error);
                        }
                    });
                });
            });
        </script>

        <script>
            document.getElementById('submitFormSeatDiagram').addEventListener('click', function() {
                document.getElementById('seatForm').submit();
            });
        </script>

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
                    });
                });
            });
        </script>
    @else
        {{-- xóa mềm và khôi phục trên 1 ghế --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.box-item-seat').forEach(function(seatElement) {
                    seatElement.addEventListener('click', function() {
                        const seatId = seatElement.getAttribute('data-seat-id');
                        const seatType = seatElement.getAttribute('data-seat-type-id');
                        const seatImg = seatElement.querySelector('img.seat');

                        // Kiểm tra xem ghế đang ở trạng thái xóa mềm hay không
                        if (seatImg.src.includes('seat-add.svg')) {
                            // Gửi yêu cầu khôi phục ghế
                            fetch('{{ route('seats.restore') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        seat_id: seatId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Khôi phục ghế (cập nhật lại hình ảnh)
                                        if (seatType == 1) {
                                            seatImg.src = "{{ asset('svg/seat-regular.svg') }}";
                                        } else {
                                            seatImg.src = "{{ asset('svg/seat-vip.svg') }}";
                                        }
                                        seatImg.style.width = "100%";
                                    } else {
                                        alert('Thao tác quá nhanh.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        } else {
                            // Gửi yêu cầu xóa mềm ghế
                            fetch('{{ route('seats.soft-delete') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        seat_id: seatId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Xóa mềm ghế (hiển thị hình ảnh xóa mềm)
                                        seatImg.src = "{{ asset('svg/seat-add.svg') }}";
                                        seatImg.style.width = "60%";
                                    } else {
                                        alert('Thao tác quá nhanh.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });
                });
            });
        </script>

        {{-- xóa mềm và khôi phục trên 1 hàng ghế --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Xử lý khi nhấn "Bỏ tất cả"
                document.querySelectorAll('.btn-remove-all').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const row = button.getAttribute('data-row');

                        fetch('{{ route('seats.soft-delete-row') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    row: row,
                                    room_id: {{ $room->id }}
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Tìm tất cả ghế trong hàng và thay đổi trạng thái
                                    document.querySelectorAll(`[data-seat-row='${row}'] img.seat`)
                                        .forEach(function(seatImg) {
                                            seatImg.src = "{{ asset('svg/seat-add.svg') }}";
                                            seatImg.style.width = "60%";
                                        });
                                } else {
                                    alert('Lỗi: ' + data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                });

                // Xử lý khi nhấn "Chọn tất cả"
                document.querySelectorAll('.btn-restore-all').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const row = button.getAttribute('data-row');

                        fetch('{{ route('seats.restore-row') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    row: row,
                                    room_id: {{ $room->id }}
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Tìm tất cả ghế trong hàng và khôi phục trạng thái
                                    document.querySelectorAll(`[data-seat-row='${row}'] img.seat`)
                                        .forEach(function(seatImg) {
                                            const seatType = seatImg.closest('.box-item-seat')
                                                .getAttribute('data-seat-type-id');
                                            if (seatType == 1) {
                                                seatImg.src =
                                                    "{{ asset('svg/seat-regular.svg') }}";
                                            } else {
                                                seatImg.src =
                                                    "{{ asset('svg/seat-vip.svg') }}";
                                            }
                                            seatImg.style.width = "100%";
                                        });
                                } else {
                                    alert('Lỗi: ' + data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                });
            });
        </script>
        {{-- thay đổi loại ghế trên 1 hàng ghế --}}
        <script>
            document.querySelectorAll('input[name^="typeSeatRow"]').forEach(function(radio) {
                radio.addEventListener('click', function() {
                    const selectedRow = this.getAttribute('data-row'); // Lấy hàng ghế (A, B, C...)
                    const roomId = {{ $room->id }}; // ID của phòng chiếu
                    const typeSeatId = this.value; // Lấy giá trị loại ghế (1: Ghế thường, 2: Ghế VIP)

                    // Gửi yêu cầu AJAX để cập nhật loại ghế trong database
                    fetch("{{ route('seats.update-type') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                row: selectedRow,
                                type_seat_id: typeSeatId,
                                room_id: roomId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Cập nhật giao diện ghế sau khi nhận được phản hồi thành công từ server
                                const seatsInRow = document.querySelectorAll(
                                    `.box-item-seat[data-seat-row="${selectedRow}"]`);
                                seatsInRow.forEach(function(seat) {
                                    seat.setAttribute('data-seat-type-id', typeSeatId);

                                    // Cập nhật hình ảnh và màu sắc ghế
                                    const seatImage = seat.querySelector('img.seat');
                                    seatImage.style.width = '100%'
                                    if (typeSeatId == 2) {
                                        seatImage.src = "{{ asset('svg/seat-vip.svg') }}";
                                        seat.closest('td').classList.remove('light-orange');
                                        seat.closest('td').classList.add('light-blue');
                                    } else {
                                        seatImage.src = "{{ asset('svg/seat-regular.svg') }}";
                                        seat.closest('td').classList.remove('light-blue');
                                        seat.closest('td').classList.add('light-orange');
                                    }
                                });
                            } else {
                                alert('Cập nhật thất bại');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        </script>
    @endif
@endsection
