@extends('layouts.app')
@section('title', 'UpcycleMatch - Kamus Kain')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght=400;600;700;800&family=DM+Sans:ital,wght=0,300;0,400;0,500;1,300&display=swap');
  
  /* Reset global biar serasi dengan dashboard & galeri */
  body, .container, .wrapper, .main-content, #app, .content-wrapper { padding: 0 !important; margin: 0 !important; max-width: 100% !important; width: 100% !important; background: #F7F4D5 !important; overflow-x: hidden; }
  
  /* Navbar */
  .nav { display: flex; align-items: center; justify-content: space-between; padding: 20px 48px; background: #0A3323; }
  .logo { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; color: #F7F4D5; letter-spacing: -0.5px; } 
  .logo span { color: #839958; }
  .nav-links { display: flex; gap: 28px; align-items: center; }
  .nav-link { font-family: 'DM Sans', sans-serif; font-size: 14px; color: #9FE1CB; text-decoration: none; font-weight: 400; } 
  .nav-link:hover { color: #F7F4D5; }
  .nav-link.active { color: #F7F4D5; font-weight: bold; }
  .nav-btn { background: #839958; color: #0A3323; padding: 8px 20px; border-radius: 100px; font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700; border: none; text-decoration: none; }

  /* Main Container Layout */
  .main-container { padding: 30px 48px 60px 48px; font-family: 'DM Sans', sans-serif; background: #F7F4D5; }
  .breadcrumb { font-size: 12px; color: #3B6D11; margin-bottom: 24px; }
  .breadcrumb span { color: #839958; font-weight: bold; }

  /* Header Section */
  .kamus-header { margin-bottom: 40px; text-align: left; }
  .kamus-title { font-family: 'Syne', sans-serif; font-size: 36px; font-weight: 800; color: #0A3323; }
  .kamus-sub { font-size: 15px; color: #3B6D11; margin-top: 6px; max-width: 650px; }

  /* 📚 Grid Kamus Kain Model Figma */
  .kamus-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
  .kamus-card { background: #fff; border: 1.5px solid #C0DD97; border-radius: 20px; padding: 24px; text-align: center; box-sizing: border-box; transition: transform 0.2s, box-shadow 0.2s; }
  .kamus-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(59, 109, 17, 0.08); }
  
  /* Highlight Card Model Denim Aktif di Figma */
  .kamus-card.active { background: #0A3323; border-color: #0A3323; color: #F7F4D5; }
  
  .kamus-img-box { width: 100%; height: 160px; border-radius: 14px; overflow: hidden; margin-bottom: 18px; border: 1px solid #FAF9ED; }
  .kamus-img { width: 100%; height: 100%; object-fit: cover; }
  
  .kamus-name { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; margin-bottom: 10px; color: #0A3323; letter-spacing: -0.3px; }
  .kamus-card.active .kamus-name { color: #F7F4D5; }
  
  .kamus-desc { font-family: 'DM Sans', sans-serif; font-size: 13px; color: #3B6D11; line-height: 1.6; text-align: justify; }
  .kamus-card.active .kamus-desc { color: #9FE1CB; }

  /* Info Note Bawah */
  .info-note { background: #E9F2D7; border: 1.5px solid #C0DD97; border-radius: 16px; padding: 20px; color: #0A3323; font-size: 13px; margin-top: 40px; display: flex; align-items: center; gap: 12px; }
</style>

<div class="page-wrap">
  <nav class="nav">
    <div class="logo">Upcycle<span>Match</span></div>
    <div class="nav-links">
      <a class="nav-link" href="{{ route('contributor.dashboard') }}">Beranda</a>
      <a class="nav-link" href="{{ route('contributor.galeri') }}">Galeri</a>
      <a class="nav-link active" href="{{ route('contributor.kamus') }}">Kamus Kain</a>
      <a class="nav-link" href="{{ route('contributor.peta') }}">Peta Jangkauan</a>
      <a class="nav-btn" href="#">👋 {{ Auth::user()->name ?? 'Contributor' }}</a>
    </div>
  </nav>

  <div class="main-container">
    <div class="breadcrumb">Dashboard > <span>Kamus Kain</span></div>

    <div class="kamus-header">
      <div class="kamus-title">Kamus Kain 📚</div>
      <div class="kamus-sub">Kenali karakteristik jenis bahan pakaianmu sebelum berkontribusi. Langkah kecil ini mempermudah mitra UMKM penjahit lokal mencocokkan pola daur ulang produksi!</div>
    </div>

    <div class="kamus-grid">
      
      <div class="kamus-card">
        <div class="kamus-img-box">
          <img src="https://images.unsplash.com/photo-1606744824163-985d376605aa?q=80&w=400&auto=format&fit=crop" class="kamus-img" alt="Bahan Katun">
        </div>
        <div class="kamus-name">KATUN</div>
        <div class="kamus-desc">Serat alami yang sangat mudah terurai dan didaur ulang. Memiliki daya serap air yang sangat tinggi, tekstur halus, serta sangat ideal dimanfaatkan untuk kerajinan kain perca, masker, maupun baju rumahan.</div>
      </div>

      <div class="kamus-card active">
        <div class="kamus-img-box">
          <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?q=80&w=400&auto=format&fit=crop" class="kamus-img" alt="Bahan Denim">
        </div>
        <div class="kamus-name">DENIM</div>
        <div class="kamus-desc">Kain rajutan kokoh berbasis katun twill yang sangat tebal dan awet. Di ekosistem upcycle, limbah denim menempati kelas premium bernilai jual tinggi karena sangat populer diubah menjadi produk kokoh seperti tote bag, jaket patchwork, atau pouch.</div>
      </div>

      <div class="kamus-card">
        <div class="kamus-img-box">
          <img src="https://images.unsplash.com/photo-1618220179428-22790b461013?q=80&w=400&auto=format&fit=crop" class="kamus-img" alt="Bahan Sutra">
        </div>
        <div class="kamus-name">SUTRA</div>
        <div class="kamus-desc">Serat protein alami premium berkilau halus yang diproduksi dari kepompong ulat sutra. Limbahnya membutuhkan penanganan yang sangat hati-hati, namun memberikan nilai tambah mewah yang tinggi untuk aksesoris rambut (scrunchie) maupun outer kombinasi.</div>
      </div>

      <div class="kamus-card">
        <div class="kamus-img-box">
          <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=400&auto=format&fit=crop" class="kamus-img" alt="Bahan Polyester">
        </div>
        <div class="kamus-name">POLYESTER</div>
        <div class="kamus-desc">Bahan sintetis berbasis polimer plastik yang tahan lama, tidak mudah kusut, dan cepat kering. Memerlukan kreasi ekstra saat di-upcycle, umumnya sangat cocok dijadikan produk fungsional pelapis anti air seperti dompet kosmetik atau bagian dalam tas.</div>
      </div>

    </div>

    <div class="info-note">
      <span style="font-size: 20px;">💡</span>
      <div><strong>Tips Kontribusi:</strong> Jika limbah kain milikmu berupa campuran (mix), pilihlah kategori bahan dominan yang paling mendekati rincian fisik kain saat mengisi form postingan limbah agar akurat.</div>
    </div>

  </div>
</div>
@endsection