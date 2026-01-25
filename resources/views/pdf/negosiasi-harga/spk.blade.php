<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPK</title>
    <style>
        .table-spk {
            border-collapse: collapse;
            width: 100%;
        }
        .table-spk th {
            background-color: #f2f2f2;
        }
        .table-spk th, .table-spk td {
            padding: 3px;
            text-align: left;
        }
        .table-spk th, .table-spk td {
            border: 1px solid black;
        }     
       
    </style>
    <style>
      div.daftar-hadir {
        margin-top: 40px;
        margin-right:40px;
        margin-bottom: 40px;
        margin-left: 60px;
      }
      table.daftar-hadir {
        width: 100%;
        vertical-align: top;
        text-align: center;
        border-collapse: collapse;
        border: 1px solid black;
      }
      table.daftar-hadir th {      
        border: 1px solid black;
      }
      table.daftar-hadir td {      
        border-left: 1px solid black;
        border-right: 1px solid black;
        height: 50px;
      }
      table.daftar-hadir:nth-child(1) {
        width: 10%;      
        
      }
      table.daftar-hadir:nth-child(2) {
        width: 27%;      
        
      }
      table.daftar-hadir:nth-child(2) {
        width: 27%;      
        
      }
      table.daftar-hadir:nth-child(2) {
        width: auto;      
        
      }
    </style>
</head>
<body>
    

<table style="width: 100%; border: 1px solid black;"><thead>
        <tr>
          <td style='text-align: center; vertical-align: bottom; background-color: #b9b9b9; border-bottom: 1px solid #b9b9b9; ' rowspan="3" >SURAT PERINTAH KERJA</td>
          <td  style="border-left: 1px solid black; text-align: center; min-width:350px" colspan="2">KABUPATEN BATANG</td>
        </tr>
        <tr>
          <td style="border-left: 1px solid black; text-align: center;"  colspan="2">KECAMATAN {{ strToUpper(Auth::user()->kecamatan) }}<</td>
        </tr>
        <tr>
          <td style="border-left: 1px solid black; text-align: center;" colspan="2">PEMERINTAH DESA {{ strToUpper(Auth::user()->desa) }}</td>
        </tr>
        <tr>
          <td rowspan="3" style='text-align: center; vertical-align: top; border-top: 1px solid #b9b9b9; background-color: #b9b9b9;'>( SPK )</td>
          <td style="border-left: 1px solid black; border-top: 1px solid black; text-align: center;" colspan="2">NOMOR DAN TANGGAL SPK</td>
        </tr>
        <tr>
          <td style="width: 15mm; border-left: 1px solid black">Nomor</td>
          <td >: {{ $data['pemberitahuan']->no_spk }}</td>
        </tr>
        <tr>
          <td style="border-left: 1px solid black" >Tanggal</td>
          <td >: {{ Illuminate\Support\Carbon::parse($data['negosiasiHarga']->tgl_perjanjian)->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
          <td style="border-top: 1px solid black"></td>
          <td style="border-left: 1px solid black; border-top: 1px solid black; text-align: center;"  colspan="2">SURAT PENAWARAN</td>
        </tr>
        <tr>
          <td style="text-align: center">PEKERJAAN</td>
          <td style="border-left: 1px solid black" >Nomor</td>
          <td >: {{ $data['kegiatan']->penawaran->no_penawaran }}/SPH/{{ Auth::user()->tahun_anggaran }}</td>
        </tr>
        <tr>
          <td style="text-align: center">{{ $data['kegiatan']->kegiatan }}</td>
          <td style="border-left: 1px solid black" >Tanggal</td>
        
          <td >: {{ Illuminate\Support\Carbon::parse($data['kegiatan']->penawaran->tgl_penawaran)->isoFormat('D MMMM Y')  }}</td>
        </tr>
        <tr>
          <td style="text-align: center">KODE REKENING BELANJA</td>
          <td style="border-left: 1px solid black; border-top: 1px solid black" colspan="2">BERITA ACARA NEGOSIASI</td>
        </tr>
        <tr>
          <td style="text-align: center">{{ $data['kegiatan']->rekening_apbdes }}</td>
          <td style="border-left: 1px solid black" >Nomor</td>
          <td>: {{ $data['pemberitahuan']->no_ba_negosiasi }}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-left: 1px solid black" >Tanggal</td>
          <td>: {{ Illuminate\Support\Carbon::parse($data['negosiasiHarga']->tgl_negosiasi)->isoFormat('D MMMM Y')  }}</td>
        </tr>    
      </thead>
      </table>

      <table style="width: 100%; border: 1px solid black;"><thead>
        <tr>
          <td style="width: 6cm;">Sumber Dana</td>
          <td >: APBDes Tahun {{ Auth::user()->tahun_anggaran }}</td>
        </tr></thead>
      <tbody>
        <tr>
          <td >NOMOR</td>
          <td >: {{ $data['pemberitahuan']->no_pbj }}/SPK/{{ Auth::user()->kode_desa }}/{{ Auth::user()->tahun_anggaran }}</td>
        </tr>
        <tr>
          <td >TANGGAL</td>
          <td >: {{ Illuminate\Support\Carbon::parse($data['negosiasiHarga']->tgl_perjanjian)->isoFormat('D MMMM Y')  }}</td>
        </tr>
        <tr>
          <td >WAKTU PELAKSANAAN</td>
          <td >: {{ $data['negosiasiHarga']->jumlah_hari_kerja }} ({{Terbilang::make($data['negosiasiHarga']->jumlah_hari_kerja)}}) hari kalender</td>
        </tr>
        <tr>
          <td >NILAI PEKERJAAN</td>
            <td >: Rp. {{ number_format(round($data['negosiasiHarga']->harga_total, 0), 0, ',', '.') }},-</td>
        </tr>
      </tbody>
      </table>
      <table class="table-spk"><thead>
        <tr>
          <th style="text-align: center">NO</th>
          <th style="text-align: center">JENIS BARANG/BAHAN</th>
          <th style="text-align: center">KUANTITAS</th>
          <th style="text-align: center">HARGA SATUAN <br>(Rp.)</th>
          <th style="text-align: center">JUMLAH <br>(Rp.)</th>
        </tr></thead>
      <tbody>
        
        @foreach ($data['item'] as $k => $v )
            
        <tr>
          <td style="text-align: center">{{ $loop->iteration }}</td>
            <td >{{ $v['uraian'] }}</td>
            <td style="text-align: center">{{ $v['volume'] . ' '. $v['satuan']  }}</td>
            <td style="text-align: right"> {{ number_format($v['harga_negosiasi'], 0, ',', '.') }}</td>
            <td style="text-align: right"> {{ number_format($v['volume'] * $v['harga_negosiasi'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
          <td style="text-align: right" colspan="4">Jumlah</td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->harga_sebelum_pajak , 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="text-align: right" colspan="4">PPN</td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->ppn, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="text-align: right" colspan="4">PPh Pasal 22</td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->pph_22, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="text-align: right" colspan="4">Jumlah Total</td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->harga_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="text-align: right" colspan="4">Dibulatkan</td>
            <td style="text-align: right">{{ number_format(round($data['negosiasiHarga']->harga_total, 0), 0, ',', '.') }}</td>
        </tr>
        @if(count($v) > 8)
        <tr style="height: 200px">
          <td style="height: 200px; text-align: center" colspan="5">( {{ ucwords(Terbilang::make(round($data['negosiasiHarga']->harga_total, 0))) }} Rupiah )</td>
        </tr>
      </tbody>
      </table>
      <div style="page-break-before: always;"></div>
      @else
      <tr>
        <td style="text-align: center" colspan="5">( {{ ucwords(Terbilang::make(round($data['negosiasiHarga']->harga_total, 0))) }} Rupiah )</td>
      </tr>
    </tbody>
    </table>
      @endif

      <table style="width: 100%; border-top: 2px solid black; border-left: 1px solid black; border-right: 1px solid black; border-collapse: collapse; text-align: center;">
       <tr>
         <td  colspan="2"><p style="text-align: justify; margin:2mm; font-size:10pt"><strong>INSTRUKSI KEPADA PENYEDIA BARANG DAN JASA : </strong>Penagihan hanya dapat dilakukan setelah penyelesaian pekerjaan yang diperintahkan dalam SPK ini dan hasil pekerjaan tersebut dapat diterima secara memuaskan oleh Tim Pelaksana Kegiatan dan dibuktikan dengan Berita Acara Serah Terima. Jika pengadaan tidak dapat diselesaikan dalam jangka waktu pengiriman karena kesalahan atau kelalaian Penyedia Barang maka Penyedia Barang berkewajiban untuk membayar denda kepada TPK sebesar 1/1000 (satu per seribu) dari nilai SPK sebelum PPN setiap hari kalender keterlambatan. Selain tunduk </p></td>
        </tr>
      <tr>
        <td></td>
      </tr>
      
    </table>
    <table style="width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center; border-collapse: collapse; page-break-inside: avoid;">
      <tr>
        <td colspan="2" style="text-align: justify; padding: 5px;">
          kepada ketentuan dalam SPK ini, Penyedia Barang dan Jasa berkewajiban untuk mematuhi
          Standar Ketentuan dan Syarat Umum SPK terlampir.
        </td>
      </tr>
      <tr>
        <td></td>
        <td style="text-align:center; padding: 5px;">
          {{ ucwords(Auth::user()->desa) .', '. $data['negosiasiHarga']->tgl_perjanjian->isoFormat('D MMMM Y') }}
        </td>
      </tr>
      <tr>
        <td>PIHAK KEDUA</td>
        <td>PIHAK PERTAMA</td>
      </tr>
      <tr>
        <td>{{ $data['penyedia']->jabata_pemilik ." ". $data['penyedia']->nama_penyedia }}</td>
        <td>PKA</td>
      </tr>
      <tr>
        <td style="height: 60px;"></td>
        <td></td>
      </tr>
      <tr>
        <td>{{ $data['penyedia']->nama_pemilik }}</td>
        <td>{{ $data['kegiatan']->pka }}</td>
      </tr>
    </table>

<div style="page-break-before: always;">
@include('pdf.negosiasi-harga.standar-ketentuan-dan-syarat-umum')
</div>