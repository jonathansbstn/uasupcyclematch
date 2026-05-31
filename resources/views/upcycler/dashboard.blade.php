@extends('layouts.dashboard')
@section('title','Dashboard Upcycler')
@section('content')

{{-- Hero Band --}}
<div class="dash-hero-band">
    <div>
        <div class="dash-greet">Selamat datang, {{ auth()->user()->name }}! ✂️</div>
        <div class="dash-greet-sub">
            Ada <strong style="color:#F7F4D5;">{{ $available->count() }}</strong> limbah tersedia di peta saat ini.
        </div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('upcycler.exploration-map') }}" class="btn-primary">🗺 Buka Peta Limbah →</a>
    </div>
</div>

{{-- KPI --}}
<div class="kpi4">
    <div class="kpi kpi-g">
        <div class="kpi-lbl kl-g">⚙️ Sedang Diproses</div>
        <div class="kpi-num kn-g">{{ $stats['processing'] }}</div>
    </div>
    <div class="kpi kpi-t">
        <div class="kpi-lbl kl-t">📦 Total Klaim</div>
        <div class="kpi-num kn-t">{{ $stats['total_claims'] }}</div>
    </div>
    <div class="kpi kpi-d">
        <div class="kpi-lbl kl-d">⚖️ Kg Diklaim</div>
        <div class="kpi-num kn-d">{{ number_format($stats['kg_total'], 1) }}</div>
    </div>
    <div class="kpi kpi-p">
        <div class="kpi-lbl kl-p">🎉 Karya Selesai</div>
        <div class="kpi-num kn-p">{{ $stats['completed'] }}</div>
    </div>
</div>

{{-- Grid utama --}}
<div class="dash-grid2">
    {{-- KLAIM AKTIF --}}
    <div class="card" id="klaim">
        <div class="card-h">
            <div>
                <div class="ct">Klaim Aktif Saya</div>
                <div class="cs">Status produksi terkini</div>
            </div>
            <a href="{{ route('upcycler.production') }}" class="clink">Lihat semua →</a>
        </div>
        <div class="card-b">
            @forelse($claims->whereIn('status',['claimed','processing'])->take(6) as $claim)
            <div class="post-item">
                <div class="pi-icon {{ $claim->fabric_type === 'denim' ? 'pi-b' : 'pi-g' }}">
                    {{ $claim->fabric_type === 'denim' ? '👖' : '🧵' }}
                </div>
                <div class="pi-info">
                    <div class="pi-title">{{ $claim->title }}</div>
                    <div class="pi-meta">{{ $claim->created_at->diffForHumans() }} · {{ $claim->weight }} kg · {{ ucfirst($claim->fabric_type ?? '-') }}</div>
                </div>
                <span class="sbadge sb-{{ $claim->status }}">
                    {{ ['claimed'=>'🟡 Diklaim','processing'=>'🔵 Diproses','completed'=>'✅ Selesai'][$claim->status] ?? ucfirst($claim->status) }}
                </span>
                @if($claim->status === 'claimed')
                <form method="POST" action="{{ route('upcycler.production.start', $claim->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="pi-act-btn">▶ Proses</button>
                </form>
                @elseif($claim->status === 'processing')
                <form method="POST" action="{{ route('upcycler.production.finish', $claim->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="pi-act-btn" style="background:#22c55e;">✅ Selesai</button>
                </form>
                @endif
            </div>
            @empty
            <div class="empty-state">
                <div style="font-size:48px;">🗺</div>
                <p>Belum ada klaim aktif.<br>Gunakan peta untuk menemukan limbah terdekat!</p>
                <a href="{{ route('upcycler.exploration-map') }}" class="btn-primary">Buka Peta Limbah →</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- LIMBAH TERSEDIA --}}
    <div class="card">
        <div class="card-h">
            <div>
                <div class="ct">Limbah Tersedia</div>
                <div class="cs">{{ $available->count() }} titik aktif di peta</div>
            </div>
            <a href="{{ route('upcycler.exploration-map') }}" class="clink">Buka Peta →</a>
        </div>
        <div class="card-b">
            @forelse($available->take(5) as $post)
            <div class="post-item">
                <div class="pi-icon pi-g">
                    {{ $post->fabric_type === 'denim' ? '👖' : '🧵' }}
                </div>
                <div class="pi-info">
                    <div class="pi-title">{{ $post->title }}</div>
                    <div class="pi-meta">{{ $post->weight }} kg · {{ $post->address ?? 'Lihat di peta' }}</div>
                </div>
                <a href="{{ route('upcycler.exploration-map') }}" class="btn-moss" style="font-size:11px;padding:6px 12px;">Lihat</a>
            </div>
            @empty
            <div class="empty-state"><p>Tidak ada limbah tersedia saat ini.</p></div>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick tips --}}
<div class="card">
    <div class="card-h">
        <div class="ct">💡 Tips Upcycling Hari Ini</div>
    </div>
    <div class="card-b" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
        <div class="tip-item">
            <strong>Denim → Tas Tote</strong><br>
            Kain denim tebal sangat cocok dibuat tas jinjing yang tahan lama dan estetik.
        </div>
        <div class="tip-item">
            <strong>Perca Katun → Scrunchie</strong><br>
            Potongan kecil katun bisa menjadi aksesoris rambut yang unik dan diminati pasar.
        </div>
        <div class="tip-item">
            <strong>Sutra Sisa → Hiasan</strong><br>
            Kain sutra sisa pembatik bisa dijadikan hiasan dinding bernilai seni tinggi.
        </div>
    </div>
</div>

@if(session('success'))
<div class="toast toast-success show" id="t-up">✅ {{ session('success') }}</div>
<script>setTimeout(()=>document.getElementById('t-up')?.classList.remove('show'),4000)</script>
@endif
@endsection
