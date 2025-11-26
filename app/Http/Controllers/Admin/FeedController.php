<?php

namespace App\Http\Controllers\Admin;

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

    public function index()
    {
        $feeds = Feed::with(['ukm', 'creator'])->latest()->get();
        $ukms = Ukm::where('status', 'active')->get();
        return view('admin.feeds.index', compact('feeds', 'ukms'));
    }

public function store(Request $request)
{
    $request->validate([
        'ukm_id' => 'required|exists:ukms,id',
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        Log::info('Feed store request received', [
            'title' => $request->title,
            'has_image' => $request->hasFile('image'),
            'file_size' => $request->hasFile('image') ? $request->file('image')->getSize() : 0
        ]);

        $imageUrl = null;
        
        if ($request->hasFile('image')) {
            Log::info('Processing image upload', [
                'file_name' => $request->file('image')->getClientOriginalName(),
                'file_extension' => $request->file('image')->getClientOriginalExtension(),
                'file_mime' => $request->file('image')->getMimeType()
            ]);
            
            // Test file readability
            $filePath = $request->file('image')->getRealPath();
            Log::info('File info', ['file_path' => $filePath, 'readable' => is_readable($filePath)]);
            
            $imageUrl = $this->cloudinary->upload($request->file('image'), 'feed-images');
            
            if (!$imageUrl) {
                Log::warning('Cloudinary upload returned null or false');
                throw new \Exception('Cloudinary upload failed - returned null');
            }
            
            Log::info('Image upload completed', ['url' => $imageUrl]);
        }

        $feed = Feed::create([
            'ukm_id' => $request->ukm_id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imageUrl,
            'created_by' => auth()->id(),
        ]);

        Log::info('Feed created successfully', [
            'feed_id' => $feed->id,
            'image_url' => $imageUrl
        ]);

        return redirect()->route('admin.feeds.index')->with('success', 'Feed berhasil ditambahkan!');
        
    } catch (\Exception $e) {
        Log::error('Feed store failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->except(['_token', 'image'])
        ]);
        
        return redirect()->back()
            ->with('error', 'Gagal menambahkan feed: ' . $e->getMessage())
            ->withInput();
    }
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $feed = Feed::findOrFail($id);

            DB::transaction(function () use ($request, $feed) {
                $oldImageUrl = $feed->image;
                $imageUrl = $oldImageUrl;
                
                if ($request->hasFile('image')) {
                    Log::info('Starting image upload for feed update', [
                        'feed_id' => $feed->id,
                        'file_name' => $request->file('image')->getClientOriginalName()
                    ]);
                    
                    // Upload new image
                    $imageUrl = $this->cloudinary->upload($request->file('image'), 'feed-images');
                    
                    Log::info('New image uploaded', ['url' => $imageUrl]);
                    
                    // Delete old image if exists
                    if ($oldImageUrl) {
                        Log::info('Deleting old image', ['old_url' => $oldImageUrl]);
                        $deleteSuccess = $this->cloudinary->delete($oldImageUrl);
                        Log::info('Old image deletion result', ['success' => $deleteSuccess]);
                    }
                }

                $feed->update([
                    'ukm_id' => $request->ukm_id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'image' => $imageUrl,
                ]);

                Log::info('Feed updated successfully', [
                    'feed_id' => $feed->id,
                    'has_new_image' => $request->hasFile('image')
                ]);
            });

            return redirect()->route('admin.feeds.index')->with('success', 'Feed berhasil diupdate!');
            
        } catch (\Exception $e) {
            Log::error('Feed update failed', [
                'error' => $e->getMessage(),
                'feed_id' => $id,
                'request' => $request->all()
            ]);
            
            return redirect()->back()->with('error', 'Gagal mengupdate feed: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $feed = Feed::findOrFail($id);

            DB::transaction(function () use ($feed) {
                // Delete image if exists
                if ($feed->image) {
                    Log::info('Deleting feed image on destroy', [
                        'feed_id' => $feed->id,
                        'image_url' => $feed->image
                    ]);
                    
                    $deleteSuccess = $this->cloudinary->delete($feed->image);
                    Log::info('Feed image deletion result', ['success' => $deleteSuccess]);
                }

                $feed->delete();
                
                Log::info('Feed deleted successfully', ['feed_id' => $feed->id]);
            });

            return redirect()->route('admin.feeds.index')->with('success', 'Feed berhasil dihapus!');
            
        } catch (\Exception $e) {
            Log::error('Feed destroy failed', [
                'error' => $e->getMessage(),
                'feed_id' => $id
            ]);
            
            return redirect()->back()->with('error', 'Gagal menghapus feed: ' . $e->getMessage());
        }
    }
}