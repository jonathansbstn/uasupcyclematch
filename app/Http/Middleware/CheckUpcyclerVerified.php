<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUpcyclerVerified
{
    /**
     * Cek apakah upcycler sudah diverifikasi admin.
     * Jika belum, arahkan ke halaman waiting-verification.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'upcycler' && !$user->is_verified) {
            // Biarkan akses ke halaman waiting-verification itu sendiri
            if ($request->routeIs('upcycler.waiting-verification')) {
                return $next($request);
            }
            return redirect()->route('upcycler.waiting-verification');
        }

        return $next($request);
    }
}
