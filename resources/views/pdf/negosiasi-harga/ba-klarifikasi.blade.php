    <div style="margin-left:1cm">
    <h3 style="margin-bottom: 0px ;text-align:center; text-decoration:underline">BERITA ACARA KLARIFIKASI DAN NEGOSIASI HARGA</h3>
    <h4 style="margin: 0px; text-align:center">Nomor :  {{ $data['pemberitahuan']->no_pbj }} /BA-KLNH/{{ Auth::user()->kode_desa }}/ {{ Auth::user()->tahun_anggaran }}</h4>
    <h4 style="text-align:center">Tanggal : {{ $data['negosiasiHarga']->tgl_negosiasi->isoFormat('D MMMM Y') }}</h4>
    @php
    @endphp

    <p style="text-align:justify; text-indent:1cm;">
    Pada hari ini <strong>{{ $data['negosiasiHarga']->tgl_negosiasi->isoFormat('dddd') }}</strong> 
    tanggal <strong>{{ Terbilang::make($data['negosiasiHarga']->tgl_negosiasi->isoFormat('D')) }}</strong> 
    bulan <strong>{{ $data['negosiasiHarga']->tgl_negosiasi->isoFormat('MMMM')}} </strong>
    tahun <strong> {{ Terbilang::make($data['negosiasiHarga']->tgl_negosiasi->isoFormat('Y')) }} </strong> 
    pada pukul Sepuluh Waktu Indonesia Barat sampai dengan Selesai,
    dengan mengambil tempat di Kantor Kepala Desa {{ ucwords(Auth::user()->desa) }} 
    kami yang bertanda tangan di bawah ini  telah melakukan klarifikasi dan negosiasi harga atas pekerjaan pengadaan material kegiatan {{ $data['kegiatan']->kegiatan }} Tahun Anggaran {{ Auth::user()->tahun_anggaran }}</p>




    <table style="width: 100%">
    <tr>
        <td style="width: 0.5cm">1.</td>
        <td style="width: 5.5cm">Nama Perusahaan/Rekanan </td>
        <td style="">:</td>
        <td style="">{{  $data['penyedia']->nama_penyedia }}<</td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data['penyedia']->alamat_penyedia }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Harga Penawaran</td>
        <td>:</td>
        <td>{{ number_format(round($data['nilai_total_penawaran'], -2), 0, ',', '.') }} ( {{ Terbilang::make(round($data['penawaranHarga']->nilai_penawaran, -2)) }} rupiah )</td>
    </tr>
    <tr>
        <td></td>
        <td>Harga Negosiasi</td>
        <td>:</td>
        <td>{{ number_format(round($data['negosiasiHarga']->harga_total,-2), 0, ',', '.') }} ( {{ Terbilang::make(round($data['negosiasiHarga']->harga_total, -2)) }} rupiah )</td>
    </tr>
    <!-- <tr>
        <td style="width: 0.5cm">2.</td>
        <td style="width: 5.5cm">Nama Perusahaan/Rekanan </td>
        <td style="">:</td>
        <td style="">.....................<</td>
    </tr>
    <tr>
        <td></td>
        <td>Alamat</td>
        <td>:</td>
        <td>.....................</td>
    </tr>
    <tr>
        <td></td>
        <td>Harga Penawaran</td>
        <td>:</td>
        <td>.....................</td>
    </tr>
    <tr>
        <td></td>
        <td>Harga Negosiasi</td>
        <td>:</td>
        <td>.....................</td>
    </tr> -->
</table>
<p>Berdasarkan hasil negosiasi disepakati harga terendah yang wajar maupun secara teknis dapat dipertanggungjawabkan dan dinyatakan sebagai pemenang adalah sebagai berikut :</p>

<table style="width: 100%; margin-left: 0.5">
    <tr>
        <td style="width: 5.5cm">Nama Perusahaan/Rekanan </td>
        <td style="">:</td>
        <td style="">{{ $data['penyedia']->nama_penyedia }}<</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data['penyedia']->alamat_penyedia }}</td>
    </tr>
    <tr>
        <td>Harga Negosiasi</td>
        <td>:</td>
        <td>Rp. {{ number_format(round($data['negosiasiHarga']->harga_total, -2), 0, ',', '.')  }} ( {{ Terbilang::make(round($data['negosiasiHarga']->harga_total, -2)) }} rupiah )</td>
    </tr>
</table>

<p style="text-align: justify">Dengan harga negosiasi tersebut diatas rekanan yang bersangkutan menyatakan sanggup dan bersedia melaksanakan pekerjaan sesuai ketentuan Rencana Kerja dan Syarat-syarat dalam dokumen pengadaan.</p>
<p style="text-align: justify">Berita Acara Negosiasi Harga Penawaran ini merupakan satu kesatuan dari bagian yang tak terpisahkan dari proses pengadaan ini dan akan dituangkan dalam Surat Perjanjian.</p>
<P style="text-align: justify">Demikian Berita Acara ini dibuat dalam rangkap 2 (dua) masing-masing bermeterai cukup dan mempunyai kekuatan hukum yang sama untuk dipertanggungjawabkan sesuai peraturan perundang-undangkan yang berlaku.</P>
<h3 style="text-align:center; text-decoration:underline">MASING-MASING PIHAK</h3>
<table style="width: 100%">
    <tr>
        <td style="text-align: center">Menyetujui,<br>{{ $data['penyedia']->jabata_pemilik ." " .$data['penyedia']->nama_penyedia }} <br><br><br><br><span>{{ $data['penyedia']->nama_pemilik}}</span></td>
        <td style="text-align: center">Tim Pelaksana Kegiatan<br> Ketua <br><br><br><br><span>{{ $data['kegiatan']->ketua_tpk}}</span></td>
   </tr>
</table>
</div>
@include('pdf.negosiasi-harga.daftar-hadir')