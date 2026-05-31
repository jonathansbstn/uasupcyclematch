<div class="card koin-card">
    <div class="card-title"><i class="ti ti-wallet"></i> Eco-Wallet</div>
    <div class="koin-val">{{ auth()->user()->eco_points ?? 0 }} Koin</div>
    <div class="koin-sub">Setara Rp {{ number_format((auth()->user()->eco_points ?? 0) * 2500, 0, ',', '.') }}</div>
    
    <div class="progress-bar">
        <div class="progress-fill" style="width: {{ min(round(((auth()->user()->eco_points ?? 0) / 20) * 100), 100) }}%;"></div>
    </div>
    <div class="progress-txt">Minimal pencairan saldo: 20 Koin</div>

    <form action="/pencairan/proses" method="POST" style="margin-top: 1.25rem;">
        @csrf
        <div class="m-tabs">
            <button type="button" class="m-tab on" id="btn-ew" onclick="setMethod('ew')">E-Wallet</button>
            <button type="button" class="m-tab" id="btn-bank" onclick="setMethod('bank')">Transfer Bank</button>
        </div>
        
        <input type="hidden" name="jenis_metode" id="inp-metode" value="ew">

        <div class="fg">
            <label class="flabel" style="color:var(--dk);" id="lbl-tujuan">Pilih E-Wallet</label>
            <select class="fselect" name="tujuan" style="background: rgba(255,255,255,0.9);">
                <option value="dana">DANA Balance</option>
                <option value="gopay">GoPay Eco</option>
                <option value="bca">Bank BCA</option>
            </select>
        </div>
        <div class="fg">
            <label class="flabel" style="color:var(--dk);">Nomor HP / Rekening</label>
            <input class="finput" name="nomor_rekening" required placeholder="Contoh: 0812345678" style="background: rgba(255,255,255,0.9);" />
        </div>
        <button type="submit" class="post-btn" style="background:var(--dk); color:var(--beige);" {{ (auth()->user()->eco_points ?? 0) < 20 ? 'disabled' : '' }}>Konversi Koin</button>
    </form>
</div>

<script>
    function setMethod(m) {
        document.getElementById('btn-ew').classList.toggle('on', m==='ew');
        document.getElementById('btn-bank').classList.toggle('on', m==='bank');
        document.getElementById('inp-metode').value = m;
        document.getElementById('lbl-tujuan').textContent = m === 'bank' ? 'Pilih Bank Tujuan' : 'Pilih E-Wallet';
    }
</script>