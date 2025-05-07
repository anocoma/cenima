<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Jobs\ReleaseSeatHoldJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\SeatRelease;
use App\Events\SeatSold;
use App\Models\Combo;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\TicketCombo;
use App\Models\TicketSeat;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        // dd(session()->all());

        $seatIds = $request->seat_id; // Danh sách ghế từ request
        $showtimeId = $request->showtime_id;
        $userId = auth()->id(); // Lấy ID người dùng đang đăng nhập

        // Kiểm tra ghế trước khi bắt đầu transaction
        $seatShowtimes = DB::table('seat_showtimes')
            ->whereIn('seat_id', $seatIds)
            ->where('showtime_id', $showtimeId)
            ->get();

        $hasExpiredSeats = false; // Biến đánh dấu có ghế hết thời gian giữ chỗ

        foreach ($seatShowtimes as $seatShowtime) {
            // Kiểm tra xem có ghế nào hết thời gian giữ chỗ
            if ($seatShowtime->hold_expires_at < now()) {
                $hasExpiredSeats = true; // Đánh dấu có ghế hết thời gian giữ chỗ
                break; // Dừng vòng lặp khi tìm thấy ghế hết thời gian giữ
            }
        }

        if ($hasExpiredSeats) {
            // Nếu có bất kỳ ghế nào hết thời gian giữ chỗ, cập nhật tất cả ghế thành 'available'
            DB::table('seat_showtimes')
                ->whereIn('seat_id', $seatIds)
                ->where('showtime_id', $showtimeId)
                ->update([
                    'status' => 'available',
                    'user_id' => null,
                    'hold_expires_at' => null,
                ]);

            // Phát sự kiện Pusher để thông báo tất cả ghế được giải phóng cho người dùng khác
            foreach ($seatIds as $seatId) {
                event(new SeatRelease($seatId, $showtimeId));
            }

            // Chuyển hướng về trang chọn ghế với thông báo lỗi
            return redirect()->route('choose-seat', $showtimeId)
                ->with('error', 'Một hoặc nhiều ghế đã hết thời gian giữ chỗ. Vui lòng chọn lại ghế.');
        }

        try {
            // Nếu không có ghế nào hết thời gian giữ, tiếp tục với transaction
            DB::transaction(function () use ($seatIds, $showtimeId, $userId, $request) {
                // Gia hạn thời gian giữ chỗ thêm 5 phút
                DB::table('seat_showtimes')
                    ->whereIn('seat_id', $seatIds)
                    ->where('showtime_id', $showtimeId)
                    ->update([
                        'hold_expires_at' => now()->addMinutes(5),
                    ]);

                // Lưu thông tin thanh toán vào session
                session([
                    'payment_data' => [
                        'code' => $request->code,
                        'user_id' => $request->user_id,
                        'payment_name' => $request->payment_name,
                        'voucher_code' => $request->voucher_code,
                        'voucher_discount' => $request->voucher_discount,
                        'total_price' => $request->total_price,
                        'showtime_id' => $request->showtime_id,
                        'seat_id' => $request->seat_id,
                        'combo' => $request->combo,
                    ]
                ]);

                // Dispatch job để giải phóng ghế sau 5 phút
                ReleaseSeatHoldJob::dispatch($seatIds, $showtimeId)->delay(now()->addMinutes(5));
            });

            // Chuyển hướng tới trang thanh toán
            if ($request->payment_name == 'momo') {
                return redirect()->route('momo.payment');
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
                        'voucher_code' => $paymentData['voucher_code'],
                        'voucher_discount' => $paymentData['voucher_discount'],
                        'payment_name' => $paymentData['payment_name'],
                        'code' => $paymentData['code'],
                        'total_price' => $paymentData['total_price'],
                        'status' => 'Chưa xuất vé',
                        'expiry' => $showtime->end_time,
                    ]);

                    // Lưu thông tin bảng ticket_seat và update lại status của ghế
                    foreach ($paymentData['seat_id'] as $seatId) {
                        TicketSeat::create([
                            'ticket_id' => $ticket->id,
                            'showtime_id' => $paymentData['showtime_id'],
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

                        event(new SeatSold($seatId, $paymentData['showtime_id']));
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
                                'status' => 'Chưa lấy đồ ăn',
                            ]);
                        }
                    }
                });

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
            event(new SeatRelease($seatId, $paymentData['showtime_id']));
        }

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

    // ====================THANH TOÁN ZALOPAY==================== //

    public function zaloPayPayment(Request $request)
    {
        $config = [
            "app_id" => 2553,
            "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
            "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        $embeddata = json_encode(['redirecturl' => 'http://datn-hn5.test']); // Merchant's data
        $items = '[]'; // Merchant's data
        $transID = rand(0, 1000000); //Random trans id
        $order = [
            "app_id" => $config["app_id"],
            "app_time" => round(microtime(true) * 1000), // miliseconds
            "app_trans_id" => date("ymd") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
            "app_user" => "user123",
            "item" => $items,
            "embed_data" => $embeddata,
            "amount" => 150000,
            "description" => "Lazada - Payment for the order #$transID",
            "bank_code" => "",
            // "callback_url" => "http://localhost/zalopay/callback.php",   //
        ];

        // appid|app_trans_id|appuser|amount|apptime|embeddata|item
        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
            . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($resp, true);

        if ($result['return_code'] == 1) { //
            header("Location:" . $result['order_url']);
            exit;
        }

        foreach ($result as $key => $value) {
            echo "$key: $value<br>";
        }
    }

    // ====================END THANH TOÁN ZALOPAY==================== //

    // ================================================================================ //

    // ====================THANH TOÁN VNPAY==================== //

    public function vnPayPayment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://datn-hn5.test/";
        $vnp_TmnCode = "1U83ZQU1"; //Mã website tại VNPAY
        $vnp_HashSecret = "WEFJK48ZJU89ZE87ITT22GQ06JCNPP8B"; //Chuỗi bí mật
        $vnp_TxnRef = '123458'; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán vnpay test';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = 20000 * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';

        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
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

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
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
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }

    // ====================END THANH TOÁN VNPAY==================== //

}
