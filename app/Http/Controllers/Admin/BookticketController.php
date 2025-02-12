<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Combo;
use App\Models\Seat;
use App\Models\SeatTemplate;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookTicketController extends Controller
{
    const PATH_VIEW = 'admin.book-tickets.';

    // public function __construct()
    // {
    //     $this->middleware('can:Danh sách đặt vé')->only('index');
    //     $this->middleware('can:Thêm đặt vé')->only(['create', 'store']);
    //     $this->middleware('can:Sửa đặt vé')->only(['edit', 'update']);
    //     $this->middleware('can:Xóa đặt vé')->only('destroy');
    // }
    public function index()
    {

        $now = Carbon::now('Asia/Ho_Chi_Minh');

        $showtimes = Showtime::with('movie')
            ->where('is_active', 1)
            ->whereDate('date', '>=', $now->toDateString())
            ->where('start_time', '>', $now)
            ->orderBy('start_time', 'asc')
            ->get();

        $dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        $groupedShowtimes = [];

        // Nhóm suất chiếu theo ngày
        foreach ($showtimes as $showtime) {
            $date = Carbon::parse($showtime->date);
            $formattedDate = $date->format('d/m') . ' - ' . $dayNames[$date->dayOfWeek];
            $dateId = $date->format('z');

            // Tạo cấu trúc dữ liệu nếu chưa tồn tại
            if (!isset($groupedShowtimes[$formattedDate])) {
                $groupedShowtimes[$formattedDate] = [
                    'date_label' => $formattedDate,
                    'date_id' => $dateId,
                    'movies' => [],
                ];
            }

            $movieId = $showtime->movie->id;
            $format = $showtime->format;

            // Tìm kiếm phim trong danh sách
            if (!isset($groupedShowtimes[$formattedDate]['movies'][$movieId])) {
                $groupedShowtimes[$formattedDate]['movies'][$movieId] = [
                    'movie' => $showtime->movie,
                    'showtime_formats' => [],
                ];
            }

            // Thêm showtime vào showtime_formats
            if (!isset($groupedShowtimes[$formattedDate]['movies'][$movieId]['showtime_formats'][$format])) {
                $groupedShowtimes[$formattedDate]['movies'][$movieId]['showtime_formats'][$format] = [
                    'showtimes' => [],
                ];
            }

            // Thêm đối tượng showtime vào showtimes
            $groupedShowtimes[$formattedDate]['movies'][$movieId]['showtime_formats'][$format]['showtimes'][] = $showtime;
        }
        $cinemas = Cinema::all();
        $branches = Branch::all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('groupedShowtimes', 'cinemas', 'branches'));
    }


    public function show(Showtime $showtime)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        // Kiểm tra và khởi tạo thời gian bắt đầu
        if (!session()->has('end_time')) {
            // Nếu chưa tồn tại trong session, khởi tạo endTime
            $endTime = $now->copy()->addMinutes(1);
            session()->put('end_time', $endTime);
        } else {
            // Nếu đã tồn tại, lấy giá trị từ session
            $endTime = session('end_time');

            if ($now->greaterThan($endTime)) {
                // Nếu now lớn hơn endTime, khởi tạo lại endTime
                $endTime = $now->copy()->addMinutes(2); // Cập nhật endTime thành 10 phút sau thời gian hiện tại
                session()->put('end_time', $endTime); // Lưu lại vào session
                echo "Thời gian kết thúc đã được cập nhật: " . $endTime; // Hiển thị thông tin
            }
        }

        // Tính thời gian còn lại
        $remainingSeconds = $now->diffInSeconds($endTime);





        $showtime->load(['cinema', 'room', 'movieVersion', 'movie', 'seats']);
        $matrix = SeatTemplate::getMatrixById($showtime->room->seatTemplate->matrix_id);
        $seats =  $showtime->seats;
        $seatNames = Seat::whereHas('showtimes', function ($query) use ($showtime) {
            $query->where('showtime_id', $showtime->id)
                ->where('user_id', auth()->id()); // Lọc ghế theo user_id trong bảng trung gian
        })->pluck('name') // Lấy tên ghế
            ->toArray(); // Chuyển đổi thành mảng
        ;
        $seatMap = [];
        if ($seats) {
            foreach ($seats as $seat) {
                $coordinates_y = $seat['coordinates_y'];
                $coordinates_x = $seat['coordinates_x'];

                if (!isset($seatMap[$coordinates_y])) {
                    $seatMap[$coordinates_y] = [];
                }
                $seatMap[$coordinates_y][$coordinates_x] = $seat;
            }
        }
        $combos = Combo::with('food')->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact('seatMap', 'matrix', 'showtime', 'combos', 'seatNames', 'remainingSeconds'));
    }
    public function clearSession(Request $request)
    {
        session()->forget('book_tickets');
        return response()->json(['message' => 'Session cleared.']);
    }
}
