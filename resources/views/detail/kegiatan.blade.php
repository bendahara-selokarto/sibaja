<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Surat Pemberitahuan PBJ') }}
        </h2>
    </x-slot>  
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <p>{{ $kegiatan['rekening_apbdes'] }} | {{ $kegiatan['kegiatan']}}</p> <br>
                    <form class="inline" action="{{ route('pemberitahuan.create', $kegiatan['id']) }}" method="post">
                        @csrf
                        @method('POST')
                        <x-bladewind::button size='tiny' can_submit="true" color="green">Buat</x-bladewind::button>
                    </form>
                    
                </div>
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-stone">
                    <div class="overflow-scroll">
                        
                        <x-bladewind::table >
                            <x-slot name="header">
                                <th>No</th>
                                <th>Dokumen</th>
                                <th>Belanja</th>
                                <th>Aksi</th>
                            </x-slot>
                               
                           
                                    @foreach($pemberitahuan as $item)
                                    @php 
                                    $string = implode(", ", array_column( $item['belanja'], "field1"));
                                    @endphp
                                    <tr>
                                        <!-- <td>{{ $item['no_pemberitahuan'] }}</td> -->
                                        <td>{{ $item['no_pbj'] }}</td>
                                        <td>Pemberitahuan </td>
                                        <td>{{ $string }}</td>
                                        <td><a target="_blank" href="{{ route('pemberitahuan.render', $item['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">Cetak</x-bladewind::button></a> <br>
                                        <td>
                                <ul>
                                    
                                    <li class="mb-4"><x-bladewind::tag outline="true" shade="dark" label="Penawaran Harga dari 2 Penyedia"  /><br>
                                        <form class="inline" action="{{ route('penawaran.create', $item['id']) }}" method="post">
                                            @csrf
                                            @method('POST')
                                            <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">Buat</x-bladewind::button>
                                        </form>
                                        <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow">Ubah</x-bladewind::button>
                                        <a target="_blank" href="{{ route('penawaran.render', $item['id']) }}" class="text-blue-500 hover:underline"><x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">Cetak</x-bladewind::button></a>
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
                                    </tr>
                                    @endforeach
                              
                            </x-bladewind::table>
                            

                       
                </div>
                </div>
            </div>
            </div>
</x-app-layout>
