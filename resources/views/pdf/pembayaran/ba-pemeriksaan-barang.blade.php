<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="margin: 60px 40px 40px 100px; line-height:1.5">
    <h3 style="text-align: center;text-decoration:underline;">BERITA ACARA PEMERIKSAAN BARANG/PEKERJAAN</h3><br>
    <p>Pada hari ini {{$tgl_invoice->isoFormat('dddd')}} tanggal {{Terbilang::make($tgl_invoice->isoFormat('D'))}} bulan {{$tgl_invoice->isoFormat('MMMM')}} Tahun  {{Terbilang::make(Auth::user()->tahun_anggaran)}}   yang bertanda tangan dibawah ini  :</p>
    <table style="width: 100%">
        <tr>
            <td style="width:6px">1.</td>
            <td style="width: 50px">Nama</td>
            <td>:</td>
            <td>{{$kegiatan->ketua_ tpk}}</td>
            <td style="width:60px">Jabatan</td>
            <td>:</td>
            <td>Ketua</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Nama</td>
            <td>:</td>
            <td>..........</td>
            <td>Jabatan</td>
            <td>:</td>
            <td>Sekretaris</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Nama</td>
            <td>:</td>
            <td>..........</td>
            <td>Jabatan</td>
            <td>:</td>
            <td>Anggota</td>
        </tr>
    </table>

    <p style="text-align: justify">Berdasarkan Surat Keputusan Kepala Desa {{Auth::user()->desa}} Nomor : ………………. tanggal ……………….  Selaku Tim Pelaksana Kegiatan dan telah memeriksa Barang / Pekerjaan dengan teliti sebagai daftar terlampir yang telah diserahkan oleh : {{$penyedia->nama_pemilik ." ".$penyedia->jabata_pemilik ." ".$penyedia->nama_penyedia}}</p>
    <p style="text-align: justify">Berdasarkan Surat Pesanan SPK Nomor : ……………………….. tanggal ...................................</p>
    <p style="text-align: justify">Dengan kesimpulan sebagai berikut :</p>
    <ol type="a">
        <li>Terdapat baik sesuai dengan Surat SPK.</li>
        <li>Kurang / Tidak baik.</li>
    </ol>
    <p style="text-align: justify">Barang yang kondisi baik yang kami beri tanda V yang selanjutnya akan diserahkan oleh rekanan kepada Tim Pengelola Kegiatan sedangkan yang tidak baik telah kami beri tanda X.</p>
    <p style="text-align: justify">Demikian Berita Acara ini dibuat dalam  3 ( tiga ) rangkap untuk dipergunakan sebagai mana mestinya.</p>
    
    <table style="width: 100%">
        <tr>
            <td style="text-align: center">Rekanan</td>
            <td>1</td>
            <td>Nama</td>
            <td> ............. </td>
            <td> 1. .........................  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Jabatan</td>
            <td>: Ketua</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>2</td>
            <td>Nama</td>
            <td> ..... </td>
            <td> 2. ........  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Jabatan</td>
            <td>: Sekretaris</td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 200px; text-align:center"> {{$penyedia->nama_pemilik}} </td>
            <td>3</td>
            <td>Nama</td>
            <td> ..... </td>
            <td> 3. ........  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Jabatan</td>
            <td>: Anggota</td>
            <td></td>
        </tr>
    </table>
    
</body>
</html>
