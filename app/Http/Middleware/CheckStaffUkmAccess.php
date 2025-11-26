<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Ukm;

class CheckStaffUkmAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Admin bisa akses semua
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Untuk staff, cek apakah mereka manage UKM ini
        $ukmId = $request->route('id') ?? $request->route('ukm') ?? $request->ukm_id;
        
        if ($ukmId) {
            $canManage = $user->managedUkmsList()->where('ukm_id', $ukmId)->exists();
            
            if (!$canManage) {
                abort(403, 'Anda tidak memiliki akses ke UKM ini.');
            }
        }

        return $next($request);
    }
}