<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\KoinTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** Halaman checkout */
    public function checkout(Product $product)
    {
        if ($product->status !== 'published') {
            return redirect()->route('contributor.dashboard')
                ->with('error', 'Produk tidak tersedia atau sudah tidak dijual.');
        }
        $product->load(['upcycler', 'textile']);
        $user = auth()->user();
        return view('contributor.checkout', compact('product', 'user'));
    }

    /** Proses pembelian */
    public function store(Request $request, Product $product)
    {
        if ($product->status !== 'published') {
            return back()->with('error', 'Produk tidak tersedia.');
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
        $koinNeeded = ceil($price / 2500);

        if ($data['payment_method'] === 'koin' && $user->koin < $koinNeeded) {
            return back()->withErrors(['error' => "Koin tidak cukup. Butuh {$koinNeeded} koin, kamu punya {$user->koin} koin."])->withInput();
        }

        $order = DB::transaction(function () use ($data, $product, $user, $price, $koinNeeded) {
            $isPaidByKoin = $data['payment_method'] === 'koin';

            $order = Order::create([
                'buyer_id'        => $user->id,
                'product_id'      => $product->id,
                'quantity'        => 1,
                'total_price'     => $price,
                'recipient_name'  => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'address'         => $data['address'],
                'city'            => $data['city'],
                'payment_method'  => $data['payment_method'],
                'notes'           => $data['notes'] ?? null,
                'status'          => $isPaidByKoin ? 'paid' : 'pending',
                'paid_at'         => $isPaidByKoin ? now() : null,
            ]);

            if ($isPaidByKoin) {
                // Potong koin dari pembeli
                $user->decrement('koin', $koinNeeded);
                KoinTransaction::create([
                    'user_id'    => $user->id,
                    'amount'     => -$koinNeeded,
                    'type'       => 'purchase',
                    'keterangan' => 'Beli produk: ' . $product->display_name,
                ]);

                // Tambah saldo pendapatan ke Upcycler
                $upcycler = $product->upcycler;
                if ($upcycler) {
                    $upcycler->increment('saldo', $price);
                }
            }

            return $order;
        });

        return redirect()->route('contributor.orders')
            ->with('success', 'Pesanan #ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' berhasil dibuat!')
            ->with('new_order_id', $order->id);
    }

    /** Daftar pesanan milik contributor */
    public function myOrders()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->with(['product.upcycler'])
            ->latest()
            ->get();

        return view('contributor.orders', compact('orders'));
    }
}
