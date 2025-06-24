<h3 style="text-align: center; text-decoration:underline">FORM ISIAN PENGADAAN BARANG/JASA</h3> 
    <p>Saya yang bertanda tangan di bawah ini :</p>
    <table>
        <tr>
            <td>Nama</td>
            <td>: {{ $penyedia1->nama_pemilik }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: {{ $penyedia1->jabata_pemilik }}  {{ $penyedia1->nama_penyedia }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
        <td>: {{ $penyedia1->alamat_pemilik }}</td>
        </tr>
        <tr>
            <td>No. Telepon/HP</td>
            <td>: {{ $penyedia1->nomor_hp }}</td>
        </tr>
        <tr>
            <td>No. Identitas</td>
            <td>: {{ $penyedia1->nomor_identitas }}</td>
        </tr>
    </table>
    <p>Menyatakan dengan sesungguhnya bahwa :</p>
    <ol>
        <li>Saya secara hukum mempunyai kapasitas menandatangani kontrak ;</li>
        <li>Saya bukan sebagai pegawai K/L.D/I</li>
        <li>Saya tidak sedang menjalani sanksi pidana;</li>
        <li>Saya tidak sedang dan tidak akan terlibat pertentangan kepentingan dengan para pihak yang terkait, langung maupun tidak langsung dalam proses pengadaan ini;</li>
        <li>Saya tidak masuk dalam daftar hitam, tidak dalam pengawasan pengadilan, tidak pailit atau kegiatan usaha saya tidak sedang dihentikan;</li>
        <li>Data-data saya adalah sebagai berikut :</li>
    </ol>
    <ol type="A">
        <strong><li>Data Administrasi</li></strong>
        <table style="border: 1px solid black; width: 100%">
            <tr>
                <td style="width: 5mm">1.</td>
                <td style="width: 5cm">Nama</td>
                <td style="width: 5mm">:</td>
                <td>{{ $penyedia1->nama_pemilik }}</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Pekerjaan</td>
                <td>:</td>
                <td> {{ $penyedia1->jabata_pemilik }} {{ $penyedia1->nama_penyedia }}</td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td> {{ $penyedia1->alamat_pemilik }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Nomor Telepon/HP</td>
                <td>:</td>
                <td>{{ $penyedia1->nomor_hp }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Fax</td>
                <td>:</td>
                <td> -</td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Alamat Kantor</td>
                <td>:</td>
                <td>{{ $penyedia1->alamat_penyedia }}</td>
            </tr>
            <tr>
                <td>5.</td>
                <td>Nomor Telepon</td>
                <td>:</td>
                <td>{{ $penyedia1->nomor_hp }}</td>
            </tr>
            <tr>
                <td>6.</td>
                <td>Nomor Identitas (KTP/SIM/Pasport)</td>
                <td>:</td>
                <td>{{ $penyedia1->nomor_identitas }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td></td>
            </tr>
        </table>
        <strong><li>Surat Izin Usaha/melaksanakan kegiatan (apabila dipersyaratkan)</li></strong>
    
    <table style="border: 1px solid black; width: 100%">
        <tr>
            <td style="width: 5mm">1.</td>
            <td style="width: 4cm">No. Surat Izin Usaha</td>
            <td style="width: 5cm">: {{ $penyedia1->nomor_izin_usaha }} </td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Instansi Pemberi Izin Usaha</td>
            <td>: {{ $penyedia1->instansi_pemberi_izin_usaha }} </td>
        </tr>
    </table>
</ol>
<table style="width: 100%">
    <tr>
        <td style="width: 8cm"><br></td>
        <td style="text-align: center;">{{$penyedia1->nama_penyedia}}
            <br><br><br><br>
            <strong style="text-decoration: underline">{{ $penyedia1->nama_pemilik}}</strong></td>
    </tr>
</table>