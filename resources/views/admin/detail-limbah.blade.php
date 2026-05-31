<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Detail Alur Limbah</title>
    <style>
        /* 🔥 PEMBASMI PUTIH-PUTIH PINGGIR LAYAR */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: #F7F4D5;
            overflow-x: hidden;
        }

        @import url('https://fonts.googleapis.com/css2?family=Syne:wght=700;800&family=DM+Sans:wght=300;400;500&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        .w { background: #F7F4D5; min-height: 100vh; font-family: 'DM Sans', sans-serif; width: 100%; }
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 0 32px; height: 56px; background: #0A3323; }
        .logo { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: #F7F4D5; }
        .logo span { color: #839958; }
        .layout { display: grid; grid-template-columns: 200px 1fr; min-height: calc(100vh - 56px); }
        .sidebar { background: #051A13; padding: 20px 0; }
        .sitem { display: flex; align-items: center; gap: 10px; padding: 11px 20px; font-size: 13px; color: #9FE1CB; cursor: pointer; font-family: 'DM Sans', sans-serif; text-decoration: none; }
        .sitem.a { background: rgba(131,153,88,0.15); color: #F7F4D5; border-right: 3px solid #839958; }
        .main { padding: 28px; background: #F7F4D5; }
        
        .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .card { background: #fff; border-radius: 18px; border: 1.5px solid #C0DD97; overflow: hidden; margin-bottom: 16px; }
        .card-head { background: #0A3323; padding: 16px 20px; }
        .cht { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 800; color: #F7F4D5; }
        .chs { font-size: 12px; color: #9FE1CB; margin-top: 2px; }
        .card-body { padding: 20px; }
        .field-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #EAF3DE; }
        .field-row:last-child { border-bottom: none; }
        .flbl { font-size: 12px; color: #3B6D11; font-weight: 500; }
        .fval { font-size: 13px; color: #0A3323; font-weight: 500; text-align: right; }
    </style>
</head>
<body>

@php
    // Fallback data agar tidak error jika id database tidak terbaca sewaktu demo offline
    $item = $textile ?? (object)[
        'id' => 2,
        'title' => 'Celana Denim Rusak 5 pcs',
        'status' => 'processing',
        'fabric_type' => 'Denim 👖',
        'weight' => 6.0,
        'description' => '5 celana denim berbagai ukuran. Kondisi masih tebal dan layak upcycle.',
        'contributor_name' => 'Budi Santoso'
    ];
@endphp

<div class="w">
  <nav class="nav">
    <div class="logo">Upcycle<span>Match</span></div>
    <div style="background:#D3968C;color:#4B1528;font-family:'Syne',sans-serif;font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;">Admin Panel</div>
  </nav>

  <div class="layout">
    <div class="sidebar">
        <a href="{{ route('admin.data-limbah') }}" class="sitem a"><span style="width:18px;">🗃</span>Data Limbah</a>
        <a href="{{ route('admin.export') }}" class="sitem"><span style="width:18px;">📥</span>Export Report</a>
    </div>

    <div class="main">
      <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.data-limbah') }}" style="text-decoration: none; color: #0A3323; font-weight: bold;">← Kembali ke Tabel</a>
      </div>

      <div class="row2">
        <div class="card">
          <div class="card-head">
            <div class="cht">{{ $item->title }}</div>
            <div class="chs">Post ID: #LMB-00{{ $item->id }}</div>
          </div>
          <div class="card-body">
            <div class="field-row"><span class="flbl">Status</span><span class="fval">{{ $item->status }}</span></div>
            <div class="field-row"><span class="flbl">Jenis Bahan</span><span class="fval">{{ $item->fabric_type }}</span></div>
            <div class="field-row"><span class="flbl">Estimasi Berat</span><span class="fval">{{ $item->weight }} kg</span></div>
            <div class="field-row"><span class="flbl">Deskripsi</span><span class="fval">{{ $item->description }}</span></div>
            <div class="field-row"><span class="flbl">Kontributor</span><span class="fval">{{ $item->contributor_name }}</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>