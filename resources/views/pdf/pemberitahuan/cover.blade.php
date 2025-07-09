<div>
    <div class="cover" style="height: 100%;">
    
    <div class="text-center"><div class="img">
        <img src="batang.png" alt="batang" style="width: 2.5cm; height:3cm;">
        <img src="garuda.png" alt="garuda" style="width: 2.8cm; height:auto;">
    </div>

        <h1 style="text-align: left; vertical-align:80%">DOKUMEN</h1>
        <h3 style="text-align: left; margin:0px;">PENGADAAN BARANG DAN JASA</h3>
        <h3 style="text-align: left">KEGIATAN {{strToUpper($kegiatan->kegiatan)}}</h3>
    </div>
    <div class="footer ">
        <h3 style="color: #ffff">DESA {{ strToUpper(Auth::user()->desa) }}</h3>
        <h3 style="color: #ffff">KECAMATAN {{ strToUpper(Auth::user()->kecamatan) }}</h3>
        <h3 style="color: #ffff">KABUPATEN BATANG</h3>
        <h3 style="color: #ffff">TAHUN {{ strToUpper(Auth::user()->tahun_anggaran) }}</h3>
    </div>
</div> 