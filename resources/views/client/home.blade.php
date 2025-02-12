@extends('client.layouts.master')

@section('title')
    Trang Chủ
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/showtime.css') }}" />
@endsection

@section('content')
    @include('client.layouts.slideshow')


    <div class="prs_upcom_slider_main_wrapper">
        <div class="container">
            <div class="row">
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
    <div class="prs_feature_slider_main_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_heading_section_wrapper">
                        <h2>Tin tức & ưu đãi</h2>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_feature_slider_wrapper" id="post-content">
                        <div class="owl-carousel owl-theme show-post">
                            @foreach ($posts as $postItem)
                                <div class="item prs_feature_slider_item_wrapper">
                                    <div class="prs_feature_img_box_wrapper">
                                        <div class="prs_feature_img"
                                            style="position: relative; overflow: hidden; width: 100%; height: 200px;">
                                            @php
                                                $url = $postItem->img_post;

                                                if (!\Str::contains($url, 'http')) {
                                                    $url = Storage::url($url);
                                                }
                                            @endphp
                                            <img src="{{ $url }}" alt="Chưa có ảnh"
                                                style="width: 100%; height: 100%; object-fit: cover;" />
                                            <div class="prs_ft_btn_wrapper">
                                                <ul>
                                                    <li><a href="{{ route('posts.show', $postItem->slug) }}">Xem chi
                                                            tiết</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="prs_feature_img_cont">
                                            <h4 class="title-post" >{!! Str::limit($postItem->title, 45) !!}</h4 >
                                            <br>
                                            <div class="prs_ft_small_cont_center">
                                                {!! Str::limit($postItem->description, 70) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @include('client.modals.movie-trailler')
    @include('client.modals.movie-screning')
@endsection

@section('scripts')
    <script src="{{ asset('theme/client/js/showtime.js') }}"></script>
    <script src="{{ asset('theme/client/js/trailler.js') }}"></script>
@endsection

@section('style-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>





    {{-- <script>
        // Ajax load xem thêm 3 tab
        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('load-more2').addEventListener('click', function() {
                const button = this;
                const page = button.getAttribute('data-page');

                fetch(`/movies2?page=${page}`, {
                        method: 'GET',

                    })
                    .then(response => response.text()) // Đảm bảo nhận về dữ liệu dạng text (HTML)
                    .then(data => {
                        const movieList2 = document.getElementById('movie-list2');
                        // console.log(data);

                        if (data.trim().length > 0) {
                            movieList2.innerHTML += data;

                        } else {
                            // Nếu không có phim để thêm, ẩn nút "Xem thêm"
                            button.style.display = 'none';
                        }

                        button.setAttribute('data-page', parseInt(page) + 1);
                    })
                    .catch(error => console.error('Error:', error));
            });

        });


        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('load-more1').addEventListener('click', function() {
                const button = this;
                const page = button.getAttribute('data-page');

                fetch(`/movies1?page=${page}`, {
                        method: 'GET',

                    })
                    .then(response => response.text()) // Đảm bảo nhận về dữ liệu dạng text (HTML)
                    .then(data => {
                        const movieList1 = document.getElementById('movie-list1');
                        // console.log(data);

                        if (data.trim().length > 0) {
                            movieList1.innerHTML += data;

                        } else {
                            // Nếu không có phim để thêm, ẩn nút "Xem thêm"
                            button.style.display = 'none';
                        }

                        button.setAttribute('data-page', parseInt(page) + 1);
                    })
                    .catch(error => console.error('Error:', error));

            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('load-more3').addEventListener('click', function() {
                // console.log('lcik odasjkf');
                const button = this;
                const page = button.getAttribute('data-page');


                fetch(`/movies3?page=${page}`, {
                        method: 'GET',

                    })
                    .then(response => response.text()) // Đảm bảo nhận về dữ liệu dạng text (HTML)
                    .then(data => {
                        const movieList3 = document.getElementById('movie-list3');
                        console.log(data);

                        if (data.trim().length > 0) {
                            movieList3.innerHTML += data;

                        } else {
                            // Nếu không có phim để thêm, ẩn nút "Xem thêm"
                            button.style.display = 'none';
                        }

                        button.setAttribute('data-page', parseInt(page) + 1);
                    })
                    .catch(error => console.error('Error:', error));
            })
        });
    </script> --}}


    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {

            const loadMoreMovieBtn = document.getElementById('load-more-movie');
            const current = document.getElementById('movie-showing');

            loadMoreMovieBtn.addEventListener('click', function() {
                fetch(`route('load-more-movie')`)
                    .then((response) => response.json())
                    .catch((error) => console.error('Error loading products:', error));
            });
        });
    </script> --}}

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
