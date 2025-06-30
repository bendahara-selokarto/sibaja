<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Negosiasi Harga</title>
</head>
<body>
   
    

    <x-kop-tpk>
        <x-slot name="kegiatan">
            {{ $data['kegiatan']->kegiatan }}
        </x-slot>
    </x-kop-tpk>   
    <table style="width: 100%">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right">{{ ucwords(Auth::user()->desa) }}, {{ Illuminate\Support\Carbon::parse($data['pemberitahuan']->tgl_batas_akhir_penawaran)->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="width: 6cm">: {{ $data['pemberitahuan']->no_pbj }}/Pers/{{ Auth::user()->kode_desa }}/{{ Auth::user()->tahun_anggaran }}</td>
            <td style="text-align: left"></td>
            <td>Kepada</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>: -</td>
            <td>Yth : </td>
            <td>{{ $data['penyedia']->nama_pemilik . ' ('. $data['penyedia']->nama_penyedia . ')' }}</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>: Undangan</td>
            <td>di-</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td> <strong> TEMPAT</strong></td>
        </tr>       
    </table>
    <br>
    {{-- <p style="margin-left:2.6cm; text-align:justify">Menanggapi surat penawaran harga Saudara Nomor {{ session('no_penyedia') }} tanggal {{ session('tgl_penyedia')->isoFormat('D MMMM Y') }} hal : Penawaran harga, bersama ini kami sampaikan hal-hal sebagai berikut :</p> --}}
    <ol style="margin-left: 2.6cm">
            <li style="text-align:justify">Setelah menerima dan mempelajari isi surat penawaran harga Saudara pada prinsipnya kami tidak keberatan, tetapi berdasarkan harga penawaran yang Saudara ajukan perlu melakukan klarifikasi dan negoisasi harga terhadap penawaran Saudara tersebut;</li>
            <li style="text-align:justify">Untuk keperlua dimaksud kami mengharap kehadiran Saudara besok pada :</li>
    </ol>
    <table style="margin-left: 4cm">
        <tr>            
           
            <td style="width: 3cm">Hari</td>
            <td>: {{ Illuminate\Support\Carbon::parse($data['negosiasiHarga']->tgl_negosiasi)->isoFormat('dddd')  }}</td>
        </tr>
        <tr>   
               
           
            <td>Tanggal</td>
            <td>: {{ Illuminate\Support\Carbon::parse($data['negosiasiHarga']->tgl_negosiasi)->isoFormat('D MMMM Y')  }}</td>
        </tr>
        <tr>            
           
            <td>Pukul</td>
            <td>: 09.00 WIB s.d Selesai</td>
        </tr>
        <tr>            
           
            <td>Hari</td>
            <td>: {{ Illuminate\Support\Carbon::parse($data['negosiasiHarga']->tgl_negosiasi)->isoFormat('dddd')  }}</td>
        </tr>
        <tr>            
           
            <td>Tempat</td>
            <td>: Kantor Kepala Desa {{ Auth::user()->desa }}</td>
        </tr>
        <tr>            
           
            <td>Acara</td>
            <td>: Negosiasi dan Klarifikasi Harga</td>
        </tr>
    </table>
    <table>

    <p style="margin-left:2.6cm">Demikian atas perhatian dan kerjasamanya diucapkan terima kasih.</p>
    <div style="margin-left:0cm">
        <table style="width: 100%">
            <tr>
                <td style="text-align: center; width:69%">
                    Mengetahui,                    
                 <br>Kepala Desa {{ Auth::user()->desa }}
                 <br>Selaku 
                 <br>Pemegang Kekuasaan Pengelolaan
                <br>Keuangan Desa
                <br>
                <br>
                <br>
                <br>
                <br><strong>{{ strToUpper(Auth::user()->kepala_desa) }}</strong>
                </td>
                <td style="text-align: center; width:31%">
                <br> 
                <br>
                Tim Pelaksana Kegiatan
                <br>Ketua
                <br>
                <br>
                <br>
                <br>
                <br>
                <br><strong>{{ $data['kegiatan']->ketua_tpk}} </strong>              
                </td>
            </tr>
        </table>
        
    </div>
    
</body>
</html>
@include('pdf.negosiasi-harga.ba-klarifikasi') 
@include('pdf.negosiasi-harga.lampiran-ba') 
@include('pdf.negosiasi-harga.perjanjian')
@include('pdf.negosiasi-harga.spk') 

