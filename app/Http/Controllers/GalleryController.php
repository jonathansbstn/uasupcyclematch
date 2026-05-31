<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['upcycler', 'textile'])
            ->where('status', 'published');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by fabric type (from textile)
        if ($request->filled('fabric_type')) {
            $query->whereHas('textile', fn($q) => $q->where('fabric_type', $request->fabric_type));
        }

        $products    = $query->latest()->paginate(12)->withQueryString();
        $fabricTypes = ['katun', 'denim', 'sutra', 'polyester', 'linen', 'wol'];
        $categories  = ['tas', 'pakaian', 'aksesori', 'keset', 'dekorasi', 'lainnya'];

        return view('gallery', compact('products', 'fabricTypes', 'categories'));
    }
}
