<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kegiatan') }}
        </h2>
    </x-slot>
    @php
        $print = 1;
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <a href="{{ route('kegiatan.create') }}"><x-bladewind::button size='tiny' color="cyan">Tambah</x-bladewind::button></a>
                    
                </div>
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-stone">
                    <div class="overflow-scroll">
                       
                    <x-bladewind::table searchable="true">
                        <x-slot name="header">
                            <th class="w-xl">No</th>
                            <th class="w-xl">Kode Rekening</th>
                            <th class="w-3xl">Kegiatan</th>                            
                        </x-slot>
                        
                        @forelse ( $kegiatans as $kegiatan )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kegiatan['rekening_apbdes'] }}</td>
                            <td>{{ $kegiatan['kegiatan'] }} <br><br><a href="{{ route('kegiatan.edit', $kegiatan['id']) }}"> <x-bladewind::button size='tiny' outline="true" can_submit="true" color="yellow" size='tiny'>Ubah</x-bladewind::button></a>
                            <form class="inline" action="{{ route('kegiatan.show', $kegiatan['id']) }}" method="post" >
                                            @csrf
                                            @method('GET')
                                            <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">Buat</x-bladewind::button> 
                                        </form>    
                            <form action="{{ route('kegiatan.destroy', $kegiatan['id']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-bladewind::button size='tiny' outline="true" can_submit="true" color="red" size='tiny'>Hapus</x-bladewind::button>
                                </form>
                            </td>                            
                        </tr>
                            
                        @empty
                        <tr aria-colspan="4">Belum ada kegiatan </tr>
                            
                        @endforelse
                        
                    </x-bladewind::table>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
