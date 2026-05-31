@extends('layouts.dashboard')
@section('title','Admin Dashboard')
@section('content')

<div class="top-bar">
    <div><div class="pg-title">Selamat pagi, {{ auth()->user()->nama_depan }} 👋</div><div class="pg-sub">{{ now()->isoFormat('dddd, D MMMM Y') }} · Data real-time</div></div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.export') }}" class="btn-primary">Export Laporan →</a>
    </div>
</div>

@if($stats['pending_penjahit'] > 0)
<div class="alert-banner">
    <div style="display:flex;align-items:center;gap:12px;">
        <div class="ab-icon">⚠️</div>
        <div><div class="ab-title">{{ $stats['pending_penjahit'] }} penjahit menunggu verifikasi</div><div class="ab-sub">Segera verifikasi agar penjahit bisa mengakses platform</div></div>
    </div>
    <a href="{{ route('admin.users') }}" class="btn-moss">Verifikasi Sekarang →</a>
</div>
@endif

<div class="kpi4">
    <div class="kpi kpi-g"><div class="kpi-lbl kl-g">Total Limbah Berkurang</div><div class="kpi-num kn-g">{{ number_format($stats['total_kg'],1) }} kg</div></div>
    <div class="kpi kpi-t"><div class="kpi-lbl kl-t">Penghematan UMKM</div><div class="kpi-num kn-t">Rp {{ number_format($stats['modal_hemat'],0,',','.') }}</div></div>
    <div class="kpi kpi-d"><div class="kpi-lbl kl-d">Total Pengguna</div><div class="kpi-num kn-d">{{ $stats['total_users'] }}</div></div>
    <div class="kpi kpi-p"><div class="kpi-lbl kl-p">Menunggu Review</div><div class="kpi-num kn-p">{{ $stats['pending_penjahit'] }}</div></div>
</div>

<div class="dash-grid3">
    {{-- RECENT POSTS --}}
    <div class="card" style="grid-column:span 2;">
        <div class="card-h"><div><div class="ct">Postingan Limbah Terbaru</div></div><a href="{{ route('admin.limbah') }}" class="clink">Lihat semua →</a></div>
        <div class="card-b">
            <table class="admin-table">
                <thead><tr><th>Judul</th><th>Kontributor</th><th>Bahan</th><th>Berat</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($recentPosts as $post)
                <tr>
                    <td>{{ Str::limit($post->judul,35) }}</td>
                    <td>{{ $post->user?->nama_depan }}</td>
                    <td><span class="type-chip tc-{{ $post->jenis_bahan }}">{{ ucfirst($post->jenis_bahan) }}</span></td>
                    <td>{{ $post->estimasi_berat }} kg</td>
                    <td><span class="sbadge sb-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#3B6D11;padding:20px;">Belum ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PENDING PENJAHIT --}}
    <div class="card">
        <div class="card-h"><div><div class="ct">Verifikasi Penjahit</div><div class="cs">{{ $stats['pending_penjahit'] }} menunggu</div></div></div>
        <div class="card-b">
            @forelse($pendingPenjahit as $user)
            <div class="pending-item">
                <div class="pav pav-g">{{ strtoupper(substr($user->nama_depan,0,2)) }}</div>
                <div class="pinfo">
                    <div class="pname">{{ $user->nama_depan }} {{ $user->nama_belakang }}</div>
                    <div class="pmeta">{{ $user->email }} · {{ $user->created_at->diffForHumans() }}</div>
                </div>
                <form method="POST" action="{{ route('admin.users.verify',$user->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="vbtn">Verifikasi</button>
                </form>
            </div>
            @empty
            <div class="empty-state"><p>Tidak ada penjahit pending.</p></div>
            @endforelse
        </div>
    </div>
</div>

{{-- SDG TARGET --}}
<div class="card" style="margin-top:16px;" id="analytics">
    <div class="card-h"><div class="ct">Target SDG Bulan Ini</div><div class="cs">Progres pencapaian {{ now()->isoFormat('MMMM Y') }}</div></div>
    <div class="card-b">
        <div class="sdg-grid">
            <div class="sdg-card">
                <div class="sdg-num">{{ number_format($stats['total_kg'],1) }} kg</div>
                <div class="sdg-label">Limbah berkurang</div>
                <div class="sdg-prog"><div class="sdg-bar" style="width:{{ min(100, ($stats['total_kg']/3000)*100) }}%;"></div></div>
                <div class="sdg-target">Target: 3.000 kg</div>
            </div>
            <div class="sdg-card">
                <div class="sdg-num">{{ \App\Models\User::where('role','penjahit')->where('is_verified',true)->count() }}</div>
                <div class="sdg-label">UMKM terlibat</div>
                <div class="sdg-prog"><div class="sdg-bar" style="width:{{ min(100,(\App\Models\User::where('role','penjahit')->where('is_verified',true)->count()/120)*100) }}%;background:#105666;"></div></div>
                <div class="sdg-target">Target: 120 UMKM</div>
            </div>
            <div class="sdg-card">
                <div class="sdg-num">{{ $stats['total_users'] }}</div>
                <div class="sdg-label">Pengguna aktif</div>
                <div class="sdg-prog"><div class="sdg-bar" style="width:{{ min(100,($stats['total_users']/500)*100) }}%;background:#D3968C;"></div></div>
                <div class="sdg-target">Target: 500 pengguna</div>
            </div>
        </div>
    </div>
</div>
@endsection