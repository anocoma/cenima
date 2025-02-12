<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function getCinemas($branchId)
    {
        $cinemas = Cinema::where('branch_id', $branchId)->get();
        return response()->json($cinemas);
    }
}
