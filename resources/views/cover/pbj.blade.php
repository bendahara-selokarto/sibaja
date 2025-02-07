<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>cover-pengadaan-barang-dan-jasa</title>
    <style>
        body .cover {
            background-image: url("cover.png");
            /* background-color: #cccccc; */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-size: cover;
            height: 100%;
            }
        /* @page {
            margin: 0px;
        } */

        .footer{
            position: absolute;
            bottom: 80pt;
            text-align: center;
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="pdf.css">
</head>
<body>
    <div class="cover" style="height: 100%;">
    
    <div class="text-center"><div class="img">
        <img src="batang.png" alt="batang" style="width: 3cm; height:auto;">
    </div>

        <h1 style="text-align: left; vertical-align:80%">DOKUMEN</h1>
        <h3 style="text-align: left; margin:0px;">PENGADAAN BARANG DAN JASA</h3>
        <h3 style="text-align: left">KEGIATAN {{strToUpper($kegiatan->kegiatan)}}</h3>
    </div>
    <div class="footer ">
        <h3 style="color: #ffff">DESA {{ strToUpper(Auth::user()->desa) }}</h3>
        <h3 style="color: #ffff">KECAMATAN {{ strToUpper(Auth::user()->kecamatan) }}</h3>
        <h3 style="color: #ffff">KABUPATEN BATANG</h3>
        <h3 style="color: #ffff">TAHUN {{ strToUpper(Auth::user()->tahun_anggaran) }}</h3>
    </div>
</div>
    
</body>
</html>

