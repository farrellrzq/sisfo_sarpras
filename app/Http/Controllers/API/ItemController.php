<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Log;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'string',
            'price' => 'integer',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|string',
            'status' => 'required|in:tersedia, dipinjam',
            'deleted_at' => 'nullable|date',
        ]);

        $item = Item::create([
            'name' => $request->name,
            'code' => $request->code,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'condition' => $request->condition,
            'status' => $request->status,
            'deleted_at' => $request->deleted_at
        ]);

        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'create',
            'model' => 'Item',
            'details' => 'Create item with id ' . $item->id
        ]);

        return response()->json([
            'message' => 'Item berhasil ditambahkan',
            'item' => $item
        ], 201);
    }

    public function show(Item $item)
    {
        $item = Item::findOrFail($item->id);
        return response()->json($item);
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'string',
            'code' => 'string',
            'price' => 'integer',
            'category_id' => 'exists:categories,id',
            'condition' => 'string',
            'status' => 'in:tersedia, dipinjam',
            'deleted_at' => 'date',
        ]);

        $item->update([
            'name' => $request->name,
            'code' => $request->code,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'deleted_at' => $request->deleted_at
        ]);

        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'update',
            'model' => 'Item',
            'details' => 'Update item with id ' . $item->id
        ]);

        return response()->json([
            'message' => 'Item berhasil diperbarui',
            'item' => $item
        ], 200);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'delete',
            'model' => 'Item',
            'details' => 'Delete item with id ' . $item->id
        ]);

        return response()->json([
            'message' => 'Item berhasil dihapus'
        ], 200);
    }
}
