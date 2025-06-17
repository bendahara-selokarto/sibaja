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
</head>
<body>
    

<table style="width: 100%; border: 1px solid black;"><thead>
        <tr>
          <td style='text-align: center; vertical-align: bottom; background-color: #b9b9b9; border-bottom: 1px solid #b9b9b9;' rowspan="3" >SURAT PERINTAH KERJA</td>
          <td  style="border-left: 1px solid black; text-align: center;" colspan="2">KABUPATEN BATANG</td>
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
          <td >: {{ $pemberitahuan->no_spk }}</td>
        </tr>
        <tr>
          <td style="border-left: 1px solid black" >Tanggal</td>
          <td >: {{ Illuminate\Support\Carbon::parse($negosiasiHarga->tgl_perjanjian)->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
          <td style="border-top: 1px solid black"></td>
          <td style="border-left: 1px solid black; border-top: 1px solid black; text-align: center;"  colspan="2">SURAT PENAWARAN</td>
        </tr>
        <tr>
          <td style="text-align: center">PEKERJAAN</td>
          <td style="border-left: 1px solid black" >Nomor</td>
          <td >: {{ $kegiatan->penawaran->no_penawaran_1 }}/SPH/{{ Auth::user()->tahun_anggaran }}</td>
        </tr>
        <tr>
          <td style="text-align: center">{{ $kegiatan->kegiatan }}</td>
          <td style="border-left: 1px solid black" >Tanggal</td>
        
          <td >: {{ Illuminate\Support\Carbon::parse($kegiatan->penawaran->tgl_penawaran_1)->isoFormat('D MMMM Y')  }}</td>
        </tr>
        <tr>
          <td style="text-align: center">KODE REKENING BELANJA</td>
          <td style="border-left: 1px solid black; border-top: 1px solid black" colspan="2">BERITA ACARA NEGOSIASI</td>
        </tr>
        <tr>
          <td style="text-align: center">{{ $kegiatan->rekening_apbdes }}</td>
          <td style="border-left: 1px solid black" >Nomor</td>
          <td>: {{ $pemberitahuan->no_ba_negosiasi }}</td>
        </tr>
        <tr>
          <td></td>
          <td style="border-left: 1px solid black" >Tanggal</td>
          <td>: {{ Illuminate\Support\Carbon::parse($negosiasiHarga->tgl_negosiasi)->isoFormat('D MMMM Y')  }}</td>
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
          <td >: {{ $pemberitahuan->no_pbj }}/SPK/{{ Auth::user()->kode_desa }}/{{ Auth::user()->tahun_anggaran }}</td>
        </tr>
        <tr>
          <td >TANGGAL</td>
          <td >: {{ Illuminate\Support\Carbon::parse($negosiasiHarga->tgl_perjanjian)->isoFormat('D MMMM Y')  }}</td>
        </tr>
        <tr>
          <td >WAKTU PELAKSANAAN</td>
          <td >: {{ $negosiasiHarga->jumlah_hari_kerja }} hari</td>
        </tr>
        <tr>
          <td >NILAI PEKERJAAN</td>
            <td >: Rp. {{ number_format($negosiasiHarga->harga_negosiasi, 0, ',', '.') }}</td>
        </tr>
      </tbody>
      </table>
      <table class="table-spk"><thead>
        <tr>
          <th style="text-align: center">NO</th>
          <th style="text-align: center">JENIS BARANG/BAHAN</th>
          <th style="text-align: center">KUANTITAS</th>
          <th style="text-align: center">HARGA SATUAN</th>
          <th style="text-align: center">JUMLAH</th>
        </tr></thead>
      <tbody>
        
        @foreach ($item['uraian'] as $k => $v )
            
        <tr>
          <td style="text-align: center">{{ $loop->iteration }}</td>
            <td >{{ $item['uraian'][$k] }}</td>
            <td style="text-align: center">{{ $item['volume'][$k] . ' '. $item['satuan'][$k]  }}</td>
            <td style="text-align: right"> {{ number_format($item['harga_satuan'][$k], 0, ',', '.') }}</td>
            <td style="text-align: right"> {{ number_format($item['volume'][$k] * $item['harga_satuan'][$k], 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
          <td style="text-align: right" colspan="4">Sub Total</td>
            <td style="text-align: right">{{ number_format($negosiasiHarga->harga_negosiasi , 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="text-align: right" colspan="4">Pajak PPN dan PPh22</td>
            <td style="text-align: right">{{ number_format($negosiasiHarga->harga_negosiasi * (14/111 ), 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="text-align: right" colspan="4">Total</td>
            <td style="text-align: right">{{ number_format($negosiasiHarga->harga_negosiasi * (100/111), 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="text-align: center" colspan="5">( {{ ucwords(Terbilang::make($negosiasiHarga->harga_negosiasi)) }} Rupiah )</td>
        </tr>
      </tbody>
      </table>
      <div style="page-break-before: always;"></div>

      <table style="width: 100%; border: 1px solid black; text-align: center;">
       <tr style="border-bottom: 1px solid black">
         <td  colspan="2"><p style="text-align: justify; margin:2mm; font-size:10pt"><strong>INSTRUKSI KEPADA PENYEDIA BARANG DAN JASA : </strong>Penagihan hanya dapat dilakukan setelah penyelesaian pekerjaan yang diperintahkan dalam SPK ini dan hasil pekerjaan tersebut dapat diterima secara memuaskan oleh Tim Pengelola Kegiatan dan dibuktikan dengan Berita Acara Serah Terima. Jika pengadaan tidak dapat diselesaikan dalam jangka waktu pengiriman karena kesalahan atau kelalaian Penyedia Barang maka Penyedia Barang berkewajiban untuk membayar denda kepada TPK sebesar 1/1000 (satu per seribu) dari nilai SPK sebelum PPN setiap hari kalender keterlambatan. Selain tunduk kepada ketentuan dalam SPK ini, Penyedia Barang dan Jasa berkewajiban untuk mematuhi Standar Ketentuan dan Syarat Umum SPK terlampir.</p></td>
       </tr>
      <tr>
        <td></td>
        <td>{{ ucwords(Auth::user()->desa) .', '. $negosiasiHarga->tgl_perjanjian->isoFormat('D MMMM Y') }}</td>
      </tr>
      <tr>
        <td>PIHAK KEDUA</td>
        <td>PIHAK PERTAMA</td>
      </tr>
      <tr>
        <td>{{ $penyedia->nama_penyedia }}</td>
        <td>PKA</td>
      </tr>
      <tr>
        <td><br><br><br></td>
        <td></td> 
      </tr>
      <tr>
        <td>{{ $penyedia->nama_pemilik }}</td>
        <td>{{ $kegiatan->pka }}</td>
      </tr>

     </table>