<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'slug',
        'surcharge',
        'address',
        'description',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    // public function ticketSeats()
    // {
    //     return $this->hasMany(TicketSeat::class);
    // }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
