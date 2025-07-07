<div style="page-break-before: always; margin: 100px 40px 40px 100px">
<div class="papan-pengumuman">
    <h2 style="text-align: center">PAPAN PENGUMUMAN</h2>
    <br><br>
    <table style="padding-left: 30px">
        <tr>
            <td>1. Jenis Pekerjaan</td>
            <td>:</td>
            <td> {{ $data['kegiatan']->kegiatan }}</td>
        </tr>       
        <tr>
            <td>2. Lokasi Pekerjaan</td>
            <td>:</td>
            <td> ......................... </td>
        </tr>       
        <tr>
            <td>3. Anggaran</td>
            <td>:</td>
            <td> ......................... </td>
        </tr>       
    </table>
    <br><br>
    <table class="papan-pengumuman">
        <tr>
            <th>NO</th>
            <th>JENIS PEKERJAAN</th>
            <th>BANYAKNYA</th>
            <th>HARGA <br> SATUAN <br>(Rp.)</th>
            <th>JUMLAH <br>(Rp.)</th>
        </tr>
        @foreach ($data['item']['uraian'] as $k => $v )
            
        <tr>
          <td style="text-align: center">{{ $loop->iteration }}</td>
            <td >{{ $data['item']['uraian'][$k] }}</td>
            <td style="text-align: center">{{ $data['item']['volume'][$k] . ' '. $data['item']['satuan'][$k]  }}</td>
            <td style="text-align: right"> {{ number_format($data['item']['harga_negosiasi'][$k] * 1.14 , 0, ',', '.') }}</td>
            <td style="text-align: right"> {{ number_format(round($data['item']['volume'][$k] * 1.14 * $data['item']['harga_negosiasi'][$k], -2), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right">JUMLAH</td>
            <td style="text-align: right">{{ number_format(round($data['negosiasiHarga']->harga_total, -2), 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    <br>
    <table>
        <tr>
            <td>Nama Rekanan</td>
            <td>: </td>
            <td>{{ $data['penyedia']->nama_penyedia}}</td>
        </tr>
        <tr>
            <td>A l a m a t</td>
            <td>: </td>
            <td>{{ $data['penyedia']->alamat_penyedia}}</td>
        </tr>
        <tr>
            <td>Harga Penawaran</td>
            <td>: </td>
            <td>{{ number_format(round($data['nilai_total_penawaran'], -2),0 ,"," ,".")}} <i> ( {{ Terbilang::make(round($data['nilai_total_penawaran'], -2)) }} rupiah )</i></td>
        </tr>
        <tr>
            <td>Harga Negosiasi</td>
            <td>:</td>
            <td>{{ number_format(round($data['negosiasiHarga']->harga_total, -2),0 ,"," ,".") }} <i> ( {{ Terbilang::make(round($data['negosiasiHarga']->harga_total, -2)) }} rupiah )</i></td>
        </tr>
    </table>
    <div style="text-align: center">
        <p>Ketua TPK</p><br><br><br>
        <p>{{$data['kegiatan']->ketua_tpk}}</p>
    </div>
</div>
</div>