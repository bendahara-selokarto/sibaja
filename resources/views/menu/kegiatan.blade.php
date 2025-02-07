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
                    <x-bladewind::table>
                        <x-slot name="header">
                            <th class="w-xl">No</th>
                            <th class="w-xl">Kode Rekening</th>
                            <th class="w-3xl">Kegiatan</th>
                            <th class="w-2xl">Dokumen PBJ</th>
                            {{-- <th>output</th> --}}
                        </x-slot>
                        
                        @forelse ( $kegiatans as $kegiatan )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kegiatan['rekening_apbdes'] }}</td>
                            <td>{{ $kegiatan['kegiatan'] }} <br><br><a href="{{ route('kegiatan.edit', $kegiatan['id']) }}"> <x-bladewind::button size='tiny' outline="true" can_submit="true" color="yellow" size='tiny'>Ubah</x-bladewind::button></a><a href="{{ route('kegiatan.edit', $kegiatan['id']) }}"> <x-bladewind::button size='tiny' outline="true" can_submit="true" color="red" size='tiny'>Hapus</x-bladewind::button></a> </td>                           
                            <td>
                                <ul>
                                    <li class="mb-4"><x-bladewind::tag outline="true" shade="dark" label="Pemberitahuan kepada 2 Penyedia"  /><br>
                                        <form class="inline" action="{{ route('pemberitahuan.create', $kegiatan['id']) }}" method="post" >
                                            @csrf
                                            @method('POST')
                                            <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">Buat</x-bladewind::button> 
                                        </form>
                                        <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow">ubah</x-bladewind::button> 
                                        <a target="_blank" href="{{ route('pemberitahuan.render', $kegiatan['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">Cetak</x-bladewind::button></a> 
                                    </li>
                                    <li class="mb-4"><x-bladewind::tag outline="true" shade="dark" label="Penawaran Harga dari 2 Penyedia"  /><br>
                                        <form class="inline" action="{{ route('penawaran.create', $kegiatan['id']) }}" method="post">
                                            @csrf
                                            @method('POST')
                                            <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">Buat</x-bladewind::button>
                                        </form>
                                        <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow">Ubah</x-bladewind::button>
                                        <a target="_blank" href="{{ route('penawaran.render', $kegiatan['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">Cetak</x-bladewind::button></a>
                                    </li>
                                    <li class="mb-4"><x-bladewind::tag outline="true" shade="dark" label="Negosiasi Harga dengan Penyedia Terpilih"  /><br>
                                        <form class="inline" action="{{ route('negosiasi.create', $kegiatan['id']) }}" method="post">
                                            @csrf
                                            @method('post')
                                            <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green" >Buat</x-bladewind::button>
                                        </form>
                                        <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow" >Ubah</x-bladewind::button>
                                        <a target="_blank" href="{{ route('negosiasi.render', $kegiatan['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo" >cetak</x-bladewind::button></a>
                                    </li>
                                    <li class="mb-4"><x-bladewind::tag outline="true" shade="dark" label="Pembayaran"  /><br>
                                        <form class="inline" action="{{ route('pembayaran.create', $kegiatan['id']) }}" method="post">
                                            @csrf
                                            @method('post')
                                            <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green" >buat</x-bladewind::button>
                                        </form>
                                        <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow" >ubah</x-bladewind::button>
                                        <a target="_blank" href="{{ route('pembayaran.render', $kegiatan['id']) }}"><x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo" >cetak</x-bladewind::button></a>
                                    </li>
                                </ul>

                            </td>
                            {{-- <td>
                                <ol>
                                    <li><a target="_blank" href="{{ route('pemberitahuan.render', $kegiatan['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' outline="true" can_submit="true" >Pemberitahuan</x-bladewind::button></a></li>
                                    <li><a target="_blank" href="{{ route('penawaran.render', $kegiatan['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' outline="true" can_submit="true" >Penawaran harga</x-bladewind::button></a></li>
                                    <li><a target="_blank" href="{{ route('negosiasi.render', $kegiatan['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' outline="true" can_submit="true" >Negosiasi</x-bladewind::button></a></li>
                                    <li><a target="_blank" href="{{ route('pembayaran.render', $kegiatan['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' outline="true" can_submit="true" >Pembayaran</x-bladewind::button></a></li>
                                </ol>
                            </td> --}}
                        </tr>
                            
                        @empty
                        <tr aria-colspan="4">Belum ada kegiatan </tr>
                            
                        @endforelse
                        
                    </x-bladewind::table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
