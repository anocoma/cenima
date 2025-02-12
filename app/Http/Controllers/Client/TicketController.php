<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\PointHistory;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    // Chức năng hủy vé đã bỏ k làm nữa
    // public function cancel(Ticket $ticket)
    // {
    //     try {
    //         $deadlineCancel = $ticket->showtime->start_time->subMinutes(Ticket::CANCELLATION_DEADLINE_MINUTES);
    //         if ($ticket->status == Ticket::NOT_ISSUED && now() < $deadlineCancel) {
    //             DB::transaction(function () use ($ticket) {

    //                 //Thay đổi trạng thái vé
    //                 $ticket->lockForUpdate();
    //                 $ticket->update([
    //                     'status' => Ticket::CANCELED
    //                 ]);



    //                 //Thay đổi trạng thái ghế trong bảng seat_showtime
    //                 $seatIds = [];

    //                 foreach ($ticket->ticketSeats as $ticketSeat) {
    //                     $seatIds[] = $ticketSeat->seat_id;
    //                 }
    //                 $seatShowtimes = DB::table('seat_showtimes')
    //                     ->whereIn('seat_id', $seatIds)
    //                     ->where('showtime_id', $ticket->showtime->id)
    //                     ->lockForUpdate()
    //                     ->get();

    //                 foreach ($seatShowtimes as $seatShowtime) {
    //                     // nếu ghế đã bán
    //                     if ($seatShowtime->status === 'sold') {
    //                         DB::table('seat_showtimes')
    //                             ->where('id', $seatShowtime->id)
    //                             ->where('seat_id', $seatShowtime->seat_id)
    //                             ->update(['status' => 'available']);
    //                     }
    //                 }


    //                 //Hoàn điểm
    //                 $membership = Membership::where('user_id',$ticket->user_id)->first();
    //                 $refundPoints = $ticket->total_price * Ticket::REFUND_POINTS_PERCENTAGE;
    //                 //Cập nhật tổng điểm ở thẻ thành viên

    //                 $membership->increment('points', $refundPoints );
    //                 PointHistory::create([
    //                     'membership_id' => $membership->id,
    //                     'points' => $refundPoints ,
    //                     'type' => PointHistory::POINTS_ACCUMULATED,
    //                     'expiry_date' => now()->addMonths(PointHistory::POINT_EXPIRY_DURATION),
    //                     // 'description' => 'Hủy vé xem phim'
    //                 ]);





    //             });

    //             return redirect()->to(route('my-account.edit') .'#cinema-journey')->with('success', 'Hủy vé thành công!');
    //         }else{
    //             return redirect()->to(route('my-account.edit') .'#cinema-journey')->with('error', 'Hủy vé không thành công, đã có lôi sảy ra trong quá trình hủy!');
    //         }


    //     } catch (\Throwable $th) {
    //         return redirect()->to(route('my-account.edit') .'#cinema-journey')->with('error', 'Hủy vé không thành công, đã có lôi sảy ra trong quá trình hủy!');
    //     }
    // }
}
