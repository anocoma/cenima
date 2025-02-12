<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {


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

        return view('client.movies', compact('moviesUpcoming', 'moviesShowing', 'moviesSpecial', 'currentNow', 'endDate','totalMovieShowing','totalMovieUpcoming','totalMovieSpecial'));
    }
}
