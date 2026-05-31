<div class="card">
    <div class="card-title"><i class="ti ti-user"></i> Akun Kontributor</div>
    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px; padding: 5px 0;">
        <div><strong>Nama Kontributor:</strong> {{ auth()->user()->name }}</div>
        <div><strong>Email Terdaftar:</strong> {{ auth()->user()->email }}</div>
        <div><strong>Tanggal Bergabung:</strong> {{ auth()->user()->created_at ? auth()->user()->created_at->format('d M Y') : '-' }}</div>
    </div>
</div>