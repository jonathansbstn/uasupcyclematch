<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Contracts\TextileRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TextileController extends Controller
{
    protected TextileRepositoryInterface $textileRepo;

    /**
     * Dependency Injection: Laravel otomatis resolve implementasi
     * melalui binding di AppServiceProvider.
     */
    public function __construct(TextileRepositoryInterface $textileRepo)
    {
        $this->textileRepo = $textileRepo;
    }

    /**
     * Halaman utama terpusat (Centralized Main Page) yang dinamis per-aktor.
     */
    /**
     * Halaman utama terpusat (Centralized Main Page) yang dinamis per-aktor.
     */
    public function index(): View
    {
        // 1. Ambil data kain aktif dari repository
        $availableTextiles = $this->textileRepo->getAllAvailable();
        
        // 💡 SOLUSI AMAN: Hitung total berat langsung dari database menggunakan Model Textile kelompokmu
        // Ini akan menghindari ketidakcocokan nama fungsi di Repository Pattern
        $totalSavedWeight = \App\Models\Textile::whereIn('status', ['claimed', 'processing', 'completed'])->sum('weight');

        // Perhitungan ilmiah konversi dampak lingkungan dan ekonomi
        $totalSavings = $totalSavedWeight * 45000;
        $totalCo2     = $totalSavedWeight * 1.2;

        // 2. Jika Pengguna sedang dalam posisi Login, arahkan ke View Dashboard Aktor masing-masing
        if (Auth::check()) {
            $user = Auth::user();

            // JIKA CONTRIBUTOR LOGIN:
            if ($user->role === 'contributor' || (method_exists($user, 'isContributor') && $user->isContributor())) {
                $myTextiles = $this->textileRepo->getByContributor($user->id);
                return view('contributor.dashboard', compact('myTextiles', 'totalSavedWeight', 'totalSavings', 'totalCo2'));
            }

            // JIKA UPCYCLER (PENJAHIT) LOGIN:
            if ($user->role === 'upcycler' || (method_exists($user, 'isUpcycler') && $user->isUpcycler())) {
                $claimedTextiles = $this->textileRepo->getByUpcycler($user->id);
                return view('upcycler.dashboard', compact('claimedTextiles', 'totalSavedWeight', 'totalSavings', 'totalCo2'));
            }

            // JIKA ADMIN LOGIN:
            if ($user->role === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin())) {
                $allTextiles    = $this->textileRepo->getAll();
                $allUsers       = User::all();
                $totalCompleted = $allTextiles->where('status', 'completed')->count();
                $totalUsers     = $allUsers->count();

                return view('admin.dashboard', compact('allTextiles', 'allUsers', 'totalCompleted', 'totalUsers', 'totalSavedWeight', 'totalSavings', 'totalCo2'));
            }
        }

        // 3. JIKA GUEST (Belum login): Sediakan data default untuk landing page utama kelompokmu
        $myTextiles      = null;
        $claimedTextiles = null;
        $allTextiles     = null;
        $allUsers        = null;
        $totalCompleted  = 0;
        $totalUsers      = 0;

        return view('upcycle-main', compact(
            'availableTextiles',
            'totalSavedWeight',
            'totalSavings',
            'totalCo2',
            'myTextiles',
            'claimedTextiles',
            'allTextiles',
            'allUsers',
            'totalCompleted',
            'totalUsers'
        ));
    }

    /**
     * Simpan postingan limbah baru dari contributor.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'fabric_type' => 'required|in:Katun,Denim,Sutra,Polyester',
            'weight'      => 'required|numeric|min:0.1|max:1000',
            'description' => 'required|string|max:2000',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ], [
            'title.required'       => 'Nama limbah wajib diisi.',
            'fabric_type.required' => 'Jenis kain wajib dipilih.',
            'fabric_type.in'       => 'Jenis kain tidak valid.',
            'weight.required'      => 'Berat kain wajib diisi.',
            'weight.numeric'       => 'Berat harus berupa angka.',
            'description.required' => 'Deskripsi kondisi wajib diisi.',
            'latitude.required'    => 'Silakan klik peta untuk menentukan lokasi.',
            'longitude.required'   => 'Silakan klik peta untuk menentukan lokasi.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'pending'; // Disesuaikan dengan status default sistem penjelajahan peta

        $this->textileRepo->store($validated);

        return redirect('/')->with('success', '🎉 Limbah berhasil diposting! Terima kasih telah berkontribusi untuk bumi.');
    }

    /**
     * Upcycler mengklaim limbah yang tersedia.
     */
    public function claim(int $id): RedirectResponse
    {
        $success = $this->textileRepo->claimTextile($id, Auth::id());

        if ($success) {
            return redirect('/')->with('success', '✂️ Klaim berhasil! Limbah siap dijemput dan diupcycle.');
        }

        return redirect('/')->with('error', '⚠️ Maaf, limbah ini sudah diklaim oleh penjahit lain.');
    }

    /**
     * Upcycler update status produksi dan opsional upload foto hasil karya.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status'        => 'required|in:processing,completed',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        $this->textileRepo->updateStatus($id, $validated['status'], $imagePath);

        $msg = $validated['status'] === 'completed'
            ? '✅ Selamat! Produk berhasil diselesaikan dan ditambahkan ke galeri.'
            : '🔵 Status diperbarui: sedang dalam proses produksi.';

        return redirect('/')->with('success', $msg);
    }
}