<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>       
    @page { margin: 0; }
    </style>
    <title>BAST TPK ke PKA</title>
</head>
<body style="margin-left: 3cm; margin-top: 1cm; margin-right: 2cm; margin-bottom: 2cm;">
    <h1 style="text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 1px; font-size: 14pt;">BERITA ACARA SERAH TERIMA</h1>
    <span style="display: block; margin-top: 1px; text-align: center; font-size: 12pt;">
        Nomor: {{ $kegiatan->nomor }}/BAST/{{Auth::user()->kode_desa}}/{{Auth::user()->tahun_anggaran }}<br>
    </span>
    <hr style="border: 2px solid black; margin-top: 10px; margin-bottom: 20px;">
    <p style="margin-top: 1rem; text-align: justify; font-size: 12pt;">
        Pada hari ini, <strong>{{ ucwords($tgl->isoFormat("dddd"))}} </strong> tanggal <strong>{{ ucwords(Terbilang::make($tgl->isoFormat("D")))}} </strong> bulan <strong>{{ ucwords($tgl->isoFormat("MMMM")) }} </strong> tahun <strong>{{ Terbilang::make($tgl->isoFormat("Y")) }} </strong>, Kami selaku Tim Pelaksana Kegiatan (TPK) Pengadaan Barang/Jasa Desa {{Auth::user()->desa }} yang ditetapkan dengan Surat Keputusan Kepala Desa {{Auth::user()->desa }} Nomor: 410/12/2024 tanggal 03 Juni 2024 Tahun Anggaran 2024, telah menyelesaikan 100% (seratus persen) pekerjaan secara Swakelola dan menyerahkan seluruh hasil kegiatan pengadaan kepada Kasi Kesejahteraan Desa {{Auth::user()->desa }} Kecamatan {{Auth::user()->kecamatan }} Kabupaten Batang dengan baik sesuai yang dipersyaratkan dalam dokumen persiapan.
    </p>
    <p style="text-align: justify; font-size: 12pt; margin-bottom: 1rem;">
        Selanjutnya Kasi Kesejahteraan menerima hasil kegiatan pengadaan yaitu berupa:
    </p>
    <table>
        <thead></thead>
        <tbody>
            <tr>
                <td>1.</td>
                <td>Nama Kegiatan</td>
            </tr>
        </tbody>
    </table>









        
        <table style="font-size: 12pt; border-collapse: collapse; width: 100%; margin-bottom: 1.5rem;">
            <tr>
            <td rowspan="4" style="width: 4%; vertical-align: top; padding: 4px;">I. </td>
            <td style="width: 20%; padding: 4px;">Nama</td>
            <td style="width: 50%; padding: 4px;">: {{ Auth::user()->kepala_desa ?? '-' }}</td>
            </tr>
            <tr>
            <td style="padding: 4px;">Jabatan</td>
            <td style="padding: 4px;">: Kepala Desa {{ Auth::user()->desa ?? '-' }}</td>
            </tr>
            <tr>
            <td style="padding: 4px;">Alamat</td>
            <td style="padding: 4px;">: Desa {{ Auth::user()->desa ?? '-' }}</td>
            </tr>
            <tr>
            <td colspan="2" style="padding: 4px;">Selanjutnya disebut PIHAK Pertama</td>
            </tr>
            <tr>
            <td rowspan="4" style="vertical-align: top; padding: 4px;">II. </td>
            <td style="padding: 4px;">Nama</td>
            <td style="padding: 4px;">: {{ $kegiatan->pka ?? '-' }}</td>
            </tr>
            <tr>
            <td style="padding: 4px;">Jabatan</td>
            <td style="padding: 4px;">: Pelaksana Kegiatan Anggaran</td>
            </tr>
            <tr>
            <td style="padding: 4px;">Instansi</td>
            <td style="padding: 4px;">: Pemerintah Desa {{ Auth::user()->desa ?? '' }}</td>
            </tr>
            <tr>
            <td colspan="2" style="padding: 4px;">Selanjutnya disebut PIHAK KEDUA</td>
            </tr>
        </table>
        <p style="text-align: justify; font-size: 12pt; margin-bottom: 1rem;">
            PIHAK PERTAMA menyatakan bahwa telah menerima hasil pekerjaan berupa {{ $kegiatan->kegiatan}} dalam keadaan baik dari PIHAK KEDUA.
        </p>
        <p style="text-align: justify; font-size: 12pt; margin-bottom: 1rem;">
            PIHAK KEDUA telah menyerahkan hasil pekerjaan berupa {{ $kegiatan->kegiatan}} dalam keadaan baik kepada PIHAK PERTAMA.
        </p>
        <p style="text-align: justify; font-size: 12pt;">
            Demikian Berita Acara ini dibuat rangkap 2 (dua) masing-masing bermeterai cukup dan mempunyai kekuatan hukum yang sama untuk dipertanggungjawabkan sesuai peraturan perundang-undangan yang berlaku.
        </p>
        <table style="width: 100%; margin-top: 2rem; font-size: 12pt; text-align: center;">
            <tr>
                <td style="width: 50%;">
                    Kepala Desa Selokarto<br>Selaku<br>Pemegang Kekuasaan Pengelolaan<br>Keuangan Desa<br> <br><br><br>
                    <u>{{ Auth::user()->kepala_desa ?? '-' }}</u>
                    <br>Pihak Kedua
                    
                </td>
                <td style="width: 50%;">
                    Pelaksana Kegiatan Anggaran<br><br><br><br><br><br><br>
                    <u>{{ $kegiatan->pka ?? '-' }}</u>
                    <br>Pihak Pertama
                </td>
            </tr>
        </table>
        

    </body>
</html>
