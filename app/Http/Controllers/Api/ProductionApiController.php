<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Textile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductionApiController extends Controller
{
    /**
     * POST /api/upload-product
     */
    public function uploadProduct(Request $request): JsonResponse
    {
        $data = $request->validate([
            'textile_id'   => 'required|integer|exists:textiles,id',
            'product_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|integer|min:0',
            'photo'        => 'required|image|max:5120',
        ]);

        $textile = Textile::where('id', $data['textile_id'])
            ->where('claimed_by', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        $path = $request->file('photo')->store('products', 'public');

        $product = Product::create([
            'upcycler_id'  => auth()->id(),
            'textile_id'   => $textile->id,
            'product_name' => $data['product_name'],
            'description'  => $data['description'] ?? null,
            'price'        => $data['price'],
            'photo'        => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupload!',
            'product' => [
                'id'           => $product->id,
                'product_name' => $product->product_name,
                'photo'        => asset('storage/' . $product->photo),
            ],
        ]);
    }

    /**
     * GET /api/analytics
     */
    public function analytics(): JsonResponse
    {
        $totalWeight   = Textile::whereIn('status', ['claimed', 'processing', 'completed'])->sum('weight');
        $totalProducts = Product::count();
        $totalUpcyclers = \App\Models\User::where('role', 'upcycler')->count();
        $materialSavings = $totalWeight * 50000; // Rp50.000/kg

        // Limbah per bulan (12 bulan terakhir)
        $monthlyWaste = Textile::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(weight) as total')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        // Produk per bulan
        $monthlyProducts = Product::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        // Distribusi status
        $statusDist = Textile::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return response()->json([
            'success' => true,
            'summary' => [
                'total_weight'    => round((float) $totalWeight, 2),
                'total_products'  => $totalProducts,
                'total_upcyclers' => $totalUpcyclers,
                'material_savings'=> $materialSavings,
            ],
            'charts' => [
                'monthly_waste'    => $monthlyWaste,
                'monthly_products' => $monthlyProducts,
                'status_dist'      => $statusDist,
            ],
        ]);
    }
}
