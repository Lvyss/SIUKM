<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\UkmCategory;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UkmController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new CloudinaryService();
    }

    public function index(Request $request)
    {
        try {
            $query = Ukm::with(['category', 'creator', 'staff'])
                ->withCount(['staff', 'events', 'feeds']);

            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('instagram', 'like', "%{$search}%")
                        ->orWhere('email_ukm', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            }

            // Filter by category
            if ($request->has('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by staff count
            if ($request->has('staff_filter') && $request->staff_filter != '') {
                switch ($request->staff_filter) {
                    case 'with_staff':
                        $query->has('staff');
                        break;
                    case 'without_staff':
                        $query->doesntHave('staff');
                        break;
                    case 'multiple_staff':
                        $query->has('staff', '>=', 2);
                        break;
                }
            }

            // Sorting
            switch ($request->get('sort', 'newest')) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'category_asc':
                    $query->join('ukm_categories', 'ukms.category_id', '=', 'ukm_categories.id')
                        ->orderBy('ukm_categories.name', 'asc')
                        ->select('ukms.*');
                    break;
                case 'staff_count_desc':
                    $query->orderBy('staff_count', 'desc');
                    break;
                default:
                    $query->latest();
            }

            // Statistics
            $totalUkms = Ukm::count();
            $activeUkms = Ukm::where('status', 'active')->count();
            $inactiveUkms = Ukm::where('status', 'inactive')->count();
            $ukmsWithStaff = Ukm::has('staff')->count();

            $perPage = $request->get('per_page', 10);
            $ukms = $query->paginate($perPage);

            $categories = UkmCategory::all();

            return view('admin.ukms.index', compact(
                'ukms',
                'categories',
                'totalUkms',
                'activeUkms',
                'inactiveUkms',
                'ukmsWithStaff'
            ));
        } catch (\Exception $e) {
            Log::error('Ukm index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load UKM: ' . $e->getMessage());
        }
    }

    public function create()
    {
        // Method ini biasanya untuk menampilkan form create
        // Tapi karena kita pakai modal, kita skip dan langsung ke store
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ukms,name',
            'description' => 'required|string|min:10|max:1000',
            'category_id' => 'required|exists:ukm_categories,id',
            'vision' => 'nullable|string|max:500',
            'mission' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Nama UKM wajib diisi',
            'name.unique' => 'Nama UKM sudah ada',
            'name.max' => 'Nama UKM maksimal 255 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 10 karakter',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'vision.max' => 'Visi maksimal 500 karakter',
            'mission.max' => 'Misi maksimal 500 karakter',
            'contact_person.max' => 'Contact person maksimal 100 karakter',
            'instagram.max' => 'Instagram maksimal 100 karakter',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'logo.max' => 'Ukuran logo maksimal 2MB',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $logoUrl = null;

                if ($request->hasFile('logo')) {
                    $logoUrl = $this->cloudinary->upload($request->file('logo'), 'ukm-logos');
                }

                Ukm::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'category_id' => $request->category_id,
                    'vision' => $request->vision,
                    'mission' => $request->mission,
                    'contact_person' => $request->contact_person,
                    'instagram' => $request->instagram,
                    'logo' => $logoUrl,
                    'status' => $request->status,
                    'created_by' => auth()->id(), // Jika ada kolom created_by
                ]);
            });

            return redirect()->route('admin.ukms.index')->with('success', 'UKM berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Ukm store error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat UKM: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ukms,name,' . $id,
            'description' => 'required|string|min:10|max:1000',
            'category_id' => 'required|exists:ukm_categories,id',
            'vision' => 'nullable|string|max:500',
            'mission' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Nama UKM wajib diisi',
            'name.unique' => 'Nama UKM sudah ada',
            'name.max' => 'Nama UKM maksimal 255 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 10 karakter',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'vision.max' => 'Visi maksimal 500 karakter',
            'mission.max' => 'Misi maksimal 500 karakter',
            'contact_person.max' => 'Contact person maksimal 100 karakter',
            'instagram.max' => 'Instagram maksimal 100 karakter',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'logo.max' => 'Ukuran logo maksimal 2MB',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus active atau inactive',
        ]);

        try {
            $ukm = Ukm::findOrFail($id);

            DB::transaction(function () use ($request, $ukm) {
                $oldLogoUrl = $ukm->logo;
                $logoUrl = $oldLogoUrl;

                if ($request->hasFile('logo')) {
                    // Upload new logo
                    $logoUrl = $this->cloudinary->upload($request->file('logo'), 'ukm-logos');

                    // Delete old logo if exists
                    if ($oldLogoUrl) {
                        $this->cloudinary->delete($oldLogoUrl);
                    }
                }

                $ukm->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'category_id' => $request->category_id,
                    'vision' => $request->vision,
                    'mission' => $request->mission,
                    'contact_person' => $request->contact_person,
                    'instagram' => $request->instagram,
                    'logo' => $logoUrl,
                    'status' => $request->status,
                ]);
            });

            return redirect()->route('admin.ukms.index')->with('success', 'UKM berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Ukm update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate UKM: ' . $e->getMessage())
                ->with('edit_errors', true)
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $ukm = Ukm::findOrFail($id);

            DB::transaction(function () use ($ukm) {
                // Delete logo if exists
                if ($ukm->logo) {
                    $this->cloudinary->delete($ukm->logo);
                }

                $ukm->delete();
            });

            return redirect()->route('admin.ukms.index')->with('success', 'UKM berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Ukm destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus UKM: ' . $e->getMessage());
        }
    }

    // Additional methods for API or other functionality
    public function getUkmByCategory($categoryId)
    {
        try {
            $ukms = Ukm::where('category_id', $categoryId)
                ->where('status', 'active')
                ->get(['id', 'name']);

            return response()->json($ukms);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch UKM'], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $ukm = Ukm::findOrFail($id);
            $ukm->status = $ukm->status == 'active' ? 'inactive' : 'active';
            $ukm->save();

            $status = $ukm->status == 'active' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "UKM berhasil $status!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status UKM');
        }
    }
}
