<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UpcycleMatch - Jembatan Limbah Kain & Kreativitas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Nunito:wght@400;500;600;700&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --dk-green: #0A3323;
      --moss: #839958;
      --beige: #F7F4D5;
      --rosy: #D3968C;
      --mid: #105666;
      --beige-dark: #E8E4B8;
      --text-on-dk: #F7F4D5;
      --text-on-moss: #0A3323;
      --text-on-beige: #0A3323;
      --radius: 12px;
      --radius-sm: 8px;
      --radius-lg: 20px;
    }

    body {
      font-family: 'Nunito', sans-serif;
      background: var(--beige);
      color: var(--dk-green);
      min-height: 100vh;
    }

    /* NAV */
    .nav {
      background: var(--dk-green);
      padding: 0 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 56px;
      position: sticky; top: 0; z-index: 100;
    }
    .nav-logo {
      font-family: 'DM Serif Display', serif;
      font-size: 24px;
      color: var(--beige);
      text-decoration: none;
    }
    .nav-logo span { color: var(--rosy); }
    .nav-right { display: flex; gap: 12px; }

    /* BUTTONS */
    .btn {
      padding: 8px 16px;
      border-radius: var(--radius-sm);
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
    }
    .btn-outline {
      border: 1.5px solid var(--text-on-dk);
      color: var(--text-on-dk);
      background: transparent;
    }
    .btn-outline:hover {
      background: rgba(247,244,213,0.1);
    }
    .btn-solid {
      background: var(--moss);
      color: var(--text-on-moss);
      border: none;
    }
    .btn-solid:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(131,153,88,0.3);
    }

    /* HERO */
    .hero {
      display: grid;
      grid-template-columns: 1.2fr 0.8fr;
      min-height: calc(85vh - 56px);
    }
    .hero-left {
      padding: 4rem 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .hero-tag {
      background: rgba(131,153,88,0.2);
      color: var(--dk-green);
      padding: 4px 12px;
      border-radius: 100px;
      font-size: 12px;
      font-weight: 700;
      width: fit-content;
      margin-bottom: 1.5rem;
    }
    .hero-title {
      font-family: 'DM Serif Display', serif;
      font-size: 54px;
      line-height: 1.1;
      margin-bottom: 1.5rem;
    }
    .hero-title em { color: var(--mid); font-style: normal; }
    .hero-sub {
      font-size: 16px;
      line-height: 1.6;
      margin-bottom: 2.5rem;
      max-width: 480px;
      color: rgba(10,51,35,0.8);
    }
    .hero-actions { display: flex; gap: 14px; }

    .hero-right {
      background: var(--dk-green);
      color: var(--text-on-dk);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem;
      position: relative;
    }
    .counter-num {
      font-family: 'DM Serif Display', serif;
      font-size: 72px;
      color: var(--beige);
      line-height: 1;
    }
    .counter-kg { font-size: 28px; color: var(--moss); font-weight: 700; }
    .counter-label { font-size: 14px; opacity: 0.8; margin-top: 4px; text-align: center; }

    /* STATS BAR */
    .stats-bar {
      background: var(--mid);
      color: var(--text-on-dk);
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      padding: 1.5rem;
      text-align: center;
    }
    .stat-num { font-family: 'DM Serif Display', serif; font-size: 28px; }
    .stat-lbl { font-size: 11px; opacity: 0.7; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px; }

    /* GRID SECTIONS */
    .section-pad { padding: 4rem 3rem 1.5rem; }
    .section-title { font-family: 'DM Serif Display', serif; font-size: 32px; }
    .section-sub { font-size: 15px; opacity: 0.8; margin-top: 4px; }

    .fabric-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
      padding: 0 3rem 4rem;
    }
    .fabric-card {
      background: #ffffff;
      border: 1.5px solid var(--beige-dark);
      border-radius: var(--radius);
      padding: 1.75rem;
      text-align: center;
      transition: transform 0.2s;
    }
    .fabric-card:hover { transform: translateY(-3px); }
    .fabric-card.dark {
      background: var(--dk-green);
      color: var(--text-on-dk);
      border-color: var(--dk-green);
    }
    .fabric-emoji { font-size: 40px; margin-bottom: 0.75rem; }
    .fabric-name { font-weight: 700; font-size: 18px; }
    .fabric-desc { font-size: 13px; opacity: 0.8; margin-top: 6px; line-height: 1.4; }

    /* GALLERY */
    .gallery-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.25rem;
      padding: 0 3rem 5rem;
    }
    .gallery-card {
      border-radius: var(--radius);
      height: 200px;
      padding: 1.75rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.2s;
    }
    .gallery-card:hover { transform: translateY(-3px); }
    .g-1 { background: var(--moss); color: var(--text-on-moss); }
    .g-2 { background: var(--mid); color: var(--text-on-dk); }
    .g-3 { background: var(--rosy); color: var(--text-on-moss); }
    
    .gallery-badge {
      background: rgba(255,255,255,0.25);
      padding: 4px 10px;
      border-radius: 100px;
      font-size: 11px;
      font-weight: 700;
      width: fit-content;
    }
    .g-1 .gallery-badge, .g-3 .gallery-badge { background: rgba(10,51,35,0.1); }

    .gallery-title { font-family: 'DM Serif Display', serif; font-size: 20px; }
    .gallery-meta { font-size: 13px; opacity: 0.8; margin-top: 2px; }
  </style>
</head>
<body>

  <nav class="nav">
    <a href="{{ route('landing') }}" class="nav-logo">Upcycle<span>Match</span></a>
    <div class="nav-right">
      @auth
        @php
          $dashRoute = match(auth()->user()->role) {
            'admin'       => route('admin.dashboard'),
            'upcycler'    => route('upcycler.dashboard'),
            'contributor' => route('contributor.dashboard'),
            default       => route('landing'),
          };
        @endphp
        <a href="{{ $dashRoute }}" class="btn btn-solid">Dashboard Saya</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-outline">Keluar</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-solid">Daftar</a>
      @endauth
    </div>
  </nav>

  <div class="hero">
    <div class="hero-left">
      <div class="hero-tag">🌿 SDG 12 — Circular Economy</div>
      <h1 class="hero-title">Limbah Kainmu<br>Jadi Karya <em>Bernilai</em></h1>
      <p class="hero-sub">Platform terintegrasi yang menghubungkan limbah kain masyarakat dengan pengrajin lokal dan UMKM penjahit kreatif. Bersama kurangi sampah tekstil nasional.</p>
      <div class="hero-actions">
        @auth
        <a href="{{ $dashRoute }}" class="btn btn-solid" style="padding:12px 24px; border-radius:var(--radius);">
          Buka Dashboard <i class="ti ti-arrow-right"></i>
        </a>
        @else
        <a href="{{ route('register') }}" class="btn btn-solid" style="padding:12px 24px; border-radius:var(--radius);">
          Mulai Sekarang <i class="ti ti-arrow-right"></i>
        </a>
        @endauth
      </div>
    </div>
    
    <div class="hero-right">
      <div class="counter-kg">Kg</div>
      <div class="counter-num">12,847</div>
      <div class="counter-label">Total Sampah Tekstil Diselamatkan Hari Ini</div>
      
      <div style="display:flex; gap:3rem; margin-top:3rem; text-align:center;">
        <div>
          <div style="font-size:24px; font-weight:700; font-family:'DM Serif Display', serif;">342</div>
          <div style="font-size:13px; opacity:0.8;">Kontributor</div>
        </div>
        <div style="width:1px; background:rgba(247,244,213,0.2);"></div>
        <div>
          <div style="font-size:24px; font-weight:700; font-family:'DM Serif Display', serif;">89</div>
          <div style="font-size:13px; opacity:0.8;">Mitra Penjahit</div>
        </div>
      </div>
    </div>
  </div>

  <div class="stats-bar">
    <div class="stat-item"><div class="stat-num">Rp 4.5M+</div><div class="stat-lbl">Modal Bahan Baku Dihemat</div></div>
    <div class="stat-item"><div class="stat-num">127 Kota</div><div class="stat-lbl">Jangkauan Wilayah</div></div>
    <div class="stat-item"><div class="stat-num">98%</div><div class="stat-lbl">Kepuasan Penjahit</div></div>
    <div class="stat-item"><div class="stat-num">2.1 Ton</div><div class="stat-lbl">CO₂ Tersimpan</div></div>
  </div>

  <div class="section-pad">
    <div class="section-title">Kamus Kain Interaktif 📚</div>
    <div class="section-sub">Kenali jenis bahan kainmu sebelum dibuang — barangkali punya nilai ekonomi tinggi!</div>
  </div>
  
  <div class="fabric-grid">
    <div class="fabric-card">
      <div class="fabric-emoji">👕</div>
      <div class="fabric-name">Katun</div>
      <div class="fabric-desc">Mudah didaur ulang · Menyerap air · Sangat bagus untuk kreasi kain perca.</div>
    </div>
    <div class="fabric-card dark">
      <div class="fabric-emoji">👖</div>
      <div class="fabric-name">Denim</div>
      <div class="fabric-desc">Sangat awet · Bernilai tinggi · Paling populer untuk di-upcycle menjadi produk tas.</div>
    </div>
    <div class="fabric-card">
      <div class="fabric-emoji">✨</div>
      <div class="fabric-name">Sutra</div>
      <div class="fabric-desc">Kain premium · Butuh penanganan ekstra · Nilai jual kembali produk kerajinan sangat tinggi.</div>
    </div>
    <div class="fabric-card">
      <div class="fabric-emoji">🧵</div>
      <div class="fabric-name">Polyester</div>
      <div class="fabric-desc">Kuat, elastis, tidak mudah menyusut · Cocok dijadikan aksesoris serbaguna baru.</div>
    </div>
  </div>

  <div class="section-pad">
    <div class="section-title">Upcycle Gallery ✂️</div>
    <div class="section-sub">Karya nyata dari mitra penjahit lokal — mengubah limbah sisa pakaian menjadi produk estetik berdaya saing!</div>
  </div>
  
  <div class="gallery-row">
    <div class="gallery-card g-1">
      <div class="gallery-badge">Denim &rarr; Tas</div>
      <div>
        <div class="gallery-title">Tote Bag Denim</div>
        <div class="gallery-meta">oleh Jahit Mandiri · 1.2 kg dihemat</div>
      </div>
    </div>
    <div class="gallery-card g-2">
      <div class="gallery-badge">Katun &rarr; Aksesoris</div>
      <div>
        <div class="gallery-title">Scrunchie Set</div>
        <div class="gallery-meta">oleh Kreasi Nusantara · 0.4 kg dihemat</div>
      </div>
    </div>
    <div class="gallery-card g-3">
      <div class="gallery-badge">Perca &rarr; Rumahtangga</div>
      <div>
        <div class="gallery-title">Keset Patchwork</div>
        <div class="gallery-meta">oleh Umi's Craft · 2.1 kg dihemat</div>
      </div>
    </div>
  </div>

</body>
</html>