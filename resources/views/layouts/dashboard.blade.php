<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — UpcycleMatch</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@stack('styles')
</head>
<body class="dash-body">

{{-- TOP NAVBAR --}}
<header class="dash-nav">
    <a href="{{ route('landing') }}" class="nav-logo">Upcycle<span>Match</span></a>
    <div class="dash-nav-center">
        @if(auth()->user()->role === 'admin')
            <span class="role-chip admin">Admin Panel</span>
        @elseif(auth()->user()->role === 'upcycler' || auth()->user()->role === 'penjahit')
            <div class="dash-tabs">
                <a href="{{ route('upcycler.dashboard') }}" class="dtab {{ request()->routeIs('upcycler.dashboard') ? 'a' : '' }}">Beranda</a>
                <a href="{{ route('upcycler.exploration-map') }}" class="dtab {{ request()->routeIs('upcycler.exploration-map') ? 'a' : '' }}">🗺 Peta Limbah</a>
                <a href="{{ route('upcycler.production') }}" class="dtab {{ request()->routeIs('upcycler.production') ? 'a' : '' }}">⚙️ Produksi</a>
                <a href="{{ route('upcycler.orders') }}" class="dtab {{ request()->routeIs('upcycler.orders') ? 'a' : '' }}" style="position:relative;">
                    🛒 Pesanan
                    @php $pendingOrders = \App\Models\Order::whereHas('product', fn($q) => $q->where('upcycler_id', auth()->id()))->where('status','pending')->count(); @endphp
                    @if($pendingOrders > 0)<span style="position:absolute;top:-4px;right:-6px;background:#ef4444;color:#fff;font-size:9px;font-weight:800;border-radius:100px;padding:1px 5px;">{{ $pendingOrders }}</span>@endif
                </a>
                <a href="{{ route('gallery') }}" class="dtab">🎨 Galeri</a>
            </div>
        @else
            {{-- TABS KHUSUS KONTRIBUTOR --}}
            <div class="dash-tabs">
                <a href="{{ route('contributor.dashboard') }}" class="dtab {{ request()->routeIs('contributor.dashboard') ? 'a' : '' }}">Beranda</a>
                <a href="{{ route('contributor.upload') }}" class="dtab {{ request()->routeIs('contributor.upload') ? 'a' : '' }}">+ Post Limbah</a>
                <a href="{{ route('contributor.orders') }}" class="dtab {{ request()->routeIs('contributor.orders') ? 'a' : '' }}">📦 Pesanan Saya</a>
                <a href="{{ route('gallery') }}" class="dtab">🎨 Galeri</a>
            </div>
        @endif
    </div>
    
    <div class="dash-nav-right">
        <span class="dash-greeting">Halo, {{ explode(' ', auth()->user()->name)[0] }}!</span>
        <div class="nav-user-menu">
            <div class="nav-avatar" onclick="toggleDropdown()">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="nav-dropdown" id="navDropdown">
                <div class="nd-header">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span class="nd-role">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                
                {{-- PERBAIKAN UTAMA: Mengubah pengecekan kata 'kontributor' menjadi 'contributor' --}}
                @if(auth()->user()->role === 'contributor')
                    <a href="{{ route('contributor.dashboard') }}" class="nd-item">📊 Dashboard</a>
                @elseif(auth()->user()->role === 'upcycler' || auth()->user()->role === 'penjahit')
                    <a href="{{ route('upcycler.dashboard') }}" class="nd-item">📊 Dashboard</a>
                @endif
                
                <div class="nd-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nd-item nd-logout">🚪 Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- FLASH TOAST NOTIFICATION --}}
@if(session('success'))
<div class="toast toast-success show" id="flashToast">✅ {{ session('success') }}</div>
@elseif(session('error'))
<div class="toast toast-error show" id="flashToast">❌ {{ session('error') }}</div>
@endif

{{-- MAIN LAYOUT STRUCTURE --}}
<div class="dash-layout">
    @if(auth()->user()->role === 'admin')
    @php
        $pendingWd = \App\Models\KoinWithdrawal::where('status','pending')->count();
        $pendingVerif = \App\Models\User::where('role','upcycler')
                        ->where('is_verified', false)
                        ->whereDoesntHave('upcyclerProfile', fn($q) => $q->where('verification_status','rejected'))
                        ->count();
        $pendingGaleri = \App\Models\Product::where('status','pending')->count();
    @endphp
    <aside class="dash-sidebar">
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sitem {{ request()->routeIs('admin.dashboard') ? 'a' : '' }}"><span>🏠</span> Beranda</a>
            <a href="{{ route('admin.analytics') }}" class="sitem {{ request()->routeIs('admin.analytics') ? 'a' : '' }}"><span>📊</span> Analytics</a>
            <div class="sdiv"></div>
            
            <a href="{{ route('admin.verification') }}" class="sitem {{ request()->routeIs('admin.verification') ? 'a' : '' }}" style="justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;"><span>👥</span> Verifikasi Upcycler</div>
                @if($pendingVerif > 0)<div style="background:#ef4444;color:#fff;font-size:11px;font-weight:800;border-radius:6px;padding:2px 6px;line-height:1;">{{ $pendingVerif }}</div>@endif
            </a>
            <a href="{{ route('admin.withdrawals') }}" class="sitem {{ request()->routeIs('admin.withdrawals') ? 'a' : '' }}" style="justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;"><span>💰</span> Pencairan Koin</div>
                @if($pendingWd > 0)<div style="background:#ef4444;color:#fff;font-size:11px;font-weight:800;border-radius:6px;padding:2px 6px;line-height:1;">{{ $pendingWd }}</div>@endif
            </a>
            
            <div class="sdiv"></div>
            <a href="{{ route('admin.limbah') }}" class="sitem {{ request()->routeIs('admin.limbah') ? 'a' : '' }}"><span>🗃</span> Data Limbah</a>
            <a href="{{ route('admin.galeri') }}" class="sitem {{ request()->routeIs('admin.galeri') ? 'a' : '' }}" style="justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;"><span>🖼</span> Galeri Karya</div>
                @if($pendingGaleri > 0)<div style="background:#F59E0B;color:#fff;font-size:11px;font-weight:800;border-radius:6px;padding:2px 6px;line-height:1;">{{ $pendingGaleri }}</div>@endif
            </a>
            
            <div class="sdiv"></div>
            <a href="{{ route('admin.report') }}" class="sitem {{ request()->routeIs('admin.report') ? 'a' : '' }}"><span>📥</span> Export Report</a>
        </nav>
    </aside>
    @endif
    <main class="dash-main">@yield('content')</main>
</div>

<script>
function toggleDropdown(){ 
    document.getElementById('navDropdown')?.classList.toggle('open'); 
}
document.addEventListener('click', e => {
    if(!e.target.closest('.nav-user-menu')) {
        document.getElementById('navDropdown')?.classList.remove('open');
    }
});
const t = document.getElementById('flashToast');
if(t) setTimeout(() => t.classList.remove('show'), 4000);
</script>
@stack('scripts')
</body>
</html>