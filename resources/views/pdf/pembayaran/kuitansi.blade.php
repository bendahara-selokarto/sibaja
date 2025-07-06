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
        .kuitansi {
            font-size: 12pt;
            margin-top: 100px;
            margin-right: 100px;
            margin-bottom: 20px;
            margin-left: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            vertical-align: top
        }
        td {
            vertical-align: top
        }
        td {
            padding: 10px;
        }
    </style>
    <title>Document</title>
</head>
<body>
    <div class="kuitansi">
    <h1 style="text-align: center;">KUITANSI</h1>
    <br><br>
    <table style="width: 100%;">
        <tr style="margin-bottom: 20px;">
            <td style="width: 22%;">Telah terima dari</td>
            <td style="width: auto">: </td>
            <td style="width: 70%"> Pelaksana Kegiatan Anggaran</td>
        </tr>
        <tr>
            <td>Uang sebanyak</td>
            <td style="width: 10px">: </td>
            <td>Rp. {{ number_format($kegiatan->negosiasiHarga->harga_negosiasi, 0, ',', '.') }},-</td>
        </tr>
        <tr>
            <td>Terbilang</td>
            <td>: </td>
            <td>{{ ucwords(Terbilang::make($kegiatan->negosiasiHarga->harga_negosiasi) )}} Rupiah.</td>
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
                {{ strtoupper($penyedia->nama_penyedia) }}
                <br><br><br>
                <i>materai</i>
                <br><br>
                {{ strtoupper($penyedia->nama_pemilik) }}
            </p>
        </div>
    </div>
    </body>
    </html>
    @include('pdf.pembayaran.invoice')