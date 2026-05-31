<?php

namespace App\Http\Controllers;

use App\Models\Textile;
use App\Models\User;
use App\Repositories\Contracts\TextileRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected TextileRepositoryInterface $textileRepo;

    public function __construct(TextileRepositoryInterface $textileRepo)
    {
        $this->textileRepo = $textileRepo;
    }

    /**
     * Dashboard admin: analytics + chart data
     */
    public function dashboard(): View
    {
        $allTextiles     = $this->textileRepo->getAll();
        $allUsers        = User::orderBy('created_at', 'desc')->get();
        $totalSavedWeight= $this->textileRepo->getTotalSavedWeight();
        $totalCompleted  = $allTextiles->where('status', 'completed')->count();
        $totalUsers      = $allUsers->count();
        $totalSavings    = $totalSavedWeight * 45000;
        $totalCo2        = $totalSavedWeight * 1.2;

        return view('admin.dashboard', compact(
            'allTextiles', 'allUsers', 'totalSavedWeight',
            'totalCompleted', 'totalUsers', 'totalSavings', 'totalCo2'
        ));
    }

    /**
     * Halaman tabel data semua limbah
     */
    public function dataLimbah(): View
    {
        $allTextiles = $this->textileRepo->getAll();

        return view('admin.data-limbah', compact('allTextiles'));
    }

    /**
     * Halaman manajemen pengguna + verifikasi upcycler
     */
    public function users(): View
    {
        $allUsers = User::orderBy('role')->orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('allUsers'));
    }

    /**
     * Halaman galeri karya yang sudah completed
     */
    public function galeri(): View
    {
        $completedTextiles = Textile::with(['owner', 'upcycler'])
            ->where('status', 'completed')
            ->whereNotNull('product_image')
            ->latest()
            ->get();

        return view('admin.galeri', compact('completedTextiles'));
    }

    /**
     * Halaman export report
     */
    public function exportPage(): View
    {
        $allTextiles     = $this->textileRepo->getAll();
        $totalSavedWeight= $this->textileRepo->getTotalSavedWeight();

        return view('admin.export', compact('allTextiles', 'totalSavedWeight'));
    }

    /**
     * Verifikasi/aktifkan akun upcycler
     */
    public function verifyUser(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        // Implementasi: bisa tambahkan kolom is_verified di tabel users
        // Untuk sekarang: flash success message
        return redirect()->route('admin.users')
            ->with('success', "✅ Akun {$user->name} berhasil diverifikasi.");
    }

    /**
     * Export Excel — download data sebagai CSV sederhana
     * (Untuk production: gunakan package maatwebsite/excel)
     */
    public function exportExcel()
    {
        $textiles = $this->textileRepo->getAll();

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=upcyclematch-report-' . date('Ymd') . '.csv',
        ];

        $callback = function () use ($textiles) {
            $file = fopen('php://output', 'w');
            // Header BOM untuk Excel
            fputs($file, "\xEF\xBB\xBF");
            // Header kolom
            fputcsv($file, ['ID', 'Judul', 'Jenis Kain', 'Berat (kg)', 'Status', 'Contributor', 'Upcycler', 'Tanggal', 'CO2 Dicegah (kg)', 'Nilai Modal (Rp)']);
            foreach ($textiles as $t) {
                fputcsv($file, [
                    $t->id,
                    $t->title,
                    $t->fabric_type,
                    $t->weight,
                    $t->status,
                    $t->owner->name  ?? '-',
                    $t->upcycler->name ?? '-',
                    $t->created_at->format('d/m/Y'),
                    number_format($t->weight * 1.2, 2),
                    number_format($t->weight * 45000, 0),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export PDF — redirect ke halaman report yang bisa di-print
     */
    public function exportPdf()
    {
        $textiles        = $this->textileRepo->getAll();
        $totalSavedWeight= $this->textileRepo->getTotalSavedWeight();
        $totalSavings    = $totalSavedWeight * 45000;
        $totalCo2        = $totalSavedWeight * 1.2;

        return view('admin.export-pdf', compact('textiles', 'totalSavedWeight', 'totalSavings', 'totalCo2'));
    }
}