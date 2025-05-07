<?php

namespace App\Jobs;

use App\Events\SeatRelease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ReleaseSeatHoldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $seatIds;
    public $showtimeId;

    /**
     * Create a new job instance.
     *
     * @param array $seatIds
     * @param mixed $showtimeId
     * @return void
     */
    public function __construct(array $seatIds, $showtimeId)
    {
        $this->seatIds = $seatIds;
        $this->showtimeId = $showtimeId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Gói trong transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::transaction(function () {
            // Lấy các ghế cần giải phóng và khóa chúng lại
            $seats = DB::table('seat_showtimes')
                ->whereIn('seat_id', $this->seatIds)
                ->where('showtime_id', $this->showtimeId)
                ->lockForUpdate()
                ->get();

            $now = Carbon::now();
            $expiredSeatIds = [];

            foreach ($seats as $seat) {
                // Chỉ giải phóng nếu thời gian hold đã hết và ghế vẫn ở trạng thái 'hold'
                if ($seat->status === 'hold' && $seat->hold_expires_at <= $now) {
                    DB::table('seat_showtimes')
                        ->where('seat_id', $seat->seat_id)
                        ->where('showtime_id', $this->showtimeId)
                        ->update([
                            'user_id' => null,
                            'status' => 'available', // Trạng thái ghế trở lại 'available'
                            'hold_expires_at' => null,
                        ]);

                    $expiredSeatIds[] = $seat->seat_id;

                    // Phát sự kiện giải phóng ghế
                    event(new SeatRelease($seat->seat_id, $this->showtimeId));
                }
            }

            // Nếu không có ghế nào bị giải phóng, không làm gì cả
            if (empty($expiredSeatIds)) {
                return;
            }

            // Ghi log hoặc xử lý thêm nếu cần (nếu cần ghi log)
            // Log::info('Seats released: ' . implode(', ', $expiredSeatIds));
        });
    }
}
