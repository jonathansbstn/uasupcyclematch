<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Donation;
use App\Models\UpcycleProduct;

class AdminController extends Controller
{
    /**
     * Ringkasan statistik dashboard untuk audit internal admin & laporan SDGs
     */
    public function dashboardSummary()
    {
        // Hitung total kilogram kain sirkular yang masuk sistem
        $totalWeight = Donation::sum('weight');
        $totalUsers = User::count();
        $totalDonations = Donation::count();
        $completedProducts = UpcycleProduct::count();

        // Rumus dampak karbon: Asumsi hemat 1 kg kain mengurangi dampak emisi sekitar 0.16 kg CO2
        $co2Saved = $totalWeight * 0.16;

        return response()->json([
            'success' => true,
            'message' => 'Statistik Dampak Sirkular UpcycleMatch (SDGs Metrics)',
            'summary' => [
                'total_pengguna_aktif' => $totalUsers,
                'total_transaksi_masuk' => $totalDonations,
                'total_limbah_terselamatkan_kg' => $totalWeight,
                'total_produk_upcycle_jadi' => $completedProducts,
                'estimasi_co2_ditekan_kg' => $co2Saved
            ]
        ], 200);
    }
}