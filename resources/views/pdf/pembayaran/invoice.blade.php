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
<<<<<<< HEAD
   <body>   
 
@if(!empty($penyedia1->kop_surat))
<img 
src="storage/{{$penyedia1->kop_surat }}" 
alt=" " 
style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
onerror="this.src='{{ asset('') }}'" 
>
@else

<table style="width: 100%">
    <tr>
        <td style="width: 100px">            
            <img 
                src="storage/{{$penyedia->logo_penyedia }}" 
                class="logo-kop-desa" 
                alt=" " 
                width="100px" 
                onerror="this.src='{{ asset('') }}'" 
            >
        </td>       
        <td>
           <h2 style="text-align: center; margin: 0cm;">{{ $penyedia->nama_penyedia }}</h2>
           <h4 style="text-align: center; margin: 0cm;">{{ $penyedia->alamat_penyedia }}</h4>
           <h4 style="text-align: center; margin: 0cm; color: blue;">HP : {{  $penyedia->nomor_hp}}</h4>
        </td>
    </tr>
</table>
<hr>
@endif



<hr>
<table style="widht: 100%">
    <tr>      
        <td style="width: 10cm; text-align:center; vertical-align:middle">
            <h2>INVOICE</h2>
       </td>
        <td><div style="width: 5cm;">
            <table>
                <tr>
                    <td>Nomor</td>
                    <td style="border-bottom: 1px dashed black;">: {{ 'INV-' . strtoupper(rand(10, 300) ) . '/' . Auth::user()->tahun_anggaran}}</td>
                
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td style="border-bottom: 1px dashed black;">: {{  Illuminate\Support\Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms)->isoFormat('D MMMM Y') }}</td>
                </tr>
                <tr>
                    <td>Metode</td>
                    <td style="border-bottom: 1px dashed black;">: cash/bank</td>
                </tr>
            </table>
        </div></td>
    </tr>
</table>
<p>Kepada Yth: PKA Desa {{ Auth::user()->desa }} Kecamatan Pecalungan</p>
<p>di-</p>
<p>TEMPAT</p>
<br>
<table style="margin-left: 4cm">
=======
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
>>>>>>> farida
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

