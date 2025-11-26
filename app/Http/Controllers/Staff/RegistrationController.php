<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        $registrations = Registration::whereIn('ukm_id', $managedUkms->pluck('id'))
            ->with(['user', 'ukm'])
            ->latest()
            ->get();
            
        return view('staff.registrations.index', compact('registrations'));
    }

    public function approve($id)
    {
        $user = auth()->user();
        $registration = Registration::with('ukm')->findOrFail($id);
        
        // Cek apakah staff manage UKM registration ini
        if (!$user->managedUkmsList->contains($registration->ukm_id)) {
            abort(403, 'Anda tidak memiliki akses ke pendaftaran ini.');
        }

        $registration->approve(auth()->id());

        return redirect()->route('staff.registrations.index')->with('success', 'Pendaftaran berhasil disetujui!');
    }

    public function reject($id)
    {
        $user = auth()->user();
        $registration = Registration::with('ukm')->findOrFail($id);
        
        // Cek apakah staff manage UKM registration ini
        if (!$user->managedUkmsList->contains($registration->ukm_id)) {
            abort(403, 'Anda tidak memiliki akses ke pendaftaran ini.');
        }

        $registration->reject(auth()->id());

        return redirect()->route('staff.registrations.index')->with('success', 'Pendaftaran berhasil ditolak!');
    }
}