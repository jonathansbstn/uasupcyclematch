<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0A3323; }
h1 { font-size: 20px; font-weight: bold; margin-bottom: 4px; }
.header { background: #0A3323; color: #F7F4D5; padding: 16px 20px; margin-bottom: 20px; }
.header h1 { color: #F7F4D5; margin: 0; }
.header p { color: #9FE1CB; font-size: 11px; margin: 4px 0 0; }
.summary { display: flex; gap: 12px; margin-bottom: 20px; }
.sum-box { border: 1.5px solid #0A3323; border-radius: 8px; padding: 12px 16px; flex: 1; text-align: center; }
.sum-val { font-size: 18px; font-weight: bold; color: #0A3323; }
.sum-lbl { font-size: 10px; color: #666; }
table { width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 11px; }
th { background: #0A3323; color: #F7F4D5; padding: 8px 10px; text-align: left; }
td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
tr:nth-child(even) td { background: #f9fafb; }
h2 { font-size: 15px; font-weight: bold; margin-bottom: 12px; border-bottom: 2px solid #839958; padding-bottom: 6px; }
.footer { text-align: center; font-size: 10px; color: #888; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 12px; }
</style>
</head>
<body>

<div class="header">
    <h1>📊 Laporan UpcycleMatch</h1>
    <p>Digenerate pada: {{ now()->isoFormat('dddd, D MMMM Y HH:mm') }}</p>
</div>

<h2>Ringkasan Dampak</h2>
<table>
    <tr>
        <th>Metrik</th><th>Nilai</th>
    </tr>
    <tr><td>Total Limbah Kain</td><td>{{ $summary['total_limbah'] }} item</td></tr>
    <tr><td>Total Produk Upcycle</td><td>{{ $summary['total_produk'] }} produk</td></tr>
    <tr><td>Total UMKM / Upcycler</td><td>{{ $summary['total_umkm'] }} UMKM</td></tr>
    <tr><td>Total Berat Diselamatkan</td><td>{{ number_format($summary['total_berat'],1) }} kg</td></tr>
    <tr><td>Total Penghematan Material</td><td>Rp {{ number_format($summary['total_penghematan'],0,',','.') }}</td></tr>
</table>

<h2>Riwayat Limbah Kain (100 Terakhir)</h2>
<table>
    <thead>
        <tr><th>Judul</th><th>Kontributor</th><th>Bahan</th><th>Berat</th><th>Status</th><th>Tanggal</th></tr>
    </thead>
    <tbody>
    @foreach($textiles as $t)
    <tr>
        <td>{{ Str::limit($t->title, 35) }}</td>
        <td>{{ $t->owner?->name ?? '—' }}</td>
        <td>{{ ucfirst($t->fabric_type ?? '—') }}</td>
        <td>{{ $t->weight }} kg</td>
        <td>{{ ucfirst($t->status) }}</td>
        <td>{{ $t->created_at->format('d/m/Y') }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<h2>Riwayat Produk Upcycle (100 Terakhir)</h2>
<table>
    <thead>
        <tr><th>Nama Produk</th><th>UMKM</th><th>Limbah Asal</th><th>Harga</th><th>Tanggal</th></tr>
    </thead>
    <tbody>
    @foreach($products as $p)
    <tr>
        <td>{{ $p->product_name }}</td>
        <td>{{ $p->upcycler?->name ?? '—' }}</td>
        <td>{{ $p->textile?->title ?? '—' }}</td>
        <td>Rp {{ number_format($p->price,0,',','.') }}</td>
        <td>{{ $p->created_at->format('d/m/Y') }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    UpcycleMatch — Platform Circular Economy Tekstil Indonesia &copy; {{ date('Y') }}
</div>
</body>
</html>
