<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Autentikasi — UpcycleMatch</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Nunito:wght@400;500;600;700;800&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --dk:    #0A3323;
      --moss:  #839958;
      --beige: #F7F4D5;
      --beige2:#E8E4B8;
      --rosy:  #D3968C;
      --mid:   #105666;
      --white: #ffffff;
      --muted: #5a7a5a;
      --r:     10px;
      --rlg:   18px;
    }

    body {
      font-family: 'Nunito', sans-serif;
      background: #F0EDD0;
      min-height: 100vh;
      color: var(--dk);
    }

    /* ── NAVBAR ──────────────────────────────────── */
    .topbar {
      position: sticky; top: 0; z-index: 100;
      height: 52px;
      background: var(--dk);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 2rem;
    }
    .topbar-logo {
      font-family: 'DM Serif Display', serif;
      font-size: 20px; color: var(--beige);
      text-decoration: none; letter-spacing: -0.3px;
    }
    .topbar-logo span { color: var(--rosy); }
    .tab-group { display: flex; gap: 4px; }
    .tab-btn {
      background: none; border: none;
      padding: 6px 22px; border-radius: 6px;
      font-family: 'Nunito', sans-serif;
      font-size: 13px; font-weight: 700;
      color: rgba(247,244,213,.55);
      cursor: pointer; transition: all .2s;
    }
    .tab-btn.active { background: var(--beige); color: var(--dk); }
    .topbar-hint { font-size: 12px; color: rgba(247,244,213,.38); }

    /* ── PAGE WRAPPER (Perbaikan Rasio Kolom) ────── */
    .page-wrap {
      display: grid;
      grid-template-columns: 1.2fr 0.8fr; /* Form dibuat lebih ramping, panel kiri lebih dominan */
      min-height: calc(100vh - 52px);
    }

    /* ── LEFT PANEL ──────────────────────────────── */
    .left-panel {
      background: var(--dk);
      padding: 3rem 2.5rem;
      display: flex; flex-direction: column;
      justify-content: center; /* Konten diposisikan ke tengah vertikal */
      position: relative; overflow: hidden;
    }
    .left-panel::before {
      content: '';
      position: absolute; right: -120px; bottom: -120px;
      width: 400px; height: 400px; border-radius: 50%;
      background: rgba(131,153,88,.08);
      pointer-events: none;
    }

    .lp-headline {
      font-family: 'DM Serif Display', serif;
      font-size: clamp(28px, 3vw, 36px);
      color: var(--beige); line-height: 1.15; margin-bottom: 1rem;
    }
    .lp-headline em { font-style: italic; color: var(--rosy); }
    .lp-desc { font-size: 13px; color: rgba(247,244,213,.6); line-height: 1.7; margin-bottom: 2rem; }

    /* stat grid */
    .stat-grid {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 10px; margin-bottom: 2rem;
    }
    .stat-box {
      background: rgba(247,244,213,.07);
      border: 1px solid rgba(247,244,213,.11);
      border-radius: var(--r); padding: 14px 12px;
    }
    .stat-val { font-family: 'DM Serif Display', serif; font-size: 26px; color: var(--beige); }
    .stat-lbl { font-size: 11px; color: rgba(247,244,213,.42); margin-top: 2px; }

    /* steps (register panel) */
    .lp-steps { display: flex; flex-direction: column; gap: 14px; margin-bottom: 2rem; }
    .step-row { display: flex; align-items: flex-start; gap: 12px; }
    .step-num {
      width: 26px; height: 26px; border-radius: 50%;
      background: var(--moss);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 800; color: var(--dk); flex-shrink: 0;
    }
    .step-txt { font-size: 12px; color: rgba(247,244,213,.62); line-height: 1.5; padding-top: 3px; }
    .step-txt strong { color: var(--beige); font-weight: 700; }

    /* sdg badge */
    .sdg-pill {
      display: inline-flex; align-items: center; gap: 7px;
      background: rgba(131,153,88,.18);
      border: 1px solid rgba(131,153,88,.32);
      border-radius: 20px; padding: 7px 14px;
      font-size: 11px; font-weight: 700; color: var(--moss);
    }
    .sdg-pill i { font-size: 13px; }

    /* trust footer */
    .trust-row { display: flex; align-items: center; gap: 8px; }
    .trust-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--moss); flex-shrink: 0; }
    .trust-txt { font-size: 11px; color: rgba(247,244,213,.38); }

    /* ── RIGHT PANEL (Perbaikan Lebar Form Card Max-Width) ── */
    .right-panel {
      background: var(--beige);
      padding: 2.5rem 3.5rem; /* Padding disesuaikan */
      display: flex; flex-direction: column; justify-content: center;
    }
    
    /* Membatasi lebar form agar tidak terlalu melar */
    .auth-form-container {
      width: 100%;
      max-width: 380px; /* Card form dikunci agar pas, padat, dan proporsional */
      margin: 0 auto;
    }

    /* page toggle */
    .auth-section { display: none; }
    .auth-section.active { display: block; }

    /* Left panel state toggle */
    .lp-state { display: none; }
    .lp-state.active { display: block; }

    .form-head { margin-bottom: 1.5rem; }
    .form-title { font-family: 'DM Serif Display', serif; font-size: 26px; color: var(--dk); }
    .form-sub {
      font-size: 13px; color: var(--muted); margin-top: 5px; line-height: 1.5;
    }
    .form-sub a { color: var(--mid); font-weight: 800; cursor: pointer; text-decoration: none; }
    .form-sub a:hover { text-decoration: underline; }

    /* success alert */
    .alert-success {
      display: none; align-items: center; gap: 10px;
      background: #EAF3DE; border: 1.5px solid #97C459;
      border-radius: var(--r); padding: 12px 14px; margin-bottom: 1.25rem;
    }
    .alert-success.show { display: flex; }
    .alert-success i { font-size: 20px; color: #27500A; flex-shrink: 0; }
    .as-title { font-size: 13px; font-weight: 700; color: #27500A; }
    .as-sub { font-size: 11px; color: #3B6D11; margin-top: 1px; }

    /* form elements */
    .fg { margin-bottom: 14px; }
    .flabel {
      display: block; font-size: 12px; font-weight: 800;
      color: var(--dk); letter-spacing: .5px;
      text-transform: uppercase; margin-bottom: 6px;
    }
    .input-wrap { position: relative; }
    .input-ico {
      position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
      font-size: 17px; color: var(--moss); pointer-events: none;
    }
    .finput, .fselect {
      width: 100%; height: 42px;
      background: #F9F9EE;
      border: 1.5px solid var(--beige2);
      border-radius: var(--r);
      padding: 0 12px 0 38px;
      font-family: 'Nunito', sans-serif; font-size: 14px; color: var(--dk);
      transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .fselect {
      appearance: none;
      background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%230A3323' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 14px;
      background-color: #F9F9EE;
      cursor: pointer;
    }
    .finput:focus, .fselect:focus {
      outline: none;
      border-color: var(--moss);
      background: var(--white);
      box-shadow: 0 0 0 3px rgba(131,153,88,.15);
    }
    .finput.err, .fselect.err {
      border-color: #D85A30;
      background: #FFF5F2;
    }
    .eye-btn {
      position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer;
      font-size: 17px; color: var(--muted); padding: 0;
    }
    .err-msg {
      display: block; font-size: 12px; font-weight: 600;
      color: #993C1D; margin-top: 4px;
    }

    /* Role selector cards */
    .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .role-card {
      border: 1.5px solid var(--beige2);
      border-radius: var(--r); padding: 14px 10px;
      cursor: pointer; transition: all .2s;
      background: var(--white); text-align: center;
    }
    .role-card:hover { border-color: var(--moss); }
    .role-card.selected { border-color: var(--dk); background: #EAF3E6; }
    .role-card i { font-size: 22px; color: var(--moss); display: block; margin-bottom: 6px; }
    .rc-name { font-size: 13px; font-weight: 800; color: var(--dk); }
    .rc-desc { font-size: 11px; color: var(--muted); margin-top: 2px; line-height: 1.4; }
    .role-radio { display: none; }

    /* password strength */
    .pw-strength { margin-top: 6px; }
    .pw-bars { display: flex; gap: 3px; margin-bottom: 3px; }
    .pw-bar { flex: 1; height: 4px; border-radius: 2px; background: var(--beige2); transition: background .3s; }
    .pw-lbl { font-size: 11px; color: var(--muted); }

    /* ToS row */
    .tos-row {
      display: flex; align-items: flex-start; gap: 8px;
      margin: 14px 0 0;
      font-size: 12px; color: var(--muted); line-height: 1.5;
    }
    .tos-row input { margin-top: 2px; accent-color: var(--dk); }
    .tos-row a { color: var(--mid); font-weight: 700; }

    /* submit button */
    .btn-submit {
      width: 100%; height: 44px;
      background: var(--dk); color: var(--beige);
      border: none; border-radius: var(--r);
      font-family: 'Nunito', sans-serif; font-size: 14px; font-weight: 800;
      cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-top: 14px; transition: opacity .2s;
    }
    .btn-submit:hover { opacity: .88; }

    /* divider */
    .divider {
      display: flex; align-items: center; gap: 10px;
      margin: 16px 0;
      font-size: 12px; font-weight: 700; color: var(--muted);
    }
    .divider::before, .divider::after {
      content: ''; flex: 1;
      border-bottom: 1px dashed var(--beige2);
    }

    /* Google OAuth button */
    .btn-google {
      width: 100%; height: 42px;
      background: var(--white);
      border: 1.5px solid var(--beige2);
      border-radius: var(--r);
      display: flex; align-items: center; justify-content: center; gap: 10px;
      font-family: 'Nunito', sans-serif; font-size: 14px; font-weight: 700; color: var(--dk);
      text-decoration: none; cursor: pointer; transition: background .2s;
    }
    .btn-google:hover { background: #F9F9EE; }

    /* info box */
    .info-box {
      display: flex; align-items: flex-start; gap: 8px;
      background: rgba(131,153,88,.1);
      border-radius: var(--r); padding: 10px 12px;
      margin-top: 12px;
      font-size: 11px; color: var(--muted); line-height: 1.5;
    }
    .info-box i { font-size: 14px; color: var(--moss); flex-shrink: 0; margin-top: 1px; }

    /* forgot password */
    .forgot-link {
      text-align: right; margin-top: -8px; margin-bottom: 10px;
    }
    .forgot-link a { font-size: 12px; color: var(--mid); font-weight: 700; text-decoration: none; }
    .forgot-link a:hover { text-decoration: underline; }

    /* upcycler note */
    .upcycler-info {
      display: none;
      background: rgba(16,86,102,.08);
      border-radius: 8px; padding: 10px 12px;
      font-size: 11px; color: var(--mid);
      line-height: 1.5; margin-top: 8px;
    }
    .upcycler-info i { font-size: 13px; margin-right: 4px; }

    @media(max-width: 992px) {
      .page-wrap { grid-template-columns: 1fr; }
      .left-panel { display: none; }
      .right-panel { padding: 2rem 1.5rem; }
    }
  </style>
</head>
<body>

  <nav class="topbar">
    <a href="{{ route('landing') }}" class="topbar-logo">Upcycle<span>Match</span></a>
    <div class="tab-group">
      <button id="tab-login"    class="tab-btn active" type="button" onclick="switchTo('login')">Masuk</button>
      <button id="tab-register" class="tab-btn"        type="button" onclick="switchTo('register')">Daftar</button>
    </div>
    <span class="topbar-hint">Platform Limbah Tekstil Indonesia</span>
  </nav>

  <div class="page-wrap">

    <div class="left-panel">

      <div id="lp-login" class="lp-state active">
        <div>
          <div class="lp-headline">Selamat datang <em>kembali,</em> pahlawan lingkungan</div>
          <div class="lp-desc">Login untuk melanjutkan perjalananmu menyelamatkan limbah kain dan mengumpulkan Eco-Koin.</div>
          <div class="stat-grid">
            <div class="stat-box"><div class="stat-val">13,250</div><div class="stat-lbl">kg limbah diselamatkan</div></div>
            <div class="stat-box"><div class="stat-val">486</div><div class="stat-lbl">kontributor aktif</div></div>
            <div class="stat-box"><div class="stat-val">204</div><div class="stat-lbl">mitra penjahit</div></div>
            <div class="stat-box"><div class="stat-val">1,820</div><div class="stat-lbl">produk terjual</div></div>
          </div>
          <div class="sdg-pill">
            <i class="ti ti-leaf"></i>
            SDG 12 — Konsumsi &amp; Produksi Bertanggung Jawab
          </div>
        </div>
        <div class="trust-row" style="margin-top:2rem;">
          <div class="trust-dot"></div><span class="trust-txt">Data terenkripsi &amp; aman</span>
          <div class="trust-dot" style="margin-left:10px;"></div><span class="trust-txt">Sistem multi-role terverifikasi</span>
        </div>
      </div>

      <div id="lp-register" class="lp-state">
        <div>
          <div class="lp-headline">Mulai perjalanan <em>hijaumu</em> hari ini</div>
          <div class="lp-desc">Daftarkan diri dalam tiga langkah mudah dan mulai berkontribusi pada ekosistem tekstil berkelanjutan.</div>
          <div class="lp-steps">
            <div class="step-row">
              <div class="step-num">1</div>
              <div class="step-txt"><strong>Pilih peranmu</strong> — Kontributor donasi limbah atau Penjahit UMKM mengolah bahan</div>
            </div>
            <div class="step-row">
              <div class="step-num">2</div>
              <div class="step-txt"><strong>Isi data diri</strong> — Nama, email, WhatsApp, dan kata sandi akunmu</div>
            </div>
            <div class="step-row">
              <div class="step-num">3</div>
              <div class="step-txt"><strong>Akun aktif</strong> — Admin memverifikasi dan kamu siap mulai berkontribusi</div>
            </div>
          </div>
          <div class="sdg-pill">
            <i class="ti ti-circle-check"></i>
            Pendaftaran 100% gratis &bull; Verifikasi 1×24 jam
          </div>
        </div>
        <div class="trust-row" style="margin-top:2rem;">
          <div class="trust-dot"></div><span class="trust-txt">Gratis untuk semua pengguna</span>
          <div class="trust-dot" style="margin-left:10px;"></div><span class="trust-txt">Terverifikasi dalam 1×24 jam</span>
        </div>
      </div>

    </div><div class="right-panel">
      <div class="auth-form-container">

        <div id="section-login" class="auth-section active">

          <div id="alert-login" class="alert-success">
            <i class="ti ti-circle-check"></i>
            <div><div class="as-title">Login berhasil!</div><div class="as-sub">Mengarahkan ke dashboard sesuai peranmu…</div></div>
          </div>

          <div class="form-head">
            <div class="form-title">Masuk ke akun</div>
            <div class="form-sub">Belum punya akun? <a onclick="switchTo('register')">Daftar sekarang</a></div>
          </div>

          <form action="{{ route('login.store') }}" method="POST" id="form-login">
            @csrf

            <div class="fg">
              <label class="flabel" for="l-email">Email</label>
              <div class="input-wrap">
                <i class="ti ti-mail input-ico"></i>
                <input
                  type="email" id="l-email" name="email"
                  class="finput @if($errors->has('email') && !old('name')) err @endif"
                  value="@if(!old('name')){{ old('email') }}@endif"
                  placeholder="nama@email.com" required autocomplete="email">
              </div>
              @if($errors->has('email') && !old('name'))
                <span class="err-msg"><i class="ti ti-alert-circle"></i> {{ $errors->first('email') }}</span>
              @endif
            </div>

            <div class="fg">
              <label class="flabel" for="l-pass">Kata Sandi</label>
              <div class="input-wrap">
                <i class="ti ti-lock input-ico"></i>
                <input
                  type="password" id="l-pass" name="password"
                  class="finput"
                  placeholder="••••••••" required autocomplete="current-password">
                <button type="button" class="eye-btn" onclick="toggleEye('l-pass','l-eye')" aria-label="Tampilkan sandi">
                  <i class="ti ti-eye" id="l-eye"></i>
                </button>
              </div>
            </div>

            <div class="forgot-link"><a href="#">Lupa kata sandi?</a></div>

            <button type="submit" class="btn-submit">
              <i class="ti ti-login"></i> Masuk ke UpcycleMatch
            </button>
          </form>

          <div class="divider">atau lanjutkan dengan</div>

          <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true">
              <path fill="#EA4335" d="M9 3.58c1.12 0 2.12.39 2.92 1.15l2.17-2.17C12.78.93 11.02 0 9 0 5.48 0 2.44 2.02 1 4.96l2.83 2.2C4.5 5.17 6.53 3.58 9 3.58z"/>
              <path fill="#4285F4" d="M17.64 9.2c0-.6-.05-1.18-.15-1.74H9v3.3h4.84c-.21 1.12-.84 2.07-1.79 2.7l2.77 2.15c1.62-1.5 2.56-3.7 2.56-6.41z"/>
              <path fill="#FBBC05" d="M3.83 10.84c-.23-.68-.36-1.41-.36-2.16s.13-1.48.36-2.16L1 4.32C.36 5.62 0 7.07 0 8.6s.36 2.98 1 4.28l2.83-2.04z"/>
              <path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.77-2.15c-.77.52-1.75.83-3.19.83-2.47 0-4.5-1.59-5.25-3.77L1 12.77C2.44 15.98 5.48 18 9 18z"/>
            </svg>
            Masuk via Akun Google
          </a>

          <div class="info-box">
            <i class="ti ti-info-circle"></i>
            Setelah login, kamu akan otomatis diarahkan ke dashboard sesuai peranmu — Kontributor, Penjahit, atau Admin.
          </div>

        </div><div id="section-register" class="auth-section">

          <div id="alert-register" class="alert-success">
            <i class="ti ti-circle-check"></i>
            <div><div class="as-title">Akun berhasil dibuat!</div><div class="as-sub">Tim admin akan memverifikasi dalam 1×24 jam.</div></div>
          </div>

          <div class="form-head">
            <div class="form-title">Buat akun baru</div>
            <div class="form-sub">Sudah punya akun? <a onclick="switchTo('login')">Masuk di sini</a></div>
          </div>

          <form action="{{ route('register.store') }}" method="POST" id="form-register">
            @csrf

            <div class="fg">
              <label class="flabel">Tipe Akun</label>
              <div class="role-grid">
                <label>
                  <input type="radio" name="role" value="contributor" class="role-radio" id="role-contrib"
                    {{ old('role', 'contributor') === 'contributor' ? 'checked' : '' }}
                    onchange="updateRoleUI()">
                  <div class="role-card selected" id="rc-contrib">
                    <i class="ti ti-leaf"></i>
                    <div class="rc-name">Kontributor</div>
                    <div class="rc-desc">Donasikan kain &amp; koin</div>
                  </div>
                </label>
                <label>
                  <input type="radio" name="role" value="upcycler" class="role-radio" id="role-upcycler"
                    {{ old('role') === 'upcycler' ? 'checked' : '' }}
                    onchange="updateRoleUI()">
                  <div class="role-card" id="rc-upcycler">
                    <i class="ti ti-needle-thread"></i>
                    <div class="rc-name">Penjahit</div>
                    <div class="rc-desc">Ambil kain &amp; jahit</div>
                  </div>
                </label>
              </div>
              <div class="upcycler-info" id="upcycler-info">
                <i class="ti ti-info-circle"></i>
                Akun Penjahit memerlukan verifikasi dokumen usaha/portofolio oleh Admin sebelum dapat diaktifkan.
              </div>
            </div>

            <div class="fg">
              <label class="flabel" for="r-name">Nama Lengkap</label>
              <div class="input-wrap">
                <i class="ti ti-user input-ico"></i>
                <input type="text" id="r-name" name="name"
                  class="finput @error('name') err @enderror"
                  value="{{ old('name') }}"
                  placeholder="Sesuai KTP / identitas" required autocomplete="name">
              </div>
              @error('name')
                <span class="err-msg"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
              @enderror
            </div>

            <div class="fg">
              <label class="flabel" for="r-email">Email Aktif</label>
              <div class="input-wrap">
                <i class="ti ti-mail input-ico"></i>
                <input type="email" id="r-email" name="email"
                  class="finput @if($errors->has('email') && old('name')) err @endif"
                  value="@if(old('name')){{ old('email') }}@endif"
                  placeholder="nama@email.com" required autocomplete="email">
              </div>
              @if($errors->has('email') && old('name'))
                <span class="err-msg"><i class="ti ti-alert-circle"></i> {{ $errors->first('email') }}</span>
              @endif
            </div>

            <div class="fg">
              <label class="flabel" for="r-wa">Nomor WhatsApp</label>
              <div class="input-wrap">
                <i class="ti ti-phone input-ico"></i>
                <input type="tel" id="r-wa" name="whatsapp"
                  class="finput"
                  placeholder="08xx-xxxx-xxxx" autocomplete="tel">
              </div>
            </div>

            <div class="fg">
              <label class="flabel" for="r-pass">Kata Sandi</label>
              <div class="input-wrap">
                <i class="ti ti-lock input-ico"></i>
                <input type="password" id="r-pass" name="password"
                  class="finput @error('password') err @enderror"
                  placeholder="Minimal 8 karakter" required autocomplete="new-password"
                  oninput="checkStrength()">
                <button type="button" class="eye-btn" onclick="toggleEye('r-pass','r-eye')" aria-label="Tampilkan sandi">
                  <i class="ti ti-eye" id="r-eye"></i>
                </button>
              </div>
              <div class="pw-strength" id="pw-strength">
                <div class="pw-bars">
                  <div class="pw-bar" id="pb1"></div>
                  <div class="pw-bar" id="pb2"></div>
                  <div class="pw-bar" id="pb3"></div>
                  <div class="pw-bar" id="pb4"></div>
                </div>
                <div class="pw-lbl" id="pw-lbl">Masukkan kata sandi</div>
              </div>
              @error('password')
                <span class="err-msg"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
              @enderror
            </div>

            <div class="fg">
              <label class="flabel" for="r-pass2">Konfirmasi Kata Sandi</label>
              <div class="input-wrap">
                <i class="ti ti-lock-check input-ico"></i>
                <input type="password" id="r-pass2" name="password_confirmation"
                  class="finput"
                  placeholder="Ulangi kata sandi" required autocomplete="new-password">
              </div>
            </div>

            <div class="tos-row">
              <input type="checkbox" id="tos-cb" required>
              <label for="tos-cb">
                Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> dan <a href="#">Kebijakan Privasi</a>.
              </label>
            </div>

            <button type="submit" class="btn-submit">
              <i class="ti ti-circle-plus"></i> Daftar Sekarang
            </button>
          </form>

          <div class="divider">atau daftar dengan</div>

          <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true">
              <path fill="#EA4335" d="M9 3.58c1.12 0 2.12.39 2.92 1.15l2.17-2.17C12.78.93 11.02 0 9 0 5.48 0 2.44 2.02 1 4.96l2.83 2.2C4.5 5.17 6.53 3.58 9 3.58z"/>
              <path fill="#4285F4" d="M17.64 9.2c0-.6-.05-1.18-.15-1.74H9v3.3h4.84c-.21 1.12-.84 2.07-1.79 2.7l2.77 2.15c1.62-1.5 2.56-3.7 2.56-6.41z"/>
              <path fill="#FBBC05" d="M3.83 10.84c-.23-.68-.36-1.41-.36-2.16s.13-1.48.36-2.16L1 4.32C.36 5.62 0 7.07 0 8.6s.36 2.98 1 4.28l2.83-2.04z"/>
              <path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.77-2.15c-.77.52-1.75.83-3.19.83-2.47 0-4.5-1.59-5.25-3.77L1 12.77C2.44 15.98 5.48 18 9 18z"/>
            </svg>
            Daftar via Akun Google
          </a>

        </div></div></div></div><script>
    /* ── TAB SWITCHER ───────────────────────────── */
    function switchTo(section) {
      // forms
      document.querySelectorAll('.auth-section').forEach(function(el){ el.classList.remove('active'); });
      document.getElementById('section-' + section).classList.add('active');

      // nav tabs
      document.querySelectorAll('.tab-btn').forEach(function(btn){ btn.classList.remove('active'); });
      document.getElementById('tab-' + section).classList.add('active');

      // left panel content
      document.querySelectorAll('.lp-state').forEach(function(el){ el.classList.remove('active'); });
      document.getElementById('lp-' + section).classList.add('active');
    }

    /* ── AUTO-DETECT LARAVEL ERRORS ─────────────── */
    @if($errors->any() && old('name'))
      switchTo('register');
    @else
      switchTo('login');
    @endif

    /* ── PASSWORD VISIBILITY TOGGLE ─────────────── */
    function toggleEye(inputId, iconId) {
      var inp = document.getElementById(inputId);
      var ico = document.getElementById(iconId);
      if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'ti ti-eye-off';
      } else {
        inp.type = 'password';
        ico.className = 'ti ti-eye';
      }
    }

    /* ── PASSWORD STRENGTH METER ─────────────────── */
    function checkStrength() {
      var v   = document.getElementById('r-pass').value;
      var lvl = 0;
      if (v.length >= 8)          lvl++;
      if (/[A-Z]/.test(v))        lvl++;
      if (/[0-9]/.test(v))        lvl++;
      if (/[^A-Za-z0-9]/.test(v)) lvl++;

      var colors = ['#E24B4A', '#EF9F27', '#639922', '#3B6D11'];
      var labels = ['Lemah', 'Cukup', 'Kuat', 'Sangat kuat'];

      for (var i = 1; i <= 4; i++) {
        document.getElementById('pb' + i).style.background =
          (i <= lvl) ? colors[lvl - 1] : 'var(--beige2)';
      }

      var lbl = document.getElementById('pw-lbl');
      if (v.length === 0) {
        lbl.textContent = 'Masukkan kata sandi';
        lbl.style.color = 'var(--muted)';
      } else {
        lbl.textContent = labels[lvl - 1] || 'Lemah';
        lbl.style.color = colors[lvl - 1] || colors[0];
      }
    }

    /* ── ROLE CARD UI ───────────────────────────── */
    function updateRoleUI() {
      var contrib  = document.getElementById('role-contrib').checked;
      document.getElementById('rc-contrib').classList.toggle('selected', contrib);
      document.getElementById('rc-upcycler').classList.toggle('selected', !contrib);
      document.getElementById('upcycler-info').style.display = contrib ? 'none' : 'block';
    }

    // init on load
    updateRoleUI();
  </script>
</body>
</html>