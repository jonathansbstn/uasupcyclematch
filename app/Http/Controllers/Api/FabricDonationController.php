<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fabric;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class FabricDonationController extends Controller
{
    /**
     * 1. Mengambil data master Kamus Kain (Untuk Pre-login Public State)
     */
    public function indexKamusKain()
    {
        $fabrics = Fabric::all();
        return response()->json([
            'success' => true,
            'message' => 'Data Kamus Kain UpcycleMatch Berhasil Dimuat',
            'data' => $fabrics
        ], 200);
    }

    /**
     * 2. Kontributor menyumbangkan/mengunggah data limbah kain baru
     */
    public function storeDonation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fabric_id' => 'required|exists:fabrics,id',
            'weight' => 'required|numeric|min:0.1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Batasan upload foto
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = JWTAuth::user();

        // Logika upload gambar (jika ada)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('donations', 'public');
        }

        $donation = Donation::create([
            'user_id' => $user->id,
            'fabric_id' => $request->fabric_id,
            'weight' => $request->weight,
            'image_path' => $imagePath,
            'status' => 'available'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Limbah kain berhasil didaftarkan ke dalam ekosistem sirkular!',
            'data' => $donation
        ], 201);
    }
}