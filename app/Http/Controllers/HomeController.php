<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\UkmCategory;
use App\Models\Event;
use App\Models\Feed;

class HomeController extends Controller
{
    // Welcome Page (Guest) - bisa dihapus atau diubah
public function welcome()
    {
        // Jika sudah login, redirect ke home/dashboard
        if (auth()->check()) {
            return redirect()->route('home');
        }
        
        return view('welcome');
    }


    // ✅ METHOD home(): Tampilkan dashboard user untuk guest & authenticated
public function home()
{
    // Ambil data untuk dashboard
    $trendingEvents = Event::with('ukm')->latest()->take(4)->get();
    $trendingFeeds = Feed::with('ukm')->latest()->take(4)->get();
    
    // ✅ TAMBAHKAN: UKM TERPOPULER (3 UKM dengan anggota terbanyak)
    $ukms = Ukm::with('category')
        ->where('status', 'active')
        ->withCount(['registrations as members_count' => function($query) {
            $query->where('status', 'approved');
        }])
        ->orderBy('members_count', 'desc')
        ->take(3)
        ->get();
    
    // ✅ TAMBAHKAN: Data Categories untuk filter
    $categories = UkmCategory::all();
    
    // Jika guest, tampilkan dashboard user (dengan modal login)
    if (!auth()->check()) {
        return view('user.dashboard', compact(
            'trendingEvents', 
            'trendingFeeds',
            'ukms', // ✅ Tambahkan ini
            'categories' // ✅ Tambahkan ini
        ));
    }
    
    // Jika sudah login, arahkan ke dashboard sesuai role
    return $this->dashboard();
}

    // Dashboard berdasarkan role (untuk yang sudah login)
    public function dashboard()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
}