<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body style="line-height: 1.3">
    <div class="ba-pembayaran">
    <h2 style="text-align: center; margin-bottom: 0">BERITA ACARA PEMBAYARAN</h2>
    <p style="text-align: center; margin-top: 0">NOMOR : {{$kegiatan->nomor }} /BA-Pemb/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran}}</p>


    <p style="text-align: justify">Pada hari ini <strong>{{ $tgl->isoFormat('dddd') }}</strong> tanggal <strong>{{ Terbilang::make($tgl->isoFormat('D')) }}</strong> bulan <strong>{{ $tgl->isoFormat('MMMM')}}</strong> tahun <strong>{{Terbilang::make(Auth::user()->tahun_anggaran)}}</strong> bertempat di <strong>  Kantor Desa {{ Auth::user()->desa}}</strong>, telah dilaksanakan pembayaran atas pekerjaan <strong>{{ $kegiatan->kegiatan}} </strong>antara :</p>

            <table class="ba-pembayaran" style="width:100%">
                <tr>
                    <td rowspan="3" style="vertical-align: top; width: 20px">I.</td>
                    <td style="width: 100px">Nama</td>
                    <td style="width:20px">: </td>
                    <td style="">{{ Auth::user()->kepala_desa}}</td>
                </tr>
                <tr style="">
                    <td>Jabatan</td>
                    <td style="width:20px">: </td>
                    <td>Kepala Desa</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td style="width:20px">: </td>
                    <td>{{Auth::user()->desa}}</td>
                </tr>
                <tr><td></td>
                    <td colspan="3">
                    Selanjutnya disebut <strong>PIHAK PERTAMA</strong>
                </td>
            </tr>
            </table>
            <table style="width: 100%">
                <tr>
                    <td rowspan="3" style="vertical-align: top; width: 20px">II.</td>
                    <td style="width: 100px">Nama</td>
                    <td style="width:20px">: </td>
                    <td>{{ $penyedia->nama_pemilik}}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: </td>
                    <td>{{ $penyedia->jabata_pemilik}}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>: </td>
                    <td>{{ $penyedia->alamat_penyedia}}</td>
                </tr>
                <tr><td></td>
                    <td colspan="3">
                    Selanjutnya disebut <strong>PIHAK KEDUA</strong>
                </td>
            </table>
            
<div style="line-height: 1.5">
    <p style="text-align: justify">
        PIHAK PERTAMA berdasarkan Surat Perjanjian Nomor {{ $kegiatan->nomor }}/PERJ/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }} atas pekerjaan {{ $kegiatan->kegiatan }} telah membayar untuk pekerjaan {{ $kegiatan->kegiatan }}. kepada PIHAK KEDUA sebesar Rp. {{ number_format(round($kegiatan->negosiasiHarga->total, -2), 0, ',', '.') }},-  <i>( {{ ucwords(Terbilang::make(round($kegiatan->negosiasiHarga->total, -2)) )}} Rupiah. )</i> <br>
        PIHAK KEDUA berdasarkan Surat Perjanjian Nomor {{ $kegiatan->nomor }}/PERJ/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }} atas pekerjaan {{ $kegiatan->kegiatan }} telah melaksanakan pekerjaan {{ $kegiatan->kegiatan }} sesuai permintaan PIHAK PERTAMA dan telah menerima pembayaran atas pekerjaan tersebut sebesar Rp. {{ number_format(round($kegiatan->negosiasiHarga->total,-2), 0, ',', '.') }},-  <i>( {{ ucwords(Terbilang::make(round($kegiatan->negosiasiHarga->total, -2)) )}} Rupiah. )</i>
    </p>
    <p style="text-align: justify">
        Pembayaran tersebut disaksikan oleh {{ Auth::user()->kepala_desa}}  Jabatan Kepala Desa {{Auth::user()->desa }} selaku Pemegang Kekuasaan Pengelolaan Keuangan Desa.
    </p>
    <p style="text-align: justify">
        Demikian Berita Acara ini dibuat rangkap 2 (dua) masing-masing bermeterai cukup dan mempunyai kekuatan hukum yang sama untuk dipertanggungjawabkan sesuai peraturan perundang-undangan yang berlaku.
    </p>
</div>

    <table width="100%">
        <tr>
            <td style="text-align: center;">PIHAK KEDUA <br><br><br><br></td>
            <td style="text-align: center;">PIHAK PERTAMA <br>PKA <br><br><br></td>
        </tr>
        <tr>
            <td style="text-align: center; text-decoration: underline"><b>{{$penyedia->nama_pemilik}}</b></td>
            <td style="text-align: center; text-decoration: underline"><b>{{ $kegiatan->pka }}</b></td>
        </tr>
    </table>
    <div style="text-align:center; line-height:1">    
        <p>Mengetahui, <br>
            Kepala Desa {{ Auth::user()->desa }} <br>
            Selaku<br>
            Pemegang Kekuasaan Pengelolaan Keuangan Desa
        </p>
        
        <br><br>
        
        <p style="text-decoration: underline"><b>{{ Auth::user()->kepala_desa }}</b></p>
    </div>   
    </div>
</body>
</html>