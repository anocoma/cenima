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
                <h4 class="mb-sm-0">Danh sách Suất chiếu</h4>

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
                <div class="card-header ">
                    {{-- d-flex justify-content-between --}}
                    <div class="row mb-3">
                        <h5 class="card-title mb-0">Danh sách Suất chiếu
                            @if (Auth::user()->cinema_id != '')
                                - {{ Auth::user()->cinema->name }}
                            @endif
                        </h5>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('admin.showtimes.index') }}" method="GET">
                                {{-- TÌm kiếm --}}
                                <div class="row">
                                    @if (Auth::user()->hasRole('System Admin'))
                                        <div class="col-md-3">
                                            <select name="branch_id" id="branch" class="form-select">
                                                <option value="">Chi nhánh</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{-- {{ request('branch_id') == $branch->id ? 'selected' : '' }} --}}
                                                        {{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <select name="cinema_id" id="cinema" class="form-select">
                                                <option value="">Chọn Rạp</option>
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-md-2">
                                            <label for="">Lọc theo ngày:</label>
                                        </div>
                                    @endif


                                    <div class="col-md-3">
                                        {{-- <label for="">Ngày chiếu:</label> --}}
                                        <input type="date" name="date" id="" class="form-control"
                                            value="{{ request('date', now()->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-success" name="btnSearch" type="submit">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="col-md-4" align="right">
                            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-info mb-3 ">Danh sách</a>
                            <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary mb-3 ">Thêm mới</a>
                        </div>
                    </div>

                </div>
                @if (session()->has('success'))
                    <div class="alert alert-success m-3">
                        {{ session()->get('success') }}
                    </div>
                @endif


                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap align-middle"
                        style="width:100%;">
                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Thời gian</th>
                                <th>Tên phim</th>
                                <th>Tên phòng</th>
                                <th>Định dạng</th>
                                <th>Số ghế</th>
                                <th>Ngày chiếu</th>
                                <th>Hoạt động</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Nhóm suất chiếu theo tên phòng và ngày chiếu
                                $groupByShowtimes = $showtimes->groupBy(function ($showtime) {
                                    return $showtime->room->name . '_' . $showtime->date;
                                });
                            @endphp

                            @foreach ($groupByShowtimes as $key => $times)
                                @php
                                    $rowCount = $times->count(); // Số suất chiếu trong nhóm phòng + ngày chiếu
                                @endphp

                                @foreach ($times as $i => $showtime)
                                    @php
                                        $startTime = \Carbon\Carbon::parse($showtime->start_time);
                                        $endTime = \Carbon\Carbon::parse($showtime->end_time);

                                        $movieReleaseDate = \Carbon\Carbon::parse($showtime->movie->release_date);
                                        $movieEndDate = \Carbon\Carbon::parse($showtime->movie->end_date);
                                        $isSpecialShowtime = !$startTime->between($movieReleaseDate, $movieEndDate);

                                        //sắp chiếu
                                        $upComing = $timeNow < $startTime->format('d-m-Y H:i:s');

                                        //đang chiếu
                                        $showing =
                                            $timeNow >= $startTime->format('d-m-Y H:i:s') &&
                                            $timeNow < $endTime->format('d-m-Y H:i:s');
                                        //đã chiếu
                                        $ended =
                                            $timeNow > $startTime->format('d-m-Y H:i:s') &&
                                            $timeNow > $endTime->format('d-m-Y H:i:s');

                                    @endphp
                                    <tr>
                                        {{-- <td>{{ $i + 1 }}</td> --}}
                                        <td>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}</td>
                                        <td>
                                            @if ($showtime->movie->is_special == 1 || $isSpecialShowtime)
                                                <p class="mb-0 ">
                                                    {!! Str::limit($showtime->movie->name, 40) !!}
                                                    <span class="badge bg-danger-subtle text-danger text-uppercase">Đặc biệt
                                                    </span>

                                                </p>
                                            @else
                                                <p class="mb-0">{!! Str::limit($showtime->movie->name, 40) !!}</p>
                                            @endif

                                        </td>

                                        @if ($i == 0)
                                            <!-- Nếu là hàng đầu tiên của nhóm, hiển thị tên phòng và ngày chiếu -->
                                            <td rowspan="{{ $rowCount }}">
                                                <b>{{ $showtime->room->cinema->name }} - {{ $showtime->room->name }}</b>
                                            </td>
                                            <td rowspan="{{ $rowCount }}">
                                                {{ $showtime->format }}
                                            </td>
                                            <td rowspan="{{ $rowCount }}">
                                                {{ $showtime->room->seats->whereNull('deleted_at')->where('is_active', true)->count() }}
                                                / {{ $showtime->room->seats->whereNull('deleted_at')->count() }}

                                                ghế
                                            </td>

                                            <td rowspan="{{ $rowCount }}">
                                                {{ \Carbon\Carbon::parse($showtime->date)->format('d-m-Y') }}
                                            </td>
                                        @endif

                                        <td>
                                            <div class="form-check form-switch form-switch-success d-inline-block">
                                                <input class="form-check-input switch-is-active changeActive"
                                                    name="is_active" type="checkbox" role="switch"
                                                    data-showtime-id="{{ $showtime->id }}" @checked($showtime->is_active)
                                                    onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                            </div>

                                            @if ($showtime->is_active == 1)
                                                @if ($showing)
                                                    <span class="badge bg-danger-subtle text-danger text-uppercase">Đang
                                                        chiếu
                                                    </span>
                                                @elseif($ended)
                                                    <span class="badge bg-primary-subtle text-primary text-uppercase">Đã
                                                        chiếu
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning text-uppercase">Sắp
                                                        chiếu
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.showtimes.show', $showtime) }}">
                                                <button title="xem" class="btn btn-success btn-sm " type="button"><i
                                                        class="fas fa-eye"></i></button></a>

                                            @if ($showtime->is_active == 0)
                                                <a href="{{ route('admin.showtimes.edit', $showtime) }}">
                                                    <button title="xem" class="btn btn-warning btn-sm" type="button"><i
                                                            class="fas fa-edit"></i></button>
                                                </a>

                                                <form action="{{ route('admin.showtimes.destroy', $showtime) }}"
                                                    method="post" class="d-inline-block">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn chắc chắn muốn xóa không?')">
                                                        <i class="ri-delete-bin-7-fill"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6" align='center'>
                            {{ $showtimes->links() }}
                        </div>
                    </div>

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
        // new DataTable("#example", {
        //     order: [
        //     ]
        // });
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
    </script>
    <script>
        $(document).ready(function() {
          $(document).on('change', '.changeActive', function() {

                let showtimeId = $(this).data('showtime-id');
                let is_active = $(this).is(':checked') ? 1 : 0;
                // Gửi yêu cầu AJAX
                $.ajax({
                    url: '{{ route('showtimes.change-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: showtimeId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (!response.success) {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Lỗi kết nối hoặc server không phản hồi.');
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
