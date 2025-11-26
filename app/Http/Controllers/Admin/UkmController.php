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

    public function index()
    {
        $ukms = Ukm::with(['category', 'creator', 'staff'])->get();
        $categories = UkmCategory::all();
        return view('admin.ukms.index', compact('ukms', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:ukm_categories,id',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'instagram' => 'nullable|string',
            'email_ukm' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                    'email_ukm' => $request->email_ukm,
                    'logo' => $logoUrl,
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->route('admin.ukms.index')->with('success', 'UKM berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload logo: ' . $e->getMessage())->withInput();
        }
    }

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:ukm_categories,id',
        'vision' => 'nullable|string',
        'mission' => 'nullable|string',
        'contact_person' => 'nullable|string',
        'instagram' => 'nullable|string',
        'email_ukm' => 'nullable|email',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:active,inactive',
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
                'email_ukm' => $request->email_ukm,
                'logo' => $logoUrl,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.ukms.index')->with('success', 'UKM berhasil diupdate!');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengupdate UKM: ' . $e->getMessage())->withInput();
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
        return redirect()->back()->with('error', 'Gagal menghapus UKM: ' . $e->getMessage());
    }
}
}