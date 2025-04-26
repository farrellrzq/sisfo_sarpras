<?php

namespace App\Http\Controllers;

use App\Models\Returning;
use Illuminate\Http\Request;

class ReturningController extends Controller
{
    public function index()
    {
        $returnings = Returning::all();
        return response()->json($returnings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'date' => 'required|date'
        ]);

        $returning = Returning::create([
            'borrowing_id' => $request->borrowing_id,
            'date' => $request->date
        ]);

        return response()->json($returning, 201);
    }
}
