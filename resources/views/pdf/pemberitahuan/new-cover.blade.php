<div class="new-cover" style="position: relative;margin: 0; padding: 0;">

   <div style="width: auto; height: auto; position: absolute; top: 100px; left: 0; right: 0; display: flex; align-items: center; justify-content: center;">
    <img src="{{ public_path('batang.png') }}" alt="batang" style="height: 140px; width: auto;">
    </div>




        <h1 style="margin-top:400px">DOKUMEN <br> PENGADAAN BARANG / JASA</h1>
        <br><br><br>
        
        
        <h1 style="margin: 60px">KEGIATAN {{strToUpper($kegiatan->kegiatan)}}</h1>
        <br><br><br><br><br><br><br><br><br>
        <div style="position: absolute; bottom: 40px; width:100%;">            
            <h2>PELAKSANAAN DANA DESA</h2>
            <h2 >DESA {{ strToUpper(Auth::user()->desa) }}</h2>
            <h2 >KEC. {{ strToUpper(Auth::user()->kecamatan) }} KAB. BATANG</h2>
            <h2>TAHUN ANGGARAN {{ strToUpper(Auth::user()->tahun_anggaran) }}</h2>
        </div>
            
</div> 