@extends('layouts.dashboard')
@section('title', 'Pesanan Saya')

@push('styles')
<style>
.ord-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.ord-empty { text-align:center; padding:60px 20px; color:#aaa; }
.ord-empty-icon { font-size:56px; margin-bottom:12px; }
.ord-card {
    background:#fff; border:1.5px solid #C0DD97; border-radius:16px;
    overflow:hidden; margin-bottom:16px;
    transition:.2s; box-shadow:0 2px 0 #C0DD97;
}
.ord-card-head {
    background:#F7F4D5; padding:12px 20px;
    display:flex; justify-content:space-between; align-items:center;
    border-bottom:1px solid #EAF3DE;
}
.ord-id { font-family:'Syne',sans-serif; font-size:13px; font-weight:800; color:#0A3323; }
.ord-date { font-size:11px; color:#888; }
.ord-status {
    font-size:11px; font-weight:800; padding:4px 12px;
    border-radius:100px;
}
.ord-body { display:flex; gap:16px; align-items:flex-start; padding:16px 20px; }
.ord-img { width:90px; height:90px; object-fit:cover; border-radius:10px; flex-shrink:0; }
.ord-img-ph { width:90px; height:90px; background:#f3f4f6; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:32px; flex-shrink:0; }
.ord-info { flex:1; }
.ord-prod-name { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#0A3323; margin-bottom:4px; }
.ord-upcycler { font-size:12px; color:#888; margin-bottom:10px; }
.ord-price { font-size:18px; font-weight:800; color:#3B6D11; }
.ord-payment-chip {
    display:inline-flex; align-items:center; gap:5px;
    font-size:11px; font-weight:700; padding:3px 10px;
    border-radius:100px; margin-left:10px;
    background:#f0fdf4; color:#166534;
}
.ord-footer {
    padding:12px 20px; background:#FBFDF8;
    border-top:1px solid #EAF3DE;
    display:flex; flex-direction:column; gap:10px;
}
.wa-btn {
    display:inline-flex; align-items:center; gap:8px;
    background:#25D366; color:#fff;
    padding:11px 20px; border-radius:10px;
    font-family:'Syne',sans-serif; font-size:13px; font-weight:800;
    text-decoration:none; width:fit-content;
    box-shadow:0 3px 0 #128c3e; transition:.2s;
}
.wa-btn:hover { transform:translateY(-2px); box-shadow:0 5px 0 #128c3e; }
.alert-pay {
    background:#fef9c3; border:1px solid #fde047;
    border-radius:10px; padding:12px 16px;
    font-size:12px; color:#854d0e;
    line-height:1.7;
}
.alert-done {
    background:#f0fdf4; border:1px solid #86efac;
    border-radius:10px; padding:12px 16px;
    font-size:12px; color:#166534;
}
.alert-ship {
    background:#eff6ff; border:1px solid #93c5fd;
    border-radius:10px; padding:12px 16px;
    font-size:12px; color:#1d4ed8;
}
</style>
@endpush

@section('content')

<div class="ord-header">
    <div>
        <div class="pg-title">📦 Pesanan Saya</div>
        <div class="pg-sub">Pantau status semua pesanan produk upcycle kamu</div>
    </div>
    <a href="{{ route('contributor.dashboard') }}#sec-gallery" class="btn-primary">+ Beli Produk Lagi</a>
</div>

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #86efac;border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:13px;color:#166534;font-weight:700;">
    ✅ {{ session('success') }}
</div>
@endif

@forelse($orders as $order)
@php
    $p = $order->product;
    $upcycler = $p?->upcycler;
    $statusMap = [
        'pending'    => ['label'=>'⏳ Menunggu Pembayaran','bg'=>'#fef9c3','color'=>'#a16207'],
        'paid'       => ['label'=>'✅ Lunas','bg'=>'#dcfce7','color'=>'#166534'],
        'processing' => ['label'=>'⚙️ Diproses','bg'=>'#dbeafe','color'=>'#1d4ed8'],
        'shipped'    => ['label'=>'🚚 Dikirim','bg'=>'#ccfbf1','color'=>'#0f766e'],
        'done'       => ['label'=>'🎉 Selesai','bg'=>'#f0fdf4','color'=>'#166534'],
        'cancelled'  => ['label'=>'❌ Dibatalkan','bg'=>'#fee2e2','color'=>'#991b1b'],
    ];
    $s = $statusMap[$order->status] ?? ['label'=>ucfirst($order->status),'bg'=>'#f3f4f6','color'=>'#6b7280'];
    $waMsg = urlencode(
        "Halo Kak *{$upcycler?->name}*, saya *" . auth()->user()->name . "* ingin mengonfirmasi pembayaran untuk:\n\n" .
        "📦 Pesanan: *#ORD-" . str_pad($order->id,4,'0',STR_PAD_LEFT) . "*\n" .
        "🛍️ Produk: *{$p?->display_name}*\n" .
        "💰 Total: *Rp" . number_format($order->total_price,0,',','.') . "*\n" .
        "📍 Kirim ke: {$order->city}, {$order->address}\n\n" .
        "Mohon infokan nomor rekening/e-wallet untuk transfer. Terima kasih! 🙏"
    );
    $waPhone = $upcycler?->whatsapp ? '62'.ltrim($upcycler->whatsapp,'0') : null;
@endphp

<div class="ord-card">
    {{-- Header --}}
    <div class="ord-card-head">
        <div>
            <div class="ord-id">#ORD-{{ str_pad($order->id,4,'0',STR_PAD_LEFT) }}</div>
            <div class="ord-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
        <span class="ord-status" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};">{{ $s['label'] }}</span>
    </div>

    {{-- Product info --}}
    <div class="ord-body">
        @if($p?->photo)
            <img src="{{ asset('storage/'.$p->photo) }}" class="ord-img" alt="{{ $p->display_name }}">
        @else
            <div class="ord-img-ph">🎨</div>
        @endif
        <div class="ord-info">
            <div class="ord-prod-name">{{ $p?->display_name ?? 'Produk Dihapus' }}</div>
            <div class="ord-upcycler">✂️ oleh {{ $upcycler?->name ?? '—' }}</div>
            <div>
                <span class="ord-price">Rp{{ number_format($order->total_price,0,',','.') }}</span>
                <span class="ord-payment-chip">
                    {{ $order->payment_method === 'koin' ? '🪙 Bayar Koin' : '🏦 Transfer' }}
                </span>
            </div>
            <div style="font-size:11px;color:#aaa;margin-top:8px;">
                Dikirim ke: {{ $order->recipient_name }} · {{ $order->city }}
            </div>
        </div>
    </div>

    {{-- Footer: aksi berdasarkan status --}}
    <div class="ord-footer">
        @if($order->status === 'pending' && $order->payment_method === 'transfer' && $waPhone)
        <div class="alert-pay">
            <strong>💳 Aksi Diperlukan!</strong> Pesanan ini belum dibayar. Hubungi penjual sekarang untuk mendapatkan nomor rekening / e-wallet mereka.
        </div>
        <a class="wa-btn" href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Konfirmasi Pembayaran via WhatsApp
        </a>

        @elseif($order->status === 'pending' && $order->payment_method === 'transfer' && !$waPhone)
        <div class="alert-pay">
            <strong>💳 Aksi Diperlukan!</strong> Hubungi penjual untuk mendapatkan nomor rekening pembayaran.
        </div>

        @elseif($order->status === 'paid')
        <div class="alert-done">
            <strong>✅ Pembayaran Diterima!</strong> Penjual sedang mempersiapkan produk Anda. Tunggu informasi pengiriman dari mereka.
            @if($waPhone)
            <br><a href="https://wa.me/{{ $waPhone }}" target="_blank" style="color:#166534;font-weight:700;">💬 Chat Penjual</a>
            @endif
        </div>

        @elseif($order->status === 'processing')
        <div class="alert-pay" style="background:#eff6ff;border-color:#93c5fd;color:#1d4ed8;">
            <strong>⚙️ Sedang Diproses!</strong> Penjual sedang mengemas produk Anda.
            @if($waPhone)
            <br><a href="https://wa.me/{{ $waPhone }}" target="_blank" style="color:#1d4ed8;font-weight:700;">💬 Chat Penjual</a>
            @endif
        </div>

        @elseif($order->status === 'shipped')
        <div class="alert-ship">
            <strong>🚚 Pesanan Dikirim!</strong> Produk sedang dalam perjalanan menuju alamat Anda.
            @if($waPhone)
            <br><a href="https://wa.me/{{ $waPhone }}" target="_blank" style="color:#1d4ed8;font-weight:700;">💬 Tanya Nomor Resi</a>
            @endif
        </div>

        @elseif($order->status === 'done')
        <div class="alert-done">
            <strong>🎉 Pesanan Selesai!</strong> Produk telah diterima. Terima kasih sudah mendukung upcycling!
        </div>
        @endif
    </div>
</div>
@empty
<div class="ord-empty">
    <div class="ord-empty-icon">🛍️</div>
    <div style="font-size:16px;font-weight:700;color:#0A3323;margin-bottom:8px;">Belum ada pesanan</div>
    <div style="font-size:13px;margin-bottom:20px;">Yuk belanja produk daur ulang dari para mitra penjahit kami!</div>
    <a href="{{ route('contributor.dashboard') }}#sec-gallery" class="btn-primary">🛒 Lihat Produk</a>
</div>
@endforelse

@endsection
