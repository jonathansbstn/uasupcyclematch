@extends('layouts.dashboard')
@section('title', 'Riwayat Pesanan')

@section('content')
<div class="top-bar" style="margin-bottom:20px;">
    <div>
        <div class="pg-title">📦 Riwayat Pesanan Saya</div>
        <div class="pg-sub">Lacak status pesanan produk hasil upcycle Anda</div>
    </div>
    <a href="{{ route('contributor.dashboard') }}#sec-gallery" class="btn-primary">← Beli Produk Lain</a>
</div>

<div class="card">
    @if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;font-weight:600;">
        {{ session('success') }}
    </div>
    @endif

    @forelse($orders as $order)
    <div style="border:1px solid var(--beige2);border-radius:12px;padding:16px;margin-bottom:16px;background:var(--beige);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;border-bottom:1px solid #e5e7eb;padding-bottom:12px;">
            <div style="font-size:12px;color:var(--muted);">
                <span style="font-weight:700;color:var(--dk);">Pesanan #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span> &nbsp;·&nbsp; {{ $order->created_at->format('d M Y, H:i') }}
            </div>
            <span style="background:{{ $order->status_bg }};color:{{ $order->status_color }};padding:4px 12px;border-radius:100px;font-size:11px;font-weight:700;">
                {{ $order->status_label }}
            </span>
        </div>
        
        <div style="display:flex;gap:16px;align-items:flex-start;">
            @if($order->product->photo)
            <img src="{{ asset('storage/'.$order->product->photo) }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
            @else
            <div style="width:80px;height:80px;background:#e5e7eb;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:24px;">🎨</div>
            @endif
            
            <div style="flex:1;">
                <div style="font-weight:800;font-size:15px;color:var(--dk);margin-bottom:4px;">{{ $order->product->display_name }}</div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:8px;">Penjual: {{ $order->product->upcycler->name ?? 'Upcycler' }}</div>
                <div style="font-size:14px;font-weight:700;color:var(--moss);">Rp{{ number_format($order->total_price, 0, ',', '.') }}</div>
            </div>
            
            <div style="text-align:right;font-size:12px;color:var(--dk);">
                <div style="margin-bottom:4px;"><span style="color:var(--muted);">Pembayaran:</span> <br> <strong>{{ $order->payment_method === 'koin' ? 'Koin UpcycleMatch' : 'Transfer Manual' }}</strong></div>
                <div><span style="color:var(--muted);">Penerima:</span> <br> <strong>{{ $order->recipient_name }}</strong></div>
            </div>
        </div>

        @if($order->payment_method === 'transfer' && $order->status === 'pending')
        <div style="margin-top:16px;padding:12px;background:#fef9c3;border:1px solid #fde047;border-radius:8px;font-size:12px;color:#854d0e;">
            <strong>Aksi Diperlukan:</strong> Silakan hubungi penjual (Upcycler) untuk melakukan transfer pembayaran dan mengatur pengiriman.
            @if($order->product->upcycler->whatsapp)
            <br>
            <a href="https://wa.me/62{{ ltrim($order->product->upcycler->whatsapp, '0') }}" target="_blank" style="display:inline-block;margin-top:8px;background:#25D366;color:#fff;padding:6px 12px;border-radius:6px;font-weight:700;text-decoration:none;">📱 Chat Penjual di WhatsApp</a>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div style="text-align:center;padding:40px;color:var(--muted);">
        <i class="ti ti-shopping-bag" style="font-size:40px;margin-bottom:12px;display:block;color:#cbd5e1;"></i>
        Belum ada riwayat pesanan.
    </div>
    @endforelse
</div>
@endsection
