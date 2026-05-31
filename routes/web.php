<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LimbahKain;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\LimbahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController; // <== Login Google lu tetap aman di sini
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| 1. LANDING PAGE (Umum)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| 2. SISTEM AUTENTIKASI (Login Form, Register, & Logout via AuthController)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () { 
    if (Auth::check()) {
        return match(Auth::user()->role) {
            'admin'       => redirect()->route('admin.dashboard'),
            'upcycler'    => redirect()->route('upcycler.dashboard'),
            'contributor' => redirect()->route('contributor.dashboard'),
            default       => redirect('/'),
        };
    }
    return view('auth.login'); 
})->name('login');

// Form submit login biasa diproses AuthController agar koin sinkron
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/register', function () { 
    return view('auth.register'); 
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 3. AREA DASHBOARD CONTRIBUTOR (Akses Khusus Role: contributor)
|--------------------------------------------------------------------------
*/
// Menggunakan middleware 'role:contributor' buatanmu
Route::middleware(['auth', 'role:contributor'])->prefix('contributor')->group(function () {
    
    Route::get('/dashboard', [ContributorController::class, 'dashboard'])->name('contributor.dashboard');
    Route::get('/upload-limbah', [LimbahController::class, 'tracker'])->name('contributor.upload');
    Route::post('/upload-limbah', [LimbahController::class, 'store'])->name('contributor.post');

    Route::get('/limbah/detail/{id}', function ($id) {
        $limbah = LimbahKain::findOrFail($id); 
        return view('contributor.detail_limbah', compact('limbah'));
    })->name('contributor.limbah.detail');

    Route::get('/galeri', function () { return view('contributor.galeri'); })->name('contributor.galeri');
    Route::get('/kamus-kain', function () { return view('contributor.kamus'); })->name('contributor.kamus');
    Route::get('/peta', function () { return view('contributor.peta'); })->name('contributor.peta');
});


/*
|--------------------------------------------------------------------------
| 4. AREA DASHBOARD MITRA PENJAHIT (Akses Khusus Role: upcycler)
|--------------------------------------------------------------------------
*/
// Menggunakan middleware 'role:upcycler' buatanmu
Route::middleware(['auth', 'role:upcycler'])->prefix('upcycler')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('upcycler.dashboard');
    })->name('upcycler.dashboard');

    Route::get('/kain-tersedia', function () {
        $daftarLimbah = LimbahKain::latest()->get(); 
        return view('upcycler.materials', compact('daftarLimbah'));
    })->name('upcycler.materials');
});


/*
|--------------------------------------------------------------------------
| 5. AREA DASHBOARD ADMIN GLOBAL (Akses Khusus Role: admin)
|--------------------------------------------------------------------------
*/
// Menggunakan middleware 'role:admin' buatanmu
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', function () { 
        $totalLimbah = LimbahKain::count(); 
        $totalBerat = LimbahKain::sum('berat_kg'); 
        $co2Saved = $totalBerat * 0.16;
        return view('admin.dashboard', compact('totalLimbah', 'totalBerat', 'co2Saved')); 
    })->name('admin.dashboard');

    Route::get('/galeri', function () { return view('contributor.galeri'); })->name('admin.galeri');
    Route::get('/data-limbah', function () { return view('contributor.kamus'); })->name('admin.data-limbah');
});


/*
|--------------------------------------------------------------------------
| 6. SECURE ROUTING & GOOGLE SOCIALITE (Jalur Login Google)
|--------------------------------------------------------------------------
*/
Route::get('/logistik-peta', function() {
    return redirect()->route('login');
})->name('maps.logistik');

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

// FIX AMAN: Mengembalikan handle callback murni ke LoginController bawaanmu yang sudah sukses kemarin
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);