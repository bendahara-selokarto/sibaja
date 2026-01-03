<div>
    <table style="width:100%">
        <tr> 
            <td style="width: 23mm"><img src="{{public_path('images/batang.png')}}" alt="batang" style="width: 21Mm; height:25mm;"></td>          
            <td style="text-align: center; width:auto">
                <h3 style="text-align: center; margin: 2pt;">PEMERINTAH KABUPATEN BATANG</h3>
                <h3 style="text-align: center; margin: 2pt;">KANTOR DESA {{ strToUpper( Auth::user()->desa )}}</h3>
                <h4 style="text-align: center; margin: 2pt;">KECAMATAN PECALUNGAN</h4>
                <p  style="text-align: center; margin: 2pt;">{{ Auth::user()->alamat_kantor }}</p>
            </td>
        </tr>       
    </table>
    <hr style="margin-bottom: 0pt">
    <hr style="margin-top: 2pt; margin-bottom: 0pt;">
    <hr style="margin-top: 0pt">    
</div>