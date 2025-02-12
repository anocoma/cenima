<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'total_spent',
        'ticket_percentage',
        'combo_percentage',
    ];

    public $casts = [
        'is_default'=>'boolean'
    ];
    const MAX_RANK = 5;


}
