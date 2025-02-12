@extends('admin.layouts.master')

@section('title')
    Thêm mới Suất chiếu
@endsection

@section('content')
    <form id="showtimesForm" action="{{ route('admin.showtimes.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Thêm mới Suất chiếu</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.showtimes.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active ">Thêm mới</li>
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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thêm thông tin Suất chiếu @if (Auth::user()->cinema_id != '')
                                - {{ Auth::user()->cinema->name }}
                            @endif
                        </h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <span class='text-danger'>*</span>
                                        <label for="title" class="form-label ">Tên phim:</label>
                                        <select name="movie_id" id="movie" class="form-select">
                                            <option value="">Chọn</option>
                                            @foreach ($movies as $item)
                                                <option value="{{ $item->id }}" @selected($item->id == old('movie_id'))>
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
                                        <span class='text-danger'>*</span>
                                        <label for="title" class="form-label ">Phiên bản phim:</label>
                                        <select name="movie_version_id" id="movie_version" class="form-select">
                                            <option value="">Chọn</option>


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
                                            <span class='text-danger'>*</span>
                                            <label for="title" class="form-label ">Tên Chi nhánh:</label>
                                            <select name="branch_id" id="branch" class="form-select">
                                                <option value="">Chọn</option>
                                                @foreach ($branches as $item)
                                                    <option value="{{ $item->id }}" @selected($item->id == old('branch_id'))>
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
                                            <span class='text-danger'>*</span>
                                            <label for="title" class="form-label ">Tên Rạp:</label>
                                            <select name="cinema_id" id="cinema" class="form-select">
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
                                            <span class='text-danger'>*</span>
                                            <label for="title" class="form-label ">Tên phòng:</label>
                                            <select name="room_id" id="room" class="form-select">
                                                <option value="">Chọn</option>

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
                                                    <option value="{{ $room->id }}" @selected($room->id == old('room_id'))>
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



                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <span class='text-danger'>*</span>
                                    <label for="date" class="form-label ">Ngày chiếu:</label>
                                    <input type="date" class="form-control" name="date" id="date"
                                        value="{{ old('date') }}">
                                    @error('date')
                                        <div class='mt-1'>
                                            <span class="text-danger">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label"></label>
                                    <button type="button" class="btn btn-primary mt-4" onclick="addShowtime()">Thêm giờ
                                        chiếu</button>
                                </div>
                            </div>



                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="auto-generate-showtimes" name="auto_generate_showtimes"
                                        {{ old('auto_generate_showtimes') ? 'checked' : '' }}>
                                    Tự động thêm các suất chiếu trong ngày
                                </label>
                            </div>

                            <div class="row" id="auto-showtime-settings" style="display: none;">
                                <div class="col-md-4 mb-1">
                                    <span class='text-danger'>*</span>
                                    <label for="start_hour">Giờ mở cửa:</label>
                                    <input type="time" id="start_hour" name="start_hour" class="form-control"
                                        value="{{ old('start_hour') }}">
                                    @error('start_hour')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <span class='text-danger'>*</span>
                                    <label for="end_hour">Giờ đóng cửa:</label>
                                    <input type="time" id="end_hour" name="end_hour" class="form-control"
                                        value="{{ old('end_hour') }}">
                                    @error('end_hour')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div id="showtime-container">

                            </div>



                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
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

                <!--end col-->
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-info">Danh sách</a>
                            <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
    </form>
@endsection


@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        //ajax load tên Rạp theo chi nhánh
        $(document).ready(function() {
            var selectedBranchId = "{{ old('branch_id', '') }}";
            var selectedCinemaId = "{{ old('cinema_id', '') }}";
            var selectedRoomId = "{{ old('room_id', '') }}";

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
                                cinemaSelect.val(selectedCinemaId).trigger(
                                    'change');
                                selectedCinemaId = false;
                            }
                        }
                    });
                }
            });


            if (selectedBranchId) {
                $('#branch').val(selectedBranchId).trigger('change');
            }


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


            if (selectedCinemaId) {
                $('#cinema').val(selectedCinemaId).trigger('change');
            }
        });


        // Ajax select Phiên bản phim (Vietsub, thueyets minh, lồng tiếng) theo phim
        $(document).ready(function() {
            var selectedMovieId = "{{ old('movie_id', '') }}";
            var selectedMovieVersionId = "{{ old('movie_version_id', '') }}";
            // Sự kiện thay đổi movie_id thì tên name (Bảng movie_version )thay đổi theo
            $('#movie').on('change', function() {
                var movieId = $(this).val(); //lấy giá trị của chính nó
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
                                    movieVersion
                                    .id +
                                    '">' + movieVersion.name + '</option>');
                            });
                            if (selectedMovieVersionId) {
                                movieVersionSelect.val(selectedMovieVersionId);
                                selectedMovieVersionId = false;
                            }

                        }
                    });
                }

            });
            if (selectedMovieId) {
                $('#movie').val(selectedMovieId).trigger('change');

            }
        });

        // Ajax đổ Suất chiếu đang có theo Phòng và Ngày
        $(document).ready(function() {
            var roomId = $('#room').val() || "{{ old('room_id') }}";
            var selectedDate = $('#date').val() || "{{ old('date') }}";

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

            function loadShowtimes(roomId, selectedDate) {
                var listShowtimes = $('#listShowtimes');
                listShowtimes.empty();

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
                    }
                });
            }
        });




        const cleaningTime = {{ $cleaningTime }}; // Thời gian dọn phòng
        let movieDuration = 0;

        // Lấy thời lượng phim
        $('#movie').on('change', function() {
            const movieId = $(this).val();
            if (!movieId) return;

            $.get(`{{ env('APP_URL') }}/api/getMovieDuration/${movieId}`, function(data) {
                movieDuration = parseInt(data.duration || 0);
                if (!movieDuration) alert("Không tìm thấy thời lượng phim!");
                updateAllEndTimes();
            });
        });



        let showtimeCount = 0;
        const minShowtimeItems = 1;
        const maxShowtimeItems = 8;

        const showtimeContainer = document.getElementById('showtime-container');

        // Thêm sẵn tối thiểu 1 showtime
        for (let i = 0; i < minShowtimeItems; i++) {
            addShowtime(i);

        }

        // function addShowtime(i) {

        //     // Thêm hàng giờ chiếu
        //     const id = 'gen_' + Math.random().toString(36).substring(2, 15).toLowerCase();
        //     const lastEndTime = $('#showtime-container .showtime-row:last input[name="end_time[]"]').val() || '';
        //     const newRow = `
    //             <div class="row showtime-row" id="${id}_item">
    //                 <div class="col-md-4 mb-3">
    //                     <label for="${id}_startTime">
    //                         <span class="text-danger">*</span> Giờ chiếu:
    //                     </label>
    //                     <input type="time" id="${id}_startTime" class="form-control" name="start_time[]" value="${lastEndTime}">
    //                     <div class="invalid-feedback fs-6" id="${showtimeCount}_startTime_error"></div> 
    //                 </div>
    //                 <div class="col-md-4 mb-3">
    //                     <label  for="${id}_endTime">Giờ kết thúc:</label>
    //                     <input type="time"  id="${id}_endTime"  class="form-control" name="end_time[]" readonly>
    //                     <div class="invalid-feedback fs-6" id="${showtimeCount}_endTime_error"></div> 
    //                 </div>
    //                 <div class="col-md-4 pt-4">
    //                     <button type="button" class="btn btn-danger delete-showtime">  <span class="bx bx-trash"></span></button>
    //                 </div>

    //             </div>`;

        //     // $('#showtime-container').append(newRow);
        //     showtimeContainer.insertAdjacentHTML('beforeend', newRow);
        //     updateEndTimeForRow($('#showtime-container .showtime-row:last'), lastEndTime);

        //     showtimeCount++;
        //     // console.log(i);
        //     console.log(showtimeCount);

        // }

        function addShowtime(i) {
            // Thêm hàng giờ chiếu
            const id = 'gen_' + Math.random().toString(36).substring(2, 15).toLowerCase();
            const lastEndTime = $('#showtime-container .showtime-row:last input[name="end_time[]"]').val() || '';

            // Thêm 5 phút vào giờ kết thúc cuối cùng để làm giờ bắt đầu mới
            let newStartTime = '';
            if (lastEndTime) {
                const [hours, minutes] = lastEndTime.split(':');
                const newTime = new Date();
                newTime.setHours(parseInt(hours), parseInt(minutes) + 5);
                newStartTime = newTime.toTimeString().slice(0, 5);
            }

            const newRow = `
        <div class="row showtime-row" id="${id}_item">
            <div class="col-md-4 mb-3">
                <label for="${id}_startTime">
                    <span class="text-danger">*</span> Giờ chiếu:
                </label>
                <input type="time" id="${id}_startTime" class="form-control" name="start_time[]" value="${newStartTime}">
                <div class="invalid-feedback fs-6" id="${showtimeCount}_startTime_error"></div> 
            </div>
            <div class="col-md-4 mb-3">
                <label for="${id}_endTime">Giờ kết thúc:</label>
                <input type="time" id="${id}_endTime" class="form-control" name="end_time[]" readonly>
                <div class="invalid-feedback fs-6" id="${showtimeCount}_endTime_error"></div> 
            </div>
            <div class="col-md-4 pt-4">
                <button type="button" class="btn btn-danger delete-showtime"><span class="bx bx-trash"></span></button>
            </div>
        </div>`;

            // Thêm hàng mới vào container
            $('#showtime-container').append(newRow);

            // Cập nhật giờ kết thúc cho hàng mới
            updateEndTimeForRow($('#showtime-container .showtime-row:last'), newStartTime);

            showtimeCount++;
            console.log(showtimeCount);
        }

        // Cập nhật giờ kết thúc khi thay đổi giờ bắt đầu
        $(document).on('change', 'input[name="start_time[]"]', function() {
            const currentRow = $(this).closest('.showtime-row');
            validateStartTime(currentRow);
            updateEndTimeForRow(currentRow, $(this).val());
        });

        // Xóa hàng giờ chiếu
        $(document).on('click', '.delete-showtime', function() {
            if (showtimeCount > minShowtimeItems) {
                $(this).closest('.showtime-row').remove();
                showtimeCount--;
            } else {
                alert('Phải có ít nhất ' + minShowtimeItems + ' giờ chiếu.');
            }
        });

        // Hàm cập nhật giờ kết thúc
        function updateEndTimeForRow(row, startTime) {
            if (!startTime || !movieDuration) return;

            const [hours, minutes] = startTime.split(':');
            const endTime = new Date();
            endTime.setHours(parseInt(hours), parseInt(minutes) + movieDuration + cleaningTime);

            const formattedEndTime = endTime.toTimeString().slice(0, 5);
            row.find('input[name="end_time[]"]').val(formattedEndTime);
        }

        // // Hàm kiểm tra giờ bắt đầu hợp lệ
        // function validateStartTime(row) {
        //     const startTime = row.find('input[name="start_time[]"]').val();
        //     const prevEndTime = row.prev('.showtime-row').find('input[name="end_time[]"]').val();

        //     if (prevEndTime && startTime < prevEndTime) {
        //         alert('Giờ bắt đầu không được nhỏ hơn giờ kết thúc của suất trước!');
        //         row.find('input[name="start_time[]"]').val(prevEndTime);
        //     }
        // }


        // Hàm kiểm tra giờ bắt đầu hợp lệ
        function validateStartTime(row) {
            const startTime = row.find('input[name="start_time[]"]').val();
            const prevEndTime = row.prev('.showtime-row').find('input[name="end_time[]"]').val();

            if (prevEndTime && startTime < prevEndTime) {
                alert('Giờ bắt đầu không được nhỏ hơn giờ kết thúc của suất trước!');

                // Tính giờ bắt đầu mới: `prevEndTime` + 5 phút
                const [hours, minutes] = prevEndTime.split(':');
                const adjustedTime = new Date();
                adjustedTime.setHours(parseInt(hours), parseInt(minutes) + 5);

                const newStartTime = adjustedTime.toTimeString().slice(0, 5);

                // Đặt lại giá trị cho input `start_time`
                row.find('input[name="start_time[]"]').val(newStartTime);

                // Cập nhật giờ kết thúc tương ứng
                updateEndTimeForRow(row, newStartTime);
            }
        }

        // Hàm cập nhật tất cả giờ kết thúc
        function updateAllEndTimes() {
            $('#showtime-container .showtime-row').each(function() {
                const startTime = $(this).find('input[name="start_time[]"]').val();
                updateEndTimeForRow($(this), startTime);
            });
        }


        function displayValidationErrors(errors) {
            // Xóa thông báo lỗi cũ
            $('.invalid-feedback').empty();

            // hiển thị validate của trường Giờ chiếu + Giờ kết thúc
            for (let field in errors) {
                let fieldErrors = errors[field];

                // Tìm id giờ chiếu và giờ kết thúc
                let errorDiv;

                if (field.startsWith('start_time')) {

                    let index = field.match(/\d+/)[0]; //output:3
                    errorDiv = $(`#${index}_startTime_error`); //output: gán id = 3_startTime_error

                } else if (field.startsWith('end_time')) {
                    let index = field.match(/\d+/)[0];
                    errorDiv = $(`#${index}_endTime_error`);
                }

                if (errorDiv && errorDiv.length) {
                    errorDiv.text(fieldErrors[0]); // Gán lỗi vào div
                    errorDiv.show();
                }
                // console.log(errorDiv);

            }

            // hiển thị validate của các trường còn lại (movie, room, branch, cinema, date)
            $('.error-message').remove();
            // Hiển thị thông báo lỗi mới
            for (let field in errors) {
                let fieldErrors = errors[field]; // Array lỗi của từng field
                let input = $(`[name="${field}"]`); // Tìm input theo tên

                // Thêm lỗi phía dưới input
                if (input.length > 0) {
                    input.after(`<div class="error-message text-danger">${fieldErrors[0]}</div>`);
                }
            }
        }

        $('#showtimesForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // alert(response.message);
                        window.location.href = '/admin/showtimes';
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;
                        console.log(errors); // Kiểm tra lỗi
                        displayValidationErrors(errors);
                    } else {
                        alert('Đã xảy ra lỗi, vui lòng thử lại!');
                    }
                }
            });
        });




        // Js để hiển thị/ẩn các input giờ mở và đóng cửa khi chọn checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const showtimeSettings = document.getElementById('auto-showtime-settings');
            // const addStartTime = document.getElementById('add-start-time');
            const addStartTime = document.getElementById('showtime-container');

            const autoGenerateCheckbox = document.getElementById('auto-generate-showtimes');

            // Hiển thị auto-showtime-settings nếu checkbox được chọn
            if (autoGenerateCheckbox.checked) {
                showtimeSettings.style.display = 'flex';
                addStartTime.style.display = 'none';
            }

            autoGenerateCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    showtimeSettings.style.display = 'flex';
                    addStartTime.style.display = 'none';
                } else {
                    showtimeSettings.style.display = 'none';
                    addStartTime.style.display = 'block';
                }
            });
        });
    </script>
@endsection
