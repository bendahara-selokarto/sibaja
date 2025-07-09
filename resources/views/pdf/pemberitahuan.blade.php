<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemberitahuan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    

    <style>
        @page :first{
            margin: 0;
            size: A4 portrait;
        }
        div.new-cover {
            padding: 25mm;
            line-height: 1;
            text-align: center;
            font-weight: bold;
            font-family: 'Poppins', serif;
        }
       
        table {
            border-collapse: collapse;
        }
        table, tr, td {
            vertical-align: top;
            /* border: 1px solid black; */
        }
        div.cover {
            background-image: url("cover.png");
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-size: cover;
            height: 100%;
            }
        .footer{
        position: absolute;
        bottom: 80pt;
        text-align: center;
        width: 100%;
        }
    .new-cover::before {
      content: "";
      position: absolute;
      top: 1cm;
      left: 1cm;
      right: 1cm;
      bottom: 1cm;
      border: 8px double #2c3e50;
      /* border: 6px inset #777;      */
      /* border: 6px groove #555; */
      pointer-events: none;
    }
    </style>  
</head>
<body>
    <div>
        @include('pdf.pemberitahuan.new-cover') 
    </div>
    <div style="page-break-before: always;">
        @include('pdf.pemberitahuan.check-list-PBJ')
    </div>
    <div style="page-break-before: always;">
        @include('pdf.pemberitahuan.surat-pemberitahuan')    
    </div>
</body>
</html>

