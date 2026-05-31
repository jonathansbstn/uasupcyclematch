<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeri Karya Upcycle — UpcycleMatch</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root {
      --dk-green: #0A3323; --moss: #839958; --beige: #F7F4D5;
      --rosy: #D3968C; --mid: #105666;
    }
    body { font-family:'DM Sans',sans-serif; background:var(--beige); color:var(--dk-green); }

    /* NAV */
    .nav { background:var(--dk-green); padding:0 2.5rem; display:flex; align-items:center; justify-content:space-between; height:60px; position:sticky; top:0; z-index:100; }
    .nav-logo { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:var(--beige); text-decoration:none; }
    .nav-logo span { color:var(--moss); }
    .nav-right { display:flex; gap:12px; }
    .btn-nav { padding:8px 18px; border-radius:8px; font-weight:700; font-size:13px; text-decoration:none; }
    .btn-outline-nav { border:1.5px solid var(--beige); color:var(--beige); background:transparent; }
    .btn-solid-nav { background:var(--moss); color:var(--dk-green); border:none; }

    /* HERO */
    .gallery-hero { background:var(--dk-green); color:var(--beige); text-align:center; padding:4rem 2rem 3rem; }
    .gallery-hero h1 { font-family:'Syne',sans-serif; font-size:42px; font-weight:800; margin-bottom:12px; }
    .gallery-hero p { font-size:16px; opacity:0.8; max-width:560px; margin:0 auto 24px; }

    /* SEARCH & FILTER */
    .filter-bar { background:#fff; border-bottom:2px solid #e5e7eb; padding:16px 2.5rem; display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
    .search-input { flex:1; min-width:240px; padding:10px 16px; border:1.5px solid #C0DD97; border-radius:8px; font-size:14px; outline:none; background:#FBFDF8; }
    .filter-select { padding:10px 14px; border:1.5px solid #C0DD97; border-radius:8px; font-size:13px; background:#FBFDF8; color:var(--dk-green); cursor:pointer; }
    .btn-filter { background:var(--dk-green); color:var(--beige); border:none; border-radius:8px; padding:10px 20px; font-weight:700; font-size:13px; cursor:pointer; }
    .btn-reset { background:transparent; border:1.5px solid #ccc; border-radius:8px; padding:10px 16px; font-size:13px; cursor:pointer; text-decoration:none; color:#666; }

    /* GALLERY GRID */
    .gallery-container { padding:2.5rem; }
    .result-count { font-size:14px; color:#666; margin-bottom:20px; }
    .result-count strong { color:var(--dk-green); }
    .gallery-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:20px; }

    /* PRODUCT CARD */
    .product-card {
        background:#fff; border:2px solid var(--dk-green); border-radius:16px;
        overflow:hidden; box-shadow:4px 4px 0 var(--dk-green); transition:transform 0.2s;
    }
    .product-card:hover { transform:translateY(-3px); }
    .product-img { width:100%; height:200px; object-fit:cover; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:48px; }
    .product-img img { width:100%; height:100%; object-fit:cover; }
    .product-body { padding:18px; }
    .product-name { font-family:'Syne',sans-serif; font-weight:800; font-size:16px; color:var(--dk-green); margin-bottom:6px; }
    .product-by { font-size:13px; color:#666; margin-bottom:10px; }
    .product-meta { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; }
    .product-chip { background:#F7F4D5; border:1.5px solid #C0DD97; border-radius:100px; padding:3px 10px; font-size:11px; font-weight:700; color:var(--dk-green); }
    .product-chip.green { background:#dcfce7; border-color:#86efac; color:#166534; }
    .product-price { font-family:'Syne',sans-serif; font-size:18px; font-weight:800; color:var(--moss); }

    /* EMPTY */
    .gallery-empty { text-align:center; padding:80px 20px; color:#666; }
    .gallery-empty .icon { font-size:56px; margin-bottom:16px; }

    /* PAGINATION */
    .pagination-wrap { display:flex; justify-content:center; margin-top:32px; gap:6px; }
    .pagination-wrap .page-link {
        display:inline-block; padding:8px 14px; border:1.5px solid #C0DD97; border-radius:8px;
        font-size:13px; font-weight:700; text-decoration:none; color:var(--dk-green); background:#fff;
    }
    .pagination-wrap .page-link:hover { background:var(--beige); }
    .pagination-wrap .page-link.active { background:var(--dk-green); color:var(--beige); border-color:var(--dk-green); }
    .pagination-wrap .page-link.disabled { opacity:0.4; pointer-events:none; }

    /* FOOTER */
    .gallery-footer { text-align:center; padding:2rem; font-size:13px; color:#888; border-top:1px solid #e5e7eb; margin-top:2rem; }
  </style>
</head>
<body>

<nav class="nav">
  <a href="{{ route('landing') }}" class="nav-logo">Upcycle<span>Match</span></a>
  <div class="nav-right">
    @auth
      @if(auth()->user()->role === 'upcycler')
        <a href="{{ route('upcycler.dashboard') }}" class="btn-nav btn-solid-nav">Dashboard</a>
      @elseif(auth()->user()->role === 'contributor')
        <a href="{{ route('contributor.dashboard') }}" class="btn-nav btn-solid-nav">Dashboard</a>
      @else
        <a href="{{ route('admin.dashboard') }}" class="btn-nav btn-solid-nav">Admin Panel</a>
      @endif
    @else
      <a href="{{ route('login') }}" class="btn-nav btn-outline-nav">Masuk</a>
      <a href="{{ route('register') }}" class="btn-nav btn-solid-nav">Daftar</a>
    @endauth
  </div>
</nav>

<div class="gallery-hero">
  <h1>✂️ Upcycle Gallery</h1>
  <p>Karya nyata mitra penjahit lokal — mengubah limbah kain menjadi produk bernilai tinggi dan ramah lingkungan.</p>
</div>

{{-- FILTER BAR --}}
<div class="filter-bar">
  <form method="GET" action="{{ route('gallery') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;width:100%;">
    <input type="text" name="search" class="search-input" placeholder="🔍 Cari nama produk..." value="{{ request('search') }}">
    <select name="fabric_type" class="filter-select">
      <option value="">Semua Bahan</option>
      @foreach($fabricTypes as $type)
        <option value="{{ $type }}" {{ request('fabric_type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn-filter">🔍 Filter</button>
    <a href="{{ route('gallery') }}" class="btn-reset">Reset</a>
  </form>
</div>

<div class="gallery-container">
  <div class="result-count">
    Menampilkan <strong>{{ $products->total() }}</strong> karya upcycle
    @if(request('search')) dari pencarian "<strong>{{ request('search') }}</strong>"@endif
  </div>

  @if($products->count())
  <div class="gallery-grid">
    @foreach($products as $product)
    <div class="product-card">
      <div class="product-img">
        @if($product->photo)
          <img src="{{ asset('storage/'.$product->photo) }}" alt="{{ $product->product_name }}">
        @else
          🧵
        @endif
      </div>
      <div class="product-body">
        <div class="product-name">{{ $product->product_name }}</div>
        <div class="product-by">oleh <strong>{{ $product->upcycler?->name ?? 'UMKM' }}</strong></div>
        <div class="product-meta">
          @if($product->textile)
          <span class="product-chip">🧵 {{ ucfirst($product->textile->fabric_type ?? '-') }}</span>
          <span class="product-chip green">⚖️ {{ $product->textile->weight }} kg diselamatkan</span>
          @endif
          <span class="product-chip">📅 {{ $product->created_at->format('d M Y') }}</span>
        </div>
        @if($product->description)
        <div style="font-size:13px;color:#555;margin-bottom:12px;line-height:1.5;">{{ Str::limit($product->description, 80) }}</div>
        @endif
        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Pagination --}}
  @if($products->hasPages())
  <div class="pagination-wrap">
    <a href="{{ $products->previousPageUrl() }}" class="page-link {{ $products->onFirstPage() ? 'disabled' : '' }}">← Prev</a>
    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
      <a href="{{ $url }}" class="page-link {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
    @endforeach
    <a href="{{ $products->nextPageUrl() }}" class="page-link {{ !$products->hasMorePages() ? 'disabled' : '' }}">Next →</a>
  </div>
  @endif

  @else
  <div class="gallery-empty">
    <div class="icon">🪡</div>
    <p style="font-size:16px;margin-bottom:8px;">Belum ada karya yang tampil</p>
    <p style="font-size:13px;">Jadilah yang pertama mengupload karya upcycle Anda!</p>
  </div>
  @endif
</div>

<div class="gallery-footer">
  &copy; {{ date('Y') }} UpcycleMatch — Bersama kurangi sampah tekstil Indonesia 🌿
</div>

</body>
</html>
