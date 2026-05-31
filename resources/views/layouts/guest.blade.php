@extends('layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autentikasi - UpcycleMatch</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.15); }
    </style>
</head>
<body class="bg-gradient-to-br from-green-950 via-green-900 to-neutral-950 min-h-screen flex flex-col justify-center items-center p-4">
    <div class="w-full sm:max-w-md p-8 glass rounded-3xl shadow-2xl text-white">
        <div class="flex flex-col items-center mb-6">
            <span class="text-4xl mb-2">♻️</span>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-emerald-400 to-green-300 bg-clip-text text-transparent">UpcycleMatch</h1>
            <p class="text-xs text-white/60 mt-1">Circular Economy Platform</p>
        </div>
        
        {{ $slot }}
    </div>
</body>
</html>
@endsection