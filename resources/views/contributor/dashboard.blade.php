
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Nunito:wght@400;500;600;700;800&display=swap');
*{box-sizing:border-box;margin:0;padding:0;}
:root{
  --dk:#0A3323;--moss:#839958;--beige:#F7F4D5;--beige2:#EAE7BE;--rosy:#D3968C;--mid:#105666;
  --white:#ffffff;--text:#0A3323;--muted:#5a7a5a;--r:10px;--rlg:16px;
}
.app{font-family:'Nunito',sans-serif;background:#F0EDD0;min-height:100vh;color:var(--text);}

.topbar{background:var(--dk);height:52px;display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;}
.topbar-logo{font-family:'DM Serif Display',serif;font-size:20px;color:var(--beige);letter-spacing:-0.3px;}
.topbar-logo span{color:var(--rosy);}
.topbar-nav{display:flex;gap:2px;}
.tnav{background:none;border:none;padding:5px 12px;border-radius:6px;font-family:'Nunito',sans-serif;font-size:12px;font-weight:600;color:rgba(247,244,213,0.6);cursor:pointer;}
.tnav.on{background:var(--moss);color:var(--dk);}
.topbar-right{display:flex;align-items:center;gap:10px;}
.koin-pill{background:rgba(247,244,213,0.12);border:1px solid rgba(247,244,213,0.25);border-radius:20px;padding:4px 12px;font-size:12px;font-weight:700;color:var(--beige);display:flex;align-items:center;gap:5px;}
.notif-btn{background:none;border:none;color:rgba(247,244,213,0.7);cursor:pointer;font-size:18px;position:relative;}
.notif-dot{width:7px;height:7px;background:var(--rosy);border-radius:50%;position:absolute;top:0;right:0;}
.avatar-pill{display:flex;align-items:center;gap:8px;cursor:pointer;}
.av-circle{width:32px;height:32px;border-radius:50%;background:var(--rosy);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;}
.av-name{font-size:12px;font-weight:700;color:var(--beige);}

.breadcrumb{background:var(--beige2);padding:8px 1.5rem;font-size:11px;color:var(--muted);border-bottom:1px solid rgba(10,51,35,0.08);}
.breadcrumb span{color:var(--dk);font-weight:700;}

.sidenav{width:200px;background:var(--dk);padding:1rem 0.75rem;flex-shrink:0;}
.snav-label{font-size:10px;font-weight:800;color:rgba(247,244,213,0.35);letter-spacing:1px;text-transform:uppercase;padding:0 8px;margin:1rem 0 6px;}
.snav-item{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;font-size:13px;font-weight:600;color:rgba(247,244,213,0.6);cursor:pointer;margin-bottom:2px;transition:all 0.15s;}
.snav-item:hover{background:rgba(247,244,213,0.07);color:var(--beige);}
.snav-item.on{background:var(--moss);color:var(--dk);}
.snav-item i{font-size:16px;}

.body-area{display:flex;min-height:calc(100vh - 88px);}
.main{flex:1;padding:1.5rem;overflow:auto;}

.page-sec{display:none;}
.page-sec.on{display:block;}

.sec-hdr{margin-bottom:1.25rem;}
.sec-title{font-family:'DM Serif Display',serif;font-size:24px;color:var(--dk);}
.sec-sub{font-size:12px;color:var(--muted);margin-top:2px;}

.gi-banner{background:var(--dk);border-radius:var(--rlg);padding:1.5rem;margin-bottom:1.25rem;}
.gi-top{display:flex;align-items:center;gap:10px;margin-bottom:1rem;}
.gi-icon{width:38px;height:38px;border-radius:50%;background:rgba(131,153,88,0.25);display:flex;align-items:center;justify-content:center;font-size:18px;}
.gi-label{font-size:11px;font-weight:700;color:rgba(247,244,213,0.45);text-transform:uppercase;letter-spacing:0.5px;}
.gi-name{font-family:'DM Serif Display',serif;font-size:17px;color:var(--beige);}
.gi-metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;}
.gi-m{background:rgba(247,244,213,0.08);border:1px solid rgba(247,244,213,0.12);border-radius:var(--r);padding:14px 12px;}
.gi-m-val{font-family:'DM Serif Display',serif;font-size:26px;color:var(--beige);line-height:1;}
.gi-m-val.gold{color:#E8C96A;}
.gi-m-sub{font-size:10px;color:rgba(247,244,213,0.4);margin-top:4px;text-transform:uppercase;letter-spacing:0.4px;}

.two-col{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;}
.three-col{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1.25rem;}

.card{background:var(--white);border-radius:var(--rlg);border:1.5px solid var(--beige2);padding:1.25rem;}
.card-title{font-family:'DM Serif Display',serif;font-size:17px;color:var(--dk);margin-bottom:1rem;display:flex;align-items:center;gap:8px;}
.card-title i{font-size:18px;color:var(--moss);}

.fl{font-family:'Nunito',sans-serif;}
.flabel{display:block;font-size:11px;font-weight:800;color:var(--dk);letter-spacing:0.4px;text-transform:uppercase;margin-bottom:5px;}
.finput{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--beige2);background:#fff;font-family:'Nunito',sans-serif;font-size:13px;color:var(--dk);outline:none;}
.finput:focus{border-color:var(--moss);}
.fselect{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--beige2);background:#fff;font-family:'Nunito',sans-serif;font-size:13px;color:var(--dk);outline:none;}
.ftextarea{width:100%;padding:9px 12px;border-radius:8px;border:1.5px solid var(--beige2);background:#fff;font-family:'Nunito',sans-serif;font-size:13px;color:var(--dk);resize:none;outline:none;}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.fg{margin-bottom:12px;}
.map-box{background:#EAF3E6;border-radius:8px;border:1.5px dashed var(--moss);height:130px;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;gap:4px;}
.map-box-icon{font-size:28px;color:var(--moss);}
.map-box-txt{font-size:11px;color:var(--muted);font-weight:600;}
.map-box-sub{font-size:10px;color:var(--moss);opacity:0.7;}
.coord-row{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px;}
.post-btn{width:100%;background:var(--dk);color:var(--beige);border:none;border-radius:8px;padding:11px;font-family:'Nunito',sans-serif;font-size:14px;font-weight:700;cursor:pointer;margin-top:12px;display:flex;align-items:center;justify-content:center;gap:6px;}
.post-btn:hover{background:#0d4530;}

.track-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--beige2);}
.track-item:last-child{border-bottom:none;}
.tdot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.tdot.av{background:#52a87c;}
.tdot.cl{background:var(--mid);}
.tdot.cp{background:var(--rosy);}
.tinfo{flex:1;}
.tname{font-size:13px;font-weight:700;color:var(--dk);}
.tsub{font-size:11px;color:var(--muted);margin-top:1px;}
.tbadge{font-size:10px;font-weight:700;padding:4px 10px;border-radius:20px;white-space:nowrap;}
.bav{background:#EAF3DE;color:#27500A;}
.bcl{background:#E6F1FB;color:#0C447C;}
.bcp{background:#FAEEDA;color:#633806;}

.wallet-card{background:var(--moss);border-radius:var(--rlg);padding:1.25rem;}
.wc-title{font-family:'DM Serif Display',serif;font-size:16px;color:var(--dk);margin-bottom:0.75rem;display:flex;align-items:center;gap:8px;}
.wc-big{display:flex;align-items:baseline;gap:8px;}
.wc-num{font-family:'DM Serif Display',serif;font-size:48px;color:var(--dk);line-height:1;}
.wc-unit{font-size:15px;font-weight:700;color:var(--dk);opacity:0.6;}
.wc-rp{font-size:14px;color:var(--dk);opacity:0.65;margin-top:3px;}
.wc-note{font-size:11px;color:rgba(10,51,35,0.55);margin:6px 0 12px;line-height:1.5;}
.wc-prog{background:rgba(10,51,35,0.15);border-radius:20px;height:6px;margin-bottom:12px;}
.wc-prog-fill{background:var(--dk);border-radius:20px;height:6px;width:65%;}
.wc-prog-label{display:flex;justify-content:space-between;font-size:10px;color:rgba(10,51,35,0.5);margin-top:4px;}
.cvt-btn{background:var(--dk);color:var(--beige);border:none;border-radius:8px;padding:9px 16px;font-family:'Nunito',sans-serif;font-size:12px;font-weight:700;cursor:pointer;width:100%;}

.stat-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:1.25rem;}
.sm-card{border-radius:var(--r);padding:14px;background:var(--white);border:1.5px solid var(--beige2);}
.sm-val{font-family:'DM Serif Display',serif;font-size:28px;color:var(--dk);}
.sm-lbl{font-size:10px;color:var(--muted);margin-top:3px;text-transform:uppercase;letter-spacing:0.4px;}

.history-row{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--beige2);}
.history-row:last-child{border-bottom:none;}
.hist-icon{width:32px;height:32px;border-radius:50%;background:var(--beige2);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;}
.hist-info{flex:1;}
.hist-name{font-size:12px;font-weight:700;color:var(--dk);}
.hist-sub{font-size:11px;color:var(--muted);}
.hist-koin{font-size:14px;font-weight:800;color:#52a87c;}

.modal-bg{background:rgba(10,51,35,0.55);border-radius:var(--rlg);padding:2rem;display:none;}
.modal-bg.on{display:flex;align-items:center;justify-content:center;min-height:400px;}
.modal-box{background:var(--beige);border-radius:var(--rlg);padding:2rem;width:100%;max-width:400px;}
.modal-title{font-family:'DM Serif Display',serif;font-size:20px;color:var(--dk);margin-bottom:4px;}
.modal-sub{font-size:12px;color:var(--muted);margin-bottom:1.25rem;}
.koin-sum{background:var(--dk);border-radius:var(--r);padding:1rem;text-align:center;margin-bottom:1.25rem;}
.ks-big{font-family:'DM Serif Display',serif;font-size:36px;color:var(--beige);}
.ks-rp{font-size:12px;color:rgba(247,244,213,0.55);margin-top:2px;}
.method-row{display:flex;gap:8px;margin-bottom:12px;}
.m-btn{flex:1;padding:9px;border-radius:8px;border:1.5px solid var(--beige2);background:#fff;font-family:'Nunito',sans-serif;font-size:12px;font-weight:700;color:var(--dk);cursor:pointer;text-align:center;}
.m-btn.on{background:var(--dk);color:var(--beige);border-color:var(--dk);}
.modal-close-btn{width:100%;background:none;border:1.5px solid var(--beige2);border-radius:8px;padding:9px;font-family:'Nunito',sans-serif;font-size:12px;font-weight:600;color:var(--muted);cursor:pointer;margin-top:8px;}

.toast{background:var(--dk);color:var(--beige);padding:12px 20px;border-radius:var(--r);font-size:13px;font-weight:600;position:relative;margin-top:1rem;display:none;align-items:center;gap:8px;}
.toast.on{display:flex;}

.success-overlay{background:#EAF3DE;border:1.5px solid #97C459;border-radius:var(--r);padding:1rem 1.25rem;display:none;align-items:center;gap:10px;margin-bottom:1rem;}
.success-overlay.on{display:flex;}

.prod-item{display:flex;align-items:center;gap:10px;padding:10px;background:var(--beige);border-radius:8px;border:1px solid var(--beige2);margin-bottom:8px;}
.prod-thumb{width:44px;height:44px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.pt1{background:#EAF3DE;}.pt2{background:#E6F1FB;}.pt3{background:#FAEEDA;}
.prod-info{flex:1;}
.prod-name{font-size:12px;font-weight:700;color:var(--dk);}
.prod-price{font-size:11px;color:var(--mid);font-weight:600;}
.prod-sold{font-size:10px;color:var(--muted);}
.prod-status{font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;background:#EAF3DE;color:#27500A;}
</style>


<div class="app">

  <div class="topbar">
    <div class="topbar-logo">Upcycle<span>Match</span></div>
    <div class="topbar-nav">
      <button class="tnav on">Beranda</button>
      <button class="tnav">Peta Limbah</button>
      <button class="tnav">Klaim Kain</button>
      <button class="tnav">Upload Karya</button>
    </div>
    <div class="topbar-right">
      <div class="koin-pill"><i class="ti ti-star" aria-hidden="true"></i> 13 Koin</div>
      <button class="notif-btn" aria-label="Notifikasi"><i class="ti ti-bell"></i><span class="notif-dot"></span></button>
      <div class="avatar-pill">
        <div class="av-circle">TH</div>
        <div class="av-name">Hai, Theo</div>
        <i class="ti ti-chevron-down" style="font-size:13px;color:rgba(247,244,213,0.6);"></i>
      </div>
    </div>
  </div>

  <div class="breadcrumb">Dashboard › <span>Kontributor</span></div>

  <div class="body-area">

    <nav class="sidenav">
      <div class="snav-label">Menu Utama</div>
      <div class="snav-item on" onclick="showSec('upload')"><i class="ti ti-upload" aria-hidden="true"></i> Upload Limbah</div>
      <div class="snav-item" onclick="showSec('tracker')"><i class="ti ti-refresh" aria-hidden="true"></i> Live Tracker</div>
      <div class="snav-item" onclick="showSec('wallet')"><i class="ti ti-star" aria-hidden="true"></i> Eco-Wallet</div>
      <div class="snav-item" onclick="showSec('gallery')"><i class="ti ti-shopping-bag" aria-hidden="true"></i> Beli Produk</div>
      <div class="snav-label">Akun</div>
      <div class="snav-item" onclick="showSec('profile')"><i class="ti ti-user" aria-hidden="true"></i> Profil Saya</div>
      <div class="snav-item"><i class="ti ti-settings" aria-hidden="true"></i> Pengaturan</div>
    </nav>

    <div class="main">

      <!-- ===== SECTION: UPLOAD ===== -->
      <div class="page-sec on" id="sec-upload">
        <div class="sec-hdr">
          <div class="sec-title">Dashboard Kontributor</div>
          <div class="sec-sub">Kelola limbah kain, pantau status, dan cairkan reward koinmu</div>
        </div>

        <div class="gi-banner">
          <div class="gi-top">
            <div class="gi-icon"><i class="ti ti-leaf" style="font-size:18px;color:var(--moss);" aria-hidden="true"></i></div>
            <div>
              <div class="gi-label">Green Identity</div>
              <div class="gi-name">Theo Hermawan — Kontributor Aktif</div>
            </div>
          </div>
          <div class="gi-metrics">
            <div class="gi-m"><div class="gi-m-val">13.3</div><div class="gi-m-sub">Kg Kain Diselamatkan</div></div>
            <div class="gi-m"><div class="gi-m-val">5</div><div class="gi-m-sub">Postingan Aktif</div></div>
            <div class="gi-m"><div class="gi-m-val gold">13</div><div class="gi-m-sub">Total Koin</div></div>
            <div class="gi-m"><div class="gi-m-val">4.9 <i class="ti ti-star" style="font-size:18px;color:#E8C96A;" aria-hidden="true"></i></div><div class="gi-m-sub">Rating Kontributor</div></div>
          </div>
        </div>

        <div id="success-post" class="success-overlay">
          <i class="ti ti-circle-check" style="font-size:20px;color:#3B6D11;" aria-hidden="true"></i>
          <div><div style="font-size:13px;font-weight:700;color:#27500A;">Limbah berhasil diposting!</div><div style="font-size:11px;color:#3B6D11;margin-top:1px;">+2 Koin otomatis ditambahkan ke saldo kamu</div></div>
        </div>

        <div class="two-col">
          <div class="card">
            <div class="card-title"><i class="ti ti-package" aria-hidden="true"></i> Upload Limbah Kain</div>
            <div class="fg">
              <label class="flabel">Judul Postingan</label>
              <input class="finput" id="inp-judul" placeholder="Contoh: Kain Katun Bekas 5kg – Kondisi Baik" />
            </div>
            <div class="frow fg">
              <div>
                <label class="flabel">Jenis Bahan</label>
                <select class="fselect" id="inp-jenis">
                  <option value="">Pilih jenis</option>
                  <option>Katun</option>
                  <option>Denim</option>
                  <option>Sutra</option>
                  <option>Polyester</option>
                </select>
              </div>
              <div>
                <label class="flabel">Estimasi Berat (kg)</label>
                <input class="finput" id="inp-berat" type="number" step="0.1" placeholder="0.0 kg" />
              </div>
            </div>
            <div class="fg">
              <label class="flabel">Deskripsi Kondisi</label>
              <textarea class="ftextarea" id="inp-desc" rows="3" placeholder="Catatan kondisi kain, warna, ukuran, dll..."></textarea>
            </div>
            <div class="fg">
              <label class="flabel"><i class="ti ti-map-pin" style="font-size:13px;" aria-hidden="true"></i> Titik Lokasi Penjemputan</label>
              <div class="map-box" onclick="setMap()">
                <div class="map-box-icon"><i class="ti ti-map" aria-hidden="true"></i></div>
                <div class="map-box-txt" id="map-txt">Klik untuk set lokasi rumahmu</div>
                <div class="map-box-sub">Powered by Leaflet.js + OpenStreetMap</div>
              </div>
              <div class="coord-row">
                <input class="finput" id="inp-lat" placeholder="-7.1575°" style="font-size:12px;" />
                <input class="finput" id="inp-lng" placeholder="112.7521°" style="font-size:12px;" />
              </div>
            </div>
            <button class="post-btn" onclick="doPost()"><i class="ti ti-send" aria-hidden="true"></i> Posting Kain Saya</button>
          </div>

          <div style="display:flex;flex-direction:column;gap:1rem;">
            <div class="card">
              <div class="card-title"><i class="ti ti-refresh" aria-hidden="true"></i> Live Status Tracker</div>
              <div class="track-item">
                <div class="tdot av"></div>
                <div class="tinfo">
                  <div class="tname">Kaos Polos Katun 3kg</div>
                  <div class="tsub">Dipost 2 hari lalu · Kec. Adiwerna</div>
                </div>
                <span class="tbadge bav">Available</span>
              </div>
              <div class="track-item">
                <div class="tdot cl"></div>
                <div class="tinfo">
                  <div class="tname">Baju Denim Rusak 1.8kg</div>
                  <div class="tsub">Diklaim oleh Toko Jahit Maju</div>
                </div>
                <span class="tbadge bcl">Claimed</span>
              </div>
              <div class="track-item">
                <div class="tdot cp"></div>
                <div class="tinfo">
                  <div class="tname">Kain Sutra Sisa 0.5kg</div>
                  <div class="tsub">Sudah jadi produk · Lihat galeri</div>
                </div>
                <span class="tbadge bcp">Completed</span>
              </div>
              <div class="track-item">
                <div class="tdot av"></div>
                <div class="tinfo">
                  <div class="tname">Polyester Mix 2.1kg</div>
                  <div class="tsub">Dipost hari ini · Kec. Talang</div>
                </div>
                <span class="tbadge bav">Available</span>
              </div>
            </div>

            <div class="wallet-card">
              <div class="wc-title"><i class="ti ti-star" style="font-size:18px;color:var(--dk);" aria-hidden="true"></i> Eco-Wallet Saya</div>
              <div class="wc-big">
                <div class="wc-num" id="koin-display">13</div>
                <div class="wc-unit">Koin</div>
              </div>
              <div class="wc-rp">≈ Rp<span id="rp-display">32.500</span></div>
              <div class="wc-prog">
                <div class="wc-prog-fill" id="prog-fill"></div>
              </div>
              <div class="wc-prog-label"><span>0</span><span>Target 20 Koin</span></div>
              <div class="wc-note">+2 koin otomatis setiap upload limbah baru · 1 koin = Rp2.500</div>
              <button class="cvt-btn" onclick="showSec('wallet')"><i class="ti ti-arrow-right" aria-hidden="true"></i> Cairkan Koin</button>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== SECTION: TRACKER ===== -->
      <div class="page-sec" id="sec-tracker">
        <div class="sec-hdr">
          <div class="sec-title">Live Status Tracker</div>
          <div class="sec-sub">Pantau status siklus hidup kainmu secara real-time</div>
        </div>
        <div class="three-col">
          <div style="background:#EAF3DE;border-radius:var(--r);padding:14px;text-align:center;border:1.5px solid #97C459;">
            <div style="font-size:22px;font-weight:800;color:#27500A;">3</div>
            <div style="font-size:10px;color:#3B6D11;text-transform:uppercase;letter-spacing:0.5px;margin-top:2px;">Available</div>
          </div>
          <div style="background:#E6F1FB;border-radius:var(--r);padding:14px;text-align:center;border:1.5px solid #85B7EB;">
            <div style="font-size:22px;font-weight:800;color:#0C447C;">1</div>
            <div style="font-size:10px;color:#185FA5;text-transform:uppercase;letter-spacing:0.5px;margin-top:2px;">Claimed</div>
          </div>
          <div style="background:#FAEEDA;border-radius:var(--r);padding:14px;text-align:center;border:1.5px solid #EF9F27;">
            <div style="font-size:22px;font-weight:800;color:#633806;">1</div>
            <div style="font-size:10px;color:#854F0B;text-transform:uppercase;letter-spacing:0.5px;margin-top:2px;">Completed</div>
          </div>
        </div>
        <div class="card">
          <div class="card-title"><i class="ti ti-list" aria-hidden="true"></i> Semua Postingan Limbah</div>
          <div class="track-item"><div class="tdot av"></div><div class="tinfo"><div class="tname">Kaos Polos Katun 3kg</div><div class="tsub">Dipost 2 hari lalu · Kec. Adiwerna · -7.1523°, 109.9152°</div></div><span class="tbadge bav">Available</span></div>
          <div class="track-item"><div class="tdot cl"></div><div class="tinfo"><div class="tname">Baju Denim Rusak 1.8kg</div><div class="tsub">Diklaim oleh Toko Jahit Maju · 3 hari lalu</div></div><span class="tbadge bcl">Claimed</span></div>
          <div class="track-item"><div class="tdot cp"></div><div class="tinfo"><div class="tname">Kain Sutra Sisa 0.5kg</div><div class="tsub">Selesai diproses · Lihat produk di galeri</div></div><span class="tbadge bcp">Completed</span></div>
          <div class="track-item"><div class="tdot av"></div><div class="tinfo"><div class="tname">Polyester Mix 2.1kg</div><div class="tsub">Dipost hari ini · Kec. Talang</div></div><span class="tbadge bav">Available</span></div>
          <div class="track-item"><div class="tdot av"></div><div class="tinfo"><div class="tname">Kain Flanel Warna 0.9kg</div><div class="tsub">Dipost kemarin · Kec. Pangkah</div></div><span class="tbadge bav">Available</span></div>
        </div>
      </div>

      <!-- ===== SECTION: WALLET ===== -->
      <div class="page-sec" id="sec-wallet">
        <div class="sec-hdr">
          <div class="sec-title">Eco-Wallet & Koin</div>
          <div class="sec-sub">Kumpulkan koin dari aktivitasmu, cairkan jadi rupiah digital</div>
        </div>
        <div class="two-col">
          <div>
            <div class="wallet-card" style="margin-bottom:1rem;">
              <div class="wc-title"><i class="ti ti-star" style="font-size:18px;color:var(--dk);" aria-hidden="true"></i> Saldo Koin</div>
              <div class="wc-big">
                <div class="wc-num">13</div>
                <div class="wc-unit">Koin</div>
              </div>
              <div class="wc-rp">≈ Rp32.500</div>
              <div class="wc-prog" style="margin-top:10px;">
                <div class="wc-prog-fill"></div>
              </div>
              <div class="wc-prog-label"><span>0</span><span>Target 20 Koin</span></div>
              <div class="wc-note">Setiap upload limbah baru = +2 koin otomatis · Tidak ada minimal berat</div>
            </div>

            <div class="card">
              <div class="card-title"><i class="ti ti-history" aria-hidden="true"></i> Riwayat Koin</div>
              <div class="history-row"><div class="hist-icon"><i class="ti ti-upload" style="font-size:14px;color:var(--moss);" aria-hidden="true"></i></div><div class="hist-info"><div class="hist-name">Upload: Kaos Polos Katun 3kg</div><div class="hist-sub">2 hari lalu</div></div><div class="hist-koin">+2</div></div>
              <div class="history-row"><div class="hist-icon"><i class="ti ti-upload" style="font-size:14px;color:var(--moss);" aria-hidden="true"></i></div><div class="hist-info"><div class="hist-name">Upload: Baju Denim Rusak 1.8kg</div><div class="hist-sub">3 hari lalu</div></div><div class="hist-koin">+2</div></div>
              <div class="history-row"><div class="hist-icon"><i class="ti ti-upload" style="font-size:14px;color:var(--moss);" aria-hidden="true"></i></div><div class="hist-info"><div class="hist-name">Upload: Kain Sutra Sisa 0.5kg</div><div class="hist-sub">5 hari lalu</div></div><div class="hist-koin">+2</div></div>
              <div class="history-row"><div class="hist-icon"><i class="ti ti-upload" style="font-size:14px;color:var(--moss);" aria-hidden="true"></i></div><div class="hist-info"><div class="hist-name">Upload: Polyester Mix 2.1kg</div><div class="hist-sub">Hari ini</div></div><div class="hist-koin">+2</div></div>
              <div class="history-row"><div class="hist-icon"><i class="ti ti-upload" style="font-size:14px;color:var(--moss);" aria-hidden="true"></i></div><div class="hist-info"><div class="hist-name">Upload: Kain Flanel Warna 0.9kg</div><div class="hist-sub">Kemarin</div></div><div class="hist-koin">+2</div></div>
              <div class="history-row"><div class="hist-icon"><i class="ti ti-receipt" style="font-size:14px;color:var(--rosy);" aria-hidden="true"></i></div><div class="hist-info"><div class="hist-name">Pencairan ke BRI – xxxx3421</div><div class="hist-sub">2 minggu lalu</div></div><div style="font-size:14px;font-weight:800;color:var(--rosy);">-3</div></div>
            </div>
          </div>

          <div>
            <div class="card">
              <div class="card-title"><i class="ti ti-cash" aria-hidden="true"></i> Cairkan Koin</div>
              <div class="koin-sum">
                <div class="ks-big">13 Koin</div>
                <div class="ks-rp">Setara Rp32.500</div>
              </div>
              <div class="fg">
                <label class="flabel">Metode Pencairan</label>
                <div class="method-row">
                  <div class="m-btn on" id="mb-bank" onclick="selM('bank')"><i class="ti ti-building-bank" aria-hidden="true"></i> Transfer Bank</div>
                  <div class="m-btn" id="mb-ew" onclick="selM('ew')"><i class="ti ti-device-mobile" aria-hidden="true"></i> E-Wallet</div>
                </div>
              </div>
              <div class="fg" id="bank-field">
                <label class="flabel">Nomor Rekening</label>
                <input class="finput" placeholder="Contoh: 1234567890" />
              </div>
              <div class="fg" id="ew-field" style="display:none;">
                <label class="flabel">Nomor E-Wallet</label>
                <input class="finput" placeholder="Contoh: 08xx-xxxx-xxxx" />
              </div>
              <div class="fg">
                <label class="flabel">Nama Penerima</label>
                <input class="finput" placeholder="Sesuai nama rekening/akun" />
              </div>
              <div style="background:var(--beige2);border-radius:8px;padding:12px;font-size:12px;color:var(--muted);line-height:1.5;margin-bottom:12px;">
                <strong style="color:var(--dk);">Info:</strong> Pencairan diproses dalam 1×24 jam kerja. Minimal pencairan 4 koin (Rp10.000). Fitur ini merupakan simulasi sistem gamifikasi ekonomi sirkular.
              </div>
              <button class="post-btn" onclick="doCvt()"><i class="ti ti-arrow-right" aria-hidden="true"></i> Cairkan Rp32.500</button>
              <div class="toast on" id="cvt-toast" style="margin-top:12px;display:none;">
                <i class="ti ti-check" aria-hidden="true"></i> Pencairan berhasil diajukan! (simulasi frontend)
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== SECTION: GALLERY (BELI PRODUK) ===== -->
      <div class="page-sec" id="sec-gallery">
        <div class="sec-hdr">
          <div class="sec-title">Beli Produk Daur Ulang</div>
          <div class="sec-sub">Produk kreatif dari limbah kain hasil karya mitra penjahit UpcycleMatch</div>
        </div>
        <div class="three-col" style="margin-bottom:1rem;">
          <div class="sm-card"><div class="sm-val" style="color:var(--dk);">1,820</div><div class="sm-lbl">Produk Tersedia</div></div>
          <div class="sm-card"><div class="sm-val" style="color:var(--mid);">204</div><div class="sm-lbl">Mitra Penjahit</div></div>
          <div class="sm-card"><div class="sm-val" style="color:var(--rosy);">3</div><div class="sm-lbl">Produkku Dibeli</div></div>
        </div>
        <div class="card">
          <div class="card-title"><i class="ti ti-shopping-bag" aria-hidden="true"></i> Upcycle Gallery</div>
          <div class="prod-item">
            <div class="prod-thumb pt1"><i class="ti ti-briefcase" style="font-size:20px;color:#3B6D11;" aria-hidden="true"></i></div>
            <div class="prod-info"><div class="prod-name">Tas Perca Katun Premium</div><div class="prod-price">Rp85.000</div><div class="prod-sold">oleh Ibu Rini · Semarang · 48 terjual</div></div>
            <button style="background:var(--dk);color:var(--beige);border:none;border-radius:7px;padding:7px 14px;font-size:11px;font-weight:700;cursor:pointer;" onclick="doToast('checkout-toast','Menuju checkout...')">Beli</button>
          </div>
          <div class="prod-item">
            <div class="prod-thumb pt2"><i class="ti ti-gift" style="font-size:20px;color:#0C447C;" aria-hidden="true"></i></div>
            <div class="prod-info"><div class="prod-name">Scrunchie Set Denim (isi 3)</div><div class="prod-price">Rp35.000</div><div class="prod-sold">oleh Toko Jahit Maju · Tegal · 112 terjual</div></div>
            <button style="background:var(--dk);color:var(--beige);border:none;border-radius:7px;padding:7px 14px;font-size:11px;font-weight:700;cursor:pointer;" onclick="doToast('checkout-toast','Menuju checkout...')">Beli</button>
          </div>
          <div class="prod-item">
            <div class="prod-thumb pt3"><i class="ti ti-shirt" style="font-size:20px;color:#633806;" aria-hidden="true"></i></div>
            <div class="prod-info"><div class="prod-name">Keset Patchwork Warna-warni</div><div class="prod-price">Rp55.000</div><div class="prod-sold">oleh Rumah Kain Nusantara · 73 terjual</div></div>
            <button style="background:var(--dk);color:var(--beige);border:none;border-radius:7px;padding:7px 14px;font-size:11px;font-weight:700;cursor:pointer;" onclick="doToast('checkout-toast','Menuju checkout...')">Beli</button>
          </div>
          <div class="prod-item">
            <div class="prod-thumb pt1"><i class="ti ti-heart" style="font-size:20px;color:#3B6D11;" aria-hidden="true"></i></div>
            <div class="prod-info"><div class="prod-name">Dompet Sutra Mini Eksklusif</div><div class="prod-price">Rp120.000</div><div class="prod-sold">oleh Atelier Kain Asri · 19 terjual</div></div>
            <button style="background:var(--dk);color:var(--beige);border:none;border-radius:7px;padding:7px 14px;font-size:11px;font-weight:700;cursor:pointer;" onclick="doToast('checkout-toast','Menuju checkout...')">Beli</button>
          </div>
          <div id="checkout-toast" class="toast" style="margin-top:4px;">
            <i class="ti ti-shopping-cart" aria-hidden="true"></i> Produk ditambahkan ke keranjang!
          </div>
        </div>
      </div>

      <!-- ===== SECTION: PROFILE ===== -->
      <div class="page-sec" id="sec-profile">
        <div class="sec-hdr">
          <div class="sec-title">Profil Saya</div>
          <div class="sec-sub">Informasi akun dan rekam jejak lingkunganmu</div>
        </div>
        <div class="two-col">
          <div class="card">
            <div class="card-title"><i class="ti ti-user" aria-hidden="true"></i> Data Diri</div>
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:1px solid var(--beige2);">
              <div style="width:56px;height:56px;border-radius:50%;background:var(--rosy);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#fff;">TH</div>
              <div><div style="font-size:16px;font-weight:700;color:var(--dk);">Theo Hermawan</div><div style="font-size:12px;color:var(--muted);">Kontributor Aktif · Bergabung Mar 2025</div></div>
            </div>
            <div class="fg"><label class="flabel">Email</label><input class="finput" value="theo.hermawan@gmail.com" /></div>
            <div class="fg"><label class="flabel">Nomor WhatsApp</label><input class="finput" value="0812-3456-7890" /></div>
            <div class="fg"><label class="flabel">Kota / Kecamatan</label><input class="finput" value="Kec. Adiwerna, Kab. Tegal" /></div>
            <button class="post-btn" onclick="doToast('profile-toast','Profil berhasil disimpan!')"><i class="ti ti-device-floppy" aria-hidden="true"></i> Simpan Perubahan</button>
            <div id="profile-toast" class="toast" style="margin-top:8px;"><i class="ti ti-check" aria-hidden="true"></i> Profil berhasil disimpan!</div>
          </div>
          <div style="display:flex;flex-direction:column;gap:1rem;">
            <div class="gi-banner">
              <div class="gi-top"><div class="gi-icon"><i class="ti ti-leaf" style="font-size:18px;color:var(--moss);" aria-hidden="true"></i></div><div><div class="gi-label">Rekam Jejak Lingkungan</div><div class="gi-name">Green Identity Score</div></div></div>
              <div class="gi-metrics" style="grid-template-columns:1fr 1fr;">
                <div class="gi-m"><div class="gi-m-val">13.3</div><div class="gi-m-sub">Kg Diselamatkan</div></div>
                <div class="gi-m"><div class="gi-m-val">5</div><div class="gi-m-sub">Postingan Total</div></div>
                <div class="gi-m"><div class="gi-m-val gold">13</div><div class="gi-m-sub">Total Koin</div></div>
                <div class="gi-m"><div class="gi-m-val">4.9</div><div class="gi-m-sub">Rating</div></div>
              </div>
            </div>
            <div class="card">
              <div class="card-title"><i class="ti ti-shield-check" aria-hidden="true"></i> Status Akun</div>
              <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--beige2);">
                <i class="ti ti-circle-check" style="font-size:18px;color:#52a87c;" aria-hidden="true"></i>
                <div><div style="font-size:13px;font-weight:700;color:var(--dk);">Akun Terverifikasi</div><div style="font-size:11px;color:var(--muted);">Diverifikasi oleh Admin UpcycleMatch</div></div>
              </div>
              <div style="display:flex;align-items:center;gap:10px;padding:10px 0;">
                <i class="ti ti-map-pin" style="font-size:18px;color:var(--mid);" aria-hidden="true"></i>
                <div><div style="font-size:13px;font-weight:700;color:var(--dk);">Peran: Kontributor</div><div style="font-size:11px;color:var(--muted);">Dapat upload limbah & beli produk</div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
var koin = 13;

function showSec(id) {
  document.querySelectorAll('.page-sec').forEach(function(el){el.classList.remove('on');});
  document.querySelectorAll('.snav-item').forEach(function(el){el.classList.remove('on');});
  document.getElementById('sec-'+id).classList.add('on');
  var map = {upload:0,tracker:1,wallet:2,gallery:3,profile:4};
  if(map[id]!==undefined){
    document.querySelectorAll('.snav-item')[map[id]].classList.add('on');
  }
}

function setMap() {
  document.getElementById('map-txt').textContent = 'Lokasi dipilih: Kec. Adiwerna, Tegal';
  document.getElementById('inp-lat').value = '-7.1575';
  document.getElementById('inp-lng').value = '109.9152';
}

function doPost() {
  var judul = document.getElementById('inp-judul').value;
  var berat = document.getElementById('inp-berat').value;
  var jenis = document.getElementById('inp-jenis').value;
  if(!judul || !berat || !jenis) {
    document.getElementById('inp-judul').style.borderColor = '#E24B4A';
    setTimeout(function(){document.getElementById('inp-judul').style.borderColor='';},2000);
    return;
  }
  koin += 2;
  document.getElementById('koin-display').textContent = koin;
  var rp = (koin * 2500).toLocaleString('id-ID');
  document.getElementById('rp-display').textContent = rp;
  var prog = Math.min(Math.round(koin/20*100), 100);
  document.getElementById('prog-fill').style.width = prog+'%';
  document.querySelector('.koin-pill').innerHTML = '<i class="ti ti-star" aria-hidden="true"></i> '+koin+' Koin';
  var so = document.getElementById('success-post');
  so.classList.add('on');
  document.getElementById('inp-judul').value='';
  document.getElementById('inp-berat').value='';
  document.getElementById('inp-desc').value='';
  setTimeout(function(){so.classList.remove('on');},4000);
}

function selM(m) {
  document.getElementById('mb-bank').classList.toggle('on', m==='bank');
  document.getElementById('mb-ew').classList.toggle('on', m==='ew');
  document.getElementById('bank-field').style.display = m==='bank'?'block':'none';
  document.getElementById('ew-field').style.display = m==='ew'?'block':'none';
}

function doCvt() {
  var t = document.getElementById('cvt-toast');
  t.style.display='flex';
  setTimeout(function(){t.style.display='none';},3500);
}

function doToast(id, msg) {
  var t = document.getElementById(id);
  if(msg) t.innerHTML = '<i class="ti ti-check" aria-hidden="true"></i> ' + msg;
  t.classList.add('on');
  setTimeout(function(){t.classList.remove('on');},3000);
}
</script>
