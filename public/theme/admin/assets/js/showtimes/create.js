
//Ajax select tên Rạp theo chi nhánh
$(document).ready(function () {
    var selectedBranchId = "{{ old('branch_id', '') }}";
    var selectedCinemaId = "{{ old('cinema_id', '') }}";
    // Xử lý sự kiện thay đổi chi nhánh
    $('#branch').on('change', function () {
        var branchId = $(this).val();
        var cinemaSelect = $('#cinema');
        cinemaSelect.empty();
        cinemaSelect.append('<option value="">Chọn rạp chiếu</option>');

        if (branchId) {
            $.ajax({
                url: "{{ env('APP_URL') }}/api/cinemas/" + branchId,
                method: 'GET',
                success: function (data) {
                    $.each(data, function (index, cinema) {
                        cinemaSelect.append('<option  value="' + cinema.id +
                            '">' + cinema.name + '</option>');
                    });

                    // Chọn lại cinema nếu có selectedCinemaId
                    if (selectedCinemaId) {
                        cinemaSelect.val(selectedCinemaId);
                        selectedCinemaId = false;
                    }
                }
            });
        }
    });
    // Nếu có selectedBranchId thì tự động kích hoạt thay đổi chi nhánh để load danh sách cinema
    if (selectedBranchId) {
        $('#branch').val(selectedBranchId).trigger('change');

    }

});

// Ajax select Phòng theo tên Rạp
$(document).ready(function () {
    var selectedCinemaId = "{{ old('cinema_id', '') }}";
    var selectedRoomId = "{{ old('room_id', '') }}";
    // Xử lý sự kiện thay đổi chi nhánh
    $('#cinema').on('change', function () {
        var cinemaId = $(this).val();
        var roomSelect = $('#room');
        roomSelect.empty();
        roomSelect.append('<option value="">Chọn phòng</option>');


        if (cinemaId) {
            $.ajax({
                url: "{{ env('APP_URL') }}/api/rooms/" + cinemaId,
                method: 'GET',
                success: function (data) {
                    // console.log(data);
                    $.each(data, function (index, room) {

                        console.log(room);
                        const roomCapacity = room.total_seats;

                        roomSelect.append('<option value="' + room.id +
                            '" >' + room.name + ' - ' + room
                                .type_room_name + ' - ' + roomCapacity +
                            ' ghế </option>');


                    });
                    //
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
$(document).ready(function () {
    var selectedMovieId = "{{ old('movie_id', '') }}";
    var selectedMovieVersionId = "{{ old('movie_version_id', '') }}";
    // Sự kiện thay đổi movie_id thì tên name (Bảng movie_version )thay đổi theo
    $('#movie').on('change', function () {
        var movieId = $(this).val(); //lấy giá trị của chính nó
        var movieVersionSelect = $('#movie_version');
        movieVersionSelect.empty();
        movieVersionSelect.append('<option value="">Chọn phiên bản</option>');

        if (movieId) {
            $.ajax({
                url: "{{ env('APP_URL') }}/api/movieVersions/" + movieId,
                method: 'GET',
                success: function (data) {
                    $.each(data, function (index, movieVersion) {
                        movieVersionSelect.append('<option  value="' +
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


const cleaningTime = {{ $cleaningTime }} // Thời gian dọn phòng = 15 phút
// Ajax lấy thời lượng phim theo phim để tự động tính thời gian kết thúc chiếu
$(document).ready(function () {
    let movieDuration = 0;

    $('#movie').on('change', function () {
        var movieId = $(this).val();
        if (movieId) {
            $.ajax({
                url: "{{ env('APP_URL') }}/api/getMovieDuration/" + movieId,
                method: 'GET',
                success: function (data) {
                    if (data.duration) {
                        movieDuration = parseInt(data.duration); // Lưu lại thời lượng
                        updateAllEndTimes(
                            movieDuration
                        ); // Cập nhật tất cả giờ kết thúc
                    }
                }
            });
        }
    });

    // Cập nhật lại thời gian kết thúc khi start_time thay đổi cho hàng cụ thể
    $(document).on('change', 'input[name="start_time[]"]', function () {
        const row = $(this).closest('.showtime-row'); // Lấy hàng hiện tại
        const startTime = $(this).val();
        updateEndTimeForRow(row, movieDuration,
            startTime); // Cập nhật lại end-time cho hàng hiện tại
    });

    // Hàm cập nhật end-time dựa trên thời lượng phim và thời gian bắt đầu cho hàng đấy
    function updateEndTimeForRow(row, duration, startTime) {
        if (startTime && duration) {
            let [hours, minutes] = startTime.split(':'); //cắt dạng giờ : phút
            let startTimeDate = new Date();
            startTimeDate.setHours(parseInt(hours), parseInt(minutes)); //parseInt: địh dạng số nguyên

            let totalMinutes = duration + cleaningTime;
            startTimeDate.setMinutes(startTimeDate.getMinutes() + totalMinutes);

            // Lấy thời gian kết thúc được định dạng
            let endHours = String(startTimeDate.getHours()).padStart(2,
                '0'); //padStart: nếu chuỗi ngắn hơn 2 ký tự, vd: 9:2 => 09:02
            let endMinutes = String(startTimeDate.getMinutes()).padStart(2, '0');
            const endTime = `${endHours}:${endMinutes}`;

            // Gán giá trị end_time vào ô input
            row.find('input[name="end_time[]"]').val(endTime); //tìm đến hàng hiện tại để cập nhật end-time
        }
    }

    // Hàm cập nhật thời gian kết thúc cho tất cả các hàng khi thay đổi thời lượng phim
    function updateAllEndTimes(duration) {
        $('input[name="start_time[]"]').each(function () {
            const row = $(this).closest('.showtime-row');
            const startTime = $(this).val();
            updateEndTimeForRow(row, duration, startTime); // Cập nhật end_time cho từng hàng
        });
    }
});

// Thêm giao diện hàng mới cho suất chiếu
function addShowtime() {
    var newRow = `
        <div class="row showtime-row">
            <div class="col-md-4 mb-3">
                <span class='text-danger'>*</span>
                <label for="start_time" class="form-label">Giờ chiếu:</label>
                <input type="time" class="form-control" name="start_time[]">
            </div>
            <div class="col-md-4">
                <label for="end_time" class="form-label">Giờ kết thúc:</label>
                <input type="time" class="form-control" name="end_time[]" readonly>
            </div>
            <div class="col-md-4 mt-4" align='left'>
                <button type="button" class="btn btn-danger remove-btn delete-showtime">
                    <span class="bx bx-trash"></span>
                </button>
            </div>
        </div>`;

    $('#showtime-container').append(newRow); // Thêm suất chiếu vào giao diện
}


$(document).on('click', '.delete-showtime', function () {
    $(this).closest('.showtime-row').remove();
});
