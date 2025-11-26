<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UkmCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = UkmCategory::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ukm_categories,name',
            'description' => 'nullable|string',
        ]);

        UkmCategory::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ukm_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category = UkmCategory::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

public function destroy($id)
{
    $category = UkmCategory::findOrFail($id);
    
    // Pakai withCount biar lebih efisien
    if ($category->ukms()->count() > 0) {
        return redirect()->route('admin.categories.index')->with('error', 'Tidak bisa menghapus kategori yang sedang digunakan!');
    }

    $category->delete();

    return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
}
}