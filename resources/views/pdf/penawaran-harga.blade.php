<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penawaran Harga</title>
    <style>
        @page {
            margin: 0.5cm 2cm 1cm 2cm;
        }
        table {
            border-collapse: collapse;
        }
    </style>
</head>
<body>  
    @if(isset($penyedia1))
        <div >
            @include('pdf.penawaran-harga.surat')
        </div>
        <div style="page-break-before: always;">
            @include('pdf.penawaran-harga.pakta-integritas')
        </div>
        <div style="page-break-before: always;">
            @include('pdf.penawaran-harga.isian')
        </div>
        <div style="page-break-before: always;">
            @include('pdf.penawaran-harga.rincian')
        </div>
    @endif
    @if (isset($penyedia2))
            <div style="page-break-before: always;">
                @include('pdf.penawaran-harga2.surat')
            </div>
            <div style="page-break-before: always;">
                @include('pdf.penawaran-harga2.pakta-integritas')
            </div>
            <div style="page-break-before: always;">
                @include('pdf.penawaran-harga2.isian')
            </div>
            <div style="page-break-before: always;">
                @include('pdf.penawaran-harga2.rincian')
            </div>        
    @endif
</body>
</html>