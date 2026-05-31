<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun — UpcycleMatch</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .auth-nav {
        background: #0A3323; padding: 0 2rem;
        display: flex; align-items: center; justify-content: space-between; height: 60px;
    }
    .auth-nav-logo { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#F7F4D5; text-decoration:none; }
    .auth-nav-logo span { color:#839958; }
    .auth-nav-link { color:#9FE1CB; font-size:13px; font-weight:600; text-decoration:none; }
    .auth-nav-link:hover { color:#F7F4D5; }

    .auth-page { display:flex; min-height:calc(100vh - 60px); background:#F7F4D5; }

    .auth-left {
        flex:1.2; background:#0A3323; position:relative; overflow:hidden;
        display:flex; align-items:center; padding:40px 60px; color:#F7F4D5;
    }
    @media(max-width:900px){ .auth-left{display:none;} }

    .deco-ring { position:absolute; border:2px dashed rgba(131,153,88,0.2); border-radius:50%; animation:rotateRing 20s linear infinite; }
    @keyframes rotateRing { from{transform:rotate(0deg);} to{transform:rotate(360deg);} }

    .auth-left-content { position:relative; z-index:2; width:100%; max-width:480px; }
    .auth-big-text { font-family:'Syne',sans-serif; font-size:38px; font-weight:800; line-height:1.15; color:#F7F4D5; margin-bottom:10px; }
    .auth-big-sub { font-size:15px; color:#9FE1CB; margin-bottom:24px; line-height:1.5; }
    .auth-benefits { display:flex; flex-direction:column; gap:10px; }
    .ab-item { display:flex; align-items:center; gap:12px; font-size:13.5px; background:rgba(255,255,255,0.04); padding:12px 16px; border-radius:12px; border:1px solid rgba(159,225,203,0.1); color:#F7F4D5; }
    .ab-dot { width:8px; height:8px; background:#839958; border-radius:50%; box-shadow:0 0 8px #839958; flex-shrink:0; }

    .auth-right { flex:1; display:flex; align-items:center; justify-content:center; padding:24px 20px; }

    .auth-card {
        background:#fff; border:2px solid #0A3323; border-radius:20px;
        width:100%; max-width:440px; padding:28px 32px;
        box-shadow:0 6px 0 #0A3323;
    }
    .auth-logo { font-family:'Syne',sans-serif; font-size:20px; font-weight:800; color:#0A3323; text-decoration:none; display:inline-block; margin-bottom:12px; }
    .auth-logo span { color:#839958; }
    .auth-title { font-family:'Syne',sans-serif; font-size:20px; font-weight:800; color:#0A3323; margin-bottom:2px; }
    .auth-sub { font-size:13px; color:#666; margin-bottom:18px; }

    /* Success flash */
    .flash-success { background:#dcfce7; border:1.5px solid #86efac; border-radius:10px; padding:12px 16px; color:#166534; font-size:13px; font-weight:600; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
    .flash-error   { background:#fee2e2; border:1.5px solid #fca5a5; border-radius:10px; padding:12px 16px; color:#991b1b; font-size:13px; font-weight:600; margin-bottom:16px; }

    .form-group { margin-bottom:13px; }
    .form-label { display:block; font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#0A3323; margin-bottom:5px; text-transform:uppercase; letter-spacing:0.3px; }
    .form-input { width:100%; padding:10px 13px; border:1.5px solid #C0DD97; border-radius:10px; font-size:13.5px; background:#FBFDF8; color:#0A3323; outline:none; font-family:'DM Sans',sans-serif; transition:border-color 0.2s; }
    .form-input:focus { border-color:#0A3323; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .error-msg { font-size:11.5px; color:#dc2626; margin-top:4px; }

    /* Role selector */
    .role-label { font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#0A3323; text-transform:uppercase; margin-bottom:8px; display:block; }
    .role-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px; }
    .role-card { border:2px solid #C0DD97; border-radius:12px; padding:14px 12px; cursor:pointer; text-align:center; background:#FBFDF8; transition:all 0.2s; position:relative; }
    .role-card input[type=radio] { position:absolute; opacity:0; width:0; height:0; }
    .role-card:has(input:checked) { background:#E8F5D6; border-color:#0A3323; box-shadow:0 3px 0 #0A3323; }
    .role-icon { font-size:26px; margin-bottom:6px; }
    .role-name { font-family:'Syne',sans-serif; font-size:13px; font-weight:800; color:#0A3323; }
    .role-desc { font-size:11px; color:#555; margin-top:2px; }

    .btn-submit { width:100%; background:#0A3323; color:#F7F4D5; border:none; padding:13px; border-radius:100px; font-family:'Syne',sans-serif; font-size:14px; font-weight:700; cursor:pointer; margin-top:4px; transition:background 0.2s; }
    .btn-submit:hover { background:#134d36; }

    .auth-footer { text-align:center; margin-top:16px; font-size:13px; color:#666; }
    .auth-footer a { color:#0A3323; font-weight:700; text-decoration:none; }
    .auth-footer a:hover { text-decoration:underline; }

    .divider { display:flex; align-items:center; gap:10px; margin-bottom:14px; color:#aaa; font-size:12px; }
    .divider::before, .divider::after { content:''; flex:1; height:1px; background:#e5e7eb; }
  </style>
</head>
<body>

<nav class="auth-nav">
  <a href="{{ route('landing') }}" class="auth-nav-logo">Upcycle<span>Match</span></a>
  <a href="{{ route('login') }}" class="auth-nav-link">Sudah punya akun? <strong>Masuk →</strong></a>
</nav>

<div class="auth-page">
  {{-- LEFT PANEL --}}
  <div class="auth-left">
    <div class="deco-ring" style="width:400px;height:400px;top:-100px;right:-100px;"></div>
    <div class="deco-ring" style="width:200px;height:200px;bottom:40px;left:40px;animation-direction:reverse;"></div>
    <div class="auth-left-content">
      <div class="auth-big-text">Bergabunglah<br>Bersama Kami 🌿</div>
      <div class="auth-big-sub">Jadilah bagian dari gerakan circular economy tekstil Indonesia bersama ribuan kontributor dan mitra penjahit.</div>
      <div class="auth-benefits">
        <div class="ab-item"><div class="ab-dot"></div>Daftar gratis, mulai berkontribusi hari ini</div>
        <div class="ab-item"><div class="ab-dot"></div>Dapatkan dampak nyata dari limbah kainmu</div>
        <div class="ab-item"><div class="ab-dot"></div>Terhubung dengan 89+ mitra UMKM penjahit</div>
        <div class="ab-item"><div class="ab-dot"></div>Lacak kontribusi SDG 12 secara real-time</div>
      </div>
    </div>
  </div>

  {{-- RIGHT PANEL --}}
  <div class="auth-right">
    <div class="auth-card">
      <a href="{{ route('landing') }}" class="auth-logo">Upcycle<span>Match</span></a>
      <div class="auth-title">Buat Akun Baru ✨</div>
      <div class="auth-sub">Pilih peranmu dan mulai perjalanan upcycling</div>

      {{-- Flash messages --}}
      @if(session('success'))
        <div class="flash-success">✅ {{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="flash-error">
          @foreach($errors->all() as $e)
            <div>• {{ $e }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register.store') }}">
        @csrf

        {{-- Role Selector --}}
        <label class="role-label">Saya adalah:</label>
        <div class="role-grid">
          <label class="role-card">
            <input type="radio" name="role" value="contributor" {{ old('role','contributor')==='contributor'?'checked':'' }} required>
            <div class="role-icon">🧺</div>
            <div class="role-name">Kontributor</div>
            <div class="role-desc">Punya limbah kain untuk didonasikan</div>
          </label>
          <label class="role-card">
            <input type="radio" name="role" value="upcycler" {{ old('role')==='upcycler'?'checked':'' }}>
            <div class="role-icon">✂️</div>
            <div class="role-name">Upcycler</div>
            <div class="role-desc">UMKM / Pengrajin kreatif</div>
          </label>
        </div>

        {{-- Name & WA --}}
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}" required placeholder="John Doe">
            @error('name')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">No. WhatsApp</label>
            <input type="text" name="whatsapp" class="form-input" value="{{ old('whatsapp') }}" placeholder="08123456789">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="nama@email.com">
          @error('email')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kata Sandi *</label>
            <input type="password" name="password" class="form-input" required placeholder="Min. 8 karakter">
            @error('password')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Konfirmasi Sandi *</label>
            <input type="password" name="password_confirmation" class="form-input" required placeholder="Ulangi sandi">
          </div>
        </div>

        <button type="submit" class="btn-submit">🚀 Daftar Sekarang</button>
      </form>

      <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang →</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>