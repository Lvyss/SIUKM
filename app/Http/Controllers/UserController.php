<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Event;
use App\Models\Feed;
use App\Models\Registration;
use App\Models\UkmCategory;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class UserController extends Controller
{
    // Dashboard User - UPDATE VERSION
    public function dashboard()
    {
        $user = auth()->user();
        $registrations = $user->registrations()->with('ukm')->latest()->get();
        
        // Trending Events (untuk slider) - ambil lebih banyak
        $trendingEvents = Event::with('ukm')
            ->where('event_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(8) // Ambil lebih banyak buat slider
            ->get();

        // Trending Feeds (untuk grid 2 kolom)
        $trendingFeeds = Feed::with('ukm')
            ->latest()
            ->take(4) // Ambil 4 untuk grid 2x2
            ->get();

        // Upcoming Events (untuk statistik)
        $upcomingEvents = Event::where('event_date', '>=', now())->get();
        
        // Recent Feeds (untuk statistik)
        $recentFeeds = Feed::latest()->take(6)->get();
        
        // Total UKM Active
        $totalUkm = Ukm::where('status', 'active')->count();
        
        return view('user.dashboard', compact(
            'user', 
            'registrations', 
            'trendingEvents',
            'trendingFeeds',
            'upcomingEvents',
            'recentFeeds',
            'totalUkm'
        ));
    }

    // List UKM untuk User - TAMBAHIN SEARCH & FILTER
    public function ukmList(Request $request)
    {
        $query = Ukm::with('category')->where('status', 'active');
        
        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        $ukms = $query->get();
        $categories = UkmCategory::all();
        
        return view('user.ukm.list', compact('ukms', 'categories'));
    }

    // Detail UKM - TAMBAHIN EVENT & FEED TERBARU
    public function ukmDetail($id)
    {
        $ukm = Ukm::with(['category', 'events', 'feeds'])->findOrFail($id);
        $user = auth()->user();
        
        $isRegistered = Registration::where('user_id', $user->id)
            ->where('ukm_id', $id)
            ->exists();
            
        $similarUkms = Ukm::where('category_id', $ukm->category_id)
            ->where('id', '!=', $id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        // Ambil events terbaru UKM ini (3 terbaru)
        $recentEvents = $ukm->events()
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(3)
            ->get();

        // Ambil feeds terbaru UKM ini (3 terbaru)
        $recentFeeds = $ukm->feeds()
            ->latest()
            ->take(3)
            ->get();
            
        return view('user.ukm.detail', compact(
            'ukm', 
            'isRegistered', 
            'similarUkms',
            'recentEvents',
            'recentFeeds'
        ));
    }

    // Daftar UKM - TAMBAHIN VALIDASI MAX 3 UKM
public function registerUkm(Request $request, $ukmId)
{
    $user = auth()->user();
    
    // DEBUG 1: Cek user dan request
    \Log::info('=== REGISTER UKM DEBUG START ===');
    \Log::info('User ID: ' . $user->id);
    \Log::info('UKM ID: ' . $ukmId);
    \Log::info('Request Data:', $request->all());
    \Log::info('Request Headers:', $request->headers->all());

    // Validasi maksimal 3 UKM
    $currentRegistrations = Registration::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    \Log::info('Current Registrations Count: ' . $currentRegistrations);

    if ($currentRegistrations >= 3) {
        \Log::warning('Max registration limit reached for user: ' . $user->id);
        return back()->with('error', 'Anda sudah mencapai batas maksimal pendaftaran UKM (3 UKM).');
    }
    
    // Cek apakah sudah mendaftar UKM ini
    $existingRegistration = Registration::where('user_id', $user->id)
        ->where('ukm_id', $ukmId)
        ->first();

    \Log::info('Existing Registration Check:', [
        'exists' => !is_null($existingRegistration),
        'registration' => $existingRegistration
    ]);

    if ($existingRegistration) {
        \Log::warning('User already registered for this UKM', [
            'user_id' => $user->id,
            'ukm_id' => $ukmId
        ]);
        return back()->with('error', 'Anda sudah mendaftar UKM ini.');
    }

    // DEBUG 2: Sebelum validasi
    Log::info('Before validation');

    $request->validate([
        'motivation' => 'required|string|min:10',
        'experience' => 'nullable|string',
        'skills' => 'nullable|string',
    ]);

    Log::info('Validation passed');

    try {
        // DEBUG 3: Sebelum create
        Log::info('Attempting to create registration', [
            'user_id' => $user->id,
            'ukm_id' => $ukmId,
            'motivation_length' => strlen($request->motivation),
            'experience_length' => strlen($request->experience ?? ''),
            'skills_length' => strlen($request->skills ?? '')
        ]);

        $registration = Registration::create([
            'user_id' => $user->id,
            'ukm_id' => $ukmId,
            'motivation' => $request->motivation,
            'experience' => $request->experience,
            'skills' => $request->skills,
            'status' => 'pending', // Pastikan ada status
        ]);

        // DEBUG 4: Setelah create
        Log::info('Registration created successfully', [
            'registration_id' => $registration->id,
            'registration_data' => $registration->toArray()
        ]);

        // DEBUG 5: Verify data saved
        $savedRegistration = Registration::find($registration->id);
        Log::info('Verified saved registration:', [
            'exists' => !is_null($savedRegistration),
            'data' => $savedRegistration ? $savedRegistration->toArray() : null
        ]);

        Log::info('=== REGISTER UKM DEBUG END - SUCCESS ===');

        return back()->with('success', 'Pendaftaran berhasil! Tunggu konfirmasi dari admin/staff UKM.');

    } catch (\Exception $e) {
        Log::error('Registration creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => $user->id,
            'ukm_id' => $ukmId
        ]);

        return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
    }
}

    // Lihat Events - TAMBAHIN SEARCH & FILTER
    public function events(Request $request)
    {
        $query = Event::with('ukm')->where('event_date', '>=', now());
        
        // Search events
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
        
        // Filter by UKM
        if ($request->has('ukm') && $request->ukm != '') {
            $query->where('ukm_id', $request->ukm);
        }
        
        $events = $query->orderBy('event_date')->get();
        
        $pastEvents = Event::with('ukm')
            ->where('event_date', '<', now())
            ->orderBy('event_date', 'desc')
            ->take(10)
            ->get();
            
        $ukms = Ukm::where('status', 'active')->get(); // Untuk filter dropdown
            
        return view('user.events.index', compact('events', 'pastEvents', 'ukms'));
    }

    // Detail Event - SUDAH BAGUS
    public function eventDetail($id)
    {
        $event = Event::with('ukm')->findOrFail($id);
        $similarEvents = Event::where('ukm_id', $event->ukm_id)
            ->where('id', '!=', $id)
            ->where('event_date', '>=', now())
            ->take(3)
            ->get();
            
        return view('user.events.detail', compact('event', 'similarEvents'));
    }

    // Lihat Feeds - TAMBAHIN SEARCH & FILTER
    public function feeds(Request $request)
    {
        $query = Feed::with('ukm');
        
        // Search feeds
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('content', 'like', '%'.$request->search.'%');
        }
        
        // Filter by UKM
        if ($request->has('ukm') && $request->ukm != '') {
            $query->where('ukm_id', $request->ukm);
        }
        
        $feeds = $query->latest()->get();
        $ukms = Ukm::where('status', 'active')->get(); // Untuk filter dropdown
        
        return view('user.feeds.index', compact('feeds', 'ukms'));
    }

    // Profile User - SUDAH BAGUS
    public function profile()
    {
        $user = auth()->user();
        $registrations = $user->registrations()->with(['ukm', 'approver'])->get();
        
        return view('user.profile', compact('user', 'registrations'));
    }

    // Update Profile - TAMBAHIN VALIDASI EMAIL UNIK
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:15',
            'fakultas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'angkatan' => 'required|integer|min:2000|max:'.(date('Y')+1),
        ]);

        $user->update($request->only('name', 'email', 'phone', 'fakultas', 'jurusan', 'angkatan'));

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    // 🔥 NEW METHOD: Batalkan Pendaftaran UKM
    public function cancelRegistration($registrationId)
    {
        $user = auth()->user();
        
        $registration = Registration::where('id', $registrationId)
            ->where('user_id', $user->id)
            ->where('status', 'pending') // Hanya bisa batalkan yang masih pending
            ->firstOrFail();
            
        $registration->delete();
        
        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    // Tambahkan method ini di UserController
public function feedDetail($id)
{
    $feed = Feed::with('ukm')->findOrFail($id);
    $relatedFeeds = Feed::where('ukm_id', $feed->ukm_id)
        ->where('id', '!=', $id)
        ->latest()
        ->take(3)
        ->get();
        
    return view('user.feeds.detail', compact('feed', 'relatedFeeds'));
}
}