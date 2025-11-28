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
        try {
            $user = auth()->user();

            $registrations = $user->registrations()
                ->with('ukm')
                ->latest()
                ->get();

            // Trending Events
            $trendingEvents = Event::with('ukm')
                ->where('event_date', '>=', now())
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            // Trending Feeds
            $trendingFeeds = Feed::with('ukm')
                ->latest()
                ->take(4)
                ->get();

            // Upcoming Events untuk statistik
            $upcomingEvents = Event::where('event_date', '>=', now())->get();

            // Recent Feeds untuk statistik
            $recentFeeds = Feed::latest()->take(6)->get();

            // Total UKM Active
            $totalUkm = Ukm::where('status', 'active')->count();

            // UKM Terpopuler
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
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memuat dashboard.');
        }
    }

    public function ukmList(Request $request)
    {
        try {
            $query = Ukm::with('category')->where('status', 'active');

            // Search by name
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter by category
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            $ukms = $query->get();
            $categories = UkmCategory::all();

            return view('user.ukm.list', compact('ukms', 'categories'));
        } catch (\Exception $e) {
            Log::error('UKM List error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat daftar UKM.');
        }
    }

    public function ukmDetail($id)
    {
        try {
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

            // Recent events UKM
            $recentEvents = $ukm->events()
                ->where('event_date', '>=', now())
                ->orderBy('event_date')
                ->take(3)
                ->get();

            // Recent feeds UKM
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
        } catch (\Exception $e) {
            Log::error('UKM Detail error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat detail UKM.');
        }
    }

    public function registerUkm(Request $request, $ukmId)
    {
        try {
            $user = auth()->user();

            // Validasi input
            $validator = Validator::make($request->all(), [
                'motivation' => 'required|string|min:20|max:1000',
                'experience' => 'nullable|string|max:500',
                'skills' => 'nullable|string|max:500',
            ], [
                'motivation.required' => 'Motivasi wajib diisi.',
                'motivation.min' => 'Motivasi minimal 20 karakter.',
                'motivation.max' => 'Motivasi maksimal 1000 karakter.',
                'experience.max' => 'Pengalaman maksimal 500 karakter.',
                'skills.max' => 'Keahlian maksimal 500 karakter.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan validasi. Silakan periksa form Anda.');
            }

            // Validasi maksimal 3 UKM
            $currentRegistrations = Registration::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->count();

            if ($currentRegistrations >= 3) {
                return back()
                    ->withInput()
                    ->with('error', 'Anda sudah mencapai batas maksimal pendaftaran UKM (3 UKM).');
            }

            // Cek apakah sudah mendaftar UKM ini
            $existingRegistration = Registration::where('user_id', $user->id)
                ->where('ukm_id', $ukmId)
                ->first();

            if ($existingRegistration) {
                return back()
                    ->withInput()
                    ->with('error', 'Anda sudah mendaftar UKM ini sebelumnya.');
            }

            // Cek apakah UKM exists
            $ukm = Ukm::where('status', 'active')->find($ukmId);
            if (!$ukm) {
                return back()
                    ->withInput()
                    ->with('error', 'UKM tidak ditemukan atau tidak aktif.');
            }

            $registration = Registration::create([
                'user_id' => $user->id,
                'ukm_id' => $ukmId,
                'motivation' => $request->motivation,
                'experience' => $request->experience,
                'skills' => $request->skills,
                'status' => 'pending',
            ]);

            Log::info('Registration created successfully', [
                'registration_id' => $registration->id,
                'user_id' => $user->id,
                'ukm_id' => $ukmId
            ]);

            return back()->with('success', 'Pendaftaran berhasil! Tunggu konfirmasi dari admin/staff UKM.');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function events(Request $request)
    {
        try {
            $query = Event::with('ukm')->where('event_date', '>=', now());

            // Search events
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            // Filter by UKM
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
        } catch (\Exception $e) {
            Log::error('Events error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat daftar event.');
        }
    }

    public function eventDetail($id)
    {
        try {
            $event = Event::with('ukm')->findOrFail($id);
            $similarEvents = Event::where('ukm_id', $event->ukm_id)
                ->where('id', '!=', $id)
                ->where('event_date', '>=', now())
                ->take(3)
                ->get();

            return view('user.events.detail', compact('event', 'similarEvents'));
        } catch (\Exception $e) {
            Log::error('Event Detail error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat detail event.');
        }
    }

    public function feeds(Request $request)
    {
        try {
            $query = Feed::with('ukm');

            // Search feeds
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('content', 'like', '%' . $request->search . '%');
                });
            }

            // Filter by UKM
            if ($request->filled('ukm')) {
                $query->where('ukm_id', $request->ukm);
            }

            $feeds = $query->latest()->get();
            $ukms = Ukm::where('status', 'active')->get();

            return view('user.feeds.index', compact('feeds', 'ukms'));
        } catch (\Exception $e) {
            Log::error('Feeds error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat daftar feed.');
        }
    }

    public function feedDetail($id)
    {
        try {
            $feed = Feed::with('ukm')->findOrFail($id);
            $relatedFeeds = Feed::where('ukm_id', $feed->ukm_id)
                ->where('id', '!=', $id)
                ->latest()
                ->take(3)
                ->get();

            return view('user.feeds.detail', compact('feed', 'relatedFeeds'));
        } catch (\Exception $e) {
            Log::error('Feed Detail error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat detail feed.');
        }
    }

    public function profile()
    {
        try {
            $user = auth()->user();
            $registrations = $user->registrations()
                ->with(['ukm', 'approver'])
                ->get();

            return view('user.profile', compact('user', 'registrations'));
        } catch (\Exception $e) {
            Log::error('Profile error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat profil.');
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:2',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'required|string|max:15|min:10',
                'fakultas' => 'required|string|max:255|min:2',
                'jurusan' => 'required|string|max:255|min:2',
                'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.min' => 'Nama minimal 2 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'phone.required' => 'Nomor HP wajib diisi.',
                'phone.min' => 'Nomor HP minimal 10 digit.',
                'phone.max' => 'Nomor HP maksimal 15 digit.',
                'fakultas.required' => 'Fakultas wajib diisi.',
                'fakultas.min' => 'Fakultas minimal 2 karakter.',
                'jurusan.required' => 'Jurusan wajib diisi.',
                'jurusan.min' => 'Jurusan minimal 2 karakter.',
                'angkatan.required' => 'Angkatan wajib diisi.',
                'angkatan.min' => 'Angkatan minimal tahun 2000.',
                'angkatan.max' => 'Angkatan maksimal tahun ' . (date('Y') + 1) . '.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan validasi. Silakan periksa form Anda.');
            }

            $user->update($request->only('name', 'email', 'phone', 'fakultas', 'jurusan', 'angkatan'));

            return back()->with('success', 'Profile berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Update Profile error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function cancelRegistration($registrationId)
    {
        try {
            $user = auth()->user();

            $registration = Registration::where('id', $registrationId)
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->firstOrFail();

            $registration->delete();

            return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Cancel Registration error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pendaftaran.');
        }
    }
}
