<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Textile;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        $totalWeight    = Textile::whereIn('status', ['claimed', 'processing', 'completed'])->sum('weight');
        $totalProducts  = Product::count();
        $totalUpcyclers = User::where('role', 'upcycler')->count();
        $materialSavings = (float) $totalWeight * 50000;

        // Monthly data for charts (last 12 months)
        $months = collect(range(11, 0))->map(fn($i) => now()->subMonths($i)->format('Y-m'));

        $monthlyWaste = Textile::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(weight) as total')
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
            ->pluck('total', 'month');

        $monthlyProducts = Product::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
            ->pluck('total', 'month');

        $statusDist = Textile::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $chartMonths       = $months->values();
        $chartWasteData    = $months->map(fn($m) => (float) ($monthlyWaste[$m] ?? 0))->values();
        $chartProductData  = $months->map(fn($m) => (int) ($monthlyProducts[$m] ?? 0))->values();
        $chartStatusLabels = $statusDist->keys();
        $chartStatusData   = $statusDist->values();

        return view('admin.analytics', compact(
            'totalWeight', 'totalProducts', 'totalUpcyclers', 'materialSavings',
            'chartMonths', 'chartWasteData', 'chartProductData',
            'chartStatusLabels', 'chartStatusData'
        ));
    }
}
