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
                            <td>{{$i['createdBy']->desa}}</td>
                            <td>
                                <form action="{{ route('penyedia.edit', $i['id']) }}" method="get">
                                    @csrf
                                    @method('GET')

                                    <button type="submit" 
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                        Pilih
                                    </button>
                                </form>
                            </td>                            
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4">Tidak ada data penyedia.</td>
                            </tr>
                        @endforelse
                    </x-bladewind::table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>