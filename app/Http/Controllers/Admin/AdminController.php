<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ukm;
use App\Models\Event;
use App\Models\Feed;
use App\Models\Registration;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_ukm' => Ukm::count(),
            'total_users' => User::count(),
            'total_staff' => User::where('role', 'staff')->count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            'total_events' => Event::count(),
            'total_feeds' => Feed::count(),
        ];

        $recentRegistrations = Registration::with(['user', 'ukm'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentRegistrations', 'recentUsers'));
    }
}