<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCombo extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'combo_id',
        'price',
        'quantity'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }
}
