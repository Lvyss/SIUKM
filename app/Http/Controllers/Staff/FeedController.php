<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\Ukm;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
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
        
        $feeds = Feed::whereIn('ukm_id', $managedUkms->pluck('id'))
            ->with('ukm')
            ->latest()
            ->get();
            
        return view('staff.feeds.index', compact('feeds', 'managedUkms'));
    }

    public function create()
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        return view('staff.feeds.create', compact('managedUkms'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        // Cek apakah UKM yang dipilih termasuk yang di-manage
        if (!$managedUkms->contains($request->ukm_id)) {
            abort(403, 'Anda tidak memiliki akses ke UKM ini.');
        }

        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $imageUrl = null;
                
                if ($request->hasFile('image')) {
                    $imageUrl = $this->cloudinary->upload($request->file('image'), 'feed-images');
                }

                Feed::create([
                    'ukm_id' => $request->ukm_id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'image' => $imageUrl,
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->route('staff.feeds.index')->with('success', 'Feed berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan feed: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $user = auth()->user();
        $feed = Feed::with('ukm')->findOrFail($id);
        
        // Cek apakah staff manage UKM feed ini
        if (!$user->managedUkmsList->contains($feed->ukm_id)) {
            abort(403, 'Anda tidak memiliki akses ke feed ini.');
        }
        
        $managedUkms = $user->managedUkmsList;
        
        return view('staff.feeds.edit', compact('feed', 'managedUkms'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $feed = Feed::findOrFail($id);
        
        // Cek apakah staff manage UKM feed ini
        if (!$user->managedUkmsList->contains($feed->ukm_id)) {
            abort(403, 'Anda tidak memiliki akses ke feed ini.');
        }

        // Cek apakah UKM yang baru juga di-manage
        if (!$user->managedUkmsList->contains($request->ukm_id)) {
            abort(403, 'Anda tidak memiliki akses ke UKM ini.');
        }

        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $feed) {
                $oldImageUrl = $feed->image;
                $imageUrl = $oldImageUrl;
                
                if ($request->hasFile('image')) {
                    // Upload new image
                    $imageUrl = $this->cloudinary->upload($request->file('image'), 'feed-images');
                    
                    // Delete old image if exists
                    if ($oldImageUrl) {
                        $this->cloudinary->delete($oldImageUrl);
                    }
                }

                $feed->update([
                    'ukm_id' => $request->ukm_id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'image' => $imageUrl,
                ]);
            });

            return redirect()->route('staff.feeds.index')->with('success', 'Feed berhasil diupdate!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate feed: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $feed = Feed::findOrFail($id);
        
        // Cek apakah staff manage UKM feed ini
        if (!$user->managedUkmsList->contains($feed->ukm_id)) {
            abort(403, 'Anda tidak memiliki akses ke feed ini.');
        }

        try {
            DB::transaction(function () use ($feed) {
                // Delete image if exists
                if ($feed->image) {
                    $this->cloudinary->delete($feed->image);
                }

                $feed->delete();
            });

            return redirect()->route('staff.feeds.index')->with('success', 'Feed berhasil dihapus!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus feed: ' . $e->getMessage());
        }
    }
}