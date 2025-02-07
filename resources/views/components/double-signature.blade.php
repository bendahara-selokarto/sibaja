<table style="width: 100%;">
    <tr>
        <td style="text-align: center;">{{ $attributes['left-keterangan'] ?? ''}}</td>
        <td style="text-align: center;">{{ $attributes['right-keterangan'] ?? ''}}</td>
    </tr>
    <tr>
        <td><br><br><br></td>
        <td></td>
    </tr>
    <tr>
        <td class="left" style="text-align: center; text-decoration: underline;"> <strong>{{ $attributes['left'] ?? ''}}</strong></td>
        <td class="right" style="text-align: center; text-decoration: underline;"><strong>{{ $attributes['right'] ?? ''}}</strong><</td>
    </tr>
</table>