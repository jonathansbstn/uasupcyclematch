@extends('layouts.dashboard')
@section('title', 'Galeri Karya Upcycle')
@section('content')

<div class="top-bar">
    <div>
        <div class="pg-title">🖼 Galeri Karya Upcycle</div>
        <div class="pg-sub">Moderasi dan kelola semua karya yang diunggah upcycler</div>
    </div>
    <div style="display:flex;gap:10px;">
        <select class="galeri-filter" id="filterStatus" onchange="filterGaleri()">
            <option value="">Semua Status</option>
            <option value="published">Published</option>
            <option value="pending">Pending Review</option>
        </select>
        <select class="galeri-filter" id="filterType" onchange="filterGaleri()">
            <option value="">Semua Jenis</option>
            <option value="tas">Tas</option>
            <option value="aksesori">Aksesori</option>
            <option value="keset">Keset</option>
            <option value="pakaian">Pakaian</option>
        </select>
    </div>
</div>

{{-- KPI --}}
<div class="kpi4" style="grid-template-columns:repeat(3,1fr);">
    <div class="kpi kpi-g">
        <div class="kpi-lbl kl-g">📦 Total Karya</div>
        <div class="kpi-num kn-g">{{ $stats['total'] }}</div>
    </div>
    <div class="kpi kpi-t">
        <div class="kpi-lbl kl-t">⏳ Menunggu Review</div>
        <div class="kpi-num kn-t">{{ $stats['pending'] }}</div>
    </div>
    <div class="kpi kpi-p">
        <div class="kpi-lbl kl-p">✅ Sudah Published</div>
        <div class="kpi-num kn-p">{{ $stats['published'] }}</div>
    </div>
</div>

{{-- Search bar --}}
<div class="card" style="padding:16px 20px;">
    <div style="display:flex;gap:12px;align-items:center;">
        <input type="text" id="searchInput" onkeyup="filterGaleri()"
               placeholder="🔍 Cari nama produk atau nama upcycler..."
               style="flex:1;padding:10px 14px;border:1.5px solid #C0DD97;border-radius:10px;font-size:13px;font-family:'DM Sans',sans-serif;background:#FBFDF8;color:#0A3323;outline:none;">
        <span style="font-size:13px;color:#666;" id="resultCount"></span>
    </div>
</div>

{{-- Gallery Grid --}}
@if($products->count())
<div class="galeri-grid" id="galeriGrid">
    @foreach($products as $product)
    @php
        $colors = ['#839958','#105666','#D3968C','#0A3323','#3B6D11','#4B1528'];
        $bg = $colors[$loop->index % count($colors)];
        $emojis = ['tas'=>'👜','aksesori'=>'🎀','keset'=>'🧶','pakaian'=>'👗'];
        $em = $emojis[strtolower($product->category ?? 'tas')] ?? '✂️';
    @endphp
    <div class="g-card" data-status="{{ $product->status }}" data-type="{{ strtolower($product->category ?? '') }}" data-name="{{ strtolower($product->name ?? $product->title ?? '') }}">
        {{-- Thumbnail --}}
        <div class="g-thumb" style="background:{{ $product->photo ? '#f3f4f6' : $bg }};">
            @if($product->photo)
            <img src="{{ asset('storage/'.$product->photo) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
            <div style="font-size:42px;">{{ $em }}</div>
            @endif
            <span class="g-badge {{ $product->status === 'published' ? 'gb-pub' : 'gb-pend' }}">
                {{ $product->status === 'published' ? '✅ Published' : '⏳ Pending' }}
            </span>
        </div>

        {{-- Info --}}
        <div class="g-info">
            <div class="g-title">{{ Str::limit($product->name ?? $product->title ?? 'Karya Tanpa Nama', 40) }}</div>
            <div class="g-by">oleh {{ $product->upcycler?->name ?? $product->owner?->name ?? '—' }}</div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
                @if($product->fabric_type)
                <span class="type-chip">{{ ucfirst($product->fabric_type) }}</span>
                @endif
                @if($product->category)
                <span class="type-chip" style="background:#E1F5EE;color:#0F6E56;border-color:#9FE1CB;">{{ ucfirst($product->category) }}</span>
                @endif
            </div>
            <div style="display:flex;gap:16px;font-size:12px;color:#555;">
                <span>⚖️ {{ $product->weight ?? '—' }} kg</span>
                <span>📅 {{ $product->created_at?->format('d M Y') }}</span>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="g-foot">
            @if($product->status === 'pending')
            <form method="POST" action="{{ route('admin.galeri.approve', $product->id) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="g-btn-pub">✅ Approve</button>
            </form>
            <form method="POST" action="{{ route('admin.galeri.reject', $product->id) }}" style="display:inline;" onsubmit="return confirm('Tolak karya ini?')">
                @csrf @method('PATCH')
                <button type="submit" class="g-btn-del">🗑 Tolak</button>
            </form>
            @else
            <form method="POST" action="{{ route('admin.galeri.reject', $product->id) }}" style="display:inline;" onsubmit="return confirm('Hapus karya ini?')">
                @csrf @method('PATCH')
                <button type="submit" class="g-btn-del">🗑 Hapus</button>
            </form>
            <span style="font-size:12px;color:#3B6D11;font-weight:600;">📊 {{ $product->views ?? 0 }} tayangan</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div style="display:flex;justify-content:center;margin-top:8px;">
    {{ $products->links() }}
</div>
@endif

@else
<div class="card">
    <div class="empty-state">
        <div style="font-size:54px;">🖼</div>
        <p>Belum ada karya upcycle yang diunggah.</p>
        <a href="{{ route('admin.dashboard') }}" class="btn-primary">← Kembali ke Dashboard</a>
    </div>
</div>
@endif

<style>
.galeri-filter {
    padding: 9px 14px; border: 1.5px solid #C0DD97; border-radius: 10px;
    font-size: 13px; font-family: 'DM Sans', sans-serif; background: #fff;
    color: #0A3323; outline: none; cursor: pointer;
}
.galeri-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 18px;
}
.g-card {
    background: #fff; border: 2px solid #e5e7eb; border-radius: 18px;
    overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;
}
.g-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(10,51,35,0.1); }
.g-thumb {
    height: 150px; display: flex; align-items: center; justify-content: center;
    position: relative;
}
.g-badge {
    position: absolute; top: 10px; left: 10px; font-size: 11px;
    padding: 4px 10px; border-radius: 100px; font-weight: 700;
}
.gb-pub  { background: rgba(10,51,35,0.75); color: #9FE1CB; }
.gb-pend { background: rgba(99,56,6,0.8); color: #FAC775; }
.g-info { padding: 14px 16px; }
.g-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 800; color: #0A3323; margin-bottom: 3px; }
.g-by    { font-size: 12px; color: #3B6D11; margin-bottom: 8px; }
.g-foot  {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px; background: #F7F4D5; border-top: 1px solid #EAF3DE; gap: 8px; flex-wrap: wrap;
}
.g-btn-pub {
    font-size: 12px; padding: 6px 14px; border-radius: 100px;
    background: #0A3323; color: #F7F4D5; border: none; cursor: pointer;
    font-family: 'Syne', sans-serif; font-weight: 700;
}
.g-btn-pub:hover { background: #134d36; }
.g-btn-del {
    font-size: 12px; padding: 6px 14px; border-radius: 100px;
    background: transparent; color: #dc2626; border: 1.5px solid #fca5a5;
    cursor: pointer; font-family: 'Syne', sans-serif; font-weight: 700;
}
.g-btn-del:hover { background: #fee2e2; }
</style>

<script>
function filterGaleri() {
    var search = document.getElementById('searchInput').value.toLowerCase();
    var status = document.getElementById('filterStatus').value;
    var type   = document.getElementById('filterType').value;
    var cards  = document.querySelectorAll('.g-card');
    var count  = 0;
    cards.forEach(function(c) {
        var name   = c.dataset.name || '';
        var cstat  = c.dataset.status || '';
        var ctype  = c.dataset.type || '';
        var show   = true;
        if (search && !name.includes(search)) show = false;
        if (status && cstat !== status) show = false;
        if (type   && !ctype.includes(type)) show = false;
        c.style.display = show ? '' : 'none';
        if (show) count++;
    });
    document.getElementById('resultCount').textContent = count + ' karya ditemukan';
}
document.getElementById('resultCount').textContent = document.querySelectorAll('.g-card').length + ' karya';
</script>

@endsection