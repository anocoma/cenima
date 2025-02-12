<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VoucherCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $voucher;

    public function __construct($voucher)
    {
        $this->voucher = $voucher;
    }

    public function broadcastOn()
    {
        return new Channel('vouchers');
    }

    public function broadcastWith()
    {
        return [
            'code' => $this->voucher->code,
            'title' => $this->voucher->title,
            'discount' => $this->voucher->discount,
            'start_date_time' => $this->voucher->start_date_time,
            'end_date_time' => $this->voucher->end_date_time,
        ];
    }


}
