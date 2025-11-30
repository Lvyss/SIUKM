<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        // Hitung total untuk setiap status
        $pendingCount = Registration::where('status', 'pending')->count();
        $approvedCount = Registration::where('status', 'approved')->count();
        $rejectedCount = Registration::where('status', 'rejected')->count();
        $totalCount = Registration::count();

        // Query untuk data registrations dengan filter
        $query = Registration::with(['user', 'ukm']);

        // Filter by status jika ada
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $registrations = $query->latest()->paginate(10);

        return view('admin.registrations.index', compact(
            'registrations',
            'pendingCount',
            'approvedCount', 
            'rejectedCount',
            'totalCount'
        ));
    }

    /**
     * Show registration details (for modal/ajax)
     */
    public function showDetails($id)
    {
        $registration = Registration::with(['user', 'ukm', 'approver'])->findOrFail($id);
        
        // Return JSON for AJAX requests, or view for direct access
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $registration
            ]);
        }

        return view('admin.registrations.partials.details', compact('registration'));
    }

    /**
     * Update single registration status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $registration = Registration::findOrFail($id);
                
                $registration->update([
                    'status' => $request->status,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // ✅ PERBAIKAN: Hapus logic members karena tidak ada tabel ukm_members
            });

            return redirect()->route('admin.registrations.index')
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
        $request->validate([
            'action' => 'required|in:approve,reject',
            'ids' => 'required|array',
            'ids.*' => 'exists:registrations,id'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $status = $request->action === 'approve' ? 'approved' : 'rejected';
                
                Registration::whereIn('id', $request->ids)
                    ->where('status', 'pending') // Only process pending registrations
                    ->update([
                        'status' => $status,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                // ✅ PERBAIKAN: Hapus logic members karena tidak ada tabel ukm_members
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
        $registration = Registration::with(['user', 'ukm', 'approver'])->findOrFail($id);
        
        return view('admin.registrations.show', compact('registration'));
    }
}