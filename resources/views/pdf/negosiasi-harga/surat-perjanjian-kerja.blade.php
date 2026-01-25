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
<br>
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
<br>
<div class="pasal">Pasal 1 <br>TUGAS PEKERJAAN</div>
PIHAK PERTAMA memberikan pekerjaan kepada PIHAK KEDUA dan PIHAK KEDUA menerima dan menyatakan bersedia, setuju dan sanggup untuk melaksanakan pekerjaan:
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
Nilai pekerjaan yang disepakati oleh kedua pihak sebesar:

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
            <td style="border: 1px solid black; vertical-align:top; text-align:right">{{(formatNumber(round($data['negosiasiHarga']->harga_total, 0)))}}</td>
            <td style="border: 1px solid black; vertical-align:top; text-align:right">{{(formatNumber(round($data['negosiasiHarga']->harga_total, 0)))}}</td>
        </tr>
        <tr>
            <td colspan="4" style="border: 1px solid black; vertical-align:top; text-align:right"><strong>Jumlah</strong></td>
            <td style="border: 1px solid black; vertical-align:top; text-align:right"><strong>{{(formatNumber(round($data['negosiasiHarga']->harga_total, 0)))}}</strong></td>
        </tr>       
    </tbody>
</table>
<br>
<div class="pasal">Pasal 3 <br> JANGKA WAKTU PELAKSANAAN</div>
Jangka waktu pelaksanaan adalah selama {{ $data['negosiasiHarga']->jumlah_hari_kerja }} ( {{Terbilang::make($data['negosiasiHarga']->jumlah_hari_kerja)}} ) hari kalender sejak tanggal {{ tanggal_indo($data['negosiasiHarga']->tgl_perjanjian) }} dan harus diselesaikan paling lambat tanggal {{ tanggal_indo($data['negosiasiHarga']->tgl_akhir_perjanjian) }}.
<br><br>
<div class="pasal">Pasal 4 <br> SERAH TERIMA PEKERJAAN</div>
<ol style="text-align: justify;margin-top:0px" type="1">
    <li>Setelah pekerjaan selesai 100% (seratus per seratus), penyedia barang mengajukan penyerahan barang secara tertulis kepada Pelaksana Kegiatan Anggaran.</li>
    <li>Pejabat pemeriksa hasil pekerjaan melakukan pemeriksaan terhadap hasil pekerjaan yang telah diselesaikan oleh penyedia barang selambat-lambatnya 7 (tujuh) hari setelah diterimanya surat permintaan dari penyedia barang. Selanjutnya dibuat berita acara pemeriksaan barang.</li>
    <li>Apabila pada waktu serah terima barang dimaksud terdapat kekeliruan, tidak sesuai dan lain sebagainya, maka pihak kedua bersedia untuk memperbaiki sesuai dalam perencanaan yang tertuang dalam dokumen penunjukkan langsung.</li>
</ol>

<div class="pasal">Pasal 5 <br> CARA PEMBAYARAN</div>
<ol style="text-align: justify;margin-top:0px" type="1">
    <li>Pembayaran 100% dilakukan setelah pekerjaan selesai dan dilampiri berita acara.</li>
    <li>Pembayaran dilakukan melalui DPA Desa {{Auth::user()->desa}} untuk belanja modal pengadaan Material kegiatan {{$data['kegiatan']->kegiatan}} kode rekening belanja {{$data['kegiatan']->rekening_apbdes}} secara Non Tunai / Transfer / CMS sejumlah nilai dalam kontrak sebesar Rp {{formatNumber(round($data['negosiasiHarga']->harga_total, 0))}} ( {{Terbilang::make(round($data['negosiasiHarga']->harga_total, 0))}} rupiah) dipotong pajak sesuai ketentuan Pemerintah.</li>
</ol>

<div class="pasal">Pasal 6 <br> HAK DAN KEWAJIBAN</div>
<ol style="text-align: justify;margin-top:0px">
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

<div class="pasal">Pasal 7 <br> SANKSI DAN DENDA</div>
<ol style="text-align: justify;margin-top:0" type="1">
    <li>Denda adalah sanksi finansial yang dikenakan kepada penyedia barang karena telah melakukan cidera janji.</li>
    <li>Besarnya denda yang harus dibayar penyedia barang atas keterlambatan penyelesaian pekerjaan adalah 1 /1000  (satu perseribu) dari nilai SPK atau bagian kontrak lainnya untuk setiap hari keterlambatan. </li>
</ol>

<div class="pasal">Pasal 8 <br> KEADAAN KAHAR (FORCE MAJEURE)</div>
<ol style="text-align: justify;justify;margin-top:0" type="a" >
    <li>Yang dimaksud dengan keadaan kahar (Force Majeure) adalah kejadian di luar kemampuan penyedia barang untuk mengatasinya  termasuk di dalamnya, tetapi tidak terbatas kejadian-kejadian sebagai akibat dari Peraturan Pemerintah baik Pusat maupun Daerah, Departemen, Instansi Sipil atau Militer, halilintar, banjir, gempa bumi, huru-hara, pemberontakan dan epidemi yang secara langsung dapat mengakibatkan keterlambatan penyerahan pekerjaan. </li>
    <li>Dalam hal terjadinya keadaan kahar (Force Majeure) penyedia barang wajib memberitahukan secara tertulis kepada Pengguna Anggaran, selambat-lambatnya dalam waktu 14 (empat belas) hari kalender terhitung sejak terjadinya  Force Majeure disertai keterangan dari pihak yang berwenang / berwajib. </li>
    <li>Apabila dalam jangka waktu sebagaimana dimaksud pada huruf b di atas penyedia barang tidak memberitahukan kejadian  Force Majeure tersebut kepada Tim Pelaksana Kegiatan, maka keterlambatan penyerahan pekerjaan dianggap bukan sebagai akibat Force Majeure. </li>
    <li>Apabila dalam jangka waktu sebagaimana dimaksud pada huruf b di atas penyedia barang tidak memberitahukan kejadian  Force Majeure tersebut kepada Tim Pelaksana Kegiatan, maka keterlambatan penyerahan pekerjaan dianggap bukan sebagai akibat Force Majeure. </li>
    <li>Tim Pelaksana Kegiatan dalam waktu 7 (tujuh) hari kalender terhitung sejak diterimanya permohonan perpanjangan akan memberikan jawaban mengenai permohonan dimaksud kepada penyedia barang. </li>
    <li>Apabila dalam jangka waktu sebagaimana dimaksud pada angka huruf e di atas Tim Pelaksana Kegiatan tidak memberikan jawaban terhadap permohonan perpanjangan waktu penyerahan pekerjaan dari penyedia barang, maka Tim Pelaksana Kegiatan dianggap telah memberikan persetujuan terhadap permohonan dimaksud. </li>
</ol>

<div class="pasal">Pasal 9 <br> PENGHENTIAN DAN PEMUTUSAN SPK</div>
<ol style="text-align: justify;margin-top:0px" type="1">
    <li>Penghentian SPK dapat dilakukan karena pekerjaan sudah selesai.</li>
    <li>Penghentian SPK dilakukan karena terjadinya keadaan kahar (force majeure), dan dalam hal ini Tim Pengelola Kegiatan wajib membayar pelaksanaan pekerjaan kepada penyedia barang sesuai dengan kemajuan pelaksanaan pekerjaan yang telah dicapai. </li>
    <li>Pemutusan SPK dilakukan apabila penyedia  barang cidera janji atau tidak memenuhi kewajiban dan tanggung jawabnya (wanprestasi) dan kepada penyedia barang dikenakan sanksi sesuai dengan ketentuan peraturan yang berlaku.</li>
    <li>Pemutusan SPK dilakukan bilamana para pihak terbukti melakukan kolusi, kecurangan atau tindak korupsi baik  dalam proses penunjukan langsung maupun pelaksanaan pekerjaan.</li>
</ol>

<div class="pasal">Pasal 10 <br> PENYELESAIAN PERSELISIHAN</div>
<ol style="text-align: justify;margin-top:0px" type="1">
    <li>Jika terjadi perselisihan antara  kedua belah pihak, maka pada dasarnya akan diselesaikan secara musyawarah. </li>
    <li>Jika dalam musyawarah tersebut tidak ditemukan kesepakatan, maka kedua belah pihak sepakat untuk penyelesaikan menurut prosedur hukum yang berlaku melalui Kantor Kepaniteraan Pengadilan. </li>
    <li>Segala akibat yang terjadi dari pelaksanaan Perjanjian ini, kedua belah pihak telah memilih tempat kedudukan (domisili) yang tetap dan sah di Kantor Kepaniteraan Pengadilan Negeri Batang. </li>
    
</ol>

<div class="pasal">Pasal 11 <br> KETENTUAN LAIN-LAIN</div>
<ol style="text-align: justify;margin-top:0px" type="1">
    <li>Biaya administrasi dan materai sebagai akibat keluarnya Surat Perjanjian Kerja ini  menjadi tanggung jawab PIHAK KEDUA.</li>
    <li>Surat Perjanjian Kerja (SPK) ini dibuat 4 (empat ) rangkap terdiri dari 2 (dua) asli bermaterai dan  ditandatangani oleh masing masing pihak, dan mempunyai kekuatan hukum yang sama. Selebihnya diberikan kepada pihak yang berkepentingan dan ada hubungannya dengan pekerjaan ini.</li>
</ol>

<div class="pasal">Pasal 12 <br> PENUTUP</div>
Demikian Surat Perintah Kerja ini di buat dan ditandatangani pada tanggal yang telah ditetapkan untuk dipergunakan sebagaimana mestinya.
<br><br><br><br>
<table style="width:100%; text-align:center">
    <tr>
        <td>
            PIHAK KEDUA<br>{{$data['penyedia']->nama_penyedia}} 
            <br><br><br><i>materai</i><br><br><br><br>
            {{$data['penyedia']->nama_pemilik}}
        </td>
        <td>
            PIHAK KESATU<br>PKA
            <br><br><br><br><br><br><br>
            {{$data['kegiatan']->pka}}
        </td>
    </tr>
</table>

</body>
</html>
