{{-- NAVBAR RESMI UPCYCLEMATCH --}}
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
                    {{ strtoupper(substr(auth()->user()->nama_depan ?? auth()->user()->name, 0, 1)) }}
                </div>
                <div class="nav-dropdown" id="navDropdown">
                    <div class="nd-header">
                        <strong>{{ auth()->user()->nama_depan ?? auth()->user()->name }}</strong>
                        <span class="nd-role">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
                    </div>
                    
                    <a href="{{ route('dashboard') }}" class="nd-item">📊 Dashboard</a>
                    
                    @if(auth()->user()->role === 'contributor')
                        <a href="{{ route('limbah.create') }}" class="nd-item">➕ Post Limbah</a>
                    @endif
                    
                    @if(auth()->user()->role === 'upcycler')
                        <a href="{{ route('peta.index') }}" class="nd-item">🗺 Peta Limbah</a>
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

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('navDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }
    // Menutup dropdown jika klik di luar avatar
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