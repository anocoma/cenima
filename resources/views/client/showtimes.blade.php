@extends('client.layouts.master')

@section('title')
    Lịch chiếu
@endsection

@section('content')
    <div class="container">
        <div class="">
            <!-- Tabs hiển thị các ngày trong tuần -->
            {{-- @if (isset($dates) && count($dates) > 0)
                <div class="top-bot">
                    <!-- Date Picker -->
                    <div class="listMovieScrening-date">
                        @foreach ($dates as $date)
                            <div data-day="{{ $date['day_id'] }}"
                                class="movieScrening-date-item {{ $loop->first ? 'active' : '' }}">
                                {{ $date['date_label'] }}
                            </div>
                        @endforeach
                    </div>
                </div>

                @foreach ($dates as $date)
                    <div class="row_content" data-day="{{ $date['day_id'] }}" style="{{ $loop->first ? '' : 'display:none;' }}">
                        <!-- Chỉ hiển thị hàng đầu tiên -->
                        @foreach ($date['showtimes'] as $movieId => $showtimes)
                            @php
                                // Lọc các suất chiếu hợp lệ, chỉ những suất chiếu sau thời gian hiện tại
                                $validShowtimes = $showtimes->filter(function ($showtime) {
                                    return \Carbon\Carbon::parse($showtime->start_time, 'Asia/Ho_Chi_Minh')->isAfter(
                                        \Carbon\Carbon::now('Asia/Ho_Chi_Minh'),
                                    );
                                });
                            @endphp

                            @if ($validShowtimes->isNotEmpty() && $validShowtimes->first() && $validShowtimes->first()->movie)
                                <!-- Chỉ hiển thị nếu có suất chiếu hợp lệ -->
                                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-8" style="margin-bottom: 30px">
                                    <div class="col-md-4 image-movie-detail">
                                        <img src="{{ $validShowtimes->first()->movie->img_thumbnail }}"
                                            class="movie-poster">
                                    </div>
                                    <div class="movie-detail-content">
                                        <h1 class="movie-title">{{ $validShowtimes->first()->movie->name }}</h1>
                                        <ul class="movie-info">
                                            <li><strong>Thể loại:</strong> {{ $validShowtimes->first()->movie->category }}
                                            </li>
                                            <li><strong>Thời lượng:</strong> {{ $validShowtimes->first()->movie->duration }}
                                                phút</li>
                                        </ul>
                                        <!-- Lịch chiếu phim -->
                                        <div class="showtime-section">
                                            @foreach ($validShowtimes->groupBy('format') as $format => $times)
                                                @php
                                                    // Lọc các suất chiếu hợp lệ trong từng format
                                                    $validTimes = $times->filter(function ($showtime) {
                                                        return \Carbon\Carbon::parse(
                                                            $showtime->start_time,
                                                            'Asia/Ho_Chi_Minh',
                                                        )->isAfter(\Carbon\Carbon::now('Asia/Ho_Chi_Minh'));
                                                    });
                                                @endphp

                                                @if ($validTimes->isNotEmpty())
                                                    <!-- Chỉ hiển thị format nếu có suất chiếu hợp lệ -->
                                                    <div class="showtime-bot">
                                                        <h4 class="showtime-title">{{ $format }}</h4>
                                                        <div class="showtime-list">
                                                            @foreach ($validTimes as $showtime)
                                                                <a href="{{ route('choose-seat', $showtime->id) }}"
                                                                    class="showtime-btn">
                                                                    {{ \Carbon\Carbon::parse($showtime->start_time, 'Asia/Ho_Chi_Minh')->format('H:i') }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="div-1">
                    <p class="p-1">Hiện tại rạp đang không có suất chiếu nào!</p>
                </div>
            @endif --}}

            @if (isset($dates) && count($dates) > 0)
                <div class="top-bot">
                    <!-- Date Picker -->
                    <div class="listMovieScrening-date">
                        @php
                            $firstActiveSet = false; // Biến này để kiểm tra xem đã đánh dấu ngày nào là active hay chưa

                            $user = auth()->user();
                            $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                        @endphp
                        @foreach ($dates as $date)
                            @php
                                // Lọc lại toàn bộ suất chiếu hợp lệ cho ngày này
                                $validMoviesForDate = collect($date['showtimes'])->filter(function ($showtimes) use (
                                    $user,
                                    $now,
                                ) {
                                    return $showtimes
                                        ->filter(function ($showtime) use ($user, $now) {
                                            $startTime = \Carbon\Carbon::parse(
                                                $showtime['start_time'], 'Asia/Ho_Chi_Minh',
                                            );

                                            if (!$user || $user->type === 'member') {
                                                return $startTime->gt($now->addMinutes(10));
                                            }
                                            return $startTime->gt($now);
                                        })
                                        ->isNotEmpty();
                                });
                            @endphp

                            @if ($validMoviesForDate->isNotEmpty())
                                <div data-day="{{ $date['day_id'] }}"
                                    class="xanh-fpt1 movieScrening-date-item {{ !$firstActiveSet ? 'active' : '' }} ">
                                    {{ $date['date_label'] }}
                                </div>
                                @php
                                    // Đánh dấu rằng đã set active cho ngày đầu tiên có suất chiếu hợp lệ
                                    $firstActiveSet = true;
                                @endphp
                            @endif
                        @endforeach
                    </div>
                </div>

                @php
                    $firstActiveSet = false; // Đặt lại biến để xử lý phần row_content

                    $user = auth()->user();
                    $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                @endphp

                @foreach ($dates as $date)
                    @php
                        // Lọc lại toàn bộ suất chiếu hợp lệ cho ngày này
                        $validMoviesForDate = collect($date['showtimes'])->filter(function ($showtimes) use (
                            $user,
                            $now,
                        ) {
                            return $showtimes
                                ->filter(function ($showtime) use ($user, $now) {
                                    $startTime = \Carbon\Carbon::parse($showtime['start_time'], 'Asia/Ho_Chi_Minh');

                                    if (!$user || $user->type === 'member') {
                                        return $startTime->gt($now->addMinutes(10));
                                    }
                                    return $startTime->gt($now);
                                })
                                ->isNotEmpty();
                        });
                    @endphp

                    @if ($validMoviesForDate->isNotEmpty())
                        <div class="row_content" data-day="{{ $date['day_id'] }}"
                            style="{{ !$firstActiveSet ? '' : 'display:none;' }}">
                            <!-- Chỉ hiển thị nếu có phim và suất chiếu hợp lệ -->
                            @foreach ($date['showtimes'] as $movieId => $showtimes)
                                @php
                                    // Lọc các suất chiếu hợp lệ cho bộ phim này
                                    $validShowtimes = $showtimes->filter(function ($showtime) {
                                        return \Carbon\Carbon::parse(
                                            $showtime['start_time'],
                                            'Asia/Ho_Chi_Minh',
                                        )->isAfter(\Carbon\Carbon::now('Asia/Ho_Chi_Minh'));
                                    });
                                @endphp

                                @if ($validShowtimes->isNotEmpty() && $validShowtimes->first() && $validShowtimes->first()->movie)
                                    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-8" style="margin-bottom: 20px">
                                        <div class="col-md-3">
                                            <img src="{{ $validShowtimes->first()->movie->img_thumbnail }}"
                                                class="movie-poster">
                                        </div>
                                        <div class="movie-detail-content">
                                            <h1 class="movie-title"><a
                                                    href="movies/{{ $validShowtimes->first()->movie->slug }}"
                                                    class="xanh-fpt1">{{ $validShowtimes->first()->movie->name }}</a></h1>
                                            <ul class="movie-info">
                                                <span style="margin-right: 15px">
                                                    <i class="fa fa-tags icons"></i>
                                                    {{ $validShowtimes->first()->movie->category }}
                                                </span>
                                                <span>
                                                    <i class="fa fa-clock-o icons"></i>
                                                    {{ $validShowtimes->first()->movie->duration }} phút
                                                </span>
                                            </ul>
                                            <!-- Lịch chiếu phim -->
                                            <div class="showtime-section">
                                                @foreach ($validShowtimes->groupBy('format') as $format => $times)
                                                    @php
                                                        // Lọc các suất chiếu hợp lệ trong từng format
                                                        $validTimes = $times->filter(function ($showtime) {
                                                            return \Carbon\Carbon::parse(
                                                                $showtime['start_time'],
                                                                'Asia/Ho_Chi_Minh',
                                                            )->isAfter(\Carbon\Carbon::now('Asia/Ho_Chi_Minh'));
                                                        });
                                                    @endphp

                                                    @if ($validTimes->isNotEmpty())
                                                        <!-- Chỉ hiển thị format nếu có suất chiếu hợp lệ -->
                                                        <div class="showtime-bot">
                                                            <h4 class="showtime-title">{{ $format }}</h4>
                                                            <div class="showtime-list">
                                                                @foreach ($validTimes as $showtime)
                                                                    <a href="{{ route('choose-seat', $showtime->slug) }}"
                                                                        class="showtime-btn">
                                                                        {{ \Carbon\Carbon::parse($showtime['start_time'], 'Asia/Ho_Chi_Minh')->format('H:i') }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endif
                            @endforeach
                        </div>
                        @php
                            // Đánh dấu rằng đã set active cho ngày đầu tiên có suất chiếu hợp lệ
                            $firstActiveSet = true;
                        @endphp
                    @endif
                @endforeach
            @else
                <div class="div-1">
                    <p class="p-1">Hiện tại rạp đang không có suất chiếu nào!</p>
                </div>
            @endif

        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/showtimes-cinema.css') }}" />
@endsection

@section('scripts')
    <script>
        // Xử lý cho các tab ngày
        document.querySelectorAll('.movieScrening-date-item').forEach(tab => {
            tab.addEventListener('click', function(event) {
                // Xóa lớp active khỏi tất cả các tab
                document.querySelectorAll('.movieScrening-date-item').forEach(btn => btn.classList.remove(
                    'active'));
                this.classList.add('active');

                // Lấy ID ngày đã chọn
                const dayId = this.getAttribute('data-day');

                // Ẩn tất cả các hàng lịch chiếu
                document.querySelectorAll('.row_content').forEach(row_content => {
                    // Ẩn tất cả các hàng
                    row_content.style.display = 'none';
                });

                // Hiển thị lịch chiếu cho ngày đã chọn
                const selectedRow = document.querySelector(`.row_content[data-day="${dayId}"]`);
                if (selectedRow) {
                    selectedRow.style.display = 'block'; // Hiện hàng cho ngày đã chọn
                }
            });
        });
    </script>
@endsection
