<div>
    <table style="width:100%">
        <tr>           
            <td style="text-align: center">
                <h3 style="text-align: center; margin: 4pt;">TIM PELAKSANA KEGIATAN</h3>
                <h3>DESA {{ strToUpper( Auth::user()->desa )}}</h3>
                <!-- <h3 style="margin: 4pt;">{{ strToUpper($kegiatan) ?? ''}}</h3> -->
                <p style="margin: 4pt;">{{ Auth::user()->alamat_kantor }}</p>
            </td>
        </tr>       
    </table>
    <hr style="margin-bottom: 0pt">
    <hr style="margin-top: 2pt; margin-bottom: 0pt;">
    <hr style="margin-top: 0pt">
    
</div>