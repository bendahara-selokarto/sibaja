<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pemberitahuan') }}
        </h2>
    </x-slot>
    <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <x-bladewind::table>
                        <x-slot name="header">
                            <tr>
                                <th>No</th>
                                <th>Pemberitahuan</th>
                                <th>Penawaran</th>
                                <th>Negosiasi</th>
                                <th>Perjanjian</th>
                            </tr>
                        </x-slot>
                        @foreach ($pemberitahuan as $p)
                            
                        
                        <tr>
                            <td>{{ $p->rekening_apbdes }}</td>                          
                            <td>
                                <a href="{{ route('pemberitahuan.render' , $p->id) }}" target="_blank"><x-bladewind::button outline="true">download</x-bladewind::button></a>                                
                            </td>
                            <td>
                                <a href="{{ route('penawaran.render' , $p->id , 1) }}" target="_blank"><x-bladewind::button outline="true">download 1</x-bladewind::button></a>                                
                                <a href="{{ route('penawaran.render' , $p->id, 2) }}" target="_blank"><x-bladewind::button outline="true">download 2</x-bladewind::button></a>                                
                            </td>
                            <td>
                                <a href="{{ route('negosiasi.render' , $p->id) }}" target="_blank"><x-bladewind::button outline="true">download 2</x-bladewind::button></a>                                
                            </td>
                            <td>
                                <a href="{{ route('negosiasi.render' , $p->id) }}" target="_blank"><x-bladewind::button outline="true">download 2</x-bladewind::button></a>                                
                            </td>
                        </tr>
                        @endforeach ( $pemberitahuan as $p )
                    </x-bladewind::table>
                </div>
            </div>
        </div>
    </div>
    @include('modal.download.negosiasi')
</x-app-layout>
