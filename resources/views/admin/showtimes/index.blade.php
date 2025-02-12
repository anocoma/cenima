@extends('admin.layouts.master')

@section('title')
    Danh sách suất chiếu
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
                <h4 class="mb-sm-0">Danh sách Suất chiếu @if (Auth::user()->cinema_id != '')
                        - {{ Auth::user()->cinema->name }}
                    @endif
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Suất chiếu</a></li>
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
                <div class="card-body border border-dashed border-end-0 border-start-0">
                    <form action="{{ route('admin.showtimes.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    @if (Auth::user()->hasRole('System Admin'))
                                        <div class="col-md-2">
                                            <label class="mb-0">Chi nhánh</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="mb-0">Rạp</label>
                                        </div>
                                    @endif
                                    <div class="col-md-2">
                                        <label class="mb-0">Ngày chiếu</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="mb-0">Trạng thái</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    @if (Auth::user()->hasRole('System Admin'))
                                        <div class="col-md-2">
                                            {{-- <label for="">Chi nhánh</label> --}}
                                            <select name="branch_id" id="branch" class="form-select">
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ $branch->id == session('showtime.branch_id') ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            {{-- <label for="">Rạp</label> --}}
                                            <select name="cinema_id" id="cinema" class="form-select">
                                                @foreach ($cinemas as $cinema)
                                                    <option value="{{ $cinema->id }}"
                                                        {{ $cinema->id == session('showtime.cinema_id') ? 'selected' : '' }}>
                                                        {{ $cinema->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        {{-- <label for="">Ngày chiếu</label> --}}
                                        <input type="date" name="date" class="form-control"
                                            value="{{ session('showtime.date', now()->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col-md-2">
                                        {{-- <label for="">Trạng thái</label> --}}
                                        <select name="is_active" class="form-select">
                                            <option value=""
                                                {{ session('showtime.is_active', null) === null ? 'selected' : '' }}>
                                                Tất cả
                                            </option>
                                            <option value="0"
                                                {{ session('showtime.is_active', null) === '0' ? 'selected' : '' }}>
                                                Không
                                                hoạt động
                                            </option>
                                            <option value="1"
                                                {{ session('showtime.is_active', null) === '1' ? 'selected' : '' }}>
                                                Đang
                                                hoạt động
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        {{-- <label for="">Lọc</label> --}}
                                        <button class="btn btn-success" name="btnSearch" type="submit">
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                            Lọc</button>
                                    </div>
                                    @can('Thêm suất chiếu')
                                        @if (Auth::user()->hasRole('System Admin'))
                                            <div class="col-md-2" align="right">
                                            @else
                                                <div class="col-md-6 text" align="right">
                                        @endif
                                        <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary">Thêm mới</a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                </div>
                </form>
            </div>
            @if (session()->has('success'))
                <div class="alert alert-success m-3">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="card-body">

                <table id="example" class="table table-bordered dt-responsive nowrap align-middle" style="width:100%;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>PHIM</th>
                            <th>THỜI LƯỢNG</th>
                            <th>THỂ LOẠI</th>
                            {{-- <th>ĐỊNH DẠNG</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($showtimes->groupBy('movie_id') as $movieId => $showtimesByMovie)
                            @php
                                $movie = $showtimesByMovie->first()->movie;
                                // dd($showtimesByMovie->toArray());
                            @endphp
                            <tr class="movie-row">
                                <td class="plusShowtime">
                                    <button class="toggle-button btn btn-link">
                                        {{-- icon cộng --}}
                                        <i class="ri-add-circle-fill"></i>
                                    </button>
                                </td>

                                <td>
                                    <b>
                                        @php
                                            $url = $movie->img_thumbnail;

                                            if (!\Str::contains($url, 'http')) {
                                                $url = Storage::url($url);
                                            }

                                        @endphp
                                        @if (!empty($movie->img_thumbnail))
                                            <img src="{{ $url }}" alt="" width="50px" height="60px"
                                                class="img-thumbnail">
                                        @else
                                            No image !
                                        @endif

                                        {{ $movie->name }}
                                    </b>
                                    @if ($movie->is_special == 1)
                                        <span class="badge bg-danger-subtle text-danger text-uppercase">Đặc biệt
                                        </span>
                                    @else
                                    @endif


                                </td>
                                <td>{{ $movie->duration }} phút</td>
                                <td>{{ $movie->category }}</td>
                                {{-- <td>{{ $showtimesByMovie->first()->format }}</td> --}}
                            </tr>

                            <tr class="showtime-row" style="display: none;">
                                <td colspan="6" class="table-showtime-row">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr class="bg-light">
                                                {{-- <th> --}}
                                                    {{-- dùng hàm contains để kiểm tra tồn tại --}}
                                                    {{-- @if ($showtimesByMovie->contains(fn($showtime) => $showtime->is_active == 0)) --}}
                                                    {{-- <input type="checkbox" id="select-all-{{ $movieId }}"
                                                        class="select-all-movie"> --}}
                                                    {{-- @endif --}}

                                                {{-- </th> --}}

                                                <th>THỜI GIAN</th>
                                                <th>PHÒNG</th>
                                                <th>CÒN LẠI</th>
                                                <th>ĐỊNH DẠNG</th>
                                                <th class="status-showtime">HOẠT ĐỘNG</th>
                                                <th>CHỨC NĂNG</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($showtimesByMovie as $showtime)
                                                @php
                                                    // $timeNow = now()->format('Y-m-d H:i:s');
                                                    $timeNow = now();
                                                    // dd($timeNow);

                                                    //danh sách ticket
                                                    $tickets = $showtime->tickets;

                                                    //Đếm số lượng ghế đã bán
                                                    $soldSeats = \App\Models\TicketSeat::whereIn(
                                                        'ticket_id',
                                                        $tickets->pluck('id'),
                                                    )->count();

                                                    //Tổng số ghế trong phòng chiếu
                                                    $totalSeats = $showtime->room->seats
                                                        ->whereNull('deleted_at')
                                                        ->where('is_active', true)
                                                        ->count();

                                                    //Tổng số ghế còn lại
                                                    $remainingSeats = $totalSeats - $soldSeats;
                                                @endphp
                                                <tr>
                                                    {{-- <td class="inputCheckBoxShowtimes">

                                                        @if (!$timeNow->greaterThan($showtime->start_time))
                                                           
                                                            @if (!($remainingSeats < $totalSeats))
                                                                <input type="checkbox"
                                                                    class="select-showtime movie-{{ $movieId }}"
                                                                    data-showtime-id="{{ $showtime->id }}">
                                                            @endif
                                                        @endif

                                                    </td> --}}
                                                    <td>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}
                                                    </td>
                                                    <td>{{ $showtime->room->name }}</td>


                                                    <td>{{ $remainingSeats }}/{{ $totalSeats }} ghế</td>

                                                    <td>
                                                        {{ $showtime->format }}
                                                    </td>
                                                    <td>
                                                        @can('Sửa suất chiếu')
                                                            {{-- Nút is_active --}}
                                                            <div
                                                                class="form-check form-switch form-switch-success d-inline-block">
                                                                <input class="form-check-input switch-is-active changeActive"
                                                                    name="is_active" type="checkbox" role="switch"
                                                                    data-showtime-id="{{ $showtime->id }}"
                                                                    @checked($showtime->is_active)
                                                                    @if ($remainingSeats < $totalSeats || $timeNow > $showtime->start_time) disabled @endif>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="form-check form-switch form-switch-success d-inline-block">
                                                                <input class="form-check-input switch-is-active changeActive"
                                                                    name="is_active" disabled readonly type="checkbox"
                                                                    role="switch" data-showtime-id="{{ $showtime->id }}"
                                                                    @checked($showtime->is_active)
                                                                    @if ($showtime->is_active) disabled @endif>
                                                            </div>
                                                        @endcan
                                                    </td>

                                                    <td>
                                                        @can('Xem chi tiết suất chiếu')
                                                            <a href="{{ route('admin.showtimes.show', $showtime) }}">
                                                                <button title="xem" class="btn btn-success btn-sm "
                                                                    type="button"><i class="fas fa-eye"></i></button></a>
                                                        @endcan
                                                        {{-- @if ($showtime->is_active == 0) --}}
                                                        @if (!$timeNow->greaterThan($showtime->start_time))
                                                            @if (!($remainingSeats < $totalSeats))
                                                                @can('Sửa suất chiếu')
                                                                    <a href="{{ route('admin.showtimes.edit', $showtime) }}">
                                                                        <button title="sửa"
                                                                            class="btn btn-warning btn-edit btn-sm"
                                                                            type="button"><i
                                                                                class="fas fa-edit"></i></button>
                                                                    </a>
                                                                @endcan

                                                                @can('Xóa suất chiếu')
                                                                    <form
                                                                        action="{{ route('admin.showtimes.destroy', $showtime) }}"
                                                                        method="post" class="d-inline-block">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-destroy btn-sm"
                                                                            onclick="return confirm('Bạn chắc chắn muốn xóa không?')">
                                                                            <i class="ri-delete-bin-7-fill"></i>
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            @endif
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        {{-- <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="d-flex justify-content-between">
                                                        @can('Xóa suất chiếu')
                                                            <form action="" method="post" class="d-inline-block">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" id="delete-all"
                                                                    class="btn btn-danger btn-sm">
                                                                    Xóa tất cả
                                                                </button>
                                                            </form>
                                                        @endcan
                                                        @can('Sửa suất chiếu')
                                                            <div>
                                                                <a href="">
                                                                    <button id="on-status-all" title="thay đổi"
                                                                        class="btn btn-primary btn-sm">Bật trạng thái
                                                                        tất cả</button>
                                                                </a>
                                                                <a href="">
                                                                    <button id="off-status-all" title="thay đổi"
                                                                        class="btn btn-secondary btn-sm">Tắt trạng thái
                                                                        tất cả</button>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                    </div>
                                          

                                                </td>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>



        </div>
    </div>
    </div>
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Lấy giá trị branchId và cinemaId từ Laravel
            var selectedBranchId = "{{ old('branch_id', '') }}";
            var selectedCinemaId = "{{ old('cinema_id', '') }}";

            // Xử lý sự kiện thay đổi chi nhánh
            $('#branch').on('change', function() {
                var branchId = $(this).val();
                var cinemaSelect = $('#cinema');
                cinemaSelect.empty();
                cinemaSelect.append('<option value="">Chọn Rạp</option>');

                if (branchId) {
                    $.ajax({
                        url: "{{ env('APP_URL') }}/api/cinemas/" + branchId,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, cinema) {
                                cinemaSelect.append('<option value="' + cinema.id +
                                    '" >' + cinema.name + '</option>');
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


        //Thay đôi ttrangj thái is_active ko load
        //mới
        $(document).on('change', '.changeActive', function() {
            let showtimeId = $(this).data('showtime-id');
            let is_active = $(this).is(':checked') ? 1 : 0;

            if (!confirm(
                    'Bạn có chắc muốn thay đổi?')) {

                $(this).prop('checked', !is_active);
                return;
            }

            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ route('showtimes.change-active') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: showtimeId,
                    is_active: is_active
                },
                success: function(response) {
                    if (response.success) {
                        let checkbox = $(`[data-showtime-id="${showtimeId}"]`);
                        // checkbox.prop('disabled', response.data.is_active); // Disable nếu bật
                        // checkbox.closest('tr').find('.btn-edit, .btn-destroy').toggle(!response.data
                        //     .is_active); // Ẩn nút sửa, xóa

                        // checkbox.closest('tr').find('.select-showtime').toggle(!response.data
                        //     .is_active); // Ẩn nút select checkbox

                        // if (response.data.is_active) {
                        //     checkbox.closest('td').find('.select-showtime')
                        //         .remove(); // Loại bỏ checkbox khỏi DOM
                        // } else {
                        //     checkbox.closest('td').find('.select-showtime')
                        //         .show(); // Hiển thị lại checkbox nếu trạng thái tắt
                        // }


                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: 'Trạng thái hoạt động đã được cập nhật.',
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: response.message,
                            timer: 3000
                        });
                        // Hoàn lại trạng thái checkbox
                        let checkbox = $(`[data-showtime-id="${showtimeId}"]`);
                        checkbox.prop('checked', !is_active);
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi cập nhật trạng thái.',
                        timer: 3000
                    });

                    // Hoàn lại trạng thái checkbox
                    let checkbox = $(`[data-showtime-id="${showtimeId}"]`);
                    checkbox.prop('checked', !is_active);
                }
            });
        });






        $(document).ready(function() {
            $('.toggle-button').click(function() {
                // Tìm hàng suất chiếu liền kề và chuyển đổi hiển thị
                $(this).closest('tr').next('.showtime-row').toggle();

                // Chuyển đổi giữa biểu tượng cộng và trừ
                const icon = $(this).find('i');
                if (icon.hasClass('ri-add-circle-fill')) {
                    icon.removeClass('ri-add-circle-fill').addClass('ri-indeterminate-circle-fill');
                } else {
                    icon.removeClass('ri-indeterminate-circle-fill').addClass('ri-add-circle-fill');
                }
            });
        });




        $(document).ready(function() {
            // Chức năng chọn tất cả
            $(document).ready(function() {
                // Chức năng chọn tất cả cho từng phim
                $('.select-all-movie').on('click', function() {
                    var movieId = $(this).attr('id').split('-')[
                        2]; // Lấy movieId từ ID của checkbox
                    var isChecked = $(this).prop('checked');
                    $('.movie-' + movieId).prop('checked', isChecked);
                });

                // Kiểm tra nếu tất cả checkbox trong từng phim được chọn hoặc bỏ chọn
                $('.select-showtime').on('change', function() {
                    var movieId = $(this).attr('class').match(/movie-(\d+)/)[
                        1]; // Lấy movieId từ class
                    var allChecked = $('.movie-' + movieId).length === $('.movie-' + movieId +
                        ':checked').length;
                    $('#select-all-' + movieId).prop('checked', allChecked);
                });
            });


            // Chức năng xóa tất cả
            $('#delete-all').on('click', function(e) {
                e.preventDefault();
                var selectedIds = [];
                $('.select-showtime:checked').each(function() {
                    selectedIds.push($(this).data('showtime-id'));
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn suất chiếu!',
                        text: 'Vui lòng chọn ít nhất một suất chiếu để xóa.',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận xóa',
                    text: 'Bạn chắc chắn muốn xóa tất cả các suất chiếu đã chọn?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Đang xử lý...',
                            text: 'Vui lòng chờ trong giây lát.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: '{{ route('showtimes.deleteSelected') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                showtime_ids: selectedIds
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: response.message ||
                                        'Các suất chiếu đã được xóa.',
                                    timer: 3000,
                                    timerProgressBar: true
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: 'Không thể xóa các suất chiếu. Vui lòng thử lại.',
                                    timer: 3000
                                });
                            }
                        });
                    }
                });
            });


            // Chức năng thay đổi trạng thái tất cả
            $('#on-status-all').on('click', function(e) {
                e.preventDefault();
                var selectedIds = [];
                $('.select-showtime:checked').each(function() {
                    selectedIds.push($(this).data('showtime-id'));
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn suất chiếu!',
                        text: 'Vui lòng chọn ít nhất một suất chiếu để thay đổi trạng thái.',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận thay đổi trạng thái',
                    text: 'Bạn chắc chắn muốn thay đổi trạng thái các suất chiếu đã chọn?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Thay đổi',
                    cancelButtonText: 'Hủy',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Đang xử lý...',
                            text: 'Vui lòng chờ trong giây lát.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: '{{ route('showtimes.onStatusSelected') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                showtime_ids: selectedIds
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: response.message ||
                                        'Trạng thái đã được cập nhật.',
                                    timer: 3000,
                                    timerProgressBar: true
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: 'Không thể cập nhật trạng thái. Vui lòng thử lại.',
                                    timer: 3000
                                });
                            }
                        });
                    }
                });
            });


            $('#off-status-all').on('click', function(e) {
                e.preventDefault();
                var selectedIds = [];
                $('.select-showtime:checked').each(function() {
                    selectedIds.push($(this).data('showtime-id'));
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn suất chiếu!',
                        text: 'Vui lòng chọn ít nhất một suất chiếu để thay đổi trạng thái.',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận thay đổi trạng thái',
                    text: 'Bạn chắc chắn muốn thay đổi trạng thái các suất chiếu đã chọn?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Thay đổi',
                    cancelButtonText: 'Hủy',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Đang xử lý...',
                            text: 'Vui lòng chờ trong giây lát.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: '{{ route('showtimes.offStatusSelected') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                showtime_ids: selectedIds
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: response.message ||
                                        'Trạng thái đã được cập nhật.',
                                    timer: 3000,
                                    timerProgressBar: true
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: 'Không thể cập nhật trạng thái. Vui lòng thử lại.',
                                    timer: 3000
                                });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
