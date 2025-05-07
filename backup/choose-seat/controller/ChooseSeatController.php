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
use Exception;
use Illuminate\Support\Facades\Cache;

class ChooseSeatController extends Controller
{
    public function show(string $id)
    {
        // dd(session()->all());

        // $showtime = Showtime::findOrFail($id);
        // // $showtime = Showtime::with(['seats'])->findOrFail($id);

        // $showtime->room->seats;
        // $showtime->movie;
        // $showtime->movieVersion;
        // $showtime->room->cinema;
        // dd($showtime->toArray());

        $showtime = Showtime::with(['room.cinema', 'room', 'movieVersion', 'movie'])->findOrFail($id);
        $showtime->room->seats;
        $matrixKey = array_search($showtime->room->matrix_id, array_column(Room::MATRIXS, 'id'));
        $matrixSeat = Room::MATRIXS[$matrixKey];
        // $seats = Seat::withTrashed()->where('room_id', $showtime->room->id)->get();

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

        // dd($showtime->toArray());
        return view('client.choose-seat', compact('showtime', 'matrixSeat', 'selectedSeats'));
    }

    public function saveInformation(Request $request, $showtimeId)
    {
        // dd($request->all());
        // dd(session()->all());
        session([
            'checkout_data' => [
                'showtime_id' => $request->showtimeId,
                'seat_ids' => explode(',', $request->seatId), // Chuỗi ghế thành mảng
                'selected_seats_name' => $request->selected_seats_name, // Tên ghế thành mảng
                'total_price' => $request->total_price,
            ]
        ]);

        return redirect()->route('checkout');
    }

    public function holdSeats(Request $request)
    {
        $seatIds = $request->seat_ids; // Nhận mảng ID ghế
        $showtimeId = $request->showtime_id;
        $userId = auth()->id(); // Lấy ID người dùng đang đăng nhập

        try {
            DB::transaction(function () use ($seatIds, $showtimeId, $userId) {
                foreach ($seatIds as $seatId) {
                    // Sử dụng lockForUpdate để tránh xung đột
                    $seatShowtime = DB::table('seat_showtimes')
                        ->where('seat_id', $seatId)
                        ->where('showtime_id', $showtimeId)
                        ->lockForUpdate()
                        ->first();

                    if ($seatShowtime && $seatShowtime->status == 'available') {
                        // Cập nhật trạng thái ghế và thông tin người giữ ghế
                        DB::table('seat_showtimes')
                            ->where('seat_id', $seatId)
                            ->where('showtime_id', $showtimeId)
                            ->update([
                                'status' => 'hold',
                                'user_id' => $userId,
                                'hold_expires_at' => now()->addMinutes(10) // Giữ ghế trong 10 phút
                            ]);

                        // Lưu ghế vào session
                        $selectedSeats = session()->get('selected_seats.' . $showtimeId, []);
                        if (!in_array($seatId, $selectedSeats)) {
                            $selectedSeats[] = $seatId;
                        }
                        session()->put('selected_seats.' . $showtimeId, $selectedSeats);

                        // Phát sự kiện Pusher để thông báo ghế được giữ
                        event(new SeatHold($seatId, $showtimeId));
                    }
                }

                // Dispatch Job để giải phóng ghế sau 10 phút
                ReleaseSeatHoldJob::dispatch($seatIds, $showtimeId)->delay(now()->addMinutes(10));
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
        $seatIds = $request->seat_ids;
        $showtimeId = $request->showtime_id;

        try {
            DB::transaction(function () use ($seatIds, $showtimeId) {
                foreach ($seatIds as $seatId) {
                    // Cập nhật trạng thái ghế về 'available'
                    DB::table('seat_showtimes')
                        ->where('seat_id', $seatId)
                        ->where('showtime_id', $showtimeId)
                        ->update([
                            'status' => 'available',
                            'user_id' => null,
                            'hold_expires_at' => null,
                        ]);

                    // Xóa ghế khỏi session
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
}
