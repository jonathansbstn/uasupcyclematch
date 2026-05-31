<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan user sudah login, jika belum lempar ke route 'login' yang asli
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Jika role tidak sesuai, kunci akses dengan error 403
        if (auth()->user()->role !== $role) {
            abort(403, 'Akses ditolak — halaman ini hanya untuk ' . $role . '.');
        }

        return $next($request);
    }
}