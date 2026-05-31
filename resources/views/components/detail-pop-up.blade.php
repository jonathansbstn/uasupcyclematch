@extends('layouts.app')
@section('title', 'Admin - Detail Alur Limbah')

@section('content')
<style>
/* 🛠️ RESET GLOBAL LAYOUT: Mengunci background dan menghilangkan padding bocor dari template bawaan */
body, .container, .wrapper, .main-content, #app, .content-wrapper {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
    background: #F7F4D5 !important;
}

@import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap');
* { box-sizing: border-box; margin: 0; padding: 0; }
.w { background: #F7F4D5; min-height: 920px; font-family: 'DM Sans', sans-serif; width: 100%; }
.nav { display: flex; align-items: center; justify-content: space-between; padding: 0 32px; height: 56px; background: #0A3323; }
.logo { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: #F7F4D5; }
.logo span { color: #839958; }
.layout { display: grid; grid-template-columns: 200px 1fr; min-height: 864px; }
.sidebar { background: #051A13; padding: 20px 0; }
.sitem { display: flex; align-items: center; gap: 10px; padding: 11px 20px; font-size: 13px; color: #9FE1CB; cursor: pointer; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all 0.2s; }
.sitem:hover { background: rgba(257,244,213,0.05); color: #F7F4D5; }
.sitem.a { background: rgba(131,153,88,0.15); color: #F7F4D5; border-right: 3px solid #839958; }
.main { padding: 28px; }

.pglbl { background: #D3968C; color: #4B1528; font-family: 'Syne', sans-serif; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 100px; letter-spacing: 1px; display: inline-block; margin-bottom: 20px; }
.row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.card { background: #fff; border-radius: 18px; border: 1.5px solid #C0DD97; overflow: hidden; }
.card-head { background: #0A3323; padding: 16px 20px; }
.cht { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 800; color: #F7F4D5; }
.chs { font-size: 12px; color: #9FE1CB; margin-top: 2px; }
.card-body { padding: 20px; }
.field-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #EAF3DE; }
.field-row:last-child { border-bottom: none; }
.flbl { font-size: 12px; color: #3B6D11; font-weight: 500; }
.fval { font-size: 13px; color: #0A3323; font-weight: 500; text-align: right; max-width: 240px; word-wrap: break-word; }

/* Status Badges */
.badge-s { font-size: 11px; padding: 4px 10px; border-radius: 100px; font-family: 'DM Sans', sans-serif; font-weight: 600; text-transform: capitalize; }
.bs-p { background: #FFF3CD; color: #856404; } /* Pending / Processing */
.bs-a { background: #E1F5EE; color: #0F6E56; } /* Active / Approved */
.bs-co { background: #EAF3DE; color: #3B6D11; } /* Completed */
.bs-d { background: #FBEAF0; color: #993556; } /* Cancelled / Deleted */

.mapbox { background: #105666; border-radius: 14px; height: 130px; display: flex; flex-direction: column; align-items: center; justify-content: center; margin-bottom: 14px; position: relative; overflow: hidden; }
.mapgrid { position: absolute; inset: 0; background-image: repeating-linear-gradient(0deg,rgba(255,255,255,0.04) 0px,rgba(255,255,255,0.04) 1px,transparent 1px,transparent 40px),repeating-linear-gradient(90deg,rgba(255,255,255,0.04) 0px,rgba(255,255,255,0.04) 1px,transparent 1px,transparent 40px); }
.mpin { font-size: 24px; z-index: 1; animation: bounce 2s infinite; }
@keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
.mcoord { font-family: 'DM Sans', sans-serif; font-size: 11px; color: #9FE1CB; margin-top: 4px; z-index: 1; }

.activity-list { display: flex; flex-direction: column; gap: 0; }
.act-item { display: flex; gap: 12px; padding: 11px 0; border-bottom: 1px solid #EAF3DE; }
.act-item:last-child { border-bottom: none; }
.act-dot-wrap { display: flex; flex-direction: column; align-items: center; gap: 0; }
.act-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
.act-line { width: 2px; flex: 1; background: #EAF3DE; margin-top: 2px; }
.act-content { flex: 1; }
.act-title { font-size: 13px; color: #0A3323; font-weight: 500; }
.act-time { font-size: 11px; color: #3B6D11; margin-top: 2px; }
.ad-g { background: #639922; }
.ad-t { background: #105666; }
.ad-p { background: #D3968C; }

.btn-row { display: flex; gap: 10px; margin-top: 20px; justify-content: flex-end; }
.b-main { background: #0A3323; color: #F7F4D5; border: none; border-radius: 100px; padding: 10px 20px; font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; transition: background 0.2s; }
.b-main:hover { background: #124d35; }
.b-wa { background: transparent; color: #0F6E56; border: 1.5px solid #C0DD97; border-radius: 100px; padding: 9px 20px; font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
.b-wa:hover { background: rgba(15,110,86,0.05); }
.b-del { background: transparent; color: #993556; border: 1.5px solid #F4C0D1; border-radius: 100px; padding: 9px 16px; font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
.b-del:hover { background: rgba(153,53,86,0.05); }

.upcycler-card { background: #EAF3DE; border-radius: 14px; padding: 14px 16px; display: flex; align-items: center; gap: 12px; margin-top: 6px; }
.uav { width: 40px; height: 40px; border-radius: 50%; background: #0A3323; display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: #F7F4D5; flex-shrink: 0; text-transform: uppercase; }
.uname { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: #0A3323; }
.umeta { font-size: 12px; color: #3B6D11; margin-top: 2px; }
.uwabtn { margin-left: auto; background: #0A3323; color: #F7F4D5; border: none; border-radius: 100px; padding: 7px 14px; font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700; cursor: pointer; white-space:nowrap; text-decoration: none; display: inline-block; transition: background 0.2s; }
.uwabtn:hover { background: #124d35; }
</style>

@php
    // Fallback data agar jika variabel $textile dari Controller kosong saat testing, sistem tidak error melaingkan menampilkan mockup data terintegrasi.
    $item = $textile ?? (object)[
        'id' => 2,
        'title' => 'Celana Denim Rusak 5 pcs',
        'created_at' => \Carbon\Carbon::parse('2025-06-14'),
        'status' => 'processing',
        'fabric_type' => 'Denim 👖',
        'weight' => 6.0,
        'description' => '5 celana denim berbagai ukuran, ada yang sobek di lutut dan pinggang. Kondisi masih tebal dan layak upcycle untuk dijadikan tas ransel atau dompet ekologis.',
        'contributor_name' => 'Budi Santoso',
        'contributor_phone' => '0812-3456-7890',
        'latitude' => '-7.2651',
        'longitude' => '112.7527',
        'address' => 'Jl. Raya Gubeng No. 45, Kel. Gubeng, Surabaya',
        'district' => 'Gubeng',
        'city' => 'Surabaya, Jawa Timur',
        'has_claim' => true,
        'tailor_name' => 'Rajin Jahit Studio',
        'tailor_initials' => 'RJ',
        'tailor_rating' => '4.9',
        'claim_date' => '11 Juni 2025, 14:23',
        'target_date' => '18 Juni 2025'
    ];

    // Rumus hitung modal hemat otomatis: Berat dikali perkiraan harga substitusi material mentah konvensional
    $modalHemat = $item->weight * 15000;
@endphp

<div class="w">
  <nav class="nav" style="margin-bottom: 24px;">
    <div class="logo">Upcycle<span>Match</span></div>
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="background:#D3968C;color:#4B1528;font-family:'Syne',sans-serif;font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;">Admin Panel</div>
      <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9FE1CB;">{{ auth()->user()->name ?? 'Ahmad Fauzi' }}</span>
      <div class="pglbl" style="margin-bottom:0;">PAGE 4D — DETAIL LIMBAH</div>
    </div>
  </nav>

  <div class="layout" style="grid-template-columns: 1fr; min-height: auto;">
    <div class="main" style="padding: 0 20px;">
      
      <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.data-limbah') }}" style="text-decoration: none; color: #0A3323; font-size: 13px; font-weight: 500;">← Kembali ke Data Limbah</a>
      </div>

      <div class="row2">
        <div>
          <div class="card" style="margin-bottom:16px;">
            <div class="card-head">
              <div class="cht">{{ $item->title }}</div>
              <div class="chs">Post ID: #LMB-00{{ $item->id }} · Diposting {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</div>
            </div>
            <div class="card-body">
              <div class="field-row">
                <span class="flbl">Status</span>
                <span class="badge-s {{ $item->status == 'completed' ? 'bs-co' : ($item->status == 'processing' ? 'bs-p' : 'bs-a') }}">
                    {{ $item->status }}
                </span>
              </div>
              <div class="field-row"><span class="flbl">Jenis Bahan</span><span class="fval">{{ $item->fabric_type }}</span></div>
              <div class="field-row"><span class="flbl">Estimasi Berat</span><span class="fval" style="font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:#0A3323;">{{ number_format($item->weight, 1) }} kg</span></div>
              <div class="field-row"><span class="flbl">Deskripsi</span><span class="fval" style="font-size:12px;color:#3B6D11;line-height:1.5;">{{ $item->description }}</span></div>
              <div class="field-row"><span class="flbl">Kontributor</span><span class="fval">{{ $item->contributor_name }}</span></div>
              <div class="field-row"><span class="flbl">No. HP</span><span class="fval" style="color:#0F6E56;">{{ $item->contributor_phone }}</span></div>
              <div class="field-row"><span class="flbl">Estimasi Modal Hemat (SDG)</span><span class="fval" style="color:#3B6D11;font-weight:700;">Rp {{ number_format($modalHemat, 0, ',', '.') }}</span></div>
            </div>
          </div>

          <div class="card">
            <div class="card-head">
              <div class="cht">Lokasi Penjemputan</div>
              <div class="chs">Koordinat GPS terekam lewat gawai kontributor</div>
            </div>
            <div class="card-body">
              <div class="mapbox">
                <div class="mapgrid"></div>
                <div class="mpin">📍</div>
                <div class="mcoord">{{ $item->latitude }}° S, {{ $item->longitude }}° E</div>
              </div>
              <div class="field-row"><span class="flbl">Alamat</span><span class="fval">{{ $item->address }}</span></div>
              <div class="field-row"><span class="flbl">Kecamatan</span><span class="fval">{{ $item->district }}</span></div>
              <div class="field-row"><span class="flbl">Kota / Provinsi</span><span class="fval">{{ $item->city }}</span></div>
            </div>
          </div>
        </div>

        <div>
          <div class="card" style="margin-bottom:16px;">
            <div class="card-head">
              <div class="cht">Penjahit yang Mengklaim</div>
              <div class="chs">Terbuka otomatis dalam ekosistem sirkular</div>
            </div>
            <div class="card-body">
              @if($item->has_claim)
                <div class="upcycler-card">
                  <div class="uav">{{ $item->tailor_initials }}</div>
                  <div>
                    <div class="uname">{{ $item->tailor_name }}</div>
                    <div class="umeta">✂️ Penjahit Terverifikasi · Rating {{ $item->tailor_rating }}</div>
                  </div>
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->contributor_phone) }}" target="_blank" class="uwabtn">💬 WA Sekarang</a>
                </div>
                <div style="margin-top:16px;">
                  <div class="field-row"><span class="flbl">Status Klaim</span><span class="badge-s bs-p">{{ $item->status }}</span></div>
                  <div class="field-row"><span class="flbl">Tanggal Klaim</span><span class="fval">{{ $item->claim_date }}</span></div>
                  <div class="field-row"><span class="flbl">Target Selesai</span><span class="fval" style="color:#993556; font-weight: 600;">{{ $item->target_date }}</span></div>
                </div>
              @else
                <div style="text-align: center; padding: 24px 0; color: #3B6D11; font-size: 13px;">
                  🚫 Belum ada penjahit yang mengambil limbah ini.
                </div>
              @endif
            </div>
          </div>

          <div class="card">
            <div class="card-head">
              <div class="cht">Riwayat Aktivitas & Log Sistem</div>
              <div class="chs">Audit trail otomatis untuk transparansi data karya</div>
            </div>
            <div class="card-body">
              <div class="activity-list">
                <div class="act-item">
                  <div class="act-dot-wrap"><div class="act-dot ad-t"></div><div class="act-line"></div></div>
                  <div class="act-content"><div class="act-title">Status material diperbarui menjadi: <b>{{ $item->status }}</b></div><div class="act-time">Oleh Mitra: {{ $item->tailor_name }}</div></div>
                </div>
                <div class="act-item">
                  <div class="act-dot-wrap"><div class="act-dot ad-t"></div><div class="act-line"></div></div>
                  <div class="act-content"><div class="act-title">Klaim berhasil divalidasi oleh sistem</div><div class="act-time">Notifikasi sirkular otomatis aktif</div></div>
                </div>
                <div class="act-item">
                  <div class="act-dot-wrap"><div class="act-dot ad-g"></div><div class="act-line"></div></div>
                  <div class="act-content"><div class="act-title">Postingan lolos kurasi Admin</div><div class="act-time">Oleh Admin Panel UpcycleMatch</div></div>
                </div>
                <div class="act-item">
                  <div class="act-dot-wrap"><div class="act-dot ad-g"></div></div>
                  <div class="act-content"><div class="act-title">Limbah berhasil diunggah oleh {{ $item->contributor_name }}</div><div class="act-time">Data awal masuk ke database</div></div>
                </div>
              </div>

              <div class="btn-row">
                <button class="b-del" onclick="handleAction('hapus')">Hapus Post</button>
                <button class="b-wa" onclick="handleAction('edit')">Ubah Status</button>
                <a href="https://maps.google.com/?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="b-main">Buka Google Maps →</a>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>

<script>
function handleAction(type) {
    if(type === 'hapus') {
        if(confirm('Apakah Anda yakin ingin menghapus data logistik kain ini dari sistem kurasi admin?')) {
            alert('Aksi disimulasikan: Data berhasil diamankan.');
        }
    } else if(type === 'edit') {
        alert('Fitur pengubahan status sirkulasi material dialihkan menuju panel logistik utama.');
    }
}
</script>
@endsection