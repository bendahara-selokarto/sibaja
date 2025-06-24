<h3 style="text-align: center; text-decoation:underline">PAKTA INTEGRITAS</h3>
<br>
<br>
    <P>Saya yang bertanda tangan dibawah ini :</P>
    <table style="line-height: 1.8;">
        <tr>
            <td>Nama</td>
            <td>: {{ $penyedia1->nama_pemilik }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ $penyedia1->jabata_pemilik }} {{ $penyedia1->nama_penyedia}}</td>
        </tr>
        <tr>
            <td>No.Identitas(KTP/SIM)</td>
            <td>: {{ $penyedia1->nomor_identitas}}</td>
        </tr>
        
        <tr>
            <td>Bertindak untuk</td>
            <td>: Calon Pemasok Material Desa {{ Auth::user()->desa }}</td>
        </tr>
    </table>
    <div style="line-height: 1.8;">
    <p>Dalam rangka pelaksanaan Pekerjaan Pengadaan Material untuk paket pekerjaan <strong> {{ $pemberitahuan->kegiatan->kegiatan}}</strong>, dengan ini menyatakan bahwa saya:</p>
        <ol>
            <li>Tidak Akan melakukan praktek Korupsi, Kolusi dan Nepotisme (KKN);</li>
            <li>Akan melaporkan kepada Inspektorat kab. Batang  dan /atau Intansi yang berwenang apabila mengetahui ada indikasi KKN di dalam proses pengadaan ini;</li>
            <li>Akan mengikuti proses pengadaan secara bersih, transparan, dan profesional untuk memberikan hasil kerja terbaik sesuai ketentuan peraturan perundang-undangan;</li>
            <li>Apabila Melanggar hal-hal yang dinyatakan dalam PAKTA INTEGRITAS ini, bersedia menerima  sanksi administratif, menerima sanksi pencantuman dalam Daftar Hitam, digugat secara perdata dan/atau dilaporkan secara pidana.  </li>
        </ol>
        </div>
        <div style="text-align: center; float: right; width: 40%;">
            <table style="width: 100%;">
                <tr>
                    <td ><br></td>
                    <td></td>
                    <td style="text-align: center; argin-right: 40px;">
                        {{ ucwords(Auth::user()->desa) }}, {{ Illuminate\Support\Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan)->modify('+1 days')->isoFormat('D MMMM Y') }} <br>
                        {{ $penyedia1->nama_penyedia }}
                        <br><br><br><br>
                        <strong> {{ $penyedia1->nama_pemilik }} </strong>
                    </td>
                </tr>
            </table>
        </div>