<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\UpcycleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpcycleController extends Controller
{
    /**
     * 1. Melihat semua limbah kain yang berstatus 'available' (Bahan Baku)
     */
    public function availableDonations()
    {
        // Mengambil data donasi kain yang belum diklaim oleh penjahit lain
        $donations = Donation::where('status', 'available')->with('user', 'fabric')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar bahan baku kain perca tersedia',
            'data' => $donations
        ], 200);
    }

    /**
     * 2. Penjahit mengklaim limbah kain untuk diproduksi
     */
    public function claimDonation($id)
    {
        $donation = Donation::find($id);

        if (!$donation || $donation->status !== 'available') {
            return response()->json(['message' => 'Bahan baku tidak ditemukan atau sudah diklaim'], 404);
        }

        // Mengubah status donasi menjadi 'claimed'
        $donation->status = 'claimed';
        $donation->save();

        return response()->json([
            'success' => true,
            'message' => 'Bahan baku kain berhasil diklaim, silakan ambil di drop point',
            'data' => $donation
        ], 200);
    }

    /**
     * 3. Menyimpan karya baru penjahit ke katalog Upcycle Gallery
     */
    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donation_id' => 'required|exists:donations,id',
            'title' => 'required|string|max:255',
            'tailor_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = JWTAuth::user();

        // Buat produk baru di galeri
        $product = UpcycleProduct::create([
            'user_id' => $user->id,
            'donation_id' => $request->donation_id,
            'title' => $request->title,
            'tailor_name' => $request->tailor_name,
            'category' => $request->category,
        ]);

        // Setelah diproduksi, update status donasi menjadi 'completed'
        $donation = Donation::find($request->donation_id);
        $donation->status = 'completed';
        $donation->save();

        return response()->json([
            'success' => true,
            'message' => 'Karya upcycle berhasil dipajang di galeri publik!',
            'product' => $product
        ], 201);
    }
}