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
        <p>Lampiran Berita Acara Klarifikasi dan Negosiasi Harga <br><span>Nomor : </span></p>
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
            </tr>
             @endforeach            
        </tbody>
    </table>
</body>
</html>

