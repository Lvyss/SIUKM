<?php

namespace App\Http\Controllers\Admin;

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

    public function index(Request $request)
    {
        $query = Feed::with(['ukm', 'creator']);

        // Search functionality
        if ($request->has('search') && $request->search) {
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
        if ($request->has('ukm_id') && $request->ukm_id) {
            $query->where('ukm_id', $request->ukm_id);
        }

        // Filter by date
        if ($request->has('date_filter') && $request->date_filter) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }

        // Sort functionality
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latest();
        }

        $perPage = $request->get('per_page', 10);
        $feeds = $query->paginate($perPage);
        $ukms = Ukm::where('status', 'active')->get();

        // Statistics
        $totalFeeds = Feed::count();
        $feedsWithImages = Feed::whereNotNull('image')->count();
        $todayFeeds = Feed::whereDate('created_at', today())->count();

        return view('admin.feeds.index', compact(
            'feeds',
            'ukms',
            'totalFeeds',
            'feedsWithImages',
            'todayFeeds'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'ukm_id.required' => 'Pilih UKM wajib diisi',
            'ukm_id.exists' => 'UKM yang dipilih tidak valid',
            'title.required' => 'Judul feed wajib diisi',
            'title.max' => 'Judul feed maksimal 255 karakter',
            'content.required' => 'Konten feed wajib diisi',
            'content.min' => 'Konten feed minimal 10 karakter',
            'content.max' => 'Konten feed maksimal 2000 karakter',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                $imageUrl = null;
                
                if ($request->hasFile('image')) {
                    $imageUrl = $this->cloudinary->upload(
                        $request->file('image'), 
                        'feed-images',
                        [
                            'width' => 800,
                            'height' => 600,
                            'crop' => 'fill'
                        ]
                    );
                }

                Feed::create([
                    'ukm_id' => $validated['ukm_id'],
                    'title' => $validated['title'],
                    'content' => $validated['content'],
                    'image' => $imageUrl,
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->route('admin.feeds.index')
                ->with('success', 'Feed berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            \Log::error('Feed creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan feed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $feed = Feed::findOrFail($id);

        $validated = $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'ukm_id.required' => 'Pilih UKM wajib diisi',
            'ukm_id.exists' => 'UKM yang dipilih tidak valid',
            'title.required' => 'Judul feed wajib diisi',
            'title.max' => 'Judul feed maksimal 255 karakter',
            'content.required' => 'Konten feed wajib diisi',
            'content.min' => 'Konten feed minimal 10 karakter',
            'content.max' => 'Konten feed maksimal 2000 karakter',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $feed) {
                $oldImageUrl = $feed->image;
                $imageUrl = $oldImageUrl;
                
                if ($request->hasFile('image')) {
                    // Upload new image
                    $imageUrl = $this->cloudinary->upload(
                        $request->file('image'), 
                        'feed-images',
                        [
                            'width' => 800,
                            'height' => 600,
                            'crop' => 'fill'
                        ]
                    );
                    
                    // Delete old image if exists
                    if ($oldImageUrl) {
                        $this->cloudinary->delete($oldImageUrl);
                    }
                }

                $feed->update([
                    'ukm_id' => $validated['ukm_id'],
                    'title' => $validated['title'],
                    'content' => $validated['content'],
                    'image' => $imageUrl,
                ]);
            });

            return redirect()->route('admin.feeds.index')
                ->with('success', 'Feed berhasil diupdate!');
            
        } catch (\Exception $e) {
            \Log::error('Feed update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengupdate feed: ' . $e->getMessage())
                ->withInput()
                ->with('edit_errors', true);
        }
    }

    public function destroy($id)
    {
        try {
            $feed = Feed::findOrFail($id);

            DB::transaction(function () use ($feed) {
                // Delete image if exists
                if ($feed->image) {
                    $this->cloudinary->delete($feed->image);
                }

                $feed->delete();
            });

            return redirect()->route('admin.feeds.index')
                ->with('success', 'Feed berhasil dihapus!');
            
        } catch (\Exception $e) {
            \Log::error('Feed deletion failed: ' . $e->getMessage());
            
            return redirect()->route('admin.feeds.index')
                ->with('error', 'Gagal menghapus feed: ' . $e->getMessage());
        }
    }
}