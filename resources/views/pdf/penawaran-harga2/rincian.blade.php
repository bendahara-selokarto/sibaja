@if(!empty($penyedia2->kop_surat))
<img 
src="storage/{{$penyedia2->kop_surat }}" 
alt=" " 
style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
onerror="this.src='{{ asset('') }}'" 
>
@else
<table style="width: 100%">
    <tr>
        <td style="width: 120px">            
            <img 
                src="storage/{{$penyedia2->logo_penyedia }}" 
                class="logo-kop-desa" 
                alt=" " 
                width="120px" 
                onerror="this.src='{{ asset('') }}'" 
            >
        </td>       
        <td>
           <h2 style="text-align: center; margin: 0cm;">{{ $penyedia2->nama_penyedia }}</h2>
           <h4 style="text-align: center; margin: 0cm;">{{ $penyedia2->alamat_penyedia }}</h4>
           <h4 style="text-align: center; margin: 0cm; color: blue;">HP : {{  $penyedia2->nomor_hp}}</h4>
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
          <td style="border: 1px solid black; text-align:center; width: 6mm">No</td>
          <td style="border: 1px solid black; text-align:center; ">Jenis Pekerjaan <br> yang dikerjakan</td>
          <td style="border: 1px solid black; text-align:center; width: 20mm">Volume / Satuan <br>(Meter, Unit, Btg, Kg)</td>
          <td style="border: 1px solid black; text-align:center; width: 30mm">Harga Satuan</td>
          <td style="border: 1px solid black; text-align:center; width: 30mm; ">Jumlah</td>
        </tr>
        @php
       
        $no = 1;   
        @endphp
         @foreach ($item_2['uraian'] as $key => $value)
         
            <tr>
                <td style="border: 1px solid black; text-align:center " >{{ $no }}</td>
                <td style="border: 1px solid black; text-align:left " >{{ $item_2['uraian'][$key] }}</td>
                <td style="border: 1px solid black; text-align:center " >{{ $item_2['volume'][$key] .'  '. $item_2['satuan'][$key] }}</td>
                <td style="border: 1px solid black; text-align:right " >{{ number_format($item_2['harga_satuan'][$key], 0, ',', '.') }}</td>
                <td style="border: 1px solid black; text-align:right " >{{ number_format($item_2['harga_satuan'][$key] *  $item_2['volume'][$key], 0, ',', '.') }}</td>
            </tr> 
              @php
              $no++
          @endphp
          @endforeach
          <tr>
              {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
              <td colspan="4" style="border: 1px solid black; text-align:right">Jumlah </td>
              <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah_2 , 0, ',', '.') }}</td>
          </tr>
          <tr>
              {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
              <td colspan="4" style="border: 1px solid black; text-align:right">PPN 11%</td>
              <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah_2 * (11/100), 0, ',', '.') }}</td>
            </tr>
            <tr>
                {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
                <td colspan="4" style="border: 1px solid black; text-align:right">PPh22 3%</td>
                <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah_2 * (3/100), 0, ',', '.') }}</td>
            </tr>
            
            <tr>
                <td colspan="4" style="border: 1px solid black; text-align:right">Jumlah Total (Harga + Pajak) </td>
                <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah_total_2 , 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid black; text-align:right; background-color: rgb(216,216,216)">Dibulatkan</td>
                <td style="border: 1px solid black; text-align:right; background-color: rgb(216,216,216)">{{ number_format(round($jumlah_total_2 ,-2), 0, ',', '.') }}</td>
            </tr>
          </table>
          <strong><p>Terbilang : {{ Terbilang::make(round($jumlah_total_2, -2)) }} rupiah</p></strong>
          <br><br><br>
          <table style="width: 100%">
            <tr>
                <td style="width: 8cm"><br></td>
                <td style="text-align: center;">{{ $penyedia2->jabata_pemilik ." " .$penyedia2->nama_penyedia}}
                    <br><br><br><br>
                    <strong>{{ $penyedia2->nama_pemilik}}</strong></td>
            </tr>
        </table>
   