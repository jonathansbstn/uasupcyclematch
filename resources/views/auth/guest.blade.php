<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Auth') — UpcycleMatch</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="auth-body">

@if(session('info'))
<div class="toast toast-info show" id="flashToast">ℹ️ {{ session('info') }}</div>
@endif

@yield('content')

<script>
const t = document.getElementById('flashToast');
if(t) setTimeout(() => t.classList.remove('show'), 5000);
</script>
</body>
</html>