@extends('layouts.dashboard')
@section('title','Postingan Limbah')
@section('content')

<div class="top-bar">
    <div><div class="pg-title">Postingan Limbah Kain</div><div class="pg-sub">Kelola semua postingan limbahmu</div></div>
    <a href="{{ route('limbah.create') }}" class="btn-primary">+ Post Limbah Baru</a>
</div>

<div class="kpi4" style="margin-bottom:20px;">
    <div class="kpi kpi-g"><div class="kpi-lbl kl-g">Total Postingan</div><div class="kpi-num kn-g">{{ $posts->total() }}</div></div>
    <div class="kpi kpi-t"><div class="kpi-lbl kl-t">Available</div><div class="kpi-num kn-t">{{ $posts->where('status','available')->count() }}</div></div>
    <div class="kpi kpi-d"><div class="kpi-lbl kl-d">Claimed/Processing</div><div class="kpi-num kn-d">{{ $posts->whereIn('status',['claimed','processing'])->count() }}</div></div>
    <div class="kpi kpi-p"><div class="kpi-lbl kl-p">Completed</div><div class="kpi-num kn-p">{{ $posts->where('status','completed')->count() }}</div></div>
</div>

<div class="card">
    <div class="card-b">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Judul</th><th>Jenis</th><th>Berat</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($posts as $post)
            <tr>
                <td style="color:#3B6D11;font-size:12px;">{{ $loop->iteration }}</td>
                <td><strong>{{ Str::limit($post->judul,40) }}</strong><br><small style="color:#3B6D11;">{{ $post->alamat }}</small></td>
                <td><span class="type-chip tc-{{ $post->jenis_bahan }}">{{ ucfirst($post->jenis_bahan) }}</span></td>
                <td><strong>{{ $post->estimasi_berat }}</strong> kg</td>
                <td><span class="sbadge sb-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                <td style="font-size:12px;color:#3B6D11;">{{ $post->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('limbah.edit',$post->id) }}" class="pi-act-btn">✏️</a>
                        @if($post->status === 'available')
                        <form method="POST" action="{{ route('limbah.destroy',$post->id) }}" onsubmit="return confirm('Hapus postingan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="pi-act-btn danger">🗑</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:32px;color:#3B6D11;">Belum ada postingan. <a href="{{ route('limbah.create') }}">Buat sekarang →</a></td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="padding:16px;">{{ $posts->links() }}</div>
    </div>
</div>
@endsection