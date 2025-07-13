<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body >  
            {{-- <img 
            src="storage/{{$penyedia->kop_surat }}" 
                alt=" " 
                style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
                onerror="this.src='{{ asset('') }}'" 
                > --}}
                <x-kop-desa></x-kop-desa>
                <p style="text-align: right">{{Auth::user()->desa}} , {{ tanggal_indo($tgl)}}</p>

        <table style="width:100%">
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td style="text-indent: 30px">/{{Terbilang::roman($tgl->isoFormat('M'))}}/{{Auth::user()->tahun_anggaran}}</td>
                <td>Kepada :</td>
            </tr>
            <tr>
                <td>Lampiaran</td>
                <td>: </td>
                <td>1 bandel</td>
                <td>Yth. Kepala Desa {{Auth::user()->desa}}</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>: </td>
                <td>Laporan Hasi Pekerjaan</td>
                <td>di-</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td colspan="" style="text-transform: uppercase; text-decoration:underline; text-align:left; text-indent:30px"><b>{{Auth::user()->desa}}</b></td>
            </tr>
            {{-- <p style="text-transform: uppercase; text-decoration:underline; margin-left:auto; width:300px; text-align:center"><b>{{Auth::user()->desa}}</b></p> --}}
        </table>
        <br><br>
        <div style="text-align: justify; text-indent:30px; margin-left:154px; vertical-align:1.5; line-height: 2">
            <p >Berdasarkan Keputusan Kepala Desa Nomor .................. Tahun .............. tentang Pelaksana Kegiatan Anggaran, maka dengan ini kami laporkan bahwa kegiatan {{$kegiatan->kegiatan}} telah selesai dilaksanakan pada {{tanggal_indo($negosiasiHarga->tgl_akhir_perjanjian)}} Adapun dokumen pelaksanaan kegiatan {{$kegiatan->kegiatan}} terlampir.</p>

<p>Demikian untuk menjadikan periksa dan guna seperlunya.</p>


        </div>
        <br><br>
        <div style="text-align: center; width: 300px; margin-left:auto">
            Pelaksana Kegiatan Anggaran <br>
            Kegiatan {{$kegiatan->kegiatan}}
            <br><br><br><br><br>
            {{$kegiatan->pka}}
        </div>
</body>
</html>