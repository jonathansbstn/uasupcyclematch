<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UpcycleController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\FabricDonationController;

/*
|--------------------------------------------------------------------------
| 🔓 ENDPOINT PUBLIK
|--------------------------------------------------------------------------
*/
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/fabrics/dictionary', [FabricDonationController::class, 'indexKamusKain']);

/*
|--------------------------------------------------------------------------
| 🔒 ENDPOINT TERPROTEKSI TOKEN JWT
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    // Autentikasi Internal
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Fitur Kontributor
    Route::post('/contributor/donate', [FabricDonationController::class, 'storeDonation']);

    // Fitur Mitra Penjahit (Upcycler)
    Route::get('/upcycler/materials', [UpcycleController::class, 'availableDonations']);
    Route::post('/upcycler/claim/{id}', [UpcycleController::class, 'claimDonation']);
    Route::post('/upcycler/gallery/store', [UpcycleController::class, 'storeProduct']);

    // Fitur Dashboard Admin
    Route::get('/admin/summary', [AdminController::class, 'dashboardSummary']);
});