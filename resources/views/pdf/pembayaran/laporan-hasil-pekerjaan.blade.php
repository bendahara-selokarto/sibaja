<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>  
            <img 
            src="storage/{{$penyedia->kop_surat }}" 
                alt=" " 
                style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
                onerror="this.src='{{ asset('') }}'" 
                >

        <table style="width:100%">
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td></td>
                <td>Kepada :</td>
            </tr>
            <tr>
                <td>Lampiaran</td>
                <td>: </td>
                <td> 1 bandel</td>
                <td>Yth ;</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>: </td>
                <td>Laporan Hasi Pekerjaan</td>
                <td></td>
            </tr>
        </table>
        <br><br>
        <div style="text-align: justify; text-indent:300px10px; margin-left:154px">
            <p>Berdasarkan Keputusan Kepala Desa Nomor ..................Tahun ..............tentang Pelaksana Kegiatan Anggaran, maka dengan ini kami laporkan bahwa kegiatan ................telah selesai dilaksanakan pada .............. Adapun dokumen pelaksanaan kegiatan.......... terlampir.</p>

<p>Demikian untuk menjadikan periksa dan guna seperlunya.</p>


        </div>
        <div style="text-align: center; width: 300px; margin-left:auto">
            Pelaksana Kegiatan Anggaran <br>
            Kegiatan {{$kegiatan->kegiatan}}
            <br><br><br>
            {{$kegiatan->pka}}
        </div>
</body>
</html>