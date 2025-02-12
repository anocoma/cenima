<?php

namespace App\Http\Controllers\API;

use App\Events\ChangeSeat;
use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Seat;
use App\Models\SeatShowtime;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookTicketController extends Controller
{
        //Hàm toggleSeat đễ lưu lại sự thay đổi khi người dùng chọn seat và lưu seat đó vào sesion luôn

        // mã mới thao tác với 1 phần tử
    public function toggleSeat(Request $request, Showtime $showtime)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $validator = Validator::make($request->all(), [
                'seatId' => 'required|integer|exists:seat_showtimes,seat_id,showtime_id,' . $showtime->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $seatId = $request->seatId;
            $showtimeId = $showtime->id;
            $userId = auth()->id();
            session()->put('book_tickets.showtime_id', $showtimeId);

            // Thực hiện transaction để tránh xung đột dữ liệu
            return DB::transaction(function () use ($seatId, $showtimeId, $userId) {
                $seatShowtime = DB::table('seat_showtimes')
                    ->where('seat_id', $seatId)
                    ->where('showtime_id', $showtimeId)
                    ->lockForUpdate()
                    ->first();

                if ($seatShowtime->status === 'hold' && $seatShowtime->user_id !== $userId) {
                    // Ghế đã được giữ bởi người khác
                    return response()->json([
                        'success' => false,
                        'message' => 'Ghế này đang được người khác giữ.',
                    ], 409);
                }

                $action = '';
                $selectedSeats = session()->get("book_tickets.$showtimeId.seat_ids", []);

                if ($seatShowtime->status === 'available') {
                    // Giữ ghế và cập nhật session
                    DB::table('seat_showtimes')
                        ->where('seat_id', $seatId)
                        ->where('showtime_id', $showtimeId)
                        ->update([
                            'status' => 'hold',
                            'user_id' => $userId,
                            'hold_expires_at' => now()->addMinutes(10),
                        ]);

                    if (!in_array($seatId, $selectedSeats)) {
                        $selectedSeats[] = $seatId;
                    }
                    $action = 'added';
                } elseif ($seatShowtime->status === 'hold' && $seatShowtime->user_id === $userId) {
                    // Bỏ giữ ghế và cập nhật session
                    DB::table('seat_showtimes')
                        ->where('seat_id', $seatId)
                        ->where('showtime_id', $showtimeId)
                        ->update([
                            'status' => 'available',
                            'user_id' => null,
                            'hold_expires_at' => null,
                        ]);

                    if (($key = array_search($seatId, $selectedSeats)) !== false) {
                        unset($selectedSeats[$key]);
                    }
                    $action = 'removed';
                }

                // Cập nhật lại session
                session()->put("book_tickets.$showtimeId.seat_ids", array_values($selectedSeats));

                // Phát sự kiện nếu có thay đổi trạng thái
                $seatData = DB::table('seat_showtimes')
                    ->where('seat_id', $seatId)
                    ->where('showtime_id', $showtimeId)
                    ->first();
                broadcast(new ChangeSeat($seatData, $showtimeId))->toOthers();

                return response()->json([
                    'success' => true,
                    'action' => $action, // 'added' hoặc 'removed'
                    'session' => session("book_tickets.$showtimeId"),
                ]);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại.',
            ], 500);
        }
    }





    //Hàm getSelectedSeat là khi người dùng đã chọn ghế xong bấm tiếp tục thì gọi đến hàm này để sử lý hiển thị số lượng ghế, giá ghế cho người dùng bằng ajax
    public function getSelectedSeat(Showtime $showtime)
    {
        try {
            $bookTickets = session('book_tickets.' . $showtime->id);
            if (empty($bookTickets['seat_ids'])) {
                return response()->json(['success' => false, 'message' => 'Ghế không được bỏ trống'], 401);
            }

            $seatIds = $bookTickets['seat_ids'];
            $showtimeId = $showtime->id;

            $seats = Seat::with('typeSeat', 'showtimes')
                ->whereIn('id', $seatIds)
                ->get();

            $seatDetails = [];
            $totalPriceSeat = 0;

            foreach ($seats as $seat) {
                $type = $seat->type_seat_id; // Lấy type_seat_id
                $price = $seat->showtimes()
                    ->where('showtime_id', $showtimeId)->first()->pivot->price;

                if (!isset($seatDetails[$type])) {
                    $seatDetails[$type] = [
                        'name' => $seat->typeSeat->name, // Lấy tên loại ghế
                        'price' => $price, // Lấy giá từ bảng seat_showtime
                        'quantity' => 0,
                    ];
                }

                $seatDetails[$type]['quantity']++;
                $totalPriceSeat += $price;
            }

            // Lưu giá tổng vào session
            session(['book_tickets.total_price_seat' => $totalPriceSeat]);

            // Chuyển seatDetails thành mảng không có key
            $seatDetails = array_values($seatDetails);

            // Chuẩn bị dữ liệu trả về
            $data = [
                'priceSeat' => $totalPriceSeat,
                'seatDetails' => $seatDetails,
                'seatIds' => $seatIds, // Thêm mảng seatIds vào dữ liệu trả về
            ];

            return response()->json(['success' => true, 'data' => $data, 'seats' => $seats]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.'], 500);
        }

    }


    // public function getPriceOrder(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'combo_id' => 'nullable|integer',
    //             'quantity' => 'nullable|integer|min:0',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['success' => false, 'message' => 'Invalid input.'], 403);
    //         }

    //         $comboId = $request->combo_id;
    //         $quantity = $request->quantity;

    //         $comboSessions = session("book_tickets.combos", []);

    //         // Kiểm tra số lượng combo hiện tại
    //         $currentComboCount = count($comboSessions);

    //         if (isset($comboId)) {
    //             if ($quantity == 0) {
    //                 // Nếu quantity là 0, xóa combo khỏi session
    //                 $existingComboKey = array_search($comboId, array_column($comboSessions, 'id'));
    //                 if ($existingComboKey !== false) {
    //                     unset($comboSessions[$existingComboKey]); // Xóa combo
    //                 }
    //             } else {
    //                 // Kiểm tra xem combo đã có trong session hay chưa
    //                 $existingComboKey = array_search($comboId, array_column($comboSessions, 'id'));

    //                 if ($existingComboKey !== false) {
    //                     // Nếu đã có thì cập nhật quantity và price
    //                     $comboSessions[$existingComboKey]['quantity'] = $quantity;
    //                 } else {
    //                     // Nếu chưa có và số lượng combo hiện tại < 10 thì thêm mới
    //                     if ($currentComboCount < 10) {
    //                         $comboSessions[] = [
    //                             'id' => $comboId,
    //                             'quantity' => $quantity,
    //                             'price' => 0 // Giá sẽ được cập nhật sau
    //                         ];
    //                     } else {
    //                         return response()->json(['success' => false, 'message' => 'Đã đạt giới hạn 10 combo.'], 403);
    //                     }
    //                 }
    //             }
    //             session(['book_tickets.combos' => $comboSessions]);
    //         }

    //         $totalPriceCombo = 0;

    //         foreach ($comboSessions as $key => $item) {
    //             // Tìm combo để lấy giá
    //             $combo = Combo::findOrFail($item['id']);
    //             $price = $combo->price_sale * $item['quantity'];
    //             $totalPriceCombo += $price;

    //             // Cập nhật giá vào session
    //             $comboSessions[$key]['price'] = $price; // Cập nhật price cho từng combo
    //         }

    //         session(['book_tickets.combos' => $comboSessions]); // Cập nhật lại session với giá

    //         $totalPriceReduced = 0;
    //         $totalPriceSeat = session('book_tickets.total_price_seat');
    //         $totalPrice = $totalPriceCombo + $totalPriceSeat;
    //         $totalPricePaid = $totalPrice - $totalPriceReduced;

    //         session(['book_tickets.total_price_combo' => $totalPriceCombo]);
    //         session(['book_tickets.total_price_reduced' => $totalPriceReduced]);
    //         session(['book_tickets.total_price' => $totalPrice]);
    //         session(['book_tickets.total_price_paid' => $totalPricePaid]);

    //         return response()->json(['success' => true, 'order' => session('book_tickets')]);
    //     } catch (\Throwable $th) {
    //         return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.'], 500);
    //     }
    // }

    public function payment(Request $request, Showtime $showtime)
    {
        try {
            if ($request->payment_method == 'cash') {
                return response()->json(['success' => true, 'data' => session('book_tickets')]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại.',
            ], 500);
        }
    }
    public function clearSession(Request $request, Showtime $showtime)
    {
        session()->forget('book_tickets');
        return response()->json(['success' => true]);
    }
}
