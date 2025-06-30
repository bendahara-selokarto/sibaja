<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Kwitansi</title>
    </head>
    <body>  
    
    <div style="margin-left: 1cm">
        <table style="border: 1px double black;">
            <tr>
                <td style="width:2cm">
                <div style="border: 2px dotted ; heihgt: 16cm; display: block; padding: 1mm; margin: 5mm; border-color:blue">
                    <div style="border: 2px dotted  ; heihgt: 16cm; widht: 2cm; display: block; padding: 1mm; border-color:blue" ><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                </div>
                    </div>
                </td>
                <td>
                    <table style="width: 100%; border-left: 1px dashed black; padding-right: 5mm ">
                        <tr>
                            <td></td>
                            <td colspan="2"><h3 style="text-align: center; text-decoration:underline; width:10cm;">KWITANSI</h3></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 2mm; border-bottom: 1px dashed black; width: 3.6cm">No. </td>
                            <td style="width: 5mm"></td>                           
                            <td ></td>
                            <td ></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm">Telah Terima Dari</td>
                            <td style="width: 5mm">:</td>                           
                            {{-- <td style="border-bottom: 1px dashed black">PKA {{ session('sub_bidang') }}</td> --}}
                            <td style="border-bottom: 1px dashed black">PKA Desa {{ ucwords(Auth::user()->desa)}}</td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm">Uang Sebesar</td>
                            <td style="width: 5mm">:</td>                           
                            {{-- <td style="border-bottom: 1px dashed black" >{{ Terbilang::make(session('harga_negosiasi')) }}.</td> --}}
                            <td style="border-bottom: 1px dashed black" >{{ ucwords(Terbilang::make($kegiatan->negosiasiHarga->harga_negosiasi) )}} Rupiah.</td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm"></td>
                            <td style="width: 5mm">:</td>                           
                            <td style="border-bottom: 1px dashed black"></td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm">Guna Membayar</td>
                            <td style="width: 5mm">:</td>                           
                            <td style="border-bottom: 1px dashed black">Belanja Material Kegiatan {{ $kegiatan->kegiatan }}</td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm"></td>
                            <td style="width: 5mm">:</td>                           
                            <td style="border-bottom: 1px dashed black"></td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm">Terbilang</td>
                            <td style="width: 5mm">:</td>                           
                            <td style="border-bottom: 1px dashed black">Rp. {{ number_format($kegiatan->negosiasiHarga->harga_negosiasi, 0, ',', '.') }}</td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm"></td>
                            <td style="width: 5mm">:</td>                           
                            <td style="border-bottom: 1px dashed black"></td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm"></td>
                            <td style="width: 5mm"></td>                           
                            <td style="text-align:center; text-indent: 2cm">{{ ucwords(Auth::user()->desa) .", ". Illuminate\Support\Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms)->isoFormat('D MMMM Y') }}</td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm"><br></td>
                            <td style="width: 5mm"></td>                           
                            <td style="text-align:center; text-indent: 2cm">Yang Menerima</td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm"><br></td>
                            <td style="width: 5mm"></td>                           
                            <td style=""><br><br><br></td>
                            <td style=""></td>
                        </tr>                       
                        <tr>
                            <td style="text-indent: 2mm"></td>
                            <td style="width: 5mm"></td>                           
                            <td style="text-align:center; text-indent: 2cm">{{ strtoupper($penyedia->nama_pemilik) }}</td>
                            <td ></td>
                        </tr>                       
                    </table> 
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
@include('pdf.pembayaran.new-invoice')

