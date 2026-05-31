<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['upcycler', 'textile'])
            ->whereHas('textile', fn($q) => $q->where('status', 'completed'));

        // Search
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // Filter by fabric type
        if ($request->filled('fabric_type')) {
            $query->whereHas('textile', fn($q) => $q->where('fabric_type', $request->fabric_type));
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $fabricTypes = ['katun', 'denim', 'sutra', 'polyester', 'linen', 'wol'];

        return view('gallery', compact('products', 'fabricTypes'));
    }
}
