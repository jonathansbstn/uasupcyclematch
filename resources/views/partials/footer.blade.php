<footer class="um-footer">
  <div class="um-footer-inner">
    <!-- Col 1: Brand -->
    <div class="um-footer-brand">
      <div class="um-footer-logo">🌿 Upcycle<span>Match</span></div>
      <p class="um-footer-tagline">Platform daur ulang limbah tekstil berbasis SDG 12. Menghubungkan kontributor, penjahit, dan komunitas untuk ekonomi sirkular yang berkelanjutan.</p>
      <div class="um-footer-social">
        <a href="https://instagram.com" target="_blank" title="Instagram" class="um-social-link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
        </a>
        <a href="https://twitter.com" target="_blank" title="Twitter/X" class="um-social-link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="https://facebook.com" target="_blank" title="Facebook" class="um-social-link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="https://youtube.com" target="_blank" title="YouTube" class="um-social-link">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
        </a>
      </div>
    </div>

    <!-- Col 2: Navigasi -->
    <div class="um-footer-col">
      <h4 class="um-footer-col-title">Navigasi</h4>
      <ul class="um-footer-links">
        <li><a href="{{ route('landing') }}">Beranda</a></li>
        <li><a href="{{ route('gallery') }}">Gallery Produk</a></li>
        @auth
        <li><a href="{{ match(auth()->user()->role) { 'admin' => route('admin.dashboard'), 'upcycler' => route('upcycler.dashboard'), default => route('contributor.dashboard') } }}">Dashboard Saya</a></li>
        @else
        <li><a href="{{ route('login') }}">Masuk</a></li>
        <li><a href="{{ route('register') }}">Daftar</a></li>
        @endauth
      </ul>
    </div>

    <!-- Col 3: Layanan -->
    <div class="um-footer-col">
      <h4 class="um-footer-col-title">Layanan</h4>
      <ul class="um-footer-links">
        <li><a href="{{ route('register') }}">Upload Limbah</a></li>
        <li><a href="{{ route('gallery') }}">Beli Produk</a></li>
        <li><a href="{{ route('landing') }}#cara-kerja">Cara Kerja</a></li>
        <li><a href="{{ route('landing') }}#kamus-kain">Kamus Kain</a></li>
      </ul>
    </div>

    <!-- Col 4: Kontak -->
    <div class="um-footer-col">
      <h4 class="um-footer-col-title">Kontak</h4>
      <ul class="um-footer-links">
        <li><a href="mailto:hello@upcyclematch.id">📧 hello@upcyclematch.id</a></li>
        <li><span style="color:rgba(255,255,255,.55);">📍 Indonesia</span></li>
        <li><a href="tel:+62218524621">📞 (021) 85246214</a></li>
        <li><a href="https://wa.me/6281234567890" target="_blank">💬 WhatsApp Admin</a></li>
      </ul>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="um-footer-bottom">
    <div>© 2026 UpcycleMatch. Hak Cipta Dilindungi. 🌿 Mendukung SDG 12 — Konsumsi &amp; Produksi Bertanggung Jawab</div>
    <div class="um-footer-bottom-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Use</a>
    </div>
  </div>
</footer>

<style>
.um-footer {
  background: #0A3323;
  color: rgba(255,255,255,.85);
  font-family: 'DM Sans', sans-serif;
  margin-top: auto;
}
.um-footer-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 56px 32px 32px;
  display: grid;
  grid-template-columns: 1.6fr 1fr 1fr 1.2fr;
  gap: 48px;
}
@media(max-width:860px) {
  .um-footer-inner { grid-template-columns: 1fr 1fr; gap: 32px; }
}
@media(max-width:540px) {
  .um-footer-inner { grid-template-columns: 1fr; gap: 28px; }
}
.um-footer-logo {
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  color: #F7F4D5;
  margin-bottom: 12px;
}
.um-footer-logo span { color: #D3968C; }
.um-footer-tagline {
  font-size: 13px;
  color: rgba(255,255,255,.55);
  line-height: 1.7;
  margin-bottom: 20px;
}
.um-footer-social { display: flex; gap: 10px; }
.um-social-link {
  display: flex; align-items: center; justify-content: center;
  width: 36px; height: 36px;
  background: rgba(255,255,255,.08);
  border-radius: 8px;
  color: rgba(255,255,255,.7);
  transition: .2s;
}
.um-social-link:hover { background: #839958; color: #fff; transform: translateY(-2px); }

.um-footer-col-title {
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1.2px;
  color: #F7F4D5;
  margin-bottom: 16px;
}
.um-footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.um-footer-links a {
  font-size: 14px;
  color: rgba(255,255,255,.55);
  text-decoration: none;
  transition: .2s;
}
.um-footer-links a:hover { color: #839958; padding-left: 4px; }

.um-footer-bottom {
  border-top: 1px solid rgba(255,255,255,.08);
  padding: 18px 32px;
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
  font-size: 12px;
  color: rgba(255,255,255,.4);
}
.um-footer-bottom-links { display: flex; gap: 20px; }
.um-footer-bottom-links a { color: rgba(255,255,255,.5); text-decoration: none; }
.um-footer-bottom-links a:hover { color: #839958; }
</style>
