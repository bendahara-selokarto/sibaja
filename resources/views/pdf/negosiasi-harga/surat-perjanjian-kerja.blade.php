<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Kerja</title>    
    </style>
</head>
<body style="margin: 0px 20px 20px 80px ;">
    <x-kop-desa></x-kop-desa>

<h2 style="text-align: center; margin-bottom: 0px">SURAT PERJANJIAN KERJA</h2>
<p style="margin-top: 0px; text-align:center">NOMOR : ..... / SPjK/{{ Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }}
    <br>TANGGAL : {{tanggal_indo($data['negosiasiHarga']->tgl_perjanjian)}}</p>

<p>Pada hari ini {{$data['negosiasiHarga']->tgl_perjanjian->isoFormat('dddd')}} tanggal {{ucwords(Terbilang::make($data['negosiasiHarga']->tgl_perjanjian->isoFormat('D')))}} bulan {{$data['negosiasiHarga']->tgl_perjanjian->isoFormat('MMM')}} tahun {{Auth::user()->tahun_anggaran}} Kami yang bertanda tangan di bawah ini:</p>

<ol type="1">
    <li>
        Nama: …………………….<br>
        Jabatan: Pelaksana Kegiatan Anggaran<br>
        Alamat: …………………….<br>
        <em>Selanjutnya disebut PIHAK PERTAMA</em>
    </li>
    <li>
        Nama: …………………….<br>
        Jabatan: …………………….<br>
        Alamat: …………………….<br>
        <em>Selanjutnya disebut PIHAK KEDUA</em>
    </li>
</ol>

<div class="pasal">Pasal 1 – TUGAS PEKERJAAN</div>
<p>PIHAK PERTAMA memberikan pekerjaan kepada PIHAK KEDUA dan PIHAK KEDUA menerima dan menyatakan bersedia melaksanakan pekerjaan:</p>
<ul>
    <li>Jenis pekerjaan: ……………………. </li>
    <li>Lokasi pekerjaan: ……………………. </li>
</ul>

<div class="pasal">Pasal 2 – NILAI PEKERJAAN</div>
<p>Nilai pekerjaan yang disepakati oleh kedua pihak sebesar:</p>

<table style="width: 100%">
    <thead>
        <tr>
            <th style="border-color:1px solid black">No</th>
            <th style="border-color:1px solid black">Jenis Pekerjaan</th>
            <th style="border-color:1px solid black">Banyaknya</th>
            <th style="border-color:1px solid black">Satuan (Rp)</th>
            <th style="border-color:1px solid black">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="5" style="height: 100px;"></td></tr>
        <tr>
            <td colspan="4"><strong>Jumlah</strong></td>
            <td>Rp ………………….</td>
        </tr>
    </tbody>
</table>

<div class="pasal">Pasal 3 – JANGKA WAKTU PELAKSANAAN</div>
<p>Jangka waktu pelaksanaan adalah selama ............. (................) hari kalender sejak tanggal …………………., dan harus diselesaikan paling lambat tanggal ………………….</p>

<div class="pasal">Pasal 4 – SERAH TERIMA PEKERJAAN</div>
<ol type="1">
    <li>Setelah pekerjaan selesai 100%, penyedia barang mengajukan penyerahan kepada PKA secara tertulis.</li>
    <li>Pejabat pemeriksa melakukan pemeriksaan maksimal 7 hari setelah pengajuan.</li>
</ol>
<p>Jika terdapat kekeliruan saat serah terima, PIHAK KEDUA wajib memperbaiki sesuai dokumen penunjukan langsung.</p>

<div class="pasal">Pasal 5 – CARA PEMBAYARAN</div>
<ol type="1">
    <li>Pembayaran 100% dilakukan setelah pekerjaan selesai dan dilampiri berita acara.</li>
    <li>Pembayaran dilakukan melalui DPA Desa ………… untuk belanja modal pengadaan ……………. sebesar Rp ………………. ( ………………. ) dipotong pajak sesuai ketentuan.</li>
</ol>

<div class="pasal">Pasal 6 – HAK DAN KEWAJIBAN</div>
<p><strong>Tim Pengelola Kegiatan:</strong></p>
<ul>
    <li>Mengawasi pekerjaan.</li>
    <li>Meminta laporan periodik.</li>
    <li>Menangguhkan pembayaran & mengenakan denda.</li>
    <li>Memberikan instruksi & membayar nilai SPK.</li>
</ul>

<p><strong>Penyedia Barang:</strong></p>
<ul>
    <li>Menerima pembayaran sesuai nilai SPK.</li>
    <li>Melaksanakan pekerjaan tepat waktu dan melaporkan secara periodik.</li>
    <li>Menyerahkan hasil pekerjaan sesuai jadwal.</li>
</ul>

<div class="pasal">Pasal 7 – SANKSI DAN DENDA</div>
<ol type="1">
    <li>Denda dikenakan jika terjadi keterlambatan pekerjaan.</li>
    <li>Besarnya denda: 1/1000 dari nilai SPK per hari keterlambatan.</li>
</ol>

<div class="pasal">Pasal 8 – KEADAAN KAHAR (FORCE MAJEURE)</div>
<ul>
    <li>Termasuk: bencana, peraturan pemerintah, huru-hara, dsb.</li>
    <li>Penyedia wajib melapor dalam 14 hari sejak kejadian dengan bukti otoritas.</li>
    <li>Tanpa laporan, keterlambatan tidak dianggap force majeure.</li>
    <li>Permintaan perpanjangan harus dijawab dalam 7 hari oleh Tim.</li>
</ul>

<div class="pasal">Pasal 9 – PENGHENTIAN DAN PEMUTUSAN SPK</div>
<ol type="1">
    <li>SPK dihentikan jika pekerjaan selesai atau karena force majeure.</li>
    <li>SPK diputus jika penyedia cidera janji, kolusi, atau korupsi.</li>
</ol>

<div class="pasal">Pasal 10 – PENYELESAIAN PERSELISIHAN</div>
<ol type="1">
    <li>Diselesaikan secara musyawarah.</li>
    <li>Jika gagal, maka diselesaikan melalui Pengadilan Negeri Tanah Paser.</li>
</ol>

<div class="pasal">Pasal 11 – KETENTUAN LAIN-LAIN</div>
<ol type="1">
    <li>Biaya administrasi dan materai ditanggung PIHAK KEDUA.</li>
    <li>SPK dibuat 4 rangkap, 2 asli bermaterai.</li>
</ol>

<div class="pasal">Pasal 12 – PENUTUP</div>
<p>Demikian Surat Perintah Kerja ini dibuat dan ditandatangani pada tanggal yang telah ditetapkan untuk dipergunakan sebagaimana mestinya.</p>

<table class="signature">
    <tr>
        <td>
            PIHAK KEDUA<br><br><br>
            (TOKO/UD/CV ....................)<br><br><br>
            ..................................................
        </td>
        <td>
            PIHAK KESATU<br>PKA<br><br><br><br>
            ..................................................
        </td>
    </tr>
</table>

</body>
</html>
