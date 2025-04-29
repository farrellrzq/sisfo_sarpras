<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    // Peminjam mengajukan peminjaman
    public function requestBorrowing(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date',
            'deleted_at' => 'nullable|date',
        ]);


        // if item status has dipinjam doesnt create a new borrowing
        $item = Item::findOrFail($request->item_id);
        if ($item->status === 'dipinjam') {
            return response()->json([
                'message' => 'Item sedang dipinjam.',
            ], 400);
        }

        $borrowing = Borrowing::create([
            'item_id' => $request->item_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'deleted_at' => null,
            'user_id' => auth()->user()->id,
            'status_borrow' => 'pending'
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

    public function rejectBorrowing($id)
    {
        $borrowing = Borrowing::with('item')->findOrFail($id);
        // Update status borrowing
        $borrowing->update([
            'status_borrow' => 'rejected',
        ]);

         // Catat log
         Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'update',
            'model' => 'Borrowing',
            'details' => 'Rejected borrowing ID: ' . $borrowing->id
        ]);

        return response()->json([
            'message' => 'Peminjaman ditolak. Barang tidak diizinkan.',
            'data' => $borrowing
        ]);

    }

    public function returnBorrowing(Request $request, $id)
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
