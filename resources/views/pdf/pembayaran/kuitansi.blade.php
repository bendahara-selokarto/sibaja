{{-- @include('pdf.pembayaran.penyerahan-hasil-pekerjaan')
@include('pdf.pembayaran.ba-pemeriksaan-barang')
@include('pdf.pembayaran.daftar-nama-barang-yang-diperiksa')
@include('pdf.pembayaran.penerimaan-hasil-pekerjaan')
@include('pdf.pembayaran.laporan-hasil-pekerjaan')
@include('pdf.pembayaran.ba-serah-terima-hasil-pekerjaan')
@include('pdf.pembayaran.berita-acara-pembayaran')
@include('pdf.pembayaran.invoice') --}}
@include('pdf.pembayaran.A2')
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @page {
            margin: 0;
        }

        body {
            margin-top: 20px;
            margin-right: 60px;
            margin-bottom: 20px;
            margin-left: 120px;
            font-size: 12pt;

        }

        table {
            border-collapse: collapse;
            vertical-align: top

        }

        table.kuitansi td {
            padding: 10px;
            vertical-align: text-top;
        }

        table.invoice th, 
        table.invoice td {
            border: 1px solid black;
            padding: 2px;
            margin-left: auto;
            width: 100%;
        }
        table.invoice {
            margin-left: auto;
            width: 100%;
        }
        table.invoice th:first-child {
            width: 5px;
            text-align: center;
        }
        table.invoice th:nth-child(2) {
            width: auto;
            text-align: center;
        }
        table.invoice th:nth-child(3) {
            width: 12mm;
        }
        table.invoice th:nth-child(4) {
            width: 12mm;
        }
        table.invoice th:nth-child(5) {
            width: 15mm;
        }
        table.invoice th:nth-child(6) {
            width: 15mm;
        }
       
        table.ba-pembayaran {
            padding: 0px;
            height: 1px;
            vertical-align: top;
        }
        table.ba-pembayaran td {
            padding: 0px;
            height: 15px;
        }
        </style>
    <title>Document</title>
</head>
<body>
    <div class="kuitansi">
        <h1 style="text-align: center; margin-top: 130px">KUITANSI</h1>
        <br><br>
        <table class="kuitansi">
            <tr style="margin-bottom: 20px;">
                <td style="width: 22%;">Telah terima dari</td>
                <td style="width: auto">: </td>
                <td style="width: auto"> Pelaksana Kegiatan Anggaran</td>
            </tr>
            <tr>
                <td>Uang sebanyak</td>
                <td style="width: 10px">: </td>
                <td><strong>Rp. {{ number_format(round($kegiatan->negosiasiHarga->total, -2), 0, ',', '.') }},-</strong></td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td>: </td>
                <td><i>( {{ ucwords(Terbilang::make(round($kegiatan->negosiasiHarga->total, -2)) )}} Rupiah. )</i></td>
            </tr>
            <tr>
                <td>Untuk Keperluan</td>
                <td>: </td>
                <td>Belanja Material Kegiatan {{ $kegiatan->kegiatan }} <br>
                Desa {{Auth::user()->desa}} Kecamatan Pecalungan <br>
                Kabupaten Batang Tahun {{ Auth::user()->tahun_anggaran}}</td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <br>
        
        
        <div style="width: 300px; margin-left: auto; margin-right: 10px; text-align: right; ">
            {{ ucwords(Auth::user()->desa) .", " .Illuminate\Support\Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms)->isoFormat('D MMMM Y') }}
        </div>
        <div style="width: 300px; text-align: center; margin-left: auto;">
            <p style="line-height: 2; ">
                Yang Menerima;
                <br>
                {{ strtoupper($penyedia->jabata_pemilik) }} ({{ strtoupper($penyedia->nama_penyedia) }})
                <br><br><br>
                <i>materai</i>
                <br><br>
                {{ strtoupper($penyedia->nama_pemilik) }}
            </p>
        </div>
    </div>
</body>
</html>
