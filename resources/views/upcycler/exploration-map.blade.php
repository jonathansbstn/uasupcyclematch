@extends('layouts.dashboard')
@section('title','Peta Eksplorasi Limbah')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* ── Layout ── */
.map-page { display:flex; gap:18px; height:calc(100vh - 110px); }
.map-sidebar {
    width:320px; flex-shrink:0; background:#fff; border:2px solid #0A3323;
    border-radius:16px; box-shadow:4px 4px 0 #0A3323; display:flex;
    flex-direction:column; overflow:hidden;
}
.map-sidebar-head {
    background:#0A3323; padding:16px 18px; flex-shrink:0;
}
.map-sidebar-head h2 { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; color:#F7F4D5; margin:0 0 10px; }
.sidebar-search {
    display:flex; align-items:center; gap:8px; background:#fff;
    border-radius:8px; padding:8px 12px;
}
.sidebar-search input {
    flex:1; border:none; outline:none; font-size:13px; font-family:'DM Sans',sans-serif; color:#0A3323; background:transparent;
}
.sidebar-filters { display:flex; gap:6px; margin-top:10px; flex-wrap:wrap; }
.sf-btn {
    padding:4px 12px; border-radius:100px; border:1.5px solid rgba(247,244,213,0.3);
    font-size:11px; font-weight:700; color:#9FE1CB; background:transparent;
    cursor:pointer; font-family:'Syne',sans-serif; transition:all 0.2s;
}
.sf-btn.on { background:#839958; color:#0A3323; border-color:#839958; }
.sidebar-list { flex:1; overflow-y:auto; padding:8px; }
.waste-card {
    border:1.5px solid #e5e7eb; border-radius:12px; padding:12px;
    margin-bottom:8px; cursor:pointer; transition:all 0.2s; position:relative;
    background:#fff;
}
.waste-card:hover { border-color:#839958; box-shadow:0 2px 8px rgba(10,51,35,0.1); }
.waste-card.active { border-color:#0A3323; box-shadow:3px 3px 0 #0A3323; }
.wc-title { font-family:'Syne',sans-serif; font-size:13px; font-weight:800; color:#0A3323; margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.wc-meta  { font-size:11px; color:#666; margin-bottom:6px; }
.wc-foot  { display:flex; align-items:center; justify-content:space-between; }
.wc-badge {
    display:inline-block; padding:3px 10px; border-radius:100px;
    font-size:10px; font-weight:800; font-family:'Syne',sans-serif;
}
.wb-available  { background:#dcfce7; color:#166534; }
.wb-claimed    { background:#fef9c3; color:#a16207; }
.wb-processing { background:#dbeafe; color:#1d4ed8; }
.wb-completed  { background:#f3f4f6; color:#6b7280; }
.wc-kg { font-size:11px; font-weight:700; color:#839958; }
.claim-btn-sm {
    background:#839958; color:#0A3323; border:none; border-radius:6px;
    padding:5px 12px; font-size:11px; font-weight:800; cursor:pointer;
    font-family:'Syne',sans-serif; transition:all 0.15s;
}
.claim-btn-sm:hover { background:#96af65; }
.empty-list { text-align:center; padding:40px 16px; color:#666; font-size:13px; }

/* ── Map ── */
.map-wrap { flex:1; position:relative; }
#map {
    height:100%; width:100%; border-radius:16px;
    border:2px solid #0A3323; box-shadow:4px 4px 0 #0A3323;
}
.map-controls {
    position:absolute; top:12px; right:12px; z-index:1000;
    display:flex; flex-direction:column; gap:6px;
}
.mc-btn {
    background:#fff; border:2px solid #0A3323; border-radius:10px;
    padding:8px 14px; font-size:12px; font-weight:700; color:#0A3323;
    cursor:pointer; font-family:'Syne',sans-serif; box-shadow:2px 2px 0 #0A3323;
    transition:all 0.15s; white-space:nowrap;
}
.mc-btn:hover { background:#F7F4D5; }
.map-legend {
    position:absolute; bottom:16px; left:16px; z-index:1000;
    background:#fff; border:2px solid #0A3323; border-radius:12px;
    padding:12px 16px; box-shadow:3px 3px 0 #0A3323; font-size:12px;
}
.legend-row { display:flex; align-items:center; gap:7px; margin-bottom:5px; font-weight:600; color:#0A3323; }
.legend-row:last-child { margin-bottom:0; }
.ldot { width:10px; height:10px; border-radius:50%; border:2px solid rgba(0,0,0,0.2); flex-shrink:0; }
.stat-bar {
    position:absolute; top:12px; left:12px; z-index:1000;
    background:#fff; border:2px solid #0A3323; border-radius:12px;
    padding:10px 16px; box-shadow:3px 3px 0 #0A3323;
    display:flex; gap:16px; font-size:12px; font-weight:700;
}
.sb-item { text-align:center; }
.sb-num  { font-family:'Syne',sans-serif; font-size:18px; font-weight:800; color:#0A3323; }
.sb-lbl  { font-size:10px; color:#666; text-transform:uppercase; letter-spacing:0.3px; }

/* ── WhatsApp Modal ── */
.modal-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6);
    z-index:9999; align-items:center; justify-content:center; padding:20px;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:#fff; border:2px solid #0A3323; border-radius:20px;
    padding:32px; max-width:420px; width:100%; box-shadow:6px 6px 0 #0A3323;
    animation:popIn 0.25s ease;
}
@keyframes popIn { from{transform:scale(0.9);opacity:0;} to{transform:scale(1);opacity:1;} }
.modal-box h3 { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#0A3323; margin-bottom:6px; }
.modal-box p  { font-size:14px; color:#666; margin-bottom:20px; }
.modal-info {
    background:#F7F4D5; border:1.5px solid #C0DD97; border-radius:12px;
    padding:16px; margin-bottom:20px; font-size:13.5px; line-height:1.8;
}
.modal-info strong { color:#0A3323; }
.btn-wa {
    display:flex; align-items:center; justify-content:center; gap:10px;
    background:#25D366; color:#fff; border:none; border-radius:100px;
    padding:13px 28px; font-family:'Syne',sans-serif; font-weight:700;
    font-size:14px; cursor:pointer; width:100%; text-decoration:none;
    box-shadow:0 4px 0 #1aaa50; transition:transform 0.1s;
}
.btn-wa:hover { transform:translateY(-1px); }
.btn-close-modal {
    width:100%; background:transparent; border:1.5px solid #0A3323; color:#0A3323;
    border-radius:100px; padding:10px; font-family:'Syne',sans-serif;
    font-weight:700; font-size:13px; cursor:pointer; margin-top:10px;
}
</style>
@endpush

@section('content')
<div class="map-page">

    {{-- SIDEBAR --}}
    <div class="map-sidebar">
        <div class="map-sidebar-head">
            <h2>🗺 Limbah Tersedia</h2>
            <div class="sidebar-search">
                <i class="ti ti-search" style="color:#839958;font-size:16px;"></i>
                <input type="text" id="sideSearch" placeholder="Cari limbah, bahan, lokasi..." oninput="filterSidebar()">
            </div>
            <div class="sidebar-filters">
                <button class="sf-btn on" data-filter="all" onclick="setFilter('all',this)">Semua</button>
                <button class="sf-btn" data-filter="available" onclick="setFilter('available',this)">Tersedia</button>
                <button class="sf-btn" data-filter="katun" onclick="setFilter('katun',this)">Katun</button>
                <button class="sf-btn" data-filter="denim" onclick="setFilter('denim',this)">Denim</button>
            </div>
        </div>
        <div class="sidebar-list" id="sidebarList">
            <div class="empty-list" id="sideLoading">⏳ Memuat data...</div>
        </div>
    </div>

    {{-- MAP AREA --}}
    <div class="map-wrap">
        <div id="map"></div>

        {{-- Stats bar --}}
        <div class="stat-bar" id="statBar">
            <div class="sb-item"><div class="sb-num" id="cnt-all">—</div><div class="sb-lbl">Total</div></div>
            <div class="sb-item"><div class="sb-num" id="cnt-av" style="color:#22c55e;">—</div><div class="sb-lbl">Tersedia</div></div>
            <div class="sb-item"><div class="sb-num" id="cnt-cl" style="color:#eab308;">—</div><div class="sb-lbl">Diklaim</div></div>
        </div>

        {{-- Controls --}}
        <div class="map-controls">
            <button class="mc-btn" onclick="locateMe()">📍 Lokasiku</button>
            <button class="mc-btn" onclick="fitAll()">🔍 Lihat Semua</button>
            <a href="{{ route('upcycler.production') }}" class="mc-btn">📋 Klaim Saya</a>
        </div>

        {{-- Legend --}}
        <div class="map-legend">
            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:11px;margin-bottom:8px;color:#0A3323;">STATUS</div>
            <div class="legend-row"><div class="ldot" style="background:#22c55e;"></div>Tersedia</div>
            <div class="legend-row"><div class="ldot" style="background:#eab308;"></div>Diklaim</div>
            <div class="legend-row"><div class="ldot" style="background:#3b82f6;"></div>Diproses</div>
            <div class="legend-row"><div class="ldot" style="background:#9ca3af;"></div>Selesai</div>
        </div>
    </div>
</div>

{{-- WhatsApp Modal --}}
<div class="modal-overlay" id="waModal">
    <div class="modal-box">
        <h3>🎉 Klaim Berhasil!</h3>
        <p>Limbah berhasil diklaim. Hubungi kontributor untuk jadwal penjemputan.</p>
        <div class="modal-info">
            <div><strong>Nama Kontributor:</strong> <span id="wa-name">—</span></div>
            <div><strong>No. WhatsApp:</strong> <span id="wa-number">—</span></div>
            <div><strong>Alamat Penjemputan:</strong> <span id="wa-address">—</span></div>
        </div>
        <a href="#" id="wa-link" target="_blank" class="btn-wa">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            Hubungi via WhatsApp
        </a>
        <button class="btn-close-modal" onclick="closeModal()">Tutup</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const statusColors = { available:'#22c55e', claimed:'#eab308', processing:'#3b82f6', completed:'#9ca3af' };
const statusLabel  = { available:'Tersedia', claimed:'Diklaim', processing:'Diproses', completed:'Selesai' };
const statusBadge  = { available:'wb-available', claimed:'wb-claimed', processing:'wb-processing', completed:'wb-completed' };

let map, allData = [], markers = {}, activeFilter = 'all';

// Init map
map = L.map('map', { zoomControl:true }).setView([-2.548926, 118.0148634], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom:19, attribution:'© OpenStreetMap'
}).addTo(map);

function makeIcon(status, size=16) {
    const c = statusColors[status] || '#9ca3af';
    const s = size;
    return L.divIcon({
        html:`<div style="background:${c};width:${s}px;height:${s}px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.4);"></div>`,
        className:'', iconSize:[s,s], iconAnchor:[s/2,s/2]
    });
}

function loadData() {
    fetch('/api/waste-map', { headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF} })
    .then(r => r.json())
    .then(res => {
        allData = res.data || [];
        renderAll();
        updateStats();
    })
    .catch(() => {
        document.getElementById('sideLoading').textContent = '❌ Gagal memuat data. Coba refresh halaman.';
    });
}

function renderAll() {
    // Clear markers
    Object.values(markers).forEach(m => map.removeLayer(m));
    markers = {};

    const search = document.getElementById('sideSearch').value.toLowerCase();
    const filtered = allData.filter(item => {
        if (activeFilter !== 'all' && activeFilter !== item.fabric && activeFilter !== item.status) return false;
        if (search && !`${item.title} ${item.fabric} ${item.address}`.toLowerCase().includes(search)) return false;
        return true;
    });

    // Render sidebar
    const list = document.getElementById('sidebarList');
    if (filtered.length === 0) {
        list.innerHTML = '<div class="empty-list">🔍 Tidak ada limbah ditemukan.</div>';
    } else {
        list.innerHTML = filtered.map(item => `
        <div class="waste-card" id="card-${item.id}" onclick="focusItem(${item.id})">
            ${item.image ? `<img src="${item.image}" style="width:100%;height:110px;object-fit:cover;border-radius:8px;margin-bottom:8px;" />` : ''}
            <div class="wc-title">${item.title}</div>
            <div class="wc-meta">📍 ${item.address || 'Lokasi belum diset'} · ${item.created}</div>
            <div class="wc-foot">
                <span class="wc-badge ${statusBadge[item.status] || ''}">${statusLabel[item.status] || item.status}</span>
                <span class="wc-kg">⚖️ ${item.weight} kg · ${ucfirst(item.fabric)}</span>
            </div>
            ${item.status === 'available' && !item.mine ? `
            <div style="margin-top:8px;">
                <button class="claim-btn-sm" onclick="event.stopPropagation(); doClaim(${item.id})">
                    🤝 Klaim Sekarang
                </button>
            </div>` : ''}
            ${item.mine ? '<div style="font-size:10px;color:#839958;margin-top:6px;font-weight:700;">📦 Limbah milikmu</div>' : ''}
        </div>`).join('');
    }

    // Render markers
    filtered.forEach(item => {
        if (!item.latitude || !item.longitude) return;
        const icon = makeIcon(item.status, item.status === 'available' ? 20 : 14);
        const m = L.marker([item.latitude, item.longitude], { icon });

        const popupHtml = `
        <div style="min-width:220px;font-family:'DM Sans',sans-serif;padding:4px;">
            ${item.image ? `<img src="${item.image}" style="width:100%;height:130px;object-fit:cover;border-radius:8px;margin-bottom:8px;"/>` : ''}
            <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:800;color:#0A3323;margin-bottom:4px;">${item.title}</div>
            <div style="font-size:12px;color:#555;margin-bottom:4px;">📦 ${ucfirst(item.fabric)} &nbsp;|&nbsp; ⚖️ ${item.weight} kg</div>
            <div style="font-size:12px;color:#555;margin-bottom:8px;">📍 ${item.address || 'Lokasi tidak tersedia'}</div>
            <div style="font-size:12px;color:#555;margin-bottom:10px;">👤 ${item.owner}</div>
            ${item.status === 'available' && !item.mine ? `<button onclick="doClaim(${item.id})" style="width:100%;background:#839958;color:#0A3323;border:2px solid #0A3323;border-radius:8px;padding:8px;font-weight:800;font-size:13px;cursor:pointer;box-shadow:2px 2px 0 #0A3323;" id="mpop-btn-${item.id}">🤝 Klaim Sekarang</button>` : `<span style="font-size:12px;font-weight:700;color:${statusColors[item.status]}">${statusLabel[item.status]}</span>`}
            ${item.mine ? '<div style="font-size:11px;color:#839958;font-weight:700;margin-top:6px;">📦 Ini limbah milikmu</div>' : ''}
        </div>`;

        m.bindPopup(popupHtml, { maxWidth:260, autoPan:true });
        m.on('click', () => highlightCard(item.id));
        m.addTo(map);
        markers[item.id] = m;
    });
}

function updateStats() {
    document.getElementById('cnt-all').textContent = allData.length;
    document.getElementById('cnt-av').textContent  = allData.filter(d => d.status === 'available').length;
    document.getElementById('cnt-cl').textContent  = allData.filter(d => d.status === 'claimed').length;
}

function setFilter(f, btn) {
    activeFilter = f;
    document.querySelectorAll('.sf-btn').forEach(b => b.classList.remove('on'));
    btn.classList.add('on');
    renderAll();
}

function filterSidebar() { renderAll(); }

function focusItem(id) {
    const item = allData.find(d => d.id === id);
    if (!item || !item.latitude) return;
    map.setView([item.latitude, item.longitude], 15);
    if (markers[id]) markers[id].openPopup();
    highlightCard(id);
}

function highlightCard(id) {
    document.querySelectorAll('.waste-card').forEach(c => c.classList.remove('active'));
    const card = document.getElementById('card-' + id);
    if (card) {
        card.classList.add('active');
        card.scrollIntoView({ behavior:'smooth', block:'nearest' });
    }
}

function doClaim(id) {
    const btns = document.querySelectorAll(`#mpop-btn-${id}, .claim-btn-sm[onclick*="${id}"]`);
    btns.forEach(b => { b.disabled = true; b.textContent = '⏳ Memproses...'; });

    fetch('/api/claim', {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({ textile_id: id })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            map.closePopup();
            loadData(); // refresh markers
            document.getElementById('wa-name').textContent    = res.whatsapp.name;
            document.getElementById('wa-number').textContent  = res.whatsapp.number;
            document.getElementById('wa-address').textContent = res.whatsapp.address;
            document.getElementById('wa-link').href           = res.whatsapp.url || '#';
            document.getElementById('waModal').classList.add('open');
        } else {
            alert('⚠️ ' + res.message);
            btns.forEach(b => { b.disabled = false; b.textContent = '🤝 Klaim Sekarang'; });
        }
    })
    .catch(() => {
        alert('Terjadi kesalahan. Silakan coba lagi.');
        btns.forEach(b => { b.disabled = false; b.textContent = '🤝 Klaim Sekarang'; });
    });
}

function locateMe() {
    map.locate({ setView:true, maxZoom:14 });
    map.on('locationerror', () => alert('Tidak bisa mendeteksi lokasi. Pastikan GPS aktif.'));
}

function fitAll() {
    const pts = Object.values(markers).map(m => m.getLatLng());
    if (pts.length) map.fitBounds(L.latLngBounds(pts).pad(0.1));
    else map.setView([-2.548926, 118.0148634], 5);
}

function closeModal() { document.getElementById('waModal').classList.remove('open'); }
document.getElementById('waModal').addEventListener('click', e => { if(e.target === e.currentTarget) closeModal(); });

function ucfirst(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : ''; }

// Initial load
loadData();
setInterval(loadData, 30000);
</script>
@endpush
