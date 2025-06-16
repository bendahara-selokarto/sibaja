<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<h3 style="text-align: center; margin-bottom: 1pt; text-decoration: underline;">PERJANJIAN</h3>
<p style="margin-top: 0pt; text-align: center;">Nomor : {{ $pemberitahuan->no_perjanjian }}</p>

<p style="text-align: justify">
    Pada hari ini,  <strong>{{ $negosiasiHarga->tgl_perjanjian->dayName}} </strong> 
    tanggal  <strong>{{ Terbilang::make($negosiasiHarga->tgl_perjanjian->isoFormat('D'));}} </strong> 
    bulan <strong>{{ $negosiasiHarga->tgl_perjanjian->isoFormat('MMMM');}} </strong> 
    tahun <strong> {{ Terbilang::make($negosiasiHarga->tgl_perjanjian->isoFormat('Y'));}} </strong> 
    bertempat di Kantor Kepala Desa {{ ucwords(Auth::user()->desa)  }},
    kami yang bertanda tangan di bawah ini:</p>
<table style="vertical-align: top;">
    <tr>
        <td style="vertical-align: top; width: 1cm;" rowspan="3">1.</td>
        <td style="vertical-align: top; width: 2.5cm;">Nama</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;"><strong>{{ $kegiatan->pka }} </strong>  </td>
    </tr>
    <tr>
        <td style="vertical-align: top;">Jabatan</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;">PKA  </td>
    </tr>
    <tr>
        <td style="vertical-align: top;">Alamat</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;">  Ds. {{ ucwords(Auth::user()->desa) }} Kec. {{ ucwords(Auth::user()->kecamatan) }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3">Selanjutnya disebut <strong>PIHAK PERTAMA</strong></td> 
        <br><br>
    </tr>
    <tr>
        <td style="vertical-align: top;" rowspan="3">2.</td>
        <td style="vertical-align: top;">Nama</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;"> <strong>{{ $penyedia->nama_pemilik }} </strong></td>
    </tr>
    <tr>
        <td style="vertical-align: top;">Jabatan</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;"> {{ $penyedia->jabata_pemilik . ' ( '.  $penyedia->nama_penyedia . ' )' }}</td>
    </tr>
    <tr>
        <td style="vertical-align: top;">Alamat</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;"> {{ $penyedia->alamat_pemilik }}  </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3">Selanjutnya disebut <strong>PIHAK KEDUA</strong></td>
    </tr>

</table>
<p style="text-align: justify">Untuk Selanjutnya PIHAK PERTAMA dan PIHAK KEDUA disebut PARA PIHAK</p>

<p>Bahwa PARA PIHAK telah sepakat dan setuju untuk mengadakan perjanjian, dengan ketentuan sebagai berikut :</p>

<p style="text-align: center">Pasal 1</p>
<p style="text-align: center">RUANG LINGKUP PEKERJAAN</p>

<p style="text-align: justify">Ruang lingkup pekerjaan dalam perjanjian ini adalah pengadaan material untuk pekerjaan <strong>{{ $kegiatan->kegiatan }}</strong></p>

<p style="text-align: center">Pasal 2</p>
<p style="text-align: center">NILAI PEKERJAAN</p>

<p style="text-align: justify">Nilai yang disepakati untuk menyelesaikan pekerjaan ( Menyediakan Material ) dalam perjanjian ini adalah ( Daftar Rincian Harga terlampir ) termasuk pajak dan bea materai.</p>

<p style="text-align: center">Pasal 3</p>
<p style="text-align: center">HAK DAN KEWAJIBAN</p>
<ol>
    <li>
        <p style="text-align: justify">PIHAK PERTAMA berhak menerima hasil pekerjaan tepat pada waktunya,</p>
    </li>
    <li>
        <p style="text-align: justify">PIHAK PERTAMA berkewajiban membayar biaya penyelesaian pekerjaan sebagaimana dimaksud dalam Pasal 2,</p>
    </li>
    <li>
        <p style="text-align: justify">PIHAK KEDUA berhak atas pembayaran untuk penyelesaian pekerjaan sebagaimana dimaksud dalam Pasal 2,</p>
    </li>
    <li>
        <p style="text-align: justify">PIHAK KEDUA berkewajiban menyerahkan hasil pekerjaan tepat pada waktunya.</p>
    </li>
</ol>
<p style="text-align: center">Pasal 4</p>
<p style="text-align: center">JANGKA WAKTU PELAKSANAAN PEKERJAAN</p>

<p style="text-align: justify">Jangka waktu untuk menyelesaikan pekerjaan dalam penyediaan material adalah <strong> {{ $negosiasiHarga->jumlah_hari_kerja }} ( {{ Terbilang::make($negosiasiHarga->jumlah_hari_kerja) }} ) </strong> hari kerja mulai tanggal <strong>{{ $negosiasiHarga->tgl_perjanjian->isoFormat('D MMMM Y') }}</strong> sampai dengan tanggal <strong>{{ $negosiasiHarga->tgl_akhir_perjanjian->isoFormat('D MMMM Y') }} </strong> sehingga pekerjaan harus selesai dan diserahkan pada tanggal <strong> {{ $negosiasiHarga->tgl_akhir_perjanjian->isoFormat('D MMMM Y')  }}</strong>.</p>
<br><br><br>
<p style="text-align: center">Pasal 5</p>
<p style="text-align: center">FORCE MAJEURE</p>
<ol>
    <li>
        <p style="text-align: justify">Yang dimaksud <span style='font-style: italic;'>Force Majeure</span> adalah suatu keadaan yanag terjadi diluar kemampuan PARA PIHAK yang tidak dapat diperhitungkan sebelumnya.</p>
    </li>
    <li>
        <p style="text-align: justify">Apabila terjadi keadaan  <span style='font-style: italic;'>Force Majeure</span> sebagaimana dimaksud Ayat (1) Pasal ini maka PARA PIHAK terbebas dari kewajiban yang harus dilaksanakan.</p>
    </li>
</ol>

<p style="text-align: center">Pasal 6</p>
<p style="text-align: center">SANKSI</p>
<p style="text-align: justify">Apabila pekerjaan melebihi batas waktu yang disepakati maka PIHAK KEDUA harus membayar denda sebesar 10% dari nilai pekerjaan dengan nominal pengadaan material dari jumlah total yang akan dikirim sebesar Rp{{ number_format($penawaranHarga->harga_penawaran_1, 0, ',', '.') }}. ( {{ Terbilang::make($penawaranHarga->harga_penawaran_1) }}) rupiah.</p>

<p style="text-align: center">Pasal 7</p>
<p style="text-align: center">KETENTUAN PENUTUP</p>
<p style="text-align: justify">Perjanjian ini dibuat rangkap 2 (dua) masing-masing bermeterai cukup dan mempunyai kekuatan hukum yang sama untuk dipertanggungjawabkan sesuai peraturan perundang-undangan yang berlaku.</p>

<table style="width: 100%; text-align: center">
    <tr>
        <td style="vertical-align: top;">PIHAK KEDUA <br> {{ $penyedia->jabata_pemilik }} ({{ $penyedia->nama_penyedia }}) </td>
        <td style="vertical-align: top;"><br>PIHAK PERTAMA <br> <br> <br> <br>    </td>
    </tr>
    <tr>
        <td><strong style="text-decoration: underline">{{ strToUpper($penyedia->nama_pemilik) }}</strong></td>
        <td><strong style="text-decoration: underline">{{ strToUpper($kegiatan->pka) }}</strong></td>
    </tr>
    <tr>
        <td style="vertical-align: top;" colspan="2">
            Mengetahui,     <br>
            Kepala Desa {{ Auth::user()->desa }}   <br>
            Selaku <br>
            Pemegang Kekuasaan Keuangan Desa <br>
            <br>
            <br>
            <br>
            <strong style="text-decoration: underline">{{  strToUpper(Auth::user()->kepala_desa) }}</strong>
        </td>
    </tr>
</table>

</body>
</html>