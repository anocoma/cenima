@extends('admin.layouts.master')

@section('title')
    Chi tiết suất chiếu
@endsection

@section('styles')
    <style>
        .available {
            background-color: green;
        }

        .reserved {
            background-color: yellow;
        }

        .sold {
            background-color: red;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/mainstyle.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/responsive.css') }}" />
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thông tin suất chiếu</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.movies.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- thông tin -->

    <div class="row">
        {{-- Ghế --}}
        <div class="col-xl-9">
            <div class="card" style="padding: 17px 70px 62px 70px;">
                <div class="st_dtts_left_main_wrapper float_left">
                    <div class="row">
                        <div class="col-md-12 box-list-status-seat">
                            <div class="border my-3">
                                <div class="list-seats"><span class="mdi--love-seat text-muted"></span>
                                    <span class="status-seat">Ghế trống</span>
                                </div>
                                <div class="list-seats"><span class="mdi--love-seat text-locked"></span>
                                    <span class="status-seat">Ghế hỏng</span>
                                </div>
                                <div class="list-seats"><span class="mdi--love-seat" style="color: #ff7307"></span>
                                    <span class="status-seat">Ghế đã bán</span>
                                </div>
                            </div>
                            <div class="">
                                <div>
                                    <div class="container-screen">

                                        <div class="container-detail-seat">
                                            <div class="screen" style="font-weight: 500">Màn Hình Chiếu</div>
                                            <div class="seat-selection">
                                                <table
                                                    class="table-chart-chair table-none align-middle mx-auto text-center">
                                                    <tbody>
                                                        @for ($row = 0; $row < $matrixSeat['max_row']; $row++)
                                                            <tr>
                                                                {{-- cột hàng ghế A,B,C --}}
                                                                {{-- <td class="row-seat">
                                                                        {{ chr(65 + $row) }}
                                                                    </td> --}}
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
                                                                        <!-- Nếu là ghế đôi -->
                                                                        <td class="row-seat" colspan="2">
                                                                            <span
                                                                                class="game-icons--sofa seat-double seat span-seat {{ $seat->pivot->status }} {{ $seat->is_active == 0 ? 'seat-is_active' : '' }}">
                                                                                <span
                                                                                    class="seat-label">{{ chr(65 + $row) . ($col + 1) }}
                                                                                    {{ chr(65 + $row) . ($col + 2) }}</span>
                                                                            </span>
                                                                        </td>
                                                                        @php $col++; @endphp
                                                                    @else
                                                                        <td class="row-seat">
                                                                            <div class="seat-item change-active">
                                                                                @switch($seat->type_seat_id ?? "")
                                                                                    @case(1)
                                                                                        <span
                                                                                            class="solar--sofa-3-bold seat span-seat {{ $seat->pivot->status }} {{ $seat->is_active == 0 ? 'seat-is_active' : '' }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                    @break

                                                                                    @case(2)
                                                                                        <span
                                                                                            class="mdi--love-seat text-muted seat span-seat {{ $seat->pivot->status }} {{ $seat->is_active == 0 ? 'seat-is_active' : '' }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
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
                                        <div style="display: flex;justify-content: center;">
                                            <div class="legend" style="display: flex;justify-content: space-between;">
                                                <div><span class="solar--sofa-3-bold text-muted"></span> Ghế Thường</div>
                                                <div><span class="mdi--love-seat text-muted"></span> Ghế Vip</div>
                                                <div><span class="game-icons--sofa text-muted"></span> Ghế Đôi</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>

        <!--Chi tiết phim-->
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0"><i
                                class=" ri-film-line align-middle me-1 text-muted"></i>{{ $showtime->movie->name }}</h5>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);"
                                class="badge bg-primary-subtle text-primary fs-11">{{ $showtime->movieVersion->name }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    @php
                        $url = $showtime->movie->img_thumbnail;

                        if (!\Str::contains($url, 'http')) {
                            $url = Storage::url($url);
                        }
                    @endphp
                    @if ($showtime->movie->img_thumbnail)
                        <div class="text-center">
                            <img src="{{ $url }}" alt="" width="40%" class="rounded-2">
                        </div>
                    @else
                        No image !
                    @endif

                </div>
            </div>
            <!--end card-->

            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Thông tin phim</h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 vstack gap-3">
                        <li><b>Thể loại</b>: {{ $showtime->movie->category }}</li>
                        <li><b>Thời lượng</b>: {{ $showtime->movie->duration }} phút</li>
                        <li><b>Định dạng</b>: {{ $showtime->format }}</li>
                        <li><b>Độ tuổi:</b> {{ $showtime->movie->rating }} -
                            {{ $showtime->movie->getRatingByName($showtime->movie->rating)['description'] }}</li>
                        <li><b>Trạng thái</b>: {{ $showtime->movie->is_active == 1 ? 'Hoạt động' : 'Đã dừng' }}</li>
                    </ul>
                </div>
            </div>
            <!--end card-->

            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Thông tin suất chiếu</h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 vstack gap-3">
                        <li><b>Phòng</b>: {{ $showtime->room->name }}</li>
                        <li><b>Rạp</b>: {{ $showtime->room->cinema->name }} </li>
                        <li><b>Lịch chiếu</b>: {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} ~
                            {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }} (
                            {{ \Carbon\Carbon::parse($showtime->start_time)->format('d/m/Y') }} )</li>
                        <li><b>Trạng thái</b>: {{ $showtime->is_active == 1 ? 'Đang bán' : 'Dừng bán' }}</li>
                        {{-- <li style="color: red;"><b>Ghế đã bán</b>: 10/150 (not work)</li>
                        <li style="color: red;"><b>Ghế hỏng</b>: 0/150 (not work)</li> --}}
                    </ul>
                </div>
            </div>
            <!--end card-->

        </div>
        <!--end col-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <a href="{{ route('admin.showtimes.index') }}" class="btn btn-info">Danh sách</a>
                    <a href="{{ route('admin.showtimes.edit', $showtime) }}">
                        <button type="submit" class="btn btn-warning mx-1">Chỉnh sửa</button>
                    </a>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>

@endsection

@section('style-libs')
    <!-- App favicon -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('theme/admin/assets/js/pages/select2.init.js') }}"></script>
    <script src="https:////cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
    <script>
        CKEDITOR.replace("content", {
            width: "100%",
            height: "750px"
        });
    </script>
@endsection
