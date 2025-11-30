<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ukm;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new CloudinaryService();
    }

    public function index(Request $request)
    {
        try {
            $query = Event::with(['ukm', 'creator']);
            
            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhereHas('ukm', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }
            
            // Filter by UKM
            if ($request->has('ukm_id') && $request->ukm_id != '') {
                $query->where('ukm_id', $request->ukm_id);
            }
            
            // Filter by date range
            if ($request->has('date_filter') && $request->date_filter != '') {
                $today = now()->format('Y-m-d');
                
                switch ($request->date_filter) {
                    case 'today':
                        $query->whereDate('event_date', $today);
                        break;
                    case 'upcoming':
                        $query->whereDate('event_date', '>=', $today);
                        break;
                    case 'past':
                        $query->whereDate('event_date', '<', $today);
                        break;
                    case 'this_week':
                        $query->whereBetween('event_date', [
                            now()->startOfWeek()->format('Y-m-d'),
                            now()->endOfWeek()->format('Y-m-d')
                        ]);
                        break;
                    case 'this_month':
                        $query->whereBetween('event_date', [
                            now()->startOfMonth()->format('Y-m-d'),
                            now()->endOfMonth()->format('Y-m-d')
                        ]);
                        break;
                }
            }
            
            // Sorting
            switch ($request->get('sort', 'newest')) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'event_date_asc':
                    $query->orderBy('event_date', 'asc')->orderBy('event_time', 'asc');
                    break;
                case 'event_date_desc':
                    $query->orderBy('event_date', 'desc')->orderBy('event_time', 'desc');
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
            $events = $query->paginate($perPage);
            
            $ukms = Ukm::where('status', 'active')->get();
            
             // TAMBAHIN INI UNTUK STATISTICS:
    $totalEvents = Event::count();
    $upcomingEvents = Event::where('event_date', '>=', now())->count();
    $pastEvents = Event::where('event_date', '<', now())->count();
    $todayEvents = Event::whereDate('event_date', today())->count();
    $eventsWithPosters = Event::whereNotNull('poster_image')->count();

    return view('admin.events.index', compact(
        'events',
        'ukms',
        'totalEvents',      // TAMBAHIN INI
        'upcomingEvents',   // TAMBAHIN INI  
        'pastEvents',       // TAMBAHIN INI
        'todayEvents',      // TAMBAHIN INI
        'eventsWithPosters' // TAMBAHIN INI
    ));
            
        } catch (\Exception $e) {
            Log::error('Event index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load events: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:2000',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'location' => 'required|string|max:500',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'registration_link' => 'nullable|url|max:500',
        ], [
            'ukm_id.required' => 'UKM wajib dipilih',
            'ukm_id.exists' => 'UKM tidak valid',
            'title.required' => 'Judul event wajib diisi',
            'title.max' => 'Judul event maksimal 255 karakter',
            'description.required' => 'Deskripsi event wajib diisi',
            'description.min' => 'Deskripsi event minimal 10 karakter',
            'description.max' => 'Deskripsi event maksimal 2000 karakter',
            'event_date.required' => 'Tanggal event wajib diisi',
            'event_date.after_or_equal' => 'Tanggal event tidak boleh di masa lalu',
            'event_time.required' => 'Waktu event wajib diisi',
            'location.required' => 'Lokasi event wajib diisi',
            'location.max' => 'Lokasi maksimal 500 karakter',
            'poster_image.image' => 'File harus berupa gambar',
            'poster_image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'poster_image.max' => 'Ukuran poster maksimal 5MB',
            'registration_link.url' => 'Format URL tidak valid',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $posterUrl = null;
                
                if ($request->hasFile('poster_image')) {
                    $posterUrl = $this->cloudinary->upload($request->file('poster_image'), 'event-posters');
                }

                Event::create([
                    'ukm_id' => $request->ukm_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'event_date' => $request->event_date,
                    'event_time' => $request->event_time,
                    'location' => $request->location,
                    'poster_image' => $posterUrl,
                    'registration_link' => $request->registration_link,
                    'created_by' => auth()->id(),
                ]);
            });

            return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            Log::error('Event store failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan event: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:2000',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string|max:500',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'registration_link' => 'nullable|url|max:500',
        ], [
            'ukm_id.required' => 'UKM wajib dipilih',
            'ukm_id.exists' => 'UKM tidak valid',
            'title.required' => 'Judul event wajib diisi',
            'title.max' => 'Judul event maksimal 255 karakter',
            'description.required' => 'Deskripsi event wajib diisi',
            'description.min' => 'Deskripsi event minimal 10 karakter',
            'description.max' => 'Deskripsi event maksimal 2000 karakter',
            'event_date.required' => 'Tanggal event wajib diisi',
            'event_time.required' => 'Waktu event wajib diisi',
            'location.required' => 'Lokasi event wajib diisi',
            'location.max' => 'Lokasi maksimal 500 karakter',
            'poster_image.image' => 'File harus berupa gambar',
            'poster_image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'poster_image.max' => 'Ukuran poster maksimal 5MB',
            'registration_link.url' => 'Format URL tidak valid',
        ]);

        try {
            $event = Event::findOrFail($id);

            DB::transaction(function () use ($request, $event) {
                $oldPosterUrl = $event->poster_image;
                $posterUrl = $oldPosterUrl;
                
                if ($request->hasFile('poster_image')) {
                    // Upload new poster
                    $posterUrl = $this->cloudinary->upload($request->file('poster_image'), 'event-posters');
                    
                    // Delete old poster if exists
                    if ($oldPosterUrl) {
                        $this->cloudinary->delete($oldPosterUrl);
                    }
                }

                $event->update([
                    'ukm_id' => $request->ukm_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'event_date' => $request->event_date,
                    'event_time' => $request->event_time,
                    'location' => $request->location,
                    'poster_image' => $posterUrl,
                    'registration_link' => $request->registration_link,
                ]);
            });

            return redirect()->route('admin.events.index')->with('success', 'Event berhasil diupdate!');
            
        } catch (\Exception $e) {
            Log::error('Event update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate event: ' . $e->getMessage())
                ->with('edit_errors', true)
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);

            DB::transaction(function () use ($event) {
                // Delete poster if exists
                if ($event->poster_image) {
                    $this->cloudinary->delete($event->poster_image);
                }

                $event->delete();
            });

            return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
            
        } catch (\Exception $e) {
            Log::error('Event destroy failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus event: ' . $e->getMessage());
        }
    }
}