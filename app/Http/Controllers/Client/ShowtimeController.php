<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Carbon\Carbon; // Import Carbon để xử lý ngày tháng
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowtimeController extends Controller
{
    public function show()
    {
        // dd(session()->all());
        $cinema = Cinema::where('id', session('cinema_id'))->firstOrFail();

        $dates = [];
        $dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        $currentDate = now(); // Sử dụng Carbon để làm việc với ngày giờ
        $firstAvailableDay = null; // Biến để lưu ngày đầu tiên có suất chiếu

        $now = now()->addMinutes(10);
        if (Auth::check() &&  Auth::user()->type == 'admin') {
            $now = now(); // Thời gian hiện tại khi truy cập vào trang
        }

        $showtimes = Showtime::with(['movie' => function ($query) {
            $query->where('is_active', 1); // Chỉ lấy phim đang active
        }, 'room'])
            ->where([
                ['cinema_id', $cinema->id],
                ['is_active', '1'],
                ['start_time', '>', $now]
            ])
            ->get();

        // dd($showtimes->toArray());

        for ($i = 0; $i < 7; $i++) {
            $formattedDate = $currentDate->format('d/m') . ' - ' . $dayNames[$currentDate->dayOfWeek];

            $filteredShowtimes = $showtimes->filter(function ($showtime) use ($currentDate) {
                return $showtime->date == $currentDate->format('Y-m-d') && $showtime->movie && $showtime->movie->is_active;
            })->groupBy('movie.id');

            foreach ($filteredShowtimes as $movieId => $showtimesGroup) {
                $filteredShowtimes[$movieId] = $showtimesGroup->filter(function ($showtime) use ($now) {
                    $showtimeDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $showtime->start_time);
                    return $showtimeDateTime->gt($now);
                })->sortBy('start_time');
            }

            if ($filteredShowtimes->flatten()->isNotEmpty()) {
                $dates[] = [
                    'day_id' => 'day' . $currentDate->format('z'),
                    'date_label' => $formattedDate,
                    'showtimes' => $filteredShowtimes,
                ];

                if (!$firstAvailableDay) {
                    $firstAvailableDay = 'day' . $currentDate->format('z');
                }
            }

            $currentDate->addDay();
        }

        return view('client.showtimes', compact('dates', 'cinema', 'firstAvailableDay'));
    }
}
