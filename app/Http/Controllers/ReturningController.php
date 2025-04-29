<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Log;
use App\Models\Returning;
use Illuminate\Http\Request;

class ReturningController extends Controller
{
    public function index()
    {
        $returnings = Returning::all();
        return response()->json($returnings);
    }

}
