<?php

namespace App\Jobs;

use App\Mail\BirthdayVoucherMail;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use App\Models\VoucherConfig;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;

class CheckBirthdayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //xoas voucher cu
        /*$lastMonth = now()->subMonths()->month;
        $lastYear = now()->subMonths()->year;

        Voucher::where('type', 2)
            ->where('code', 'LIKE', 'BDAY%')
            ->whereMonth('start_date_time', $lastMonth)
            ->whereYear('start_date_time', $lastYear)
            ->delete();*/

        $users = User::whereMonth('birthday', now()->month)
            ->whereNotNull('email_verified_at')
            ->get();

        // Lấy số ngày chính xác của tháng hiện tại
        $daysInMonth = now()->daysInMonth;

        foreach ($users as $user) {
            // Kiểm tra xem người dùng đã có voucher sinh nhật chưa
            $existVoucher = $user->vouchers()
                ->where('type', 2)
                ->whereMonth('start_date_time', now()->month) // Voucher trong tháng hiện tại
                ->whereYear('start_date_time', now()->year) // Voucher trong năm hiện tại
                ->where('end_date_time', '>=', now()) // Voucher chưa hết hạn
                ->where('is_active', 1)
                ->first();

            // Nếu đã có voucher sinh nhật, bỏ qua người dùng này
            if ($existVoucher) {
                continue;
            }

            $discount = VoucherConfig::getValue('birthday_voucher', 50000);

            try {
                $voucherCode = null;

                do {
                    $randomString = Str::upper(Str::random(6));
                    $voucherCode = 'BDAY' . $user->id . $randomString;
                } while (Voucher::where('code', $voucherCode)->exists());

                $voucher = Voucher::create([
                    'code' => $voucherCode,
                    'title' => 'Voucher Sinh Nhật của ' . $user->name,
                    'description' => 'Voucher giảm giá sinh nhật tháng ' . now()->month,
                    'start_date_time' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'end_date_time' => Carbon::now('Asia/Ho_Chi_Minh')->addDays($daysInMonth),
                    'discount' => $discount,
                    'quantity' => 1,
                    'limit' => 1,
                    'is_active' => 1,
                    'is_publish' => 1,
                    'type' => 2,
                ]);

                // Gắn voucher vào người dùng
                $user->vouchers()->attach($voucher->id);

                // Gửi email thông báo voucher
                Mail::to($user->email)->queue(new BirthdayVoucherMail($user, $voucher));

            } catch (\Exception $e) {
                Log::error("Lỗi tạo voucher sinh nhật cho user {$user->id}: {$e->getMessage()}");
            }

        }
    }
}
