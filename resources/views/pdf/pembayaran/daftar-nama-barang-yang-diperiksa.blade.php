<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body.daftar-nama-barang-yang-diperiksa {
            margin-top:70px;
            margin-left: 60px;

        }
        table.daftar-nama-barang-yang-diperiksa {
            width: 100%;
            margin-left: auto;
            border-collapse: collapse;
        }
        table.daftar-nama-barang-yang-diperiksa td,
        table.daftar-nama-barang-yang-diperiksa th
        {
            border:1px solid black;
        }
    </style>
    <title>Document</title>
</head>
<body class="daftar-nama-barang-yang-diperiksa">
    <h3 style="text-align: center">DAFTAR NAMA BARANG/PEKERJAAN YANG DIPERIKSA</h3>
    <br>
    <table class="daftar-nama-barang-yang-diperiksa">
        <tr>
            <th style="width: 4%">No.</th>
            <th style="width: 36%">Nama Pekerjaan</th>
            <th style="width: 20%">Volume Barang</th>
            <th style="width: 20%">Tanda</th>
            <th style="width: 20%">Keterangan</th>
        </tr>
        @foreach ($item['uraian'] as $key => $value)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td style="text-align: left">{{ $item['uraian'][$key] }}</td>         
            <td style="text-align: center">{{ $item['volume'][$key]  }} {{ $item['satuan'][$key] }}</td> 
            <td style="text-align: center">V</td>         
            <td style="text-align: center">Baik dan Lengkap</td>         
        </tr>              
        @endforeach       
    </table>
    <p style="text-align: right">{{Auth::user()->desa}}, </p>
    <p style="text-align:center">Tim Pelaksana Kegiatan</p>
    <table style="width: 100%">
        <tr>
            <td>Rekanan</td>
            <td>1</td>
            <td>Nama</td>
            <td> ............. </td>
            <td> 1. .........................  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Jabatan</td>
            <td>: Ketua</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>2</td>
            <td>Nama</td>
            <td> ..... </td>
            <td> 2. ........  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Jabatan</td>
            <td>: Sekretaris</td>
            <td></td>
        </tr>
        <tr>
            <td> .................. </td>
            <td>3</td>
            <td>Nama</td>
            <td> ..... </td>
            <td> 3. ........  </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Jabatan</td>
            <td>: Anggota</td>
            <td></td>
        </tr>
    </table>
    
</body>
</html>