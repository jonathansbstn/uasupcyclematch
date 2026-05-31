@extends('layouts.app')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box;margin:0;padding:0;}
.w{background:#F7F4D5;min-height:920px;font-family:'DM Sans',sans-serif;}
.nav{display:flex;align-items:center;justify-content:space-between;padding:0 32px;height:56px;background:#0A3323;}
.logo{font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#F7F4D5;}
.logo span{color:#839958;}
.layout{display:grid;grid-template-columns:200px 1fr;min-height:864px;}
.sidebar{background:#051A13;}
.sitem{display:flex;align-items:center;gap:10px;padding:11px 20px;font-size:13px;color:#9FE1CB;cursor:pointer;font-family:'DM Sans',sans-serif;}
.sitem.a{background:rgba(131,153,88,0.15);color:#F7F4D5;border-right:3px solid #839958;}
.main{padding:28px;}
.toprow{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px;}
.ptitle{font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#0A3323;}
.psub{font-size:13px;color:#3B6D11;margin-top:3px;}
.searchrow{display:flex;gap:10px;margin-bottom:20px;}
.sinput{flex:1;padding:10px 14px;border-radius:10px;border:1.5px solid #C0DD97;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;color:#0A3323;}
.sselect{padding:10px 14px;border-radius:10px;border:1.5px solid #C0DD97;font-family:'DM Sans',sans-serif;font-size:13px;background:#fff;color:#0A3323;}
.kpirow{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:22px;}
.kpi{border-radius:14px;padding:15px 14px;}
.kpi-g{background:#EAF3DE;border:1.5px solid #C0DD97;}
.kpi-t{background:#E1F5EE;border:1.5px solid #9FE1CB;}
.kpi-d{background:#0A3323;border:1.5px solid #27500A;}
.klbl{font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:500;margin-bottom:6px;}
.kl-g{color:#3B6D11;} .kl-t{color:#0F6E56;} .kl-d{color:#9FE1CB;}
.knum{font-family:'Syne',sans-serif;font-size:22px;font-weight:800;}
.kn-g{color:#0A3323;} .kn-t{color:#085041;} .kn-d{color:#F7F4D5;}
.gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
.gcard{background:#fff;border-radius:18px;border:1.5px solid #C0DD97;overflow:hidden;}
.gthumb{height:160px;display:flex;align-items:center;justify-content:center;position:relative;}
.gt-1{background:#839958;}
.gt-2{background:#105666;}
.gt-3{background:#D3968C;}
.gt-4{background:#0A3323;}
.gt-5{background:#3B6D11;}
.gt-6{background:#4B1528;}
.gthumb-icon{font-size:42px;}
.gthumb-badge{position:absolute;top:12px;left:12px;font-size:11px;padding:4px 10px;border-radius:100px;font-family:'DM Sans',sans-serif;font-weight:500;}
.gb-pub{background:rgba(10,51,35,0.7);color:#9FE1CB;}
.gb-pend{background:rgba(5,26,19,0.7);color:#FAC775;}
.gaction{position:absolute;top:12px;right:12px;display:flex;gap:6px;}
.gact-btn{width:28px;height:28px;border-radius:8px;background:rgba(5,26,19,0.6);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;}
.ginfo{padding:14px 16px;}
.gtitle{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:#0A3323;margin-bottom:3px;}
.gby{font-size:12px;color:#3B6D11;margin-bottom:8px;}
.gtags{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;}
.gtag{font-size:11px;padding:3px 8px;border-radius:100px;}
.tg-g{background:#EAF3DE;color:#3B6D11;}
.tg-t{background:#E1F5EE;color:#0F6E56;}
.gstats{display:flex;gap:12px;}
.gst{text-align:left;}
.gsv{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:#0A3323;}
.gsl{font-size:11px;color:#3B6D11;}
.gfoot{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;background:#F7F4D5;border-top:1px solid #EAF3DE;}
.gf-del{font-size:11px;padding:5px 12px;border-radius:100px;background:transparent;color:#993556;border:1px solid #F4C0D1;font-family:'Syne',sans-serif;font-weight:700;cursor:pointer;}
.gf-pub{font-size:11px;padding:5px 12px;border-radius:100px;background:#0A3323;color:#F7F4D5;border:none;font-family:'Syne',sans-serif;font-weight:700;cursor:pointer;}
.pglbl{background:#D3968C;color:#4B1528;font-family:'Syne',sans-serif;font-size:10px;font-weight:700;padding:4px 10px;border-radius:100px;letter-spacing:1px;}
</style>
<div class="w">
  <nav class="nav">
    <div class="logo">Upcycle<span>Match</span></div>
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="background:#D3968C;color:#4B1528;font-family:'Syne',sans-serif;font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;">Admin Panel</div>
      <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9FE1CB;">Ahmad Fauzi</span>
      <div class="pglbl">PAGE 4C — GALERI KARYA</div>
    </div>
  </nav>

  <div class="layout">
    <div class="sidebar">
      <div style="padding:20px 0;">
        <div class="sitem"><span style="font-size:16px;width:18px;">📊</span>Impact Analytics</div>
        <div class="sitem"><span style="font-size:16px;width:18px;">👥</span>Verifikasi User</div>
        <div class="sitem"><span style="font-size:16px;width:18px;">🗃</span>Data Limbah</div>
        <div class="sitem a"><span style="font-size:16px;width:18px;">🖼</span>Galeri Karya</div>
        <div class="sitem"><span style="font-size:16px;width:18px;">📥</span>Export Report</div>
        <div class="sitem"><span style="font-size:16px;width:18px;">⚙️</span>Pengaturan</div>
      </div>
    </div>

    <div class="main">
      <div class="toprow">
        <div>
          <div class="ptitle">Galeri Karya Upcycle</div>
          <div class="psub">Moderasi dan kelola semua karya yang diunggah penjahit · 1.204 karya</div>
        </div>
        <div style="display:flex;gap:10px;">
          <button style="background:transparent;color:#0A3323;border:1.5px solid #C0DD97;padding:9px 18px;border-radius:100px;font-family:'Syne',sans-serif;font-size:13px;font-weight:700;cursor:pointer;">Filter ▼</button>
          <button style="background:#0A3323;color:#F7F4D5;border:none;padding:9px 18px;border-radius:100px;font-family:'Syne',sans-serif;font-size:13px;font-weight:700;cursor:pointer;">Pending (12) ●</button>
        </div>
      </div>

      <div class="kpirow">
        <div class="kpi kpi-g"><div class="klbl kl-g">Total Karya Published</div><div class="knum kn-g">1.204</div></div>
        <div class="kpi kpi-t"><div class="klbl kl-t">Menunggu Review</div><div class="knum kn-t">12</div></div>
        <div class="kpi kpi-d"><div class="klbl kl-d">Total Kg Diselamatkan</div><div class="knum kn-d">12.847</div></div>
      </div>

      <div class="searchrow">
        <input class="sinput" placeholder="Cari nama produk atau nama penjahit..." />
        <select class="sselect"><option>Semua Status</option><option>Published</option><option>Pending Review</option></select>
        <select class="sselect"><option>Semua Jenis</option><option>Tas</option><option>Aksesori</option><option>Keset</option><option>Pakaian</option></select>
        <select class="sselect"><option>Terbaru</option><option>Terlama</option><option>Berat Terbesar</option></select>
      </div>

      <div class="gallery-grid">
        <div class="gcard">
          <div class="gthumb gt-1">
            <div class="gthumb-icon">👜</div>
            <span class="gthumb-badge gb-pub">Published</span>
            <div class="gaction"><button class="gact-btn">✏️</button><button class="gact-btn">🗑</button></div>
          </div>
          <div class="ginfo">
            <div class="gtitle">Tote Bag Denim Premium</div>
            <div class="gby">oleh Rajin Jahit Studio · Surabaya</div>
            <div class="gtags"><span class="gtag tg-g">Denim</span><span class="gtag tg-t">Tas</span></div>
            <div class="gstats">
              <div class="gst"><div class="gsv">1.2 kg</div><div class="gsl">Kain dihemat</div></div>
              <div class="gst"><div class="gsv">14 Jun</div><div class="gsl">Tanggal upload</div></div>
            </div>
          </div>
          <div class="gfoot"><button class="gf-del">Hapus</button><span style="font-size:12px;color:#3B6D11;">52 tayangan</span></div>
        </div>

        <div class="gcard">
          <div class="gthumb gt-2">
            <div class="gthumb-icon">🎀</div>
            <span class="gthumb-badge gb-pub">Published</span>
            <div class="gaction"><button class="gact-btn">✏️</button><button class="gact-btn">🗑</button></div>
          </div>
          <div class="ginfo">
            <div class="gtitle">Scrunchie Set Katun 5 pcs</div>
            <div class="gby">oleh Kreasi Nusantara · Bandung</div>
            <div class="gtags"><span class="gtag tg-g">Katun</span><span class="gtag tg-t">Aksesori</span></div>
            <div class="gstats">
              <div class="gst"><div class="gsv">0.4 kg</div><div class="gsl">Kain dihemat</div></div>
              <div class="gst"><div class="gsv">12 Jun</div><div class="gsl">Tanggal upload</div></div>
            </div>
          </div>
          <div class="gfoot"><button class="gf-del">Hapus</button><span style="font-size:12px;color:#3B6D11;">31 tayangan</span></div>
        </div>

        <div class="gcard" style="border-color:#FAC775;">
          <div class="gthumb gt-3">
            <div class="gthumb-icon">🧣</div>
            <span class="gthumb-badge gb-pend" style="background:rgba(99,56,6,0.8);color:#FAC775;">Pending Review</span>
            <div class="gaction"><button class="gact-btn">✏️</button><button class="gact-btn">🗑</button></div>
          </div>
          <div class="ginfo">
            <div class="gtitle">Syal Perca Warna Pastel</div>
            <div class="gby">oleh Umi's Craft · Jogjakarta</div>
            <div class="gtags"><span class="gtag tg-g">Sutra</span><span class="gtag" style="background:#FFF4E0;color:#633806;">Aksesori</span></div>
            <div class="gstats">
              <div class="gst"><div class="gsv">0.8 kg</div><div class="gsl">Kain dihemat</div></div>
              <div class="gst"><div class="gsv">Hari ini</div><div class="gsl">Baru diupload</div></div>
            </div>
          </div>
          <div class="gfoot"><button class="gf-del">Tolak</button><button class="gf-pub">Approve & Publish</button></div>
        </div>

        <div class="gcard">
          <div class="gthumb gt-4">
            <div class="gthumb-icon" style="color:#839958;">🧶</div>
            <span class="gthumb-badge gb-pub">Published</span>
            <div class="gaction"><button class="gact-btn">✏️</button><button class="gact-btn">🗑</button></div>
          </div>
          <div class="ginfo">
            <div class="gtitle">Keset Rajut Perca Colorful</div>
            <div class="gby">oleh Umi's Craft · Jogjakarta</div>
            <div class="gtags"><span class="gtag tg-g">Katun</span><span class="gtag tg-t">Keset</span></div>
            <div class="gstats">
              <div class="gst"><div class="gsv">2.1 kg</div><div class="gsl">Kain dihemat</div></div>
              <div class="gst"><div class="gsv">10 Jun</div><div class="gsl">Tanggal upload</div></div>
            </div>
          </div>
          <div class="gfoot"><button class="gf-del">Hapus</button><span style="font-size:12px;color:#3B6D11;">78 tayangan</span></div>
        </div>

        <div class="gcard">
          <div class="gthumb gt-5">
            <div class="gthumb-icon">👗</div>
            <span class="gthumb-badge gb-pub">Published</span>
            <div class="gaction"><button class="gact-btn">✏️</button><button class="gact-btn">🗑</button></div>
          </div>
          <div class="ginfo">
            <div class="gtitle">Dress Patchwork Bohemian</div>
            <div class="gby">oleh Jahit Mandiri · Surabaya</div>
            <div class="gtags"><span class="gtag tg-g">Katun</span><span class="gtag tg-t">Pakaian</span></div>
            <div class="gstats">
              <div class="gst"><div class="gsv">1.6 kg</div><div class="gsl">Kain dihemat</div></div>
              <div class="gst"><div class="gsv">8 Jun</div><div class="gsl">Tanggal upload</div></div>
            </div>
          </div>
          <div class="gfoot"><button class="gf-del">Hapus</button><span style="font-size:12px;color:#3B6D11;">94 tayangan</span></div>
        </div>

        <div class="gcard" style="border-color:#FAC775;">
          <div class="gthumb gt-6">
            <div class="gthumb-icon">🎒</div>
            <span class="gthumb-badge gb-pend" style="background:rgba(99,56,6,0.8);color:#FAC775;">Pending Review</span>
            <div class="gaction"><button class="gact-btn">✏️</button><button class="gact-btn">🗑</button></div>
          </div>
          <div class="ginfo">
            <div class="gtitle">Ransel Denim Upcycle</div>
            <div class="gby">oleh Studio Jahit Kita · Jakarta</div>
            <div class="gtags"><span class="gtag" style="background:#0F3B42;color:#5DCAA5;">Denim</span><span class="gtag tg-t">Tas</span></div>
            <div class="gstats">
              <div class="gst"><div class="gsv">2.4 kg</div><div class="gsl">Kain dihemat</div></div>
              <div class="gst"><div class="gsv">Hari ini</div><div class="gsl">Baru diupload</div></div>
            </div>
          </div>
          <div class="gfoot"><button class="gf-del">Tolak</button><button class="gf-pub">Approve & Publish</button></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection