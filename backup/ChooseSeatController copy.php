<?php

namespace App\Http\Controllers\Client;

use App\Events\SeatRelease;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\SeatHold;
use App\Jobs\ReleaseSeatHoldJob;
use App\Models\SeatShowtime;
use Carbon\Carbon;

class ChooseSeatController extends Controller
{
    // public function show(string $id)
    // {
    //     // dd(session()->all());

    //     $showtime = Showtime::with(['room.cinema', 'room', 'movieVersion', 'movie'])->findOrFail($id);
    //     $showtime->room->seats;
    //     $matrixKey = array_search($showtime->room->matrix_id, array_column(Room::MATRIXS, 'id'));
    //     $matrixSeat = Room::MATRIXS[$matrixKey];
    //     // $seats = Seat::withTrashed()->where('room_id', $showtime->room->id)->get();

    //     // Lấy danh sách ghế đã được chọn từ session
    //     $selectedSeats = session()->get('selected_seats.' . $id, []);

    //     // Kiểm tra và xóa những ghế đã hết hạn giữ chỗ
    //     $selectedSeats = array_filter($selectedSeats, function ($seatId) use ($id) {
    //         $seatShowtime = SeatShowtime::where('showtime_id', $id)
    //             ->where('seat_id', $seatId)
    //             ->first();

    //         // Trả về true nếu ghế còn thời gian giữ chỗ, ngược lại trả về false
    //         return $seatShowtime && $seatShowtime->hold_expires_at >= now();
    //     });

    //     // Cập nhật lại session
    //     session('selected_seats.' . $id, array_values($selectedSeats));

    //     // dd($selectedSeats);

    //     $now = Carbon::now('Asia/Ho_Chi_Minh');

    //     // Kiểm tra xem session có chứa "end_time" cho suất chiếu này chưa
    //     $sessionKey = 'end_time.' . $id; // Tạo khóa session dựa trên ID suất chiếu
    //     if (!session()->has($sessionKey)) {
    //         // Nếu chưa tồn tại trong session, tạo mới "end_time" 10 phút sau thời điểm hiện tại
    //         $endTime = $now->copy()->addMinutes(2);
    //         session()->put($sessionKey, $endTime); // Lưu vào session với khóa riêng cho suất chiếu
    //     } else {
    //         // Lấy "end_time" đã lưu trong session
    //         $endTime = session($sessionKey);

    //         // Tính thời gian còn lại so với thời gian hiện tại
    //         $remainingSeconds = $now->diffInSeconds($endTime);

    //         // Kiểm tra nếu thời gian giữ chỗ đã hết
    //         if ($remainingSeconds <= 0) {
    //             // Xóa session "end_time" khi thời gian đã hết
    //             session()->forget($sessionKey);

    //             // Tạo mới "end_time" với thời gian bắt đầu từ 10 phút sau thời điểm hiện tại
    //             $endTime = $now->copy()->addMinutes(2);
    //             session()->put($sessionKey, $endTime);

    //             // Cập nhật lại thời gian còn lại (10 phút)
    //             $remainingSeconds = $now->diffInSeconds($endTime);
    //         }
    //     }

    //     // Hiển thị thời gian còn lại
    //     $remainingSeconds = $now->diffInSeconds($endTime);
    //     echo "Thời gian còn lại cho suất chiếu $id: " . gmdate("i:s", $remainingSeconds) . " phút";

    //     // dd($showtime->toArray());
    //     return view('client.choose-seat', compact('showtime', 'matrixSeat', 'selectedSeats', 'remainingSeconds'));
    // }


    public function show(string $id)
    {
        $showtime = Showtime::with(['room.cinema', 'room', 'movieVersion', 'movie'])->findOrFail($id);
        $showtime->room->seats;
        $matrixKey = array_search($showtime->room->matrix_id, array_column(Room::MATRIXS, 'id'));
        $matrixSeat = Room::MATRIXS[$matrixKey];

        // Lấy danh sách ghế đã được chọn từ session
        $selectedSeats = session()->get('selected_seats.' . $id, []);

        // Kiểm tra và xóa những ghế đã hết hạn giữ chỗ
        $selectedSeats = array_filter($selectedSeats, function ($seatId) use ($id) {
            $seatShowtime = SeatShowtime::where('showtime_id', $id)
                ->where('seat_id', $seatId)
                ->first();

            // Trả về true nếu ghế còn thời gian giữ chỗ, ngược lại trả về false
            return $seatShowtime && $seatShowtime->hold_expires_at >= now();
        });

        // Cập nhật lại session
        session('selected_seats.' . $id, array_values($selectedSeats));

        // dd($selectedSeats);

        $now = Carbon::now();
        $timeKey = 'timeData.' . $id; // Khóa chung cho cả end_time 

        // Kiểm tra session
        if (session()->has($timeKey)) {
            // Lấy dữ liệu từ session
            $timeData = session($timeKey);
            $endTime = Carbon::parse($timeData['end_time']);

            // Kiểm tra thời gian còn lại
            if ($now->greaterThanOrEqualTo($endTime)) {
                // Nếu hết thời gian, xóa session
                session()->forget($timeKey);
                $remainingSeconds = 10 * 60; // 10 phút
            } else {
                // Tính thời gian còn lại
                $remainingSeconds = $now->diffInSeconds($endTime);
            }
        } else {
            // Nếu chưa có session, tạo mới với thời gian 10 phút
            $endTime = $now->copy()->addMinutes(10);
            session()->put($timeKey, [
                'end_time' => $endTime->toDateTimeString(),
            ]);
            $remainingSeconds = 10 * 60; // 10 phút
        }

        // dd(session()->all());

        // Hiển thị thời gian còn lại
        // echo "Thời gian còn lại cho suất chiếu $id: " . gmdate("i:s", $remainingSeconds);

        return view('client.choose-seat', compact('showtime', 'matrixSeat', 'selectedSeats', 'remainingSeconds'));
    }



    public function saveInformation(Request $request, $showtimeId)
    {
        // dd($request->all());
        // dd(session()->all());
        // session()->forget('checkout_data');
        session()->put([
            'checkout_data' => [
                'showtime_id' => $request->showtimeId,
                'seat_ids' => explode(',', $request->seatId), // Chuỗi ghế thành mảng
                'selected_seats_name' => $request->selected_seats_name, // Tên ghế thành mảng
                'total_price' => $request->total_price,
                'remainingSeconds' => $request->remainingSeconds
            ]
        ]);

        return redirect()->route('checkout');
    }

    public function holdSeats(Request $request)
    {
        $seatId = $request->seat_id;
        $showtimeId = $request->showtime_id;
        $userId = auth()->id(); // Lấy ID người dùng đang đăng nhập

        $timeKey = 'timeData.' . $showtimeId;
        $timeData = session()->get($timeKey, null);

        try {
            DB::transaction(function () use ($seatId, $showtimeId, $userId, $timeData) {

                // Lấy thời gian kết thúc giữ chỗ từ session
                $holdExpiresAt = Carbon::parse($timeData['end_time']);

                // Cập nhật trạng thái ghế và thông tin người giữ ghế
                DB::table('seat_showtimes')
                    ->where('seat_id', $seatId)
                    ->where('showtime_id', $showtimeId)
                    ->lockForUpdate()
                    ->update([
                        'status' => 'hold',
                        'user_id' => $userId,
                        'hold_expires_at' => $holdExpiresAt,
                    ]);

                // Lưu ghế vào session theo suất chiếu
                $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
                if (!in_array($seatId, $selectedSeats)) {
                    $selectedSeats[] = $seatId; // Thêm seatId vào mảng của suất chiếu
                }
                session()->put('selected_seats.' . $showtimeId, $selectedSeats);

                // Phát sự kiện Pusher để thông báo ghế được giữ
                event(new SeatHold($seatId, $showtimeId));


                // Dispatch Job để giải phóng ghế sau 10 phút
                ReleaseSeatHoldJob::dispatch([$seatId], $showtimeId)->delay($holdExpiresAt);
            });

            return response()->json(['message' => 'Trạng thái ghế đã được cập nhật thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi giữ ghế.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function releaseSeats(Request $request)
    {
        $seatId = $request->seat_id;
        $showtimeId = $request->showtime_id;

        try {
            DB::transaction(function () use ($seatId, $showtimeId) {
                // Sử dụng lockForUpdate để tránh xung đột
                $seatShowtime = DB::table('seat_showtimes')
                    ->where('seat_id', $seatId)
                    ->where('showtime_id', $showtimeId)
                    ->lockForUpdate()
                    ->first();

                if ($seatShowtime && $seatShowtime->status == 'hold') {
                    // Cập nhật trạng thái ghế về 'available'
                    DB::table('seat_showtimes')
                        ->where('seat_id', $seatId)
                        ->where('showtime_id', $showtimeId)
                        ->update([
                            'status' => 'available',
                            'user_id' => null,
                            'hold_expires_at' => null,
                        ]);

                    // Xóa ghế khỏi session theo suất chiếu
                    $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
                    if (($key = array_search($seatId, $selectedSeats)) !== false) {
                        unset($selectedSeats[$key]);
                    }
                    session()->put('selected_seats.' . $showtimeId, array_values($selectedSeats));

                    // Phát sự kiện Pusher để thông báo ghế được giải phóng
                    event(new SeatRelease($seatId, $showtimeId));
                }
            });

            return response()->json(['message' => 'Ghế đã được giải phóng.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi giải phóng ghế.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // public function holdSeats(Request $request)
    // {
    //     $seatId = $request->seat_id; // Nhận một ID ghế
    //     $showtimeId = $request->showtime_id;
    //     $userId = auth()->id(); // Lấy ID người dùng đang đăng nhập

    //     $timeKey = 'timeData.' . $showtimeId;
    //     $timeData = session()->get($timeKey, null);

    //     try {
    //         DB::transaction(function () use ($seatId, $showtimeId, $userId, $timeData) {

    //             // Lấy thời gian kết thúc giữ chỗ từ session
    //             $holdExpiresAt = Carbon::parse($timeData['end_time']);

    //             // Sử dụng lockForUpdate để tránh xung đột
    //             $seatShowtime = DB::table('seat_showtimes')
    //                 ->where('seat_id', $seatId)
    //                 ->where('showtime_id', $showtimeId)
    //                 ->lockForUpdate()
    //                 ->first();

    //             if ($seatShowtime && $seatShowtime->status == 'available') {
    //                 // Cập nhật trạng thái ghế và thông tin người giữ ghế
    //                 DB::table('seat_showtimes')
    //                     ->where('seat_id', $seatId)
    //                     ->where('showtime_id', $showtimeId)
    //                     ->update([
    //                         'status' => 'hold',
    //                         'user_id' => $userId,
    //                         'hold_expires_at' => $holdExpiresAt,
    //                     ]);

    //                 // Lưu ghế vào session
    //                 $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
    //                 if (!in_array($seatId, $selectedSeats)) {
    //                     $selectedSeats[] = $seatId;  // Thêm seatId vào mảng
    //                 }
    //                 session()->put('selected_seats.' . $showtimeId, $selectedSeats); // Lưu lại session

    //                 // Phát sự kiện Pusher để thông báo ghế được giữ
    //                 event(new SeatHold($seatId, $showtimeId));
    //             }

    //             // Dispatch Job để giải phóng ghế sau 10 phút
    //             ReleaseSeatHoldJob::dispatch([$seatId], $showtimeId)->delay($holdExpiresAt);
    //         });

    //         return response()->json(['message' => 'Trạng thái ghế đã được cập nhật thành công.'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Có lỗi xảy ra khi giữ ghế.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function releaseSeats(Request $request)
    // {
    //     $seatId = $request->seat_id; // Nhận một ID ghế
    //     $showtimeId = $request->showtime_id;

    //     try {
    //         DB::transaction(function () use ($seatId, $showtimeId) {
    //             // Sử dụng lockForUpdate để tránh xung đột
    //             $seatShowtime = DB::table('seat_showtimes')
    //                 ->where('seat_id', $seatId)
    //                 ->where('showtime_id', $showtimeId)
    //                 ->lockForUpdate()
    //                 ->first();

    //             if ($seatShowtime && $seatShowtime->status == 'hold') {
    //                 // Cập nhật trạng thái ghế về 'available'
    //                 DB::table('seat_showtimes')
    //                     ->where('seat_id', $seatId)
    //                     ->where('showtime_id', $showtimeId)
    //                     ->update([
    //                         'status' => 'available',
    //                         'user_id' => null,
    //                         'hold_expires_at' => null,
    //                     ]);

    //                 // Xóa ghế khỏi session
    //                 $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
    //                 if (($key = array_search($seatId, $selectedSeats)) !== false) {
    //                     unset($selectedSeats[$key]);
    //                 }
    //                 session()->put('selected_seats.' . $showtimeId, array_values($selectedSeats));

    //                 // Phát sự kiện Pusher để thông báo ghế được giải phóng
    //                 event(new SeatRelease($seatId, $showtimeId));
    //             }
    //         });

    //         return response()->json(['message' => 'Ghế đã được giải phóng.'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Có lỗi xảy ra khi giải phóng ghế.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
