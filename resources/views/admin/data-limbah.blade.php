@extends('layouts.dashboard')
@section('title', 'Data Limbah')
@section('content')

<div class="top-bar">
    <div>
        <div class="pg-title">🗃 Manajemen Data Limbah</div>
        <div class="pg-sub">Seluruh postingan limbah kain dari para kontributor ekosistem UpcycleMatch</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.report') }}" class="btn-primary">📊 Export Data →</a>
    </div>
</div>

{{-- KPI --}}
<div class="kpi4">
    <div class="kpi kpi-g">
        <div class="kpi-lbl kl-g">📦 Total Postingan</div>
        <div class="kpi-num kn-g">{{ $textiles->total() }}</div>
    </div>
    <div class="kpi kpi-t">
        <div class="kpi-lbl kl-t">✅ Tersedia</div>
        <div class="kpi-num kn-t">{{ $textiles->where('status','available')->count() }}</div>
    </div>
    <div class="kpi kpi-d">
        <div class="kpi-lbl kl-d">🔵 Sedang Diklaim</div>
        <div class="kpi-num kn-d">{{ $textiles->whereIn('status',['claimed','processing'])->count() }}</div>
    </div>
    <div class="kpi kpi-p">
        <div class="kpi-lbl kl-p">🏁 Selesai</div>
        <div class="kpi-num kn-p">{{ $textiles->where('status','completed')->count() }}</div>
    </div>
</div>

{{-- Search --}}
<div class="card" style="padding:16px 20px;">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <input type="text" id="searchInput" oninput="filterTable()"
               placeholder="🔍 Cari judul limbah atau nama kontributor..."
               style="flex:1;min-width:200px;padding:10px 14px;border:1.5px solid #C0DD97;border-radius:10px;font-size:13px;font-family:'DM Sans',sans-serif;background:#FBFDF8;color:#0A3323;outline:none;">
        <select id="filterStatus" onchange="filterTable()"
                style="padding:10px 14px;border:1.5px solid #C0DD97;border-radius:10px;font-size:13px;font-family:'DM Sans',sans-serif;background:#fff;color:#0A3323;outline:none;">
            <option value="">Semua Status</option>
            <option value="available">Tersedia</option>
            <option value="claimed">Diklaim</option>
            <option value="processing">Diproses</option>
            <option value="completed">Selesai</option>
        </select>
        <span id="rowCount" style="font-size:13px;color:#666;white-space:nowrap;"></span>
    </div>
</div>

{{-- Table --}}
<div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table" id="limbahTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Limbah</th>
                <th>Kontributor</th>
                <th>Jenis Bahan</th>
                <th>Berat</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
        @forelse($textiles as $t)
        <tr data-name="{{ strtolower($t->title ?? '') }} {{ strtolower($t->owner?->name ?? '') }}"
            data-status="{{ $t->status }}">
            <td style="color:#839958;font-weight:700;font-family:'Syne',sans-serif;">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</td>
            <td>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:#0A3323;">
                    {{ Str::limit($t->title ?? '-', 40) }}
                </div>
                @if($t->description)
                <div style="font-size:11px;color:#666;margin-top:2px;">{{ Str::limit($t->description, 50) }}</div>
                @endif
            </td>
            <td>
                <div style="font-weight:600;font-size:13px;">{{ $t->owner?->name ?? '—' }}</div>
                <div style="font-size:11px;color:#666;">{{ $t->owner?->email ?? '' }}</div>
            </td>
            <td><span class="type-chip">{{ ucfirst($t->fabric_type ?? '—') }}</span></td>
            <td><strong>{{ $t->weight ?? '—' }}</strong> kg</td>
            <td style="font-size:12px;color:#555;max-width:140px;">{{ Str::limit($t->address ?? 'Tidak ada', 30) }}</td>
            <td><span class="sbadge sb-{{ $t->status }}">{{ ucfirst($t->status) }}</span></td>
            <td style="font-size:12px;color:#666;">{{ $t->created_at?->format('d M Y') }}</td>
            <td>
                <a href="{{ route('admin.detail-limbah', $t->id) }}" class="btn-moss" style="font-size:12px;padding:6px 12px;">
                    Detail →
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center;padding:40px;color:#666;">
                <div style="font-size:36px;margin-bottom:8px;">📭</div>
                Belum ada data limbah.
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($textiles->hasPages())
<div style="display:flex;justify-content:center;margin-top:4px;">
    {{ $textiles->links() }}
</div>
@endif

<script>
function filterTable() {
    var search = document.getElementById('searchInput').value.toLowerCase();
    var status = document.getElementById('filterStatus').value;
    var rows   = document.querySelectorAll('#tableBody tr[data-name]');
    var count  = 0;
    rows.forEach(function(r) {
        var name  = r.dataset.name || '';
        var rstat = r.dataset.status || '';
        var show  = true;
        if (search && !name.includes(search)) show = false;
        if (status && rstat !== status) show = false;
        r.style.display = show ? '' : 'none';
        if (show) count++;
    });
    document.getElementById('rowCount').textContent = count + ' data';
}
document.getElementById('rowCount').textContent = document.querySelectorAll('#tableBody tr[data-name]').length + ' data';
</script>

@endsection