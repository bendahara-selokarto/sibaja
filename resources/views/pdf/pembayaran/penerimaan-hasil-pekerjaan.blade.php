<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Penerimaan Hasil Pekerjaan</title>
</head>
<body style="font-family: serif; line-height: 1.6; font-size: 14px; margin: 40px 40px 40px 100px;">

    <h2 style="text-align: center; text-transform: uppercase;">BERITA ACARA PENERIMAAN HASIL PEKERJAAN</h2>
    <p style="text-align: center;">NOMOR {{$pemberitahuan->no_pbj}}/PHP/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran}}</p>

    <p style="text-align: justify">Pada hari ini .... tanggal ……….. bulan ………… tahun {{Terbilang::make(Auth::user()->tahun_anggaran)}} bertempat di Kantor Kepala Desa {{Auth::user()->desa}}, telah dilaksanakan pembayaran atas pekerjaan pengadaan material {{$kegiatan->kegiatan}} antara :</p>

    <p style="text-align: justify"><strong>I. PIHAK PERTAMA</strong></p>
    <table style="margin-left: 20px;">
        <tr>
            <td style="width: 120px;">Nama</td>
            <td>: {{$kegiatan->pka}}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: Pelaksana Kegiatan Anggaran</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: Desa {{ Auth::user()->desa }} Kec. Pecalungan</td>
        </tr>
    </table>

    <p style="text-align: justify"><strong>II. PIHAK KEDUA</strong></p>
    <table style="margin-left: 20px;">
        <tr>
            <td style="width: 120px;">Nama</td>
            <td>: {{$penyedia->nama_pemilik}}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{$penyedia->jabata_pemilik}} {{$penyedia->nama_penyedia}}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{$penyedia->alamat_pemilik}}</td>
        </tr>
    </table>

    <p style="text-align: justify">PIHAK PERTAMA menyatakan bahwa telah menerima hasil pekerjaan berupa Material kegiatan {{$kegiatan->kegiatan }} dalam keadaan baik dari PIHAK KEDUA sesuai dengan Surat Perjanjian Nomor: …………………  tertanggal ……………….</p>

    <p style="text-align: justify">PIHAK KEDUA telah menyerahkan hasil pekerjaan berupa Material kegiatan {{$kegiatan->kegiatan }} dalam keadaan baik kepada PIHAK PERTAMA sesuai dengan Surat Perjanjian Nomor ……… tertanggal ……………….</p>

    <p style="text-align: justify">Demikian Berita Acara ini dibuat rangkap 2 (dua) masing-masing bermeterai cukup dan mempunyai kekuatan hukum yang sama untuk dipertanggungjawabkan sesuai peraturan perundang-undangan yang berlaku.</p>

    <br><br>

    <table style="width: 100%; text-align: center;">
        <tr>
            <td>{{$penyedia->jabata_pemilik}} {{$penyedia->nama_penyedia}}</td>
            <td>Pelaksana Kegiatan Anggaran</td>
        </tr>
        <tr>
            <td style="padding-top: 60px;">{{$penyedia->nama_pemilik}}</td>
            <td style="padding-top: 60px;">{{$kegiatan->pka}}</td>
        </tr>
    </table>

    <br><br>
    <div style="text-align: center; line-height:1">
        Mengetahui,<br>
        Kepala Desa {{Auth::user()->desa}}<br>
        Selaku Pemegang Kekuasaan Pengelolaan <br>Keuangan Desa<br>
        <br><br><br><br><br>
        <b>{{Auth::user()->kepala_desa}}</b><br>
    </div>

</body>
</html>
