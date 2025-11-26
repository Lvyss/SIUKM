<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\Event;
use App\Models\Feed;
use App\Models\Registration;

class StaffController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        $stats = [
            'managed_ukms' => $managedUkms->count(),
            'pending_registrations' => Registration::whereIn('ukm_id', $managedUkms->pluck('id'))
                ->where('status', 'pending')
                ->count(),
            'total_events' => Event::whereIn('ukm_id', $managedUkms->pluck('id'))->count(),
            'total_feeds' => Feed::whereIn('ukm_id', $managedUkms->pluck('id'))->count(),
        ];

        $recentRegistrations = Registration::whereIn('ukm_id', $managedUkms->pluck('id'))
            ->with(['user', 'ukm'])
            ->latest()
            ->take(5)
            ->get();

        return view('staff.dashboard', compact('stats', 'managedUkms', 'recentRegistrations'));
    }
}