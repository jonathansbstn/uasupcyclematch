@extends('layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upcycle Gallery - UpcycleMatch</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-green-900 text-white p-4 font-bold shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
            <a href="{{ route('home') }}">⬅️ Kembali ke Beranda</a>
            <span>Upcycle Gallery Showcase 🎨</span>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-800">Inspirasi Transformasi Karya Bernilai Tinggi</h1>
            <p class="text-gray-500 text-sm mt-2">Bukti nyata hasil karya UMKM Penjahit lokal memanfaatkan 100% kain perca buangan masyarakat.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <div>
                        <span class="text-xs font-bold text-red-500">🗑️ SEBELUM</span>
                        <p class="font-bold text-gray-700 text-sm">5 Pcs Celana Jeans Rusak</p>
                    </div>
                    <span class="text-xl">➡️</span>
                    <div class="text-right">
                        <span class="text-xs font-bold text-emerald-500">✨ SESUDAH</span>
                        <p class="font-bold text-gray-700 text-sm">3 Tas Ransel Denim Premium</p>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Oleh: Taylor Jahit Kreatif</span>
                    <span class="text-green-600 font-bold">♻️ 3.5 Kg Diselamatkan</span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
@endsection