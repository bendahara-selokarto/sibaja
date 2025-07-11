<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Kerja</title>    
    <style>
        .pasal {
            text-align: center;
        }
        table   {
            border-collapse: collapse;
        }
    </style>
</head>
<body style="margin: 0px 20px 20px 80px ;">
    <x-kop-desa></x-kop-desa>

<h2 style="text-align: center; margin-bottom: 0px">SURAT PERJANJIAN KERJA</h2>
<p style="margin-top: 0px; text-align:center">NOMOR : ..... / SPjK/{{ Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }}
    <br>TANGGAL : {{tanggal_indo($data['negosiasiHarga']->tgl_perjanjian)}}</p>

<p>Pada hari ini {{$data['negosiasiHarga']->tgl_perjanjian->isoFormat('dddd')}} tanggal {{ucwords(Terbilang::make($data['negosiasiHarga']->tgl_perjanjian->isoFormat('D')))}} bulan {{$data['negosiasiHarga']->tgl_perjanjian->isoFormat('MMMM')}} tahun {{Auth::user()->tahun_anggaran}} Kami yang bertanda tangan di bawah ini:</p>
<table table style="width: 100%">
    <tr>
        <td rowspan="4" style="vertical-align: top">1.</td>
        <td style="width:30mm">Nama</td>
        <td style="width: 4pt">: </td>
        <td>{{ $data['kegiatan']->pka}} </td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>: </td>
        <td>Pelaksana Kegiatan Angaran </td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: </td>
        <td>Desa {{Auth::user()->desa }} Kec. Pecalungan</td>
    </tr>
    <tr>        
        <td colspan="4">
                <em>Selanjutnya dalam hal ini disebut PIHAK PERTAMA </em>
        </td>
    </tr>
</table>
<br><br>
<table style="width: 100%">
    <tr>
        <td rowspan="4" style="vertical-align: top">2.</td>
        <td style="width:30mm">Nama</td>
        <td style="width: 4pt">: </td>
        <td>{{ $data['penyedia']->nama_pemilik}}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>: </td>
        <td>{{ $data['penyedia']->jabata_pemilik}} {{$data['penyedia']->nama_penyedia}}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: </td>
        <td>{{$data['penyedia']->alamat_pemilik}}</td>
    </tr>
    <tr>        
        <td colspan="4">
            <em>Selanjutnya dalam hal ini disebut PIHAK KEDUA </em>
        </td>
    </tr>
</table>
<br><br>

<div class="pasal">Pasal 1 <br>TUGAS PEKERJAAN</div>
<p>PIHAK PERTAMA memberikan pekerjaan kepada PIHAK KEDUA dan PIHAK KEDUA menerima dan menyatakan bersedia, setuju dan sanggup untuk melaksanakan pekerjaan:</p>
<table>
    <tr>
        <td>1. Jenis Pekerjaan</td>
        <td>: Pengadaan Material</td>
    </tr>
    <tr>
        <td>2. Lokasi Pekerjaan</td>
        <td>: Desa {{Auth::user()->desa}}</td>
    </tr>
</table>
<br>
<div class="pasal">Pasal 2 <br> NILAI PEKERJAAN</div>
<p>Nilai pekerjaan yang disepakati oleh kedua pihak sebesar:</p>

<table style="width: 100%; padding: 5px">
    <thead>
        <tr>
            <th style="border:1px solid black; width:5%">No</th>
            <th style="border:1px solid black; width:35%">Jenis Pekerjaan <br> Yang Dikerjakan</th>
            <th style="border:1px solid black; width:20%">Banyaknya</th>
            <th style="border:1px solid black; width:20%">Harga Satuan <br>(Rp)</th>
            <th style="border:1px solid black; width:20%">Jumlah <br>(Rp)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid black; vertical-align:top; text-align:center">1</td>
            <td style="border: 1px solid black; vertical-align:top">Pengadaan Material Kegiatan {{$data['kegiatan']->kegiatan}}</td>
            <td style="border: 1px solid black; vertical-align:top">1 paket</td>
            <td style="border: 1px solid black; vertical-align:top; text-align:right">{{(formatNumber(round($data['negosiasiHarga']->jumlah_total, -2)))}}</td>
            <td style="border: 1px solid black; vertical-align:top; text-align:right">{{(formatNumber(round($data['negosiasiHarga']->jumlah_total, -2)))}}</td>
        </tr>
        <tr>
            <td colspan="4" style="border: 1px solid black; vertical-align:top; text-align:right"><strong>Jumlah</strong></td>
            <td style="border: 1px solid black; vertical-align:top; text-align:right"><strong>{{(formatNumber(round($data['negosiasiHarga']->jumlah_total, -2)))}}</strong></td>
        </tr>       
    </tbody>
</table>
<br><br>
<div class="pasal">Pasal 3 <br> JANGKA WAKTU PELAKSANAAN</div>
<p>Jangka waktu pelaksanaan adalah selama {{ $data['negosiasiHarga']->jumlah_hari_kerja }} ( {{Terbilang::make($data['negosiasiHarga']->jumlah_hari_kerja)}} ) hari kalender sejak tanggal {{ tanggal_indo($data['negosiasiHarga']->tgl_perjanjian) }} dan harus diselesaikan paling lambat tanggal {{ tanggal_indo($data['negosiasiHarga']->tgl_akhir_perjanjian) }}.</p>

<div class="pasal">Pasal 4 <br> SERAH TERIMA PEKERJAAN</div>
<ol type="1">
    <li>Setelah pekerjaan selesai 100% (seratus per seratus), penyedia barang mengajukan penyerahan barang secara tertulis kepada Pelaksana Kegiatan Anggaran.</li>
    <li>Pejabat pemeriksa hasil pekerjaan melakukan pemeriksaan terhadap hasil pekerjaan yang telah diselesaikan oleh penyedia barang selambat-lambatnya 7 (tujuh) hari setelah diterimanya surat permintaan dari penyedia barang. Selanjutnya dibuat berita acara pemeriksaan barang.</li>
    <li>Apabila pada waktu serah terima barang dimaksud terdapat kekeliruan, tidak sesuai dan lain sebagainya, maka pihak kedua bersedia untuk memperbaiki sesuai dalam perencanaan yang tertuang dalam dokumen penunjukkan langsung.</li>
</ol>

<div class="pasal">Pasal 5 <br> CARA PEMBAYARAN</div>
<ol type="1">
    <li>Pembayaran 100% dilakukan setelah pekerjaan selesai dan dilampiri berita acara.</li>
    <li>Pembayaran dilakukan melalui DPA Desa {{Auth::user()->desa}} untuk belanja modal pengadaan Material kegiatan {{$data['kegiatan']->kegiatan}} kode rekening belanja {{$data['kegiatan']->rekening_apbdes}} secara Non Tunai / Transfer / CMS sejumlah nilai dalam kontrak sebesar Rp {{formatNumber(round($data['negosiasiHarga']->jumlah_total, -2))}} ( {{Terbilang::make(round($data['negosiasiHarga']->jumlah_total, -2))}} rupiah) dipotong pajak sesuai ketentuan Pemerintah.</li>
</ol>

<div class="pasal">Pasal 6 <br> HAK DAN KEWAJIBAN</div>
<ol>
    <li>Hak dan Kewajiban Tim Pelaksana Kegiatan, sebagai berikut <br>
        <ul type="a">
            <li>Mengawasi pekerjaan yang dilaksanakan oleh penyedia barang.</li>
            <li>Meminta laporan secara periodik mengenai pelaksanaan pekerjaan yang dilakukan oleh penyedia barang.</li>
            <li>Menangguhkan pembayaran </li>
            <li>Mengenakan denda keterlambatan.</li>
            <li>Membayar nilai SPK kepada penyedia barang. </li> 
            <li>Memberikan instruksi sesuai jadwal.</li>
        </ul>    
    </li>
    <li>Hak dan Kewajiban Penyedia Barang, sebagai berikut: <br>
        <ul type="a">
            <li>Menerima pembayaran sesuai nilai SPK.</li>
            <li>Menerima pembayaran ganti rugi / kompensasi (bila ada).</li>
            <li>Melaksanakan dan menyelesaikan pekerjaan sesuai dengan jadwal pelaksanaan pekerjaan yang telah ditetapkan dalam kontrak.</li>
            <li>Melaporan pelaksanaan pekerjaan secara periodik kepada Tim Pelaksana Kegiatan</li>
            <li>Menyerahkan hasil pekerjaan sesuai jadwal penyerahan yang telah ditetapkan dalam SPK.</li>
        </ul>
    </li>
</ol>

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
