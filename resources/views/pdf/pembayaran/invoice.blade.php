   <!DOCTYPE html>
   <html lang="id">
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        @page {
            margin: 0;
        }
        .invoice {
            font-size: 12pt;
            margin-top: 20px;
            margin-right: 60px;
            margin-bottom: 60px;
            margin-left: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table:nth-of-type(2) th,
        table:nth-of-type(2) td {
            border: 1px solid black;
            padding: 2px;
        }
        table:nth-of-type(3) td {
            line-height: 2px;
        }
          
        
        
    </style>
   </head>
   <body>
    <div class="invoice">   
    <img 
        src="storage/{{$penyedia->kop_surat }}" 
        alt=" " 
        style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
        onerror="this.src='{{ asset('') }}'" 
        >
    <p style="text-align: right">{{ $penyedia->kabupaten }}, {{  Illuminate\Support\Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms)->isoFormat('D MMMM Y') }}</p>
    
    <table style="line-height: 2px;" >
        <tr>
            <td style="width: 70px">Nomor</td>
            <td>: {{ 'INV-' . strtoupper(rand(10, 300) ) . '/' . Auth::user()->tahun_anggaran}}</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>: Invoice</td>
        </tr>
    </table>
    
    
    <div style="width: 40%; margin-left:auto">
        <p>Kepada Yth: PKA Desa {{ Auth::user()->desa }} Kecamatan Pecalungan</p>
        <p>di-</p>
        <p>TEMPAT</p>
    </div>
    <h2 style="text-align: center">INVOICE</h2>  
    <br>
<table>
    <thead>
        <tr>
            <th style=" ">No.</th>
            <th style=" ">Jenis Barang</th>
            <th style=" ">Vol</th>
            <th style=" ">Sat</th>
            <th style=" ">Harga Satuan</th>
            <th style=" ">Jumlah</th>
        </tr>
    </thead>
    <tbody>
       
        @foreach ($item['uraian'] as $key => $value)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td style="text-align: left">{{ $item['uraian'][$key] }}</td>         
            <td style="text-align: right">{{ $item['volume'][$key]  }}</td>         
            <td style="text-align: left">{{ $item['satuan'][$key] }}</td>         
            <td style="text-align: right">{{ number_format($item['harga_satuan'][$key], 0, ',', '.') }}</td>         
            <td style="text-align: right">{{ number_format($item['volume'][$key] * $item['harga_satuan'][$key], 0, ',', '.') }}</td>         
        </tr>              
        @endforeach        
        <tr>
           
            <td style="text-indent: 300px"  colspan="5">Jumlah</td>
            <td >{{ number_format($kegiatan->negosiasiHarga->harga_negosiasi, 0, ',', '.') }}</td>   </tr>
        <tr>            
            <td style="text-indent: 300px" colspan="5">PPN dan PPh Pasal 22</td>
            <td >{{ number_format(round($kegiatan->negosiasiHarga->harga_negosiasi * (14/111)), 0, ',', '.') }}</td>
        </tr>   <tr>
           
            <td style="text-indent: 300px" colspan="5">Jumlah Total</td>
            <td>
                {{ number_format(round($kegiatan->negosiasiHarga->harga_negosiasi - ($kegiatan->negosiasiHarga->harga_negosiasi * (14/111))), 0, ',', '.') }}
            </td>   </tr>   </tbody>
</table>
<br>
<table>
    <tr>
        <td>Pembayaran Via Bank</td>
        <td></td>
    </tr>
    <tr>
        <td>Nomor Rekening</td>
        <td>: {{ $penyedia->rekening }}</td>
    </tr>
    <tr>
        <td>a.n</td>
        <td>: {{ $penyedia->atas_nama }}</td>
    </tr>
    <tr>
        <td>Nama Bank</td>
        <td>: {{ $penyedia->bank }}</td>s
    </tr>
</table>
<br>
<h6><strong>Pembayaran via Transfer dianggap lunasi setelah terkonfirmasi</strong></h6>
</div>
</body>
</html>

