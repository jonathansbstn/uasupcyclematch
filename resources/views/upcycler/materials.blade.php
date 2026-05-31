@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght=700;800&family=DM+Sans:wght=400;500&display=swap');
    .page-bg { background: #F7F4D5; min-height: 100vh; padding: 40px 20px; font-family: 'DM Sans', sans-serif; text-align: center; }
    .container { max-width: 900px; margin: 0 auto; }
    .page-title { font-family: 'Syne', sans-serif; font-size: 28px; color: #0A3323; margin-bottom: 8px; font-weight: 800; }
    .page-sub { color: #3B6D11; font-size: 14px; margin-bottom: 30px; }
    .grid-kain { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; text-align: left; }
    .card-kain { background: #fff; border: 1.5px solid #C0DD97; padding: 20px; border-radius: 16px; box-sizing: border-box; }
    .card-title { font-family: 'Syne', sans-serif; font-size: 18px; color: #0A3323; margin-bottom: 10px; }
    .card-detail { font-size: 13px; color: #3B6D11; margin-bottom: 6px; }
    .btn-claim { display: block; width: 100%; text-align: center; background: #0A3323; color: #F7F4D5; border: none; padding: 10px; border-radius: 100px; font-family: 'Syne', sans-serif; font-size: 12px; font-weight: 700; cursor: pointer; margin-top: 15px; text-decoration: none; }
</style>

<div class="page-bg">
    <div class="container">
        <div class="page-title">Bahan Baku Tersedia ✂️</div>
        <div class="page-sub">Ambil limbah kain perca dari kontributor untuk diolah menjadi produk bernilai tinggi</div>

        <div class="grid-kain">
            @forelse($daftarLimbah as $limbah)
                <div class="card-kain">
                    @if($limbah->foto)
                        <img src="{{ asset($limbah->foto) }}" style="width:100%; height:150px; object-fit:cover; border-radius:10px; margin-bottom:12px; border: 1px solid #C0DD97;">
                    @endif
                    <div class="card-title">{{ $limbah->judul }}</div>
                    <div class="card-detail"><strong>Bahan:</strong> {{ $limbah->bahan }}</div>
                    <div class="card-detail"><strong>Berat:</strong> {{ $limbah->berat }} Kg</div>
                    <div class="card-detail"><strong>Deskripsi:</strong> {{ Str::limit($limbah->deskripsi, 60) }}</div>
                    
                    <a href="#" class="btn-claim" onclick="alert('Kain berhasil diklaim! Silakan koordinasi via WhatsApp kontributor.')">Klaim Bahan Baku</a>
                </div>
            @empty
                <div style="grid-column: 1/-1; background: #fff; padding: 30px; border-radius: 16px; border: 1.5px dashed #C0DD97; color: #3B6D11;">
                    📭 Belum ada limbah kain yang diposting oleh kontributor.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection