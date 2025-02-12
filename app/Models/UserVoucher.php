<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voucher_id',
        'usage_count',
    ];

    /**
     * Get the user that owns the voucher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the voucher associated with the user.
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
