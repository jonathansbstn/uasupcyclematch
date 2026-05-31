@extends('layouts.dashboard')
@section('title','Impact Analytics')

@push('styles')
<style>
.analytics-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.analytics-title { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#0A3323; }

.counter-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
.counter-card {
    background:#fff; border:2px solid #0A3323; border-radius:16px;
    padding:24px; box-shadow:4px 4px 0 #0A3323; text-align:center;
}
.cc-icon { font-size:32px; margin-bottom:8px; }
.cc-num { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; color:#0A3323; }
.cc-lbl { font-size:12px; color:#666; text-transform:uppercase; font-weight:700; margin-top:4px; }
.cc-sub { font-size:11px; color:#839958; font-weight:700; margin-top:2px; }

.charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
.chart-card { background:#fff; border:2px solid #0A3323; border-radius:16px; padding:24px; box-shadow:4px 4px 0 #0A3323; }
.chart-title { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; margin-bottom:16px; color:#0A3323; }
.chart-full { grid-column:span 2; }
canvas { max-height:260px; }
</style>
@endpush

@section('content')
<div class="analytics-header">
    <div>
        <div class="analytics-title">📊 Impact Analytics Dashboard</div>
        <div style="font-size:13px;color:#666;margin-top:4px;">Data real-time dampak lingkungan platform UpcycleMatch</div>
    </div>
    <a href="{{ route('admin.report') }}" class="btn-primary">📄 Export Laporan →</a>
</div>

{{-- Animated KPI Cards --}}
<div class="counter-grid">
    <div class="counter-card">
        <div class="cc-icon">♻️</div>
        <div class="cc-num" data-target="{{ number_format($totalWeight, 0, '', '') }}" id="c-weight">0</div>
        <div class="cc-lbl">Total Limbah (kg)</div>
        <div class="cc-sub">Diselamatkan dari TPA</div>
    </div>
    <div class="counter-card">
        <div class="cc-icon">🎨</div>
        <div class="cc-num" data-target="{{ $totalProducts }}" id="c-products">0</div>
        <div class="cc-lbl">Produk Upcycle</div>
        <div class="cc-sub">Karya aktif di galeri</div>
    </div>
    <div class="counter-card">
        <div class="cc-icon">✂️</div>
        <div class="cc-num" data-target="{{ $totalUpcyclers }}" id="c-upcyclers">0</div>
        <div class="cc-lbl">Upcycler Aktif</div>
        <div class="cc-sub">UMKM & Pengrajin</div>
    </div>
    <div class="counter-card">
        <div class="cc-icon">💰</div>
        <div class="cc-num" data-target="{{ number_format($materialSavings, 0, '', '') }}" id="c-savings" data-prefix="Rp " data-short="true">0</div>
        <div class="cc-lbl">Material Cost Savings</div>
        <div class="cc-sub">@ Rp50.000/kg</div>
    </div>
</div>

{{-- Charts --}}
<div class="charts-grid">
    <div class="chart-card">
        <div class="chart-title">📦 Limbah Terselamatkan per Bulan (kg)</div>
        <canvas id="chartWaste"></canvas>
    </div>
    <div class="chart-card">
        <div class="chart-title">🎨 Produk Upcycle per Bulan</div>
        <canvas id="chartProducts"></canvas>
    </div>
    <div class="chart-card chart-full">
        <div class="chart-title">🍩 Distribusi Status Limbah</div>
        <div style="display:flex;align-items:center;gap:40px;">
            <canvas id="chartStatus" style="max-width:260px;max-height:260px;"></canvas>
            <div id="status-legend" style="flex:1;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Animated Counter ────────────────────────────────────────────────────
document.querySelectorAll('.cc-num[data-target]').forEach(el => {
    const target  = parseInt(el.dataset.target.replace(/\D/g,'')) || 0;
    const prefix  = el.dataset.prefix || '';
    const isShort = el.dataset.short === 'true';
    const dur     = 1500;
    const step    = Math.ceil(target / (dur / 16));
    let curr = 0;
    const timer = setInterval(() => {
        curr = Math.min(curr + step, target);
        if (isShort) {
            el.textContent = prefix + (curr >= 1000000
                ? (curr/1000000).toFixed(1)+'Jt'
                : curr >= 1000 ? (curr/1000).toFixed(0)+'Rb'
                : curr.toLocaleString('id'));
        } else {
            el.textContent = prefix + curr.toLocaleString('id');
        }
        if (curr >= target) clearInterval(timer);
    }, 16);
});

// ── Chart Data (from PHP) ───────────────────────────────────────────────
const months       = @json($chartMonths);
const wasteData    = @json($chartWasteData);
const productData  = @json($chartProductData);
const statusLabels = @json($chartStatusLabels);
const statusData   = @json($chartStatusData);

const labelMap = { available:'Tersedia', claimed:'Diklaim', processing:'Diproses', completed:'Selesai' };
const colorMap = { available:'#22c55e', claimed:'#eab308', processing:'#3b82f6', completed:'#9ca3af' };

const monthNames = months.map(m => {
    const [y,mo] = m.split('-');
    return new Date(y, mo-1).toLocaleDateString('id-ID', {month:'short', year:'2-digit'});
});

// ── Bar Chart — Limbah ──────────────────────────────────────────────────
new Chart(document.getElementById('chartWaste'), {
    type:'bar',
    data: {
        labels: monthNames,
        datasets: [{ label:'Berat Limbah (kg)', data:wasteData,
            backgroundColor:'#839958', borderRadius:6, borderSkipped:false }]
    },
    options: { responsive:true, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,grid:{color:'#f0f0f0'}} } }
});

// ── Line Chart — Produk ─────────────────────────────────────────────────
new Chart(document.getElementById('chartProducts'), {
    type:'line',
    data: {
        labels: monthNames,
        datasets: [{ label:'Produk Upcycle', data:productData,
            borderColor:'#0A3323', backgroundColor:'rgba(10,51,35,0.08)',
            fill:true, tension:0.4, pointBackgroundColor:'#0A3323', pointRadius:5 }]
    },
    options: { responsive:true, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,grid:{color:'#f0f0f0'}} } }
});

// ── Pie Chart — Status ──────────────────────────────────────────────────
new Chart(document.getElementById('chartStatus'), {
    type:'doughnut',
    data: {
        labels: statusLabels.map(l => labelMap[l] || l),
        datasets: [{ data:statusData, backgroundColor:statusLabels.map(l => colorMap[l] || '#ccc'), borderWidth:2 }]
    },
    options: { responsive:true, plugins:{ legend:{ display:false } } }
});

// Custom legend
const legend = document.getElementById('status-legend');
statusLabels.forEach((l,i) => {
    legend.innerHTML += `<div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
        <div style="width:14px;height:14px;border-radius:50%;background:${colorMap[l]||'#ccc'};"></div>
        <div><strong>${labelMap[l]||l}</strong>: ${statusData[i]} limbah</div>
    </div>`;
});
</script>
@endpush
