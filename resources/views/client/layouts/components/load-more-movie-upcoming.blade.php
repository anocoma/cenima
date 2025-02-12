@foreach ($movies as $movie)
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 prs_upcom_slide_first movie-item">
        <div class="movie_box_wrapper">
            <div class="movie_img_box">
                @if ($movie->is_hot == '1')
                    <img class="is_hot" src="{{ asset('theme/client/images/hot.png') }}" alt="">
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
                        <img src="{{ asset('theme/client/images/index_III/icon.png') }}" alt="img">
                    </div>
                </div>
            </div>


            <div class="content-movie">
                <h3 class="movie-name-home">
                    <a href="movies/{{ $movie->slug }}">{{ Str::limit($movie->name, 28) }}</a>
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
                    <button onclick="openModalMovieScrening({{ $movie->id }})" class="buy-ticket-btn">MUA
                        VÉ</button>
                @endif
            </div>


        </div>

    </div>
@endforeach
