<?php

namespace App\Http\Controllers\Client;

use App\Events\ChangeSeat;
use App\Events\SeatRelease;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\SeatHold;
use App\Events\SeatStatusChange;
use App\Jobs\ReleaseSeatHoldJob;
use App\Models\SeatShowtime;
use App\Models\SeatTemplate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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


    public function show(string $slug)
    {
        // $showtime = Showtime::with(['room.cinema', 'room', 'movieVersion', 'movie'])->findOrFail($id);
        // $showtime->room->seats;
        // $matrixKey = array_search($showtime->room->matrix_id, array_column(Room::MATRIXS, 'id'));
        // $matrixSeat = Room::MATRIXS[$matrixKey];

        $showtime = Showtime::with(['room.cinema', 'room', 'movieVersion', 'movie', 'seats'])->where('slug', $slug)->first();

        if (!$showtime || !$showtime->slug || $showtime->is_active != 1) {
            return redirect()->route('home')->with('error', 'Suất chiếu không tồn tại.');
        }

        $matrixSeat = SeatTemplate::getMatrixById($showtime->room->seatTemplate->matrix_id);
        $seats =  $showtime->seats;

        $seatMap = [];
        foreach ($seats as $seat) {
            $seatMap[$seat->coordinates_y][$seat->coordinates_x] = $seat;
        }

        // dd($seatMap);

        // nếu hết giờ start_time < thời gian hiện tại thì chuyển về trang chủ
        // if($showtime->start_time < now()){
        //     return redirect()->route('home')->with('error', 'Đã hết thời gian đặt vé.');
        // }

        if (auth()->user()->type === User::TYPE_MEMBER) {
            // Kiểm tra nếu member không thể đặt vé trước 10 phút so với thời gian bắt đầu chiếu
            if ($showtime->start_time <= now()->addMinutes(10)) {
                return redirect()->route('home')->with('error', 'Đã hết thời gian đặt vé.');
            }
        } elseif ($showtime->start_time < now()) {
            // Nếu là admin, chỉ kiểm tra nếu suất chiếu đã bắt đầu
            return redirect()->route('home')->with('error', 'Đã hết thời gian đặt vé.');
        }

        if ($showtime->cinema_id != session('cinema_id')) {
            return redirect()->route('home');
        }

        // cập nhật lại ghế nếu gặp phải 1 trong các trường hợp sau
        DB::table('seat_showtimes')
            ->where('showtime_id', $showtime->id)
            ->where(function ($query) {
                $query->where('user_id', 0)
                    ->orWhereNull('user_id')
                    ->orWhere('status', 'available')
                    ->orWhere(function ($query) {
                        $query->where('status', '!=', 'sold')
                            ->where('hold_expires_at', '<', now());
                    });
            })
            ->update([
                'status' => 'available',
                'user_id' => null,
                'hold_expires_at' => null,
            ]);


        // Lấy danh sách ghế được giữ bởi user hiện tại cho suất chiếu này
        $userId = auth()->id(); // Lấy user ID
        $selectedSeats = SeatShowtime::where('showtime_id', $showtime->id)
            ->where('user_id', $userId)
            ->where('status', 'hold')
            ->where('hold_expires_at', '>=', now())
            ->pluck('seat_id')
            ->toArray();

        // dd($selectedSeats);

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $timeKey = 'timeData.' . $showtime->id; // Khóa chung cho cả end_time 

        // Kiểm tra session
        if (session()->has($timeKey)) {
            // Lấy dữ liệu từ session
            $timeData = session($timeKey);
            $endTime = Carbon::parse($timeData['end_time']);

            // Kiểm tra thời gian còn lại
            if ($now->greaterThanOrEqualTo($endTime)) {
                // Nếu hết thời gian, xóa session
                session()->forget($timeKey);

                $endTime = $now->copy()->addMinutes(10);
                session()->put($timeKey, [
                    'end_time' => $endTime->toDateTimeString(),
                ]);
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

        return view('client.choose-seat', compact('showtime', 'matrixSeat', 'seatMap', 'selectedSeats', 'remainingSeconds'));
    }



    public function saveInformation(Request $request, $showtimeId)
    {
        // dd($request->all());
        // dd(session()->all());

        $seatIds = explode(',', $request->seatId); // Chuỗi ghế thành mảng
        $userId = auth()->id();

        $slug = Showtime::where('id', $showtimeId)->where('is_active', '1')->pluck('slug')->first();

        // dd($slug);

        // Kiểm tra ghế, bất kỳ ghế nào hết thời gian giữ chỗ hoặc != hold hoặc khác người giữ chỗ
        $seatShowtimes = DB::table('seat_showtimes')
            ->whereIn('seat_id', $seatIds)
            ->where('showtime_id', $showtimeId)
            ->get();

        $hasExpiredSeats = false; // Biến đánh dấu
        foreach ($seatShowtimes as $seatShowtime) {
            // Kiểm tra xem có ghế xem có đủ tiêu chuẩn để đc bấm nút tiếp tục hay không
            if ($seatShowtime->hold_expires_at < now() || $seatShowtime->user_id != $userId || $seatShowtime->status != 'hold') {
                $hasExpiredSeats = true; // Đánh dấu có ghế
                break; // Dừng vòng lặp khi tìm thấy ghế
            }
        }

        if ($hasExpiredSeats) {
            // Nếu có bất kỳ ghế nào hết thời gian giữ chỗ hoặc != hold hoặc khác người giữ chỗ
            return back()->with('error', 'Ghế đã có người khác giữ hoặc ghế đã bán. Vui lòng chọn lại ghế.');
        }

        if (!$slug) {
            return redirect()->route('home')->with('error', 'Suất chiếu không tồn tại.');
        }

        session()->put([
            "checkout_data.$showtimeId" => [
                'showtime_id' => $request->showtimeId,
                'seat_ids' => $seatIds,
                'selected_seats_name' => $request->selected_seats_name, // Tên ghế thành mảng
                'total_price' => $request->total_price,
                'remainingSeconds' => $request->remainingSeconds,
                'lastUpdated' => now(), // lưu thời gian hiện tại lúc bấm nút tiếp tục
            ]
        ]);

        return redirect()->route('checkout', ['slug' => $slug]);
    }

    // public function holdSeats(Request $request)
    // {
    //     $seatId = $request->seat_id;
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

    //                 // Lưu ghế vào session theo suất chiếu
    //                 $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
    //                 if (!in_array($seatId, $selectedSeats)) {
    //                     $selectedSeats[] = $seatId; // Thêm seatId vào mảng của suất chiếu
    //                 }
    //                 session()->put('selected_seats.' . $showtimeId, $selectedSeats);

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
    //     $seatId = $request->seat_id;
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

    //                 // Xóa ghế khỏi session theo suất chiếu
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

    // public function updateSeat(Request $request)
    // {
    //     try {
    //         $seatId = $request->seat_id;
    //     $showtimeId = $request->showtime_id;
    //     $action = $request->action; // 'hold' hoặc 'release'
    //     $userId = auth()->id();
    //     $timeKey = 'timeData.' . $showtimeId;
    //     $timeData = session()->get($timeKey, null);
    //         DB::transaction(function () use ($seatId, $showtimeId, $userId, $action, $timeData) {
    //             // Log::info("Starting transaction for seat ID: $seatId with action: $action by user $userId");

    //             $seatShowtime = DB::table('seat_showtimes')
    //                 ->where('seat_id', $seatId)
    //                 ->where('showtime_id', $showtimeId)
    //                 ->lockForUpdate()
    //                 ->first();

    //             // Log::info("Seat data: ", (array) $seatShowtime);
    //             // Log::info("Seat status before update: {$seatShowtime->status}");

    //             if ($action === 'hold' && $seatShowtime && $seatShowtime->status == 'available') {
    //                 $holdExpiresAt = Carbon::parse($timeData['end_time']);

    //                 DB::table('seat_showtimes')
    //                     ->where('seat_id', $seatId)
    //                     ->where('showtime_id', $showtimeId)
    //                     ->update([
    //                         'status' => 'hold',
    //                         'user_id' => $userId,
    //                         'hold_expires_at' => $holdExpiresAt,
    //                     ]);

    //                 $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
    //                 Log::info("Current selected seats before update: ", $selectedSeats);
    //                 if (!in_array((string)$seatId, array_map('strval', $selectedSeats))) {
    //                     $selectedSeats[] = (string)$seatId;
    //                     Log::info("session ghế chọn: $seatId");
    //                 }
    //                 session()->put('selected_seats.' . $showtimeId, $selectedSeats);

    //                 event(new SeatHold($seatId, $showtimeId));
    //                 ReleaseSeatHoldJob::dispatch([$seatId], $showtimeId)->delay($holdExpiresAt);
    //             } elseif ($action === 'release' && $seatShowtime && $seatShowtime->status == 'hold') {
    //                 DB::table('seat_showtimes')
    //                     ->where('seat_id', $seatId)
    //                     ->where('showtime_id', $showtimeId)
    //                     ->update([
    //                         'status' => 'available',
    //                         'user_id' => null,
    //                         'hold_expires_at' => null,
    //                     ]);

    //                 $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
    //                 if (($key = array_search($seatId, $selectedSeats)) !== false) {
    //                     unset($selectedSeats[$key]);
    //                     Log::info("session ghế bỏ: $seatId");
    //                 }
    //                 session()->put('selected_seats.' . $showtimeId, array_values($selectedSeats));

    //                 event(new SeatRelease($seatId, $showtimeId));
    //             }

    //             // Kiểm tra và xóa ghế hết thời gian giữ
    //             $heldSeats = DB::table('seat_showtimes')
    //                 ->where('showtime_id', $showtimeId)
    //                 ->where('status', 'available')
    //                 ->get();

    //             foreach ($heldSeats as $heldSeat) {
    //                 if (Carbon::now()->isAfter($heldSeat->hold_expires_at && $heldSeat->status == 'available')) {
    //                     // Xóa ghế khỏi session nếu đã hết thời gian giữ
    //                     $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
    //                     if (($key = array_search($heldSeat->seat_id, $selectedSeats)) !== false) {
    //                         unset($selectedSeats[$key]);
    //                         Log::info("Ghế hết thời gian giữ, xóa khỏi session: {$heldSeat->seat_id}");
    //                     }
    //                     session()->put('selected_seats.' . $showtimeId, array_values($selectedSeats));
    //                 }
    //             }
    //         });

    //         return response()->json(['message' => 'Cập nhật trạng thái ghế thành công.'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Có lỗi xảy ra khi cập nhật trạng thái ghế.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    // public function updateSeat(Request $request)
    // {
    //     try {
    //         $seatId = $request->seat_id;
    //         $showtimeId = $request->showtime_id;
    //         $action = $request->action; // 'hold' hoặc 'release'
    //         $userId = auth()->id();
    //         $timeKey = 'timeData.' . $showtimeId;
    //         $timeData = session()->get($timeKey);

    //         // Kiểm tra xem thời gian giữ chỗ có hợp lệ không
    //         if ($action === 'hold' && (!$timeData || !isset($timeData['end_time']))) {
    //             return response()->json(['message' => 'Dữ liệu thời gian không hợp lệ.'], 400);
    //         }

    //         $holdExpiresAt = Carbon::parse($timeData['end_time']);

    //         DB::transaction(function () use ($seatId, $showtimeId, $userId, $action, $holdExpiresAt) {
    //             // Tìm ghế trong showtime và kiểm tra trạng thái
    //             $seatShowtime = DB::table('seat_showtimes')
    //                 ->join('seats', 'seats.id', '=', 'seat_showtimes.seat_id')
    //                 ->where('seat_showtimes.seat_id', $seatId)
    //                 ->where('seat_showtimes.showtime_id', $showtimeId)
    //                 ->where('seats.is_active', 1)
    //                 ->lockForUpdate()
    //                 ->first();

    //             if (!$seatShowtime) {
    //                 throw new \Exception('Không tìm thấy ghế trong showtime này.');
    //             }

    //             // Cập nhật trạng thái ghế
    //             if ($action === 'hold' && $seatShowtime->status === 'available') {
    //                 DB::table('seat_showtimes')
    //                     ->where('seat_id', $seatId)
    //                     ->where('showtime_id', $showtimeId)
    //                     ->update([
    //                         'status' => 'hold',
    //                         'user_id' => $userId,
    //                         'hold_expires_at' => $holdExpiresAt,
    //                     ]);
    //             } elseif ($action === 'release' && $seatShowtime->status === 'hold') {
    //                 DB::table('seat_showtimes')
    //                     ->where('seat_id', $seatId)
    //                     ->where('showtime_id', $showtimeId)
    //                     ->update([
    //                         'status' => 'available',
    //                         'user_id' => null,
    //                         'hold_expires_at' => null,
    //                     ]);
    //             }
    //         });

    //         // Lấy lại dữ liệu ghế sau khi transaction thành công
    //         $seatData = DB::table('seat_showtimes')
    //             ->where('seat_id', $seatId)
    //             ->where('showtime_id', $showtimeId)
    //             ->first();

    //         // Phát sự kiện sau khi transaction hoàn tất
    //         if ($action === 'hold') {
    //             broadcast(new SeatStatusChange($seatData->seat_id, $showtimeId, 'hold'))->toOthers();
    //             ReleaseSeatHoldJob::dispatch([$seatData->seat_id], $showtimeId, null)->delay($holdExpiresAt);
    //         } elseif ($action === 'release') {
    //             broadcast(new SeatStatusChange($seatData->seat_id, $showtimeId, 'available'))->toOthers();
    //         }


    //         return response()->json(['message' => 'Cập nhật trạng thái ghế thành công.'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Có lỗi xảy ra khi cập nhật trạng thái ghế.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function updateSeat(Request $request)
    {
        try {
            $seatId = $request->seat_id;
            $showtimeId = $request->showtime_id;
            $action = $request->action; // 'hold' hoặc 'release'
            $userId = auth()->id();
            $timeKey = 'timeData.' . $showtimeId;
            $timeData = session()->get($timeKey);

            // Kiểm tra xem thời gian giữ chỗ có hợp lệ không
            if ($action === 'hold' && (!$timeData || !isset($timeData['end_time']))) {
                return response()->json(['message' => 'Dữ liệu thời gian không hợp lệ.'], 400);
            }

            $holdExpiresAt = Carbon::parse($timeData['end_time']);

            // Tìm ghế trong showtime và kiểm tra trạng thái
            $seatShowtime = DB::table('seat_showtimes')
                ->join('seats', 'seats.id', '=', 'seat_showtimes.seat_id')
                ->where('seat_showtimes.seat_id', $seatId)
                ->where('seat_showtimes.showtime_id', $showtimeId)
                ->where('seats.is_active', 1)
                ->lockForUpdate()
                ->first();

            if (!$seatShowtime) {
                throw new \Exception('Không tìm thấy ghế trong showtime này.');
            }

            if (
                $action == 'hold' && $seatShowtime->status != 'available' &&
                ($seatShowtime->user_id != $userId || $seatShowtime->user_id != null) &&
                $seatShowtime->seat_id == $seatId && $seatShowtime->showtime_id == $showtimeId
            ) {
                return response()->json([
                    'message' => 'Ghế này đã có người khác chọn. Vui lòng chọn ghế khác.',
                ], 409); // HTTP 409 Conflict
            }

            DB::transaction(function () use ($seatShowtime, $seatId, $showtimeId, $userId, $action, $holdExpiresAt) {
                // Cập nhật trạng thái ghế
                if ($action === 'hold' && $seatShowtime->status === 'available' && $seatShowtime->user_id === null) {
                    DB::table('seat_showtimes')
                        ->where('seat_id', $seatId)
                        ->where('showtime_id', $showtimeId)
                        ->update([
                            'status' => 'hold',
                            'user_id' => $userId,
                            'hold_expires_at' => $holdExpiresAt,
                        ]);
                } elseif ($action === 'release' && $seatShowtime->status === 'hold' && $seatShowtime->user_id === $userId) {
                    DB::table('seat_showtimes')
                        ->where('seat_id', $seatId)
                        ->where('showtime_id', $showtimeId)
                        ->update([
                            'status' => 'available',
                            'user_id' => null,
                            'hold_expires_at' => null,
                        ]);
                }
            });

            // Lấy lại dữ liệu ghế sau khi transaction thành công
            $seatData = DB::table('seat_showtimes')
                ->where('seat_id', $seatId)
                ->where('showtime_id', $showtimeId)
                ->first();

            // Phát sự kiện sau khi transaction hoàn tất
            if ($action === 'hold') {
                broadcast(new SeatStatusChange($seatData->seat_id, $showtimeId, 'hold'))->toOthers();
                ReleaseSeatHoldJob::dispatch([$seatData->seat_id], $showtimeId, null)->delay($holdExpiresAt);
            } elseif ($action === 'release') {
                broadcast(new SeatStatusChange($seatData->seat_id, $showtimeId, 'available'))->toOthers();
            }


            return response()->json(['message' => 'Cập nhật trạng thái ghế thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái ghế.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
