<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Textile;
use App\Models\User;
use App\Models\WasteClaim;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_limbah'   => Textile::count(),
            'total_produk'   => Product::count(),
            'total_umkm'     => User::where('role', 'upcycler')->count(),
            'total_berat'    => (float) Textile::whereIn('status', ['claimed','processing','completed'])->sum('weight'),
        ];
        $summary['total_penghematan'] = $summary['total_berat'] * 50000;

        $textiles = Textile::with('owner', 'upcycler')->latest()->get();
        $claims   = WasteClaim::with('textile', 'upcycler')->latest()->get();
        $products = Product::with('upcycler', 'textile')->latest()->get();

        return view('admin.report', compact('summary', 'textiles', 'claims', 'products'));
    }

    public function exportExcel()
    {
        // Export via Maatwebsite Excel
        if (!class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return back()->with('error', 'Package Excel belum terinstall.');
        }
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ReportExport(),
            'laporan-upcyclematch-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        $summary = [
            'total_limbah'      => Textile::count(),
            'total_produk'      => Product::count(),
            'total_umkm'        => User::where('role', 'upcycler')->count(),
            'total_berat'       => (float) Textile::whereIn('status', ['claimed','processing','completed'])->sum('weight'),
        ];
        $summary['total_penghematan'] = $summary['total_berat'] * 50000;

        $textiles = Textile::with('owner', 'upcycler')->latest()->take(100)->get();
        $products = Product::with('upcycler', 'textile')->latest()->take(100)->get();

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return back()->with('error', 'Package PDF belum terinstall.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report-pdf', compact('summary', 'textiles', 'products'));
        return $pdf->download('laporan-upcyclematch-' . now()->format('Y-m-d') . '.pdf');
    }
}
