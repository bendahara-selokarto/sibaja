@include('cover.pbj') 
@include('pdf.check-list-PBJ') 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemberitahuan</title>
    <style>
        @page {
            padding: 0;
        }
        .pemberitahuan {
            margin-top: 20px;
            margin-right: 40px;
            margin-bottom: 40px;
            margin-left: 60px;
        }
        table {
            border-collapse: collapse;
        }
        table, tr, td {
            vertical-align: top;
            /* border: 1px solid black; */
        }
    </style>
</head>
<body>
    <div class="pemberitahuan">   
    <x-kop-tpk >
        <!-- <x-slot name="kegiatan">{{ $kegiatan->kegiatan }}</x-slot> -->
    </x-kop-tpk>
    <table style="width: 100%">
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
        <td style="60mm">{{ $pemberitahuan['no_pbj'] }}/Pemb/{{ Auth::user()->kode_desa }}/{{  Auth::user()->tahun_anggaran }}</td>
        <td style="text-align: right">Kepada :</td>
        <td></td>
    </tr>
    <tr>
        <td style="width: 2.5cm">Lamp</td>
        <td style="width: 1mm">:</td>
        <td> -</td>
        <td style="text-align: right">Yth;</td>
        
        <td style="vertical-align: top; padding: 0px" rowspan="2">
                @foreach ($pemberitahuan['penyedia'] as $p)
                {{ $loop->iteration}}. 
                {{ App\Models\Penyedia::find($p)->nama_penyedia }} <br>
                @endforeach
        </td>
    </tr>
    <tr>
        <td style="width: 2.5cm">Hal</td>
        <td style="width: 1mm">:</td>
        <td style="text-decoration: underline">Pemberitahuan</td>
        <td></td> 
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
            
        </table>
        <div style="margin-left: auto; width: 80%">
        <p style="text-align: justify; text-indent: 1cm;">Sehubungan dengan akan dilaksanakan kegiatan <strong> {{ $kegiatan->kegiatan }}</strong>, dengan pekerjaan Pengadaan Material.
        <br> Adapun spesifikasi teknis yang kami persyaratkan yaitu :</p>
<ol>
    <li>Ruang lingkup pekerjaan <strong> Pengadaan Material</li>
    <li>Daftar Barang/Jasa ;</li>
</ol>

<table style="margin-left: auto; width: 100%;">
  <tr>
    <td style="border: 1px solid black; text-align:center; width: 0.6cm">No</td>
    <td style="border: 1px solid black; text-align:center; width: auto; ">Jenis <br>Barang/Bahan/Jasa</td>
    <td style="border: 1px solid black; text-align:center; width: auto; ">Volume / <br>Satuan</td>
    <td style="border: 1px solid black; text-align:center; width: auto; min-width: 60px" >Harga Satuan<br> ( Rp. )</td>
    <td style="border: 1px solid black; text-align:center; width: auto; min-width: 60px">Jumlah Harga <br>( Rp. )</td>
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
        <td colspan="4" style="border: 1px solid black; text-align:right">Jumlah</td>
        <td style="border: 1px solid black; text-align:right"></td>
    </tr>
    <tr>
        {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
        <td colspan="4" style="border: 1px solid black; text-align:right">PPN </td>
        <td style="border: 1px solid black; text-align:right"></td>
    </tr>
    <tr>
        {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
        <td colspan="4" style="border: 1px solid black; text-align:right">PPh 22 </td>
        <td style="border: 1px solid black; text-align:right"></td>
    </tr>
    <tr>
        <td colspan="4" style="border: 1px solid black; text-align:right">Jumlah Total</td>
        <td style="border: 1px solid black; text-align:right"></td>
    </tr>
    <tr>
        <td colspan="4" style="border: 1px solid black; text-align:right; background-color:rgb(216, 216, 216)">Dibulatkan</td>
        <td style="border: 1px solid black; text-align:right; background-color:rgb(216, 216, 216)"></td>
    </tr>

    </table>    
    <p style=" text-align:justify">Maka apabila Saudara berminat dan bersedia melaksanakan pekerjaan pengadaan material kegiatan <strong>{{ $kegiatan->kegiatan }}</strong> tersebut, diminta segera mengajukan surat penawaran harga.</p>
    <p style=" text-align:justify">Surat penawaran dialamatkan kepada Bapak  <strong>{{ $kegiatan->ketua_tpk }}</strong>, selaku Tim Pelaksana Kegiatan dengan ketentuan sebagai berikut :</p>
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
    <p>Demikian surat permintaan penawaran ini kami sampaikan atas perhatian Saudara diucapkan terima kasih. </p>
    </div>
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
                <br><strong>{{ $kegiatan->ketua_tpk}} </strong>              
                </td>
            </tr>
        </table>
        
    </div>
</div>
</body>
</html>

