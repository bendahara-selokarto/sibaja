
@if(!empty($penyedia1->kop_surat))
<img 
src="{{public_path('storage/'.$penyedia1->kop_surat)}} " 
alt=" " 
style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
onerror="this.src='{{ asset('') }}'" 
>
@else
<table style="width: 100%">
    <tr>
        <td style="width: 120px">            
            <img 
                src="{{public_path('storage/'.$penyedia1->logo_penyedia)}}" 
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
            <td style="width: 40mm">Kegiatan</td>
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
         @foreach ($item as $item)
            <tr>
                <td style="border: 1px solid black; text-align:center " >{{ $loop->iteration }}</td>
                <td style="border: 1px solid black; text-align:left " >{{ $item['uraian'] }}</td>
                <td style="border: 1px solid black; text-align:center " >{{ $item['volume'] .'  '. $item['satuan'] }}</td>
                <td style="border: 1px solid black; text-align:right " >{{ number_format(($item['harga_satuan'] * (100/114)), 0, ',', '.') }}</td>
                <td style="border: 1px solid black; text-align:right " >{{ number_format(($item['harga_satuan'] * (100/114)) *  $item['volume'], 0, ',', '.') }}</td>
            </tr> 
          @endforeach
          <tr>
              <td colspan="4" style="border: 1px solid black; text-align:right">Jumlah</td>
              <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah, 0, ',', '.') }}</td>
          </tr>
          <tr>
              {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
              <td colspan="4" style="border: 1px solid black; text-align:right">PPN 11%</td>
              <td style="border: 1px solid black; text-align:right">{{ number_format($ppn_1, 0, ',', '.') }}</td>
            </tr>
            <tr>
                {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
                <td colspan="4" style="border: 1px solid black; text-align:right">PPh22 3%</td>
                <td style="border: 1px solid black; text-align:right">{{ number_format($pph_22_1, 0, ',', '.') }}</td>
            </tr>
            
            <tr>
                {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
                <td colspan="4" style="border: 1px solid black; text-align:right">Jumlah Total (Harga + Pajak)</td>
                <td style="border: 1px solid black; text-align:right">{{ number_format($jumlah * (114/100), 0, ',', '.') }}</td>
            </tr>
            <tr>
                {{-- <td colspan="2" style="border: 1px solid black;"></td>         --}}
                <td colspan="4" style="border: 1px solid black; text-align:right; background-color: rgb(216,216,216)">Dibulatkan</td>
                <td style="border: 1px solid black; text-align:right; background-color: rgb(216,216,216)">{{ number_format(round($jumlah * (114/100), -2 ), 0, ',', '.') }}</td>
            </tr>
          </table>
          <strong><p>Terbilang : {{ Terbilang::make(round($jumlah_total_1, -2)) }} rupiah</p></strong>
          <br><br><br>
          <table style="width: 100%">
            <tr>
                <td style="width: 8cm"><br></td>
                <td style="text-align: center;">{{ $penyedia1->jabata_pemilik ." " .$penyedia1->nama_penyedia}}
                    <br><br><br><br>
                    <strong>{{ $penyedia1->nama_pemilik}}</strong></td>
            </tr>
        </table>

        
   