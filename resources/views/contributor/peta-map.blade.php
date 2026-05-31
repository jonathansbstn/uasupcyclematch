@extends('layouts.dashboard')
@section('title','Peta Lokasi Limbah Saya')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.peta-wrap { display:flex; gap:18px; height:calc(100vh - 110px); }
.peta-info {
    width:280px; flex-shrink:0; background:#fff; border:2px solid #0A3323;
    border-radius:16px; box-shadow:4px 4px 0 #0A3323;
    display:flex; flex-direction:column; overflow:hidden;
}
.peta-info-head { background:#0A3323; padding:16px; }
.peta-info-head h2 { font-family:'Syne',sans-serif; font-size:15px; font-weight:800; color:#F7F4D5; margin:0 0 4px; }
.peta-info-head p  { font-size:11px; color:#9FE1CB; margin:0; }
.peta-list { flex:1; overflow-y:auto; padding:10px; }
.peta-card {
    border:1.5px solid #e5e7eb; border-radius:10px; padding:11px;
    margin-bottom:8px; cursor:pointer; transition:all 0.15s;
}
.peta-card:hover { border-color:#839958; }
.peta-card.act   { border-color:#0A3323; box-shadow:2px 2px 0 #0A3323; }
.pc-title { font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:#0A3323; margin-bottom:2px; }
.pc-sub   { font-size:11px; color:#666; }
.pc-badge { display:inline-block; padding:3px 9px; border-radius:100px; font-size:10px; font-weight:700; margin-top:5px; }
.pb-av { background:#dcfce7; color:#166534; }
.pb-cl { background:#fef9c3; color:#a16207; }
.pb-pr { background:#dbeafe; color:#1d4ed8; }
.pb-co { background:#f3f4f6; color:#6b7280; }
.no-loc { font-size:10px; color:#aaa; margin-top:4px; }
#petaMap {
    flex:1; border-radius:16px; border:2px solid #0A3323; box-shadow:4px 4px 0 #0A3323;
}
.peta-ctrl { position:absolute; top:12px; right:12px; z-index:1000; display:flex; gap:6px; }
.pc-btn {
    background:#fff; border:2px solid #0A3323; border-radius:10px;
    padding:8px 14px; font-size:12px; font-weight:700; color:#0A3323;
    cursor:pointer; font-family:'Syne',sans-serif; box-shadow:2px 2px 0 #0A3323;
}
.pc-btn:hover { background:#F7F4D5; }
</style>
@endpush

@section('content')
<div class="top-bar">
    <div>
        <div class="pg-title">🗺 Peta Limbah Saya</div>
        <div class="pg-sub">Lihat lokasi semua limbah yang sudah kamu posting</div>
    </div>
    <a href="{{ route('contributor.dashboard') }}" class="btn-primary">← Kembali ke Dashboard</a>
</div>

<div class="peta-wrap" style="position:relative;">
    {{-- Daftar limbah --}}
    <div class="peta-info">
        <div class="peta-info-head">
            <h2>📦 Postinganku</h2>
            <p>Klik item untuk lihat di peta</p>
        </div>
        <div class="peta-list" id="petaList">
            <div style="text-align:center;padding:30px;color:#666;font-size:13px;">⏳ Memuat...</div>
        </div>
    </div>

    {{-- Map --}}
    <div style="flex:1;position:relative;">
        <div id="petaMap" style="height:100%;"></div>
        <div class="peta-ctrl">
            <button class="pc-btn" onclick="locateMe()">📍 Lokasimu</button>
            <button class="pc-btn" onclick="fitAll()">🔍 Semua</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const badgeClass = { available:'pb-av', claimed:'pb-cl', processing:'pb-pr', completed:'pb-co' };
const badgeLabel = { available:'Tersedia', claimed:'Diklaim', processing:'Diproses', completed:'Selesai' };
const dotColor   = { available:'#22c55e', claimed:'#eab308', processing:'#3b82f6', completed:'#9ca3af' };

let petaMap, myMarkers = [], myData = [];

petaMap = L.map('petaMap').setView([-2.548926, 118.0148634], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom:19, attribution:'© OpenStreetMap'
}).addTo(petaMap);

function makeIcon(status) {
    const c = dotColor[status] || '#9ca3af';
    return L.divIcon({
        html:`<div style="background:${c};width:18px;height:18px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.4);"></div>`,
        className:'', iconSize:[18,18], iconAnchor:[9,9]
    });
}

function loadMyData() {
    fetch('/api/waste-map', { headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF} })
    .then(r => r.json())
    .then(res => {
        myData = (res.data || []).filter(d => d.mine);
        renderPeta();
    });
}

function renderPeta() {
    // Clear
    myMarkers.forEach(m => petaMap.removeLayer(m));
    myMarkers = [];

    const list = document.getElementById('petaList');
    if (!myData.length) {
        list.innerHTML = '<div style="text-align:center;padding:30px;color:#666;font-size:13px;">📭 Belum ada limbah. Upload dari dashboard!</div>';
        return;
    }

    list.innerHTML = myData.map(item => `
    <div class="peta-card ${item.latitude ? '' : 'no-loc-card'}" id="pc-${item.id}" onclick="focusPeta(${item.id})">
        <div class="pc-title">${item.title}</div>
        <div class="pc-sub">⚖️ ${item.weight} kg · ${item.fabric}</div>
        ${item.address ? `<div class="pc-sub">📍 ${item.address}</div>` : ''}
        <span class="pc-badge ${badgeClass[item.status] || ''}">${badgeLabel[item.status] || item.status}</span>
        ${!item.latitude ? '<div class="no-loc">⚠️ Belum ada koordinat lokasi</div>' : ''}
    </div>`).join('');

    myData.forEach(item => {
        if (!item.latitude || !item.longitude) return;
        const m = L.marker([item.latitude, item.longitude], { icon:makeIcon(item.status) });
        m.bindPopup(`
            <div style="font-family:'DM Sans',sans-serif;min-width:180px;">
                <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:14px;color:#0A3323;margin-bottom:4px;">${item.title}</div>
                <div style="font-size:12px;color:#555;">⚖️ ${item.weight} kg · ${item.fabric}</div>
                <div style="font-size:12px;color:#555;margin-top:2px;">📍 ${item.address || '—'}</div>
                <div style="font-size:12px;font-weight:700;color:${dotColor[item.status]};margin-top:6px;">${badgeLabel[item.status]}</div>
            </div>
        `);
        m.on('click', () => {
            document.querySelectorAll('.peta-card').forEach(c => c.classList.remove('act'));
            document.getElementById('pc-'+item.id)?.classList.add('act');
        });
        m.addTo(petaMap);
        myMarkers.push(m);
    });
}

function focusPeta(id) {
    const item = myData.find(d => d.id === id);
    document.querySelectorAll('.peta-card').forEach(c => c.classList.remove('act'));
    document.getElementById('pc-'+id)?.classList.add('act');
    if (item?.latitude) {
        petaMap.setView([item.latitude, item.longitude], 15);
        myMarkers.find(m => m.getLatLng().lat == item.latitude)?.openPopup();
    }
}

function locateMe() {
    petaMap.locate({ setView:true, maxZoom:14 });
}
function fitAll() {
    if (myMarkers.length) petaMap.fitBounds(L.latLngBounds(myMarkers.map(m => m.getLatLng())).pad(0.1));
    else petaMap.setView([-2.548926,118.0148634], 5);
}

loadMyData();
</script>
@endpush
