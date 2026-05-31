<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Textile;
use App\Models\Product;
use App\Models\KoinTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ContributorController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Ambil semua limbah kontributor dari tabel textiles
        $myTextiles = Textile::where('user_id', $user->id)->latest()->get();

        $stats = [
            'total_berat'     => $myTextiles->sum('weight'),
            'postingan_aktif' => $myTextiles->count(),
            'total_koin'      => $user->koin,
            'total_rupiah'    => $user->koin * 2500,
        ];

        // Untuk live tracker di dashboard
        $limbahList = $myTextiles;

        $koinTransactions = KoinTransaction::where('user_id', $user->id)->latest()->get();
        $recentOrders = \App\Models\Order::with('product')->where('buyer_id', $user->id)->latest()->take(3)->get();

        // Produk published dari upcycler (untuk section Beli Produk)
        $publishedProducts = Product::with(['upcycler', 'textile'])
            ->where('status', 'published')
            ->latest()
            ->take(8)
            ->get();

        return view('contributor.dashboard', compact(
            'user', 'stats', 'limbahList', 'koinTransactions', 'recentOrders', 'publishedProducts'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'whatsapp'         => 'nullable|string|max:20',
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:8|confirmed',
        ]);

        // Jika ingin ganti password, verifikasi password lama
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name      = $data['name'];
        $user->whatsapp  = $data['whatsapp'] ?? $user->whatsapp;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}