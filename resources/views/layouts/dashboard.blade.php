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
                <a href="{{ route('penjahit.dashboard') }}" class="dtab {{ request()->routeIs('penjahit.dashboard') ? 'a' : '' }}">Beranda</a>
                <a href="#" class="dtab">Peta Limbah</a>
                <a href="#" class="dtab">Klaim Saya</a>
                <a href="#" class="dtab">Upload Karya</a>
            </div>
        @else
            {{-- TABS KHUSUS KONTRIBUTOR (Sudah disinkronkan jalurnya) --}}
            <div class="dash-tabs">
                <a href="{{ route('contributor.dashboard') }}" class="dtab {{ request()->routeIs('contributor.dashboard') ? 'a' : '' }}">Beranda</a>
                <a href="#" class="dtab">Postingan Saya</a>
                <a href="/upload-limbah" class="dtab">+ Post Limbah</a>
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
                    <a href="{{ route('penjahit.dashboard') }}" class="nd-item">📊 Dashboard</a>
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
    <aside class="dash-sidebar">
        <nav class="sidebar-nav">
            <a href="#" class="sitem a"><span>🏠</span> Beranda</a>
            <a href="#" class="sitem"><span>📊</span> Analytics</a>
            <a href="#" class="sitem"><span>👥</span> Verifikasi User</a>
            <div class="sdiv"></div>
            <a href="#" class="sitem"><span>🗃</span> Data Limbah</a>
            <a href="#" class="sitem"><span>🖼</span> Galeri Karya</a>
            <div class="sdiv"></div>
            <a href="#" class="sitem"><span>📥</span> Export Report</a>
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