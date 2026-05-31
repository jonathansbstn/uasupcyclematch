<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LimbahKain; // 1. Diperbaiki agar sesuai dengan model limbah_kains
use App\Models\KoinTransaction; // 2. Digunakan untuk menarik data Eco-Wallet riil
use Illuminate\Support\Facades\Auth;

class ContributorController extends Controller 
{
    public function dashboard(Request $request) 
    {
        // Mengunci object user real-time yang sedang login (Salsa / id=2)
        $user = $request->user();

        // 3. Kalkulasi statistik riil berdasarkan struktur database terbaru lu
        $stats = [
            'total_berat'     => $user->limbahKains()->sum('berat_kg'),
            'postingan_aktif' => $user->limbahKains()->where('status', 'available')->count(),
            'total_koin'      => $user->koin,
            'total_rupiah'    => $user->koin * 2500, // Konversi Rp2.500 per koin sesuai mockup asli lu
        ];

        // 4. Ambil list riwayat kain dan transaksi koin real dari MySQL
        $limbahList = $user->limbahKains()->latest()->get();
        $koinTransactions = KoinTransaction::where('user_id', $user->id)->latest()->get();
        
        // 5. Riwayat pembelian produk di Eco-Mall (jika ada)
        $recentOrders = $user->orders()->with('produkJadi')->latest()->take(3)->get();

        // Kirim semua data asli ke file blade mockup lu
        return view('contributor.dashboard', compact('user', 'stats', 'limbahList', 'koinTransactions', 'recentOrders'));
    }
}