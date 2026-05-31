<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - UpcycleMatch</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- 🎨 CSS LANGSUNG UNTUK NAVBAR RESMI --}}
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #F7F4D5;
        }
        .navbar { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 20px 48px; 
            background: #0A3323; 
            position: relative;
            z-index: 999;
        }
        .nav-logo { 
            font-family: 'Syne', sans-serif; 
            font-size: 20px; 
            font-weight: 800; 
            color: #F7F4D5; 
            letter-spacing: -0.5px; 
            text-decoration: none;
        }
        .nav-logo span { color: #839958; }
        .nav-links { display: flex; gap: 28px; align-items: center; }
        .nav-link { 
            font-family: 'DM Sans', sans-serif; 
            font-size: 14px; 
            color: #9FE1CB; 
            text-decoration: none; 
            font-weight: 400; 
            transition: color 0.2s; 
        }
        .nav-link:hover { color: #F7F4D5; }
        .nav-actions { display: flex; align-items: center; gap: 15px; }
        .nav-cta { 
            background: #839958; 
            color: #0A3323; 
            padding: 8px 20px; 
            border-radius: 100px; 
            font-family: 'Syne', sans-serif; 
            font-size: 13px; 
            font-weight: 700; 
            border: none; 
            cursor: pointer; 
            text-decoration: none; 
            transition: opacity 0.2s; 
        }
        .nav-cta.outline {
            background: transparent;
            color: #9FE1CB;
            border: 1px solid #9FE1CB;
        }
        .nav-cta:hover { opacity: 0.9; }
        
        /* Dropdown User Menu */
        .nav-user-menu { position: relative; }
        .nav-avatar { 
            width: 35px; height: 35px; background: #839958; color: #0A3323; 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            font-weight: bold; cursor: pointer; user-select: none;
        }
        .nav-dropdown { 
            display: none; position: absolute; right: 0; top: 45px; 
            background: white; border: 1px solid #ccc; border-radius: 8px; 
            width: 200px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); padding: 10px 0;
        }
        .nav-dropdown.show { display: block; }
        .nd-header { padding: 5px 15px 10px; border-bottom: 1px solid #eee; display: flex; flex-direction: column; }
        .nd-header strong { color: #0A3323; font-size: 14px; }
        .nd-role { font-size: 11px; color: #666; }
        .nd-item { display: block; padding: 8px 15px; color: #333; text-decoration: none; font-size: 13px; font-family: 'DM Sans', sans-serif; }
        .nd-item:hover { background: #f5f5f5; }
        .nd-divider { height: 1px; background: #eee; margin: 5px 0; }
        .nd-logout { width: 100%; text-align: left; background: none; border: none; cursor: pointer; color: red !important; font-weight: bold; }
    </style>
</head>
<body>

    {{-- 🎯 NAVBAR RESMI --}}
    <nav class="navbar" id="mainNav">
        <a href="{{ route('landing') }}" class="nav-logo">Upcycle<span>Match</span></a>
        <div class="nav-links">
            <a href="{{ route('landing') }}#kamus" class="nav-link">Kamus Kain</a>
            <a href="{{ route('landing') }}#gallery" class="nav-link">Galeri</a>
            <a href="{{ route('landing') }}#how" class="nav-link">Cara Kerja</a>
            <a href="{{ route('landing') }}#tentang" class="nav-link">Tentang</a>
        </div>
        <div class="nav-actions">
            @guest
                <a href="{{ route('login') }}" class="nav-cta outline">Login</a>
                <a href="{{ route('register') }}" class="nav-cta">Daftar Gratis</a>
            @else
                <div class="nav-user-menu">
                    <div class="nav-avatar" onclick="toggleDropdown()">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="nav-dropdown" id="navDropdown">
                        <div class="nd-header">
                            <strong>{{ auth()->user()->name }}</strong>
                            <span class="nd-role">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
                        </div>
                        
                        {{-- 🔀 DYNAMIC ROUTING UNTUK DASHBOARD SESUAI ROLE DI WEB.PHP --}}
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nd-item">📊 Dashboard Admin</a>
                        @elseif(auth()->user()->role === 'upcycler')
                            <a href="{{ route('upcycler.dashboard') }}" class="nd-item">📊 Dashboard Upcycler</a>
                            <a href="{{ route('upcycler.materials') }}" class="nd-item">🗺 Kain Tersedia</a>
                        @else
                            <a href="{{ route('contributor.dashboard') }}" class="nd-item">📊 Dashboard Kontributor</a>
                            <a href="{{ route('contributor.upload') }}" class="nd-item">➕ Post Limbah</a>
                            <a href="{{ route('contributor.peta') }}" class="nd-item">🗺 Peta Logistik</a>
                        @endif

                        <div class="nd-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nd-item nd-logout">🚪 Keluar</button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </nav>

    {{-- 🎽 TEMPAT KONTEN --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- 📜 TEMPAT SCRIPT JAVASCRIPT --}}
    @stack('scripts')

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('navDropdown');
            if(dropdown) dropdown.classList.toggle('show');
        }
        // Tutup dropdown otomatis jika klik di luar avatar
        window.onclick = function(event) {
            if (!event.target.matches('.nav-avatar')) {
                const dropdowns = document.getElementsByClassName("nav-dropdown");
                for (let i = 0; i < dropdowns.length; i++) {
                    let openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>