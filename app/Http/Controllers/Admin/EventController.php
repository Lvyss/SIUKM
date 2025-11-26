<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ukm;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new CloudinaryService();
    }

    public function index()
    {
        $events = Event::with(['ukm', 'creator'])->latest()->get();
        $ukms = Ukm::where('status', 'active')->get();
        return view('admin.events.index', compact('events', 'ukms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'registration_link' => 'nullable|url',
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
            return redirect()->back()->with('error', 'Gagal menambahkan event: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'registration_link' => 'nullable|url',
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
            return redirect()->back()->with('error', 'Gagal mengupdate event: ' . $e->getMessage())->withInput();
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
            return redirect()->back()->with('error', 'Gagal menghapus event: ' . $e->getMessage());
        }
    }
}