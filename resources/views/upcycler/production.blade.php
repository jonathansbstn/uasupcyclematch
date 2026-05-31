@extends('layouts.dashboard')
@section('title','Dashboard Produksi')

@push('styles')
<style>
.prod-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.prod-title { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#0A3323; }

.prod-item {
    background:#fff; border:2px solid #0A3323; border-radius:16px;
    padding:24px; margin-bottom:20px; box-shadow: 4px 4px 0 #0A3323;
}
.prod-item-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px; }
.prod-item-title { font-family:'Syne',sans-serif; font-size:17px; font-weight:800; color:#0A3323; }
.prod-item-meta { font-size:13px; color:#666; margin-top:4px; }

/* Status badge */
.sbadge { display:inline-block; padding:4px 12px; border-radius:100px; font-size:12px; font-weight:700; }
.sb-claimed    { background:#fef9c3; color:#a16207; border:1.5px solid #fde047; }
.sb-processing { background:#dbeafe; color:#1d4ed8; border:1.5px solid #93c5fd; }
.sb-completed  { background:#dcfce7; color:#166534; border:1.5px solid #86efac; }
.sb-available  { background:#f0fdf4; color:#166534; border:1.5px solid #86efac; }

/* Workflow steps */
.workflow-steps { display:flex; align-items:center; gap:8px; margin-bottom:20px; }
.wf-step {
    flex:1; text-align:center; padding:10px; border-radius:10px; font-size:12px;
    font-weight:700; border:1.5px solid #e5e7eb; color:#9ca3af; background:#f9fafb;
}
.wf-step.active { background:#0A3323; color:#F7F4D5; border-color:#0A3323; }
.wf-step.done   { background:#839958; color:#0A3323; border-color:#839958; }
.wf-arrow { color:#9ca3af; font-size:16px; }

/* Action buttons */
.btn-start    { background:#3b82f6; color:#fff; border:2px solid #1d4ed8; border-radius:8px; padding:10px 20px; font-weight:700; font-size:14px; cursor:pointer; box-shadow:0 3px 0 #1d4ed8; }
.btn-finish   { background:#22c55e; color:#fff; border:2px solid #166534; border-radius:8px; padding:10px 20px; font-weight:700; font-size:14px; cursor:pointer; box-shadow:0 3px 0 #166534; }
.btn-start:hover  { transform:translateY(-1px); }
.btn-finish:hover { transform:translateY(-1px); }

/* Upload form */
.upload-section {
    background:#F7F4D5; border:1.5px dashed #839958; border-radius:12px;
    padding:20px; margin-top:16px;
}
.upload-section h4 { font-family:'Syne',sans-serif; font-weight:800; font-size:15px; color:#0A3323; margin-bottom:14px; }
.upload-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.upload-grid .full { grid-column: span 2; }

.kpi4-small { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
.empty-prod { text-align:center; padding:60px 20px; color:#666; }
.empty-prod .icon { font-size:56px; margin-bottom:16px; }
.empty-prod p { font-size:15px; margin-bottom:20px; }
</style>
@endpush

@section('content')
<div class="prod-header">
    <div>
        <div class="prod-title">⚙️ Dashboard Produksi Saya</div>
        <div style="font-size:13px;color:#666;margin-top:4px;">Kelola proses upcycling limbah yang telah Anda klaim</div>
    </div>
    <a href="{{ route('upcycler.exploration-map') }}" class="btn-primary">🗺 Cari Limbah Baru →</a>
</div>

{{-- KPI --}}
@php
    $claimed    = $textiles->where('status','claimed')->count();
    $processing = $textiles->where('status','processing')->count();
    $completed  = $textiles->where('status','completed')->count();
@endphp
<div class="kpi4-small">
    <div class="kpi kpi-t"><div class="kpi-lbl kl-t">🟡 Diklaim</div><div class="kpi-num kn-t">{{ $claimed }}</div></div>
    <div class="kpi kpi-d"><div class="kpi-lbl kl-d">🔵 Diproses</div><div class="kpi-num kn-d">{{ $processing }}</div></div>
    <div class="kpi kpi-g"><div class="kpi-lbl kl-g">✅ Selesai</div><div class="kpi-num kn-g">{{ $completed }}</div></div>
</div>

@forelse($textiles as $textile)
<div class="prod-item">
    <div class="prod-item-header">
        <div>
            <div class="prod-item-title">{{ $textile->title }}</div>
            <div class="prod-item-meta">
                ⚖️ {{ $textile->weight }} kg &nbsp;·&nbsp;
                🧵 {{ ucfirst($textile->fabric_type ?? '-') }} &nbsp;·&nbsp;
                📍 {{ $textile->address ?? 'Alamat tidak tersedia' }} &nbsp;·&nbsp;
                🕐 Diklaim {{ $textile->claimed_at?->diffForHumans() ?? '-' }}
            </div>
        </div>
        <span class="sbadge sb-{{ $textile->status }}">
            {{ ['claimed'=>'🟡 Diklaim','processing'=>'🔵 Diproses','completed'=>'✅ Selesai'][$textile->status] ?? $textile->status }}
        </span>
    </div>

    {{-- Workflow indicator --}}
    <div class="workflow-steps">
        <div class="wf-step {{ in_array($textile->status,['claimed','processing','completed']) ? 'done' : '' }}">✅ Diklaim</div>
        <div class="wf-arrow">→</div>
        <div class="wf-step {{ $textile->status === 'processing' ? 'active' : ($textile->status === 'completed' ? 'done' : '') }}">⚙️ Diproses</div>
        <div class="wf-arrow">→</div>
        <div class="wf-step {{ $textile->status === 'completed' ? 'active' : '' }}">🎉 Selesai</div>
    </div>

    {{-- Action Buttons --}}
    @if($textile->status === 'claimed')
    <form method="POST" action="{{ route('upcycler.production.start', $textile->id) }}">
        @csrf @method('PATCH')
        <button type="submit" class="btn-start">▶ Mulai Produksi</button>
    </form>
    @endif

    @if($textile->status === 'processing')
    <form method="POST" action="{{ route('upcycler.production.finish', $textile->id) }}" style="display:inline;">
        @csrf @method('PATCH')
        <button type="submit" class="btn-finish">✅ Selesaikan Produksi</button>
    </form>
    @endif

    {{-- Upload form (jika completed dan belum ada produk) --}}
    @if($textile->status === 'completed')
        @if($textile->product)
        <div style="background:#f0fdf4;border:1.5px solid #86efac;border-radius:12px;padding:16px;margin-top:12px;">
            <div style="font-weight:700;color:#166534;font-size:14px;margin-bottom:8px;">🎨 Karya Sudah Diupload</div>
            <div style="font-size:13px;color:#555;margin-bottom:8px;">
                <strong>{{ $textile->product->display_name }}</strong> &nbsp;·&nbsp;
                Rp {{ number_format($textile->product->price, 0, ',', '.') }}
            </div>
            @if($textile->product->photo)
            <img src="{{ asset('storage/'.$textile->product->photo) }}" style="width:140px;height:90px;object-fit:cover;border-radius:8px;margin-bottom:8px;" />
            @endif
            <div style="margin-top:6px;">
                @if($textile->product->status === 'published')
                    <span style="background:#dcfce7;color:#166534;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">✅ Sudah Dipublish di Galeri</span>
                @elseif($textile->product->status === 'rejected')
                    <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">❌ Ditolak Admin</span>
                @else
                    <span style="background:#fef9c3;color:#a16207;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">⏳ Menunggu Verifikasi Admin</span>
                @endif
            </div>
        </div>
        @else
        <div class="upload-section">
            <h4>🎨 Upload Karya Anda</h4>
            <p style="font-size:12px;color:#666;margin-bottom:14px;">Karya akan tampil di Galeri setelah diverifikasi admin.</p>
            @if($errors->any())
            <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;margin-bottom:12px;font-size:12px;color:#991b1b;">
                @foreach($errors->all() as $e) <div>⚠️ {{ $e }}</div> @endforeach
            </div>
            @endif
            <form method="POST" action="{{ route('upcycler.production.upload', $textile->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="upload-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Produk *</label>
                        <input type="text" name="product_name" class="form-input" required
                               placeholder="Contoh: Tote Bag Denim Premium"
                               value="{{ old('product_name') }}" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori Produk</label>
                        <select name="category" class="form-input">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="tas" {{ old('category')=='tas'?'selected':'' }}>Tas</option>
                            <option value="pakaian" {{ old('category')=='pakaian'?'selected':'' }}>Pakaian</option>
                            <option value="aksesori" {{ old('category')=='aksesori'?'selected':'' }}>Aksesori</option>
                            <option value="keset" {{ old('category')=='keset'?'selected':'' }}>Keset / Karpet</option>
                            <option value="dekorasi" {{ old('category')=='dekorasi'?'selected':'' }}>Dekorasi</option>
                            <option value="lainnya" {{ old('category')=='lainnya'?'selected':'' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Jual (Rp) *</label>
                        <input type="number" name="price" class="form-input" required
                               min="0" placeholder="75000"
                               value="{{ old('price') }}" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Foto Produk * (JPG/PNG, max 5MB)</label>
                        <input type="file" name="photo" class="form-input"
                               accept="image/jpeg,image/png,image/webp" required />
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Deskripsi Produk</label>
                        <textarea name="description" class="form-input" rows="3"
                                  placeholder="Ceritakan proses kreatif & keunikan produk Anda...">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn-submit" style="margin-top:14px;width:100%;">
                    📤 Upload & Kirim ke Admin untuk Verifikasi
                </button>
            </form>
        </div>
        @endif

    @endif
</div>
@empty
<div class="empty-prod">
    <div class="icon">🧵</div>
    <p>Anda belum mengklaim limbah apapun.<br>Jelajahi peta untuk menemukan limbah terdekat!</p>
    <a href="{{ route('upcycler.exploration-map') }}" class="btn-primary">🗺 Buka Peta Eksplorasi →</a>
</div>
@endforelse

@if(session('success'))
<div style="position:fixed;bottom:24px;right:24px;background:#0A3323;color:#F7F4D5;padding:16px 24px;border-radius:12px;font-weight:700;z-index:9999;box-shadow:4px 4px 0 #839958;" id="toast">
    ✅ {{ session('success') }}
</div>
<script>setTimeout(()=>document.getElementById('toast')?.remove(), 4000)</script>
@endif
@endsection
