@extends('layouts.dashboard')
@section('title', 'Detail Limbah #' . str_pad($item->id, 4, '0', STR_PAD_LEFT))

@section('content')

<div class="top-bar" style="margin-bottom:20px;">
    <div>
        <div class="pg-title">🗃 Detail Limbah #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="pg-sub">Informasi lengkap alur limbah kain dari kontributor ke upcycler</div>
    </div>
    <a href="{{ route('admin.limbah') }}" class="btn-primary">← Kembali ke Tabel</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- Info Limbah --}}
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="background:#0A3323;padding:16px 20px;">
            <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:#F7F4D5;">
                {{ $item->title ?? 'Tanpa Judul' }}
            </div>
            <div style="font-size:12px;color:#9FE1CB;margin-top:2px;">
                Post ID: #LMB-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        @if($item->image)
        <img src="{{ asset('storage/'.$item->image) }}"
             style="width:100%;height:200px;object-fit:cover;" />
        @endif

        <div style="padding:20px;">
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #EAF3DE;">
                <span style="font-size:12px;color:#3B6D11;font-weight:600;">Status</span>
                <span class="sbadge sb-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #EAF3DE;">
                <span style="font-size:12px;color:#3B6D11;font-weight:600;">Jenis Bahan</span>
                <span style="font-size:13px;color:#0A3323;font-weight:600;">{{ ucfirst($item->fabric_type ?? '—') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #EAF3DE;">
                <span style="font-size:12px;color:#3B6D11;font-weight:600;">Estimasi Berat</span>
                <span style="font-size:13px;color:#0A3323;font-weight:600;">{{ $item->weight ?? '—' }} kg</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #EAF3DE;">
                <span style="font-size:12px;color:#3B6D11;font-weight:600;">Alamat Penjemputan</span>
                <span style="font-size:13px;color:#0A3323;font-weight:600;text-align:right;max-width:200px;">{{ $item->address ?? '—' }}</span>
            </div>
            @if($item->latitude && $item->longitude)
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #EAF3DE;">
                <span style="font-size:12px;color:#3B6D11;font-weight:600;">Koordinat</span>
                <span style="font-size:12px;color:#666;">{{ $item->latitude }}, {{ $item->longitude }}</span>
            </div>
            @endif
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #EAF3DE;">
                <span style="font-size:12px;color:#3B6D11;font-weight:600;">Deskripsi</span>
                <span style="font-size:13px;color:#0A3323;text-align:right;max-width:200px;">{{ $item->description ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;">
                <span style="font-size:12px;color:#3B6D11;font-weight:600;">Tanggal Post</span>
                <span style="font-size:13px;color:#0A3323;">{{ $item->created_at?->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- Info Pihak Terlibat --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Kontributor --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="background:#3B6D11;padding:14px 18px;">
                <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:800;color:#F7F4D5;">👤 Kontributor (Pemilik)</div>
            </div>
            <div style="padding:18px;">
                @if($item->owner)
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:#839958;display:flex;align-items:center;justify-content:center;font-weight:800;color:#0A3323;font-size:16px;">
                        {{ strtoupper(substr($item->owner->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#0A3323;">{{ $item->owner->name }}</div>
                        <div style="font-size:12px;color:#666;">{{ $item->owner->email }}</div>
                    </div>
                </div>
                @if($item->owner->whatsapp)
                <a href="https://wa.me/62{{ ltrim($item->owner->whatsapp,'0') }}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:6px;background:#25D366;color:#fff;padding:7px 16px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;">
                    📱 WhatsApp Kontributor
                </a>
                @endif
                @else
                <div style="color:#666;font-size:13px;">Data kontributor tidak ditemukan.</div>
                @endif
            </div>
        </div>

        {{-- Upcycler --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="background:#105666;padding:14px 18px;">
                <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:800;color:#F7F4D5;">✂️ Upcycler (Pengklaim)</div>
            </div>
            <div style="padding:18px;">
                @if($item->upcycler)
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:#D3968C;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:16px;">
                        {{ strtoupper(substr($item->upcycler->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#0A3323;">{{ $item->upcycler->name }}</div>
                        <div style="font-size:12px;color:#666;">{{ $item->upcycler->email }}</div>
                    </div>
                </div>
                <div style="font-size:12px;color:#666;">Diklaim: {{ $item->claimed_at?->format('d M Y, H:i') ?? '—' }}</div>
                @else
                <div style="color:#aaa;font-size:13px;font-style:italic;">Belum diklaim oleh upcycler.</div>
                @endif
            </div>
        </div>

        {{-- Produk Hasil --}}
        @if($item->product)
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="background:#839958;padding:14px 18px;">
                <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:800;color:#0A3323;">🎨 Produk Hasil Upcycle</div>
            </div>
            <div style="padding:18px;">
                @if($item->product->photo)
                <img src="{{ asset('storage/'.$item->product->photo) }}"
                     style="width:100%;height:150px;object-fit:cover;border-radius:10px;margin-bottom:12px;" />
                @endif
                <div style="font-weight:700;color:#0A3323;font-size:14px;margin-bottom:4px;">
                    {{ $item->product->display_name }}
                </div>
                <div style="font-size:13px;color:#555;margin-bottom:8px;">
                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                </div>
                <span style="font-size:11px;padding:3px 10px;border-radius:100px;font-weight:700;
                    {{ $item->product->status === 'published' ? 'background:#dcfce7;color:#166534;' : ($item->product->status === 'rejected' ? 'background:#fee2e2;color:#991b1b;' : 'background:#fef9c3;color:#a16207;') }}">
                    {{ $item->product->status_label }}
                </span>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
.sbadge { display:inline-block; padding:4px 12px; border-radius:100px; font-size:12px; font-weight:700; }
.sb-available  { background:#dcfce7; color:#166534; }
.sb-claimed    { background:#fef9c3; color:#a16207; }
.sb-processing { background:#dbeafe; color:#1d4ed8; }
.sb-completed  { background:#f3f4f6; color:#6b7280; }
</style>

@endsection