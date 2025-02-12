@foreach ($moviesShowing as $movie)
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first">
        <div class="prs_upcom_movie_box_wrapper">
            <div class="prs_upcom_movie_img_box">
                @if ($movie->is_hot == '1')
                    <img class="is_hot" src="{{ asset('theme/client/images/hot.png') }}" alt="">
                @endif
                @php
                    $url = $movie->img_thumbnail;
                    if (!\Str::contains($url, 'http')) {
                        $url = Storage::url($url);
                    }
                @endphp
                <img src="{{ $url }}" alt="movie_img" />
                <div class="prs_upcom_movie_img_overlay"></div>
                <div class="prs_upcom_movie_img_btn_wrapper">
                    <ul>
                        <li>
                            @if ($movie->showtimes->count() > 0)
                                <a onclick="openModalMovieScrening({{ $movie->id }})" class="buy-ticket-btn">Mua
                                    vé</a>
                            @else
                                <a>Không suất chiếu</a>
                            @endif

                        </li>
                        <li><a href="movies/{{ $movie->slug }}">Xem chi tiết</a></li>
                    </ul>
                </div>
            </div>
            <div class="prs_upcom_movie_content_box">
                <div class="prs_upcom_movie_content_box_inner">
                    <h2 class="movie-name-home"><a href="movies/{{ $movie->slug }}">{{ $movie->name }}</a></h2>
                    <p>Thể loại: {{ $movie->category }}</p>
                    <p>Thời lượng: {{ $movie->duration }} phút</p>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                </div>
            </div>
        </div>
    </div>
@endforeach
