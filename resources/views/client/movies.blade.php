@extends('client.layouts.master')

@section('title')
    Danh sách phim
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/showtime.css') }}" />
@endsection

@section('content')
    <div class="prs_upcom_slider_main_wrapper" style='padding-bottom:80px'>
        <div class="container">
            <div class="row-pro-max">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_heading_section_wrapper">
                        <h2>DANH SÁCH PHIM</h2>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_upcome_tabs_wrapper">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation"> <a href="#best" aria-controls="best" role="tab"
                                    data-toggle="tab">Phim sắp chiếu</a>
                            </li>
                            <li role="presentation" class="active"><a href="#hot" aria-controls="hot" role="tab"
                                    data-toggle="tab">Phim đang chiếu</a>
                            </li>
                            <li role="presentation"><a href="#trand" aria-controls="trand" role="tab"
                                    data-toggle="tab">Suất chiếu đặc biệt</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    {{-- Phim sắp chiếu --}}
                    <div role="tabpanel" class="tab-pane fade" id="best">
                        <div class="tab-pane-content-movie-list">
                            <div class="item">
                                <div class="row" id="movie-upcoming">
                                    @foreach ($moviesUpcoming as $movie)
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first movie-item">
                                            <div class="movie_box_wrapper">
                                                <div class="movie_img_box">
                                                    @if ($movie->is_hot == '1')
                                                        <img class="is_hot" src="{{ asset('theme/client/images/hot.png') }}"
                                                            alt="">
                                                    @endif
                                                    @php
                                                        $imageTag = App\Models\Movie::getImageTagRating($movie->rating);
                                                    @endphp
                                                    @if ($imageTag)
                                                        <img class="tag-rating" src="{{ $imageTag }}" alt="">
                                                    @endif
                                                    @php
                                                        $url = $movie->img_thumbnail;

                                                        if (!\Str::contains($url, 'http')) {
                                                            $url = Storage::url($url);
                                                        }

                                                    @endphp

                                                    <div class='img_thumbnail_movie'>
                                                        <img src="{{ $url }}" alt="movie_img" />
                                                    </div>
                                                    <div class='movie_img_trailer'>
                                                        <div class='animation-icon open-trailer-btn'
                                                            data-trailer-url="https://www.youtube.com/embed/{{ $movie->trailer_url }}"
                                                            data-movie-name="{{ $movie->name }}">
                                                            <img src="{{ asset('theme/client/images/index_III/icon.png') }}"
                                                                alt="img">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="content-movie">
                                                    <h3 class="movie-name-home">
                                                        <a
                                                            href="movies/{{ $movie->slug }}">{{ Str::limit($movie->name, 28) }}</a>
                                                    </h3>
                                                    <p><span class='text-bold'>Thể loại:</span> {{ $movie->category }} </p>
                                                    <p><span class='text-bold'>Thời lượng:</span> {{ $movie->duration }}
                                                        phút </p>
                                                    <p><span class='text-bold'>Ngày khởi chiếu:</span>
                                                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                                    </p>



                                                </div>


                                                @php
                                                    // Kiểm tra có suất chiếu trong 7 ngày tới tại cinema_id
                                                    $hasShowtimeInNextWeek = $movie
                                                        ->showtimes()
                                                        ->where('cinema_id', session('cinema_id')) // Kiểm tra theo cinema_id
                                                        ->where('start_time', '>', $currentNow)
                                                        ->whereDate('date', '<', $endDate)
                                                        ->exists();
                                                @endphp

                                                <div class='buy-ticket-movie'>
                                                    @if ($hasShowtimeInNextWeek)
                                                        <button onclick="openModalMovieScrening({{ $movie->id }})"
                                                            class="buy-ticket-btn">MUA VÉ</button>
                                                    @endif
                                                </div>


                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        @if ($totalMovieUpcoming > 8)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="prs_animate_btn1 prs_upcom_main_wrapper">
                                        <ul>
                                            <li>
                                                <button class="button button--tamaya prs_upcom_main_btn text-white"
                                                    data-text="Xem thêm" id="load-more-movie-upcoming"
                                                    data-max={{ $totalMovieUpcoming }} data-offset="8">Xem thêm</button>

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{-- Phim đang chiếu --}}
                    <div role="tabpanel" class="tab-pane fade  in active" id="hot">
                        <div class="tab-pane-content-movie-list">
                            <div class="item">
                                <div class="row" id="movie-showing">
                                    {{-- @dd($moviesShowing) --}}
                                    @foreach ($moviesShowing as $movie)
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first movie-item">
                                            <div class="movie_box_wrapper">
                                                <div class="movie_img_box">
                                                    @if ($movie->is_hot == '1')
                                                        <img class="is_hot"
                                                            src="{{ asset('theme/client/images/hot.png') }}"
                                                            alt="">
                                                    @endif
                                                    @php
                                                        $imageTag = App\Models\Movie::getImageTagRating($movie->rating);
                                                    @endphp
                                                    @if ($imageTag)
                                                        <img class="tag-rating" src="{{ $imageTag }}" alt="">
                                                    @endif
                                                    @php
                                                        $url = $movie->img_thumbnail;

                                                        if (!\Str::contains($url, 'http')) {
                                                            $url = Storage::url($url);
                                                        }

                                                    @endphp

                                                    <div class='img_thumbnail_movie'>
                                                        <img src="{{ $url }}" alt="movie_img" />
                                                    </div>
                                                    <div class='movie_img_trailer'>
                                                        <div class='animation-icon open-trailer-btn'
                                                            data-trailer-url="https://www.youtube.com/embed/{{ $movie->trailer_url }}"
                                                            data-movie-name="{{ $movie->name }}">
                                                            <img src="{{ asset('theme/client/images/index_III/icon.png') }}"
                                                                alt="img">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="content-movie">
                                                    <h3 class="movie-name-home">
                                                        <a
                                                            href="movies/{{ $movie->slug }}">{{ Str::limit($movie->name, 28) }}</a>
                                                    </h3>
                                                    <p><span class='text-bold'>Thể loại:</span> {{ $movie->category }} </p>
                                                    <p><span class='text-bold'>Thời lượng:</span> {{ $movie->duration }}
                                                        phút </p>

                                                </div>


                                                @php
                                                    // Kiểm tra có suất chiếu trong 7 ngày tới tại cinema_id
                                                    $hasShowtimeInNextWeek = $movie
                                                        ->showtimes()
                                                        ->where('cinema_id', session('cinema_id')) // Kiểm tra theo cinema_id
                                                        ->where('start_time', '>', $currentNow)
                                                        ->whereDate('date', '<', $endDate)
                                                        ->exists();
                                                @endphp

                                                <div class='buy-ticket-movie'>
                                                    @if ($hasShowtimeInNextWeek)
                                                        <button onclick="openModalMovieScrening({{ $movie->id }})"
                                                            class="buy-ticket-btn">MUA VÉ</button>
                                                    @endif
                                                </div>


                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        @if ($totalMovieShowing > 8)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="prs_animate_btn1 prs_upcom_main_wrapper">
                                        <ul>
                                            <li>
                                                <button class="button button--tamaya prs_upcom_main_btn text-white"
                                                    data-text="Xem thêm" id="load-more-movie-showing"
                                                    data-max={{ $totalMovieShowing }} data-offset="8">Xem thêm</button>

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{-- Suất chiếu đặc biệt --}}
                    <div role="tabpanel" class="tab-pane fade" id="trand">
                        <div class="tab-pane-content-movie-list">
                            <div class="item">
                                <div class="row" id="movie-special">
                                    @foreach ($moviesSpecial as $movie)
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first movie-item">
                                            <div class="movie_box_wrapper">
                                                <div class="movie_img_box">
                                                    @if ($movie->is_hot == '1')
                                                        <img class="is_hot"
                                                            src="{{ asset('theme/client/images/hot.png') }}"
                                                            alt="">
                                                    @endif
                                                    @php
                                                        $imageTag = App\Models\Movie::getImageTagRating($movie->rating);
                                                    @endphp
                                                    @if ($imageTag)
                                                        <img class="tag-rating" src="{{ $imageTag }}"
                                                            alt="">
                                                    @endif
                                                    @php
                                                        $url = $movie->img_thumbnail;

                                                        if (!\Str::contains($url, 'http')) {
                                                            $url = Storage::url($url);
                                                        }

                                                    @endphp

                                                    <div class='img_thumbnail_movie'>
                                                        <img src="{{ $url }}" alt="movie_img" />
                                                    </div>
                                                    <div class='movie_img_trailer'>
                                                        <div class='animation-icon open-trailer-btn'
                                                            data-trailer-url="https://www.youtube.com/embed/{{ $movie->trailer_url }}"
                                                            data-movie-name="{{ $movie->name }}">
                                                            <img src="{{ asset('theme/client/images/index_III/icon.png') }}"
                                                                alt="img">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="content-movie">
                                                    <h3 class="movie-name-home">
                                                        <a
                                                            href="movies/{{ $movie->slug }}">{{ Str::limit($movie->name, 28) }}</a>
                                                    </h3>
                                                    <p><span class='text-bold'>Thể loại:</span> {{ $movie->category }}</p>
                                                    <p><span class='text-bold'>Thời lượng:</span> {{ $movie->duration }}
                                                        phút </p>

                                                </div>


                                                @php
                                                    // Kiểm tra có suất chiếu trong 7 ngày tới tại cinema_id
                                                    $hasShowtimeInNextWeek = $movie
                                                        ->showtimes()
                                                        ->where('cinema_id', session('cinema_id')) // Kiểm tra theo cinema_id
                                                        ->where('start_time', '>', $currentNow)
                                                        ->whereDate('date', '<', $endDate)
                                                        ->exists();
                                                @endphp

                                                <div class='buy-ticket-movie'>
                                                    @if ($hasShowtimeInNextWeek)
                                                        <button onclick="openModalMovieScrening({{ $movie->id }})"
                                                            class="buy-ticket-btn">MUA VÉ</button>
                                                    @endif
                                                </div>


                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        @if ($totalMovieSpecial > 8)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="prs_animate_btn1 prs_upcom_main_wrapper">
                                        <ul>
                                            <li>
                                                <button class="button button--tamaya prs_upcom_main_btn text-white"
                                                    data-text="Xem thêm" id="load-more-movie-special"
                                                    data-max={{ $totalMovieSpecial }} data-offset="8">Xem thêm</button>

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('client.modals.movie-trailler')
    @include('client.modals.movie-screning')
@endsection

@section('scripts')
    <script src="{{ asset('theme/client/js/trailler.js') }}"></script>
    <script src="{{ asset('theme/client/js/showtime.js') }}"></script>

     {{-- Xử lý nút xem thêm --}}
     <script>
        ///===Phim đang chiếu===\\\
        $(document).ready(function() {
            $('#load-more-movie-showing').click(function() {
                var offset = $(this).data('offset');
                const max = $(this).data('max')
                var button = $(this); // Nút xem thêm

                $.ajax({
                    url: '{{ route('load-more-movie-showing') }}',
                    method: 'GET',
                    data: {
                        offset: offset
                    },
                    beforeSend: function() {
                        button.prop('disabled', true);
                    },
                    success: function(response) {
                        $('#movie-showing').append(response);

                        button.data('offset', offset + 8);

                        if ($('#movie-showing .movie-item').length >= max) {
                            button.hide();
                        } else {
                            button.prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Đã có lỗi xảy ra. Vui lòng thử lại!');
                        button.prop('disabled', false);
                    }
                });
            });
        });

        ///===Phim sắp chiếu===\\\
        $(document).ready(function() {
            $('#load-more-movie-upcoming').click(function() {
                var offset = $(this).data('offset');
                const max = $(this).data('max')
                var button = $(this); // Nút xem thêm

                $.ajax({
                    url: '{{ route('load-more-movie-upcoming') }}',
                    method: 'GET',
                    data: {
                        offset: offset
                    },
                    beforeSend: function() {
                        button.prop('disabled', true);
                    },
                    success: function(response) {
                        $('#movie-upcoming').append(response);

                        button.data('offset', offset + 8);

                        if ($('#movie-upcoming .movie-item').length >= max) {
                            button.hide();
                        } else {
                            button.prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Đã có lỗi xảy ra. Vui lòng thử lại!');
                        button.prop('disabled', false);
                    }
                });
            });
        });


        ///===Suất chiếu đặc biệt===\\\
        $(document).ready(function() {
            $('#load-more-movie-special').click(function() {
                var offset = $(this).data('offset');
                const max = $(this).data('max')
                var button = $(this); // Nút xem thêm

                $.ajax({
                    url: '{{ route('load-more-movie-special') }}',
                    method: 'GET',
                    data: {
                        offset: offset
                    },
                    beforeSend: function() {
                        button.prop('disabled', true);
                    },
                    success: function(response) {
                        $('#movie-special').append(response);

                        button.data('offset', offset + 8);

                        if ($('#movie-special .movie-item').length >= max) {
                            button.hide();
                        } else {
                            button.prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Đã có lỗi xảy ra. Vui lòng thử lại!');
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
