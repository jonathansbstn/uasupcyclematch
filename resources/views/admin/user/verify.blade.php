@extends('layouts.dashboard')
@section('title','Verifikasi Penjahit')
@section('content')
<div class="top-bar"><div><div class="pg-title">Manajemen Pengguna</div><div class="pg-sub">Verifikasi akun penjahit baru</div></div></div>
<div class="card">
    <div class="card-h"><div class="ct">Penjahit Menunggu Verifikasi</div><div class="cs">{{ $pending->total() }} akun</div></div>
    <div class="card-b">
        <table class="admin-table">
            <thead><tr><th>Nama</th><th>Email</th><th>HP</th><th>Daftar</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($pending as $user)
            <tr>
                <td><strong>{{ $user->nama_depan }} {{ $user->nama_belakang }}</strong></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->no_hp }}</td>
                <td style="font-size:12px;color:#3B6D11;">{{ $user->created_at->format('d M Y') }}</td>
                <td><span class="sbadge {{ $user->is_verified ? 'sb-completed' : 'sb-available' }}">{{ $user->is_verified ? 'Aktif' : 'Pending' }}</span></td>
                <td>
                    @if(!$user->is_verified)
                    <form method="POST" action="{{ route('admin.users.verify',$user->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="vbtn">✅ Verifikasi</button>
                    </form>
                    @else
                    <span style="font-size:12px;color:#3B6D11;">Terverifikasi ✓</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:24px;color:#3B6D11;">Tidak ada penjahit pending</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="padding:16px;">{{ $pending->links() }}</div>
    </div>
</div>
@endsection