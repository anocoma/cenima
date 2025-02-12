<?php

namespace App\Console\Commands;

use App\Models\Membership;
use App\Models\PointHistory;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\DB;

class ExpirePoints extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'points:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật điểm hết hạn cho các thành viên';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredPoints = PointHistory::where('type', PointHistory::POINTS_ACCUMULATED)
            ->where('processed',false)
            ->where('expiry_date', '<=', now())
            ->get();
        $this->info('Số điểm hết hạn: ' . $expiredPoints->count());

        if ($expiredPoints->isEmpty()) {
            $this->info('Không có điểm nào hết hạn.'); // Thông báo khi không có điểm hết hạn
            return; // Kết thúc phương thức nếu không có điểm hết hạn
        }

        DB::transaction(function () use ($expiredPoints) {
            foreach ($expiredPoints as $point) {
                // Cập nhật điểm trong memberships
                $membership = Membership::where('id', $point->membership_id)
                    ->lockForUpdate() // Khóa bản ghi
                    ->first();
                if ($membership) {
                    $membership->update([
                        'points' => $membership->points - $point->points
                    ]);

                    PointHistory::create([
                        'membership_id' => $point->membership_id,
                        'points' => $point->points,
                        'type' => PointHistory::POINTS_EXPIRY,
                    ]);

                     // Đánh dấu điểm đã xử lý trừ điểm
                    $point->update([
                        'processed' => true,
                    ]);
                    $this->info('Đã trừ ' . $point->points . " điểm cho " . $point->membership->user->name .  ". Mã thành viên: " . $point->membership->code); // In ra thông tin người dùng
                }
            }
        });
    }
}
