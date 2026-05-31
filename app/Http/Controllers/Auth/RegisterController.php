<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Memproses data registrasi dari form
     */
    public function store(Request $request)
    {
        // 1. Validasi input form (Hapus aturan no_hp dan kota/alamat jika tidak dipakai)
        $request->validate([
            'nama_depan'    => 'required|string|max:255',
            'nama_belakang' => 'nullable|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'gender'        => 'required|in:L,P',
            'role'          => 'required|in:kontributor,penjahit',
        ]);

        $fullName = trim($request->nama_depan . ' ' . $request->nama_belakang);

        // 2. Simpan ke database tanpa no_hp dan kota
        $user = User::create([
            'name'     => $fullName,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'gender'   => $request->gender,
            'role'     => $request->role,
        ]);

        Auth::login($user);

        if ($user->role === 'kontributor') {
            return redirect()->route('contributor.dashboard')->with('success', 'Registrasi berhasil! 🌱');
        } else {
            return redirect()->route('penjahit.dashboard')->with('success', 'Registrasi berhasil! ✂️');
        }
    
        // 3. Masukkan data ke database
        $user = User::create([
            'name'     => $fullName,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => bcrypt($request->password),
            'gender'   => $request->gender,
            'role'     => $request->role,
        ]);

        // 4. Otomatis loloskan login setelah sukses daftar
        Auth::login($user);

        // 5. Arahkan ke dashboard masing-masing role dengan pesan sukses
        if ($user->role === 'kontributor') {
            return redirect()->route('kontributor.dashboard')->with('success', 'Registrasi berhasil! Selamat datang Kontributor 🌱');
        } else {
            return redirect()->route('penjahit.dashboard')->with('success', 'Registrasi berhasil! Selamat datang Penjahit ✂️');
        }
    }
} // <--- Pastikan kurung kurawal penutup class ini ada di paling bawah!