<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lampiran</title>
</head>
<body>
    
    <!-- <div style="page-break-before: always;"></div> -->
    <div style="margin-left: 40%; text-align: left;">
        <p style="padding: 0.4px;">Lampiran Berita Acara Klarifikasi dan Negosiasi Harga <br><span>Nomor : {{ $data['pemberitahuan']->no_pbj }} /BA-KLNH/{{ Auth::user()->kode_desa }}/ {{ Auth::user()->tahun_anggaran }}</span><br><span>Tangal : {{ $data['negosiasiHarga']->tgl_negosiasi->isoFormat('D MMMM Y')}}</span></p>
        
    </div>
    <table border="1" cellspacing="0" cellpadding="5" style="width:100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Jenis Barang/Jasa</th>
                <th rowspan="2">Vol/Sat</th>
                <th colspan="2">Harga Penawaran</th>
                <th colspan="2">Harga Negosiasi</th>
            </tr>
            <tr>
                <th>Harga Satuan</th>
                <th>Jumlah Harga</th>
                <th>Harga Satuan</th>
                <th>Jumlah Harga</th>
            </tr>
        </thead>
        <tbody>
             @foreach ($data['item']['uraian'] as $k => $v )
             <tr>
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td>{{ $data['item']['uraian'][$k] }}</td>
                <td>{{ $data['item']['volume'][$k] . ' '. $data['item']['satuan'][$k] }}</td>
                <td style="text-align: right"> {{ number_format($data['item']['harga_satuan'][$k], 0, ',', '.') }}</td>
                <td style="text-align: right"> {{ number_format($data['item']['volume'][$k] * $data['item']['harga_satuan'][$k], 0, ',', '.') }}</td>
                <td style="text-align: right"> {{ number_format($data['item']['harga_negosiasi'][$k], 0, ',', '.' )}}</td>
                <td style="text-align: right"> {{ number_format($data['item']['volume'][$k] * $data['item']['harga_negosiasi'][$k], 0, ',', '.') }}</td>
            </tr>
             @endforeach
             <tr>
            <td style="text-align: right" colspan="4">Jumlah</td>
            <td style="text-align: right">{{ number_format($data['penawaranHarga']['nilai_penawaran'] , 0, ',', '.') }}</td>
            <td style="text-align: right; background-color:rgb(209, 206, 206);"></td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->harga_negosiasi , 0, ',', '.') }}</td>
            </tr>
            <tr>
            <td style="text-align: right" colspan="4">PPN</td>
            <td style="text-align: right">{{ number_format($data['penawaranHarga']->ppn, 0, ',', '.') }}</td>
            <td style="text-align: right; background-color:rgb(209, 206, 206);"></td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->ppn, 0, ',', '.') }}</td>
            </tr>
            <tr>
            <td style="text-align: right" colspan="4">PPh Pasal 22</td>
            <td style="text-align: right">{{ number_format($data['penawaranHarga']->pph_22, 0, ',', '.') }}</td>
            <td style="text-align: right; background-color:rgb(209, 206, 206);"></td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->pph_22, 0, ',', '.') }}</td>
            </tr>
            <tr>
            <td style="text-align: right" colspan="4">Jumlah Total</td>
            <td style="text-align: right">{{ number_format($data['penawaranHarga']->harga_total, 0, ',', '.') }}</td>
            <td style="text-align: right; background-color:rgb(209, 206, 206);"></td>
            <td style="text-align: right">{{ number_format($data['negosiasiHarga']->harga_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
            <td style="text-align: center" colspan="7">( {{ ucwords(Terbilang::make($data['negosiasiHarga']->harga_total)) }} Rupiah )</td>
            </tr>         
        </tbody>
    </table> 
    <br>
    <br>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="text-align: center">{{ $data['penyedia']->jabata_pemilik . " ".$data['penyedia']->nama_penyedia }} <br><br><br><br><span>{{ $data['penyedia']->nama_pemilik}}</span></td>
            <td style="text-align: center">Tim Pelaksana Kegiatan<br> Ketua <br><br><br><br><span>{{ $data['kegiatan']->ketua_tpk}}</span></td>
    </tr>
    </table>   
    
    
      
    
</body>
</html>

