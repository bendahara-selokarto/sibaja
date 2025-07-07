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
    <p style="text-align: right">{{ ucwords($penyedia->kabupaten) }}, {{ Illuminate\Support\Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms)->isoFormat('D MMMM Y') }} </p>
    <table style="width:100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 8%;">Nomor</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;"> {{ $kegiatan->nomor}}/PHP/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran}}</td>
            <td style="width: 24%;">Kepada</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td> 1 bandel</td>
            <td>Yth. Pelaksana Kegiatan Anggaran</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td>Penyerahan Hasil Pekerjaan</td>
            <td>di -</td>
        </tr>
        <tr>
            <td colspan="4" style="padding-left: 60%; text-align:center">{{ Auth::user()->desa }}</td>
        </tr>
    </table>

    <br><br>
    <div style="margin-left: 40px; ">
    <p style="text-align:justify; line-height: 2">
        Berdasarkan surat perjanjian Nomor : <strong>  {{ $kegiatan->nomor }}/PERJ/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }}</strong> dan <strong>{{ $kegiatan->nomor }}/SPK/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }}</strong> maka kami sampaikan bahwa pekerjaan <strong>Pengadaan Material {{ $kegiatan->kegiatan }}</strong> telah selesai dan dengan ini kami kirimkan hasil pelaksanaan pekerjaan <strong>Pengadaan Material {{ $kegiatan->kegiatan }}</strong>, untuk dapat diteliti apakah sudah sesuai dengan spesifikasi teknis atau belum.
    </p>

    <p style="text-align:justify; line-height: 2">
        Demikian atas kerjasamanya kami sampaikan terima kasih.
    </p>
    
    <br><br><br>
    <div style="text-align: center; width: 300px; margin-left: auto">
        <p>{{ $penyedia->jabata_pemilik}} ({{ $penyedia->nama_penyedia}})</p>
        <br><br>
        <p>{{ $penyedia->nama_pemilik}}</p>
    </div>
    
</div>
</body>
</html>
