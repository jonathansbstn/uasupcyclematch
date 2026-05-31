<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WasteMapController;
use App\Http\Controllers\Api\ClaimApiController;
use App\Http\Controllers\Api\ProductionApiController;

/*
|--------------------------------------------------------------------------
| PUBLIC ENDPOINTS
|--------------------------------------------------------------------------
*/

// Waste Map — diakses dari Leaflet.js (butuh auth session web)
Route::get('/waste-map', [WasteMapController::class, 'index'])->name('api.waste-map');

// Analytics publik
Route::get('/analytics', [ProductionApiController::class, 'analytics'])->name('api.analytics');

/*
|--------------------------------------------------------------------------
| PROTECTED ENDPOINTS (auth:sanctum via session)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Claim
    Route::post('/claim', [ClaimApiController::class, 'store'])->name('api.claim');

    // Production workflow
    Route::post('/start-production', [ClaimApiController::class, 'startProduction'])->name('api.start-production');
    Route::post('/finish-production', [ClaimApiController::class, 'finishProduction'])->name('api.finish-production');

    // Upload product
    Route::post('/upload-product', [ProductionApiController::class, 'uploadProduct'])->name('api.upload-product');
});