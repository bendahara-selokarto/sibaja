@include('cover.pbj') 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemberitahuan</title>
    <style>
        table {
            border-collapse: collapse;
        }
        table, tr, td {
            vertical-align: top;
        }
    </style>
</head>
<body>   
    <x-kop-tpk >
        <x-slot name="kegiatan">{{ $kegiatan->kegiatan }}</x-slot>
    </x-kop-tpk>
    <table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right">{{ ucwords(Auth::user()->desa) }}, {{ \Carbon\Carbon::parse($pemberitahuan['tgl_surat_pemberitahuan'])->isoFormat('D MMMM Y') }}</td>
    </tr>    
    <tr>
        <td style="width: 2.5cm">Nomor</td>
        <td style="width: 1mm">:</td>
        <td>{{ $pemberitahuan['no_pbj'] }}/Pemb/{{ Auth::user()->kode_desa }}/{{  Auth::user()->tahun_anggaran }}</td>
        <td></td> 
        <td></td>
    </tr>
    <tr>
        <td style="width: 2.5cm">Lamp</td>
        <td style="width: 1mm">:</td>
        <td>-</td>
        <td style="text-align: right">Kepada :</td>
        <td></td>
        
    </tr>
    <tr>
        <td style="width: 2.5cm">Hal</td>
        <td style="width: 1mm">:</td>
        <td style="text-decoration: underline">Pemberitahuan</td>
        <td style="text-align: right">Yth;</td>
        <td>
            <ol>
            @foreach ($pemberitahuan['penyedia'] as $p)
                <li>{{ App\Models\Penyedia::find($p)->nama_penyedia }}</li>
                @endforeach
            </ol>            
    </td>
    </tr>    
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: left">di-</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center">TEMPAT</td>
    </tr>
    <tr>
        <td><br><br><br><br></td>
        <td colspan="4">
                <p style="text-align: justify; text-indent: 1cm;">Sehubungan dengan akan dilaksanakan kegiatan <strong> {{ $kegiatan->kegiatan }}</strong>, dengan pekerjaan Belanja Material ( <strong> {{ $pemberitahuan['pekerjaan'] }}</strong> ).</p>
                <p style="text-align: justify; text-indent: 1cm;">Adapun spesifikasi teknis yang kami persyaratkan yaitu :</p>
        </td>
    </tr>
    <tr>
        <td></td>
    <td colspan="4">
        <ol>
            <li>Ruang lingkup pekerjaan <strong> Pengadaan Material</li>
            <li>Daftar Barang/Jasa ;</li>
        </ol>
    </td>
    </tr>
    
</table>

<table style="margin-left: 2.6cm; width: 15cm;">
  <tr>
    <td style="border: 1px solid black; text-align:center; width: 0.6cm">No</td>
    <td style="border: 1px solid black; text-align:center; ">Jenis Barang/jasa</td>
    <td style="border: 1px solid black; text-align:center; width: 2cm">vol/Satuan</td>
    <td style="border: 1px solid black; text-align:center; width: 3cm">Harga</td>
    <td style="border: 1px solid black; text-align:center; width: 3cm; ">Jumlah</td>
  </tr>
  @php
      $no = 1;      
  @endphp
   @foreach ($pemberitahuan['belanja'] as $r)
      <tr>
          <td style="border: 1px solid black; text-align:center " >{{ $no }}</td>
          <td style="border: 1px solid black; text-align:left" >{{ $r['field1']}}</td>
          <td style="border: 1px solid black; text-align:center" >{{ Number::format($r['field2']) }} {{ $r['field3'] }}</td>
          <td style="border: 1px solid black; text-align:center"></td>
          <td style="border: 1px solid black; text-align:center"></td>
      </tr> 
        @php
        $no++
    @endphp
    @endforeach
    <tr>
        {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
        <td colspan="4" style="border: 1px solid black; text-align:right">Sub Total</td>
        <td style="border: 1px solid black; text-align:right"></td>
    </tr>
    <tr>
        {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
        <td colspan="4" style="border: 1px solid black; text-align:right">PPN dan PPh Pasal 22</td>
        <td style="border: 1px solid black; text-align:right"></td>
    </tr>
    <tr>
        <td colspan="4" style="border: 1px solid black; text-align:right">Total</td>
        <td style="border: 1px solid black; text-align:right"></td>
    </tr>

    </table>
    <div style=" page-break-after: always"></div>
    <table>
   <tr>
    <td></td>
    <td colspan="5" >
        <div style="margin-left: 2.6cm; text-align:justify">
        <p >Maka apabila Saudara berminat dan bersedia melaksanakan pekerjaan  <strong>{{ $kegiatan->kegiatan }}</strong> tersebut, diminta segera mengajukan surat penawaran harga.</p>
        <p style=" text-align:justify">Surat penawaran dialamatkan kepada Bapak  <strong>{{ $kegiatan->ketua_tpk }} </strong>, selaku Tim Pengelola Kegiatan dengan ketentuan sebagai berikut :</p>
        <ol>
           
            <li>Surat penawaran dibuat rangkap 3 (tiga) asli, 1 (satu) bermeterai Rp. 10.000 dan sudah harus kami terima tanggal <strong>{{ Carbon\Carbon::parse($pemberitahuan->tgl_batas_akhir_penawaran)->isoFormat('D MMMM Y')}}</strong></li>
            <li>Surat penawaran dilampiri :</li>
            <ol type="a">
                <li>Form isian pengadaan barang/jasa,</li>
                <li>Pakta integritas</li>
                <li>Daftar penawaran harga termasuk pajak, bea meterai dan jasa penggandaan,</li>
                <li>Foto copy Surat Ijin Usaha Perdagangan (SIUP),</li>
                <li>Foto copy Nomor Pokok Wajib Pajak (NPWP).</li>
            </ol>
        </ol>
    </div>
    </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5">
        </td>
    </tr>
</table>
<p style="margin-left: 2.5cm">Demikian surat permintaan penawaran ini kami sampaikan atas perhatian Saudara diucapkan terima kasih. </p>
<table>
    <tr>
        {{-- <td style="text-align: center">Mengetahui,<br><x-sign-kades></x-sign-kades></td> --}}
        {{-- <td><br><x-sign-tpk>{{ strToUpper($pemberitahuan['kegiatan']->ketua_tpk) }}</x-sign-tpk></td> --}}
    </tr>
</table>
<x-double-signature left-keterangan="Kepala Desa Selokarto" left="{{ strToUpper(Auth::user()->kepala_desa) }}" right="{{ strToUpper($kegiatan->ketua_tpk) }}" right-keterangan="TPK Desa"></x-double-signature>
</body>
</html>

