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
                <h4 class="mb-sm-0">Quản lý phòng chiếu</h4>

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
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Danh sách phòng chiếu</h5>
                    {{-- <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary mb-3 ">Thêm mới</a> --}}
                    <button class="btn btn-primary mb-3 " data-bs-toggle="modal" data-bs-target="#createRoomModal">Thêm
                        mới</button>
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
                    <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle">
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
                            @foreach ($rooms as $room)
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
        </div>
    </div>

    <!--Modal thêm mới phòng chiếu-->
    <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Thêm Phòng Chiếu Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createRoomForm">
                        @csrf
                        <div class="row">
                            <!-- Tên phòng chiếu -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label"><span class="text-danger">*</span> Tên Phòng</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Poly 202">
                                <span class="text-danger mt-3" id="nameError"></span> <!-- Thêm thông báo lỗi -->
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="branchId" class="form-label"><span class="text-danger">*</span> Chi Nhánh</label>
                                <select class="form-select" id="branchId" name="branch_id" onchange="loadCinemas()"
                                    required>
                                    <option value="" disabled selected>Chọn chi nhánh</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="branchError"></span> <!-- Thêm thông báo lỗi -->
                            </div>

                            <!-- Chọn Rạp Chiếu -->
                            <div class="col-md-7 mb-3">
                                <label for="cinemaId" class="form-label"><span class="text-danger">*</span> Rạp Chiếu</label>
                                <select class="form-select" id="cinemaId" name="cinema_id" required>
                                    <option value="" disabled selected>Chọn rạp chiếu</option>
                                </select>
                                <span class="text-danger mt-3" id="cinemaError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="type_room_id" class="form-label"><span class="text-danger">*</span> Loại phòng chiếu</label>
                                <select class="form-select" id="type_room_id" name="type_room_id" required>
                                   @foreach ($typeRooms as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                   @endforeach
                                </select>
                                <span class="text-danger mt-3" id="typeRoomError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="matrix_id" class="form-label"><span class="text-danger">*</span> Ma trận ghế</label>
                                <select class="form-select" id="matrix_id" name="matrix_id" required>
                                    @foreach (App\Models\SeatTemplate::MATRIXS as $matrix)
                                        <option value="{{ $matrix['id'] }}">{{ $matrix['name'] }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="matrixSeatError"></span>
                            </div>
                            <!-- Chọn Chi Nhánh -->

                            <input type="hidden" name="capacity" value='5'> <!-- Giá trị cố định cho capacity -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="saveRoomBtn">Thêm mới</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script-libs')
    <script>
        // Hàm load các rạp chiếu khi chọn chi nhánh
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

        document.getElementById('saveRoomBtn').addEventListener('click', function(event) {
            const form = document.getElementById('createRoomForm');
            const formData = new FormData(form);
            let hasErrors = false; // Biến để theo dõi có lỗi hay không

            fetch('http://datn-hn5.me/api/rooms', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        // Nếu có lỗi (400, 422, 500, ...), chuyển đến phần xử lý lỗi
                        return response.json().then(errorData => {
                            handleErrors(errorData.error); // Gọi hàm xử lý lỗi
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
                        window.location.href = `http://datn-hn5.me/admin/rooms/${data.room.id}/edit`; // Sử dụng room.id vừa thêm
                    }
                })
                .catch(error => console.error('Error adding room:', error));
        });

        // Hàm để hiển thị lỗi xác thực
        function handleErrors(errors) {
            // Reset thông báo lỗi trước đó
            document.getElementById('nameError').innerText = '';
            document.getElementById('branchError').innerText = '';
            document.getElementById('cinemaError').innerText = '';
            document.getElementById('matrixSeatError').innerText = '';
            document.getElementById('typeRoomError').innerText = '';
            // Kiểm tra và hiển thị lỗi cho từng trường
            if (errors.name) {
                document.getElementById('nameError').innerText = errors.name.join(', ');
            }
            if (errors.branch_id) {
                document.getElementById('branchError').innerText = errors.branch_id.join(', ');
            }
            if (errors.cinema_id) {
                document.getElementById('cinemaError').innerText = errors.cinema_id.join(', ');
            }
            if (errors.matrix_id) {
                document.getElementById('matrixSeatError').innerText = errors.matrix_id.join(', ');
            }
            if (errors.type_room_id) {
                document.getElementById('typeRoomError').innerText = errors.type_room_id.join(', ');
            }
            // Thêm các trường khác nếu cần
        }
    </script>

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

