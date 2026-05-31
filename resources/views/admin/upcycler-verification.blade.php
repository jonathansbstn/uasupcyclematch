@extends('layouts.dashboard')
@section('title','Verifikasi Upcycler')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
.verif-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.verif-title { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#0A3323; }

.stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
.stat-box { background:#fff; border:2px solid #0A3323; border-radius:14px; padding:20px; box-shadow:4px 4px 0 #0A3323; text-align:center; }
.stat-num { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; color:#0A3323; }
.stat-lbl { font-size:12px; color:#666; font-weight:700; text-transform:uppercase; margin-top:4px; }

.user-table { width:100%; border-collapse:collapse; font-size:14px; }
.user-table th { background:#0A3323; color:#F7F4D5; padding:12px 16px; text-align:left; font-family:'Syne',sans-serif; font-size:12px; text-transform:uppercase; }
.user-table th:first-child { border-radius:10px 0 0 0; }
.user-table th:last-child { border-radius:0 10px 0 0; }
.user-table td { padding:14px 16px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
.user-table tr:hover td { background:#fafff5; }
.user-avatar { width:40px; height:40px; background:#839958; color:#0A3323; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:15px; flex-shrink:0; }

.status-pill { display:inline-block; padding:4px 12px; border-radius:100px; font-size:11px; font-weight:700; }
.sp-pending  { background:#fef9c3; color:#a16207; border:1.5px solid #fde047; }
.sp-verified { background:#dcfce7; color:#166534; border:1.5px solid #86efac; }
.sp-rejected { background:#fee2e2; color:#991b1b; border:1.5px solid #fca5a5; }

.btn-verify { background:#22c55e; color:#fff; border:2px solid #166534; border-radius:6px; padding:6px 14px; font-weight:700; font-size:12px; cursor:pointer; margin-right:6px; }
.btn-reject { background:#ef4444; color:#fff; border:2px solid #991b1b; border-radius:6px; padding:6px 14px; font-weight:700; font-size:12px; cursor:pointer; }
.verified-badge { color:#22c55e; font-weight:700; font-size:13px; }
</style>
@endpush

@section('content')
<div class="verif-header">
    <div>
        <div class="verif-title">🔍 Verifikasi Upcycler / Penjahit</div>
        <div style="font-size:13px;color:#666;margin-top:4px;">Kelola dan verifikasi akun upcycler yang mendaftar di platform</div>
    </div>
</div>

@php
    $total    = $upcyclers->total();
    $verified = $upcyclers->filter(fn($u) => $u->is_verified)->count();
    $pending  = $upcyclers->filter(fn($u) => !$u->is_verified)->count();
@endphp
<div class="stats-row">
    <div class="stat-box"><div class="stat-num">{{ $total }}</div><div class="stat-lbl">Total Upcycler</div></div>
    <div class="stat-box"><div class="stat-num" style="color:#22c55e;">{{ $verified }}</div><div class="stat-lbl">Terverifikasi</div></div>
    <div class="stat-box"><div class="stat-num" style="color:#eab308;">{{ $pending }}</div><div class="stat-lbl">Menunggu</div></div>
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <table class="user-table">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Email</th>
                <th>WhatsApp</th>
                <th>Terdaftar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($upcyclers as $user)
        <tr>
            <td>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="user-avatar">{{ strtoupper(substr($user->name,0,2)) }}</div>
                    <div>
                        <div style="font-weight:700;">{{ $user->name }}</div>
                        <div style="font-size:11px;color:#666;">ID #{{ $user->id }}</div>
                    </div>
                </div>
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->whatsapp ?: '—' }}</td>
            <td>{{ $user->created_at->format('d M Y') }}</td>
            <td>
                @if($user->is_verified)
                    <span class="status-pill sp-verified">✅ Terverifikasi</span>
                @else
                    <span class="status-pill sp-pending">⏳ Menunggu</span>
                @endif
            </td>
            <td>
                @if(!$user->is_verified)
                <button class="btn-verify" onclick="confirmVerify({{ $user->id }}, '{{ $user->name }}')">✅ Verifikasi</button>
                <button class="btn-reject" onclick="confirmReject({{ $user->id }}, '{{ $user->name }}')">❌ Tolak</button>
                @else
                <span class="verified-badge">✅ Sudah Diverifikasi</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:#666;">
                Belum ada akun upcycler yang terdaftar.
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($upcyclers->hasPages())
<div style="display:flex;justify-content:center;margin-top:20px;gap:6px;">
    {{ $upcyclers->links() }}
</div>
@endif

{{-- Hidden forms for SweetAlert --}}
<form id="verify-form" method="POST" style="display:none;">
    @csrf @method('PATCH')
</form>
<form id="reject-form" method="POST" style="display:none;">
    @csrf @method('PATCH')
    <input type="hidden" name="reason" id="reject-reason" />
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmVerify(userId, name) {
    Swal.fire({
        title: 'Verifikasi Akun?',
        html: `Apakah Anda yakin ingin memverifikasi akun <strong>${name}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '✅ Ya, Verifikasi',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.getElementById('verify-form');
            form.action = `/admin/upcycler-verification/${userId}/verify`;
            form.submit();
        }
    });
}

function confirmReject(userId, name) {
    Swal.fire({
        title: 'Tolak Akun?',
        html: `<p>Masukkan alasan penolakan untuk <strong>${name}</strong>:</p>`,
        input: 'textarea',
        inputPlaceholder: 'Contoh: KTP tidak jelas, data tidak lengkap...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '❌ Tolak Akun',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.getElementById('reject-form');
            form.action = `/admin/upcycler-verification/${userId}/reject`;
            document.getElementById('reject-reason').value = result.value || '';
            form.submit();
        }
    });
}

@if(session('success'))
Swal.fire({ icon:'success', title:'Berhasil!', text:'{{ session("success") }}', confirmButtonColor:'#0A3323' });
@endif
</script>
@endpush
