<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    public function getShowtimes(Movie $movie)
    {
        // Truy vấn lịch chiếu
        $dates = [];
        $dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

        // Lấy ngày hiện tại
        $currentDate = new \DateTime();
        $startTime = now()->addMinutes(10);
        if (Auth::check() &&  Auth::user()->type == 'admin') {
            $startTime = now();
        }

        for ($i = 0; $i < 7; $i++) {
            // Format ngày và lấy lịch chiếu
            $dayOfWeek = $currentDate->format('w');
            $formattedDate = $currentDate->format('d/m') . ' - ' . $dayNames[$dayOfWeek];
            $showtimes = DB::table('showtimes')
                ->where('showtimes.movie_id', $movie->id)
                ->where('showtimes.cinema_id', session('cinema_id'))
                ->where('showtimes.is_active', 1) // Lọc trực tiếp theo cinema_id trong bảng showtimes
                ->whereDate('showtimes.date', $currentDate)
                ->where('showtimes.start_time', '>', $startTime)
                ->orderBy('showtimes.start_time', 'asc')
                ->get();
            $showtimeFormats  = [];
            foreach ($showtimes as $showtime) {
                if (!isset($showtimeFormats[$showtime->format])) {
                    $showtimeFormats[$showtime->format] = [];
                }
                // Nếu phim chưa có trong mảng của định dạng, thêm vào
                if (!in_array($showtime, $showtimeFormats[$showtime->format])) {
                    $showtimeFormats[$showtime->format][] = $showtime;
                }
            }

            if (!$showtimes->isEmpty()) {

                $dates[] = [
                    'day_id' => 'day' . $currentDate->format('z'),
                    'date_label' => $formattedDate,
                    'showtimes' => $showtimeFormats,
                ];
            }
            // Cộng thêm 1 ngày
            $currentDate->add(new \DateInterval('P1D'));
        }
        return response()->json(['dates' => $dates, 'movie' => $movie]);
    }



    public function updateActive(Request $request)
    {
        try {
            $movie = Movie::findOrFail($request->id);

            $movie->is_active = $request->is_active;
            $movie->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.','data'=>$movie]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.']);
        }
    }




    public function updateHot(Request $request)
    {
        try {
            $movie = Movie::findOrFail($request->id);

            $movie->is_hot = $request->is_hot;
            $movie->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.','data'=>$movie]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.']);
        }
    }
}
