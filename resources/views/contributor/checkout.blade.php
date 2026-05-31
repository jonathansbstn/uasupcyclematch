@extends('layouts.dashboard')
@section('title', 'Checkout Produk')
@push('styles')
<style>
.checkout-grid { display:grid; grid-template-columns:1.5fr 1fr; gap:24px; align-items:start; }
@media(max-width:768px){ .checkout-grid { grid-template-columns:1fr; } }
.prod-sum { display:flex; gap:16px; align-items:flex-start; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid var(--beige2); }
.prod-sum img { width:80px; height:80px; object-fit:cover; border-radius:10px; }
</style>
@endpush

@section('content')
<div class="top-bar" style="margin-bottom:20px;">
    <div>
        <div class="pg-title">🛍️ Checkout Produk</div>
        <div class="pg-sub">Selesaikan pesanan Anda untuk {{ $product->display_name }}</div>
    </div>
    <a href="{{ route('contributor.dashboard') }}#sec-gallery" class="btn-primary">← Batal</a>
</div>

<div class="checkout-grid">
    <div class="card">
        <div class="card-title">📝 Detail Pengiriman</div>
        <form id="formCheckout" action="{{ route('contributor.checkout.store', $product->id) }}" method="POST">
            @csrf
            <div class="fg">
                <label class="flabel">Nama Penerima *</label>
                <input type="text" name="recipient_name" class="finput" required value="{{ old('recipient_name', $user->name) }}">
            </div>
            <div class="fg">
                <label class="flabel">Nomor HP / WhatsApp *</label>
                <input type="text" name="recipient_phone" class="finput" required value="{{ old('recipient_phone', $user->whatsapp) }}">
            </div>
            <div class="fg">
                <label class="flabel">Kota / Kabupaten *</label>
                <input type="text" name="city" class="finput" required value="{{ old('city') }}" placeholder="Contoh: Jakarta Selatan">
            </div>
            <div class="fg">
                <label class="flabel">Alamat Lengkap *</label>
                <textarea name="address" class="ftextarea" required rows="3" placeholder="Nama jalan, RT/RW, no rumah, kelurahan...">{{ old('address') }}</textarea>
            </div>
            <div class="fg">
                <label class="flabel">Catatan untuk Penjual (opsional)</label>
                <input type="text" name="notes" class="finput" value="{{ old('notes') }}" placeholder="Warna, ukuran, atau instruksi khusus">
            </div>

            <div class="card-title" style="margin-top:24px;">💳 Metode Pembayaran</div>
            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
                <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1.5px solid var(--moss);border-radius:10px;cursor:pointer;background:var(--beige);">
                    <input type="radio" name="payment_method" value="transfer" checked style="accent-color:var(--moss);width:18px;height:18px;">
                    <div>
                        <div style="font-weight:700;color:var(--dk);">Transfer Bank / E-Wallet</div>
                        <div style="font-size:12px;color:var(--muted);">Transfer manual ke rekening pembuat (Upcycler)</div>
                    </div>
                </label>
                
                @php $koinNeeded = ceil($product->price / 2500); @endphp
                <label style="display:flex;align-items:center;gap:12px;padding:14px;border:1.5px solid var(--beige2);border-radius:10px;cursor:pointer;background:var(--beige);opacity:{{ $user->koin >= $koinNeeded ? '1' : '0.6' }};">
                    <input type="radio" name="payment_method" value="koin" {{ $user->koin < $koinNeeded ? 'disabled' : '' }} style="accent-color:var(--moss);width:18px;height:18px;">
                    <div>
                        <div style="font-weight:700;color:var(--dk);">Bayar dengan Koin (Butuh {{ $koinNeeded }} Koin)</div>
                        <div style="font-size:12px;color:var(--muted);">
                            Saldo Anda: {{ $user->koin }} Koin 
                            @if($user->koin < $koinNeeded) <span style="color:#991b1b;font-weight:600;">(Koin Tidak Cukup)</span> @endif
                        </div>
                    </div>
                </label>
            </div>

            <button type="submit" class="post-btn" style="width:100%;font-size:15px;padding:14px;">💳 Bayar Sekarang</button>
        </form>
    </div>

    <div class="card">
        <div class="card-title">Ringkasan Pesanan</div>
        <div class="prod-sum">
            @if($product->photo)
            <img src="{{ asset('storage/'.$product->photo) }}">
            @else
            <div style="width:80px;height:80px;background:#e5e7eb;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;">🎨</div>
            @endif
            <div>
                <div style="font-weight:700;color:var(--dk);">{{ $product->display_name }}</div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:6px;">oleh {{ $product->upcycler->name ?? 'Upcycler' }}</div>
                <div style="font-size:11px;background:#f3f4f6;padding:4px 8px;border-radius:6px;display:inline-block;">Kategori: {{ ucfirst($product->category) }}</div>
            </div>
        </div>
        
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;">
            <span style="color:var(--muted);">Harga Produk</span>
            <span style="color:var(--dk);font-weight:600;">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:16px;font-size:13px;padding-bottom:16px;border-bottom:1px solid var(--beige2);">
            <span style="color:var(--muted);">Ongkos Kirim</span>
            <span style="color:var(--moss);font-weight:700;">Dihitung Nanti</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;color:var(--dk);">
            <span>Total Bayar</span>
            <span>Rp{{ number_format($product->price, 0, ',', '.') }}</span>
        </div>
        <div style="font-size:11px;color:var(--muted);margin-top:12px;text-align:center;">
            *Harga belum termasuk ongkos kirim. Upcycler akan menghubungi Anda untuk detail pengiriman.
        </div>
    </div>
</div>
@endsection
