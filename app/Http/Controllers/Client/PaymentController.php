<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Jobs\ReleaseSeatHoldJob;
use App\Mail\TicketInvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\SeatRelease;
use App\Events\SeatSold;
use App\Events\SeatStatusChange;
use App\Models\Combo;
use App\Models\Membership;
use App\Models\PointHistory;
use App\Models\Rank;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\TicketCombo;
use App\Models\TicketSeat;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        // dd(session()->all());
        // dd($request->all());

        // 1. Xác thực dữ liệu đầu vào
        $request->validate([
            'seat_id' => 'required|array',
            'seat_id.*' => 'integer|exists:seats,id',
            'combo' => 'nullable|array',
            'combo.*' => 'nullable|integer|min:0|max:10',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ]);


        $seatIds = $request->seat_id; // Danh sách ghế từ request
        $showtimeId = $request->showtime_id;
        $userId = auth()->id(); // Lấy ID người dùng đang đăng nhập
        $showtime = Showtime::where('id', $showtimeId)->first();

        // Kiểm tra ghế và tính tổng giá ghế trước khi bắt đầu transaction
        $seatShowtimes = DB::table('seat_showtimes')
            ->whereIn('seat_id', $seatIds)
            ->where('showtime_id', $showtimeId)
            ->get();
        $priceSeat = $seatShowtimes->sum('price');


        // 4. Tính giá combo
        $priceCombo = 0;
        foreach ($request->combo as $comboId => $quantity) {
            if ($quantity > 0) {
                $combo = Combo::findOrFail($comboId);
                $comboPrice = $combo->price_sale ?? $combo->price; // Nếu có giá khuyến mãi thì dùng, không thì dùng giá gốc
                $priceCombo += $comboPrice * $quantity;
            }
        }

        // 5. Xác thực và tính giá voucher
        $voucherDiscount = 0;
        $voucher =   null;
        if (session()->has('payment_voucher')) {
            try {
                $voucher = DB::transaction(function () use (&$voucherDiscount, &$voucherCode) {
                    // Khóa bản ghi voucher để tránh xung đột
                    $voucher = Voucher::where('id', session('payment_voucher.voucher_id'))
                        ->lockForUpdate()
                        ->first();

                    // Kiểm tra nếu voucher tồn tại và có số lượng lớn hơn 0
                    if ($voucher && $voucher->quantity > 0) {
                        $voucherDiscount = $voucher->discount;
                        $voucherCode = $voucher->code;
                        $voucher->decrement('quantity');

                        return $voucher;
                    } else {
                        throw new \Exception('Rất tiếc, mã voucher bạn sử dụng đã đạt giới hạn. Xin vui lòng sử dụng mã khác.'); // Ném ra exception nếu voucher không hợp lệ
                    }
                });
            } catch (\Exception $e) {
                return redirect()->route('checkout', $showtime->slug)
                    ->with('error', $e->getMessage());
            }
        }
        // 6. Tính giảm giá từ điểm tích lũy (nếu có trong session)
        $dataUsePoint = session('payment_point', []);
        $pointDiscount = $dataUsePoint['point_discount'] ?? 0;

        // 7. Tính tổng giá, tổng giảm giá và tổng thanh toán
        $totalPrice = $priceSeat + $priceCombo;
        $totalDiscount = $pointDiscount + $voucherDiscount;
        $totalPayment = max($totalPrice - $totalDiscount, 10000); // Đảm bảo giá tối thiểu là 10k

        $hasExpiredSeats = false; // Biến đánh dấu có ghế hết thời gian giữ chỗ
        foreach ($seatShowtimes as $seatShowtime) {
            // Kiểm tra xem có ghế xem có đủ tiêu chuẩn để đc bấm nút tiếp tục hay không
            if ($seatShowtime->hold_expires_at < now() || $seatShowtime->user_id != $userId || $seatShowtime->status != 'hold') {
                $hasExpiredSeats = true; // Đánh dấu có ghế hết thời gian giữ chỗ
                break; // Dừng vòng lặp khi tìm thấy ghế hết thời gian giữ
            }
        }

        if ($hasExpiredSeats) {
            // Nếu có bất kỳ ghế nào hết thời gian giữ chỗ hoặc != hold hoặc khác người giữ chỗ
            // Chuyển hướng về trang chọn ghế với thông báo lỗi
            return redirect()->route('choose-seat', $showtime->slug)
                ->with('error', 'Ghế đã hết thời gian giữ chỗ hoặc ghế đã bán. Vui lòng chọn lại ghế.');
        }

        try {
            // Nếu không có ghế nào hết thời gian giữ, tiếp tục với transaction
            DB::transaction(function () use ($seatIds, $showtimeId, $userId, $request, $voucherDiscount, $totalPayment, $pointDiscount, $dataUsePoint, $priceSeat, $priceCombo, $voucher) {
                // Gia hạn thời gian giữ chỗ thêm 15 phút
                DB::table('seat_showtimes')
                    ->whereIn('seat_id', $seatIds)
                    ->where('showtime_id', $showtimeId)
                    ->update([
                        'hold_expires_at' => now()->addMinutes(15),
                    ]);

                // Lưu thông tin thanh toán vào session
                session([
                    'payment_data' => [
                        'code' => Ticket::generateTicketCode(),
                        'user_id' => $request->user_id,
                        'payment_name' => $request->payment_name,
                        'voucher_code' => $voucher->code ?? null,
                        'voucher_discount' => $voucher->discount ?? 0,
                        'point_use' => $dataUsePoint['use_points'] ?? null,
                        'point_discount' => $pointDiscount,
                        'total_price' => $totalPayment,
                        'showtime_id' => $request->showtime_id,
                        'seat_id' => $request->seat_id,
                        'priceSeat' => $priceSeat,
                        'priceCombo' => $priceCombo,
                        'combo' => $request->combo,
                    ]
                ]);

                // Dispatch job để giải phóng ghế sau 15 phút
                ReleaseSeatHoldJob::dispatch($seatIds, $showtimeId, $voucher->code ?? null)->delay(now()->addMinutes(15));

            });

            // Chuyển hướng tới trang thanh toán
            if ($request->payment_name == 'momo') {
                return redirect()->route('momo.payment');
            } else if ($request->payment_name == 'vnpay') {
                return redirect()->route('vnpay.payment');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán. Vui lòng thử lại.');
        }
    }


    // ====================THANH TOÁN MOMO==================== //

    // Hàm gửi request POST tới MoMo
    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // Khởi tạo thanh toán MoMo
    public function moMoPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $paymentData = session()->get('payment_data', []);

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $paymentData['total_price'];
        $orderId = $paymentData['code'];
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.notify');
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        return redirect()->to($jsonResult['payUrl']);
    }

    // Xử lý khi người dùng được chuyển về từ MoMo
    public function returnPayment(Request $request)
    {
        // lấy dữ liệu của session và kiểm tra nó xem còn tồn tại hay không
        $paymentData = session()->get('payment_data', []);
        if (empty($paymentData)) {
            return redirect()->route('home')->with('error', 'Dữ liệu thanh toán không tồn tại.');
        }

        $resultCode = $request->resultCode;
        $orderId = $request->orderId;
        $showtime = Showtime::find($paymentData['showtime_id']);

        if ($resultCode == 0) {  // Thanh toán thành công
            try {
                DB::transaction(function () use ($paymentData, $showtime) {
                    $existingTicket = Ticket::where('code', $paymentData['code'])->first();
                    if ($existingTicket) {
                        throw new \Exception('Đơn hàng đã được xử lý.');
                    }

                    // Lưu vào bảng tickets
                    $ticket = Ticket::create([
                        'user_id' => $paymentData['user_id'],
                        'cinema_id' => $showtime->cinema_id,
                        'room_id' => $showtime->room_id,
                        'movie_id' => $showtime->movie_id,
                        'showtime_id' => $paymentData['showtime_id'],
                        'voucher_code' => $paymentData['voucher_code'],
                        'voucher_discount' => $paymentData['voucher_discount'],
                        'point_use' => $paymentData['point_use'],
                        'point_discount' => $paymentData['point_discount'],
                        'payment_name' => "Ví MoMo",
                        'code' => $paymentData['code'],
                        'total_price' => $paymentData['total_price'],
                        'status' => 'Chưa xuất vé',
                        'expiry' => $showtime->end_time,
                    ]);

                    // Lưu thông tin bảng ticket_seat và update lại status của ghế
                    foreach ($paymentData['seat_id'] as $seatId) {
                        TicketSeat::create([
                            'ticket_id' => $ticket->id,
                            // 'showtime_id' => $paymentData['showtime_id'],
                            'seat_id' => $seatId,
                            'price' => DB::table('seat_showtimes')
                                ->where('seat_id', $seatId)
                                ->where('showtime_id', $paymentData['showtime_id'])
                                ->value('price'),
                        ]);

                        DB::table('seat_showtimes')
                            ->where('seat_id', $seatId)
                            ->where('showtime_id', $paymentData['showtime_id'])
                            ->update([
                                'status' => 'sold',
                                'hold_expires_at' => null,
                            ]);

                        // event(new SeatStatusChange($seatId, $paymentData['showtime_id'], 'sold'));
                        broadcast(new SeatStatusChange($seatId, $paymentData['showtime_id'], 'sold'))->toOthers();
                    }

                    // Lưu thông tin combo vào bảng ticket_combos
                    foreach ($paymentData['combo'] as $comboId => $quantity) {
                        if ($quantity > 0) {
                            $combo = Combo::find($comboId);

                            // Tính giá bằng price_sale nếu có, nếu không thì lấy price
                            $price = $combo->price_sale ?? $combo->price;

                            TicketCombo::create([
                                'ticket_id' => $ticket->id,
                                'combo_id' => $comboId,
                                'price' => $price * $quantity,  // Nhân giá với số lượng
                                'quantity' => $quantity,
                                // 'status' => 'Chưa lấy đồ ăn',
                            ]);
                        }
                    }


                    // Lấy thông tin thành viên
                    $membership = Membership::findOrFail($ticket->user_id);

                    // Tiêu điểm
                    if ($ticket->point_use > 0) {
                        $membership->decrement('points', $ticket->point_use);
                        PointHistory::create([
                            'membership_id' => $membership->id,
                            'points' => $ticket->point_use,
                            'type' => PointHistory::POINTS_SPENT,
                        ]);
                    }

                    // Tích điểm
                    $rank = Rank::findOrFail($membership->rank_id);
                    $pointsForTicket = $paymentData['priceSeat'] * ($rank->ticket_percentage / 100);
                    $pointsForCombo = $paymentData['priceCombo'] * ($rank->combo_percentage / 100);
                    $totalPoints = $pointsForTicket + $pointsForCombo;

                    $membership->increment('points', $totalPoints);
                    $membership->increment('total_spent', $ticket->total_price);
                    PointHistory::create([
                        'membership_id' => $membership->id,
                        'points' => $totalPoints,
                        'type' => PointHistory::POINTS_ACCUMULATED,

                    ]);

                    // Kiểm tra thăng hạng
                    $newRank = Rank::where('total_spent', '<=', $membership->total_spent)
                        ->orderBy('total_spent', 'desc')
                        ->first();

                    if ($newRank && $newRank->id != $membership->rank_id) {
                        $membership->update(['rank_id' => $newRank->id]);
                    }
                    if (session()->has('payment_voucher')) {
                        $voucher = Voucher::find(session('payment_voucher.voucher_id'));
                        if ($voucher) {
                            $userVoucher = UserVoucher::where('user_id', $paymentData['user_id'])
                                ->where('voucher_id', $voucher->id)
                                ->first();

                            if ($userVoucher) {
                                // Nếu đã tồn tại, tăng usage_count
                                $userVoucher->increment('usage_count');
                            } else {
                                // Nếu chưa tồn tại, tạo bản ghi mới với usage_count = 1
                                UserVoucher::create([
                                    'user_id' => $paymentData['user_id'],
                                    'voucher_id' => $voucher->id,
                                    'usage_count' => 1,
                                ]);
                            }
                        }
                    }

                    // // lưu voucher lượt sd voucher
                    // if ($paymentData['voucher_code'] != null) {
                    //     $voucher = Voucher::where('code', $paymentData['voucher_code'])->first();
                    //     if ($voucher) {
                    //         $userVoucher = UserVoucher::where('user_id', $paymentData['user_id'])
                    //             ->where('voucher_id', $voucher->id)
                    //             ->first();

                    //         if ($userVoucher) {
                    //             // Nếu đã tồn tại, tăng usage_count
                    //             $userVoucher->increment('usage_count');
                    //         } else {
                    //             // Nếu chưa tồn tại, tạo bản ghi mới với usage_count = 1
                    //             UserVoucher::create([
                    //                 'user_id' => $paymentData['user_id'],
                    //                 'voucher_id' => $voucher->id,
                    //                 'usage_count' => 1,
                    //             ]);
                    //         }
                    //     }
                    // }

                    // Gửi email hóa đơn
                    Mail::to($ticket->user->email)->send(new TicketInvoiceMail($ticket));
                });

                $timeKey = 'timeData.' . $paymentData['showtime_id'];

                session()->forget($timeKey);
                session()->forget("checkout_data.$showtime->id");
                session()->forget('payment_data');

                return redirect()->route('home')->with('success', 'Thanh toán thành công!');
            } catch (\Exception $e) {
                // Xử lý thanh toán thất bại hoặc hủy
                return $this->handleFailedPayment($paymentData);
            }
        } else {
            // Xử lý thanh toán thất bại hoặc hủy
            return $this->handleFailedPayment($paymentData);
        }
    }

    public function handleFailedPayment($paymentData)
    {
        $showtime = Showtime::find($paymentData['showtime_id']);

        //trường hợp nếu hủy hoặc thanh toán thất bại
        DB::table('seat_showtimes')
            ->whereIn('seat_id', $paymentData['seat_id'])
            ->where('showtime_id', $paymentData['showtime_id'])
            ->update([
                'status' => 'available',
                'user_id' => null,
                'hold_expires_at' => null,
            ]);

        foreach ($paymentData['seat_id'] as $seatId) {
            // event(new SeatStatusChange($seatId, $paymentData['showtime_id'], 'available'));
            broadcast(new SeatStatusChange($seatId, $paymentData['showtime_id'], 'available'))->toOthers();
        }

        if($paymentData['voucher_code'] != null){
            $voucher = Voucher::where('code', $paymentData['voucher_code'])->first();
            $voucher->increment('quantity');
        }

        // xóa session
        $timeKey = 'timeData.' . $paymentData['showtime_id'];

        session()->forget($timeKey);
        session()->forget("checkout_data.$showtime->id");
        session()->forget('payment_data');

        return redirect()->route('home')->with('error', 'Thanh toán thất bại hoặc đã hủy.');
    }

    // Xử lý thông báo từ MoMo
    public function notifyPayment(Request $request)
    {
        // Ghi log nhận thông báo từ MoMo
        Log::info('Momo payment notify: ', $request->all());

        // Xử lý logic tùy theo trạng thái từ MoMo
        return response()->json(['status' => 'success']);
    }

    // ====================END THANH TOÁN MOMO==================== //

    // ================================================================================ //

    // ====================THANH TOÁN VNPAY==================== //

    public function vnPayPayment(Request $request)
    {
        // Lấy dữ liệu thanh toán từ session
        $paymentData = session()->get('payment_data', []);

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "6Q5Z9DG8"; //Mã website tại VNPAY
        $vnp_HashSecret = "NSEYDYAIT1XETEVUA24DF40DOCMC6NYE"; //Chuỗi bí mật

        $vnp_TxnRef = $paymentData['code']; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này

        $vnp_OrderInfo = 'Nội dung thang toán';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $paymentData['total_price'] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        // $vnp_ExpireDate = $_POST['txtexpire'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
            // "vnp_ExpireDate" => $vnp_ExpireDate,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );
        if (isset($request)) {
            // Chuyển hướng tới trang thanh toán của VNPAY
            return redirect($vnp_Url);
        } else {
            echo json_encode($returnData);
        }
    }

    public function returnVnpay(Request $request)
    {
        // lấy dữ liệu của session và kiểm tra nó xem còn tồn tại hay không
        $paymentData = session()->get('payment_data', []);
        if (empty($paymentData)) {
            return redirect()->route('home')->with('error', 'Dữ liệu thanh toán không tồn tại.');
        }

        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_TxnRef = $request->input('vnp_TxnRef'); // mã giao dịch duy nhất
        $showtime = Showtime::find($paymentData['showtime_id']);

        // Kiểm tra nếu thanh toán thành công
        if ($vnp_ResponseCode == '00') {
            try {
                DB::transaction(function () use ($paymentData, $showtime) {
                    $existingTicket = Ticket::where('code', $paymentData['code'])->first();
                    if ($existingTicket) {
                        throw new \Exception('Đơn hàng đã được xử lý.');
                    }

                    // Lưu vào bảng tickets
                    $ticket = Ticket::create([
                        'user_id' => $paymentData['user_id'],
                        'cinema_id' => $showtime->cinema_id,
                        'room_id' => $showtime->room_id,
                        'movie_id' => $showtime->movie_id,
                        'showtime_id' => $paymentData['showtime_id'],
                        'voucher_code' => $paymentData['voucher_code'],
                        'voucher_discount' => $paymentData['voucher_discount'],
                        'point_use' => $paymentData['point_use'],
                        'point_discount' => $paymentData['point_discount'],
                        'payment_name' => "Ví VnPay",
                        'code' => $paymentData['code'],
                        'total_price' => $paymentData['total_price'],
                        'status' => 'Chưa xuất vé',
                        'expiry' => $showtime->end_time,
                    ]);

                    // Lưu thông tin bảng ticket_seat và update lại status của ghế
                    foreach ($paymentData['seat_id'] as $seatId) {
                        TicketSeat::create([
                            'ticket_id' => $ticket->id,
                            // 'showtime_id' => $paymentData['showtime_id'],
                            'seat_id' => $seatId,
                            'price' => DB::table('seat_showtimes')
                                ->where('seat_id', $seatId)
                                ->where('showtime_id', $paymentData['showtime_id'])
                                ->value('price'),
                        ]);

                        DB::table('seat_showtimes')
                            ->where('seat_id', $seatId)
                            ->where('showtime_id', $paymentData['showtime_id'])
                            ->update([
                                'status' => 'sold',
                                'hold_expires_at' => null,
                            ]);

                        // event(new SeatStatusChange($seatId, $paymentData['showtime_id'], 'sold'));
                        broadcast(new SeatStatusChange($seatId, $paymentData['showtime_id'], 'sold'))->toOthers();
                    }

                    // Lưu thông tin combo vào bảng ticket_combos
                    foreach ($paymentData['combo'] as $comboId => $quantity) {
                        if ($quantity > 0) {
                            $combo = Combo::find($comboId);

                            // Tính giá bằng price_sale nếu có, nếu không thì lấy price
                            $price = $combo->price_sale ?? $combo->price;

                            TicketCombo::create([
                                'ticket_id' => $ticket->id,
                                'combo_id' => $comboId,
                                'price' => $price * $quantity,  // Nhân giá với số lượng
                                'quantity' => $quantity,
                                // 'status' => 'Chưa lấy đồ ăn',
                            ]);
                        }
                    }

                    // Lấy thông tin thành viên
                    $membership = Membership::findOrFail($ticket->user_id);

                    // Tiêu điểm
                    if ($ticket->point_use > 0) {
                        $membership->decrement('points', $ticket->point_use);
                        PointHistory::create([
                            'membership_id' => $membership->id,
                            'points' => $ticket->point_use,
                            'type' => PointHistory::POINTS_SPENT,
                        ]);
                    }

                    // Tích điểm
                    $rank = Rank::findOrFail($membership->rank_id);
                    $pointsForTicket = $paymentData['priceSeat'] * ($rank->ticket_percentage / 100);
                    $pointsForCombo = $paymentData['priceCombo'] * ($rank->combo_percentage / 100);
                    $totalPoints = $pointsForTicket + $pointsForCombo;

                    $membership->increment('points', $totalPoints);
                    $membership->increment('total_spent', $ticket->total_price);
                    PointHistory::create([
                        'membership_id' => $membership->id,
                        'points' => $totalPoints,
                        'type' => PointHistory::POINTS_ACCUMULATED,

                    ]);

                    // Kiểm tra thăng hạng
                    $newRank = Rank::where('total_spent', '<=', $membership->total_spent)
                        ->orderBy('total_spent', 'desc')
                        ->first();

                    if ($newRank && $newRank->id != $membership->rank_id) {
                        $membership->update(['rank_id' => $newRank->id]);
                    }

                    if (session()->has('payment_voucher')) {
                        $voucher = Voucher::find(session('payment_voucher.voucher_id'));
                        if ($voucher) {
                            $userVoucher = UserVoucher::where('user_id', $paymentData['user_id'])
                                ->where('voucher_id', $voucher->id)
                                ->first();

                            if ($userVoucher) {
                                // Nếu đã tồn tại, tăng usage_count
                                $userVoucher->increment('usage_count');
                            } else {
                                // Nếu chưa tồn tại, tạo bản ghi mới với usage_count = 1
                                UserVoucher::create([
                                    'user_id' => $paymentData['user_id'],
                                    'voucher_id' => $voucher->id,
                                    'usage_count' => 1,
                                ]);
                            }
                        }
                    }
                    // // lưu voucher lượt sd voucher
                    // if ($paymentData['voucher_code'] != null) {
                    //     $voucher = Voucher::where('code', $paymentData['voucher_code'])->first();
                    //     if ($voucher) {
                    //         $userVoucher = UserVoucher::where('user_id', $paymentData['user_id'])
                    //             ->where('voucher_id', $voucher->id)
                    //             ->first();

                    //         if ($userVoucher) {
                    //             // Nếu đã tồn tại, tăng usage_count
                    //             $userVoucher->increment('usage_count');
                    //         } else {
                    //             // Nếu chưa tồn tại, tạo bản ghi mới với usage_count = 1
                    //             UserVoucher::create([
                    //                 'user_id' => $paymentData['user_id'],
                    //                 'voucher_id' => $voucher->id,
                    //                 'usage_count' => 1,
                    //             ]);
                    //         }
                    //     }
                    // }

                    // Gửi email hóa đơn
                    Mail::to($ticket->user->email)->send(new TicketInvoiceMail($ticket));
                });

                $timeKey = 'timeData.' . $paymentData['showtime_id'];

                session()->forget($timeKey);
                session()->forget("checkout_data.$showtime->id");
                session()->forget('payment_data');

                return redirect()->route('home')->with('success', 'Thanh toán thành công!');
            } catch (\Exception $e) {
                // Xử lý thanh toán thất bại hoặc hủy
                return $this->handleFailedPayment($paymentData);
            }
        } else {
            // Xử lý thanh toán thất bại hoặc hủy
            return $this->handleFailedPayment($paymentData);
        }
    }
    // ====================END THANH TOÁN VNPAY==================== //

    public function paymentAdmin(Request $request)
    {
        // dd(session()->all());
        // 1. Xác thực dữ liệu đầu vào
        $request->validate([
            'seat_id' => 'required|array',
            'seat_id.*' => 'integer|exists:seats,id',
            'combo' => 'nullable|array',
            'combo.*' => 'nullable|integer|min:0|max:10',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ]);

        // 2. Lấy dữ liệu từ request và các thông tin liên quan
        $seatIds = $request->seat_id;
        $showtimeId = $request->showtime_id;
        $showtime = Showtime::findOrFail($showtimeId);
        $authId = auth()->id();
        $customerId = $authId;
        if (session()->has('customer')) {
            $customerId = session('customer');
        }

        // 3. Kiểm tra ghế tồn tại trong suất chiếu và tính tổng giá ghế
        $seatShowtimes = DB::table('seat_showtimes')
            ->whereIn('seat_id', $seatIds)
            ->where('showtime_id', $showtimeId)
            ->get();
        $priceSeat = $seatShowtimes->sum('price');

        // 4. Tính giá combo
        $priceCombo = 0;
        foreach ($request->combo as $comboId => $quantity) {
            if ($quantity > 0) {
                $combo = Combo::findOrFail($comboId);
                $comboPrice = $combo->price_sale ?? $combo->price; // Nếu có giá khuyến mãi thì dùng, không thì dùng giá gốc
                $priceCombo += $comboPrice * $quantity;
            }
        }


        // 5. Xác thực và tính giá voucher
        $voucherDiscount = 0;
        $voucher =   null;
        if (session()->has('payment_voucher')) {
            try {
                $voucher = DB::transaction(function () use (&$voucherDiscount, &$voucherCode) {
                    // Khóa bản ghi voucher để tránh xung đột
                    $voucher = Voucher::where('id', session('payment_voucher.voucher_id'))
                        ->lockForUpdate()
                        ->first();

                    // Kiểm tra nếu voucher tồn tại và có số lượng lớn hơn 0
                    if ($voucher && $voucher->quantity > 0) {
                        $voucherDiscount = $voucher->discount;
                        $voucherCode = $voucher->code;
                        $voucher->decrement('quantity');

                        return $voucher;
                    } else {
                        throw new \Exception('Rất tiếc, mã voucher bạn sử dụng đã đạt giới hạn. Xin vui lòng sử dụng mã khác.'); // Ném ra exception nếu voucher không hợp lệ
                    }
                });
            } catch (\Exception $e) {
                return redirect()->route('checkout', $showtime->slug)
                    ->with('error', $e->getMessage());
            }
        }

        // 6. Tính giảm giá từ điểm tích lũy (nếu có trong session)
        $dataUsePoint = session('payment_point', []);
        $pointDiscount = $dataUsePoint['point_discount'] ?? 0;

        // 7. Tính tổng giá, tổng giảm giá và tổng thanh toán
        $totalPrice = $priceSeat + $priceCombo;
        $totalDiscount = $pointDiscount + $voucherDiscount;
        $totalPayment = max($totalPrice - $totalDiscount, 10000); // Đảm bảo giá tối thiểu là 10k

        // 8. Thiết lập dữ liệu ticket
        $dataTicket = [
            'code' => Ticket::generateTicketCode(),
            'cinema_id' => $showtime->cinema_id,
            'room_id' => $showtime->room_id,
            'movie_id' => $showtime->movie_id,
            'user_id' => $customerId,
            'showtime_id' => $showtimeId,
            'staff_id' => $authId,
            'payment_name' => "Thanh toán tiền mặt",
            'voucher_code' => $voucherCode ?? null,
            'voucher_discount' => $voucherDiscount,
            'point_use' => $dataUsePoint['use_points'] ?? null,
            'point_discount' => $pointDiscount,
            'total_price' => $totalPayment,
            'status' => 'Chưa xuất vé',
            'expiry' => $showtime->end_time,
        ];

        // 9. Kiểm tra trạng thái giữ chỗ của ghế
        $hasExpiredSeats = false;
        foreach ($seatShowtimes as $seatShowtime) {
            if ($seatShowtime->hold_expires_at < now()) {
                $hasExpiredSeats = true;
                break;
            }
        }

        // 10. Xử lý ghế hết thời gian giữ chỗ
        $hasExpiredSeats = false; // Biến đánh dấu có ghế hết thời gian giữ chỗ
        foreach ($seatShowtimes as $seatShowtime) {
            // Kiểm tra xem có ghế xem có đủ tiêu chuẩn để đc bấm nút tiếp tục hay không
            if ($seatShowtime->hold_expires_at < now() || $seatShowtime->user_id != $authId || $seatShowtime->status != 'hold') {
                $hasExpiredSeats = true; // Đánh dấu có ghế hết thời gian giữ chỗ
                break; // Dừng vòng lặp khi tìm thấy ghế hết thời gian giữ
            }
        }

        if ($hasExpiredSeats) {
            // Nếu có bất kỳ ghế nào hết thời gian giữ chỗ hoặc != hold hoặc khác người giữ chỗ
            // Chuyển hướng về trang chọn ghế với thông báo lỗi
            return redirect()->route('choose-seat', $showtime->slug)
                ->with('error', 'Ghế đã hết thời gian giữ chỗ hoặc ghế đã bán. Vui lòng chọn lại ghế.');
        }

        // 11. Thực hiện transaction nếu không có ghế hết thời gian giữ
        // try {
        DB::transaction(function () use ($dataTicket, $seatIds, $showtimeId, $request, $priceSeat, $priceCombo, $voucher, $customerId, $showtime) {
            // Tạo ticket
            $ticket = Ticket::create($dataTicket);

            // Tạo ticket_seat và cập nhật trạng thái ghế
            foreach ($seatIds as $seatId) {
                // Lấy giá ghế từ seat_showtimes
                $price = DB::table('seat_showtimes')
                    ->where('seat_id', $seatId)
                    ->where('showtime_id', $showtimeId)
                    ->value('price');

                TicketSeat::create([
                    'ticket_id' => $ticket->id,
                    // 'showtime_id' => $showtimeId,
                    'seat_id' => $seatId,
                    'price' => $price,
                ]);

                // Cập nhật trạng thái ghế
                DB::table('seat_showtimes')
                    ->where('seat_id', $seatId)
                    ->where('showtime_id', $showtimeId)
                    ->update([
                        'status' => 'sold',
                        'hold_expires_at' => null,
                    ]);

                // event(new SeatSold($seatId, $showtimeId));
                broadcast(new SeatStatusChange($seatId, $showtimeId, 'sold'))->toOthers();
            }

            // Tạo ticket_combo
            foreach ($request->combo as $comboId => $quantity) {
                if ($quantity > 0) {
                    $combo = Combo::findOrFail($comboId); // Sử dụng findOrFail
                    $price = $combo->price_sale ?? $combo->price;
                    TicketCombo::create([
                        'ticket_id' => $ticket->id,
                        'combo_id' => $comboId,
                        'price' => $price * $quantity,
                        'quantity' => $quantity,
                        // 'status' => 'Chưa lấy đồ ăn',
                    ]);
                }
            }

            // Lấy thông tin thành viên
            $membership = Membership::findOrFail($ticket->user_id);

            // Tiêu điểm
            if ($ticket->point_use > 0) {
                $membership->decrement('points', $ticket->point_use);
                PointHistory::create([
                    'membership_id' => $membership->id,
                    'points' => $ticket->point_use,
                    'type' => PointHistory::POINTS_SPENT,
                ]);
            }

            // Tích điểm
            $rank = Rank::findOrFail($membership->rank_id);
            $pointsForTicket = $priceSeat * ($rank->ticket_percentage / 100);
            $pointsForCombo = $priceCombo * ($rank->combo_percentage / 100);
            $totalPoints = $pointsForTicket + $pointsForCombo;

            $membership->increment('points', $totalPoints);
            $membership->increment('total_spent', $ticket->total_price);
            PointHistory::create([
                'membership_id' => $membership->id,
                'points' => $totalPoints,
                'type' => PointHistory::POINTS_ACCUMULATED
            ]);

            // Kiểm tra thăng hạng
            $newRank = Rank::where('total_spent', '<=', $membership->total_spent)
                ->orderBy('total_spent', 'desc')
                ->first();

            if ($newRank && $newRank->id != $membership->rank_id) {
                $membership->update(['rank_id' => $newRank->id]);
            }

            // lưu voucher lượt sd voucher

            if ($voucher) {
                $userVoucher = UserVoucher::where('user_id', $customerId)
                    ->where('voucher_id', $voucher->id)
                    ->first();

                if ($userVoucher) {
                    // Nếu đã tồn tại, tăng usage_count
                    $userVoucher->increment('usage_count');
                } else {
                    // Nếu chưa tồn tại, tạo bản ghi mới với usage_count = 1
                    UserVoucher::create([
                        'user_id' => $customerId,
                        'voucher_id' => $voucher->id,
                        'usage_count' => 1,
                    ]);
                }
            }

            $timeKey = 'timeData.' . $showtimeId;

            session()->forget($timeKey);
            session()->forget("checkout_data.$showtime->id");
            session()->forget('payment_data');
        });

        return redirect()->route('home')->with('success', 'Thanh toán thành công!');
        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán. Vui lòng thử lại.');
        // }
    }
}
