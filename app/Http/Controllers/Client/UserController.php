<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UpdateUserRequest;
use App\Models\Rank;
use App\Models\Ticket;
use App\Models\TicketMovie;
use App\Models\TicketSeat;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PasswordChanged;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Thông tin tài khoản
    const PATH_UPLOAD = 'my-account';
    public function edit(string $page = 'my-account')
    {
        $pages = [
            'my-account',
            'membership',
            'cinema-journey',
            'my-voucher'
        ];

        // Kiểm tra nếu $page không nằm trong mảng $pages, gán lại $page là 'my-account'
        if (!in_array($page, $pages)) {
            $page = 'my-account';
        }
        $userID = Auth::user()->id;
        $user = User::with('membership')->findOrFail($userID);
        $genders = User::GENDERS;
        $ranks = Rank::orderBy('total_spent', 'asc')->get();
        $vouchers = $this->getVoucher($userID);
        $tickets = Ticket::query()->with('ticketSeats')->where('user_id', $userID)->latest('id')->get();
        // $tickets = TicketMovie::with('ticket', 'movie')->where('tickets.user_id', $userID)->paginate(5);
        return view('client.users.my-account', compact('user', 'genders', 'tickets','ranks','page','vouchers'));
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

    public function update(UpdateUserRequest $request)
    {
        $userID = Auth::user()->id;
        $user = User::findOrFail($userID);
        try {
            $dataUser = [
                'name' => $request->name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
            ];


            if ($user->birthday == null && $request->filled('birthday')) {
                $dataUser['birthday'] = $request->birthday;
            }
            if ($request->hasFile('img_thumbnail')) {
                $dataUser['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $request->img_thumbnail);
                $ImgThumbnailCurrent = $user->img_thumbnail;
            }

            $user->update($dataUser);

            if (!empty($ImgThumbnailCurrent) && ($dataUser['img_thumbnail'] ?? null) != $ImgThumbnailCurrent && Storage::exists($ImgThumbnailCurrent)) {
                Storage::delete($ImgThumbnailCurrent);
            }

             // Return success response for AJAX
             session()->flash('success', 'Thông tin của bạn đã được cập nhật thành công!');

             // Trả về phản hồi thành công
             return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->validator->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    // public function changePassword(Request $request)
    // {
    //     // Validate input
    //     $request->validate([
    //         'old_password' => 'required',
    //         'password' => 'required|min:8|max:30|confirmed',
    //     ], [
    //         'old_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
    //         'password.required' => 'Vui lòng nhập mật khẩu mới.',
    //         'password.min' => 'Mật khẩu tối thiểu phải 8 ký tự.',
    //         'password.max' => 'Mật khẩu không được quá 30 ký tự.',
    //         'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không trùng khớp.',
    //     ]);

    //     $user = User::findOrFail(Auth::user()->id);

    //     // Kiểm tra mật khẩu hiện tại
    //     if (!Hash::check($request->old_password, $user->password)) {
    //         return redirect()->back()->withErrors(['old_password' => 'Mật khẩu hiện tại không chính xác.']);
    //     }

    //     // Cập nhật mật khẩu mới
    //     $user->update([
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // Gửi thông báo qua email
    //     Notification::send($user, new PasswordChanged());

    //     return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    // }

    public function changePasswordAjax(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:8|max:30|confirmed',
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu tối thiểu phải 8 ký tự.',
            'password.max' => 'Mật khẩu không được quá 30 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không trùng khớp.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail(Auth::user()->id);

        // Check if old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Mật khẩu hiện tại không chính xác.'], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Gửi thông báo qua email
        Notification::send($user, new PasswordChanged());

        // Return success response for AJAX
        session()->flash('success', 'Đổi mật khẩu thành công!');

        // Trả về phản hồi thành công
        return response()->json(['success' => true]);
    }

    // Thẻ thành viên

    // ...

    // Hành trình điện ảnh

    // public function showCinemaJourney()
    // {
    //     return view('client.users.cinema-journey');
    // }

    public function ticketDetail($ticketId)
    {
        $userID = Auth::user()->id;

        $ticketSeat = Ticket::with(['ticketSeats', 'ticketCombos.combo'])
            ->where('user_id', $userID)
            ->where('id', $ticketId)
            ->get();




        $qrCode = QrCode::size(120)->generate($ticketSeat->first()->code);


        $barcode = DNS1D::getBarcodeHTML($ticketSeat->first()->code, 'C128', 1.5, 50);       //C39 , C128

        return view('client.users.ticket-detail', compact('ticketSeat', 'qrCode', 'barcode'));
    }

    // function transactionHistory()
    // {
    //     return redirect()->back();
    //     // dd('Quay trở về');
    // }
}
