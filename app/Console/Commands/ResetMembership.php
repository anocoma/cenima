<?php

namespace App\Console\Commands;

use App\Models\Membership;
use Illuminate\Console\Command;

class ResetMembership extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset hạng thành viên hàng năm';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Đặt lại tổng chi tiêu và cấp hạng nếu cần
        Membership::query()->update(['total_spent' => 0, 'rank_id' => 1,'points'=>0]); // Ví dụ đặt rank_id về mặc định là 1

        $this->info('Hạng thành viên đã được reset.');
    }
}
