<div>
    <h1>REKAP</h1>
    <table>
        <tr>
            <td>nomor PBJ</td>
            <td>:</td>
            <td>{{$pemberitahuan->no_pbj}}</td>
        </tr>
        <tr>
            <td>Kode Rekening</td>
            <td>:</td>
            <td>{{$kegiatan->rekening_apbdes}}</td>
        </tr>
        <tr>
            <td>Kegiatan</td>
            <td>:</td>
            <td>{{$kegiatan->kegiatan}}</td>
        </tr>
        <tr>
            <td>Lokasi Kegiatan</td>
            <td>:</td>
            <td>{{$kegiatan->lokasi_kegiatan}}</td>
        </tr>
        <tr>
            <td>PKA</td>
            <td>:</td>
            <td>{{$kegiatan->pka}}</td>
        </tr>
        <tr>
            <td>Ketua TPK</td>
            <td>:</td>
            <td>{{$kegiatan->ketua_tpk}}</td>
        </tr>
        <tr>
            <td>Sekretaris TPK</td>
            <td>:</td>
            <td>{{$kegiatan->sekretaris_tpk}}</td>
        </tr>
        <tr>
            <td>Anggota TPK</td>
            <td>:</td>
            <td>{{$kegiatan->anggota_tpk}}</td>
        </tr>
        <tr>
            <td>Belanja</td>
            <td>:</td>
            <td>{{$pemberitahuan->pekerjaan}}</td>
        </tr>
        <tr>
            <td>Penyedia Pemenang</td>
            <td>:</td>
            <td>{{$namaPenyedia1}}</td>
        </tr>
        <tr>
            <td>Penyedia 2</td>
            <td>:</td>
            <td>{{$namaPenyedia2}}</td>
        </tr>
        <tr>
            <td>Tanggal Pemberitahuan</td>
            <td>:</td>
            <td>{{hari($pemberitahuan->tgl_surat_pemberitahuan) .", ". tanggal_indo($pemberitahuan->tgl_surat_pemberitahuan)}}</td>
        </tr>
        <tr>
            <td>Tanggal Penawaran</td>
            <td>:</td>
            <td>{{hari($penawaran->tgl_penawaran) .", ". tanggal_indo($penawaran->tgl_penawaran)}}</td>
        </tr>
        <tr>
            <td>Tanggal Negosiasi</td>
            <td>:</td>
            <td>{{hari($negosiasiHarga->tgl_negosiasi) .", ". tanggal_indo($negosiasiHarga->tgl_negosiasi)}}</td>
        </tr>
        <tr>
            <td>Tanggal Persetujuan</td>
            <td>:</td>
            <td>{{hari($negosiasiHarga->tgl_persetujuan) .", ". tanggal_indo($negosiasiHarga->tgl_persetujuan)}}</td>
        </tr>
        <tr>
            <td>Tanggal Akhir Perjanjian</td>
            <td>:</td>
            <td>{{hari($negosiasiHarga->tgl_akhir_perjanjian) .", ". tanggal_indo($negosiasiHarga->tgl_akhir_perjanjian)}}</td>
        </tr>
        <tr>
            <td>Tanggal Invoice</td>
            <td>:</td>
            <td>{{hari($pembayaran->tgl_invoice) .", ". tanggal_indo($pembayaran->tgl_invoice)}}</td>
        </tr>
        <tr>
            <td>Tanggal Pembayaran</td>
            <td>:</td>
            <td>{{hari($pembayaran->tgl_pembayaran_cms) .", ". tanggal_indo($pembayaran->tgl_pembayaran_cms)}}</td>
        </tr>
    </table>
</div>
<div style="page-break-before: always;">
        @include('pdf.pemberitahuan.check-list-PBJ')
</div>
