<div class="card">
    <div class="card-title"><i class="ti ti-package"></i> Upload Limbah Kain</div>
    
    <form action="/limbah/store" method="POST">
        @csrf
        <div class="fg">
            <label class="flabel">Judul Postingan / Jenis Kain</label>
            <input class="finput" name="judul" required placeholder="Contoh: Kain Katun Sisa Jahitan Baju" />
        </div>
        <div class="frow fg">
            <div>
                <label class="flabel">Kategori Bahan</label>
                <select class="fselect" name="jenis_bahan" required>
                    <option value="katun">Katun Percas</option>
                    <option value="denim">Denim / Jeans</option>
                    <option value="perca">Campuran / Perca</option>
                </select>
            </div>
            <div>
                <label class="flabel">Perkiraan Berat (Kg)</label>
                <input class="finput" type="number" step="0.1" name="berat" required placeholder="0.0" />
            </div>
        </div>
        <div class="fg">
            <label class="flabel">Deskripsi Singkat &amp; Kondisi</label>
            <textarea class="ftextarea" name="deskripsi" rows="3" placeholder="Contoh: Bersih, kering, sisa potongan konveksi rumahan..."></textarea>
        </div>
        <div class="fg">
            <label class="flabel">Lokasi Penjemputan</label>
            <input class="finput" name="lokasi" value="Kec. Adiwerna, Tegal" readonly style="background:#f9f9f9; color:var(--muted);" />
        </div>
        <button type="submit" class="post-btn"><i class="ti ti-send"></i> Posting Sekarang</button>
    </form>
</div>