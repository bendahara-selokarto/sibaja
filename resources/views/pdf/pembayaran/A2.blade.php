<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tanda Bukti Pengeluaran</title>
    <style>
        
    </style>
</head>
<body style="margin: 50px 50px 300px 50px; line-height: 1; vertical-align:text-top; ">
    <div style="border: 2px solid black">
    <table style="width: 100%; border-bottom: 1px solid black; margin:0px;">        
        <tr>
            <td rowspan="5" style="width: 124px; text-align:center"><img src="{{ public_path('images/batang.png') }}" alt="batang" style="width: 80px; height:auto;"></td>
            <td colspan="4" style="text-transform: uppercase; font-weight: bold">
                PEMERINTAH KABUPATEN BATANG               
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; width:120pt">KECAMATAN</td>
            <td style="font-weight: bold; width:10pt;">: </td>
            <td style="font-weight: bold; width:auto; text-transform: uppercase; ">PECALUNGAN</td>
            <td style="width: auto">No: {{$pemberitahuan->no_pbj ??""}}</td>
        </tr>
        <tr>
            <td style="font-weight: bold">DESA</td>
            <td style="font-weight: bold">: </td>
            <td style="font-weight: bold; text-transform: uppercase;">{{Auth::user()->desa }}</td>
            <td>Lembar ke: 1</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">TAHUN ANGGARAN</td>
            <td style="font-weight: bold;">: </td>
            <td style="font-weight: bold;">{{Auth::user()->tahun_anggaran}}</td>
            <td></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">SUMBER DANA</td>
            <td style="font-weight: bold;">: </td>
            <td style="font-weight: bold; text-transform: uppercase;">DD</td>
            <td style="border-bottom: 1px solid black"></td>
        </tr>
    </table>
<table style="width: 100%; margin:0px">
    <tr>
        <td style="width: 58%; vertical-align: top; padding-right: 20px;">
            {{-- Kiri --}}
            <h3 style="text-align: center; text-decoration: underline; margin-top: 30px;">TANDA BUKTI PENGELUARAN</h3>
            <table style="width: 100%; margin-top: 20px;">
                <tr>
                    <td style="vertical-align: top; padding: 5px;">Sudah menerima dari</td>
                    <td style="vertical-align: top; width: 4pt; padding: 5px;">:</td>
                    <td style="vertical-align: top; padding: 5px;">Bendahara Desa</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; padding: 5px;">Uang Sejumlah</td>
                    <td style="vertical-align: top; width: 4pt; padding: 5px;">:</td>
                    <td style="vertical-align: top; padding: 5px;">
                        <strong>Rp. {{formatNumber(round($negosiasiHarga->total, -2))}},-</strong><br>
                        <em>{{Terbilang::make(round($negosiasiHarga->total, -2))}} rupiah</em>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; padding: 5px;">Yaitu Untuk Pembayaran</td>
                    <td style="vertical-align: top; width: 4pt; padding: 5px;">:</td>
                    <td style="vertical-align: top; padding: 5px;">Belanja Material</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 45%; padding: 5px;">Untuk Pekerjaan/ Kegiatan</td>
                    <td style="vertical-align: top; width: 4pt; padding: 5px;">:</td>
                    <td style="vertical-align: top; padding: 5px;">{{$kegiatan->kegiatan}}</td>
                </tr>
            </table>
        </td>
        
        <td style="width: 42%; vertical-align: top; padding-left: 20px; border: 1px solid black;" cellpadding="2">
            {{-- Kanan --}}

            
            <div style="text-align: left; margin-top: 30px;">
                Barang-barang dimaksud telah dibukukan ke buku persediaan/inventaris pada tanggal:
            </div>
            <hr>

            <table style="width: 100%; margin-top: 10px;" cellspacing="0" cellpadding="2">
                <tr>
                    <td>Jumlah Kotor</td>
                    <td>Rp. </td>
                    <td style="text-align: right">{{formatNumber(round($negosiasiHarga->total, -2))}},-</td>
                </tr>
                <tr>
                    <td>Potongan</td>
                    <td>Rp. </td>
                    <td style="text-align: right">{{formatNumber(round($negosiasiHarga->pajak))}},-</td>
                </tr>
                <tr>
                    <td>Dibayarkan</td>
                    <td>Rp. </td>
                    <td style="text-align: right">{{formatNumber(round($negosiasiHarga->total, -2))}},-</td>
                </tr>
            </table>

            <div style="margin-top: 10px;"><strong>Perincian Potongan :</strong></div>
            <table style="width: 100%; margin-top: 5px;">
                <tr><td>1. PPh 21</td><td>Rp. </td><td style="text-align: right"></td></tr>
                <tr><td>2. PPh 22</td><td>Rp. </td><td style="text-align: right">{{formatNumber($negosiasiHarga->pph_22 ?? 0)}},-</td></tr>
                <tr><td>3. PPh 23</td><td>Rp. </td><td style="text-align: right"></td></tr>
                <tr><td>4. PPN</td><td>Rp. </td> <td style="text-align: right">{{formatNumber($negosiasiHarga->ppn ?? 0)}},-</td></tr>
            </table>
        </td>
    </tr>
</table>
<table style="width: 100%">
    <tr>
        <td>
            <div style="border: 2px solid black; display: inline-block; padding: 10px 20px; margin-left:20px;">
                <strong style="font-size: 18px;">LUNAS DIBAYAR</strong><br>
                <span style="font-style: italic;">{{tanggal_indo($tgl)}}</span>
            </div>
            
        </td>
        <td>
            <div style="text-align: center; margin-left:auto; width:300px">
                <p style="margin-top: 30px;">{{Auth::user()->desa}}, {{tanggal_indo($tgl) ??""}}</p>
                <p>Yang menerima</p><br>Terlampir
            
            </div>

        </td>
    </tr>
</table>



    

    <table style="width: 100%; text-align: center; margin-top: 40px;">
        <tr>
            <td style="border: 1px solid black" >Setuju dibayarkan<br>Kepala Desa {{Auth::user()->desa}} <br><br><br><br><br><strong>{{Auth::user()->kepala_desa}}</strong></td>
            <td style="border: 1px solid black" >Telah di Verifikasi<br>Sekretaris Desa <br><br><br><br><br><strong>{{Auth::user()->sekretaris_desa}}</strong></td>
            <td style="border: 1px solid black" >Telah dibayarkan<br>Kaur Keuangan <br><br><br><br><br><strong>{{Auth::user()->bendahara_desa}}</strong></td>
            <td style="border: 1px solid black" >Pelaksana Kegiatan Anggaran<br>PKA<br><br><br><br><br><strong>{{$kegiatan->pka }}</strong></td>
        </tr>      
    </table>
    
</body>
</html>
