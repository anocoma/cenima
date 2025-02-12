@extends('admin.layouts.master')

@section('title')
    Đặt vé tại quầy
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
                <h4 class="mb-sm-0">Đặt vé tại quầy</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Lịch chiếu</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        {{-- <div class="container">
            @foreach ($groupedShowtimes as $dateLabel => $data)
                <h2 id="{{ $data['date_id'] }}">{{ $dateLabel }}</h2>
                @foreach ($data['movies'] as $movie)
                    <h3>{{ $movie['movie']->name }}</h3> <!-- Tên phim -->
                    <p>Thể loại: {{ $movie['movie']->genre ?? 'N/A' }}</p> <!-- Thể loại phim -->
                    <p>Thời lượng: {{ $movie['movie']->duration ?? 'N/A' }} phút</p> <!-- Thời gian phim -->
                    @foreach ($movie['showtime_formats'] as $format => $formatData)
                        <h4>{{ $format }}</h4>
                        @foreach ($formatData['showtimes'] as $showtime)
                            <p>Giờ chiếu: {{ $showtime['start_time'] }} - Số ghế trống: {{ $showtime['available_seats'] }}
                            </p>
                        @endforeach
                    @endforeach
                @endforeach
                <hr>
            @endforeach
        </div> --}}



        <div class="col-lg-12">
            <div class="card">
                {{-- fillter --}}
                <div class="card-body border border-dashed border-end-0 border-start-0">
                    <form>
                        <div class="row g-3">
                            <div class="col-xxl-5 col-sm-6">
                                <div class="search-box">
                                    <input type="text" class="form-control search" placeholder="Tìm kiếm theo tên phim">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->
                            <!--end col-->
                            <div class="col-xxl-2 col-sm-4">
                                <div>
                                    <select name="branch_id" id="branch" class="form-select">
                                        <option value="">Chi nhánh</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-2 col-sm-4">
                                <div>
                                    <select name="cinema_id" id="cinema" class="form-select">
                                        <option value="">Chọn Rạp</option>
                                    </select>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-1 col-sm-4">
                                <div>
                                    <button type="submit" class="btn btn-primary w-100"><i
                                            class="ri-equalizer-fill me-1 align-bottom"></i>
                                        Lọc
                                    </button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <div class="card-body pt-0">
                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                        @foreach ($groupedShowtimes as $dateLable => $data)
                            <li class="nav-item fs-5">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }} py-3" data-bs-toggle="tab"
                                    href="#{{ $data['date_id'] }}" role="tab" aria-selected="true">
                                    {{ $dateLable }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="card-body tab-content ">
                        @foreach ($groupedShowtimes as $dateLable => $data)
                            <div class="tab-pane {{ $loop->first ? 'show active' : '' }} " id="{{ $data['date_id'] }}"
                                role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap  w-100">
                                    <thead class='table-light'>
                                        <tr>
                                            <th colspan="2" class='text-center'>Lịch chiếu phim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['movies'] as $movie)
                                            <tr>
                                                <td class='text-center' style='width: 20%'>

                                                    @php
                                                        $url = $movie['movie']->img_thumbnail;

                                                        if (!\Str::contains($url, 'http')) {
                                                            $url = Storage::url($url);
                                                        }

                                                    @endphp
                                                    @if (!empty($movie['movie']->img_thumbnail))
                                                        <img src="{{ $url }}" alt="" width="230px">
                                                    @else
                                                        No image !
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="mb-2">
                                                            <h3>{{ $movie['movie']->name }}</h3>
                                                            <ul class="nav nav-sm flex-column">
                                                                <li class="nav-item mb-1"><span class="fw-semibold">Thể
                                                                        loại:</span>
                                                                    {{ $movie['movie']->category }}</li>
                                                                <li class="nav-item mb-1"><span class="fw-semibold">Thời
                                                                        lượng:</span>
                                                                    {{ $movie['movie']->duration }}</li>
                                                            </ul>
                                                            <hr>
                                                        </div>

                                                        <div>
                                                            @foreach ($movie['showtime_formats'] as $format => $formatData)
                                                                <h5>{{ $format }}</h5>
                                                                @foreach ($formatData['showtimes'] as $showtime)
                                                                    <div class="list-showtimes">
                                                                        <div class="showtime-item">
                                                                            <a
                                                                                href="{{ route('admin.book-tickets.show', $showtime) }}">
                                                                                <div class="showtime-item-start-time">
                                                                                    {{ Carbon\Carbon::parse($showtime['start_time'])->format('H:i') }}
                                                                                </div>
                                                                            </a>

                                                                            <div class="empty-seat-showtime">150 ghế trống
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>
                        @endforeach
                    </div>

                </div>


            </div>
        </div><!--end col-->
    </div><!--end row-->
@endsection


@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Xử lý sự kiện thay đổi chi nhánh
            $('#branch').on('change', function() {
                var branchId = $(this).val();
                var cinemaSelect = $('#cinema');
                cinemaSelect.empty();
                cinemaSelect.append('<option value="">Chọn Rạp</option>');

                if (branchId) {
                    $.ajax({
                        url: "{{ url('api/cinemas') }}/" + branchId,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, cinema) {
                                cinemaSelect.append('<option value="' + cinema.id +
                                    '" >' + cinema.name + '</option>');
                            });

                            // Chọn lại cinema nếu có selectedCinemaId
                            if (selectedCinemaId) {
                                cinemaSelect.val(selectedCinemaId);
                                // selectedCinemaId = false;
                            }
                        }
                    });
                }
            });

           
        });
    </script>
@endsection
