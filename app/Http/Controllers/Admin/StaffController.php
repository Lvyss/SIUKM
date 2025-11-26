<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ukm;
use App\Models\UkmStaff;

class StaffController extends Controller
{
    public function index()
    {
        $staffUsers = User::where('role', 'staff')->get();
        $ukms = Ukm::all();
        $ukmStaff = UkmStaff::with(['user', 'ukm'])->get();
        
        return view('admin.staff.index', compact('staffUsers', 'ukms', 'ukmStaff'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ukm_id' => 'required|exists:ukms,id',
        ]);

        // Pastikan user adalah staff
        $user = User::findOrFail($request->user_id);
        if (!$user->isStaff()) {
            return redirect()->route('admin.staff.index')->with('error', 'User harus memiliki role staff!');
        }

        // Cek apakah sudah menjadi staff di UKM ini
        $existing = UkmStaff::where('user_id', $request->user_id)
            ->where('ukm_id', $request->ukm_id)
            ->first();

        if ($existing) {
            return redirect()->route('admin.staff.index')->with('error', 'User sudah menjadi staff di UKM ini!');
        }

        UkmStaff::create($request->all());

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan ke UKM!');
    }

    public function remove($id)
    {
        $ukmStaff = UkmStaff::findOrFail($id);
        $ukmStaff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus dari UKM!');
    }
}