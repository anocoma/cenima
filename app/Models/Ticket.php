<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cinema_id',
        'room_id',
        'movie_id',
        'showtime_id',
        'payment_name',
        'voucher_code',
        'voucher_discount',
        'point_use',
        'point_discount',
        'code',
        'total_price',
        'status',
        'expiry',
        'staff'
    ];

    protected $casts = [
        'expiry' => 'datetime',
    ];

    // const STATUS = [
    //     ['value' => 'pending', 'label' => 'Chưa xuất vé'],
    //     ['value' => 'confirmed', 'label' => 'Đã xuất vé'],
    //     ['value' => 'cancelled', 'label' => 'Đã hết hạn'],
    // ];

    //Trạng thái vé
    const ISSUED = 'Đã xuất vé';
    const NOT_ISSUED = 'Chưa xuất vé';
    const EXPIRED = 'Đã hết hạn';
    const CANCELED = 'Đã hủy';

    // Được hủy vé trước suất chiếu bao nhiêu phút
    const CANCELLATION_DEADLINE_MINUTES = 60;

    const REFUND_POINTS_PERCENTAGE  = 1; //Tỷ lệ hoàn trả điểm 1= 100% , 0.8 = 50%
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function ticketSeats()
    {
        return $this->hasMany(TicketSeat::class);
    }

    public function ticketCombos()
    {
        return $this->hasMany(TicketCombo::class);
    }

    // Sơn sửa lại qh model
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    // //Hàm tạo mã vé: ví dụ hôm này là ngày 20/10/2024 thì hóa đơn sẽ có dạng HD201024-0001 , HD201024-0002,...
    // public static function generateTicketCode()
    // {
    //     // Lấy ngày hiện tại theo định dạng ddmmyy
    //     $dateCode = now()->setTimezone('Asia/Ho_Chi_Minh')->format('dmy');

    //     // Tìm mã vé mới nhất
    //     $latestTicket = Ticket::query()
    //         ->where('code', 'like', "HD{$dateCode}-%")
    //         ->latest('id')
    //         ->first();

    //     // Tính số thứ tự tiếp theo
    //     $nextNumber = $latestTicket ? ((int)substr($latestTicket->code, -4) + 1) : 1;

    //     // Tạo mã hóa đơn theo định dạng HDddmmyy-xxxx
    //     //  %s là chuỗi string tức là $dateCode ta lấy được 201024
    //     // %04d: Là số nguyên (integer) với độ dài 4 chữ số, và 0 ở đây chỉ định điền thêm số 0 vào phía trước nếu số không đủ độ dài tức  là nếu nextNumber = 123 thì trả về  0123
    //     return sprintf("HD%s-%04d", $dateCode, $nextNumber);
    // }

    public static function generateTicketCode()
    {
        // Lấy thời gian hiện tại theo định dạng yyyymmddHis (NămThángNgàyGiờPhútGiây)
        return now()->setTimezone('Asia/Ho_Chi_Minh')->format('YmdHis');
    }
}
