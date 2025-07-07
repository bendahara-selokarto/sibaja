
<div class="persetujuan-penawaran" style="margin: 10px 20px 20px 50px">
<x-kop-tpk/>
<p style="text-align: right">{{Auth::user()->desa .", "}}{{illuminate\support\Carbon::parse($data['penawaranHarga']->tgl_penawaran)->isoFormat('D MMMM Y') }} </p>
<table style="margin-bottom: 40px">
    <tr>
        <td style="width: 60px">Nomor</td>
        <td style="10px">: </td>
        <td style="width: 360px">{{ $data['pemberitahuan']->no_pbj }}/PPH/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran}}</td>
        <td style="text-indent: 40px">Kepada :</td>
    </tr>
    <tr>
        <td>Lamp</td>
        <td>: </td>
        <td>1 Bandel</td>
        <td>Yth ;</td>
    </tr>
    <tr>
        <td>Hal</td>
        <td>:</td>
        <td> Persetujuan Penawaran Harga</td>
        <td>{{$data['penyedia']->nama_pemilik }} <br>({{$data['penyedia']->nama_penyedia}})</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>di-</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center">TEMPAT</td>
    </tr>
</table>
<div style="margin-left: 74px">
    <p style="line-height: 1; text-align:justify">
        Berdasarkan Surat Saudara Nomor {{$data['penawaranHarga']->no_penawaran }}/SPH/{{Auth::user()->tahun_anggaran }} tanggal {{illuminate\support\Carbon::parse($data['penawaranHarga']->tgl_penawaran)->isoFormat('D MMMM Y') }} perihal Penawaran Harga dan Berdasarkan Berita Acara Klarifikasi dan Negoisasi Harga Nomor : {{$data['pemberitahuan']->no_ba_negosiasi }} dan ……………, maka kami sampaikan bahwa setelah menerima dan mempelajari isi surat Saudara serta Berita Acara Klarifikasi dan Negoisasi Harga, maka pada prinsipnya kami tidak keberatan dan dapat menerima dengan penawaran harga yang telah disepakati sebesar Rp {{ number_format($data['negosiasiHarga']->harga_total, 0, ',', '.') }},- ({{ Terbilang::make($data['negosiasiHarga']->harga_total)}} rupiah ).
    </p>
    <p style="line-height: 1; text-align:justify">
        Sehubungan dengan hal tersebut diatas, diminta kehadiran Saudara besok pada :
    </p>
        <table>
            <tr>
                <td>Hari</td>
                <td>:</td>
                <td><strong> {{ $data['negosiasiHarga']->tgl_negosiasi->isoFormat('dddd') }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $data['negosiasiHarga']->tgl_negosiasi->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td>Jam</td>
                <td>:</td>
                <td>09.30 WIB s.d selesai</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>: </td>
                <td>Kantor Desa {{ Auth::user()->desa}}</td>
            </tr>
            <tr>
                <td>Acara</td>
                <td>:</td>
                <td> Penandatanganan Surat Perjanjian</td>
            </tr>
        </table>
    <p style="line-height: 1; text-align:justify">
        Demikian atas perhatian dan kerjasamanya diucapkan terima kasih.
    </p>
    <table style="text-align: center; width: 100%">
        <tr>
            <td >Mengetahui, </td>
            <td></td>
        </tr>
        <tr>
            <td>Kepala Desa {{Auth::user()->desa}}<br><br><br><br></td>
            <td>Tim Pelakasana Kegiatan<br><br><br><br></td>
        </tr>
        <tr>
            <td>{{Auth::user()->kepala_desa}}</td>
            <td>{{ $data['kegiatan']->ketua_tpk}}</td>
        </tr>
    </table>        
</div>
</div>
