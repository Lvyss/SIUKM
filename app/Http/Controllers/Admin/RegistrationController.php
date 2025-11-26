<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::with(['user', 'ukm', 'approver'])
            ->latest()
            ->get();
            
        return view('admin.registrations.index', compact('registrations'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $registration = Registration::findOrFail($id);

        if ($request->status === 'approved') {
            $registration->approve(auth()->id());
        } else {
            $registration->reject(auth()->id());
        }

        return redirect()->route('admin.registrations.index')->with('success', 'Status pendaftaran berhasil diupdate!');
    }
}