<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyedia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <a href="{{ route('submenu.penyedia') }}"><x-bladewind::button color="cyan">Tambah Dari Daftar Penyedia</x-bladewind::button></a>                    
                    <a href="{{ route('penyedia.create') }}"><x-bladewind::button color="cyan">Tambah Baru</x-bladewind::button></a>                    
                </div>
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <x-bladewind::table>
                        <x-slot name="header">
                            <th>No</th>
                            <th>Penyedia</th>
                            <th>Detail Penyedia</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </x-slot>
                       
                        @forelse ($penyedia as $i)
                            
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $i['nama_penyedia'] }} <br>
                                @if($i['kop_surat'] && $i['kop_surat'] != 'kop_surat/default.png')
                                <img style="width: 80pt; height: auto;" src="{{ asset('storage/' . $i['kop_surat']) }}" alt="kop surat">
                                @endif
                             </td>
                            <td><ol>
                                <li>Alamat: {{ $i['alamat_penyedia'] }}</li>
                                <li>{{ $i['nama_pemilik'] }}</li>
                                <li>{{ $i['alamat_pemilik'] }}</li>
                                <li>{{ $i['nomor_hp'] }}</li>
                                <li>{{ $i['nomor_identitas'] }}</li>
                                <li>{{ $i['nomor_npwp'] }}</li>                                
                            </ol></td>
                            <td>Desa {{ $i['createdBy'] ? $i['createdBy']->desa : '' }}</td>
                            <td>
                                @if($i['createdBy'] && $i['createdBy']->desa === Auth::user()->desa)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-3 gap-2">                                   
                                <form id="delete-form-{{ $i->id }}" 
                                    action="{{ route('penyedia.destroy', $i->id) }}" 
                                    method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" 
                                            data-id="{{ $i->id }}"
                                            onclick="confirmDelete(this)"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Hapus
                                    </button>
                                </form>
                                <form action="{{ route('penyedia.edit', $i['id']) }}" method="get">
                                    @csrf
                                    @method('GET')
                                    <x-bladewind::button can_submit="true" size='tiny' color="cyan">ubah</x-bladewind::button>
                                </form>
                                </div>
                                @else
                                <form action="{{ route('penyedia.detach', $i->id) }}" method="POST" id="delete-form-{{ $i->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            data-id="{{ $i->id }}"
                                            onclick="confirmCerai(this)"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        hapus
                                    </button>
                                </form>
                                @endif

                            </td>
                        </tr> 
                      
                        @empty
                        <p>Belum ada penyedia</p>                      
                        @endforelse
                    </x-bladewind::table>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
    <script>
        toastr.success('{{ session('success') }}');
    </script>
    @endif
</x-app-layout>
