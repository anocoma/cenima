<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    public $fillable = [
        'user_id',
        'rank_id',
        'code',
        'points',
        'total_spent',
    ];

    const MIN_USE_POINT = 10000; //Số điểm tối thiểu khi sử dụng
    const CONVERSION_RATE = 1; // Tỷ lệ quy đổi VD CONVERSION_RATE = 2 thì 10.000 diểm = 20.000 VND;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pointHistories(){
        return $this->hasMany(PointHistory::class);
    }
    public function rank(){
        return $this->belongsTo(Rank::class);
    }

    public static function  codeMembership() {
        $codes = Membership::pluck('code')->toArray();
        do {
            $code = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        } while (in_array($code, $codes));
        return $code;
    }
}
