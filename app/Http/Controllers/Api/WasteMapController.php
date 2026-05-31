<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TextileResource;
use App\Models\Textile;
use Illuminate\Http\JsonResponse;

class WasteMapController extends Controller
{
    /**
     * GET /api/waste-map
     * Mengembalikan semua limbah yang punya koordinat untuk ditampilkan di peta.
     */
    public function index(): JsonResponse
    {
        $textiles = Textile::with('owner')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => TextileResource::collection($textiles),
        ]);
    }
}
