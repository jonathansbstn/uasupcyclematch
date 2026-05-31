@extends('layouts.dashboard')
@section('title','Admin Dashboard')
@section('content')

<div class="top-bar">
    <div>
        <div class="pg-title">Selamat datang, {{ auth()->user()->name }} 👋</div>
        <div class="pg-sub">{{ now()->isoFormat('dddd, D MMMM Y') }} &nbsp;·&nbsp; Data real-time</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.analytics') }}" class="btn-primary" style="margin-right:4px;">📊 Analytics</a>
        <a href="{{ route('admin.report') }}" class="btn-primary">📄 Export →</a>
    </div>
</div>

@if($stats['pending_penjahit'] > 0)
<div class="alert-banner">
    <div style="display:flex;align-items:center;gap:12px;">
        <div class="ab-icon">⚠️</div>
        <div>
            <div class="ab-title">{{ $stats['pending_penjahit'] }} upcycler menunggu verifikasi</div>
            <div class="ab-sub">Segera verifikasi agar penjahit bisa mengakses platform</div>
        </div>
    </div>
    <a href="{{ route('admin.verification') }}" class="btn-moss">Verifikasi Sekarang →</a>
</div>
@endif

{{-- KPI Cards --}}
<div class="kpi4">
    <div class="kpi kpi-g">
        <div class="kpi-lbl kl-g">♻️ Limbah Diselamatkan</div>
        <div class="kpi-num kn-g">{{ number_format($stats['total_kg'], 1) }} kg</div>
    </div>
    <div class="kpi kpi-t">
        <div class="kpi-lbl kl-t">💰 Penghematan UMKM</div>
        <div class="kpi-num kn-t" style="font-size:18px;">Rp {{ number_format($stats['modal_hemat'], 0, ',', '.') }}</div>
    </div>
    <div class="kpi kpi-d">
        <div class="kpi-lbl kl-d">👥 Total Pengguna</div>
        <div class="kpi-num kn-d">{{ $stats['total_users'] }}</div>
    </div>
    <div class="kpi kpi-p">
        <div class="kpi-lbl kl-p">⏳ Menunggu Review</div>
        <div class="kpi-num kn-p">{{ $stats['pending_penjahit'] }}</div>
    </div>
</div>

{{-- Main Grid --}}
<div class="dash-grid3">
    {{-- RECENT POSTS --}}
    <div class="card">
        <div class="card-h">
            <div>
                <div class="ct">Postingan Limbah Terbaru</div>
                <div class="cs">10 postingan terakhir</div>
            </div>
            <a href="{{ route('admin.limbah') }}" class="clink">Lihat semua →</a>
        </div>
        <div class="card-b">
            @if($recentPosts->count())
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kontributor</th>
                        <th>Bahan</th>
                        <th>Berat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($recentPosts as $post)
                <tr>
                    <td><strong>{{ Str::limit($post->title ?? $post->judul ?? '-', 35) }}</strong></td>
                    <td>{{ $post->owner?->name ?? $post->user?->name ?? '—' }}</td>
                    <td>
                        <span class="type-chip">{{ ucfirst($post->fabric_type ?? $post->jenis_bahan ?? '—') }}</span>
                    </td>
                    <td>{{ $post->weight ?? $post->berat_kg ?? '—' }} kg</td>
                    <td><span class="sbadge sb-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <div style="font-size:40px;">📦</div>
                <p>Belum ada data limbah.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- PENDING PENJAHIT --}}
    <div class="card">
        <div class="card-h">
            <div>
                <div class="ct">Verifikasi Upcycler</div>
                <div class="cs">{{ $stats['pending_penjahit'] }} menunggu</div>
            </div>
            <a href="{{ route('admin.verification') }}" class="clink">Kelola →</a>
        </div>
        <div class="card-b">
            @forelse($pendingPenjahit as $user)
            <div class="pending-item">
                <div class="pav pav-g">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                <div class="pinfo">
                    <div class="pname">{{ $user->name }}</div>
                    <div class="pmeta">{{ $user->email }}<br>{{ $user->created_at->diffForHumans() }}</div>
                </div>
                <form method="POST" action="{{ route('admin.users.verify', $user->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="vbtn">✅ Verif</button>
                </form>
            </div>
            @empty
            <div class="empty-state" style="padding:20px;">
                <div style="font-size:36px;">✅</div>
                <p style="margin-top:8px;font-size:13px;">Semua upcycler sudah terverifikasi</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- SDG Progress --}}
<div class="card">
    <div class="card-h">
        <div class="ct">🌿 Target SDG 12 — {{ now()->isoFormat('MMMM Y') }}</div>
        <div class="cs">Progres pencapaian bulan ini</div>
    </div>
    <div class="card-b">
        <div class="sdg-grid">
            <div class="sdg-card">
                <div class="sdg-num">{{ number_format($stats['total_kg'], 1) }} kg</div>
                <div class="sdg-label">Limbah kain berkurang</div>
                <div class="sdg-prog"><div class="sdg-bar" style="width:{{ min(100, ($stats['total_kg']/3000)*100) }}%;"></div></div>
                <div class="sdg-target">Target: 3.000 kg/bulan</div>
            </div>
            <div class="sdg-card">
                <div class="sdg-num">{{ \App\Models\User::where('role','upcycler')->where('is_verified',true)->count() }}</div>
                <div class="sdg-label">UMKM aktif & terverifikasi</div>
                <div class="sdg-prog">
                    <div class="sdg-bar" style="width:{{ min(100,(\App\Models\User::where('role','upcycler')->where('is_verified',true)->count()/120)*100) }}%;background:#105666;"></div>
                </div>
                <div class="sdg-target">Target: 120 UMKM</div>
            </div>
            <div class="sdg-card">
                <div class="sdg-num">{{ $stats['total_users'] }}</div>
                <div class="sdg-label">Total pengguna platform</div>
                <div class="sdg-prog">
                    <div class="sdg-bar" style="width:{{ min(100,($stats['total_users']/500)*100) }}%;background:#D3968C;"></div>
                </div>
                <div class="sdg-target">Target: 500 pengguna</div>
            </div>
        </div>
    </div>
</div>

@endsection