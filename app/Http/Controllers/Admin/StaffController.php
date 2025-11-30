<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ukm;
use App\Models\UkmStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        try {
            $staffUsers = User::where('role', 'staff')
                ->withCount('ukmStaff')
                ->orderBy('name')
                ->get();
                
            $ukms = Ukm::with(['category'])
                ->withCount('staff')
                ->orderBy('name')
                ->get();
                
            $ukmStaff = UkmStaff::with(['user', 'ukm.category'])
                ->latest()
                ->get();

            return view('admin.staff.index', compact('staffUsers', 'ukms', 'ukmStaff'));
            
        } catch (\Exception $e) {
            Log::error('Staff index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load staff management page: ' . $e->getMessage());
        }
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ukm_id' => 'required|exists:ukms,id',
        ], [
            'user_id.required' => 'Staff user wajib dipilih',
            'user_id.exists' => 'User tidak valid',
            'ukm_id.required' => 'UKM wajib dipilih',
            'ukm_id.exists' => 'UKM tidak valid',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::findOrFail($request->user_id);
                if ($user->role !== 'staff') {
                    throw new \Exception('User harus memiliki role staff!');
                }

                $ukm = Ukm::findOrFail($request->ukm_id);
                if ($ukm->status !== 'active') {
                    throw new \Exception('Tidak bisa assign staff ke UKM yang tidak aktif!');
                }

                $existing = UkmStaff::where('user_id', $request->user_id)
                    ->where('ukm_id', $request->ukm_id)
                    ->first();

                if ($existing) {
                    throw new \Exception('User sudah menjadi staff di UKM ini!');
                }

                // ✅ PERBAIKAN: Hapus created_by jika kolom tidak ada
                UkmStaff::create([
                    'user_id' => $request->user_id,
                    'ukm_id' => $request->ukm_id,
                    // 'created_by' => auth()->id(), // HAPUS BARIS INI
                ]);

                Log::info('Staff assigned successfully', [
                    'user_id' => $request->user_id,
                    'ukm_id' => $request->ukm_id,
                    'assigned_by' => auth()->id()
                ]);
            });

            return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan ke UKM!');
            
        } catch (\Exception $e) {
            Log::error('Staff assignment failed: ' . $e->getMessage(), [
                'user_id' => $request->user_id,
                'ukm_id' => $request->ukm_id
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan staff: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function remove($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $ukmStaff = UkmStaff::findOrFail($id);
                
                Log::info('Removing staff assignment', [
                    'assignment_id' => $id,
                    'user_id' => $ukmStaff->user_id,
                    'ukm_id' => $ukmStaff->ukm_id
                ]);
                
                $ukmStaff->delete();
            });

            return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus dari UKM!');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Staff assignment not found for removal', ['assignment_id' => $id]);
            return redirect()->route('admin.staff.index')->with('error', 'Staff assignment tidak ditemukan!');
            
        } catch (\Exception $e) {
            Log::error('Staff removal failed: ' . $e->getMessage(), ['assignment_id' => $id]);
            return redirect()->back()->with('error', 'Gagal menghapus staff: ' . $e->getMessage());
        }
    }
}