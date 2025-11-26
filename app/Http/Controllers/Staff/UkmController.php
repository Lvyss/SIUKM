<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;

class UkmController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new CloudinaryService();
    }

    public function index()
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        return view('staff.ukms.index', compact('managedUkms'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $ukm = Ukm::findOrFail($id);
        
        // Cek apakah staff manage UKM ini
        if (!$user->managedUkmsList->contains($id)) {
            abort(403, 'Anda tidak memiliki akses ke UKM ini.');
        }
        
        return view('staff.ukms.edit', compact('ukm'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $ukm = Ukm::findOrFail($id);
        
        // Cek apakah staff manage UKM ini
        if (!$user->managedUkmsList->contains($id)) {
            abort(403, 'Anda tidak memiliki akses ke UKM ini.');
        }

        $request->validate([
            'description' => 'required|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'instagram' => 'nullable|string',
            'email_ukm' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                    'email_ukm' => $request->email_ukm,
                    'logo' => $logoUrl,
                ]);
            });

            return redirect()->route('staff.ukms.index')->with('success', 'UKM berhasil diupdate!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate UKM: ' . $e->getMessage())->withInput();
        }
    }
}