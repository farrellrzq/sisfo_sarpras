<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    // Peminjam mengajukan peminjaman
    public function requestBorrowing(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date',
            'deleted_at' => 'nullable|date',
        ]);

        $borrowing = Borrowing::create([
            'item_id' => $request->item_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'deleted_at' => null,
            'user_id' => auth()->user()->id,
            'status' => 'pending'
        ]);

        // Log aktivitas
        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'create',
            'model' => 'Borrowing',
            'details' => 'Created borrowing with ID: ' . $borrowing->id
        ]);

        return response()->json([
            'message' => 'Peminjaman berhasil diajukan.',
            'data' => $borrowing
        ], 201);
    }

    // Peminjam melihat daftar peminjamannya sendiri
    public function historyBorrowings()
    {
        $borrowings = Borrowing::with('item')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Log aktivitas lihat histori
        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'read',
            'model' => 'Borrowing',
            'details' => 'Viewed own borrowing history.'
        ]);



        return response()->json($borrowings);
    }

    // Admin menyetujui peminjaman
public function approveBorrowing($id)
{
    $borrowing = Borrowing::with('item')->findOrFail($id);

    // Update status borrowing
    $borrowing->update([
        'status_borrow' => 'approved',
    ]);

    // Update status item
    $borrowing->item->update([
        'status' => 'dipinjam', // dari "tersedia" → "dipinjam"
    ]);

    // Catat log
    Log::create([
        'user_id' => auth()->user()->id,
        'action' => 'update',
        'model' => 'Borrowing',
        'details' => 'Approved borrowing ID: ' . $borrowing->id
    ]);

    return response()->json([
        'message' => 'Peminjaman disetujui. Barang telah dipinjam.',
        'data' => $borrowing
    ]);
}

}
