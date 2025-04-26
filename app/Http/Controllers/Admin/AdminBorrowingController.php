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

     // Admin memproses pengajuan (setujui atau tolak)
    // public function processBorrowing(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'status' => 'required|in:approved,rejected',
    //     ]);

    //     $borrowing = Borrowing::findOrFail($id);
    //     $borrowing->status = $validated['status'];
    //     $borrowing->save();

    //     Log::create([
    //         'user_id' => auth()->user()->id,
    //         'action' => 'update',
    //         'model' => 'Borrowing',
    //         'details' => 'Update status peminjaman with id ' . $borrowing->id
    //     ]);

    //     return response()->json([
    //         'message' => 'Status peminjaman diperbarui.',
    //         'data' => $borrowing
    //     ]);
    // }

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
        Borrowing::destroy($id);
        return response()->json(['message' => 'Data peminjaman dihapus.']);
    }

}
