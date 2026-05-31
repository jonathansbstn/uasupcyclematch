@extends('layouts.dashboard')
@section('title','Dashboard Penjahit')
@section('content')

<div class="dash-hero-band" style="background:#051A13;">
    <div>
        <div class="dash-greet" style="color:#F7F4D5;">Selamat datang, {{ auth()->user()->nama_depan }}! ✂️</div>
        <div class="dash-greet-sub" style="color:#9FE1CB;">Ada <strong style="color:#F7F4D5;">{{ $available->count() }}</strong> limbah tersedia di sekitarmu sekarang.</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('peta.index') }}" class="btn-moss">Buka Peta Limbah →</a>
    </div>
</div>

<div class="kpi4">
    <div class="kpi kpi-g"><div class="kpi-lbl kl-g">Klaim Aktif</div><div class="kpi-num kn-g">{{ $stats['processing'] }}</div></div>
    <div class="kpi kpi-t"><div class="kpi-lbl kl-t">Total Klaim</div><div class="kpi-num kn-t">{{ $stats['total_claims'] }}</div></div>
    <div class="kpi kpi-d"><div class="kpi-lbl kl-d">Kg Diklaim</div><div class="kpi-num kn-d">{{ number_format($stats['kg_total'],1) }}</div></div>
    <div class="kpi kpi-p"><div class="kpi-lbl kl-p">Karya Selesai</div><div class="kpi-num kn-p">{{ $stats['completed'] }}</div></div>
</div>

<div class="dash-grid2">
    <div class="card" id="klaim">
        <div class="card-h"><div><div class="ct">Klaim Aktif Saya</div><div class="cs">Status produksi terkini</div></div></div>
        <div class="card-b">
            @forelse($claims as $claim)
            <div class="post-item">
                <div class="pi-icon pi-{{ $claim->limbahPost?->jenis_bahan==='denim'?'b':'g' }}">
                    {{ $claim->limbahPost?->jenis_bahan==='denim'?'👖':'👕' }}
                </div>
                <div class="pi-info">
                    <div class="pi-title">{{ $claim->limbahPost?->judul ?? 'N/A' }}</div>
                    <div class="pi-meta">{{ $claim->created_at->diffForHumans() }} · {{ $claim->limbahPost?->estimasi_berat }} kg</div>
                </div>
                <span class="sbadge sb-{{ $claim->status }}">{{ ucfirst($claim->status) }}</span>
                @if($claim->status !== 'completed')
                <form method="POST" action="{{ route('klaim.status',$claim->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $claim->status==='claimed'?'processing':'completed' }}">
                    <button type="submit" class="pi-act-btn">
                        {{ $claim->status==='claimed'?'▶ Proses':'✅ Selesai' }}
                    </button>
                </form>
                @endif
            </div>
            @empty
            <div class="empty-state">
                <div style="font-size:48px;">🗺</div>
                <p>Belum ada klaim aktif.</p>
                <a href="{{ route('peta.index') }}" class="btn-moss">Cari Limbah di Peta →</a>
            </div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-h"><div class="ct">Limbah Tersedia Dekatmu</div><div class="cs">{{ $available->count() }} titik aktif</div></div>
        <div class="card-b">
            @forelse($available as $post)
            <div class="post-item">
                <div class="pi-icon pi-g">{{ $post->jenis_bahan==='denim'?'👖':'👕' }}</div>
                <div class="pi-info">
                    <div class="pi-title">{{ $post->judul }}</div>
                    <div class="pi-meta">{{ $post->estimasi_berat }} kg · {{ $post->alamat }}</div>
                </div>
                <form method="POST" action="{{ route('klaim.store',$post->id) }}">
                    @csrf
                    <button type="submit" class="btn-moss" style="font-size:12px;padding:7px 14px;">Klaim</button>
                </form>
            </div>
            @empty
            <div class="empty-state"><p>Tidak ada limbah tersedia saat ini.</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection




