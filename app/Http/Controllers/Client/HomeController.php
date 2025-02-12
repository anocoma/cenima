<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Slideshow;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    //
    // const PATH_VIEW = 'client.';
    // const PATH_UPLOAD = 'home';


    public function home()
    {
        $slideShows = Slideshow::where('is_active', 1)->get();

        $currentNow = now();
        $endDate = now()->addDays(7);

        // phim đang chiếu
        $moviesShowing = Movie::where([
            ['is_active', '1'],
            ['is_publish', '1'],
            ['release_date', '<=', $currentNow],
            ['end_date', '>=', $currentNow]
        ]);
        $totalMovieShowing = $moviesShowing->count();
        $moviesShowing = $moviesShowing->orderBy('is_hot', 'desc')
            ->latest('id')
            ->limit(8)
            ->get();



        // Phim sắp chiếu
        $moviesUpcoming = Movie::where([
            ['is_active', '1'],
            ['is_publish', '1'],
            ['release_date', '>', $currentNow]
        ]);
        $totalMovieUpcoming = $moviesUpcoming->count();
        $moviesUpcoming = $moviesUpcoming->orderBy('is_hot', 'desc')
            ->latest('id')
            ->limit(8)
            ->get();



        // Phim suất chiếu đặc biệt (chưa đến ngày khởi chiếu hoặc đã hết thời gian khởi chiếu)
        $moviesSpecial = Movie::where([
            ['is_active', '1'],
            ['is_publish', '1'],
            ['is_special', '1']
        ]);
        $totalMovieSpecial = $moviesSpecial->count();
        $moviesSpecial = $moviesSpecial
            ->orderBy('is_hot', 'desc')
            ->latest('id')
            ->limit(8)->get();


        $posts = Post::where('is_active', 1)->orderBy('created_at', 'desc')->take(5)->get();

        return view('client.home', compact('moviesUpcoming', 'moviesShowing', 'moviesSpecial', 'slideShows', 'posts', 'currentNow', 'endDate', 'totalMovieShowing', 'totalMovieUpcoming', 'totalMovieSpecial'));
    }

    public function policy()
    {
        return view('client.policy');
    }


    public function getShowtimes($movieId)
    {
        $showtimes = Showtime::with(['room.cinema', 'movieVersion', 'movie'])
            ->where('movie_id', $movieId)
            ->where('is_active', '1')
            ->get();

        return response()->json($showtimes);
    }






    public function loadMoreMovieShowing(Request $request)
    {
        $currentNow = now();
        $endDate = now()->addDays(7);
        $offset = $request->offset ?? 8;

        // Lọc danh sách phim sắp chiếu
        $movies = Movie::where([
            ['is_active', '1'],
            ['is_publish', '1'],
            ['release_date', '<=', $currentNow],
            ['end_date', '>=', $currentNow]
        ])
            ->orderBy('is_hot', 'desc')
            ->latest('id')
            ->skip($offset)
            ->limit(8)
            ->get();

        return view('client.layouts.components.load-more-movie-showing', compact('movies', 'currentNow', 'endDate'))->render();
    }
    public function loadMoreMovieUpcoming(Request $request)
    {
        $currentNow = now();
        $endDate = now()->addDays(7);
        $offset = $request->offset ?? 8;

        // phim đang chiếu
        $movies = Movie::where([
            ['is_active', '1'],
            ['is_publish', '1'],
            ['release_date', '>', $currentNow]
        ])
            ->orderBy('is_hot', 'desc')
            ->latest('id')
            ->skip($offset)
            ->limit(8)
            ->get();

        return view('client.layouts.components.load-more-movie-upcoming', compact('movies', 'currentNow', 'endDate'))->render();
    }

    public function loadMoreMovieSpecial(Request $request)
    {
        $currentNow = now();
        $endDate = now()->addDays(7);
        $offset = $request->offset ?? 8;

        // Phim suất chiếu đặc biệt (chưa đến ngày khởi chiếu hoặc đã hết thời gian khởi chiếu)
        $movies = Movie::where([
            ['is_active', '1'],
            ['is_publish', '1'],
            ['is_special', '1']
        ])
            ->orderBy('is_hot', 'desc')
            ->latest('id')
            ->skip($offset)
            ->limit(8)
            ->get();

        return view('client.layouts.components.load-more-movie-special', compact('movies', 'currentNow', 'endDate'))->render();
    }
}
