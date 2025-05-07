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
                        <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Phòng chiếu</a></li>
                        <li class="breadcrumb-item active">Cập nhật</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
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
                                        <label for="name" class="form-label "><span class="text-danger">*</span> Tên
                                            phòng chiếu:</label>
                                        <input type="text" class="form-control" value="{{ $room->name }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="branchId" class="form-label"><span class="text-danger">*</span> Chi
                                            Nhánh</label>
                                        <select class="form-select" id="branchId" name="branch_id"
                                            onchange="loadCinemas()">
                                            <option value="" disabled selected>Chọn chi nhánh</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" @selected($room->branch->id == $branch->id)>
                                                    {{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="cinemaId" class="form-label"><span class="text-danger">*</span> Rạp
                                            Chiếu</label>
                                        <select class="form-select" id="cinemaId" name="cinema_id" required>
                                            @foreach ($cinemas as $cinema)
                                                <option value="{{ $cinema->id }}" @selected($room->cinema->id == $cinema->id)>
                                                    {{ $cinema->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="surcharge" class="form-label "><span class="text-danger">*</span> Loại
                                            phòng chiếu:</label>
                                        <select class="form-select" id="type_room_id" name="type_room_id" required>
                                            @foreach ($typeRooms as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="matrix_id" class="form-label "><span class="text-danger">*</span> Ma
                                            trận ghế:</label>
                                        <select name="" id="" class='form-select' disabled>
                                            @foreach (App\Models\SeatTemplate::MATRIXS as $matrix)
                                                <option value="{{ $matrix['name'] }}">
                                                    {{ $matrix['name'] }}
                                                </option>
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
            <div class="card card-seat ">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Xuất bản</h4>
                </div><!-- end card header -->
                <div class="card-body ">
                    {{-- <table class="table table-borderless   align-middle mb-0">
                        <tbody>
                            <tr>
                                <td>Ghế thường</td>
                                <td class="text-center"> <img src="{{ asset('svg/seat-regular.svg') }}" height="30px">
                                </td>
                            </tr>
                            <tr>
                                <td>Ghế vip</td>
                                <td class="text-center"> <img src="{{ asset('svg/seat-vip.svg') }}" height="30px">
                                </td>
                            </tr>
                            <tr>
                                <td>Ghế đôi</td>
                                <td class="text-center"> <img src="{{ asset('svg/seat-double.svg') }}" height="30px"></td>
                            <tr class="table-active">
                                <th colspan='2' class="text-center">Tổng {{ $seats->count() }} chỗ ngồi</th>

                            </tr>
                        </tbody>
                    </table> --}}

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
                    @php
                        // giả sử $row_seat là dữ liệu trong database
                        $row_seat = [
                            'row_seat_regular' => 4,
                            'row_seat_double' => 0,
                        ];
                        $rowSeatRegular = $row_seat['row_seat_regular'] ?? App\Models\Room::ROW_SEAT_REGULAR; // số hàng ghế thường (bắt buộc phải ở đầu hàng)
                        $rowSeatDouble = $row_seat['row_seat_double'] ?? App\Models\Room::ROW_SEAT_DOUBLE; // số hàng ghế đôi (bắt buộc phải ở cuối hàng)
                        $rowEndSeatVip = $matrixSeat['max_col'] - $rowSeatDouble; // vị trí hàng kết thúc ghế vip
                    @endphp

                    <table class="table-chart-chair table-none align-middle mx-auto text-center mb-5">
                        <thead>
                            <tr>
                                <th></th>
                                @for ($col = 0; $col < $matrixSeat['max_col']; $col++)
                                    <th class="box-item"></th>
                                @endfor
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($row = 0; $row < $matrixSeat['max_row']; $row++)
                                <tr>
                                    <td class="box-item">{{ chr(65 + $row) }}</td>
                                    @for ($col = 0; $col < $matrixSeat['max_col']; $col++)
                                        @if ($row < $rowSeatRegular)
                                            <!-- Ghế thường -->
                                            <td class="box-item-seat border-1 light-orange" data-type-seat="regular"
                                                data-selected="true">
                                                <div class="box-item-seat-selected">
                                                    <img src="{{ asset('svg/seat-regular.svg') }}" class='seat'
                                                        width="100%">
                                                    <input type="hidden" name="seatJsons[]"
                                                        value='{"coordinates_x": {{ $col + 1 }}, "coordinates_y": "{{ chr(65 + $row) }}"}'>
                                                </div>
                                            </td>
                                        @else
                                            @if ($row < $rowEndSeatVip)
                                                <!-- Ghế VIP -->
                                                <td class="box-item-seat border-1 light-blue" data-type-seat="vip"
                                                    data-selected="true">
                                                    <div class="box-item-seat-selected">
                                                        <img src="{{ asset('svg/seat-vip.svg') }}" class='seat'
                                                            width="100%">
                                                        <input type="hidden" name="seatJsons[]"
                                                            value='{"coordinates_x": {{ $col + 1 }}, "coordinates_y": "{{ chr(65 + $row) }}"}'>
                                                    </div>
                                                </td>
                                            @else
                                                <!-- Ghế Đôi -->
                                                <td class="box-item-seat border-1 light-blue" data-type-seat="double"
                                                    data-selected="true">
                                                    <div class="box-item-seat-selected">
                                                        ghế đôi
                                                    </div>
                                                </td>
                                            @endif
                                        @endif
                                    @endfor
                                    <td class="box-item">
                                        <select class="row-dropdown"
                                            onchange="toggleRowSeatsDropdown(this, {{ $row }})">
                                            <option value="no-change"></option>
                                            <option value="select-all">Chọn tất cả</option>
                                            <option value="deselect-all">Bỏ chọn tất cả</option>
                                            <option value="increase-regular">hàng ghế thường</option>
                                        </select>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-seat ">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Chú thích</h4>
                </div><!-- end card header -->
                <div class="card-body ">
                    <table class="table table-borderless   align-middle mb-0">
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
                            <tr class="table-active">
                                <th colspan='2' class="text-center">Tổng {{ $seats->count() }} chỗ ngồi</th>

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 mb-5 text-end">

            <div class="">
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-info">Danh sách</a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-primary mx-2">Cập nhật</a>
            </div>

        </div>
        <!--end col-->
    </div>

@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/mainstyle.css') }}">
@endsection


@section('script-libs')
    <script>
        function loadCinemas() {
            const branchId = document.getElementById('branchId').value;
            const cinemaSelect = document.getElementById('cinemaId');
            cinemaSelect.innerHTML = '<option value="" disabled selected>Chọn rạp chiếu</option>'; // Reset options

            if (branchId) {
                console.log();

                fetch(`http://datn-hn5.me/api/cinemas/${branchId}`)
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
    </script>
    <script>
        // Mảng để lưu trữ nội dung ban đầu cho từng hàng ghế
        let originalContentArray = [];

        // Hàm để lưu trữ nội dung ban đầu của ghế khi trang được tải
        function storeOriginalContent() {
            document.querySelectorAll('.box-item-seat').forEach(function(seat) {
                let seatSelected = seat.querySelector('.box-item-seat-selected');
                let rowIndex = Array.from(seat.parentElement.parentElement.children).indexOf(seat.parentElement);
                if (!originalContentArray[rowIndex]) {
                    originalContentArray[rowIndex] = [];
                }
                originalContentArray[rowIndex].push(seatSelected.innerHTML.trim());
            });
        }

        // Gọi hàm lưu trữ nội dung ban đầu khi trang được tải
        storeOriginalContent();

        // Hàm để xử lý dropdown "Chọn tất cả" hoặc "Bỏ chọn tất cả"
        function toggleRowSeatsDropdown(dropdown, row) {
            const seats = document.querySelectorAll(`tr:nth-child(${row + 1}) .box-item-seat`);
            const selectedOption = dropdown.value;

            seats.forEach(function(seat, index) {
                let seatSelected = seat.querySelector('.box-item-seat-selected');

                if (selectedOption === "select-all") {
                    // Nếu chọn "Chọn tất cả", khôi phục lại dữ liệu ban đầu
                    seatSelected.innerHTML = originalContentArray[row][index];
                } else if (selectedOption === "deselect-all") {
                    // Nếu chọn "Bỏ chọn tất cả", thay đổi thành hình ảnh ghế đã chọn
                    seatSelected.innerHTML =
                        `<img src="{{ asset('svg/seat-add.svg') }}" class='seat' width="60%" >`;
                }
            });
        }


        // Lưu trữ nội dung ban đầu và gán sự kiện cho ghế
        document.querySelectorAll('.box-item-seat').forEach(function(seat) {
            let originalContent = seat.querySelector('.box-item-seat-selected').innerHTML;

            seat.addEventListener('click', function() {
                let seatSelected = seat.querySelector('.box-item-seat-selected');

                if (seatSelected.innerHTML.trim() === originalContent.trim()) {
                    seatSelected.innerHTML =
                        `<img src="{{ asset('svg/seat-add.svg') }}" class='seat' width="60%" >`;
                } else {
                    seatSelected.innerHTML = originalContent;
                }
            });
        });
    </script>
    {{-- <script>
        function toggleRowSeatsDropdown(dropdown, row) {
            const seats = document.querySelectorAll(`tr:nth-child(${row + 1}) .box-item-seat`);
            const selectedOption = dropdown.value;

            if (selectedOption === "increase-regular") {
                // Tăng số hàng ghế thường lên 1
                @php
                    // Kiểm tra số hàng ghế thường có vượt quá tổng số hàng ghế không
                    if ($rowSeatRegular + 1 <= $matrixSeat['max_row'] - $rowSeatDouble) {
                        $rowSeatRegular++; // Tăng giá trị của $rowSeatRegular
                        echo "window.rowSeatRegular = {$rowSeatRegular};";
                    } else {
                        echo "alert('Không thể tăng hàng ghế thường nữa!');"; // Thông báo nếu không thể tăng
                    }
                @endphp
                // Cập nhật giao diện
                updateSeatChart();
            } else {
                seats.forEach(function(seat, index) {
                    let seatSelected = seat.querySelector('.box-item-seat-selected');

                    if (selectedOption === "select-all") {
                        seatSelected.innerHTML = originalContentArray[row][index];
                    } else if (selectedOption === "deselect-all") {
                        seatSelected.innerHTML =
                            `<img src="{{ asset('svg/seat-add.svg') }}" class='seat' width="60%" >`;
                    }
                });
            }
        }



        // Hàm cập nhật giao diện ghế
        function updateSeatChart() {
            const maxRow = {{ $matrixSeat['max_row'] }};
            const maxCol = {{ $matrixSeat['max_col'] }};
            const tableBody = document.querySelector('tbody');

            // Xóa nội dung hiện tại
            tableBody.innerHTML = '';

            for (let row = 0; row < maxRow; row++) {
                let tr = document.createElement('tr');
                let td = document.createElement('td');
                td.className = 'box-item';
                td.innerText = String.fromCharCode(65 + row);
                tr.appendChild(td);

                for (let col = 0; col < maxCol; col++) {
                    let tdSeat = document.createElement('td');
                    let seatSelected = '';

                    if (row < window.rowSeatRegular) {
                        // Ghế thường
                        seatSelected = `<div class="box-item-seat-selected">
                    <img src="{{ asset('svg/seat-regular.svg') }}" class='seat' width="100%">
                    <input type="hidden" name="seatJsons[]" value='{"coordinates_x": ${col + 1}, "coordinates_y": "${String.fromCharCode(65 + row)}"}'>
                </div>`;
                        tdSeat.className = "box-item-seat border-1 light-orange";
                    } else if (row < window.rowSeatRegular + {{ $rowSeatDouble }}) {
                        // Ghế Đôi
                        seatSelected = `<div class="box-item-seat-selected">ghế đôi</div>`;
                        tdSeat.className = "box-item-seat border-1 light-blue";
                    } else {
                        // Ghế VIP
                        seatSelected = `<div class="box-item-seat-selected">
                    <img src="{{ asset('svg/seat-vip.svg') }}" class='seat' width="100%">
                    <input type="hidden" name="seatJsons[]" value='{"coordinates_x": ${col + 1}, "coordinates_y": "${String.fromCharCode(65 + row)}"}'>
                </div>`;
                        tdSeat.className = "box-item-seat border-1 light-blue";
                    }
                    tdSeat.innerHTML = seatSelected;
                    tr.appendChild(tdSeat);
                }

                // Thêm dropdown cho hàng ghế
                let dropdownTd = document.createElement('td');
                dropdownTd.className = 'box-item';
                let newDropdown = document.createElement('select');
                newDropdown.className = 'row-dropdown';
                newDropdown.onchange = function() {
                    toggleRowSeatsDropdown(this, row);
                };
                newDropdown.innerHTML = `
            <option value="no-change"></option>
            <option value="select-all">Chọn tất cả</option>
            <option value="deselect-all">Bỏ chọn tất cả</option>
            <option value="increase-regular">Tăng hàng ghế thường</option>
        `;
                dropdownTd.appendChild(newDropdown);
                tr.appendChild(dropdownTd);

                tableBody.appendChild(tr);
            }
        }
    </script> --}}
@endsection


