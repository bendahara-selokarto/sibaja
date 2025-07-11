   <!DOCTYPE html>
   <html lang="id">
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>  
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
    
    <table style="line-height: 1;" >
        <tr>
            <td style="width: 70px">Nomor</td>
            <td style="width: 300px">: {{ 'INV-' . strtoupper(rand(10, 300) ) . '/' . Auth::user()->tahun_anggaran}}</td>
            <td>
                Kepada Yth:
            </td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>: Invoice</td>
            <td>
                PKA Desa {{ Auth::user()->desa }} <br>Kecamatan Pecalungan
            </td>
        </tr>
    </table>   
    <div style="width: 37%; margin-left:auto"><br>
        di- <br>
        <p style="text-align: center; text-indent: 5pt">TEMPAT</p>
    </div>
    <h2 style="text-align: center">INVOICE</h2>  
    <br>
<table class="invoice" style="width:100%">
    <thead>
        <tr>
            <th style="text-align:center">No.</th>
            <th style="width:50mm">Jenis Barang / Jasa </th>
            <th style="text-align:center">Volume</th>
            <th style="text-align:center">Satuan</th>
            <th style="text-align:center">Harga Satuanbr <br>(Rp.)</th>
            <th style="text-align:center">Jumlah <br>(Rp.)</th>
        </tr>
    </thead>
    <tbody>
       
        @foreach ($item['uraian'] as $key => $value)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td style="text-align: left">{{ $item['uraian'][$key] }}</td>         
            <td style="text-align: center">{{ $item['volume'][$key]  }}</td>         
            <td style="text-align: center">{{ $item['satuan'][$key] }}</td>         
            <td style="text-align: right">{{ number_format($item['harga_negosiasi'][$key], 0, ',', '.') }}</td>         
            <td style="text-align: right">{{ number_format($item['volume'][$key] * $item['harga_negosiasi'][$key], 0, ',', '.') }}</td>         
        </tr>              
        @endforeach        
        <tr>
           
            <td style="text-indent: 300px"  colspan="5">Jumlah</td>
            <td style="text-align: right">{{ number_format($kegiatan->negosiasiHarga->harga_negosiasi, 0, ',', '.') }}</td>   </tr>
        <tr>            
            <td style="text-indent: 300px" colspan="5">PPN dan PPh Pasal 22</td>
            <td style="text-align: right">{{ number_format(round($kegiatan->negosiasiHarga->harga_negosiasi * (14/100)), 0, ',', '.') }}</td>
        </tr>
        <tr>
           
            <td style="text-indent: 300px" colspan="5">Jumlah Total</td>
            <td style="text-align: right">
                {{ number_format(round($kegiatan->negosiasiHarga->harga_negosiasi + ($kegiatan->negosiasiHarga->harga_negosiasi * (14/100))), 0, ',', '.') }}
            </td>   
        </tr>
        <tr>
           
            <td style="text-indent: 300px" colspan="5">Dibulatkan</td>
            <td style="text-align: right">
                {{ number_format(round($kegiatan->negosiasiHarga->harga_negosiasi + ($kegiatan->negosiasiHarga->harga_negosiasi * (14/100)), -2  ), 0, ',', '.') }}
            </td>   
        </tr>
    </tbody>
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
        <td>: {{ $penyedia->bank }}</td>
    </tr>
</table>
<br>
<h4><strong>Pembayaran via Transfer dianggap lunas setelah terkonfirmasi</strong></h4>
</div>
</body>
</html>

