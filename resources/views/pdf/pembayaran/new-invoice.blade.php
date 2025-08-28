<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
</head>
<body style="margin-left: 2cm;">
    <img 
src="{{ public_path('storage/'.$penyedia->kop_surat) }}" 
alt=" " 
style="max-width: 21cm; width: 100%; max-height: 3cm; height: auto;"
onerror="this.src='{{ asset('') }}'" 
>
<table style="width: 40%; margin-bottom: 10px;">
    <tr style="padding: 1px;">
        <td>No. </td>
        <td>: {{ rand(21,97)}}/IVC/{{ Auth::user()->tahun_anggaran}}</td>
    </tr>
    <tr style="padding: 1px;">
        <td>Hal</td>
        <td>: INVOICE</td>
    </tr>
</table>
<p style="margin: 1px;">Kepada Yth;</p>
<p style="margin: 1px;"><strong>Desa {{ ucwords(Auth::user()->desa) }} Kec. Pecalungan Kab. Batang</strong></p>
<p style="margin: 1px;">Di Tempat</p>
    
    <h1 style="text-align: center;">INVOICE</h1>

    <table style="border-collapse: collapse; width: 90%; border: 1px solid black; margin: auto;">
        <tr style="border: 1px solid black;">
            <td style="border: 1px solid black; text-align: center;">NO</td>
            <td style="border: 1px solid black; text-align: center;">URAIAN</td>
            <td style="border: 1px solid black; text-align: center;">VOL/SAT</td>
            <td style="border: 1px solid black; text-align: center;">HARGA</td>
            <td style="border: 1px solid black; text-align: center;">JUMLAH</td>
        </tr>
        @foreach ($item['uraian'] as $v )
            <tr style="border: 1px solid black;">
                <td style="text-align: center; border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black;">{{ $item['uraian'] }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $item['volume'] . ' '. $item['satuan'] }}</td>
                <td style="text-align: right; border: 1px solid black;">{{ number_format($item['harga_negosiasi'], 0, ',', '.') }}</td>
                <td style="text-align: right; border: 1px solid black;">{{ number_format($item['jumlah_negosiasi'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr style="border: 1px solid black;">
            <td style="text-align: right; border: 1px solid black;" colspan="4">Jumlah</td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($harga_negosiasi , 0, ',', '.') }}</td>
        </tr>
        <tr style="border: 1px solid black;">
            <td style="text-align: right; border: 1px solid black;" colspan="4">PPN</td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($negosiasiHarga['ppn'], 0, ',', '.') }}</td>
        </tr>
        <tr style="border: 1px solid black;">
            <td style="text-align: right; border: 1px solid black;" colspan="4">PPh Pasal 22</td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($negosiasiHarga['pph_22'], 0, ',', '.') }}</td>
        </tr>
        <tr style="border: 1px solid black;">
            <td style="text-align: right; border: 1px solid black;" colspan="4">Jumlah Total</td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($negosiasiHarga['jumlah_total'], 0, ',', '.') }}</td>
        </tr>
        <tr style="border: 1px solid black;">
            <td style="text-align: center; border: 1px solid black;" colspan="5">( {{ ucwords(Terbilang::make($negosiasiHarga['jumlah_total'])) }} Rupiah )</td>
        </tr>
    </table>
    <p><strong>Keterangan :</strong></p>
    <p>1. Transfer ke Bank {{ $penyedia->bank}} a.n <strong> {{ $penyedia->atas_nama}} ( {{ $penyedia->rekening}})</strong></p>
    <table style="width: 100%">
        <tr>
            <td style="width: 8cm"><br></td>
            <td style="text-align: center">{{ ucwords($penyedia->kabupaten)}}, {{ Illuminate\Support\Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms)->isoFormat('D MMMM Y') }}</td>
        </tr>
            <tr>
                <td style="width: 8cm"><br></td>
                <td style="text-align: center;">Hormat Kami,
                    <br><br><br><br>
                    <strong>{{ $penyedia->nama_pemilik}}</strong></td>
            </tr>
    </table>
</body>
</html>