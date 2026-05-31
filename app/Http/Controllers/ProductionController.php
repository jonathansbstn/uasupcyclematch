<?php

namespace App\Http\Controllers;

use App\Models\Textile;
use App\Models\Product;
use App\Services\ClaimService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductionController extends Controller
{
    public function __construct(protected ClaimService $claimService) {}

    public function index()
    {
        $user     = auth()->user();
        $textiles = Textile::where('claimed_by', $user->id)
            ->with('product')
            ->latest()
            ->get();

        return view('upcycler.production', compact('textiles'));
    }

    public function startProduction(Request $request, Textile $textile)
    {
        // Pastikan hanya upcycler yang mengklaim yang bisa mengubah status
        if ($textile->claimed_by !== auth()->id()) {
            return back()->withErrors(['error' => 'Akses ditolak.']);
        }

        $result = $this->claimService->updateStatus($textile->id, auth()->id(), 'processing');

        if (!$result['success']) {
            return back()->withErrors(['error' => $result['message']]);
        }

        return back()->with('success', 'Status berubah ke Sedang Diproses!');
    }

    public function finishProduction(Request $request, Textile $textile)
    {
        if ($textile->claimed_by !== auth()->id()) {
            return back()->withErrors(['error' => 'Akses ditolak.']);
        }

        $result = $this->claimService->updateStatus($textile->id, auth()->id(), 'completed');

        if (!$result['success']) {
            return back()->withErrors(['error' => $result['message']]);
        }

        return back()->with('success', 'Produksi selesai! Silakan upload karya Anda.');
    }

    public function uploadProduct(Request $request, Textile $textile)
    {
        // Manual authorization — no Policy needed
        if ($textile->claimed_by !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengupload karya untuk limbah ini.');
        }

        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:2000',
            'price'        => 'required|integer|min:0',
            'photo'        => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('photo')->store('products', 'public');

        Product::updateOrCreate(
            ['textile_id' => $textile->id, 'upcycler_id' => auth()->id()],
            [
                'product_name' => $data['product_name'],
                'name'         => $data['product_name'],
                'category'     => $data['category'] ?? null,
                'description'  => $data['description'] ?? null,
                'price'        => $data['price'],
                'photo'        => $path,
                'status'       => 'pending', // menunggu verifikasi admin
            ]
        );

        return back()->with('success', 'Karya berhasil diupload! Menunggu verifikasi admin sebelum tampil di galeri.');
    }
}
