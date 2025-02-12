<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\TypeRoom;
use App\Models\TypeSeat;

class TicketPriceController extends Controller
{
    public function index(Request $request)
    {
        $typeRooms = TypeRoom::where('surcharge', '>' ,0)->get();
        
        $typeSeats = TypeSeat::all();
        
        $cinemas = Cinema::where('id', session('cinema_id'))->firstOrFail();

        return view('client.ticket-price', compact('typeRooms','typeSeats','cinemas'));
    }
}