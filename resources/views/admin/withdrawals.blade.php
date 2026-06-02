@extends('layouts.dashboard')
@section('title','Manajemen Pencairan Koin')
@section('content')

<div class="top-bar">
    <div>
        <div class="pg-title">💰 Manajemen Pencairan Koin</div>
        <div class="pg-sub">Kelola permintaan pencairan koin dari Kontributor</div>
    </div>
</div>

{{-- Stats --}}
<div class="kpi4" style="margin-bottom:1.5rem;">
    <div class="kpi" style="background:#FEF9C3;border:1.5px solid #FDE68A;">
        <div class="kpi-lbl" style="color:#92400E;">⏳ Pending</div>
        <div class="kpi-num" style="color:#78350F;">{{ $stats['pending'] }}</div>
    </div>
    <div class="kpi" style="background:#DCFCE7;border:1.5px solid #86EFAC;">
        <div class="kpi-lbl" style="color:#166534;">✅ Disetujui</div>
        <div class="kpi-num" style="color:#14532D;">{{ $stats['approved'] }}</div>
    </div>
    <div class="kpi" style="background:#FEE2E2;border:1.5px solid #FCA5A5;">
        <div class="kpi-lbl" style="color:#991B1B;">❌ Ditolak</div>
        <div class="kpi-num" style="color:#7F1D1D;">{{ $stats['rejected'] }}</div>
    </div>
    <div class="kpi" style="background:#DBEAFE;border:1.5px solid #93C5FD;">
        <div class="kpi-lbl" style="color:#1E40AF;">💸 Total Dicairkan</div>
        <div class="kpi-num" style="color:#1E3A8A;font-size:16px;">Rp{{ number_format($stats['total_rp'],0,',','.') }}</div>
    </div>
</div>

<div class="card">
    <div class="card-h">
        <div class="ct">Daftar Permintaan Pencairan</div>
        <div class="cs">Terbaru tampil di atas · Koin dikembalikan otomatis jika ditolak</div>
    </div>
    <div class="card-b" style="padding:0;">
        @if($withdrawals->count())
        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#F9FAFB;border-bottom:1.5px solid #E5E7EB;">
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">#</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">Kontributor</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">Koin / Nominal</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">Metode</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">Rekening / No. HP</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">Tanggal</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">Status</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:700;color:#374151;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($withdrawals as $wd)
            <tr style="border-bottom:1px solid #F3F4F6;transition:.15s;" onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background=''">
                <td style="padding:12px 16px;color:#9CA3AF;">#{{ $wd->id }}</td>
                <td style="padding:12px 16px;">
                    <div style="font-weight:700;color:#111827;">{{ $wd->user->name }}</div>
                    <div style="font-size:11px;color:#6B7280;">{{ $wd->user->email }}</div>
                </td>
                <td style="padding:12px 16px;">
                    <div style="font-weight:800;color:#0A3323;">{{ $wd->jumlah_koin }} Koin</div>
                    <div style="font-size:12px;color:#6B7280;">Rp{{ number_format($wd->nominal_rupiah,0,',','.') }}</div>
                </td>
                <td style="padding:12px 16px;">
                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:700;">
                        {{ $wd->metode === 'bank' ? '🏦 Transfer Bank' : '📱 E-Wallet' }}
                    </span>
                    <div style="font-size:11px;color:#374151;margin-top:2px;">{{ $wd->nama_bank }}</div>
                </td>
                <td style="padding:12px 16px;">
                    <div style="font-family:monospace;font-size:13px;color:#0A3323;font-weight:700;">{{ $wd->nomor_rekening }}</div>
                    <div style="font-size:11px;color:#6B7280;">a.n. {{ $wd->nama_penerima }}</div>
                </td>
                <td style="padding:12px 16px;font-size:12px;color:#6B7280;">{{ $wd->created_at->format('d M Y H:i') }}</td>
                <td style="padding:12px 16px;">
                    @if($wd->status === 'pending')
                        <span style="background:#FEF9C3;color:#92400E;font-size:10px;font-weight:800;padding:4px 10px;border-radius:9999px;">⏳ Pending</span>
                    @elseif($wd->status === 'approved')
                        <span style="background:#DCFCE7;color:#166534;font-size:10px;font-weight:800;padding:4px 10px;border-radius:9999px;">✅ Disetujui</span>
                        @if($wd->processed_at)<div style="font-size:10px;color:#6B7280;margin-top:2px;">{{ $wd->processed_at->format('d M Y') }}</div>@endif
                    @elseif($wd->status === 'rejected')
                        <span style="background:#FEE2E2;color:#991B1B;font-size:10px;font-weight:800;padding:4px 10px;border-radius:9999px;">❌ Ditolak</span>
                        @if($wd->catatan_admin)<div style="font-size:10px;color:#6B7280;margin-top:2px;">{{ $wd->catatan_admin }}</div>@endif
                    @endif
                </td>
                <td style="padding:12px 16px;">
                    @if($wd->status === 'pending')
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        {{-- APPROVE --}}
                        <form method="POST" action="{{ route('admin.withdrawals.approve', $wd) }}" onsubmit="return confirm('Setujui pencairan Rp{{ number_format($wd->nominal_rupiah, 0, ',', '.') }} untuk {{ $wd->user->name }}?')">
                            @csrf @method('PATCH')
                            <button type="submit" style="width:100%;padding:6px 12px;background:#22c55e;color:#fff;border:none;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer;">
                                ✅ Setujui
                            </button>
                        </form>
                        {{-- REJECT --}}
                        <button onclick="showReject({{ $wd->id }})" style="width:100%;padding:6px 12px;background:#ef4444;color:#fff;border:none;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer;">
                            ❌ Tolak
                        </button>
                        {{-- Reject modal per row --}}
                        <div id="reject-{{ $wd->id }}" style="display:none;margin-top:6px;">
                            <form method="POST" action="{{ route('admin.withdrawals.reject', $wd) }}">
                                @csrf @method('PATCH')
                                <textarea name="catatan_admin" placeholder="Alasan penolakan (opsional)..."
                                    style="width:100%;padding:6px 8px;border:1.5px solid #E5E7EB;border-radius:6px;font-size:11px;resize:none;margin-bottom:4px;" rows="2"></textarea>
                                <button type="submit" style="width:100%;padding:5px;background:#DC2626;color:#fff;border:none;border-radius:6px;font-size:11px;cursor:pointer;">
                                    Konfirmasi Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <span style="font-size:11px;color:#9CA3AF;">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        {{-- Pagination --}}
        @if($withdrawals->hasPages())
        <div style="padding:16px;display:flex;justify-content:center;gap:6px;">
            {{ $withdrawals->links() }}
        </div>
        @endif
        @else
        <div style="text-align:center;padding:48px;color:#9CA3AF;">
            <div style="font-size:40px;margin-bottom:12px;">💰</div>
            <div style="font-weight:700;color:#374151;margin-bottom:4px;">Belum ada permintaan pencairan</div>
            <div style="font-size:13px;">Permintaan akan muncul di sini ketika Kontributor mengajukan pencairan koin.</div>
        </div>
        @endif
    </div>
</div>

<script>
function showReject(id) {
    const el = document.getElementById('reject-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection
