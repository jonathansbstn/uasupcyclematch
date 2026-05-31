@extends('layouts.dashboard')
@section('title','Export Laporan')

@push('styles')
<style>
.report-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.report-title { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#0A3323; }

.summary-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
.sum-card { background:#fff; border:2px solid #0A3323; border-radius:14px; padding:20px; box-shadow:4px 4px 0 #0A3323; }
.sum-val { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#0A3323; }
.sum-lbl { font-size:12px; color:#666; font-weight:700; text-transform:uppercase; margin-top:4px; }

.export-actions { display:flex; gap:14px; margin-bottom:28px; }
.btn-excel { background:#1d6f42; color:#fff; border:2px solid #0d4a2c; border-radius:10px; padding:12px 28px; font-family:'Syne',sans-serif; font-weight:700; font-size:14px; cursor:pointer; text-decoration:none; box-shadow:0 3px 0 #0d4a2c; display:inline-flex; align-items:center; gap:8px; }
.btn-excel:hover { transform:translateY(-1px); }
.btn-pdf   { background:#c0392b; color:#fff; border:2px solid #922b21; border-radius:10px; padding:12px 28px; font-family:'Syne',sans-serif; font-weight:700; font-size:14px; cursor:pointer; text-decoration:none; box-shadow:0 3px 0 #922b21; display:inline-flex; align-items:center; gap:8px; }
.btn-pdf:hover { transform:translateY(-1px); }

.data-table { width:100%; border-collapse:collapse; font-size:13px; }
.data-table th { background:#0A3323; color:#F7F4D5; padding:10px 14px; text-align:left; font-size:11px; text-transform:uppercase; }
.data-table td { padding:12px 14px; border-bottom:1px solid #f0f0f0; }
.data-table tr:hover td { background:#fafff5; }

.tabs { display:flex; gap:8px; margin-bottom:16px; }
.tab-btn { padding:8px 18px; border:1.5px solid #C0DD97; border-radius:8px; background:#fff; font-weight:700; font-size:13px; cursor:pointer; color:#0A3323; }
.tab-btn.active { background:#0A3323; color:#F7F4D5; border-color:#0A3323; }
.tab-content { display:none; }
.tab-content.active { display:block; }
</style>
@endpush

@section('content')
<div class="report-header">
    <div>
        <div class="report-title">📊 Export Laporan Data</div>
        <div style="font-size:13px;color:#666;margin-top:4px;">Ekspor seluruh data platform dalam format Excel atau PDF</div>
    </div>
</div>

{{-- Summary --}}
<div class="summary-grid">
    <div class="sum-card"><div class="sum-val">{{ $summary['total_limbah'] }}</div><div class="sum-lbl">Total Limbah</div></div>
    <div class="sum-card"><div class="sum-val">{{ $summary['total_produk'] }}</div><div class="sum-lbl">Total Produk</div></div>
    <div class="sum-card"><div class="sum-val">{{ $summary['total_umkm'] }}</div><div class="sum-lbl">Total UMKM</div></div>
    <div class="sum-card"><div class="sum-val">Rp {{ number_format($summary['total_penghematan'],0,',','.') }}</div><div class="sum-lbl">Total Penghematan</div></div>
</div>

{{-- Export Buttons --}}
<div class="export-actions">
    <a href="{{ route('admin.report.excel') }}" class="btn-excel">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
        Export Excel (.xlsx)
    </a>
    <a href="{{ route('admin.report.pdf') }}" class="btn-pdf">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 15h8v2H8v-2zm0-4h8v2H8v-2z"/></svg>
        Export PDF
    </a>
</div>

{{-- Tabbed Data Preview --}}
<div class="card">
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('limbah')">♻️ Riwayat Limbah</button>
        <button class="tab-btn" onclick="switchTab('produk')">🎨 Riwayat Produk</button>
    </div>

    <div id="tab-limbah" class="tab-content active">
        <table class="data-table">
            <thead><tr><th>Judul</th><th>Kontributor</th><th>Bahan</th><th>Berat</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            @forelse($textiles as $t)
            <tr>
                <td><strong>{{ Str::limit($t->title,40) }}</strong></td>
                <td>{{ $t->owner?->name ?? '—' }}</td>
                <td>{{ ucfirst($t->fabric_type ?? '—') }}</td>
                <td>{{ $t->weight }} kg</td>
                <td><span class="sbadge sb-{{ $t->status }}">{{ ucfirst($t->status) }}</span></td>
                <td>{{ $t->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:30px;color:#666;">Belum ada data</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div id="tab-produk" class="tab-content">
        <table class="data-table">
            <thead><tr><th>Nama Produk</th><th>UMKM/Upcycler</th><th>Limbah Asal</th><th>Harga</th><th>Tanggal Upload</th></tr></thead>
            <tbody>
            @forelse($products as $p)
            <tr>
                <td><strong>{{ $p->product_name }}</strong></td>
                <td>{{ $p->upcycler?->name ?? '—' }}</td>
                <td>{{ $p->textile?->title ?? '—' }}</td>
                <td>Rp {{ number_format($p->price,0,',','.') }}</td>
                <td>{{ $p->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:#666;">Belum ada data</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(name) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+name).classList.add('active');
    event.target.classList.add('active');
}
</script>
@endpush
