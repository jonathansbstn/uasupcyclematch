<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Limbah UpcycleMatch</title>
    <style>
        /* 🔥 PEMBASMI PUTIH-PUTIH PINGGIR LAYAR: Memaksa halaman full screen 100% */
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
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 0 32px; height: 56px; background: #0A3323; width: 100%; }
        .logo { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; color: #F7F4D5; }
        .logo span { color: #839958; }
        .layout { display: grid; grid-template-columns: 200px 1fr; min-height: calc(100vh - 56px); width: 100%; }
        .sidebar { background: #051A13; padding: 20px 0; }
        
        /* Link navigasi aktif dan fungsional */
        .sitem { display: flex; align-items: center; gap: 10px; padding: 11px 20px; font-size: 13px; color: #9FE1CB; cursor: pointer; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all 0.2s; }
        .sitem:hover { background: rgba(257,244,213,0.05); color: #F7F4D5; }
        .sitem.a { background: rgba(131,153,88,0.15); color: #F7F4D5; border-right: 3px solid #839958; }
        
        .main { padding: 28px; background: #F7F4D5; }
        .ptitle { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: #0A3323; }
        .psub { font-size: 13px; color: #3B6D11; margin-top: 3px; margin-bottom: 24px; }
        
        /* Desain Tabel Database UI Baru */
        .table-card { background: #fff; border-radius: 18px; border: 1.5px solid #C0DD97; overflow: hidden; padding: 10px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #0A3323; color: #F7F4D5; font-family: 'Syne', sans-serif; font-size: 13px; padding: 12px; }
        td { padding: 14px 12px; border-bottom: 1px solid #EAF3DE; color: #0A3323; font-size: 13px; }
        tr:last-child td { border-bottom: none; }
        
        .badge { padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; text-transform: capitalize; }
        .bg-p { background: #FFF3CD; color: #856404; }
        
        .b-main { background: #0A3323; color: #F7F4D5; border: none; border-radius: 100px; padding: 6px 14px; font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-block; }
        .b-main:hover { background: #124d35; }
        .pglbl { background: #D3968C; color: #4B1528; font-family: 'Syne', sans-serif; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 100px; letter-spacing: 1px; }
    </style>
</head>
<body>

<div class="w">
  <nav class="nav">
    <div class="logo">Upcycle<span>Match</span></div>
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="background:#D3968C;color:#4B1528;font-family:'Syne',sans-serif;font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;">Admin Panel</div>
      <span style="font-family:'DM Sans',sans-serif;font-size:13px;color:#9FE1CB;">{{ auth()->user()->name ?? 'Ahmad Fauzi' }}</span>
      <div class="pglbl">PAGE 4D — DATA LIMBAH</div>
    </div>
  </nav>

  <div class="layout">
    <div class="sidebar">
      <div style="padding:20px 0;">
        <a href="#" class="sitem"><span style="width:18px;">📊</span>Impact Analytics</a>
        <a href="#" class="sitem"><span style="width:18px;">👥</span>Verifikasi User</a>
        <a href="{{ route('admin.data-limbah') }}" class="sitem a"><span style="width:18px;">🗃</span>Data Limbah</a>
        <a href="#" class="sitem"><span style="width:18px;">🖼</span>Galeri Karya</a>
        <a href="{{ route('admin.export') }}" class="sitem"><span style="width:18px;">📥</span>Export Report</a>
      </div>
    </div>

    <div class="main">
      <div class="ptitle">Manajemen Data Limbah Kain 🗃</div>
      <div class="psub">Daftar material sirkular masuk dari para kontributor ekosistem UpcycleMatch</div>

      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Material</th>
              <th>Berat</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($textiles as $t)
            <tr>
              <td>#LMB-00{{ $t->id }}</td>
              <td><strong>{{ $t->title ?? $t->nama_limbah }}</strong></td>
              <td>{{ $t->weight ?? $t->berat }} kg</td>
              <td><span class="badge bg-p">{{ $t->status }}</span></td>
              <td>
                <a href="{{ route('admin.detail-limbah', $t->id) }}" class="b-main">
                  Lihat Detail →
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td>#LMB-002</td>
              <td>Celana Denim Rusak 5 pcs</td>
              <td>6.0 kg</td>
              <td><span class="badge bg-p">processing</span></td>
              <td><a href="{{ route('admin.detail-limbah', 2) }}" class="b-main">Lihat Detail →</a></td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

</body>
</html>