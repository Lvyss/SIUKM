<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Ukm;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        $managedUkmIds = $managedUkms->pluck('id');
        
        // Hitung total untuk setiap status (hanya UKM yang di-manage)
        $pendingCount = Registration::whereIn('ukm_id', $managedUkmIds)
            ->where('status', 'pending')->count();
        $approvedCount = Registration::whereIn('ukm_id', $managedUkmIds)
            ->where('status', 'approved')->count();
        $rejectedCount = Registration::whereIn('ukm_id', $managedUkmIds)
            ->where('status', 'rejected')->count();
        $totalCount = Registration::whereIn('ukm_id', $managedUkmIds)->count();

        // Query untuk data registrations dengan filter (hanya UKM yang di-manage)
        $query = Registration::whereIn('ukm_id', $managedUkmIds)
            ->with(['user', 'ukm']);

        // Filter by status jika ada
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by UKM jika ada
        if ($request->has('ukm_id') && $request->ukm_id) {
            // Pastikan UKM yang dipilih termasuk yang di-manage
            if ($managedUkms->contains($request->ukm_id)) {
                $query->where('ukm_id', $request->ukm_id);
            }
        }

        $registrations = $query->latest()->paginate(10);

        return view('staff.registrations.index', compact(
            'registrations',
            'pendingCount',
            'approvedCount', 
            'rejectedCount',
            'totalCount',
            'managedUkms'
        ));
    }

    /**
     * Show registration details (for modal/ajax)
     */
    public function showDetails($id)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        $registration = Registration::with(['user', 'ukm', 'approver'])
            ->whereIn('ukm_id', $managedUkms->pluck('id'))
            ->findOrFail($id);
        
        // Return JSON for AJAX requests, or view for direct access
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $registration
            ]);
        }

        return view('staff.registrations.partials.details', compact('registration'));
    }

    /**
     * Update single registration status
     */
    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        try {
            DB::transaction(function () use ($request, $id, $managedUkms) {
                $registration = Registration::whereIn('ukm_id', $managedUkms->pluck('id'))
                    ->findOrFail($id);
                
                $registration->update([
                    'status' => $request->status,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // ✅ PERBAIKAN: Hapus logic members karena tidak ada tabel ukm_members
                // Jika status approved, registrasi sudah otomatis menjadi anggota UKM
                // Jika butuh logic tambahan, bisa ditambahkan di sini
            });

            return redirect()->route('staff.registrations.index')
                ->with('success', 'Registration status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update registration: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for multiple registrations
     */
    public function bulkAction(Request $request)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'ids' => 'required|array',
            'ids.*' => 'exists:registrations,id'
        ]);

        try {
            DB::transaction(function () use ($request, $managedUkms) {
                $status = $request->action === 'approve' ? 'approved' : 'rejected';
                
                // Update status registrasi
                Registration::whereIn('id', $request->ids)
                    ->whereIn('ukm_id', $managedUkms->pluck('id')) // Hanya registrasi UKM yang di-manage
                    ->where('status', 'pending') // Only process pending registrations
                    ->update([
                        'status' => $status,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                // ✅ PERBAIKAN: Hapus logic members karena tidak ada tabel ukm_members
                // Registrasi yang approved otomatis menjadi anggota UKM
            });

            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed successfully!',
                'processed_count' => count($request->ids)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alternative: Show full registration details page
     */
    public function show($id)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        $registration = Registration::with(['user', 'ukm', 'approver'])
            ->whereIn('ukm_id', $managedUkms->pluck('id'))
            ->findOrFail($id);
        
        return view('staff.registrations.show', compact('registration'));
    }

    /**
     * Get registration statistics for dashboard (AJAX)
     */
    public function getStats()
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        $stats = [
            'pending' => Registration::whereIn('ukm_id', $managedUkms->pluck('id'))
                ->where('status', 'pending')->count(),
            'approved' => Registration::whereIn('ukm_id', $managedUkms->pluck('id'))
                ->where('status', 'approved')->count(),
            'rejected' => Registration::whereIn('ukm_id', $managedUkms->pluck('id'))
                ->where('status', 'rejected')->count(),
            'total' => Registration::whereIn('ukm_id', $managedUkms->pluck('id'))->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get active members count for a UKM (jika diperlukan)
     */
    public function getUkmMembersCount($ukmId)
    {
        $user = auth()->user();
        $managedUkms = $user->managedUkmsList;
        
        // Pastikan UKM termasuk yang di-manage
        if (!$managedUkms->contains($ukmId)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to UKM'
            ], 403);
        }

        // Hitung jumlah anggota aktif (registrasi yang approved)
        $membersCount = Registration::where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'members_count' => $membersCount
            ]
        ]);
    }
}