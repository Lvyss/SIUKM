<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Event;
use App\Models\Feed;
use App\Models\Registration;
use App\Models\UkmCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $registrations = $user->registrations()
            ->with('ukm')
            ->latest()
            ->get();

        $trendingEvents = Event::with('ukm')
            ->where('event_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $trendingFeeds = Feed::with('ukm')
            ->latest()
            ->take(4)
            ->get();

        $upcomingEvents = Event::where('event_date', '>=', now())->get();
        $recentFeeds = Feed::latest()->take(6)->get();
        $totalUkm = Ukm::where('status', 'active')->count();

        $ukms = Ukm::with('category')
            ->where('status', 'active')
            ->withCount(['registrations as members_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->orderBy('members_count', 'desc')
            ->take(3)
            ->get();

        $categories = UkmCategory::all();

        return view('user.dashboard', compact(
            'user',
            'registrations',
            'trendingEvents',
            'trendingFeeds',
            'upcomingEvents',
            'recentFeeds',
            'totalUkm',
            'ukms',
            'categories'
        ));
    }

    public function ukmList(Request $request)
    {
        $query = Ukm::with('category')->where('status', 'active');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $ukms = $query->get();
        $categories = UkmCategory::all();

        return view('user.ukm.list', compact('ukms', 'categories'));
    }

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

        $recentEvents = $ukm->events()
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(3)
            ->get();

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

public function registerUkm(Request $request, $id)
{
    Log::info('Register UKM Attempt:', [
        'user_id' => auth()->id(),
        'ukm_id' => $id,
        'data' => $request->all()
    ]);
    
    $user = auth()->user();

    $validator = Validator::make($request->all(), [
        'motivation' => 'required|string|min:20|max:1000',
        'experience' => 'nullable|string|max:500',
        'skills' => 'nullable|string|max:500',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed:', $validator->errors()->toArray());
        
        // Return JSON response untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        return back()->withErrors($validator)->withInput();
    }

    $currentRegistrations = Registration::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'approved'])
        ->count();

    if ($currentRegistrations >= 3) {
        $message = 'Anda sudah mencapai batas maksimal pendaftaran UKM (3 UKM).';
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 422);
        }
        
        return back()->with('error', $message);
    }

    $existingRegistration = Registration::where('user_id', $user->id)
        ->where('ukm_id', $id)
        ->first();

    if ($existingRegistration) {
        $message = 'Anda sudah mendaftar UKM ini sebelumnya.';
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 422);
        }
        
        return back()->with('error', $message);
    }

    $ukm = Ukm::where('status', 'active')->find($id);
    if (!$ukm) {
        $message = 'UKM tidak ditemukan atau tidak aktif.';
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }
        
        return back()->with('error', $message);
    }

    Registration::create([
        'user_id' => $user->id,
        'ukm_id' => $id,
        'motivation' => $request->motivation,
        'experience' => $request->experience,
        'skills' => $request->skills,
        'status' => 'pending',
    ]);

    Log::info('Registration created successfully');
    
    $successMessage = 'Pendaftaran berhasil! Tunggu konfirmasi dari admin/staff UKM.';
    
    // Return JSON response untuk AJAX
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => $successMessage
        ]);
    }
    
    return back()->with('success', $successMessage);
}
    public function events(Request $request)
    {
        $query = Event::with('ukm')->where('event_date', '>=', now());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('ukm')) {
            $query->where('ukm_id', $request->ukm);
        }

        $events = $query->orderBy('event_date')->get();

        $pastEvents = Event::with('ukm')
            ->where('event_date', '<', now())
            ->orderBy('event_date', 'desc')
            ->take(10)
            ->get();

        $ukms = Ukm::where('status', 'active')->get();

        return view('user.events.index', compact('events', 'pastEvents', 'ukms'));
    }

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

    public function feeds(Request $request)
    {
        $query = Feed::with('ukm');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('ukm')) {
            $query->where('ukm_id', $request->ukm);
        }

        $feeds = $query->latest()->get();
        $ukms = Ukm::where('status', 'active')->get();

        return view('user.feeds.index', compact('feeds', 'ukms'));
    }

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

    public function profile()
    {
        $user = auth()->user();
        $registrations = $user->registrations()
            ->with(['ukm', 'approver'])
            ->get();

        return view('user.profile', compact('user', 'registrations'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'phone' => 'required|string|max:15|min:11',
            'fakultas' => 'required|string|max:255|min:2',
            'jurusan' => 'required|string|max:255|min:2',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only('name', 'email', 'phone', 'fakultas', 'jurusan', 'angkatan'));

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    public function cancelRegistration($registrationId)
    {
        $user = auth()->user();

        $registration = Registration::where('id', $registrationId)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $registration->delete();

        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }
}