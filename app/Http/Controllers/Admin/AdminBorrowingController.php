<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Log;
use Illuminate\Http\Request;

class AdminBorrowingController extends Controller
{
    // Admin melihat semua data peminjaman
    public function allBorrowings()
    {
        $borrowings = Borrowing::with('user', 'item')->latest()->get();
        return response()->json($borrowings);
    }

    // Admin menyetujui peminjaman
    public function approveBorrowing(Request $request, $id)
    {
        // Cari data borrowing
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->status_borrow = 'approved';
        $borrowing->save();

        // Update status item jadi dipinjamkan
        $item = $borrowing->item;
        $item->status = 'dipinjam';
        $item->save();

        // Log aktivitas approve
        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'update',
            'model' => 'Borrowing',
            'details' => 'Approved borrowing ID: ' . $borrowing->id,
        ]);

        return response()->json([
            'message' => 'Peminjaman disetujui dan item sudah dipinjamkan.',
            'data' => $borrowing
        ]);
    }

    //Admin menolak peminjaman
    public function rejectBorrowing($id){

        //Cari data barang
        $borrowing = Borrowing::findOrFail($id);

        //Mengubah status menjadi ditolak
        $borrowing->status_borrow = 'rejected';
        $borrowing->save();

        // Log aktivitas reject
        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'update',
            'model' => 'Borrowing',
            'details' => 'Rejected borrowing ID: ' . $borrowing->id,
        ]);
    }

    // Admin menandai peminjaman telah dikembalikan
    public function markAsReturned($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->status = 'returned';
        $borrowing->return_date = now();
        $borrowing->save();

        return response()->json([
            'message' => 'Peminjaman ditandai sudah dikembalikan.',
            'data' => $borrowing
        ]);
    }

    // Admin menghapus data peminjaman
    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->delete();

        return response()->json(['message' => 'Data peminjaman dihapus.']);
    }
}
