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
    <p style="text-align: center; margin-top: 0">NOMOR : {{$kegiatan->nomor }} /BA-Pembayaran/{{Auth::user()->tahun_anggaran}}</p>


    <p>Pada hari ini <strong></strong> tanggal <strong></strong> bulan <strong></strong> tahun <strong>{{Auth::user()->tahun_anggaran}}</strong> bertempat di <strong>  Balaidesa {{ Auth::user()->desa}} </strong>, telah dilaksanakan pembayaran atas pekerjaan <strong>{{ $kegiatan->kegiatan}} </strong>antara :</p>

    <ol type="I" style="line-height: 1">
        <li>
            <table >
                <tr style="height: 3px">
                    <td style="height: 3px">Nama</td>
                    <td style="height: 3px">:</td>
                    <td style="height: 3px">…………………………</td>
                </tr>
                <tr style="height: 3px">
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>…………………………</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>…………………………</td>
                </tr>
            </table>
            <p>Selanjutnya disebut <strong>PIHAK PERTAMA</strong></p>
        </li>
        <li>
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>…………………………</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>…………………………</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>…………………………</td>
                </tr>
            </table>
            <p>Selanjutnya disebut <strong>PIHAK KEDUA</strong></p>
        </li>
    </ol>

    <p>
        PIHAK PERTAMA berdasarkan Surat Perjanjian Nomor ………. atas pekerjaan …… telah membayar untuk pekerjaan ……………. kepada PIHAK KEDUA sebesar Rp…………… (…………..) <br>
        PIHAK KEDUA berdasarkan Surat Perjanjian Nomor ………. atas pekerjaan …… telah melaksanakan pekerjaan ……………. sesuai permintaan PIHAK PERTAMA dan telah menerima pembayaran atas pekerjaan tersebut sebesar Rp…………… (…………..)
    </p>
    <p>
        Pembayaran tersebut disaksikan oleh {{ Auth::user()->kepala_desa}}  Jabatan Kepala Desa {{Auth::user()->desa }} selaku Pemegang Kekuasaan Pengelolaan Keuangan Desa.
    </p>
    <p>
        Demikian Berita Acara ini dibuat rangkap 2 (dua) masing-masing bermeterai cukup dan mempunyai kekuatan hukum yang sama untuk dipertanggungjawabkan sesuai peraturan perundang-undangan yang berlaku.
    </p>

    <br>

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