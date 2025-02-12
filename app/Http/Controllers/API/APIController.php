<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Membership;
use App\Models\Movie;
use App\Models\MovieVersion;
use App\Models\Room;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class APIController extends Controller
{
    public function getCinemas($branchId)
    {
        $cinemas = Cinema::where('branch_id', $branchId)
            ->where('is_active', '1')
            ->get();
        return response()->json($cinemas);
    }


    public function getRooms($cinemaId)
    {
        $rooms = Room::where('cinema_id', $cinemaId)
            ->where('rooms.is_active', '1')
            ->join('type_rooms', 'type_rooms.id', '=', 'rooms.type_room_id') // Join bảng type_rooms
            ->leftJoin('seats', 'seats.room_id', '=', 'rooms.id') // Join bảng seats để đếm số lượng ghế
            ->select('rooms.*', 'type_rooms.name as type_room_name', DB::raw('COUNT(seats.id) as total_seats')) // Đếm số ghế
            ->groupBy('rooms.id') // Nhóm theo id của rooms để đếm chính xác
            ->get();

        return response()->json($rooms);
    }


    public function getMovieVersion($movieId)
    {
        $movieVersions = MovieVersion::where('movie_id', $movieId)->get();
        return response()->json($movieVersions);
    }
    public function getMovieDuration($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        if ($movie) {
            return response()->json(['duration' => $movie->duration]);
        }
        return response()->json(['error' => 'Không tìm thấy phim'], 404);
    }

    public function deleteSelected(Request $request)
    {
        $showtimeIds = $request->input('showtime_ids');
        Showtime::whereIn('id', $showtimeIds)->delete();
        return response()->json(['message' => 'Xóa thành công !']);
    }

    public function onStatusSelected(Request $request)
    {
        $showtimeIds = $request->input('showtime_ids');
        $showtimes = Showtime::whereIn('id', $showtimeIds)->get();

        foreach ($showtimes as $showtime) {
            $showtime->is_active = 1;  // Toggle trạng thái
            $showtime->save();
        }

        return response()->json(['message' => 'Cập nhật trạng thái thành công']);
    }
    public function offStatusSelected(Request $request)
    {
        $showtimeIds = $request->input('showtime_ids');
        $showtimes = Showtime::whereIn('id', $showtimeIds)->get();

        foreach ($showtimes as $showtime) {
            $showtime->is_active = 0;  // Toggle trạng thái
            $showtime->save();
        }

        return response()->json(['message' => 'Cập nhật trạng thái thành công']);
    }


    public function getShowtimesByRoom(Request $request)
    {
        $roomId = $request->get('room_id');
        $date = $request->get('date');

        $showtimes = Showtime::with('room')
            ->where('room_id', $roomId)
            ->where('date', $date)
            ->orderBy('start_time')
            ->get();

        if ($showtimes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không có suất chiếu nào cho ngày này.'
            ]);
        }

        foreach ($showtimes as $showtime) {
            $showtime->start_time = \Carbon\Carbon::parse($showtime->start_time)->format('H:i');
            $showtime->end_time = \Carbon\Carbon::parse($showtime->end_time)->format('H:i');
        }

        return response()->json($showtimes);
    }




    //lấy thông tin membership
    public function getMembership(Request $request)
    {
        // Lấy dữ liệu từ request (email hoặc mã thẻ thành viên)
        $dataMembership = $request->input('data_membership');

        // Tìm thông tin thành viên theo mã thẻ hoặc email
        $membership = Membership::where('code', $dataMembership)->first();
        $user = null;

        if ($membership) {
            $user = User::find($membership->user_id);
        }

        // Nếu tìm thấy thông tin user và membership
        if ($user && $membership) {
            session()->put('customer', $user->id);
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'membership_code' => $membership->code,
                    'rank' => $membership->rank,
                    'points' => $membership->points,
                    'user_id' => $user->id,
                    'customer' => session('customer'),
                ]
            ]);
        }

        // Trả về nếu không tìm thấy thông tin
        return response()->json(['success' => false, 'message' => 'Thông tin không hợp lệ.'], 404);
    }
    public function cancelMembership(Request $request)
    {
        if (session()->has('customer')) {
            session()->forget('customer');
        }
        return response()->json([
            'success' => true,
        ]);
    }
}
