{{-- CONTRIBUTOR DASHBOARD — Full Redesign dengan Design System UpcycleMatch --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Kontributor — UpcycleMatch</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
/* ==================== DESIGN SYSTEM ==================== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --color-dark-green:     #0A3323;
  --color-moss-green:     #839958;
  --color-beige:          #F7F4D5;
  --color-beige2:         #EAE7BE;
  --color-rosy-brown:     #D3968C;
  --color-midnight-green: #105666;
  --font-heading: 'Playfair Display', serif;
  --font-body:    'DM Sans', sans-serif;
  --card-radius:  12px;
  --card-shadow:  0 2px 12px rgba(0,0,0,0.08);
}
html { scroll-behavior: smooth; }
body {
  font-family: var(--font-body);
  background: var(--color-beige);
  color: var(--color-dark-green);
  min-height: 100vh;
  display: flex; flex-direction: column;
}

/* ==================== TOPBAR ==================== */
.topbar {
  background: var(--color-dark-green);
  height: 56px;
  display: flex; align-items: center;
  justify-content: space-between;
  padding: 0 1.5rem;
  position: sticky; top: 0; z-index: 100;
  box-shadow: 0 2px 10px rgba(0,0,0,.2);
}
.topbar-logo {
  font-family: var(--font-heading);
  font-size: 20px; color: var(--color-beige);
}
.topbar-logo span { color: var(--color-rosy-brown); }
.topbar-nav { display: flex; gap: 2px; }
.tnav {
  background: none; border: none;
  padding: 6px 14px; border-radius: 8px;
  font-family: var(--font-body); font-size: 12px; font-weight: 600;
  color: rgba(247,244,213,.6); cursor: pointer; transition: .2s;
}
.tnav:hover { background: rgba(247,244,213,.12); color: var(--color-beige); }
.tnav.on { background: var(--color-moss-green); color: var(--color-dark-green); }
.topbar-right { display: flex; align-items: center; gap: 10px; }
.koin-pill {
  background: rgba(247,244,213,.1); border: 1px solid rgba(247,244,213,.25);
  border-radius: 100px; padding: 5px 14px;
  font-size: 12px; font-weight: 700; color: var(--color-beige);
  display: flex; align-items: center; gap: 5px;
}
.av-circle {
  width: 34px; height: 34px; border-radius: 50%;
  background: var(--color-rosy-brown);
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 800; color: #fff;
}
.av-name { font-size: 12px; font-weight: 700; color: var(--color-beige); }
.logout-btn {
  background: rgba(247,244,213,.1); border: 1.5px solid rgba(247,244,213,.2);
  border-radius: 100px; padding: 5px 14px;
  font-family: var(--font-body); font-size: 12px; font-weight: 700;
  color: var(--color-beige); cursor: pointer; display: flex; align-items: center; gap: 5px;
  transition: .2s;
}
.logout-btn:hover { background: rgba(211,150,140,.25); border-color: var(--color-rosy-brown); color: var(--color-rosy-brown); }

/* ==================== LAYOUT ==================== */
.body-area { display: flex; flex: 1; }
.sidenav {
  width: 210px; background: var(--color-dark-green);
  padding: 1.25rem .75rem; flex-shrink: 0;
}
.snav-label {
  font-size: 10px; font-weight: 800; letter-spacing: 1px;
  text-transform: uppercase; color: rgba(247,244,213,.3);
  padding: 0 8px; margin: 1rem 0 6px;
}
.snav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 11px; border-radius: 9px;
  font-size: 13px; font-weight: 600;
  color: rgba(247,244,213,.6); cursor: pointer;
  margin-bottom: 2px; transition: .15s;
}
.snav-item:hover { background: rgba(247,244,213,.08); color: var(--color-beige); }
.snav-item.on { background: var(--color-moss-green); color: var(--color-dark-green); font-weight: 700; }
.snav-item i { font-size: 17px; }
.main { flex: 1; padding: 1.5rem; overflow: auto; }

/* ==================== SECTIONS ==================== */
.page-sec { display: none; }
.page-sec.on { display: block; }
.sec-title { font-family: var(--font-heading); font-size: 24px; color: var(--color-dark-green); margin-bottom: 4px; }
.sec-sub { font-size: 13px; color: #5a7a5a; margin-bottom: 1.25rem; }

/* ==================== CARD ==================== */
.card {
  background: #fff; border-radius: var(--card-radius);
  border: 1.5px solid var(--color-beige2);
  padding: 1.25rem; box-shadow: var(--card-shadow);
}
.card-title {
  font-family: var(--font-heading); font-size: 17px;
  color: var(--color-dark-green); margin-bottom: 1rem;
  display: flex; align-items: center; gap: 8px;
}
.card-title i { font-size: 18px; color: var(--color-moss-green); }

/* ==================== FORMS ==================== */
.flabel {
  display: block; font-size: 11px; font-weight: 700;
  color: var(--color-dark-green); letter-spacing: .5px;
  text-transform: uppercase; margin-bottom: 5px;
}
.finput, .fselect, .ftextarea {
  width: 100%; padding: 9px 12px; border-radius: 8px;
  border: 1.5px solid var(--color-beige2);
  background: #fff; font-family: var(--font-body);
  font-size: 13px; color: var(--color-dark-green); outline: none;
  transition: border-color .2s;
}
.finput:focus, .fselect:focus, .ftextarea:focus { border-color: var(--color-moss-green); }
.ftextarea { resize: none; }
.frow { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.fg { margin-bottom: 12px; }

/* ==================== BUTTONS ==================== */
.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--color-dark-green); color: var(--color-beige);
  border: none; border-radius: 9px; padding: 11px 20px;
  font-family: var(--font-body); font-size: 13px; font-weight: 700;
  cursor: pointer; text-decoration: none; transition: .2s;
}
.btn-primary:hover { background: #0d4530; transform: translateY(-1px); }
.btn-primary-full { width: 100%; justify-content: center; }
.btn-outline {
  display: inline-flex; align-items: center; gap: 6px;
  background: transparent; color: var(--color-dark-green);
  border: 1.5px solid var(--color-dark-green); border-radius: 9px;
  padding: 9px 18px; font-family: var(--font-body);
  font-size: 13px; font-weight: 700; cursor: pointer; transition: .2s;
}
.btn-outline:hover { background: var(--color-dark-green); color: var(--color-beige); }

/* ==================== GREEN IDENTITY BANNER ==================== */
.gi-banner {
  background: var(--color-dark-green); border-radius: 16px;
  padding: 1.5rem; margin-bottom: 1.25rem;
}
.gi-top { display: flex; align-items: center; gap: 12px; margin-bottom: 1rem; }
.gi-icon {
  width: 40px; height: 40px; border-radius: 50%;
  background: rgba(131,153,88,.25);
  display: flex; align-items: center; justify-content: center;
}
.gi-label { font-size: 11px; font-weight: 700; color: rgba(247,244,213,.45); text-transform: uppercase; letter-spacing: .5px; }
.gi-name { font-family: var(--font-heading); font-size: 18px; color: var(--color-beige); }
.gi-metrics { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; }
@media(max-width:600px) { .gi-metrics { grid-template-columns: repeat(2,1fr); } }
.gi-m {
  background: rgba(247,244,213,.08); border: 1px solid rgba(247,244,213,.12);
  border-radius: 10px; padding: 14px 12px;
}
.gi-m-val { font-family: var(--font-heading); font-size: 28px; color: var(--color-beige); line-height: 1; }
.gi-m-val.gold { color: #E8C96A; }
.gi-m-sub { font-size: 10px; color: rgba(247,244,213,.4); margin-top: 4px; text-transform: uppercase; letter-spacing: .4px; }

/* ==================== TWO COL ==================== */
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
@media(max-width:720px) { .two-col { grid-template-columns: 1fr; } }
.three-col { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; margin-bottom: 1.25rem; }
@media(max-width:600px) { .three-col { grid-template-columns: repeat(2,1fr); } }

/* ==================== MINI STAT CARDS ==================== */
.sm-card {
  background: #fff; border-radius: 10px;
  border: 1.5px solid var(--color-beige2);
  padding: 14px; box-shadow: var(--card-shadow);
}
.sm-val { font-family: var(--font-heading); font-size: 28px; color: var(--color-dark-green); }
.sm-lbl { font-size: 10px; color: #5a7a5a; margin-top: 3px; text-transform: uppercase; letter-spacing: .4px; }

/* ==================== LIVE TRACKER — card style ==================== */
.tracker-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(280px,1fr)); gap: 14px; }
.tracker-card {
  background: #fff; border-radius: var(--card-radius);
  border: 1.5px solid var(--color-beige2);
  overflow: hidden; box-shadow: var(--card-shadow);
  transition: .2s;
}
.tracker-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
.tracker-card-img {
  width: 100%; height: 130px; object-fit: cover;
  background: var(--color-beige2);
  display: flex; align-items: center; justify-content: center;
  font-size: 40px;
}
.tracker-card-img img { width: 100%; height: 130px; object-fit: cover; }
.tracker-card-body { padding: 12px; }
.tracker-card-title { font-weight: 700; font-size: 13px; color: var(--color-dark-green); margin-bottom: 4px; }
.tracker-card-meta { font-size: 11px; color: #6b7280; margin-bottom: 8px; }
.tbadge {
  display: inline-block; font-size: 10px; font-weight: 700;
  padding: 3px 10px; border-radius: 9999px;
}
.tbadge-available { background: #DCFCE7; color: #166534; }
.tbadge-claimed   { background: #DBEAFE; color: #1e40af; }
.tbadge-completed { background: #F3F4F6; color: #374151; }
.tbadge-processing{ background: #FEF3C7; color: #92400E; }

/* ==================== ECO-WALLET ==================== */
.wallet-hero {
  background: linear-gradient(135deg, var(--color-dark-green) 60%, #1a5c3a);
  border-radius: 16px; padding: 28px;
  display: flex; align-items: center; gap: 24px;
  margin-bottom: 20px; position: relative; overflow: hidden;
}
.wallet-hero::before {
  content: '🌿'; position: absolute; right: 24px; top: 16px;
  font-size: 80px; opacity: .07;
}
.wallet-hero-left { flex: 1; }
.wallet-hero-label { font-size: 11px; font-weight: 700; color: rgba(247,244,213,.5); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
.wallet-hero-koin { font-family: var(--font-heading); font-size: 52px; color: #E8C96A; line-height: 1; }
.wallet-hero-unit { font-size: 16px; color: rgba(247,244,213,.7); margin-left: 8px; }
.wallet-hero-rp { font-size: 15px; color: rgba(247,244,213,.6); margin-top: 4px; }
.wallet-prog-wrap { margin-top: 14px; }
.wallet-prog-bar { background: rgba(255,255,255,.15); border-radius: 100px; height: 6px; margin-bottom: 6px; }
.wallet-prog-fill { background: var(--color-moss-green); border-radius: 100px; height: 6px; transition: width .8s; }
.wallet-prog-label { display: flex; justify-content: space-between; font-size: 10px; color: rgba(247,244,213,.4); }
.wallet-hero-right { text-align: right; }

/* UPLOAD THUMBNAIL in wallet */
.wallet-upload-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(64px,1fr)); gap: 8px; margin-top: 12px; }
.wallet-upload-thumb {
  width: 64px; height: 64px; border-radius: 8px;
  object-fit: cover; border: 2px solid var(--color-beige2);
  background: var(--color-beige2);
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; overflow: hidden;
}
.wallet-upload-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* History rows */
.history-row {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 0; border-bottom: 1px solid var(--color-beige2);
}
.history-row:last-child { border-bottom: none; }
.hist-icon {
  width: 34px; height: 34px; border-radius: 50%;
  background: var(--color-beige2);
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; flex-shrink: 0;
}
.hist-info { flex: 1; }
.hist-name { font-size: 12px; font-weight: 700; color: var(--color-dark-green); }
.hist-sub  { font-size: 11px; color: #6b7280; }
.hist-koin { font-size: 14px; font-weight: 800; color: #22c55e; }
.hist-koin.neg { color: var(--color-rosy-brown); }

/* Withdrawal form */
.method-row { display: flex; gap: 8px; margin-bottom: 14px; }
.m-btn {
  flex: 1; padding: 10px; border-radius: 9px;
  border: 1.5px solid var(--color-beige2); background: #fff;
  font-family: var(--font-body); font-size: 12px; font-weight: 700;
  color: var(--color-dark-green); cursor: pointer; text-align: center;
  transition: .2s;
}
.m-btn.on { background: var(--color-dark-green); color: var(--color-beige); border-color: var(--color-dark-green); }

/* WITHDRAWAL HISTORY */
.wd-row {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 12px; border-radius: 10px;
  border: 1.5px solid var(--color-beige2); margin-bottom: 8px;
  background: #fff;
}
.wd-icon { font-size: 22px; flex-shrink: 0; }
.wd-info { flex: 1; }
.wd-title { font-size: 13px; font-weight: 700; color: var(--color-dark-green); }
.wd-sub   { font-size: 11px; color: #6b7280; margin-top: 2px; }
.wd-status {
  font-size: 10px; font-weight: 800; padding: 4px 10px;
  border-radius: 9999px;
}
.wd-pending  { background: #FEF9C3; color: #a16207; }
.wd-approved { background: #DCFCE7; color: #166534; }
.wd-rejected { background: #FEE2E2; color: #991b1b; }

/* ==================== E-COMMERCE GALLERY ==================== */
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 18px;
}
.product-card {
  background: #fff; border-radius: 14px;
  border: 1.5px solid var(--color-beige2);
  overflow: hidden; transition: .25s;
  box-shadow: var(--card-shadow);
  display: flex; flex-direction: column;
}
.product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.12); }
.product-card-img {
  width: 100%; height: 180px; object-fit: cover;
  background: var(--color-beige2);
  display: flex; align-items: center; justify-content: center;
  font-size: 52px; color: #aaa;
}
.product-card-img img { width: 100%; height: 180px; object-fit: cover; }
.product-card-body { padding: 14px; flex: 1; display: flex; flex-direction: column; }
.product-card-name { font-family: var(--font-heading); font-size: 15px; font-weight: 600; color: var(--color-dark-green); margin-bottom: 4px; }
.product-card-by   { font-size: 11px; color: #6b7280; margin-bottom: 10px; }
.product-card-price{ font-size: 18px; font-weight: 800; color: var(--color-midnight-green); margin-bottom: 12px; }
.product-card-koin { font-size: 12px; color: #6b7280; margin-bottom: 12px; }
.product-card-koin span { color: #E8C96A; font-weight: 700; }
.product-card-btn {
  display: block; width: 100%; padding: 10px;
  background: var(--color-dark-green); color: var(--color-beige);
  border: none; border-radius: 9px;
  font-family: var(--font-body); font-size: 13px; font-weight: 700;
  cursor: pointer; text-align: center; text-decoration: none;
  transition: .2s; margin-top: auto;
}
.product-card-btn:hover { background: var(--color-midnight-green); }

/* ==================== ORDER STATUS CARDS ==================== */
.order-mini-card {
  background: #fff; border-radius: var(--card-radius);
  border: 1.5px solid var(--color-beige2);
  padding: 14px 16px; display: flex;
  align-items: center; gap: 14px;
  box-shadow: var(--card-shadow); margin-bottom: 10px;
  transition: .2s;
}
.order-mini-card:hover { border-color: var(--color-moss-green); }
.order-mini-img {
  width: 60px; height: 60px; border-radius: 10px;
  object-fit: cover; flex-shrink: 0;
  background: var(--color-beige2);
  display: flex; align-items: center; justify-content: center;
  font-size: 24px;
}
.order-mini-img img { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; }
.status-pill { font-size: 10px; font-weight: 800; padding: 3px 9px; border-radius: 9999px; }
.sp-pending    { background: #fef9c3; color: #a16207; }
.sp-paid       { background: #dcfce7; color: #166534; }
.sp-processing { background: #dbeafe; color: #1e40af; }
.sp-shipped    { background: #ccfbf1; color: #0f766e; }
.sp-done       { background: #f0fdf4; color: #166534; }
.sp-cancelled  { background: #fee2e2; color: #991b1b; }

/* ==================== PROFILE ==================== */
.profile-avatar-big {
  width: 80px; height: 80px; border-radius: 50%;
  background: var(--color-rosy-brown);
  display: flex; align-items: center; justify-content: center;
  font-size: 28px; font-weight: 800; color: #fff; flex-shrink: 0;
}

/* ==================== FLASH / TOAST ==================== */
.flash-ok { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; font-weight: 700; color: #166534; display: flex; align-items: center; gap: 8px; }
.flash-err { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #991b1b; }

/* Map box */
.map-box {
  background: #EAF3E6; border-radius: 8px;
  border: 1.5px dashed var(--color-moss-green); height: 130px;
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; cursor: pointer; gap: 4px;
}
.map-box-icon { font-size: 28px; color: var(--color-moss-green); }
.map-box-txt  { font-size: 11px; color: #5a7a5a; font-weight: 600; }
.coord-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px; }

/* Footer minimal */
.dash-footer-minimal {
  background: var(--color-dark-green);
  text-align: center; padding: 12px;
  font-size: 11px; color: rgba(247,244,213,.4);
}
.dash-footer-minimal a { color: rgba(247,244,213,.6); text-decoration: none; margin: 0 6px; }
.dash-footer-minimal a:hover { color: var(--color-moss-green); }
</style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
  <div class="topbar-logo">Upcycle<span>Match</span></div>
  <div class="topbar-right">
    <div class="koin-pill">⭐ {{ $stats['total_koin'] }} Koin</div>
    <div style="display:flex;align-items:center;gap:8px;cursor:pointer;" onclick="showSec('profile')" title="Edit Profil">
      @if($user->photo)
      <img src="{{ asset('storage/'.$user->photo) }}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid rgba(247,244,213,.4);">
      @else
      <div class="av-circle">{{ strtoupper(substr($user->name,0,2)) }}</div>
      @endif
      <span class="av-name">{{ explode(' ',trim($user->name))[0] }}</span>
    </div>
    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
      @csrf
      <button type="submit" class="logout-btn"><i class="ti ti-logout"></i> Keluar</button>
    </form>
  </div>
</div>

<div class="body-area">

  {{-- SIDENAV --}}
  <nav class="sidenav">
    <div class="snav-label">Menu Utama</div>
    <div class="snav-item on" onclick="showSec('upload')"><i class="ti ti-upload"></i> Upload Limbah</div>
    <div class="snav-item" onclick="showSec('tracker')"><i class="ti ti-radar"></i> Live Tracker</div>
    <div class="snav-item" onclick="showSec('wallet')"><i class="ti ti-wallet"></i> Eco-Wallet</div>
    <div class="snav-item" onclick="showSec('gallery')"><i class="ti ti-shopping-bag"></i> Beli Produk</div>
    <div class="snav-label">Akun</div>
    <div class="snav-item" onclick="showSec('profile')"><i class="ti ti-user"></i> Profil Saya</div>
    <div class="snav-label">Link Lain</div>
    <a href="{{ route('contributor.orders') }}" style="text-decoration:none;">
      <div class="snav-item"><i class="ti ti-package"></i> Pesanan Saya</div>
    </a>
  </nav>

  <div class="main">

    @if(session('success'))
    <div class="flash-ok"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="flash-err">
      @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    {{-- ======================== SECTION: BERANDA / UPLOAD ======================== --}}
    <div class="page-sec on" id="sec-upload">
      <div class="sec-title">Dashboard Kontributor</div>
      <div class="sec-sub">Kelola limbah kain, pantau status, dan cairkan reward koinmu</div>

      {{-- Green Identity Banner --}}
      <div class="gi-banner">
        <div class="gi-top">
          <div class="gi-icon"><i class="ti ti-leaf" style="font-size:18px;color:var(--color-moss-green);"></i></div>
          <div>
            <div class="gi-label">Green Identity</div>
            <div class="gi-name">{{ $user->name }} — Kontributor Aktif</div>
          </div>
        </div>
        <div class="gi-metrics">
          <div class="gi-m"><div class="gi-m-val">{{ number_format($stats['total_berat'],1) }}</div><div class="gi-m-sub">Kg Diselamatkan</div></div>
          <div class="gi-m"><div class="gi-m-val">{{ $stats['postingan_aktif'] }}</div><div class="gi-m-sub">Postingan</div></div>
          <div class="gi-m"><div class="gi-m-val gold">{{ $stats['total_koin'] }}</div><div class="gi-m-sub">Total Koin</div></div>
          <div class="gi-m"><div class="gi-m-val">{{ number_format($stats['total_rupiah'],0,',','.') }}</div><div class="gi-m-sub">Nilai Koin (Rp)</div></div>
        </div>
      </div>

      <div class="two-col">
        {{-- Form Upload --}}
        <div class="card">
          <div class="card-title"><i class="ti ti-package"></i> Upload Limbah Kain</div>
          <form action="{{ route('contributor.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="fg">
              <label class="flabel">Judul Postingan *</label>
              <input class="finput" name="judul" placeholder="Contoh: Kain Katun Bekas 5kg – Kondisi Baik" required />
            </div>
            <div class="frow fg">
              <div>
                <label class="flabel">Jenis Bahan *</label>
                <select class="fselect" name="jenis_bahan" required>
                  <option value="">Pilih jenis</option>
                  <option value="katun">Katun</option>
                  <option value="denim">Denim</option>
                  <option value="sutra">Sutra</option>
                  <option value="polyester">Polyester</option>
                  <option value="lainnya">Lainnya</option>
                </select>
              </div>
              <div>
                <label class="flabel">Estimasi Berat (kg) *</label>
                <input class="finput" name="berat_kg" type="number" step="0.1" min="0.1" placeholder="0.0" required />
              </div>
            </div>
            <div class="fg">
              <label class="flabel">Alamat Penjemputan</label>
              <input class="finput" name="alamat" id="inp-alamat" placeholder="Nama jalan, kelurahan, kota..." />
            </div>
            <div class="fg">
              <label class="flabel">📷 Foto Limbah (maks 5MB)</label>
              <div style="border:2px dashed var(--color-moss-green);border-radius:8px;padding:12px;text-align:center;cursor:pointer;background:#FBFDF8;"
                   onclick="document.getElementById('inp-foto').click()" id="foto-drop">
                <div style="font-size:24px;margin-bottom:4px;">📷</div>
                <div style="font-size:12px;color:#5a7a5a;" id="foto-label">Klik untuk pilih foto (JPG, PNG)</div>
              </div>
              <input type="file" id="inp-foto" name="foto" accept="image/*" style="display:none;" onchange="previewFoto(this)">
              <div id="foto-preview" style="display:none;margin-top:8px;">
                <img id="foto-img" style="max-height:120px;border-radius:8px;border:1.5px solid var(--color-beige2);">
              </div>
            </div>
            <div class="fg">
              <label class="flabel">Deskripsi Kondisi</label>
              <textarea class="ftextarea" name="deskripsi" rows="2" placeholder="Catatan kondisi kain, warna, ukuran..."></textarea>
            </div>
            <div class="fg">
              <label class="flabel"><i class="ti ti-map-pin" style="font-size:13px;"></i> Titik Lokasi (opsional)</label>
              <div class="map-box" onclick="openPickerModal()" id="map-box-btn">
                <div class="map-box-icon"><i class="ti ti-map"></i></div>
                <div class="map-box-txt" id="map-txt">Klik untuk pin lokasi di peta</div>
              </div>
              <div class="coord-row">
                <input class="finput" name="latitude"  id="inp-lat" placeholder="Latitude" readonly style="font-size:11px;">
                <input class="finput" name="longitude" id="inp-lng" placeholder="Longitude" readonly style="font-size:11px;">
              </div>
            </div>
            <button type="submit" class="btn-primary btn-primary-full" style="margin-top:8px;">
              <i class="ti ti-send"></i> Posting Kain Saya
            </button>
          </form>
        </div>

        {{-- Sidebar: Live Tracker Preview + Wallet Mini --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">
          <div class="card">
            <div class="card-title"><i class="ti ti-radar"></i> Status Terkini</div>
            @forelse($limbahList->take(4) as $limbah)
            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--color-beige2);">
              @if($limbah->image ?? $limbah->foto ?? null)
              <img src="{{ asset('storage/'.($limbah->image ?? $limbah->foto)) }}" style="width:40px;height:40px;object-fit:cover;border-radius:7px;flex-shrink:0;">
              @else
              <div style="width:40px;height:40px;background:var(--color-beige2);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">🧵</div>
              @endif
              <div style="flex:1;min-width:0;">
                <div style="font-size:12px;font-weight:700;color:var(--color-dark-green);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $limbah->title ?? $limbah->judul ?? 'Tanpa Judul' }}</div>
                <div style="font-size:10px;color:#6b7280;">{{ number_format($limbah->weight ?? $limbah->berat_kg ?? 0,1) }}kg · {{ $limbah->created_at->diffForHumans() }}</div>
              </div>
              <span class="tbadge tbadge-{{ $limbah->status }}">{{ ucfirst($limbah->status) }}</span>
            </div>
            @empty
            <div style="text-align:center;font-size:13px;color:#6b7280;padding:12px 0;">Belum ada kain diposting.</div>
            @endforelse
            @if($limbahList->count() > 4)
            <div style="text-align:center;margin-top:10px;">
              <button class="btn-outline" style="font-size:11px;padding:6px 14px;" onclick="showSec('tracker')">Lihat Semua ({{ $limbahList->count() }})</button>
            </div>
            @endif
          </div>

          {{-- Wallet Mini --}}
          <div style="background:var(--color-dark-green);border-radius:14px;padding:20px;color:var(--color-beige);">
            <div style="font-size:11px;font-weight:700;color:rgba(247,244,213,.5);text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px;">💰 Eco-Wallet</div>
            <div style="font-family:var(--font-heading);font-size:40px;color:#E8C96A;line-height:1;">{{ $stats['total_koin'] }}<span style="font-size:15px;color:rgba(247,244,213,.6);margin-left:6px;">Koin</span></div>
            <div style="font-size:13px;color:rgba(247,244,213,.55);margin-bottom:14px;">≈ Rp{{ number_format($stats['total_rupiah'],0,',','.') }}</div>
            <div style="background:rgba(255,255,255,.1);border-radius:100px;height:5px;margin-bottom:5px;">
              <div style="background:var(--color-moss-green);border-radius:100px;height:5px;width:{{ min(($stats['total_koin']/20)*100,100) }}%;"></div>
            </div>
            <div style="font-size:10px;color:rgba(247,244,213,.35);margin-bottom:14px;">Target pencairan 20 koin</div>
            <button class="btn-outline" style="width:100%;justify-content:center;border-color:rgba(247,244,213,.3);color:var(--color-beige);" onclick="showSec('wallet')">
              <i class="ti ti-cash"></i> Cairkan Koin
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- ======================== SECTION: LIVE TRACKER ======================== --}}
    <div class="page-sec" id="sec-tracker">
      <div class="sec-title">Live Status Tracker</div>
      <div class="sec-sub">Pantau status siklus hidup kainmu secara real-time</div>

      <div class="three-col">
        <div class="sm-card" style="border-top:3px solid #22c55e;">
          <div class="sm-val" style="color:#166534;">{{ $limbahList->where('status','available')->count() }}</div>
          <div class="sm-lbl">Available</div>
        </div>
        <div class="sm-card" style="border-top:3px solid var(--color-midnight-green);">
          <div class="sm-val" style="color:var(--color-midnight-green);">{{ $limbahList->where('status','claimed')->count() }}</div>
          <div class="sm-lbl">Claimed</div>
        </div>
        <div class="sm-card" style="border-top:3px solid #9ca3af;">
          <div class="sm-val" style="color:#374151;">{{ $limbahList->where('status','completed')->count() }}</div>
          <div class="sm-lbl">Completed</div>
        </div>
      </div>

      @forelse($limbahList as $limbah)
      <div class="tracker-card" style="margin-bottom:14px;display:flex;gap:0;flex-direction:row;overflow:hidden;">
        <div style="width:110px;flex-shrink:0;">
          @if($limbah->image ?? $limbah->foto ?? null)
          <img src="{{ asset('storage/'.($limbah->image ?? $limbah->foto)) }}" style="width:110px;height:100%;min-height:90px;object-fit:cover;">
          @else
          <div style="width:110px;min-height:90px;background:var(--color-beige2);display:flex;align-items:center;justify-content:center;font-size:36px;">🧵</div>
          @endif
        </div>
        <div style="padding:14px 16px;flex:1;display:flex;align-items:center;gap:14px;">
          <div style="flex:1;">
            <div style="font-family:var(--font-heading);font-size:15px;color:var(--color-dark-green);margin-bottom:3px;">{{ $limbah->title ?? $limbah->judul ?? 'Tanpa Judul' }}</div>
            <div style="font-size:12px;color:#6b7280;margin-bottom:8px;">
              {{ number_format($limbah->weight ?? $limbah->berat_kg ?? 0,1) }} kg
              · {{ $limbah->fabric_type ?? $limbah->jenis_bahan ?? '-' }}
              · {{ $limbah->created_at->format('d M Y') }}
            </div>
            <span class="tbadge tbadge-{{ $limbah->status }}">
              @if($limbah->status==='available') ✅ Tersedia
              @elseif($limbah->status==='claimed') 🔵 Diklaim Upcycler
              @elseif($limbah->status==='completed') 🎉 Selesai Diolah
              @elseif($limbah->status==='processing') ⚙️ Sedang Diproses
              @else {{ ucfirst($limbah->status) }}
              @endif
            </span>
          </div>
        </div>
      </div>
      @empty
      <div class="card" style="text-align:center;padding:40px;border:2px dashed var(--color-beige2);box-shadow:none;">
        <div style="font-size:48px;margin-bottom:12px;">📦</div>
        <div style="font-family:var(--font-heading);font-size:18px;color:var(--color-dark-green);margin-bottom:6px;">Belum ada postingan</div>
        <div style="font-size:13px;color:#6b7280;">Upload limbah pertamamu di tab Beranda!</div>
      </div>
      @endforelse
    </div>

    {{-- ======================== SECTION: ECO-WALLET ======================== --}}
    <div class="page-sec" id="sec-wallet">
      <div class="sec-title">Eco-Wallet &amp; Koin</div>
      <div class="sec-sub">Kumpulkan koin dari aktivitasmu, cairkan jadi rupiah</div>

      {{-- Hero Wallet --}}
      <div class="wallet-hero">
        <div class="wallet-hero-left">
          <div class="wallet-hero-label">Saldo Koin</div>
          <div>
            <span class="wallet-hero-koin">{{ $stats['total_koin'] }}</span>
            <span class="wallet-hero-unit">Koin</span>
          </div>
          <div class="wallet-hero-rp">≈ Rp{{ number_format($stats['total_rupiah'],0,',','.') }}</div>
          <div class="wallet-prog-wrap">
            <div class="wallet-prog-bar">
              <div class="wallet-prog-fill" style="width:{{ min(($stats['total_koin']/20)*100,100) }}%;"></div>
            </div>
            <div class="wallet-prog-label"><span>0</span><span>Target 20 koin</span></div>
          </div>
          <div style="font-size:11px;color:rgba(247,244,213,.4);margin-top:8px;">+2 koin setiap upload limbah · 1 koin = Rp2.500</div>
        </div>
        {{-- Upload thumbnails --}}
        @if($limbahList->count() > 0)
        <div style="text-align:right;">
          <div style="font-size:10px;color:rgba(247,244,213,.4);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Limbah Anda</div>
          <div class="wallet-upload-grid" style="justify-content:flex-end;max-width:200px;">
            @foreach($limbahList->take(6) as $lbh)
            <div class="wallet-upload-thumb">
              @if($lbh->image ?? $lbh->foto ?? null)
              <img src="{{ asset('storage/'.($lbh->image ?? $lbh->foto)) }}" alt="">
              @else
              🧵
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>

      <div class="two-col">
        {{-- Riwayat Koin --}}
        <div class="card">
          <div class="card-title"><i class="ti ti-history"></i> Riwayat Transaksi Koin</div>
          @forelse($koinTransactions as $tx)
          <div class="history-row">
            {{-- Cari limbah yang relevan berdasarkan keterangan --}}
            @php
              $txImg = null;
              // Upload koin: cari limbah terbaru user saat itu
              if($tx->amount > 0) {
                $relLimbah = $limbahList->sortByDesc('created_at')->first(function($l) use($tx) {
                  return $l->created_at <= $tx->created_at->addMinutes(5);
                });
                $txImg = $relLimbah?->image ?? null;
              }
            @endphp
            <div class="hist-icon" style="{{ $txImg ? 'background:none;overflow:hidden;' : '' }}">
              @if($txImg)
              <img src="{{ asset('storage/'.$txImg) }}" style="width:34px;height:34px;object-fit:cover;border-radius:50%;">
              @else
              <i class="ti {{ $tx->amount > 0 ? 'ti-leaf' : 'ti-arrow-up' }}" style="font-size:15px;color:{{ $tx->amount > 0 ? '#22c55e' : '#D3968C' }};"></i>
              @endif
            </div>
            <div class="hist-info">
              <div class="hist-name">{{ $tx->keterangan }}</div>
              <div class="hist-sub">{{ $tx->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="hist-koin {{ $tx->amount < 0 ? 'neg' : '' }}">
              {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }} Koin
            </div>
          </div>
          @empty
          <div style="text-align:center;font-size:13px;color:#6b7280;padding:16px 0;">Belum ada riwayat transaksi koin.</div>
          @endforelse

          {{-- Riwayat Withdrawal --}}
          @php $myWithdrawals = \App\Models\KoinWithdrawal::where('user_id',auth()->id())->latest()->take(5)->get(); @endphp
          @if($myWithdrawals->count() > 0)
          <div style="margin-top:16px;">
            <div style="font-family:var(--font-heading);font-size:14px;color:var(--color-dark-green);margin-bottom:10px;padding-top:12px;border-top:1px solid var(--color-beige2);">
              📋 Riwayat Pencairan
            </div>
            @foreach($myWithdrawals as $wd)
            <div class="wd-row">
              <div class="wd-icon">{{ $wd->metode === 'bank' ? '🏦' : '📱' }}</div>
              <div class="wd-info">
                <div class="wd-title">{{ $wd->jumlah_koin }} Koin → Rp{{ number_format($wd->nominal_rupiah,0,',','.') }}</div>
                <div class="wd-sub">{{ $wd->nama_bank }} · {{ $wd->nomor_rekening }} · {{ $wd->created_at->format('d M Y') }}</div>
              </div>
              <span class="wd-status wd-{{ $wd->status }}">
                {{ ['pending'=>'⏳ Diproses','approved'=>'✅ Disetujui','rejected'=>'❌ Ditolak'][$wd->status] }}
              </span>
            </div>
            @endforeach
          </div>
          @endif
        </div>

        {{-- Form Pencairan --}}
        <div class="card">
          <div class="card-title"><i class="ti ti-cash"></i> Cairkan Koin</div>
          <div style="background:var(--color-dark-green);border-radius:12px;padding:16px;text-align:center;margin-bottom:16px;">
            <div style="font-family:var(--font-heading);font-size:36px;color:#E8C96A;">{{ $stats['total_koin'] }} Koin</div>
            <div style="font-size:13px;color:rgba(247,244,213,.6);">Setara Rp{{ number_format($stats['total_rupiah'],0,',','.') }}</div>
          </div>

          @if($stats['total_koin'] >= 4)
          <form action="{{ route('contributor.wallet.withdraw') }}" method="POST" id="form-withdraw">
            @csrf
            <div class="fg">
              <label class="flabel">Jumlah Koin yang Dicairkan</label>
              <input class="finput" type="number" name="jumlah_koin" min="4" max="{{ $stats['total_koin'] }}"
                     placeholder="Min. 4 koin" required
                     oninput="updateNominal(this.value)">
              <div style="font-size:11px;color:#6b7280;margin-top:4px;">Min. 4 koin (Rp10.000) · Saldo: {{ $stats['total_koin'] }} koin</div>
              <div id="nominal-preview" style="font-size:15px;font-weight:700;color:var(--color-midnight-green);margin-top:6px;"></div>
            </div>

            <div class="fg">
              <label class="flabel">Metode Pencairan</label>
              <div class="method-row">
                <div class="m-btn on" id="mb-bank" onclick="selMetode('bank')"><i class="ti ti-building-bank"></i> Transfer Bank</div>
                <div class="m-btn" id="mb-ew" onclick="selMetode('ewallet')"><i class="ti ti-device-mobile"></i> E-Wallet</div>
              </div>
              <input type="hidden" name="metode" id="metode-val" value="bank">
            </div>

            {{-- Single select, options berubah via JS --}}
            <div class="fg">
              <label class="flabel" id="platform-label">Nama Bank</label>
              <select class="fselect" name="nama_platform" id="inp-platform" required>
                <option value="">Pilih Bank</option>
                <option>BCA</option><option>BRI</option><option>BNI</option>
                <option>Mandiri</option><option>BSI</option><option>CIMB</option>
                <option>Permata</option><option>Bank Lainnya</option>
              </select>
            </div>

            <div class="fg">
              <label class="flabel" id="norek-label">Nomor Rekening</label>
              <input class="finput" type="text" name="nomor_rekening" id="inp-norek"
                     placeholder="Contoh: 1234567890" required>
            </div>
            <div class="fg">
              <label class="flabel">Nama Penerima (sesuai rekening/akun)</label>
              <input class="finput" type="text" name="nama_penerima" value="{{ $user->name }}" required>
            </div>

            <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:12px;font-size:12px;color:#1e40af;margin-bottom:14px;">
              <strong>ℹ️ Info:</strong> Pencairan diproses dalam 1×24 jam kerja. Koin terpotong otomatis setelah pengajuan. Jika ditolak, koin dikembalikan.
            </div>

            <button type="submit" class="btn-primary btn-primary-full">
              <i class="ti ti-cash"></i> Ajukan Pencairan
            </button>
          </form>
          @else
          <div style="text-align:center;padding:20px;background:var(--color-beige2);border-radius:10px;">
            <div style="font-size:32px;margin-bottom:8px;">🪙</div>
            <div style="font-size:13px;font-weight:700;color:var(--color-dark-green);margin-bottom:4px;">Koin belum mencukupi</div>
            <div style="font-size:12px;color:#6b7280;">Minimal 4 koin untuk mencairkan. Anda punya {{ $stats['total_koin'] }} koin.</div>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- ======================== SECTION: GALLERY / BELI PRODUK ======================== --}}
    <div class="page-sec" id="sec-gallery">

      {{-- Pesanan Terkini --}}
      @if($recentOrders->count() > 0)
      <div style="margin-bottom:28px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
          <div>
            <div class="sec-title">Pesanan Terkini</div>
            <div class="sec-sub">Status barang yang Anda beli</div>
          </div>
          <a href="{{ route('contributor.orders') }}" style="font-size:12px;color:var(--color-moss-green);font-weight:700;text-decoration:none;">Lihat Semua <i class="ti ti-arrow-right"></i></a>
        </div>
        @foreach($recentOrders as $order)
        @php
          $sl = ['pending'=>'⏳ Menunggu Bayar','paid'=>'✅ Lunas','processing'=>'⚙️ Diproses','shipped'=>'🚚 Dikirim','done'=>'🎉 Selesai','cancelled'=>'❌ Dibatalkan'][$order->status] ?? $order->status;
          $sc = 'sp-'.$order->status;
        @endphp
        <div class="order-mini-card">
          <div class="order-mini-img">
            @if($order->product?->photo)
            <img src="{{ asset('storage/'.$order->product->photo) }}" alt="">
            @else 🎨 @endif
          </div>
          <div style="flex:1;">
            <div style="font-family:var(--font-heading);font-size:14px;font-weight:600;color:var(--color-dark-green);margin-bottom:2px;">{{ $order->product?->display_name ?? 'Produk' }}</div>
            <div style="font-size:11px;color:#6b7280;margin-bottom:6px;">#ORD-{{ str_pad($order->id,4,'0',STR_PAD_LEFT) }} · Rp{{ number_format($order->total_price,0,',','.') }}</div>
            <span class="status-pill {{ $sc }}">{{ $sl }}</span>
          </div>
          @if($order->status==='pending' && $order->payment_method==='transfer')
          <a href="{{ route('contributor.orders') }}" class="btn-primary" style="font-size:11px;padding:7px 14px;">Konfirmasi Bayar</a>
          @endif
        </div>
        @endforeach
      </div>
      @endif

      {{-- Gallery Header --}}
      <div>
        <div class="sec-title">Beli Produk Daur Ulang</div>
        <div class="sec-sub">Karya premium dari limbah kain hasil tangan mitra penjahit UpcycleMatch</div>
      </div>

      <div class="three-col" style="margin-bottom:1.5rem;">
        <div class="sm-card"><div class="sm-val" style="color:var(--color-dark-green);">{{ $publishedProducts->count() }}</div><div class="sm-lbl">Produk Tersedia</div></div>
        <div class="sm-card"><div class="sm-val" style="color:var(--color-midnight-green);">{{ \App\Models\User::where('role','upcycler')->where('is_verified',true)->count() }}</div><div class="sm-lbl">Mitra Penjahit</div></div>
        <div class="sm-card"><div class="sm-val" style="color:#E8C96A;">{{ $user->koin }}</div><div class="sm-lbl">Koin Anda</div></div>
      </div>

      {{-- E-Commerce Product Grid --}}
      <div class="gallery-grid">
        @forelse($publishedProducts as $p)
        <div class="product-card">
          <div class="product-card-img">
            @if($p->photo)
            <img src="{{ asset('storage/'.$p->photo) }}" alt="{{ $p->display_name }}">
            @else 🎨 @endif
          </div>
          <div class="product-card-body">
            <div class="product-card-name">{{ $p->display_name }}</div>
            <div class="product-card-by">oleh {{ $p->upcycler->name ?? 'Upcycler' }}</div>
            <div class="product-card-price">Rp{{ number_format($p->price,0,',','.') }}</div>
            <div class="product-card-koin">
              atau <span>{{ ceil($p->price/2500) }} Koin</span>
              @if($user->koin >= ceil($p->price/2500))<span style="background:#dcfce7;color:#166534;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px;margin-left:4px;">✓ Koin Cukup</span>@endif
            </div>
            <a href="{{ route('contributor.checkout', $p->id) }}" class="product-card-btn">Beli Sekarang</a>
          </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:48px;color:#6b7280;">
          <div style="font-size:48px;margin-bottom:12px;">🛍️</div>
          <div style="font-family:var(--font-heading);font-size:18px;color:var(--color-dark-green);margin-bottom:6px;">Belum ada produk tersedia</div>
          <div style="font-size:13px;">Upcycler masih memproduksi karya terbaru!</div>
        </div>
        @endforelse
      </div>

      @if($publishedProducts->count() > 0)
      <div style="text-align:center;margin-top:20px;">
        <a href="{{ route('gallery') }}" class="btn-outline">🎨 Lihat Semua di Gallery Publik</a>
      </div>
      @endif
    </div>

    {{-- ======================== SECTION: PROFIL ======================== --}}
    <div class="page-sec" id="sec-profile">
      <div class="sec-title">Profil Saya</div>
      <div class="sec-sub">Kelola informasi akun dan preferensimu</div>

      <div class="two-col">
        {{-- Form Edit --}}
        <div class="card">
          {{-- Avatar dengan tombol ganti foto --}}
          <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--color-beige2);">
            <div style="position:relative;">
              @if($user->photo)
              <img src="{{ asset('storage/'.$user->photo) }}" id="profile-preview"
                   style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--color-moss-green);">
              @else
              <div class="profile-avatar-big" id="profile-preview-fallback">{{ strtoupper(substr($user->name,0,2)) }}</div>
              @endif
              <button type="button" onclick="document.getElementById('inp-photo').click()"
                style="position:absolute;bottom:0;right:0;width:24px;height:24px;border-radius:50%;
                       background:var(--color-dark-green);border:2px solid #fff;
                       display:flex;align-items:center;justify-content:center;cursor:pointer;">
                <i class="ti ti-camera" style="font-size:11px;color:#fff;"></i>
              </button>
            </div>
            <div>
              <div style="font-family:var(--font-heading);font-size:19px;color:var(--color-dark-green);">{{ $user->name }}</div>
              <div style="font-size:12px;color:#6b7280;">Kontributor Aktif · Bergabung {{ $user->created_at->format('M Y') }}</div>
              <span style="display:inline-block;background:#dcfce7;color:#166534;font-size:10px;font-weight:700;padding:3px 10px;border-radius:9999px;margin-top:4px;">✅ Akun Terverifikasi</span>
            </div>
          </div>
          <div class="card-title" style="margin-bottom:.75rem;"><i class="ti ti-edit"></i> Edit Profil</div>
          <form action="{{ route('contributor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Input foto tersembunyi --}}
            <input type="file" id="inp-photo" name="photo" accept="image/*" style="display:none;" onchange="previewProfilePhoto(this)">
            <div class="fg">
              <label class="flabel">Nama Lengkap *</label>
              <input type="text" name="name" class="finput" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="fg">
              <label class="flabel">Email (Login)</label>
              <input class="finput" value="{{ $user->email }}" readonly style="background:#f3f4f6;cursor:not-allowed;opacity:.7;">
              <div style="font-size:11px;color:#6b7280;margin-top:3px;">Email tidak dapat diubah untuk keamanan akun.</div>
            </div>
            <div class="fg">
              <label class="flabel">Nomor WhatsApp</label>
              <input type="text" name="whatsapp" class="finput" value="{{ old('whatsapp', $user->whatsapp) }}" placeholder="Mulai dengan 08...">
            </div>

            <div style="background:var(--color-beige2);border-radius:10px;padding:14px;margin:16px 0 12px;">
              <div style="font-size:13px;font-weight:700;color:var(--color-dark-green);margin-bottom:10px;">🔒 Ubah Password (Opsional)</div>
              <div class="fg">
                <label class="flabel">Password Saat Ini</label>
                <input type="password" name="current_password" class="finput" placeholder="Kosongkan jika tidak ingin ganti">
              </div>
              <div class="frow fg">
                <div>
                  <label class="flabel">Password Baru</label>
                  <input type="password" name="new_password" class="finput" placeholder="Min. 8 karakter">
                </div>
                <div>
                  <label class="flabel">Konfirmasi Password Baru</label>
                  <input type="password" name="new_password_confirmation" class="finput" placeholder="Ketik ulang">
                </div>
              </div>
            </div>

            <button type="submit" class="btn-primary btn-primary-full">
              <i class="ti ti-device-floppy"></i> Simpan Perubahan
            </button>
          </form>
        </div>

        {{-- Stats & Status --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">
          <div class="gi-banner">
            <div class="gi-top">
              <div class="gi-icon"><i class="ti ti-leaf" style="font-size:18px;color:var(--color-moss-green);"></i></div>
              <div>
                <div class="gi-label">Rekam Jejak Lingkungan</div>
                <div class="gi-name">Green Identity Score</div>
              </div>
            </div>
            <div class="gi-metrics" style="grid-template-columns:1fr 1fr;">
              <div class="gi-m"><div class="gi-m-val">{{ number_format($stats['total_berat'],1) }}</div><div class="gi-m-sub">Kg Diselamatkan</div></div>
              <div class="gi-m"><div class="gi-m-val">{{ $stats['postingan_aktif'] }}</div><div class="gi-m-sub">Total Postingan</div></div>
              <div class="gi-m"><div class="gi-m-val gold">{{ $stats['total_koin'] }}</div><div class="gi-m-sub">Total Koin</div></div>
              <div class="gi-m"><div class="gi-m-val" style="color:var(--color-rosy-brown);">{{ \App\Models\Order::where('buyer_id',auth()->id())->where('status','done')->count() }}</div><div class="gi-m-sub">Produk Dibeli</div></div>
            </div>
          </div>
          <div class="card">
            <div class="card-title"><i class="ti ti-shield-check"></i> Status Akun</div>
            <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--color-beige2);">
              <i class="ti ti-circle-check" style="font-size:18px;color:#22c55e;"></i>
              <div>
                <div style="font-size:13px;font-weight:700;color:var(--color-dark-green);">Akun Terverifikasi</div>
                <div style="font-size:11px;color:#6b7280;">Diverifikasi oleh Admin UpcycleMatch</div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:10px 0;">
              <i class="ti ti-users" style="font-size:18px;color:var(--color-midnight-green);"></i>
              <div>
                <div style="font-size:13px;font-weight:700;color:var(--color-dark-green);">Peran: Kontributor</div>
                <div style="font-size:11px;color:#6b7280;">Dapat upload limbah &amp; beli produk</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- end .main --}}
</div>{{-- end .body-area --}}

{{-- FOOTER MINIMAL --}}
<footer class="dash-footer-minimal">
  © 2026 UpcycleMatch ·
  <a href="#">Privacy Policy</a> ·
  <a href="#">Terms of Use</a> ·
  🌿 SDG 12
</footer>

{{-- MAP PICKER MODAL --}}
<div id="pickerOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:9999;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:640px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.4);">
    <div style="background:var(--color-dark-green);padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
      <div style="font-family:var(--font-heading);font-size:17px;color:var(--color-beige);">📍 Pin Lokasi Penjemputan</div>
      <button onclick="closePicker()" style="background:none;border:none;color:#9FE1CB;font-size:22px;cursor:pointer;line-height:1;">✕</button>
    </div>
    <div style="padding:10px 16px;background:var(--color-beige);font-size:12px;color:#3B6D11;">
      <i class="ti ti-info-circle"></i> Klik di peta untuk menentukan titik lokasi penjemputan.
    </div>
    <div id="pickerMap" style="height:360px;width:100%;"></div>
    <div style="padding:14px 20px;display:flex;align-items:center;gap:12px;">
      <div id="pickerAddr" style="font-size:12px;color:#666;flex:1;">Belum ada titik dipilih.</div>
      <button onclick="useMyLoc()" style="padding:9px 16px;border-radius:8px;background:var(--color-midnight-green);color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;">📍 Lokasimu</button>
      <button onclick="confirmPicker()" style="padding:9px 20px;border-radius:8px;background:var(--color-moss-green);color:var(--color-dark-green);border:2px solid var(--color-dark-green);font-size:13px;font-weight:800;cursor:pointer;">✅ Konfirmasi</button>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* TAB NAVIGATION */
function showSec(id) {
  document.querySelectorAll('.page-sec').forEach(el => el.classList.remove('on'));
  document.querySelectorAll('.snav-item').forEach(el => el.classList.remove('on'));
  document.querySelectorAll('.tnav').forEach(el => el.classList.remove('on'));
  document.getElementById('sec-' + id).classList.add('on');
  const navMap = { upload:0, tracker:1, wallet:2, gallery:3, profile:4 };
  const tnav   = { upload:0, tracker:1, wallet:2, gallery:3 };
  const items = document.querySelectorAll('.snav-item');
  if (navMap[id] !== undefined && items[navMap[id]]) items[navMap[id]].classList.add('on');
  const btns = document.querySelectorAll('.tnav');
  if (tnav[id] !== undefined && btns[tnav[id]]) btns[tnav[id]].classList.add('on');
}

/* FOTO PREVIEW */
function previewFoto(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('foto-img').src = e.target.result;
      document.getElementById('foto-preview').style.display = 'block';
      document.getElementById('foto-label').textContent = '✅ ' + input.files[0].name;
      document.getElementById('foto-drop').style.borderColor = '#839958';
      document.getElementById('foto-drop').style.background = '#EAF3DE';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

/* WITHDRAWAL METHOD SELECTOR */
const bankOptions = [
  { value:'', text:'Pilih Bank' }, { value:'BCA', text:'BCA' }, { value:'BRI', text:'BRI' },
  { value:'BNI', text:'BNI' }, { value:'Mandiri', text:'Mandiri' }, { value:'BSI', text:'BSI' },
  { value:'CIMB', text:'CIMB Niaga' }, { value:'Permata', text:'Permata' }, { value:'Bank Lainnya', text:'Bank Lainnya' }
];
const ewOptions = [
  { value:'', text:'Pilih E-Wallet' }, { value:'GoPay', text:'GoPay (Gopay/Gojek)' },
  { value:'OVO', text:'OVO' }, { value:'Dana', text:'Dana' },
  { value:'ShopeePay', text:'ShopeePay' }, { value:'LinkAja', text:'LinkAja' }
];
function selMetode(m) {
  document.getElementById('mb-bank').classList.toggle('on', m === 'bank');
  document.getElementById('mb-ew').classList.toggle('on', m === 'ewallet');
  document.getElementById('metode-val').value = m;
  const sel = document.getElementById('inp-platform');
  const options = m === 'bank' ? bankOptions : ewOptions;
  sel.innerHTML = options.map(o => `<option value="${o.value}">${o.text}</option>`).join('');
  document.getElementById('platform-label').textContent = m === 'bank' ? 'Nama Bank' : 'Platform E-Wallet';
  document.getElementById('norek-label').textContent = m === 'bank' ? 'Nomor Rekening' : 'Nomor HP / ID Akun';
  document.getElementById('inp-norek').placeholder = m === 'bank' ? 'Contoh: 1234567890' : 'Contoh: 08xxxxxxxxxx';
}
function updateNominal(val) {
  const n = parseInt(val) || 0;
  const el = document.getElementById('nominal-preview');
  if(n >= 4) { el.textContent = '= Rp' + (n * 2500).toLocaleString('id-ID'); el.style.color = 'var(--color-midnight-green)'; }
  else { el.textContent = n > 0 ? '⚠️ Minimum 4 koin' : ''; el.style.color = '#ef4444'; }
}
function previewProfilePhoto(input) {
  if(input.files && input.files[0]) {
    const r = new FileReader();
    r.onload = e => {
      const existing = document.getElementById('profile-preview');
      const fallback = document.getElementById('profile-preview-fallback');
      if(existing) { existing.src = e.target.result; }
      else if(fallback) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.id = 'profile-preview';
        img.style.cssText = 'width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--color-moss-green);';
        fallback.parentNode.insertBefore(img, fallback);
        fallback.style.display = 'none';
      }
    };
    r.readAsDataURL(input.files[0]);
  }
}

/* MAP PICKER */
let pickerMap = null, pickerMarker = null, pickerLat = null, pickerLng = null;
function openPickerModal() {
  document.getElementById('pickerOverlay').style.display = 'flex';
  setTimeout(() => {
    if (!pickerMap) {
      pickerMap = L.map('pickerMap').setView([-2.548926, 118.0148634], 5);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom:19 }).addTo(pickerMap);
      pickerMap.on('click', e => {
        pickerLat = e.latlng.lat.toFixed(6);
        pickerLng = e.latlng.lng.toFixed(6);
        if (pickerMarker) pickerMap.removeLayer(pickerMarker);
        pickerMarker = L.marker([pickerLat, pickerLng]).addTo(pickerMap);
        document.getElementById('pickerAddr').textContent = '📍 Lat: ' + pickerLat + ', Lng: ' + pickerLng;
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + pickerLat + '&lon=' + pickerLng)
          .then(r => r.json()).then(d => {
            document.getElementById('pickerAddr').textContent = '📍 ' + (d.display_name || '');
            if (document.getElementById('inp-alamat')) {
              document.getElementById('inp-alamat').value = (d.display_name || '').split(',').slice(0,4).join(',').trim();
            }
          }).catch(() => {});
      });
    } else { pickerMap.invalidateSize(); }
  }, 200);
}
function useMyLoc() {
  if (!pickerMap) return;
  pickerMap.locate({ setView:true, maxZoom:15 });
  pickerMap.once('locationfound', e => {
    pickerLat = e.latlng.lat.toFixed(6); pickerLng = e.latlng.lng.toFixed(6);
    if (pickerMarker) pickerMap.removeLayer(pickerMarker);
    pickerMarker = L.marker([pickerLat, pickerLng]).addTo(pickerMap);
    document.getElementById('pickerAddr').textContent = '📍 Lat: ' + pickerLat + ', Lng: ' + pickerLng;
  });
  pickerMap.once('locationerror', () => alert('GPS tidak tersedia. Klik manual di peta.'));
}
function confirmPicker() {
  if (!pickerLat) { alert('Klik dulu di peta!'); return; }
  document.getElementById('inp-lat').value = pickerLat;
  document.getElementById('inp-lng').value = pickerLng;
  document.getElementById('map-txt').textContent = '✅ Lokasi dipilih!';
  closePicker();
}
function closePicker() { document.getElementById('pickerOverlay').style.display = 'none'; }

// Auto-show section from URL hash
const hash = window.location.hash.replace('#','');
if (['tracker','wallet','gallery','profile'].includes(hash)) showSec(hash);
</script>
</body>
</html>
