<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menunggu Verifikasi — UpcycleMatch</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --color-dark-green:     #0A3323;
      --color-moss-green:     #839958;
      --color-beige:          #F7F4D5;
      --color-rosy-brown:     #D3968C;
      --color-midnight-green: #105666;
      --font-heading: 'Playfair Display', serif;
      --font-body:    'DM Sans', sans-serif;
    }
    body {
      font-family: var(--font-body);
      background: var(--color-beige);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* NAV */
    .nav {
      background: var(--color-dark-green);
      padding: 0 2rem;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .nav-logo {
      font-family: var(--font-heading);
      font-size: 22px;
      color: var(--color-beige);
      text-decoration: none;
    }
    .nav-logo span { color: var(--color-rosy-brown); }
    .nav-logout {
      display: flex; align-items: center; gap: 6px;
      background: rgba(247,244,213,0.1); border: 1.5px solid rgba(247,244,213,0.2);
      color: var(--color-beige); border-radius: 100px;
      padding: 6px 16px; font-size: 13px; font-weight: 600;
      cursor: pointer; text-decoration: none; font-family: var(--font-body);
      transition: .2s;
    }
    .nav-logout:hover { background: rgba(211,150,140,.2); border-color: var(--color-rosy-brown); }

    /* CENTER CONTENT */
    .center-wrap {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    .wait-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 40px rgba(10,51,35,.10);
      padding: 56px 48px;
      max-width: 540px;
      width: 100%;
      text-align: center;
      border-top: 4px solid var(--color-moss-green);
    }

    .wait-icon-wrap {
      width: 88px; height: 88px;
      background: linear-gradient(135deg, #EAF3DE, #C0DD97);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 28px;
      font-size: 40px;
      animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50%       { transform: translateY(-8px); }
    }

    .wait-title {
      font-family: var(--font-heading);
      font-size: 28px;
      color: var(--color-dark-green);
      margin-bottom: 10px;
      line-height: 1.3;
    }
    .wait-sub {
      font-size: 15px;
      color: #6b7280;
      line-height: 1.7;
      margin-bottom: 32px;
    }

    /* Steps */
    .steps { text-align: left; display: flex; flex-direction: column; gap: 14px; margin-bottom: 36px; }
    .step {
      display: flex; align-items: flex-start; gap: 14px;
      background: #F9FAFB; border-radius: 12px; padding: 14px 16px;
    }
    .step-num {
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--color-dark-green); color: #fff;
      font-weight: 700; font-size: 13px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .step-num.done { background: var(--color-moss-green); }
    .step-title { font-size: 13px; font-weight: 700; color: var(--color-dark-green); margin-bottom: 2px; }
    .step-desc  { font-size: 12px; color: #6b7280; }

    /* Info badge */
    .info-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: #EFF6FF; border: 1px solid #BFDBFE;
      color: #1d4ed8; border-radius: 100px;
      padding: 8px 20px; font-size: 12px; font-weight: 600;
      margin-bottom: 28px;
    }

    /* WA button */
    .wa-btn {
      display: inline-flex; align-items: center; gap: 8px;
      background: #25D366; color: #fff;
      border-radius: 12px; padding: 13px 28px;
      font-size: 14px; font-weight: 700; text-decoration: none;
      box-shadow: 0 4px 0 #1da851; transition: .2s;
      font-family: var(--font-body);
      margin-bottom: 16px; width: 100%; justify-content: center;
    }
    .wa-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 0 #1da851; }

    /* Footer */
    footer {
      background: var(--color-dark-green);
      text-align: center;
      padding: 16px;
      font-size: 12px;
      color: rgba(247,244,213,.5);
    }
    footer a { color: rgba(247,244,213,.7); text-decoration: none; }
    footer a:hover { color: var(--color-moss-green); }
  </style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <a href="{{ route('landing') }}" class="nav-logo">Upcycle<span>Match</span></a>
  <form action="{{ route('logout') }}" method="POST" style="margin:0;">
    @csrf
    <button type="submit" class="nav-logout">
      <i class="ti ti-logout"></i> Keluar
    </button>
  </form>
</nav>

<!-- CENTER -->
<div class="center-wrap">
  <div class="wait-card">
    @php $vStat = auth()->user()->upcyclerProfile->verification_status ?? 'pending'; @endphp
    @if($vStat === 'rejected')
    <div class="wait-icon-wrap" style="background: linear-gradient(135deg, #FEE2E2, #FCA5A5); color: #991b1b;">❌</div>

    <div class="info-badge" style="background:#FEE2E2; border-color:#FCA5A5; color:#991B1B;">
      <i class="ti ti-alert-circle"></i>
      Pendaftaran Akun Ditolak
    </div>

    <h1 class="wait-title" style="color:#991B1B;">Verifikasi Akun Ditolak</h1>
    <p class="wait-sub">
      Halo, <strong>{{ auth()->user()->name }}</strong>. Mohon maaf, pendaftaran akun Upcycler Anda ditolak oleh admin dengan alasan:<br>
      <strong style="color:#ef4444;display:block;margin-top:8px;padding:8px;background:#f9fafb;border-radius:6px;border:1px solid #fca5a5;">{{ auth()->user()->upcyclerProfile->rejection_reason ?? 'Tidak memenuhi persyaratan.' }}</strong>
    </p>

    <div style="font-size:13px;color:#6b7280;margin-bottom:24px;background:#fff1f2;padding:12px;border-radius:8px;border:1px solid #fecdd3;">
        Anda saat ini tidak dapat mengakses fitur platform UpcycleMatch. Silakan hubungi admin untuk informasi lebih lanjut atau memperbaiki data pendaftaran.
    </div>
    @else
    <div class="wait-icon-wrap">⏳</div>

    <div class="info-badge">
      <i class="ti ti-clock"></i>
      Akun Anda sedang dalam proses verifikasi
    </div>

    <h1 class="wait-title">Menunggu Verifikasi Admin</h1>
    <p class="wait-sub">
      Halo, <strong>{{ auth()->user()->name }}</strong>! Terima kasih telah mendaftar sebagai Upcycler di UpcycleMatch.
      Akun Anda saat ini sedang ditinjau oleh tim admin kami.
    </p>

    <!-- Steps -->
    <div class="steps">
      <div class="step">
        <div class="step-num done"><i class="ti ti-check" style="font-size:14px;"></i></div>
        <div>
          <div class="step-title">✅ Pendaftaran Selesai</div>
          <div class="step-desc">Akun Anda berhasil dibuat pada {{ auth()->user()->created_at->format('d M Y') }}.</div>
        </div>
      </div>
      <div class="step">
        <div class="step-num" style="animation: pulse 1.5s infinite; background: var(--color-midnight-green);">2</div>
        <div>
          <div class="step-title">🔍 Sedang Diverifikasi Admin</div>
          <div class="step-desc">Tim UpcycleMatch sedang meninjau profil dan kelengkapan data Anda. Proses ini biasanya memakan waktu <strong>1–2 hari kerja</strong>.</div>
        </div>
      </div>
      <div class="step" style="opacity:.5;">
        <div class="step-num">3</div>
        <div>
          <div class="step-title">🎉 Akses Dashboard Upcycler</div>
          <div class="step-desc">Setelah diverifikasi, Anda bisa langsung login dan mengakses semua fitur Upcycler.</div>
        </div>
      </div>
    </div>
    @endif

    <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin UpcycleMatch, saya ' . auth()->user()->name . ' (email: ' . auth()->user()->email . ') ingin menanyakan status verifikasi akun Upcycler saya. Terima kasih!') }}"
       target="_blank" class="wa-btn">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
      Hubungi Admin via WhatsApp
    </a>

    <div style="font-size:12px; color:#9ca3af; text-align:center;">
      Atau email kami di <a href="mailto:hello@upcyclematch.id" style="color: var(--color-midnight-green); font-weight:600;">hello@upcyclematch.id</a>
    </div>
  </div>
</div>

<footer>
  © 2026 UpcycleMatch ·
  <a href="#">Privacy Policy</a> ·
  <a href="#">Terms of Use</a>
</footer>

<style>
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}
</style>

</body>
</html>
