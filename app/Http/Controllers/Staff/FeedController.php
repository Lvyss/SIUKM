<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feed;
use App\Models\Ukm;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new CloudinaryService();
    }

    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $managedUkms = $user->managedUkmsList;
            $managedUkmIds = $managedUkms->pluck('id');
            
            $query = Feed::whereIn('ukm_id', $managedUkmIds)
                        ->with(['ukm', 'creator'])
                        ->latest();
            
            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhereHas('ukm', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }
            
            // Filter by UKM
            if ($request->has('ukm_id') && $request->ukm_id != '') {
                $query->where('ukm_id', $request->ukm_id);
            }
            
            // Filter by date
            if ($request->has('date_filter') && $request->date_filter != '') {
                switch ($request->date_filter) {
                    case 'today':
                        $query->whereDate('created_at', today());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [
                            now()->startOfWeek(),
                            now()->endOfWeek()
                        ]);
                        break;
                    case 'month':
                        $query->whereBetween('created_at', [
                            now()->startOfMonth(),
                            now()->endOfMonth()
                        ]);
                        break;
                }
            }
            
            // Statistics - hanya untuk UKM yang di-manage
            $totalFeeds = Feed::whereIn('ukm_id', $managedUkmIds)->count();
            $feedsWithImages = Feed::whereIn('ukm_id', $managedUkmIds)
                                ->whereNotNull('image')
                                ->count();
            $todayFeeds = Feed::whereIn('ukm_id', $managedUkmIds)
                            ->whereDate('created_at', today())
                            ->count();

            $perPage = $request->get('per_page', 10);
            $feeds = $query->paginate($perPage);
            
            return view('staff.feeds.index', compact(
                'feeds',
                'managedUkms', 
                'totalFeeds',
                'feedsWithImages',
                'todayFeeds'
            ));
            
        } catch (\Exception $e) {
            Log::error('Staff Feed index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load feeds: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        // Cek apakah UKM yang dipilih termasuk yang di-manage
        if (!$managedUkms->contains($request->ukm_id)) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses ke UKM ini.')
                ->withInput();
        }

        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'ukm_id.required' => 'UKM wajib dipilih',
            'ukm_id.exists' => 'UKM tidak valid',
            'title.required' => 'Judul feed wajib diisi',
            'title.max' => 'Judul feed maksimal 255 karakter',
            'content.required' => 'Konten feed wajib diisi',
            'content.min' => 'Konten minimal 10 karakter',
            'content.max' => 'Konten maksimal 2000 karakter',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'image.max' => 'Ukuran gambar maksimal 2MB',
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

            return redirect()->route('staff.feeds.index')->with('success', 'Feed berhasil dibuat!');
            
        } catch (\Exception $e) {
            Log::error('Staff Feed store error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat feed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $feed = Feed::findOrFail($id);
        
        // Cek apakah staff manage UKM feed ini
        if (!$user->managedUkmsList->contains($feed->ukm_id)) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses ke feed ini.')
                ->withInput();
        }

        // Cek apakah UKM yang baru juga di-manage
        if (!$user->managedUkmsList->contains($request->ukm_id)) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses ke UKM ini.')
                ->withInput();
        }

        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'ukm_id.required' => 'UKM wajib dipilih',
            'ukm_id.exists' => 'UKM tidak valid',
            'title.required' => 'Judul feed wajib diisi',
            'title.max' => 'Judul feed maksimal 255 karakter',
            'content.required' => 'Konten feed wajib diisi',
            'content.min' => 'Konten minimal 10 karakter',
            'content.max' => 'Konten maksimal 2000 karakter',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'image.max' => 'Ukuran gambar maksimal 2MB',
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
            Log::error('Staff Feed update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate feed: ' . $e->getMessage())
                ->with('edit_errors', true)
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $feed = Feed::findOrFail($id);
        
        // Cek apakah staff manage UKM feed ini
        if (!$user->managedUkmsList->contains($feed->ukm_id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke feed ini.');
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
            Log::error('Staff Feed destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus feed: ' . $e->getMessage());
        }
    }
}