<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TextileApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ClaimApiController;
use App\Http\Controllers\Api\ProductionApiController;
use App\Http\Controllers\Api\WasteMapController;

/*
|--------------------------------------------------------------------------
| API ROUTES — UpcycleMatch
|--------------------------------------------------------------------------
|
| Versi API: v1
| Auth:
|   - JWT Bearer Token  → Authorization: Bearer <token>
|   - Basic Auth        → Authorization: Basic base64(email:password)
|   - API Key           → X-API-KEY: <key>        (hanya endpoint tertentu)
|
*/

// ===========================================================================
// LEGACY ENDPOINTS (backward-compat, tidak di-break)
// ===========================================================================
Route::get('/waste-map',         [WasteMapController::class, 'index'])->name('api.waste-map');
Route::get('/analytics',         [ProductionApiController::class, 'analytics'])->name('api.analytics');
Route::middleware('auth')->group(function () {
    Route::post('/claim',            [ClaimApiController::class, 'store'])->name('api.claim');
    Route::post('/start-production', [ClaimApiController::class, 'startProduction'])->name('api.start-production');
    Route::post('/finish-production',[ClaimApiController::class, 'finishProduction'])->name('api.finish-production');
    Route::post('/upload-product',   [ProductionApiController::class, 'uploadProduct'])->name('api.upload-product');
});

// ===========================================================================
// API v1 — PUBLIC (tanpa auth)
// ===========================================================================
Route::prefix('v1')->name('api.v1.')->group(function () {

    // -----------------------------------------------------------------------
    // 1. AUTENTIKASI (JWT)
    // -----------------------------------------------------------------------
    Route::prefix('auth')->name('auth.')->group(function () {
        /**
         * POST /api/v1/auth/register
         * Daftar akun baru → dapat JWT token.
         */
        Route::post('/register', [AuthController::class, 'register'])->name('register');

        /**
         * POST /api/v1/auth/login
         * Login dengan email+password → JWT token.
         * Mendukung: JSON body ATAU Basic Auth header (Authorization: Basic base64(email:password))
         */
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

    // -----------------------------------------------------------------------
    // 2. DATA PUBLIK (hanya baca, no auth)
    // -----------------------------------------------------------------------

    /**
     * GET /api/v1/textiles?status=available&fabric_type=katun&per_page=15
     * Daftar limbah tersedia (publik, bisa diakses tanpa login)
     */
    Route::get('/textiles',      [TextileApiController::class, 'index'])->name('textiles.index');
    Route::get('/textiles/{textile}', [TextileApiController::class, 'show'])->name('textiles.show');

    /**
     * GET /api/v1/analytics
     * Statistik platform (publik)
     */
    Route::get('/analytics',     [ProductionApiController::class, 'analytics'])->name('analytics');

    // -----------------------------------------------------------------------
    // 3. ENDPOINT DILINDUNGI API KEY (alternatif auth untuk integrasi sistem)
    // -----------------------------------------------------------------------
    Route::middleware('api.key')->prefix('public')->name('public.')->group(function () {
        /**
         * GET /api/v1/public/waste-map
         * Data peta limbah untuk integrasi eksternal (Leaflet, GIS, dll).
         * Header: X-API-KEY: <key>
         */
        Route::get('/waste-map', [WasteMapController::class, 'index'])->name('waste-map');

        /**
         * GET /api/v1/public/stats
         * Statistik ringkas untuk dashboard eksternal.
         */
        Route::get('/stats', [ProductionApiController::class, 'analytics'])->name('stats');
    });

    // -----------------------------------------------------------------------
    // 4. ENDPOINT DILINDUNGI JWT — semua role
    // -----------------------------------------------------------------------
    Route::middleware('jwt.auth')->group(function () {

        // Auth: profile, logout, refresh
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
            Route::get('/me',       [AuthController::class, 'profile'])->name('me');
        });

        // Textile CRUD (create/edit/delete butuh login)
        Route::post('/textiles',              [TextileApiController::class, 'store'])->name('textiles.store');
        Route::put('/textiles/{textile}',     [TextileApiController::class, 'update'])->name('textiles.update');
        Route::delete('/textiles/{textile}',  [TextileApiController::class, 'destroy'])->name('textiles.destroy');

        // Klaim limbah (Upcycler)
        Route::post('/textiles/{textile}/claim', [ClaimApiController::class, 'claimViaRoute'])->name('textiles.claim');

        // Order / Pesanan
        Route::get('/orders',                        [OrderApiController::class, 'index'])->name('orders.index');
        Route::post('/orders',                       [OrderApiController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}',                [OrderApiController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status',       [OrderApiController::class, 'updateStatus'])->name('orders.status');

        // Produksi (Upcycler)
        Route::post('/production/start',   [ClaimApiController::class, 'startProduction'])->name('production.start');
        Route::post('/production/finish',  [ClaimApiController::class, 'finishProduction'])->name('production.finish');
        Route::post('/production/upload',  [ProductionApiController::class, 'uploadProduct'])->name('production.upload');
    });
});