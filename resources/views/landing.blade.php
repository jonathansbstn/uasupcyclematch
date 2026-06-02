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
      transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease, background 0.22s ease;
      position: relative;
      overflow: hidden;
    }
    .fabric-card::after {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: var(--radius);
      background: linear-gradient(135deg, rgba(131,153,88,0.08) 0%, transparent 60%);
      opacity: 0;
      transition: opacity 0.22s ease;
    }
    .fabric-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 32px rgba(10,51,35,0.13);
      border-color: var(--moss);
    }
    .fabric-card:hover::after { opacity: 1; }
    .fabric-card.dark {
      background: var(--dk-green);
      color: var(--text-on-dk);
      border-color: var(--dk-green);
    }
    .fabric-card.dark::after {
      background: linear-gradient(135deg, rgba(247,244,213,0.07) 0%, transparent 60%);
    }
    .fabric-card.dark:hover {
      border-color: var(--moss);
      box-shadow: 0 12px 32px rgba(10,51,35,0.25);
    }
    .fabric-emoji {
      font-size: 40px;
      margin-bottom: 0.75rem;
      display: inline-block;
      transition: transform 0.22s ease;
    }
    .fabric-card:hover .fabric-emoji { transform: scale(1.18) rotate(-5deg); }
    .fabric-name { font-weight: 700; font-size: 18px; }
    .fabric-desc { font-size: 13px; opacity: 0.8; margin-top: 6px; line-height: 1.4; }
    .fabric-hint {
      font-size: 11px;
      margin-top: 12px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 100px;
      background: rgba(131,153,88,0.12);
      color: var(--moss);
      font-weight: 700;
      opacity: 0;
      transform: translateY(4px);
      transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .fabric-card.dark .fabric-hint {
      background: rgba(247,244,213,0.12);
      color: var(--beige);
    }
    .fabric-card:hover .fabric-hint {
      opacity: 1;
      transform: translateY(0);
    }

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

    /* KAMUS INTERAKTIF */
    .fabric-filter { display: flex; gap: 8px; padding: 0 3rem 1.5rem; flex-wrap: wrap; }
    .filter-btn {
      padding: 6px 16px; border-radius: 100px; border: 1.5px solid var(--beige-dark);
      background: white; font-size: 13px; font-weight: 600; cursor: pointer;
      color: var(--dk-green); transition: all 0.2s;
    }
    .filter-btn:hover, .filter-btn.active { background: var(--dk-green); color: var(--beige); border-color: var(--dk-green); }
    .fabric-card { cursor: pointer; }
    .fabric-hint { font-size: 11px; opacity: 0.5; margin-top: 10px; }

    /* MODAL KAIN */
    .fabric-modal-overlay {
      display: none; position: fixed; inset: 0; background: rgba(10,51,35,0.6);
      z-index: 1000; align-items: center; justify-content: center;
    }
    .fabric-modal-overlay.open { display: flex; }
    .fabric-modal {
      background: var(--beige); border-radius: var(--radius-lg); padding: 2.5rem;
      max-width: 480px; width: 90%; position: relative;
    }
    .modal-close {
      position: absolute; top: 1rem; right: 1.25rem; background: none;
      border: none; font-size: 22px; cursor: pointer; color: var(--dk-green);
    }
    .modal-emoji { font-size: 52px; margin-bottom: 0.75rem; }
    .modal-name { font-family: 'DM Serif Display', serif; font-size: 28px; margin-bottom: 0.5rem; }
    .modal-desc { font-size: 15px; line-height: 1.6; opacity: 0.8; margin-bottom: 1rem; }
    .modal-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .modal-tag {
      background: var(--moss); color: var(--text-on-moss);
      padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 700;
    }
    .modal-value {
      margin-top: 1.25rem; background: var(--dk-green); color: var(--text-on-dk);
      border-radius: var(--radius); padding: 1rem 1.25rem; font-size: 14px; line-height: 1.5;
    }
    .modal-value strong { color: var(--moss); }

    /* GALLERY DENGAN FOTO */
    .gallery-card-photo {
      background-size: cover; background-position: center;
      position: relative; overflow: hidden;
    }
    .gallery-card-photo::before {
      content: ''; position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(10,51,35,0.85) 40%, transparent 100%);
    }
    .gallery-card-photo .gallery-badge,
    .gallery-card-photo .gallery-title,
    .gallery-card-photo .gallery-meta { position: relative; z-index: 1; }
    .gallery-card-photo .gallery-title { color: #fff; }
    .gallery-card-photo .gallery-meta { color: rgba(255,255,255,0.8); }
    .gallery-card-photo .gallery-badge { background: rgba(255,255,255,0.25); color: #fff; }
    .gallery-empty {
      grid-column: 1/-1; text-align: center; padding: 3rem;
      background: white; border-radius: var(--radius); border: 1.5px dashed var(--beige-dark);
    }
    .gallery-empty-icon { font-size: 48px; margin-bottom: 1rem; }
    .gallery-empty-title { font-family: 'DM Serif Display', serif; font-size: 22px; }
    .gallery-empty-sub { font-size: 14px; opacity: 0.7; margin-top: 4px; }
    .gallery-cta { margin-top: 2rem; text-align: center; padding-bottom: 1rem; }
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
      <div class="counter-num">{{ number_format($totalWeight, 1, ',', '.') }}</div>
      <div class="counter-label">Total Berat Limbah Kain Terdaftar di Platform</div>
      
      <div style="display:flex; gap:3rem; margin-top:3rem; text-align:center;">
        <div>
          <div style="font-size:24px; font-weight:700; font-family:'DM Serif Display', serif;">{{ $totalContribs }}</div>
          <div style="font-size:13px; opacity:0.8;">Kontributor</div>
        </div>
        <div style="width:1px; background:rgba(247,244,213,0.2);"></div>
        <div>
          <div style="font-size:24px; font-weight:700; font-family:'DM Serif Display', serif;">{{ $totalUpcyclers }}</div>
          <div style="font-size:13px; opacity:0.8;">Mitra Penjahit</div>
        </div>
        <div style="width:1px; background:rgba(247,244,213,0.2);"></div>
        <div>
          <div style="font-size:24px; font-weight:700; font-family:'DM Serif Display', serif;">{{ $totalProducts }}</div>
          <div style="font-size:13px; opacity:0.8;">Produk Upcycle</div>
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

  {{-- ======= KAMUS KAIN INTERAKTIF ======= --}}
  <div class="section-pad">
    <div class="section-title">Kamus Kain Interaktif 📚</div>
    <div class="section-sub">Kenali jenis bahan kainmu sebelum dibuang — klik kartu untuk melihat potensi nilai ekonominya!</div>
  </div>

  <div class="fabric-filter">
    <button class="filter-btn active" onclick="filterFabric('all', this)">Semua</button>
    <button class="filter-btn" onclick="filterFabric('alami', this)">🌿 Alami</button>
    <button class="filter-btn" onclick="filterFabric('sintetis', this)">⚗️ Sintetis</button>
    <button class="filter-btn" onclick="filterFabric('premium', this)">✨ Premium</button>
  </div>

  <div class="fabric-grid">
    <div class="fabric-card" data-cat="alami" onclick="openModal('katun')">
      <div class="fabric-emoji">👕</div>
      <div class="fabric-name">Katun</div>
      <div class="fabric-desc">Mudah didaur ulang · Menyerap air · Sangat bagus untuk kreasi kain perca.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
    <div class="fabric-card" data-cat="alami" onclick="openModal('denim')">
      <div class="fabric-emoji">👖</div>
      <div class="fabric-name">Denim</div>
      <div class="fabric-desc">Sangat awet · Bernilai tinggi · Paling populer untuk di-upcycle menjadi produk tas.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
    <div class="fabric-card" data-cat="premium" onclick="openModal('sutra')">
      <div class="fabric-emoji">✨</div>
      <div class="fabric-name">Sutra</div>
      <div class="fabric-desc">Kain premium · Butuh penanganan ekstra · Nilai jual kembali produk kerajinan sangat tinggi.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
    <div class="fabric-card" data-cat="sintetis" onclick="openModal('polyester')">
      <div class="fabric-emoji">🧵</div>
      <div class="fabric-name">Polyester</div>
      <div class="fabric-desc">Kuat, elastis, tidak mudah menyusut · Cocok dijadikan aksesoris serbaguna baru.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
    <div class="fabric-card" data-cat="alami" onclick="openModal('linen')">
      <div class="fabric-emoji">🌾</div>
      <div class="fabric-name">Linen</div>
      <div class="fabric-desc">Terbuat dari tanaman rami · Ramah lingkungan · Makin lembut seiring pemakaian.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
    <div class="fabric-card" data-cat="sintetis" onclick="openModal('nylon')">
      <div class="fabric-emoji">🪡</div>
      <div class="fabric-name">Nylon</div>
      <div class="fabric-desc">Sangat kuat dan ringan · Tahan air · Cocok untuk produk outdoor dan aksesoris.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
    <div class="fabric-card" data-cat="premium" onclick="openModal('wool')">
      <div class="fabric-emoji">🐑</div>
      <div class="fabric-name">Wool</div>
      <div class="fabric-desc">Hangat dan tahan lama · Alami dari bulu domba · Sangat dicari pengrajin tas dan topi.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
    <div class="fabric-card" data-cat="alami" onclick="openModal('rayon')">
      <div class="fabric-emoji">🌀</div>
      <div class="fabric-name">Rayon / Viscose</div>
      <div class="fabric-desc">Lembut seperti sutra · Menyerap keringat · Populer untuk kain baju dan blouse.</div>
      <div class="fabric-hint">Klik untuk detail ↗</div>
    </div>
  </div>

  {{-- MODAL DETAIL KAIN --}}
  <div class="fabric-modal-overlay" id="fabricModal" onclick="closeModal(event)">
    <div class="fabric-modal">
      <button class="modal-close" onclick="document.getElementById('fabricModal').classList.remove('open')">✕</button>
      <div class="modal-emoji" id="modalEmoji"></div>
      <div class="modal-name" id="modalName"></div>
      <div class="modal-desc" id="modalDesc"></div>
      <div class="modal-tags" id="modalTags"></div>
      <div class="modal-value" id="modalValue"></div>
    </div>
  </div>

  {{-- ======= UPCYCLE GALLERY ======= --}}
  <div class="section-pad" style="padding-top: 4rem;">
    <div class="section-title">Upcycle Gallery ✂️</div>
    <div class="section-sub">Karya nyata dari mitra penjahit lokal — mengubah limbah sisa pakaian menjadi produk estetik berdaya saing!</div>
  </div>

  <div class="gallery-row">
    @forelse($galleryProducts as $index => $product)
      @php
        $gColors = ['g-1', 'g-2', 'g-3'];
        $gClass  = $gColors[$index % 3];
        $hasPhoto = $product->photo && file_exists(public_path('storage/' . $product->photo));
        $fabricType = optional($product->textile)->fabric_type ?? $product->category ?? 'Limbah Kain';
        $weight = optional($product->textile)->weight ?? 0;
        $upcyclerName = optional($product->upcycler)->name ?? 'Pengrajin Lokal';
        $productName = $product->name ?? $product->product_name ?? 'Produk Upcycle';
      @endphp
      <div class="gallery-card {{ $gClass }} {{ $hasPhoto ? 'gallery-card-photo' : '' }}"
        @if($hasPhoto) style="background-image: url('{{ asset('storage/' . $product->photo) }}')" @endif>
        <div class="gallery-badge">{{ Str::limit($fabricType, 20) }} &rarr; {{ Str::limit($product->category ?? 'Karya', 15) }}</div>
        <div>
          <div class="gallery-title">{{ $productName }}</div>
          <div class="gallery-meta">oleh {{ $upcyclerName }}{{ $weight > 0 ? ' · ' . number_format($weight, 1) . ' kg limbah' : '' }}</div>
        </div>
      </div>
    @empty
      <div class="gallery-empty">
        <div class="gallery-empty-icon">🧶</div>
        <div class="gallery-empty-title">Belum Ada Produk Dipublikasikan</div>
        <div class="gallery-empty-sub">Daftarkan diri Anda sebagai Upcycler dan unggah produk pertama Anda!</div>
        <a href="{{ route('register') }}" class="btn btn-solid" style="margin-top:1.5rem;">Daftar Sebagai Upcycler</a>
      </div>
    @endforelse
  </div>

  @if($galleryProducts->count() > 0)
  <div class="gallery-cta">
    <a href="{{ route('gallery') }}" class="btn btn-solid" style="padding:12px 28px;">
      Lihat Semua Produk <i class="ti ti-arrow-right"></i>
    </a>
  </div>
  @endif

  <script>
    const fabricData = {
      katun:     { emoji:'👕', name:'Katun', desc:'Kain paling umum di Indonesia. Berasal dari serat kapas alami yang sangat mudah diolah kembali. Kain perca katun sangat dicari oleh UMKM penjahit untuk dibuat produk baru.', tags:['Alami','Ramah Lingkungan','Mudah Diolah'], value:'<strong>Nilai Ekonomi:</strong> Kain perca katun bisa diolah menjadi tas kanvas, sarung bantal, boneka, hingga baju anak. Harga jual produk Rp 25.000 – Rp 150.000.' },
      denim:     { emoji:'👖', name:'Denim', desc:'Kain denim dari serat kapas yang ditenun rapat dan dicelup nila. Sangat tahan lama dan makin "vintage" seiring waktu. Limbah denim memiliki nilai jual tertinggi di pasar upcycle.', tags:['Alami','Bernilai Tinggi','Vintage'], value:'<strong>Nilai Ekonomi:</strong> Limbah denim diolah menjadi tas, dompet, jaket, dan furnitur. Produk upcycle denim bisa dijual Rp 75.000 – Rp 500.000.' },
      sutra:     { emoji:'✨', name:'Sutra', desc:'Kain premium dari serat ulat sutra. Ringan, mengkilap, dan sangat nyaman di kulit. Membutuhkan penanganan ekstra hati-hati namun produk kerajinannya bernilai sangat tinggi.', tags:['Premium','Alami','Mewah'], value:'<strong>Nilai Ekonomi:</strong> Limbah sutra cocok untuk aksesori mewah, syal, dan pouch premium. Harga jual Rp 150.000 – Rp 1.000.000+.' },
      polyester: { emoji:'🧵', name:'Polyester', desc:'Serat sintetis yang kuat, elastis, dan tidak mudah kusut. Paling banyak diproduksi di dunia. Limbah polyester tetap bisa diolah menjadi berbagai produk aksesoris dan pelengkap.', tags:['Sintetis','Elastis','Tahan Lama'], value:'<strong>Nilai Ekonomi:</strong> Polyester dapat dijadikan tas lipat, sarung handphone, dan dekorasi. Harga produk jadi Rp 15.000 – Rp 100.000.' },
      linen:     { emoji:'🌾', name:'Linen', desc:'Terbuat dari tanaman rami (flax). Salah satu kain tertua di dunia yang ramah lingkungan. Makin sering dicuci makin lembut. Sangat cocok untuk produk rumah tangga ekologis.', tags:['Alami','Ramah Lingkungan','Eco'], value:'<strong>Nilai Ekonomi:</strong> Linen diolah menjadi tote bag, celemek, placemat, dan pouch. Produk eco-linen diminati pasar ekspor, harga Rp 50.000 – Rp 300.000.' },
      nylon:     { emoji:'🪡', name:'Nylon', desc:'Serat sintetis pertama di dunia. Sangat kuat, ringan, dan tahan air. Banyak dipakai untuk jaket, ransel, dan perlengkapan outdoor. Limbah nylon memiliki pasar yang terus berkembang.', tags:['Sintetis','Tahan Air','Kuat'], value:'<strong>Nilai Ekonomi:</strong> Limbah nylon dijadikan tas gym, dompet waterproof, dan cover sepatu. Harga produk Rp 30.000 – Rp 200.000.' },
      wool:      { emoji:'🐑', name:'Wool', desc:'Serat alami dari bulu domba. Hangat, breathable, dan tahan lama secara alami. Limbah wool sangat dicari pengrajin topi, syal, dan tas berkualitas tinggi.', tags:['Premium','Alami','Hangat'], value:'<strong>Nilai Ekonomi:</strong> Wool limbah diolah menjadi topi, syal, dan aksesori musiman. Produk wool upcycle dijual Rp 100.000 – Rp 600.000.' },
      rayon:     { emoji:'🌀', name:'Rayon / Viscose', desc:'Serat semi-sintetis dari selulosa kayu. Terasa lembut seperti sutra namun harganya lebih terjangkau. Sangat menyerap keringat dan nyaman dipakai di iklim tropis seperti Indonesia.', tags:['Semi-Sintetis','Lembut','Nyaman'], value:'<strong>Nilai Ekonomi:</strong> Rayon sisa diolah menjadi scrunchie, bandana, dan blouse patchwork. Harga produk jadi Rp 20.000 – Rp 120.000.' },
    };
    function openModal(key) {
      const d = fabricData[key];
      if (!d) return;
      document.getElementById('modalEmoji').textContent = d.emoji;
      document.getElementById('modalName').textContent  = d.name;
      document.getElementById('modalDesc').textContent  = d.desc;
      document.getElementById('modalTags').innerHTML    = d.tags.map(t => `<span class="modal-tag">${t}</span>`).join('');
      document.getElementById('modalValue').innerHTML   = d.value;
      document.getElementById('fabricModal').classList.add('open');
    }
    function closeModal(e) {
      if (e.target === document.getElementById('fabricModal')) {
        document.getElementById('fabricModal').classList.remove('open');
      }
    }
    function filterFabric(cat, btn) {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.querySelectorAll('.fabric-card').forEach(card => {
        card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
      });
    }
  </script>

@include('partials.footer')

</body>
</html>