@extends('layouts.dashboard')
@section('title', 'Pesanan Masuk')

@push('styles')
<style>
/* ===== UPCYCLER ORDERS ===== */
.upo-grid { display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start; }
@media(max-width:860px){ .upo-grid { grid-template-columns:1fr; } }

/* KPI Bar */
.upo-kpi { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
@media(max-width:700px){ .upo-kpi { grid-template-columns:repeat(2,1fr); } }
.upo-kpi-card {
    background:#fff; border:1.5px solid #C0DD97; border-radius:14px;
    padding:16px; text-align:center;
}
.upo-kpi-num { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; color:#0A3323; }
.upo-kpi-lbl { font-size:11px; color:#888; margin-top:2px; }

/* Order card */
.upo-card {
    background:#fff; border:1.5px solid #C0DD97;
    border-radius:16px; overflow:hidden; margin-bottom:14px;
    box-shadow:0 2px 0 #C0DD97; transition:.2s;
}
.upo-card-head {
    background:#F7F4D5; padding:11px 18px;
    display:flex; justify-content:space-between; align-items:center;
    border-bottom:1px solid #EAF3DE;
}
.upo-card-body { padding:16px 18px; display:flex; gap:14px; align-items:flex-start; }
.upo-card-foot { padding:12px 18px; background:#FBFDF8; border-top:1px solid #EAF3DE; }
.upo-prod-img { width:80px; height:80px; object-fit:cover; border-radius:10px; flex-shrink:0; }
.upo-prod-ph  { width:80px; height:80px; background:#f3f4f6; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:28px; flex-shrink:0; }

.upo-status { font-size:11px; font-weight:800; padding:4px 12px; border-radius:100px; }

/* Status update dropdown */
.upo-status-form { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
.upo-select {
    flex:1; min-width:160px; padding:8px 12px;
    border:1.5px solid #C0DD97; border-radius:8px;
    font-family:'DM Sans',sans-serif; font-size:12px; color:#0A3323;
    background:#FBFDF8; cursor:pointer; outline:none;
}
.upo-act-btn {
    padding:8px 16px; border:none; border-radius:8px;
    font-family:'Syne',sans-serif; font-size:12px; font-weight:800;
    background:#0A3323; color:#F7F4D5; cursor:pointer;
    white-space:nowrap; transition:.2s;
}
.upo-act-btn:hover { background:#3B6D11; }

/* Wallet card */
.wallet-big {
    background:linear-gradient(135deg,#0A3323 60%,#3B6D11);
    border-radius:18px; padding:24px; color:#F7F4D5;
    position:sticky; top:80px;
}
.wallet-title { font-size:12px; color:#9FE1CB; font-weight:600; letter-spacing:.05em; text-transform:uppercase; margin-bottom:8px; }
.wallet-amount { font-family:'Syne',sans-serif; font-size:34px; font-weight:800; color:#C0DD97; margin-bottom:4px; }
.wallet-sub { font-size:12px; color:#9FE1CB; margin-bottom:20px; }
.withdraw-btn {
    display:flex; align-items:center; justify-content:center; gap:8px;
    width:100%; padding:13px; border-radius:10px;
    background:#839958; color:#0A3323; border:none;
    font-family:'Syne',sans-serif; font-size:13px; font-weight:800;
    cursor:pointer; text-decoration:none;
    box-shadow:0 4px 0 #3B6D11; transition:.2s;
}
.withdraw-btn:hover { transform:translateY(-2px); box-shadow:0 6px 0 #3B6D11; }
.wallet-info { font-size:11px; color:#9FE1CB; text-align:center; margin-top:10px; line-height:1.7; }

/* Status colors */
.s-pending    { background:#fef9c3; color:#a16207; }
.s-paid       { background:#dcfce7; color:#166534; }
.s-processing { background:#dbeafe; color:#1d4ed8; }
.s-shipped    { background:#ccfbf1; color:#0f766e; }
.s-done       { background:#f0fdf4; color:#166534; }
.s-cancelled  { background:#fee2e2; color:#991b1b; }

.buyer-addr { font-size:11px; color:#888; margin-top:6px; line-height:1.6; }
.new-badge { 
    display:inline-block; background:#ef4444; color:#fff; 
    font-size:9px; font-weight:800; padding:2px 7px; border-radius:100px; 
    margin-left:6px; animation:pulse 1.5s infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
</style>
@endpush

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <div class="pg-title">🛒 Pesanan Masuk</div>
        <div class="pg-sub">Kelola pesanan produk karya Anda dari para pembeli</div>
    </div>
</div>

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #86efac;border-radius:12px;padding:12px 18px;margin-bottom:20px;font-size:13px;color:#166534;font-weight:700;">
    ✅ {{ session('success') }}
</div>
@endif

{{-- KPI --}}
<div class="upo-kpi">
    <div class="upo-kpi-card">
        <div class="upo-kpi-num" style="color:#a16207;">{{ $stats['pending'] }}</div>
        <div class="upo-kpi-lbl">Menunggu Bayar</div>
    </div>
    <div class="upo-kpi-card">
        <div class="upo-kpi-num" style="color:#166534;">{{ $stats['paid'] }}</div>
        <div class="upo-kpi-lbl">Sudah Lunas</div>
    </div>
    <div class="upo-kpi-card">
        <div class="upo-kpi-num" style="color:#1d4ed8;">{{ $stats['processing'] }}</div>
        <div class="upo-kpi-lbl">Diproses</div>
    </div>
    <div class="upo-kpi-card">
        <div class="upo-kpi-num" style="color:#3B6D11;">{{ $stats['done'] }}</div>
        <div class="upo-kpi-lbl">Selesai</div>
    </div>
</div>

{{-- Main Grid --}}
<div class="upo-grid">

    {{-- ===== LEFT: ORDER LIST ===== --}}
    <div>
        @forelse($orders as $order)
        @php
            $buyer = $order->buyer;
            $p     = $order->product;
            $statusClass = 's-' . $order->status;
            $statusLabel = [
                'pending'    => '⏳ Menunggu Pembayaran',
                'paid'       => '✅ Lunas — Siap Diproses',
                'processing' => '⚙️ Sedang Diproses',
                'shipped'    => '🚚 Dikirim',
                'done'       => '🎉 Selesai',
                'cancelled'  => '❌ Dibatalkan',
            ][$order->status] ?? $order->status;

            $waMsg = urlencode(
                "Halo *{$buyer?->name}*, pesanan Anda *#ORD-" . str_pad($order->id,4,'0',STR_PAD_LEFT) . "* untuk produk *{$p?->display_name}* sudah kami terima.\n\n" .
                ($order->status === 'pending'
                    ? "Mohon lakukan pembayaran sebesar *Rp" . number_format($order->total_price,0,',','.') . "* ke:\n📲 [Nomor Rekening/E-Wallet Anda]\n\nSetelah transfer, mohon konfirmasi di sini ya. Terima kasih! 🙏"
                    : "Terima kasih atas pesanannya! Kami sedang memproses pengiriman. Nanti kami kabari lagi ya. 😊"
                )
            );
            $waPhone = $buyer?->whatsapp ? '62'.ltrim($buyer->whatsapp,'0') : null;
        @endphp

        <div class="upo-card">
            {{-- Head --}}
            <div class="upo-card-head">
                <div>
                    <span style="font-family:'Syne',sans-serif;font-size:13px;font-weight:800;color:#0A3323;">
                        #ORD-{{ str_pad($order->id,4,'0',STR_PAD_LEFT) }}
                    </span>
                    @if($order->status === 'paid' || $order->status === 'pending')
                    <span class="new-badge">BARU</span>
                    @endif
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:11px;color:#aaa;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    <span class="upo-status {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
            </div>

            {{-- Body --}}
            <div class="upo-card-body">
                @if($p?->photo)
                    <img src="{{ asset('storage/'.$p->photo) }}" class="upo-prod-img" alt="">
                @else
                    <div class="upo-prod-ph">🎨</div>
                @endif
                <div style="flex:1;">
                    <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:14px;color:#0A3323;margin-bottom:2px;">{{ $p?->display_name ?? 'Produk' }}</div>
                    <div style="font-size:18px;font-weight:800;color:#3B6D11;margin-bottom:10px;">
                        Rp{{ number_format($order->total_price,0,',','.') }}
                        <span style="font-size:11px;font-weight:600;background:{{ $order->payment_method === 'koin' ? '#f0fdf4' : '#eff6ff' }};color:{{ $order->payment_method === 'koin' ? '#166534' : '#1d4ed8' }};padding:2px 8px;border-radius:100px;margin-left:6px;">
                            {{ $order->payment_method === 'koin' ? '🪙 Koin' : '🏦 Transfer' }}
                        </span>
                    </div>

                    {{-- Buyer info --}}
                    <div style="background:#F7F4D5;border-radius:8px;padding:10px 12px;">
                        <div style="font-size:12px;font-weight:700;color:#0A3323;margin-bottom:4px;">📦 Kirim ke:</div>
                        <div class="buyer-addr">
                            <strong>{{ $order->recipient_name }}</strong> · {{ $order->recipient_phone }}<br>
                            {{ $order->address }}, {{ $order->city }}
                        </div>
                        @if($order->notes)
                        <div style="font-size:11px;color:#666;margin-top:4px;font-style:italic;">Catatan: {{ $order->notes }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer: actions --}}
            <div class="upo-card-foot">
                <div class="upo-status-form">
                    @if($order->status !== 'done' && $order->status !== 'cancelled')
                    <form method="POST" action="{{ route('upcycler.orders.status', $order->id) }}" style="display:flex;gap:8px;flex:1;min-width:0;">
                        @csrf @method('PATCH')
                        <select name="status" class="upo-select">
                            @if($order->status === 'paid' || $order->status === 'pending')
                            <option value="processing">⚙️ Tandai Sedang Diproses</option>
                            @endif
                            @if(in_array($order->status, ['paid','processing','pending']))
                            <option value="shipped">🚚 Tandai Sudah Dikirim</option>
                            @endif
                            @if(in_array($order->status, ['paid','processing','shipped']))
                            <option value="done">🎉 Tandai Selesai</option>
                            @endif
                            <option value="cancelled">❌ Batalkan Pesanan</option>
                        </select>
                        <button type="submit" class="upo-act-btn">Simpan</button>
                    </form>
                    @else
                    <div style="font-size:12px;color:#888;font-style:italic;">Pesanan ini sudah {{ $order->status === 'done' ? 'selesai ✅' : 'dibatalkan ❌' }}.</div>
                    @endif

                    {{-- WA to buyer --}}
                    @if($waPhone)
                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank"
                       style="display:inline-flex;align-items:center;gap:6px;background:#25D366;color:#fff;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat Pembeli
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:16px;border:1.5px solid #C0DD97;">
            <div style="font-size:52px;margin-bottom:12px;">📭</div>
            <div style="font-size:16px;font-weight:700;color:#0A3323;margin-bottom:8px;">Belum ada pesanan masuk</div>
            <div style="font-size:13px;color:#888;">Upload dan publish produk Anda, lalu pembeli akan mulai berdatangan!</div>
            <a href="{{ route('upcycler.production') }}" class="btn-primary" style="display:inline-block;margin-top:16px;">⚙️ Ke Halaman Produksi</a>
        </div>
        @endforelse
    </div>

    {{-- ===== RIGHT: WALLET ===== --}}
    <div>
        <div class="wallet-big">
            <div class="wallet-title">💰 Saldo Pendapatan</div>
            <div class="wallet-amount">Rp{{ number_format($user->saldo, 0, ',', '.') }}</div>
            <div class="wallet-sub">
                Dari {{ $stats['done'] + $orders->where('payment_method','koin')->whereIn('status',['paid','processing','shipped','done'])->count() }} transaksi koin lunas
            </div>

            @if($user->saldo > 0)
            <a href="{{ route('upcycler.orders.withdraw') }}" class="withdraw-btn">
                📲 Cairkan Dana via WhatsApp Admin
            </a>
            <div class="wallet-info">
                Permintaan pencairan akan dikirim ke WhatsApp Admin.<br>
                Dana akan ditransfer dalam 1×24 jam kerja.
            </div>
            @else
            <div style="background:rgba(255,255,255,.06);border-radius:10px;padding:12px;text-align:center;font-size:12px;color:#9FE1CB;margin-top:4px;">
                Saldo akan terisi otomatis saat ada pembeli yang membayar menggunakan Koin UpcycleMatch.
            </div>
            @endif
        </div>

        {{-- Rekam transaksi --}}
        <div style="background:#fff;border:1.5px solid #C0DD97;border-radius:16px;padding:18px;margin-top:16px;">
            <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:800;color:#0A3323;margin-bottom:12px;">📊 Ringkasan Penjualan</div>
            @php
                $totalPendapatan = $orders->whereIn('status',['paid','processing','shipped','done'])->sum('total_price');
                $orderByKoin = $orders->where('payment_method','koin')->count();
                $orderByTransfer = $orders->where('payment_method','transfer')->count();
            @endphp
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #EAF3DE;font-size:13px;">
                <span style="color:#888;">Total Pendapatan</span>
                <span style="font-weight:800;color:#3B6D11;">Rp{{ number_format($totalPendapatan,0,',','.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #EAF3DE;font-size:13px;">
                <span style="color:#888;">Bayar via Koin</span>
                <span style="font-weight:700;color:#0A3323;">{{ $orderByKoin }} pesanan</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13px;">
                <span style="color:#888;">Bayar via Transfer</span>
                <span style="font-weight:700;color:#0A3323;">{{ $orderByTransfer }} pesanan</span>
            </div>
        </div>
    </div>
</div>

@endsection
