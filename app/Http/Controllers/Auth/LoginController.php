<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- TAMBAHAN: Wajib di-import agar fungsi login biasa tidak error
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Jalur Callback untuk memproses data dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'role' => 'kontributor', // DISESUAIKAN: Otomatis masuk sebagai kontributor sesuai sistem barumu
                    'password' => bcrypt(Str::random(16)),
                ]);
            }
            
            Auth::login($user);
            
            // Redirect sesuai role dari akun Google yang terdaftar
            if ($user->role === 'contributor') {
                return redirect()->route('contributor.dashboard')->with('success', 'Selamat datang, Kontributor! 🌱');
            } elseif ($user->role === 'upcycler') {
                return redirect()->route('upcycler.dashboard')->with('success', 'Selamat datang, Mitra Penjahit! ✂️');
            }
            
            return redirect()->route('landing');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login menggunakan Google.']);
        }
    }

    /**
     * Proses Login Form Biasa (Email & Password)
     */
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // Ambil data user yang baru saja login
            $user = Auth::user();

            // Cek role dan arahkan ke halaman khusus
            if ($user->role === 'contributor') {
                return redirect()->route('contributor.dashboard')->with('success', 'Selamat datang kembali, Kontributor! 🌱');
            } elseif ($user->role === 'upcycler') {
                return redirect()->route('upcycler.dashboard')->with('success', 'Selamat datang kembali, Mitra Penjahit! ✂️');
            }

            // Default jika ada role lain
            return redirect()->route('landing');
        }

        // PERBAIKAN: Membetulkan posisi kurung kurawal penutup fungsi yang sempat terbalik
        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ]);
    }
}