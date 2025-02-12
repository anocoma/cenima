@extends('admin.layouts.master')

@section('title')
    Cập nhật Suất chiếu
@endsection

@section('content')
    <form action="{{ route('admin.showtimes.update', $showtime) }}" method="post" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Cập nhật Suất chiếu</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.showtimes.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active ">Cập nhật</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">
            <div class="col-md-12">
                @if (session()->has('error'))
                    <div class="alert alert-danger m-3">
                        {{ session()->get('error') }}
                    </div>
                @endif
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif --}}
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Cập nhật thông tin Suất chiếu @if (Auth::user()->cinema_id != '')
                                - {{ Auth::user()->cinema->name }}
                            @endif
                        </h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label ">Tên phim:</label>
                                        <select name="movie_id" id="movie" class="form-select">
                                            <option value="">Chọn</option>
                                            {{-- @foreach ($movies as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($item->id == $showtime->movieVersion->movie->id) selected @endif>{{ $item->name }}
                                                </option>
                                            @endforeach --}}

                                            @foreach ($movies as $item)
                                                <option value="{{ $item->id }}" @selected($item->id == old('movie_id', $showtime->movie_id ?? ''))>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('movie_id')
                                            <div class='mt-1'>
                                                <span class="text-danger">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="title" class="form-label ">Phiên bản phim:</label>
                                        <select name="movie_version_id" id="movie_version" class="form-select">
                                            <option value="">Chọn</option>


                                            {{-- <option value="{{ $showtime->movieVersion->id }}" selected>
                                                {{ $showtime->movieVersion->name }}</option> --}}
                                        </select>
                                        @error('movie_version_id')
                                            <div class='mt-1'>
                                                <span class="text-danger">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->hasRole('System Admin'))
                                <div class="row gy-4">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="title" class="form-label ">Tên Chi Nhánh:</label>
                                            <select name="branch_id" id="branch" disabled @readonly(true) class="form-select">
                                                <option value="">Chọn</option>
                                         
                                                @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}" @selected($item->id == old('branch_id', $showtime->cinema->branch_id ?? ''))>
                                                        {{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                            @error('cinema_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="title" class="form-label ">Tên Rạp:</label>
                                            <select name="cinema_id" id="cinema" disabled @readonly(true) class="form-select">
                                                <option value="">Chọn</option>
                                            </select>
                                            @error('cinema_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="title" class="form-label ">Tên phòng:</label>
                                            <select name="room_id" id="room" class="form-select">
                                                <option value="">Chọn</option>

                                                {{-- <option value="{{ $showtime->room->id }}" selected>
                                                    {{ $showtime->room->name }} - {{ $showtime->room->typeRoom->name }} -
                                                    {{ $showtime->room->seats->whereNull('deleted_at')->where('is_active', true)->count() }}
                                                    ghế
                                                </option> --}}

                                            </select>
                                            @error('room_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>

                                    </div>


                                </div>
                            @else
                                <div class="row gy-4">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <span class='text-danger'>*</span>
                                            <label for="title" class="form-label ">Tên phòng:</label>
                                            <select name="room_id" id="room" class="form-select">
                                                <option value="">Chọn phòng</option>
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}"
                                                        @if ($showtime->room_id == $room->id) selected @endif>
                                                        {{ $room->name }} -
                                                        {{ $room->typeRoom->name }}
                                                        - {{ $room->seats->where('is_active', true)->count() }} ghế
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('room_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="date" class="form-label ">Ngày chiếu:</label>
                                    <input type="date" class="form-control" name="date" id="date"
                                        value="{{ $showtime->date }}">
                                    @error('date')
                                        <div class='mt-1'>
                                            <span class="text-danger">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="start_time" class="form-label ">Giờ chiếu:</label>
                                    <input type="time" class="form-control" name="start_time" id="start_time"
                                        value="{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}">
                                    @error('start_time')
                                        <div class='mt-1'>
                                            <span class="text-danger">{{ $message }}</span>
                                        </div>
                                    @enderror

                                </div>
                                <div class="col-md-4">
                                    <label for="end_time" class="form-label ">Giờ kết thúc:</label>
                                    <input type="time" class="form-control" name="end_time" id="end_time"
                                        value="{{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}" readonly>
                                    @error('end_time')
                                        <div class='mt-1'>
                                            <span class="text-danger">{{ $message }}</span>
                                        </div>
                                    @enderror

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <a href="{{ route('admin.showtimes.index') }}" class="btn btn-info">Danh sách</a>
                                <button type="submit" class="btn btn-primary mx-1">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div>
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">

                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label class="form-check-label" for="is_active">Hoạt động</label>
                                            <div class="form-check form-switch form-switch-default">
                                                <input class="form-check-input" type="checkbox" role=""
                                                    name="is_active" @checked($showtime->is_active == 1)>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <label for="">Suất chiếu đang có:</label>
                                <table class="table table-bordered dt-responsive nowrap align-middle">
                                    <thead>
                                        <tr>
                                            <th>Thời gian</th>
                                            <th>Phòng</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listShowtimes">

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>



    </form>
@endsection


@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        //Ajax select tên Rạp theo chi nhánh
        $(document).ready(function() {
            var selectedBranchId = "{{ old('branch_id', $showtime->cinema->branch_id ?? '') }}";
            var selectedCinemaId = "{{ old('cinema_id', $showtime->cinema_id ?? '') }}";
            var selectedRoomId = "{{ old('room_id', $showtime->room_id ?? '') }}";
            var selectedMovieId = "{{ old('movie_id', $showtime->movie_id ?? '') }}";
            var selectedMovieVersionId = "{{ old('movie_version_id', $showtime->movie_version_id ?? '') }}";

            // Load Cinemas when Branch changes
            $('#branch').on('change', function() {
                var branchId = $(this).val();
                var cinemaSelect = $('#cinema');
                cinemaSelect.empty();
                cinemaSelect.append('<option value="">Chọn rạp chiếu</option>');

                if (branchId) {
                    $.ajax({
                        url: "{{ env('APP_URL') }}/api/cinemas/" + branchId,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, cinema) {
                                cinemaSelect.append('<option value="' + cinema.id +
                                    '">' + cinema.name + '</option>');
                            });
                            if (selectedCinemaId) {
                                cinemaSelect.val(selectedCinemaId).trigger('change');
                                selectedCinemaId = false;
                            }
                        }
                    });
                }
            });

            // Load Rooms when Cinema changes
            $('#cinema').on('change', function() {
                var cinemaId = $(this).val();
                var roomSelect = $('#room');
                roomSelect.empty();
                roomSelect.append('<option value="">Chọn phòng</option>');

                if (cinemaId) {
                    $.ajax({
                        url: "{{ env('APP_URL') }}/api/rooms/" + cinemaId,
                        method: 'GET',
                        success: function(data) {

                            $.each(data, function(index, room) {
                                roomSelect.append('<option value="' + room.id + '">' +
                                    room.name + ' - ' + room.type_room_name +
                                    ' - ' + room.total_seats + ' ghế</option>');
                            });
                            if (selectedRoomId) {
                                roomSelect.val(selectedRoomId);
                                selectedRoomId = false;
                            }
                        }
                    });
                }
            });

            // Load Movie Versions when Movie changes
            $('#movie').on('change', function() {
                var movieId = $(this).val();
                var movieVersionSelect = $('#movie_version');
                movieVersionSelect.empty();
                movieVersionSelect.append('<option value="">Chọn phiên bản</option>');

                if (movieId) {
                    $.ajax({
                        url: "{{ env('APP_URL') }}/api/movieVersions/" + movieId,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, movieVersion) {
                                movieVersionSelect.append('<option value="' +
                                    movieVersion.id + '">' + movieVersion.name +
                                    '</option>');
                            });
                            if (selectedMovieVersionId) {
                                movieVersionSelect.val(selectedMovieVersionId);
                                selectedMovieVersionId = false;
                            }
                        }
                    });
                }
            });

            // Trigger load data on page load
            if (selectedBranchId) {
                $('#branch').val(selectedBranchId).trigger('change');
            }
            if (selectedMovieId) {
                $('#movie').val(selectedMovieId).trigger('change');
            }
        });



        //ajax load suất chiếu đang có
        $(document).ready(function() {
            var roomId = $('#room').val() || "{{ old('room_id', $showtime->room_id ?? '') }}";
            var selectedDate = $('#date').val() || "{{ old('date', $showtime->date ?? '') }}";

            // Gọi hàm loadShowtimes khi trang được tải
            if (roomId && selectedDate) {
                loadShowtimes(roomId, selectedDate);
            }

            // Xử lý sự kiện thay đổi phòng
            $('#room').on('change', function() {
                roomId = $(this).val();
                loadShowtimes(roomId, selectedDate);
            });

            // Xử lý sự kiện thay đổi ngày chiếu
            $('#date').on('change', function() {
                selectedDate = $(this).val();
                loadShowtimes(roomId, selectedDate);
            });

            // Hàm load danh sách suất chiếu
            function loadShowtimes(roomId, selectedDate) {
                var listShowtimes = $('#listShowtimes');
                listShowtimes.empty(); // Xóa dữ liệu cũ

                $.ajax({
                    url: "{{ env('APP_URL') }}/api/getShowtimesByRoom",
                    method: 'GET',
                    data: {
                        room_id: roomId,
                        date: selectedDate
                    },
                    success: function(data) {
                        if (data.status === 'error') {
                            listShowtimes.append('<tr><td colspan="2">' + data.message + '</td></tr>');
                        } else {
                            $.each(data, function(index, showtime) {
                                var startTime = showtime.start_time;
                                var endTime = showtime.end_time;
                                var roomName = showtime.room.name;

                                // Đổ dữ liệu vào bảng
                                listShowtimes.append('<tr><td>' + startTime + ' - ' + endTime +
                                    '</td><td>' + roomName + '</td></tr>');
                            });
                        }
                    },
                    error: function(xhr) {
                        listShowtimes.append('<tr><td colspan="2">Không thể tải dữ liệu.</td></tr>');
                    }
                });
            }
        });





        const cleaningTime = {{ $cleaningTime }};
        // Ajax lấy thời lượng phim theo phim để tự động tính thời gian kết thúc chiếu
        $(document).ready(function() {
            let movieDuration = 0;

            // Khi chọn phim, lấy thời lượng phim qua API
            $('#movie').on('change', function() {
                const movieId = $(this).val();
                if (movieId) {
                    $.ajax({
                        url: "{{ env('APP_URL') }}/api/getMovieDuration/" + movieId,
                        method: 'GET',
                        success: function(data) {
                            if (data.duration) {
                                movieDuration = parseInt(data
                                    .duration); // Cập nhật thời lượng phim
                                updateEndTime(
                                    movieDuration); // Gọi lại hàm cập nhật thời gian kết thúc
                            }
                        },
                        error: function() {
                            console.error("Không thể lấy thời lượng phim.");
                        }
                    });
                } else {
                    movieDuration = 0; // Nếu không chọn phim, đặt thời lượng về 0
                }
            });

            // Cập nhật giờ kết thúc khi giờ bắt đầu thay đổi
            $('#start_time').on('change', function() {
                if (movieDuration > 0) {
                    updateEndTime(movieDuration); // Chỉ cập nhật nếu có thời lượng phim
                } else {
                    // alert("Vui lòng chọn phim trước để tính giờ kết thúc!");
                    const movieDuration = {{ $movieDuration ?? 'null' }};
                    updateEndTime(movieDuration);
                }
            });

            // Hàm cập nhật giờ kết thúc
            function updateEndTime(duration) {
                const startTime = document.getElementById('start_time').value;
                if (startTime && duration) {
                    let [hours, minutes] = startTime.split(':');
                    let startTimeDate = new Date();
                    startTimeDate.setHours(parseInt(hours), parseInt(minutes));

                    // Tính tổng thời gian
                    let totalMinutes = duration + cleaningTime;
                    startTimeDate.setMinutes(startTimeDate.getMinutes() + totalMinutes);

                    // Định dạng giờ kết thúc
                    let endHours = String(startTimeDate.getHours()).padStart(2, '0');
                    let endMinutes = String(startTimeDate.getMinutes()).padStart(2, '0');
                    const endTime = `${endHours}:${endMinutes}`;

                    // Gán giá trị vào ô giờ kết thúc
                    document.getElementById('end_time').value = endTime;
                }
            }
        });
    </script>
@endsection
