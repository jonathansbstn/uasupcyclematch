<style>
    .track-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
    .track-item:last-child { border-bottom: none; }
    .tdot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 8px; }
    .tdot.available { background: #52a87c; }
    .tdot.claimed { background: var(--mid); }
    .tdot.completed { background: var(--rosy); }
    .tbadge { font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: 700; text-transform: capitalize; }
    .tbadge.available { background: #EAF3DE; color: #27500A; }
    .tbadge.claimed { background: #E6F1FB; color: #0C447C; }
    .tbadge.completed { background: #FAEEDA; color: #633806; }
</style>

<div class="card">
    <div class="card-title"><i class="ti ti-refresh"></i> Live Status Tracker</div>
    
    @if(isset($posts) && count($posts) > 0)
        @foreach($posts as $post)
            <div class="track-item">
                <div style="display: flex; align-items: center; min-width: 0; flex: 1;">
                    <span class="tdot {{ $post->status ?? 'available' }}"></span>
                    <div style="display: flex; flex-direction: column; gap: 2px;">
                        <span style="font-weight: bold; font-size: 13px; line-height: 1.3;">{{ $post->judul }} ({{ $post->berat }}kg)</span>
                        <span style="font-size: 11px; color: var(--muted); line-height: 1.2;">
                            {{ $post->created_at ? \Carbon\Carbon::parse($post->created_at)->withLocale('id')->diffForHumans() : '-' }}
                        </span>
                    </div>
                </div>
                <span class="tbadge {{ $post->status ?? 'available' }}">{{ $post->status ?? 'available' }}</span>
            </div>
        @endforeach
    @else
        <p style="text-align: center; font-size: 13px; color: var(--muted); padding: 10px 0;">Belum ada riwayat pengiriman kain.</p>
    @endif
</div>