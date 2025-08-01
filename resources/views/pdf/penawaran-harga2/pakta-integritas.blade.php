<h3 style="text-align: center; text-decoation:underline">PAKTA INTEGRITAS</h3>
    <P>Saya yang bertanda tangan dibawah ini :</P>
    <table style="line-height: 1.8;">
        <tr>
            <td>Nama</td>
            <td>: {{ $penyedia2->nama_pemilik }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ $penyedia2->jabata_pemilik }} {{ $penyedia2->nama_penyedia}}</td>
        </tr>
        <tr>
            <td>No.Identitas(KTP/SIM)</td>
            <td>: {{ $penyedia2->nomor_identitas}}</td>
        </tr>
        
        <tr>
            <td>Bertindak untuk</td>
            <td>: Calon Pemasok Material Desa {{ Auth::user()->desa }}</td>
        </tr>
    </table>
    <div style="line-height: 1.8;">

    <p style="text-align:justify">Dalam rangka pelaksanaan Pekerjaan <i>Pengadaan Bahan Material untuk </i> paket pekerjaan <strong> {{ $pemberitahuan->kegiatan->kegiatan}}</strong>, dengan ini menyatakan bahwa saya:</p>
        <ol>
            <li>Tidak Akan melakukan praktek Korupsi, Kolusi dan Nepotisme (KKN);</li>
            <li>Akan melaporkan kepada Inspektorat kab. Batang  dan /atau Intansi yang berwenang apabila mengetahui ada indikasi KKN di dalam proses pengadaan ini;</li>
            <li>Akan mengikuti proses pengadaan secara bersih, transparan, dan profesional untuk memberikan hasil kerja terbaik sesuai ketentuan peraturan perundang-undangan;</li>
            <li>Apabila Melanggar hal-hal yang dinyatakan dalam PAKTA INTEGRITAS ini, bersedia menerima  sanksi administratif, menerima sanksi pencantuman dalam Daftar Hitam, digugat secara perdata dan/atau dilaporkan secara pidana.  </li>
        </ol>
        
    </div>
    <div style="text-align: center; float: right; width: 40%;">
        <table style="width: 100%">
            <tr>
                <td ><br></td>
                <td style="text-align: center;">{{ ucwords($penyedia2->kabupaten) }}, {{ Illuminate\Support\Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan)->modify('+1 days')->isoFormat('D MMMM Y') }} <br>
                {{ $penyedia2->jabata_pemilik ." " .$penyedia2->nama_penyedia }}
                <br><br><br><br><i>materai</i><br><br><br>
                <strong> {{ $penyedia2->nama_pemilik }} </strong> </td>
            </tr>
    </div>
        </table>