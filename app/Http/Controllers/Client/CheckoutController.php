<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Food;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    //
    public function checkout(string $slug)
    {
        // dd(session()->all());

        //Nếu tồn tại session thì hủy
        session()->forget(['customer', 'payment_voucher', 'payment_point']); // customer có thể là id khách hàng hoặc id admin

        $showtime = Showtime::where('slug', $slug)->first();
        // dd($showtime->toArray());

        if (empty($showtime)) {
            return redirect()->route('home')->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }

        $checkoutData = session()->get("checkout_data.$showtime->id", []);

        // Kiểm tra nếu session không tồn tại hoặc remainingSeconds <= 5 thì xóa session
        if (empty($checkoutData) || $checkoutData['remainingSeconds'] <= 5) {
            session()->forget("checkout_data.$showtime->id"); // Xóa session checkout_data

            return redirect()->route('home')->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }

        // Tính lại thời gian còn lại
        if (isset($checkoutData['remainingSeconds']) && isset($checkoutData['lastUpdated'])) {
            $elapsedTime = now()->diffInSeconds($checkoutData['lastUpdated']);
            $checkoutData['remainingSeconds'] = max(0, $checkoutData['remainingSeconds'] - $elapsedTime);
            session()->put("checkout_data.$showtime->id.remainingSeconds", $checkoutData['remainingSeconds']);
            session()->put("checkout_data.$showtime->id.lastUpdated", now()); // Cập nhật thời điểm hiện tại
        }

        //lấy ghế
        $showtimeId = $checkoutData['showtime_id'];
        $seats = Seat::whereIn('id', $checkoutData['seat_ids'])
            ->with(['typeSeat', 'showtimes' => function ($query) use ($showtimeId) {
                $query->where('showtime_id', $showtimeId);
            }])
            ->get();

        // Lấy danh sách combo và thực phẩm liên quan
        $data = Combo::query()->where('is_active', '1')->with('food')->latest('id')->get();
        $vouchers = $this->getVoucher(Auth::user()->id);

        // Trả về view với dữ liệu
        return view('client.checkout', compact('data', 'showtime', 'checkoutData', 'seats', 'vouchers'));
    }


    public function applyVoucher(Request $request)

    {
        $customerId = Auth::id();
        if (session()->has('customer')) {
            $customerId = session('customer');
        }
        $voucher = Voucher::where('is_active', 1)
            ->where('code', $request->code)
            ->first();

        if (!$voucher) {
            return response()->json(['error' => 'Voucher không hợp lệ.'], 400);
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        if ($voucher->start_date_time > $now || $voucher->end_date_time < $now) {
            return response()->json(['error' => 'Voucher không còn hiệu lực.'], 400);
        }

        if ($voucher->quantity < 1) {
            return response()->json(['error' => 'Voucher đã được sử dụng hết.'], 400);
        }

        /*if ($voucher->type == 2) { // Voucher sinh nhật type =2
            $userVoucher = UserVoucher::where([
                'voucher_id' => $voucher->id,
                'user_id' => Auth::id(),
            ])->first();

            if (!$userVoucher) {
                return response()->json(['error' => 'Voucher sinh nhật không áp dụng cho bạn.'], 400);
            }

            if ($userVoucher->usage_count >= $voucher->limit) {
                return response()->json(['error' => 'Voucher sinh nhật đã hết lượt sử dụng.'], 400);
            }
        } else { // Voucher toàn hệ thống (type = 1)
            $userVoucher = UserVoucher::where([
                'voucher_id' => $voucher->id,
                'user_id' => Auth::id(),
            ])->first();

            if ($userVoucher && $userVoucher->usage_count >= $voucher->limit) {
                return response()->json(['error' => 'Voucher không còn lượt sử dụng.'], 400);
            }
        }*/

        // Kiểm tra quyền truy cập vào voucher của người dùng
        $userVoucher = UserVoucher::firstOrNew([
            'voucher_id' => $voucher->id,
            'user_id' => $customerId,
        ]);

        if ($voucher->type == 2) { // Voucher sinh nhật
            if (!$userVoucher->exists) {
                return response()->json(['error' => 'Voucher sinh nhật không áp dụng cho bạn.'], 400);
            }
        }

        if ($userVoucher->usage_count >= $voucher->limit) {
            return response()->json(['error' => 'Voucher đã hết lượt sử dụng.'], 400);
        }

        if ($voucher->discount == 0) {
            return response()->json(['error' => 'Voucher không hợp lệ.'], 400);
        }


        $paymentVoucher = [
            'voucher_id' => $voucher->id,
            'voucher_code' => $voucher->code,
            'voucher_discount' => $voucher->discount,
        ];
        session(['payment_voucher' => $paymentVoucher]);
        return response()->json([
            'success' => 'Voucher hợp lệ!',
            'id' => $voucher->id,
            'voucher_code' => $voucher->code,
            'discount' => $voucher->discount,
        ]);
    }


    //    public function cancelVouche  r(Request $request)
    //    {
    //        $userVoucher = UserVoucher::where('user_id', auth()->id())
    //            ->where('voucher_id', $request->voucher_id)
    //            ->first();
    //
    //        if (!$userVoucher) {
    //            return response()->json(['error' => 'Voucher không tồn tại hoặc chưa được áp dụng.'], 400);
    //        }
    //
    //        $voucher = Voucher::find($userVoucher->voucher_id);
    //        if ($voucher) {
    //            $voucher->increment('quantity');
    //        }
    //
    //        $userVoucher->delete();
    //
    //        DB::transaction(function() use ($userVoucher) {
    //            $voucher = Voucher::find($userVoucher->voucher_id);
    //            if ($voucher) {
    //                $voucher->increment('quantity');
    //            }
    //
    //            $userVoucher->delete();
    //        });
    //
    //        return response()->json(['success' => 'Hủy voucher thành công!']);
    //    }

    public function cancelVoucher()
    {
        session()->forget('payment_voucher');
        return response()->json(['success' => true]);
    }


    public static function getVoucher($userId)
    {
        $user = User::whereNotNull('email_verified_at')
            ->find($userId);

        // Nếu user chưa xác nhận thì ko trả về voucher nào (rỗng)
        if (!$user) {
            return collect();
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách voucher
        $vouchersQuery = Voucher::where('is_publish', 1)
            ->where('is_active', 1)
            ->where('start_date_time', '<=', $now)
            ->where('end_date_time', '>=', $now)
            ->where('quantity', '>', 0)
            ->whereDoesntHave('users', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereColumn('user_vouchers.usage_count', '>=', 'vouchers.limit');
            });

        if (!$user->birthday) {
            $vouchersQuery->where('type', 1);
        } else {
            $vouchersQuery->where(function ($query) use ($userId) {
                $query->where('type', 1)
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('type', 2)
                            ->whereExists(function ($subquery) use ($userId) {
                                $subquery->from('user_vouchers')
                                    ->whereColumn('vouchers.id', 'user_vouchers.voucher_id')
                                    ->where('user_vouchers.user_id', $userId);
                            });
                    });
            });
        }

        $vouchers = $vouchersQuery->get();

        // Tính số lượt sử dụng còn lại
        foreach ($vouchers as $voucher) {
            $usageCount = $voucher->users()
                ->where('user_id', $userId)
                ->first()
                ->pivot->usage_count ?? 0;

            $voucher->remaining_uses = max(0, $voucher->limit - $usageCount);
        }

        return $vouchers;
    }
    public  function getMyVoucher(Request $request)
    {


        $userId = $request->userId;

        $user = User::findOrFail($userId);

        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy danh sách voucher
        $vouchersQuery = Voucher::where('is_publish', 1)
            ->where('is_active', 1)
            ->where('start_date_time', '<=', $now)
            ->where('end_date_time', '>=', $now)
            ->where('quantity', '>', 0)
            ->whereDoesntHave('users', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereColumn('user_vouchers.usage_count', '>=', 'vouchers.limit');
            });

        if (!$user->birthday) {
            $vouchersQuery->where('type', 1);
        } else {
            $vouchersQuery->where(function ($query) use ($userId) {
                $query->where('type', 1)
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('type', 2)
                            ->whereExists(function ($subquery) use ($userId) {
                                $subquery->from('user_vouchers')
                                    ->whereColumn('vouchers.id', 'user_vouchers.voucher_id')
                                    ->where('user_vouchers.user_id', $userId);
                            });
                    });
            });
        }

        $vouchers = $vouchersQuery->get();

        // Tính số lượt sử dụng còn lại
        foreach ($vouchers as $voucher) {
            $usageCount = $voucher->users()
                ->where('user_id', $userId)
                ->first()
                ->pivot->usage_count ?? 0;

            $voucher->remaining_uses = max(0, $voucher->limit - $usageCount);
        }

        return view('client.modals.data-my-voucher', compact('vouchers'))->render();
    }
}
