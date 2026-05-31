@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
<style>
    .ad-wrap { background: var(--cream); min-height: calc(100vh - 60px); }
    .ad-layout { display: grid; grid-template-columns: 200px 1fr; min-height: calc(100vh - 60px); }
    .ad-sidebar { background: #051A13; padding: 24px 0; }
    .ad-menu-item { display: flex; align-items: center; gap: 10px; padding: 11px 20px; font-size: 13px; color: var(--mint); text-decoration: none; }
    .ad-menu-item:hover { background: rgba(131,153,88,0.1); color: var(--cream); }
    .ad-menu-item.active { background: rgba(131,153,88,0.15); color: var(--cream); border-right: 3px solid var(--olive); }
    .ad-main { padding: 28px; }
    .ad-page-title { font-family: var(--font-head); font-size: 22px; font-weight: 800; color: var(--dark); margin-bottom: 4px; }
    .ad-page-sub { font-size: 13px; color: var(--text-muted); margin-bottom: 24px; }
    .user-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 14px; }
    .user-card { background: #fff; border-radius: 16px; border: 1.5px solid var(--border); padding: 18px; }
    .uc-top { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .uc-avatar { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: var(--font-head); font-size: 16px; font-weight: 700; flex-shrink: 0; }
    .av-c { background: var(--olive); color: var(--dark); }
    .av-u { background: var(--teal); color: var(--mint); }
    .av-a { background: #D3968C; color: #4B1528; }
    .uc-name { font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--dark); }
    .uc-email { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
    .uc-meta { display: flex; align-items: center; justify-content: space-between; }
    .filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
    .filter-tab { padding: 6px 16px; border-radius: 100px; font-size: 12px; border: 1.5px solid var(--border); color: var(--text-muted); background: #fff; cursor: pointer; font-family: var(--font-body); transition: all .15s; }
    .filter-tab.active { background: var(--dark); color: var(--cream); border-color: var(--dark); }
</style>

<div class="ad-wrap">
    <div class="ad-layout">
        <div class="ad-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="ad-menu-item">📊 Dashboard</a>
            <a href="{{ route('admin.data-limbah') }}" class="ad-menu-item">📦 Data Limbah</a>
            <a href="{{ route('admin.users') }}" class="ad-menu-item active">👥 Pengguna</a>
            <a href="{{ route('admin.galeri') }}" class="ad-menu-item">🖼️ Galeri Karya</a>
            <a href="{{ route('admin.export') }}" class="ad-menu-item">📥 Export Report</a>
        </div>

        <div class="ad-main">
            <div class="ad-page-title">👥 Manajemen Pengguna</div>
            <div class="ad-page-sub">Total {{ $allUsers->count() }} akun terdaftar — verifikasi upcycler sebelum mereka dapat beroperasi</div>

            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterRole('all', this)">Semua ({{ $allUsers->count() }})</button>
                <button class="filter-tab" onclick="filterRole('contributor', this)">Contributor ({{ $allUsers->where('role','contributor')->count() }})</button>
                <button class="filter-tab" onclick="filterRole('upcycler', this)">Upcycler ({{ $allUsers->where('role','upcycler')->count() }})</button>
                <button class="filter-tab" onclick="filterRole('admin', this)">Admin ({{ $allUsers->where('role','admin')->count() }})</button>
            </div>

            <div class="user-cards" id="userCards">
                @foreach($allUsers as $u)
                <div class="user-card" data-role="{{ $u->role }}">
                    <div class="uc-top">
                        <div class="uc-avatar av-{{ substr($u->role,0,1) }}">{{ strtoupper(substr($u->name,0,2)) }}</div>
                        <div>
                            <div class="uc-name">{{ $u->name }}</div>
                            <div class="uc-email">{{ $u->email }}</div>
                        </div>
                    </div>
                    <div class="uc-meta">
                        <span class="um-role-badge badge-{{ $u->role }}">{{ ucfirst($u->role) }}</span>
                        <span style="font-size:11px; color:var(--text-muted);">{{ $u->created_at->format('d M Y') }}</span>
                        @if($u->isUpcycler())
                        <form method="POST" action="{{ route('admin.users.verify', $u->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:var(--dark); color:var(--cream); border:none; border-radius:100px; padding:5px 12px; font-family:var(--font-head); font-size:11px; font-weight:700; cursor:pointer;">
                                ✅ Verifikasi
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterRole(role, btn) {
    document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.user-card').forEach(c => {
        c.style.display = (role === 'all' || c.dataset.role === role) ? 'block' : 'none';
    });
}
</script>
@endpush
@endsection