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

    @if($penyedia->kop_surat)
        <img 
            src="{{public_path('storage/'.$penyedia->kop_surat)}}" 
            alt=" " 
            style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
            onerror="this.src='{{ asset('') }}'" 
            >
     @endif
        <p style="text-align: right">{{ $penyedia->kabupaten }}, {{  Illuminate\Support\Carbon::parse($kegiatan->pembayaran->tgl_invoice)->isoFormat('D MMMM Y') }}</p>
    
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
    <div style="width: 37%; margin-left:auto">
        di-
        <p style="text-align: center; text-indent: 5pt">TEMPAT</p>
    </div>
    <h2 style="text-align: center; margin-bottom:1px">INVOICE</h2>  
<table class="invoice" style="width:100%; margin-top:1px">
    <thead>
        <tr>
            <th style="text-align:center">No.</th>
            <th style="width:50mm">Jenis Barang / Jasa </th>
            <th style="text-align:center">Volume</th>
            <th style="text-align:center">Satuan</th>
            <th style="text-align:center">Harga Satuan <br>(Rp.)</th>
            <th style="text-align:center">Jumlah <br>(Rp.)</th>
        </tr>
    </thead>
    <tbody>
       
        @foreach ($item as $value)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td style="text-align: left">{{ $value['uraian'] }}</td>         
            <td style="text-align: center">{{ $value['volume']  }}</td>         
            <td style="text-align: center">{{ $value['satuan'] }}</td>         
            <td style="text-align: right">{{ number_format($value['harga_negosiasi'] , 0, ',', '.') }}</td>         
            <td style="text-align: right">{{ number_format($value['jumlah_negosiasi'] , 0, ',', '.') }}</td>         
        </tr>              
        @endforeach        
        <tr>
           
            <td style="text-indent: 300px"  colspan="5">Jumlah</td>
            <td style="text-align: right">{{ number_format($negosiasiHarga->jumlah, 0, ',', '.') }}</td>   </tr>
        <tr>            
            <td style="text-indent: 300px" colspan="5">PPN dan PPh Pasal 22</td>
            <td style="text-align: right">{{ number_format(round($negosiasiHarga->pajak), 0, ',', '.') }}</td>
        </tr>
        <tr>
           
            <td style="text-indent: 300px" colspan="5">Jumlah Total</td>
            <td style="text-align: right">
                {{ number_format(round($negosiasiHarga->total), 0, ',', '.') }}
            </td>   
        </tr>
        <tr>
           
            <td style="text-indent: 300px" colspan="5">Dibulatkan</td>
            <td style="text-align: right">
                {{ number_format(round($kegiatan->negosiasiHarga->total, -2  ), 0, ',', '.') }}
            </td>   
        </tr>
    </tbody>
</table><br>
Terbilang : {{Terbilang::make(round($negosiasiHarga->total, -2))}} rupiah
<br><br>
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
<h4><strong>Pembayaran via Transfer dianggap lunas setelah terkonfirmasi</strong></h4>
</div>
</body>
</html>

