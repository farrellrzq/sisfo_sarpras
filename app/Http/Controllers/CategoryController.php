<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Log;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function create()
    {
        return response()->json([
            'message' => 'Kategori berhasil ditambahkan'
        ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'create',
            'model' => 'Category',
            'details' => 'Create category with name ' . $category->name
        ]);
        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'category' => $category
        ], 200);
    }


    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui'
        ],200);
    }


    public function destroy(Category $category)
    {
        $category->delete();

        Log::create([
            'user_id' => auth()->user()->id,
            'action' => 'delete',
            'model' => 'Category',
            'details' => 'Delete category with name ' . $category->name
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ], 200);
    }

}
