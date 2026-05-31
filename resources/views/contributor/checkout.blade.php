@extends('layouts.dashboard')
@section('title', 'Checkout — ' . $product->display_name)

@push('styles')
<style>
/* ===== CHECKOUT PAGE ===== */
.co-wrap { display:grid; grid-template-columns:1fr 380px; gap:28px; align-items:start; }
@media(max-width:860px){ .co-wrap { grid-template-columns:1fr; } }

.co-section-title {
    font-family:'Syne',sans-serif;
    font-size:13px; font-weight:800; letter-spacing:.06em; text-transform:uppercase;
    color:#3B6D11; margin:0 0 14px;
}

/* Form card */
.co-card {
    background:#fff;
    border:1.5px solid #C0DD97;
    border-radius:18px;
    padding:28px;
    margin-bottom:20px;
}

/* Input */
.co-label { font-size:12px; font-weight:700; color:#0A3323; margin-bottom:6px; display:block; }
.co-input {
    width:100%; padding:11px 14px;
    border:1.5px solid #C0DD97; border-radius:10px;
    font-family:'DM Sans',sans-serif; font-size:13px; color:#0A3323;
    background:#FBFDF8; outline:none; transition:.2s;
    box-sizing:border-box;
}
.co-input:focus { border-color:#839958; box-shadow:0 0 0 3px rgba(131,153,88,.15); }
.co-input[readonly] { background:#f3f4f6; color:#6b7280; cursor:not-allowed; }
.co-fg { margin-bottom:14px; }
.co-row2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }

/* Payment options */
.pay-opt {
    border:2px solid #e5e7eb; border-radius:12px; padding:14px 16px;
    cursor:pointer; display:flex; align-items:flex-start; gap:12px;
    transition:.2s; position:relative; margin-bottom:10px;
}
.pay-opt:hover { border-color:#839958; background:#FBFDF8; }
.pay-opt.selected { border-color:#839958; background:#F0F7E6; }
.pay-opt input[type=radio] { margin-top:3px; accent-color:#3B6D11; flex-shrink:0; width:16px; height:16px; }
.pay-opt-title { font-weight:700; font-size:13px; color:#0A3323; margin-bottom:2px; }
.pay-opt-sub  { font-size:11px; color:#666; }
.pay-opt-badge {
    position:absolute; top:10px; right:12px;
    font-size:10px; font-weight:700; padding:2px 8px; border-radius:100px;
}

/* Summary card */
.sum-card {
    background:#0A3323; border-radius:18px; padding:24px;
    position:sticky; top:80px;
}
.sum-prod-img {
    width:100%; height:180px; object-fit:cover;
    border-radius:12px; margin-bottom:16px;
    border:2px solid rgba(255,255,255,.1);
}
.sum-prod-placeholder {
    width:100%; height:180px; background:rgba(255,255,255,.06);
    border-radius:12px; margin-bottom:16px;
    display:flex; align-items:center; justify-content:center;
    font-size:48px;
}
.sum-prod-name { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; color:#F7F4D5; margin-bottom:4px; }
.sum-upcycler  { font-size:12px; color:#9FE1CB; margin-bottom:16px; }
.sum-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-top:1px solid rgba(255,255,255,.08); }
.sum-row-label { font-size:12px; color:#9FE1CB; }
.sum-row-val   { font-size:13px; color:#F7F4D5; font-weight:600; }
.sum-total-row { display:flex; justify-content:space-between; align-items:center; padding:14px 0 0; border-top:2px solid rgba(255,255,255,.15); margin-top:4px; }
.sum-total-label { font-family:'Syne',sans-serif; font-size:14px; color:#F7F4D5; font-weight:800; }
.sum-total-val   { font-family:'Syne',sans-serif; font-size:22px; color:#C0DD97; font-weight:800; }

/* CTA Button */
.co-btn {
    width:100%; padding:16px; border:none; border-radius:12px;
    font-family:'Syne',sans-serif; font-size:15px; font-weight:800;
    background:#839958; color:#0A3323;
    cursor:pointer; transition:.2s; margin-top:20px;
    box-shadow:0 4px 0 #3B6D11;
}
.co-btn:hover { transform:translateY(-2px); box-shadow:0 6px 0 #3B6D11; }
.co-btn:active { transform:translateY(2px); box-shadow:0 2px 0 #3B6D11; }

/* Koin tag */
.koin-tag {
    display:inline-flex; align-items:center; gap:6px;
    background:#fef9c3; border:1px solid #fde047;
    border-radius:8px; padding:8px 12px; font-size:12px; color:#854d0e;
    font-weight:600; margin-top:10px; width:100%; box-sizing:border-box;
}
</style>
@endpush

@section('content')

{{-- Back --}}
<div style="margin-bottom:20px;">
    <a href="{{ route('contributor.dashboard') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#3B6D11;text-decoration:none;">
        ← Kembali ke Dashboard
    </a>
</div>

{{-- Error --}}
@if($errors->any() || session('error'))
<div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:13px;color:#991b1b;font-weight:600;">
    @foreach($errors->all() as $e) <div>⚠️ {{ $e }}</div> @endforeach
    @if(session('error')) <div>⚠️ {{ session('error') }}</div> @endif
</div>
@endif

<div class="co-wrap">

    {{-- ===== LEFT: FORM ===== --}}
    <div>
        <form id="checkoutForm" action="{{ route('contributor.checkout.store', $product->id) }}" method="POST">
            @csrf

            {{-- STEP 1: Alamat --}}
            <div class="co-card">
                <div class="co-section-title">📍 Langkah 1 — Detail Pengiriman</div>

                <div class="co-row2">
                    <div class="co-fg">
                        <label class="co-label">Nama Penerima *</label>
                        <input type="text" name="recipient_name" class="co-input" required
                               value="{{ old('recipient_name', $user->name) }}" placeholder="Nama lengkap penerima">
                    </div>
                    <div class="co-fg">
                        <label class="co-label">No. HP / WhatsApp *</label>
                        <input type="text" name="recipient_phone" class="co-input" required
                               value="{{ old('recipient_phone', $user->whatsapp) }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="co-row2">
                    <div class="co-fg">
                        <label class="co-label">Kota / Kabupaten *</label>
                        <input type="text" name="city" class="co-input" required
                               value="{{ old('city') }}" placeholder="Contoh: Semarang">
                    </div>
                    <div class="co-fg">
                        <label class="co-label">Kode Pos</label>
                        <input type="text" name="postal_code" class="co-input"
                               value="{{ old('postal_code') }}" placeholder="Opsional">
                    </div>
                </div>
                <div class="co-fg">
                    <label class="co-label">Alamat Lengkap *</label>
                    <textarea name="address" class="co-input" required rows="3"
                              placeholder="Nama jalan, no. rumah, RT/RW, kelurahan, kecamatan...">{{ old('address') }}</textarea>
                </div>
                <div class="co-fg">
                    <label class="co-label">Catatan untuk Penjual <span style="color:#aaa;font-weight:400;">(opsional)</span></label>
                    <input type="text" name="notes" class="co-input"
                           value="{{ old('notes') }}" placeholder="Warna pilihan, instruksi khusus, dll.">
                </div>
            </div>

            {{-- STEP 2: Pembayaran --}}
            <div class="co-card">
                <div class="co-section-title">💳 Langkah 2 — Metode Pembayaran</div>

                {{-- OPSI TRANSFER --}}
                <label class="pay-opt" id="opt-transfer" onclick="selectPay('transfer')">
                    <input type="radio" name="payment_method" value="transfer" checked>
                    <div style="flex:1;">
                        <div class="pay-opt-title">🏦 Transfer Bank / E-Wallet</div>
                        <div class="pay-opt-sub">Transfer ke rekening Penjual setelah checkout.<br>Penjual akan mengirimkan nomor rekening via WhatsApp.</div>
                    </div>
                    <span class="pay-opt-badge" style="background:#e0f2fe;color:#0369a1;">Populer</span>
                </label>

                {{-- OPSI KOIN --}}
                @php $koinNeeded = ceil($product->price / 2500); $canPayKoin = $user->koin >= $koinNeeded; @endphp
                <label class="pay-opt {{ !$canPayKoin ? 'opacity-60' : '' }}" id="opt-koin" onclick="{{ $canPayKoin ? 'selectPay(\'koin\')' : '' }}" style="{{ !$canPayKoin ? 'cursor:not-allowed;opacity:.55;' : '' }}">
                    <input type="radio" name="payment_method" value="koin" {{ !$canPayKoin ? 'disabled' : '' }}>
                    <div style="flex:1;">
                        <div class="pay-opt-title">🪙 Tukar Koin UpcycleMatch</div>
                        <div class="pay-opt-sub">
                            Butuh <strong>{{ $koinNeeded }} Koin</strong> · Saldo kamu: <strong>{{ $user->koin }} Koin</strong>
                            @if(!$canPayKoin) <span style="color:#ef4444;"> (Tidak Cukup)</span> @endif
                        </div>
                    </div>
                    @if($canPayKoin)
                    <span class="pay-opt-badge" style="background:#dcfce7;color:#166534;">Langsung Lunas</span>
                    @endif
                </label>

                {{-- Info koin --}}
                @if($canPayKoin)
                <div class="koin-tag">
                    ⚡ Jika bayar pakai Koin, pesanan langsung berstatus <strong>Lunas</strong> & Penjual akan segera memproses pengiriman.
                </div>
                @else
                <div class="koin-tag" style="background:#fef2f2;border-color:#fca5a5;color:#991b1b;">
                    💡 Koin kamu belum cukup. Kumpulkan lebih banyak Koin dengan mengupload limbah kain!
                </div>
                @endif
            </div>

            {{-- Hidden submit trigger from summary card --}}
            <button id="coSubmit" type="submit" style="display:none;"></button>
        </form>
    </div>

    {{-- ===== RIGHT: SUMMARY ===== --}}
    <div class="sum-card">
        @if($product->photo)
        <img src="{{ asset('storage/'.$product->photo) }}" class="sum-prod-img" alt="{{ $product->display_name }}">
        @else
        <div class="sum-prod-placeholder">🎨</div>
        @endif

        <div class="sum-prod-name">{{ $product->display_name }}</div>
        <div class="sum-upcycler">✂️ dibuat oleh <strong>{{ $product->upcycler->name ?? 'Upcycler' }}</strong></div>

        @if($product->category)
        <div style="margin-bottom:16px;">
            <span style="background:rgba(131,153,88,.2);color:#C0DD97;font-size:11px;font-weight:700;padding:4px 10px;border-radius:100px;">
                {{ ucfirst($product->category) }}
            </span>
        </div>
        @endif

        <div class="sum-row">
            <span class="sum-row-label">Harga Produk</span>
            <span class="sum-row-val">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
        </div>
        <div class="sum-row">
            <span class="sum-row-label">Ongkos Kirim</span>
            <span class="sum-row-val" style="color:#9FE1CB;">Dikonfirmasi penjual</span>
        </div>

        <div class="sum-total-row">
            <span class="sum-total-label">Total Bayar</span>
            <span class="sum-total-val">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
        </div>

        <button class="co-btn" onclick="document.getElementById('coSubmit').click();">
            🛒 Konfirmasi Pesanan
        </button>

        <div style="text-align:center;font-size:11px;color:#9FE1CB;margin-top:12px;line-height:1.6;">
            Dengan menekan tombol, kamu menyetujui <br>syarat & ketentuan UpcycleMatch.
        </div>
    </div>
</div>

<script>
function selectPay(method) {
    document.querySelectorAll('.pay-opt').forEach(el => el.classList.remove('selected'));
    if (method === 'transfer') {
        document.querySelector('[value=transfer]').checked = true;
        document.getElementById('opt-transfer').classList.add('selected');
    } else {
        document.querySelector('[value=koin]').checked = true;
        document.getElementById('opt-koin').classList.add('selected');
    }
}
// Set initial state
selectPay('transfer');
</script>
@endsection
