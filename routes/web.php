<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LimbahKain;
use App\Models\Textile;
use App\Models\User;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\LimbahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExplorationMapController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AdminAnalyticsController;
use App\Http\Controllers\AdminVerificationController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\OrderController;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| 1. LANDING PAGE & GALLERY PUBLIK (Umum)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

/*
|--------------------------------------------------------------------------
| 2. SISTEM AUTENTIKASI
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

Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/register', function () {
    if (Auth::check()) {
        return match(Auth::user()->role) {
            'admin'       => redirect()->route('admin.dashboard'),
            'upcycler'    => redirect()->route('upcycler.dashboard'),
            'contributor' => redirect()->route('contributor.dashboard'),
            default       => redirect('/'),
        };
    }
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 3. AREA DASHBOARD CONTRIBUTOR
|--------------------------------------------------------------------------
*/
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
    Route::get('/peta', [ExplorationMapController::class, 'contributorMap'])->name('contributor.peta');
    
    // Profile Update
    Route::post('/profile', [ContributorController::class, 'updateProfile'])->name('contributor.profile.update');

    // Checkout & Orders
    Route::get('/checkout/{product}', [OrderController::class, 'checkout'])->name('contributor.checkout');
    Route::post('/checkout/{product}', [OrderController::class, 'store'])->name('contributor.checkout.store');
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('contributor.orders');
});

/*
|--------------------------------------------------------------------------
| API ROUTES (accessible by authenticated users - no prefix)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Peta: ambil semua data limbah sebagai JSON
    Route::get('/api/waste-map', [ExplorationMapController::class, 'wasteMap'])->name('api.waste-map');
    // Peta: upcycler klaim limbah
    Route::post('/api/claim', [ExplorationMapController::class, 'claim'])->name('api.claim');
});

/*
|--------------------------------------------------------------------------
| 4. AREA DASHBOARD UPCYCLER (MITRA PENJAHIT)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:upcycler'])->prefix('upcycler')->group(function () {

    // Dashboard utama upcycler
    Route::get('/dashboard', function () {
        $user     = auth()->user();
        $claims   = Textile::where('claimed_by', $user->id)->latest()->get();
        $available = Textile::where('status', 'available')->latest()->get();
        $stats = [
            'processing'   => $claims->where('status', 'processing')->count(),
            'total_claims' => $claims->count(),
            'kg_total'     => $claims->sum('weight'),
            'completed'    => $claims->where('status', 'completed')->count(),
        ];
        return view('upcycler.dashboard', compact('claims', 'available', 'stats'));
    })->name('upcycler.dashboard');

    // Exploration Map (Fitur Utama)
    Route::get('/exploration-map', [ExplorationMapController::class, 'index'])->name('upcycler.exploration-map');

    // Production Dashboard
    Route::get('/production', [ProductionController::class, 'index'])->name('upcycler.production');
    Route::patch('/production/{textile}/start', [ProductionController::class, 'startProduction'])->name('upcycler.production.start');
    Route::patch('/production/{textile}/finish', [ProductionController::class, 'finishProduction'])->name('upcycler.production.finish');
    Route::post('/production/{textile}/upload', [ProductionController::class, 'uploadProduct'])->name('upcycler.production.upload');

    // Kain tersedia
    Route::get('/kain-tersedia', function () {
        $daftarLimbah = Textile::where('status', 'available')->latest()->get();
        return view('upcycler.materials', compact('daftarLimbah'));
    })->name('upcycler.materials');

    // Dummy routes untuk backward compat
    Route::get('/peta', function () { return redirect()->route('upcycler.exploration-map'); })->name('peta.index');
    Route::post('/klaim/{id}', function ($id) { return back()->with('success', 'Gunakan peta untuk klaim'); })->name('klaim.store');
    Route::patch('/klaim/status/{id}', function ($id) { return back()->with('success', 'Status diupdate'); })->name('klaim.status');
});

/*
|--------------------------------------------------------------------------
| 5. AREA DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        $stats = [
            'total_kg'        => (float) Textile::whereIn('status', ['claimed','processing','completed'])->sum('weight'),
            'modal_hemat'     => (float) Textile::whereIn('status', ['claimed','processing','completed'])->sum('weight') * 50000,
            'total_users'     => User::count(),
            'pending_penjahit'=> User::where('role', 'upcycler')->where('is_verified', false)->count(),
        ];
        $recentPosts    = Textile::with('owner')->latest()->take(10)->get();
        $pendingPenjahit = User::where('role', 'upcycler')->where('is_verified', false)->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentPosts', 'pendingPenjahit'));
    })->name('admin.dashboard');

    // Analytics
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics');

    // User Verification
    Route::get('/upcycler-verification', [AdminVerificationController::class, 'index'])->name('admin.verification');
    Route::patch('/upcycler-verification/{user}/verify', [AdminVerificationController::class, 'verify'])->name('admin.verification.verify');
    Route::patch('/upcycler-verification/{user}/reject', [AdminVerificationController::class, 'reject'])->name('admin.verification.reject');

    // Report & Export
    Route::get('/report', [AdminReportController::class, 'index'])->name('admin.report');
    Route::get('/report/excel', [AdminReportController::class, 'exportExcel'])->name('admin.report.excel');
    Route::get('/report/pdf', [AdminReportController::class, 'exportPdf'])->name('admin.report.pdf');

    // Galeri & Data Limbah
    Route::get('/galeri', function () {
        $products = \App\Models\Product::with(['owner','upcycler'])->latest()->paginate(12);
        
        $stats = [
            'total' => \App\Models\Product::count(),
            'pending' => \App\Models\Product::where('status', 'pending')->count(),
            'published' => \App\Models\Product::where('status', 'published')->count(),
        ];

        return view('admin.galeri', compact('products', 'stats'));
    })->name('admin.galeri');

    Route::patch('/galeri/{product}/approve', function (\App\Models\Product $product) {
        $product->update(['status' => 'published']);
        return back()->with('success', 'Karya berhasil dipublish.');
    })->name('admin.galeri.approve');

    Route::patch('/galeri/{product}/reject', function (\App\Models\Product $product) {
        $product->update(['status' => 'rejected']);
        return back()->with('success', 'Karya telah ditolak.');
    })->name('admin.galeri.reject');

    Route::get('/data-limbah', function () {
        $textiles = \App\Models\Textile::with('owner')->latest()->paginate(20);
        return view('admin.data-limbah', compact('textiles'));
    })->name('admin.limbah');

    Route::get('/data-limbah/{textile}', function (\App\Models\Textile $textile) {
        $textile->load(['owner', 'upcycler', 'product']);
        return view('admin.detail-limbah', ['item' => $textile, 'textile' => $textile]);
    })->name('admin.detail-limbah');

    Route::get('/users', function () {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    })->name('admin.users');
    Route::patch('/users/{user}/verify', function (User $user) {
        $user->update(['is_verified' => true]);
        return back()->with('success', 'User berhasil diverifikasi.');
    })->name('admin.users.verify');
    Route::get('/export', function () {
        return redirect()->route('admin.report');
    })->name('admin.export');
});

/*
|--------------------------------------------------------------------------
| 6. GOOGLE SOCIALITE
|--------------------------------------------------------------------------
*/
Route::get('/logistik-peta', function () {
    return redirect()->route('login');
})->name('maps.logistik');

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);