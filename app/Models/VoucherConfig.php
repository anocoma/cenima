<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'discount',
    ];

    // Lấy tiền discount của voucher sinh nhật theo key
    public static function getValue($name, $default = null)
    {
        $config = self::where('name', $name)->first();
        return $config ? $config->discount : $default;
    }

    // Cập nhật số tiền discount
    public static function updateValue($name, $discount)
    {
        $config = self::updateOrCreate(
            ['name' => $name],
            ['discount' => $discount]
        );
        return $config;
    }
}
