<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UkmCategory;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = UkmCategory::withCount('ukms');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by UKM count
        if ($request->has('ukm_count') && $request->ukm_count) {
            switch ($request->ukm_count) {
                case 'empty':
                    $query->having('ukms_count', '=', 0);
                    break;
                case 'has_ukms':
                    $query->having('ukms_count', '>', 0);
                    break;
                case 'popular':
                    $query->having('ukms_count', '>=', 5);
                    break;
            }
        }

        // Sort functionality
        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'ukms_desc':
                $query->orderBy('ukms_count', 'desc');
                break;
            case 'ukms_asc':
                $query->orderBy('ukms_count', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $perPage = $request->get('per_page', 10);
        $categories = $query->paginate($perPage);

        // Statistics for dashboard
        $totalCategories = UkmCategory::count();
        $categoriesWithUkms = UkmCategory::has('ukms')->count();
        $emptyCategories = UkmCategory::doesntHave('ukms')->count();

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'categoriesWithUkms',
            'emptyCategories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ukm_categories,name',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Nama kategori sudah ada',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'description.max' => 'Deskripsi maksimal 500 karakter'
        ]);

        try {
            UkmCategory::create($request->all());
            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ukm_categories,name,' . $id,
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Nama kategori sudah ada',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'description.max' => 'Deskripsi maksimal 500 karakter'
        ]);

        try {
            $category = UkmCategory::findOrFail($id);
            $category->update($request->all());
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate kategori: ' . $e->getMessage())
                ->with('edit_errors', true)
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = UkmCategory::findOrFail($id);
            
            if ($category->ukms_count > 0) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Tidak bisa menghapus kategori yang memiliki UKM!');
            }

            $category->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}