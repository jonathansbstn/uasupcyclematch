@extends('layouts.app')
@section('title', 'UpcycleMatch - Detail Limbah Kain')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght=400;600;700;800&family=DM+Sans:ital,wght=0,300;0,400;0,500;1,300&display=swap');
  body, .container, .wrapper, .main-content, #app, .content-wrapper { padding: 0 !important; margin: 0 !important; max-width: 100% !important; width: 100% !important; background: #F7F4D5 !important; overflow-x: hidden; }
  .nav { display: flex; align-items: center; justify-content: space-between; padding: 20px 48px; background: #0A3323; }
  .logo { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; color: #F7F4D5; letter-spacing: -0.5px; } 
  .logo span { color: #839958; }
  .main-container { padding: 30px 48px 60px 48px; font-family: 'DM Sans', sans-serif; background: #F7F4D5; }
  .breadcrumb { font-size: 12px; color: #3B6D11; margin-bottom: 24px; }
  .breadcrumb a { color: #3B6D11; text-decoration: none; }
  .breadcrumb span { color: #839958; font-weight: bold; }
  .detail-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 32px; align-items: start; }
  .visual-column { display: flex; flex-direction: column; gap: 20px; }
  .main-image-box { width: 100%; height: 340px; background: #fff; border: 1.5px solid #C0DD97; border-radius: 24px; overflow: hidden; position: relative; }
  .main-image { width: 100%; height: 100%; object-fit: cover; }
  .status-tag { position: absolute; top: 20px; right: 20px; padding: 6px 16px; border-radius: 100px; font-size: 12px; font-weight: bold; font-family: 'Syne', sans-serif; }
  .bg-avail { background: #E9F2D7; color: #3B6D11; border: 1px solid #C0DD97; }
  .info-column { background: #fff; border: 1.5px solid #C0DD97; border-radius: 24px; padding: 32px; box-sizing: border-box; }
  .info-title { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: #0A3323; line-height: 1.2; }
  .info-meta { font-size: 12px; color: #3B6D11; margin-top: 6px; }
  .spec-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
  .spec-table td { padding: 12px 0; border-bottom: 1px solid #FAF9ED; font-size: 14px; }
  .spec-label { color: #839958; font-weight: 500; width: 40%; }
  .spec-value { color: #0A3323; font-weight: bold; }
  .desc-box { background: #FAF9ED; border-radius: 16px; padding: 20px; margin-bottom: 24px; }
  .desc-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: #0A3323; margin-bottom: 8px; }
  .desc-text { font-size: 13px; color: #3B6D11; line-height: 1.6; }
  .map-preview-box { background: #105666; border-radius: 16px; padding: 24px; color: #9FE1CB; text-size: 12px; text-align: center; margin-top: 16px; }
</style>

<div class="page-wrap">
  <nav class="nav">
    <div class="logo">Upcycle<span>Match</span></div>
  </nav>

  <div class="main-container">
    <div class="breadcrumb">
      <a href="{{ route('contributor.upload') }}">Dashboard</a> > <span>Detail Limbah Kain #{{ $limbah->id }}</span>
    </div>

    <div class="detail-grid">
      <div class="visual-column">
        <div class="main-image-box">
          <img src="{{ asset($limbah->foto) }}" class="main-image" alt="Foto Kain">
          <div class="status-tag bg-avail">{{ $limbah->status }}</div>
        </div>
      </div>

      <div class="info-column">
        <div class="info-header">
          <h1 class="info-title">{{ $limbah->judul }}</h1>
          <div class="info-meta">Diposting oleh: <strong>Kamu (Contributor)</strong></div>
        </div>

        <hr style="border: 0; border-top: 1.5px solid #FAF9ED; margin-bottom: 20px;">

        <table class="spec-table">
          <tr>
            <td class="spec-label">👕 Jenis Bahan</td>
            <td class="spec-value">{{ $limbah->bahan }}</td>
          </tr>
          <tr>
            <td class="spec-label">⚖️ Berat Bersih</td>
            <td class="spec-value">{{ $limbah->berat }} Kg</td>
          </tr>
          <tr>
            <td class="spec-label">🧼 Status Higienis</td>
            <td class="spec-value" style="color: #3B6D11;">✓ Sudah Dicuci Bersih</td>
          </tr>
        </table>

        <div class="desc-box">
          <div class="desc-title">📝 Catatan Kondisi Kain</div>
          <div class="desc-text">{{ $limbah->deskripsi }}</div>
        </div>

        <div class="map-preview-box">
          <strong>Peta Terkunci ke Alamat Rumahmu</strong><br>
          <span style="opacity:0.8; font-size:11px;">Surabaya, Jawa Timur</span>
        </div>

        <a href="{{ route('contributor.upload') }}" style="display:block; text-align:center; background:#0A3323; color:#F7F4D5; text-decoration:none; padding:14px; margin-top:24px; border-radius:12px; font-weight:bold; font-size:14px;">← Kembali ke Tracking</a>
      </div>

    </div>
  </div>
</div>
@endsection