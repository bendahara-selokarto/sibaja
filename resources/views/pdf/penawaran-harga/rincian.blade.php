
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
        <td style="width: 120px">            
            <img 
                src="storage/{{$penyedia1->logo_penyedia }}" 
                class="logo-kop-desa" 
                alt=" " 
                width="120px" 
                onerror="this.src='{{ asset('') }}'" 
            >
        </td>       
        <td>
           <h2 style="text-align: center; margin: 0cm;">{{ $penyedia1->nama_penyedia }}</h2>
           <h4 style="text-align: center; margin: 0cm;">{{ $penyedia1->alamat_penyedia }}</h4>
           <h4 style="text-align: center; margin: 0cm; color: blue;">HP : {{  $penyedia1->nomor_hp}}</h4>
        </td>
    </tr>
</table>
<hr>
@endif
<h3 style="text-align: center;line-height: 50%;">DAFTAR RINCIAN PENAWARAN HARGA BARANG/JASA</h3>
    <h3 style="text-align: center; line-height: 50%">SUDAH TERMASUK PAJAK-PAJAK KEPADA NEGARA DAN</h3>
    <h3 style="text-align: center; line-height: 50%">BEA MATERAI</h3>

    <table>
        <tr>
            <td>Kegiatan</td>
            <td>:</td>
            <td>{{ $kegiatan->kegiatan }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>Pengadaan Material</td>
        </tr>
        <tr>
            <td>Tahun Anggaran</td>
            <td>:</td>
            <td>{{ Auth::user()->tahun_anggaran }}</td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
          <td style="border: 1px solid black; text-align:center; width: 0.6cm">No</td>
          <td style="border: 1px solid black; text-align:center; ">Jenis Barang/jasa</td>
          <td style="border: 1px solid black; text-align:center; width: 2cm">vol/Satuan</td>
          <td style="border: 1px solid black; text-align:center; width: 3cm">Harga</td>
          <td style="border: 1px solid black; text-align:center; width: 3cm; ">Jumlah</td>
        </tr>
        @php
       
        $no = 1;   
        @endphp
         @foreach ($item['uraian'] as $key => $value)
         
            <tr>
                <td style="border: 1px solid black; text-align:center " >{{ $no }}</td>
                <td style="border: 1px solid black; text-align:left " >{{ $item['uraian'][$key] }}</td>
                <td style="border: 1px solid black; text-align:center " >{{ $item['volume'][$key] .'  '. $item['satuan'][$key] }}</td>
                <td style="border: 1px solid black; text-align:right " >{{ number_format($item['harga_satuan'][$key], 0, ',', '.') }}</td>
                <td style="border: 1px solid black; text-align:right " >{{ number_format($item['harga_satuan'][$key] *  $item['volume'][$key], 0, ',', '.') }}</td>
            </tr> 
              @php
              $no++
          @endphp
          @endforeach
          <tr>
              <td colspan="4" style="border: 1px solid black; text-align:right">Sub Total</td>
              <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah, 0, ',', '.') }}</td>
          </tr>
          <tr>
              {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
              <td colspan="4" style="border: 1px solid black; text-align:right">PPN 11%</td>
              <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah * (11/111), 0, ',', '.') }}</td>
            </tr>
            <tr>
                {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
                <td colspan="4" style="border: 1px solid black; text-align:right">PPh22 3%</td>
                <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah * (3/111), 0, ',', '.') }}</td>
            </tr>
            
            <tr>
                {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
                <td colspan="4" style="border: 1px solid black; text-align:right">Total</td>
                <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah * (100/111), 0, ',', '.') }}</td>
            </tr>
          </table>
          <strong><p>Terbilang : {{ Terbilang::make($jumlah) }} rupiah</p></strong>
          <br><br><br>
          <table style="width: 100%">
            <tr>
                <td style="width: 8cm"><br></td>
                <td style="text-align: center;">{{ $penyedia1->nama_penyedia}}
                    <br><br><br><br>
                    <strong>{{ $penyedia1->nama_pemilik}}</strong></td>
            </tr>
        </table>

        
   