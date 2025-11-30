<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ukm;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('registrations');
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);
           $totalUsers = User::count();
    $adminUsers = User::where('role', 'admin')->count();
    $staffUsers = User::where('role', 'staff')->count();
    $regularUsers = User::where('role', 'user')->count();
        $ukms = Ukm::where('status', 'active')->get();
            return view('admin.users.index', compact(
        'users',
        'totalUsers',      // TAMBAHIN INI
        'adminUsers',      // TAMBAHIN INI  
        'staffUsers',      // TAMBAHIN INI
        'regularUsers'     // TAMBAHIN INI
    ));
    }

    public function updateRole(Request $request, $id)
    {
        // Prevent users from changing their own role
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }
        
        $request->validate([
            'role' => 'required|in:user,staff,admin'
        ]);
        
        try {
            $user = User::findOrFail($id);
            $user->update(['role' => $request->role]);
            
            return redirect()->back()->with('success', "User role updated to {$request->role} successfully!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user role: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Prevent users from deleting themselves
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        try {
            $user = User::findOrFail($id);
            
            DB::transaction(function () use ($user) {
                // Delete user's registrations
                $user->registrations()->delete();
                
                // Delete user
                $user->delete();
            });
            
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}