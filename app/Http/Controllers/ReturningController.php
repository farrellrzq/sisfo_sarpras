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


    public function returnItem(Request $request, $id)
    {
        $borrowing = Borrowing::with('item')->findOrFail($id);

        if ($borrowing->status_borrow !== 'returned') {
            $borrowing->status_borrow = 'returned';
            $borrowing->return_date = now();
            $borrowing->save();

            $borrowing->item->update([
                'status' => 'tersedia'
            ]);
            
            Log::create([
                'user_id' => auth()->user()->id,
                'action' => 'update',
                'model' => 'Borrowing',
                'details' => 'Returned borrowing ID: ' . $borrowing->id,
            ]);

            return response()->json([
                'message' => 'Item successfully returned.',
                'data' => $borrowing
            ]);
        }

        return response()->json([
            'message' => 'Item was already returned.',
        ], 400);
    }
}
