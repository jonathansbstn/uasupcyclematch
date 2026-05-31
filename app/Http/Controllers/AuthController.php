<?php

namespace App\Http\Controllers;

// 1. IMPORT NAMESPACE YANG WAJIB ADA AGAR TIDAK ERROR
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller 
{
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->withInput();
        }

        $request->session()->regenerate();

        // 2. Redirect otomatis berdasarkan nama route yang ada di web.php kamu
        return match(Auth::user()->role) {
            'contributor' => redirect()->route('contributor.dashboard'),
            'upcycler'    => redirect()->route('upcycler.dashboard'), // <== Disamakan dengan routes/web.php
            'admin'       => redirect()->route('admin.dashboard'),
            default       => redirect('/'),
        };
    }

    public function register(Request $request) 
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'whatsapp' => 'nullable|string|max:20',
            'role'     => 'required|in:contributor,upcycler',
            'password' => 'required|min:8|confirmed',
        ]);

        // Mengamankan password menggunakan Hash bawaan Laravel terbaru
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'whatsapp' => $data['whatsapp'] ?? null,
            'role'     => $data['role'],
            'password' => Hash::make($data['password']),
            'koin'     => 0,
        ]);

        Auth::login($user);

        // 3. FIX: Redirect register juga harus adil membedakan role pendaftar!
        return match($user->role) {
            'upcycler'    => redirect()->route('upcycler.dashboard'),
            'contributor' => redirect()->route('contributor.dashboard'),
            default       => redirect('/'),
        };
    }

    // 4. TAMBAHAN FITUR LOGOUT: Memindahkan fungsi logout dari web.php ke controller
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}