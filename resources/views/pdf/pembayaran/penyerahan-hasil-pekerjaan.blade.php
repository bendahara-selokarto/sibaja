<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penyerahan Hasil Pekerjaan</title>
</head>
<body>
    {{-- @if(isset($penyedia->kop_surat) && $penyedia->kop_surat != null)
    <img 
    src="{{ public_path('storage/' . $penyedia->kop_surat) }}" 
    alt=" " 
    style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
    onerror="this.src='{{ asset('') }}'" 
    >
    @endif --}}
<br>
    <p style="text-align: right">{{ $penyedia->kabupaten}}, {{ $tgl_invoice->isoFormat('D MMMM Y')}}</p>
    <table style="width:100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 80px;">Nomor</td>
            <td style="width: 2%;">: </td>
            <td style="width: auto;">{{rand(11,49)}}</td>
            <td style="width: 40%;">Kepada</td>
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
            <td colspan="4" style="padding-left: 70%; text-decoration:underline; text-align:center"> {{ strtoupper(Auth::user()->desa) }}</td>
        </tr>
    </table>

    <br><br>
    <div style="margin-left: 100px; line-height: 1.7; text-align:justify">
    <p>
        Berdasarkan surat perjanjian Nomor : {{$pemberitahuan->no_pbj}}/SPK/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran}} maka kami sampaikan bahwa pekerjaan {{$kegiatan->kegiatan }} telah selesai dan dengan ini kami kirimkan hasil pelaksanaan pekerjaan {{$kegiatan->kegiatan }}, untuk dapat diteliti apakah sudah sesuai dengan spesifikasi teknis atau belum.
    </p>

    <p>
        Demikian atas kerjasamanya kami sampaikan terima kasih.
    </p>

    <br><br><br>
    <div style="width: 300px; margin-left:auto; text-align:center">
        <p>{{$penyedia->jabata_pemilik}} ( {{$penyedia->nama_penyedia}} )</p>
        <br><br>
        <p>{{$penyedia->nama_pemilik}}</p>
    </div>
</div>
</body>
</html>
