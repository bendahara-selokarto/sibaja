<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body style="line-height: 1.5">
    <div class="ba-pembayaran">
    <h2 style="text-align: center; margin-bottom: 0">BERITA ACARA PEMBAYARAN</h2>
    <p style="text-align: center; margin-top: 0">NOMOR : {{$kegiatan->nomor }} /BA-Pemb/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran}}</p>


    <p>Pada hari ini <strong>{{ $tgl->isoFormat('dddd') }}</strong> tanggal <strong>{{ Terbilang::make($tgl->isoFormat('D')) }}</strong> bulan <strong>{{ $tgl->isoFormat('MMMM')}}</strong> tahun <strong>{{Auth::user()->tahun_anggaran}}</strong> bertempat di <strong>  Balaidesa {{ Auth::user()->desa}} </strong>, telah dilaksanakan pembayaran atas pekerjaan <strong>{{ $kegiatan->kegiatan}} </strong>antara :</p>

    <ol type="I">
        <li >
            <table>
                <tr style="height: 3px">
                    <td style="width: 100px">Nama</td>
                    <td style="">:</td>
                    <td style="">{{ Auth::user()->kepala_desa}}</td>
                </tr>
                <tr style="">
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>Kepala Desa</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{Auth::user()->desa}}</td>
                </tr>
            </table>
        </li>
        <p>Selanjutnya disebut <strong>PIHAK PERTAMA</strong></p>
        <li>
            <table>
                <tr>
                    <td style="width: 100px">Nama</td>
                    <td>:</td>
                    <td>{{ $penyedia->nama_pemilik}}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $penyedia->jabata_pemilik}}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $penyedia->alamat_penyedia}}</td>
                </tr>
            </table>
        </li>
        <p>Selanjutnya disebut <strong>PIHAK KEDUA</strong></p>
    </ol>

    <p style="text-align: justify">
        PIHAK PERTAMA berdasarkan Surat Perjanjian Nomor {{ $kegiatan->nomor }}/PERJ/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }} atas pekerjaan {{ $kegiatan->kegiatan }} telah membayar untuk pekerjaan {{ $kegiatan->kegiatan }}. kepada PIHAK KEDUA sebesar Rp. {{ number_format($kegiatan->negosiasiHarga->harga_negosiasi, 0, ',', '.') }},-  <i>( {{ ucwords(Terbilang::make($kegiatan->negosiasiHarga->harga_negosiasi) )}} Rupiah. )</i> <br>
        PIHAK KEDUA berdasarkan Surat Perjanjian Nomor {{ $kegiatan->nomor }}/PERJ/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }} atas pekerjaan {{ $kegiatan->kegiatan }} telah melaksanakan pekerjaan {{ $kegiatan->kegiatan }} sesuai permintaan PIHAK PERTAMA dan telah menerima pembayaran atas pekerjaan tersebut sebesar Rp. {{ number_format($kegiatan->negosiasiHarga->harga_negosiasi, 0, ',', '.') }},-  <i>( {{ ucwords(Terbilang::make($kegiatan->negosiasiHarga->harga_negosiasi) )}} Rupiah. )</i>
    </p>
    <p style="text-align: justify">
        Pembayaran tersebut disaksikan oleh {{ Auth::user()->kepala_desa}}  Jabatan Kepala Desa {{Auth::user()->desa }} selaku Pemegang Kekuasaan Pengelolaan Keuangan Desa.
    </p>
    <p style="text-align: justify">
        Demikian Berita Acara ini dibuat rangkap 2 (dua) masing-masing bermeterai cukup dan mempunyai kekuatan hukum yang sama untuk dipertanggungjawabkan sesuai peraturan perundang-undangan yang berlaku.
    </p>

    <table width="100%">
        <tr>
            <td style="text-align: center;">PIHAK KEDUA</td>
            <td></td>
            <td style="text-align: center;">PIHAK PERTAMA</td>
        </tr>
        <tr>
            <td style="height: 60px;"></td>
            <td></td>
            <td style="text-align: center;">PKA</td>
        </tr>
        <tr>
            <td style="text-align: center;">{{$penyedia->nama_pemilik}}</td>
            <td></td>
            <td style="text-align: center;">{{ $kegiatan->pka }}</td>
        </tr>
    </table>
    <div style="text-align:center">    
        <p>Mengetahui, <br>
            Kepala Desa {{ Auth::user()->desa }} <br>
            Selaku<br>
            Pemegang Kekuasaan Pengelolaan Keuangan Desa
        </p>
        
        <br>
        
        <p>{{ Auth::user()->kepala_desa }}</p>
    </div>   
    </div>
</body>
</html>