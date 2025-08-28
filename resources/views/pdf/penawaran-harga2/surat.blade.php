@if(!empty($penyedia2->kop_surat))
<img 
    src="{{public_path('storage/'.$penyedia2->kop_surat)}}" 
    alt=" " 
    style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
    onerror="this.src='{{ asset('') }}'" 
>
@else
<table style="width: 100%">
    <tr>
        <td style="width: 120px">            
            <img 
                src="{{public_path('storage/'.$penyedia2->logo_penyedia)}}" 
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
<table style="width: 100%">
    <tr>
        <td></td>
        <td style="width: 6cm"></td>
        <td></td>
        <td style='text-align:right'>{{ ucwords($penyedia2->kabupaten) }}, {{ Illuminate\Support\Carbon::parse($penawaran_1->tgl_penawaran)->isoFormat('D MMMM Y') }}</td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td>: {{ $penawaran_2->no_penawaran }}/SPH/{{ Auth::user()->tahun_anggaran }}</td>
        <td></td>
        <td>Kepada</td>
    </tr>
    <tr>
        <td>Lampiran</td>
        <td>: -</td>
        <td style="width: 40px">Yth. </td>
        <td rowspan="3" style="vertical-align: top">Ketua Tim Pelaksana Kegiatan {{ $kegiatan->kegiatan }}</td>
    </tr>
    <tr>
        <td>Perihal</td>
        <td>: Penawaran Harga</td>
        <td></td>
        
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>di-</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:center; text-decoration: underline">{{ strToUpper(Auth::user()->desa )}}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="3">
            <p>Dengan Hormat,</p>
            <p>Yang bertanda tangan dibawah ini</p>
        </td>
    </tr>
</table>
<div style="margin-left: 2.4cm">
    <table>
        <tr>
            <td>Nama</td>
            <td>: {{ $penyedia2->nama_pemilik }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ $penyedia2->jabata_pemilik }} {{ $penyedia2->nama_penyedia }}</td>
        </tr> 
        <tr>
            <td>Alamat</td>
            <td>: {{ $penyedia2->alamat_penyedia }}</td>
        </tr> 
    </table>
   
    <p style="text-align: justify">Menanggapi Surat permintaan penawaran dari Ketua Tim Pelaksana Kegiatan Nomor {{ $pemberitahuan->no_pbj }}/{{ Auth::user()->kode_desa }}/ {{ Auth::user()->tahun_anggaran }} tanggal {{ Illuminate\Support\Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan)->isoFormat('D MMMM Y')}} perihal Pemberitahuan permintaan penawaran, maka bersama ini kami mengajukan penawaran harga untuk melaksanakan pekerjaan tersebut.</p>
    <p style="text-align: justify">Adapun harga penawaran yang kami ajukan adalah sebesar <strong>Rp. {{ number_format(round($jumlah_total_2, 0) , 0, ',', '.') }} ({{ Terbilang::make(round($jumlah_total_2, 0))  }} rupiah)</strong>   dengan rincian sebagaimana terlampir.</p>
    <p>Sesuai dengan persyaratan yang diminta bersama ini kami sampaikan :</p>
    <ol type="a">
        <li>Form isian pengadaan barang/jasa</li>
        <li>Pakta Integritas</li>
        <li>Daftar penawaran harga termasuk pajak, bea meterai dan jasa penggandaan;</li>
        <li>Foto copy Surat Ijin Usaha Perdagangan (SIUP);</li>
        <li>Foto copy Nomor Pokok Wajib Pajak (NPWP)</li>
    </ol>
    <p>Harga penawaran tersebut di atas sudah termasuk Pajak Pertambahan Nilai serta biaya lainnya yang wajib dilunasi oleh kami.</p>
    
    
    <table>
        <p>Demikian di sampaikannya Surat Penawaran ini, maka kami menyatakan sanggup dan akan tunduk pada semua ketentuan yang berlaku.</p>
        <div style="text-align:center; margin-left: auto; width:300px">
            <p>Hormat Kami, </p>
            <p>{{ $penyedia2->jabata_pemilik ." " .$penyedia2->nama_penyedia }}</p>
            <br><br>
            <p><strong>{{ $penyedia2->nama_pemilik }}</strong></p>
        </div>
    </table>