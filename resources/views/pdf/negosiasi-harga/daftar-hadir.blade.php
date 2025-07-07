<div class="daftar-hadir">
    <table>
        <tr>
            <td>Daftar Hadir</td>
            <td>: </td>
            <td>Negosiasi dan klarifikasi Harga</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: </td>
            <td>{{ $tgl_negosiasi ?? ""}}</td>
        </tr>
        <tr>
            <td>Jam</td>
            <td>: </td>
            <td>09.30 WIB s.d Selesai</td>
        </tr>
        <tr>
            <td>Acara</td>
            <td>: </td>
            <td>Negosiasi Harga</td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>: </td>
            <td>Kantor Desa {{Auth::user()->desa}} </td>
        </tr>
    </table>
    <br>
    <table class="daftar-hadir">
        <tr>
            <th>No.</th>
            <th>N a m a</th>
            <th>Jabatan</th>
            <th >Tanda Tangan</th>            
        </tr>
       <tr>
            <td>1</td>
            {{-- <td>{{ $data['kegiatan']->ketua_tpk }}</td> --}}
            <td> .............. </td>
            <td>Ketua TPK</td>
            <td> .............. </td>
       </tr>
       <tr>
            <td>2</td>
            <td> .............. </td>
            <td> .............. </td>
            <td> .............. </td>
        </tr>
        <tr>
            <td>3</td>
            <td> .............. </td>
            <td> .............. </td>
            <td> .............. </td>
        </tr>
        <tr>
            <td>4</td>
            {{-- <td>{{ $data['penyedia']->nama_pemilik}}</td> --}}
            {{-- <td>{{ $data['penyedia']->jabata_pemilik}} <br> ({{ $data['penyedia']->nama_penyedia}})</td> --}}
            <td> .............. </td>
            <td> .............. </td>
            <td> .............. </td>
       </tr>
    </table>
    <div style="text-align: center; width: 300px; margin-left:auto">
        <p>{{Auth::user()->desa}}, {{$data['negosiasiHarga']->tgl_negosiasi->isoFormat('D MMMM Y') }}</p>
        <p>Tim Pelaksana Kegiatan</p>
        <p>Ketua</p>
        <br><br><br>
        <p>{{ $data['kegiatan']->ketua_tpk }}</p>
    </div>
</div>
