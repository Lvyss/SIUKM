<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ukm;
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
        try {
            $user = auth()->user();
            $managedUkms = $user->managedUkmsList;
            
            return view('staff.ukms.index', compact('managedUkms'));
            
        } catch (\Exception $e) {
            Log::error('Staff Ukm index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load UKM: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = auth()->user();
            $ukm = Ukm::with(['category', 'staff', 'events', 'feeds'])
                      ->withCount(['staff', 'events', 'feeds'])
                      ->findOrFail($id);
            
            // Cek apakah staff manage UKM ini
            if (!$user->managedUkmsList->contains($id)) {
                abort(403, 'Anda tidak memiliki akses ke UKM ini.');
            }
            
            return response()->json([
                'success' => true,
                'data' => $ukm
            ]);
            
        } catch (\Exception $e) {
            Log::error('Staff Ukm show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load UKM data'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $ukm = Ukm::findOrFail($id);
        
        // Cek apakah staff manage UKM ini
        if (!$user->managedUkmsList->contains($id)) {
            abort(403, 'Anda tidak memiliki akses ke UKM ini.');
        }

        // Validasi lengkap seperti di admin
        $request->validate([
            'description' => 'required|string|min:10|max:1000',
            'vision' => 'nullable|string|max:500',
            'mission' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 10 karakter',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'vision.max' => 'Visi maksimal 500 karakter',
            'mission.max' => 'Misi maksimal 500 karakter',
            'contact_person.max' => 'Contact person maksimal 100 karakter',
            'instagram.max' => 'Instagram maksimal 100 karakter',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        try {
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
                    'description' => $request->description,
                    'vision' => $request->vision,
                    'mission' => $request->mission,
                    'contact_person' => $request->contact_person,
                    'instagram' => $request->instagram,
                    'logo' => $logoUrl,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'UKM berhasil diupdate!',
                'data' => [
                    'logo' => $ukm->fresh()->logo,
                    'description' => $ukm->fresh()->description,
                    'vision' => $ukm->fresh()->vision,
                    'mission' => $ukm->fresh()->mission,
                    'contact_person' => $ukm->fresh()->contact_person,
                    'instagram' => $ukm->fresh()->instagram
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Staff Ukm update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate UKM: ' . $e->getMessage()
            ], 500);
        }
    }
}