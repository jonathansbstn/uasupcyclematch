<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\KoinTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** Halaman checkout — tampilkan detail produk + form */
    public function checkout(Product $product)
    {
        if ($product->status !== 'published') {
            abort(404, 'Produk tidak tersedia.');
        }
        $product->load(['upcycler', 'textile']);
        $user = auth()->user();
        return view('contributor.checkout', compact('product', 'user'));
    }

    /** Proses pembelian */
    public function store(Request $request, Product $product)
    {
        if ($product->status !== 'published') {
            return back()->withErrors(['error' => 'Produk tidak tersedia.']);
        }

        $data = $request->validate([
            'recipient_name'  => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'address'         => 'required|string|max:500',
            'city'            => 'required|string|max:100',
            'payment_method'  => 'required|in:transfer,koin',
            'notes'           => 'nullable|string|max:500',
        ]);

        $user  = auth()->user();
        $price = $product->price;

        // Jika bayar pakai koin, pastikan saldo cukup
        if ($data['payment_method'] === 'koin') {
            $koinNeeded = ceil($price / 2500); // 1 koin = Rp2.500
            if ($user->koin < $koinNeeded) {
                return back()->withErrors(['error' => "Koin tidak cukup. Butuh {$koinNeeded} koin, kamu punya {$user->koin} koin."]);
            }
        }

        DB::transaction(function () use ($data, $product, $user, $price) {
            $order = Order::create([
                'buyer_id'       => $user->id,
                'product_id'     => $product->id,
                'quantity'       => 1,
                'total_price'    => $price,
                'recipient_name' => $data['recipient_name'],
                'recipient_phone'=> $data['recipient_phone'],
                'address'        => $data['address'],
                'city'           => $data['city'],
                'payment_method' => $data['payment_method'],
                'notes'          => $data['notes'] ?? null,
                'status'         => $data['payment_method'] === 'koin' ? 'paid' : 'pending',
                'paid_at'        => $data['payment_method'] === 'koin' ? now() : null,
            ]);

            // Potong koin jika bayar pakai koin
            if ($data['payment_method'] === 'koin') {
                $koinNeeded = ceil($price / 2500);
                $user->decrement('koin', $koinNeeded);
                KoinTransaction::create([
                    'user_id'    => $user->id,
                    'amount'     => -$koinNeeded,
                    'type'       => 'purchase',
                    'keterangan' => 'Beli produk: ' . $product->display_name,
                ]);
            }
        });

        return redirect()->route('contributor.orders')
            ->with('success', 'Pesanan berhasil dibuat! ' .
                ($data['payment_method'] === 'koin'
                    ? 'Pembayaran koin berhasil diproses.'
                    : 'Silakan transfer ke rekening penjual.'));
    }

    /** Daftar pesanan milik contributor yang login */
    public function myOrders()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->with(['product.upcycler'])
            ->latest()
            ->get();

        return view('contributor.orders', compact('orders'));
    }
}
