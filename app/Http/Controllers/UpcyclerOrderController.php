<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UpcyclerOrderController extends Controller
{
    /** Daftar pesanan masuk untuk upcycler yang login */
    public function index()
    {
        $user = auth()->user();

        $orders = Order::whereHas('product', fn($q) => $q->where('upcycler_id', $user->id))
            ->with(['product', 'buyer'])
            ->latest()
            ->get();

        $stats = [
            'pending'    => $orders->where('status', 'pending')->count(),
            'paid'       => $orders->where('status', 'paid')->count(),
            'processing' => $orders->where('status', 'processing')->count(),
            'done'       => $orders->where('status', 'done')->count(),
            'total_pendapatan' => $orders->whereIn('status', ['paid','processing','shipped','done'])->sum('total_price'),
        ];

        return view('upcycler.orders', compact('orders', 'stats', 'user'));
    }

    /** Update status pesanan */
    public function updateStatus(Request $request, Order $order)
    {
        $user = auth()->user();

        // Pastikan order ini milik produk yang dibuat upcycler ini
        if ($order->product->upcycler_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $allowed = ['processing', 'shipped', 'done', 'cancelled'];
        $newStatus = $request->validate([
            'status' => 'required|in:' . implode(',', $allowed),
        ])['status'];

        $order->update(['status' => $newStatus]);

        $label = match($newStatus) {
            'processing' => 'Pesanan sedang diproses.',
            'shipped'    => 'Pesanan telah dikirim. Pembeli akan menerima notifikasi.',
            'done'       => 'Pesanan selesai.',
            'cancelled'  => 'Pesanan dibatalkan.',
        };

        return back()->with('success', $label);
    }

    /** Cairkan saldo ke WhatsApp Admin */
    public function requestWithdraw()
    {
        $user = auth()->user();
        $saldo = $user->saldo;

        if ($saldo <= 0) {
            return back()->with('error', 'Saldo Anda kosong, tidak bisa dicairkan.');
        }

        // Buat pesan WA ke Admin
        $msg = urlencode(
            "Halo Admin UpcycleMatch, saya *{$user->name}* ingin mencairkan saldo pendapatan sebesar *Rp" . number_format($saldo, 0, ',', '.') . "*.\n" .
            "Akun saya: {$user->email}\n" .
            "Mohon konfirmasinya. Terima kasih 🙏"
        );

        // Nomor WA admin dari .env
        $adminWa = env('ADMIN_WHATSAPP', '6281234567890');

        return redirect("https://wa.me/{$adminWa}?text={$msg}");
    }
}
