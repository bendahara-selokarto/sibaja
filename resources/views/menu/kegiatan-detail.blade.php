<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <h1>{{ $kegiatan['rekening_apbdes'] }} : Kegiatan {{ $kegiatan['kegiatan'] }}</h1>
                </div>
            </div>
            
            <br>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-7 text-stone">
                    <div class="overflow-scroll">
                        <x-bladewind::table>
                            <x-slot name="header">
                                <th class="w-xl">Kode Rekening</th>
                            </x-slot>
                            
                            <tr>
                                <td>
                                    <div class="mt-2 font-semibold">1. Pemberitahuan kepada 2 Penyedia</div>
                                </td>
                                <td class="mb-4">

                                    {{-- 1. Pemberitahuan kepada 2 Penyedia --}}
                                    @if (!$kegiatan->pemberitahuan)
                                        <form action="{{ route('pemberitahuan.create', $kegiatan['id']) }}" method="post" class="inline">
                                            @csrf
                                            @method('POST')
                                            <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">
                                                Buat
                                            </x-bladewind::button>
                                        </form>
                                    @else
                                        <form action="{{ route('pemberitahuan.edit', $pemberitahuan[0]->id) }}" method="post" class="inline">
                                            @csrf
                                            @method('POST')
                                            <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow">
                                                Ubah
                                            </x-bladewind::button>
                                        </form>

                                        <form action="{{ route('pemberitahuan.destroy', $kegiatan['id']) }}" method="post" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-bladewind::button size='tiny' icon="trash" can_submit="true" color="red">
                                                Hapus
                                            </x-bladewind::button>
                                        </form>

                                        <a target="_blank" href="{{ route('pemberitahuan.render', $kegiatan['id']) }}">
                                            <x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">
                                                Cetak
                                            </x-bladewind::button>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="mt-2 font-semibold">2. Penawaran Harga dari 2 Penyedia</div>
                                </td>
                                <td colspan="2">
                                    @if ($kegiatan->pemberitahuan)
                                        <div class="mt-4">
                                            @if (!$kegiatan->penawaran_1 || !$kegiatan->penawaran_2)
                                                <form action="{{ route('penawaran.create', [$kegiatan['id'] , $penyedia[0]]) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">
                                                        Tambah {{ $nama_penyedia_1 }}
                                                    </x-bladewind::button>
                                                </form>

                                                <form action="{{ route('penawaran.create', [$kegiatan['id'] , $penyedia[1] ]) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">
                                                        Tambah {{ $nama_penyedia_2 }}
                                                    </x-bladewind::button>
                                                </form>
                                            @else
                                                {{-- <form action="{{ route('penawaran.edit', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow">
                                                        Ubah
                                                    </x-bladewind::button>
                                                </form> --}}

                                                <form action="{{ route('penawaran.destroy', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-bladewind::button size='tiny' icon="trash" can_submit="true" color="red">
                                                        Hapus
                                                    </x-bladewind::button>
                                                </form>

                                                <a target="_blank" href="{{ route('penawaran.render', $kegiatan['id']) }}">
                                                    <x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">
                                                        Cetak
                                                    </x-bladewind::button>
                                                </a>
                                            @endif

                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>

                                    <div class="mt-2 font-semibold">3. Negosiasi Harga dengan Penyedia Terpilih</div>
                                </td>
                                <td colspan="2">
                                    {{-- 3. Negosiasi Harga --}}
                                    @if ($kegiatan->penawaran_1 && $kegiatan->penawaran_2)
                                        <div class="mt-4">
                                            @if (!$kegiatan->negosiasiHarga)
                                                <form action="{{ route('negosiasi.create', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">
                                                        Buat
                                                    </x-bladewind::button>
                                                </form>
                                            @else
                                                <form action="{{ route('negosiasi.edit', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow">
                                                        Ubah
                                                    </x-bladewind::button>
                                                </form>

                                                <form action="{{ route('negosiasi.destroy', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-bladewind::button size='tiny' icon="trash" can_submit="true" color="red">
                                                        Hapus
                                                    </x-bladewind::button>
                                                </form>

                                                <a target="_blank" href="{{ route('negosiasi.render', $kegiatan['id']) }}">
                                                    <x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">
                                                        Cetak
                                                    </x-bladewind::button>
                                                </a>
                                            @endif

                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="mt-2 font-semibold">4. Pembayaran</div>
                                </td>
                                <td colspan="2">
                                    
                                    {{-- 4. Pembayaran --}}
                                    @if ($kegiatan->negosiasiHarga)
                                    <div class="mt-4">
                                            @if (!$kegiatan->pembayaran)
                                                <form action="{{ route('pembayaran.create', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="document-plus" can_submit="true" color="green">
                                                        Buat
                                                    </x-bladewind::button>
                                                </form>
                                            @else
                                                <form action="{{ route('pembayaran.edit', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="pencil-square" can_submit="true" color="yellow">
                                                        Ubah
                                                    </x-bladewind::button>
                                                </form>

                                                <form action="{{ route('pembayaran.destroy', $kegiatan['id']) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-bladewind::button size='tiny' icon="trash" can_submit="true" color="red">
                                                        Hapus
                                                    </x-bladewind::button>
                                                </form>

                                                <a target="_blank" href="{{ route('pembayaran.render', $kegiatan['id']) }}">
                                                    <x-bladewind::button size='tiny' icon="printer" can_submit="true" color="indigo">
                                                        Cetak
                                                    </x-bladewind::button>
                                                </a>
                                            @endif

                                        </div>
                                    @endif
                                </td>
                            </tr>

                        </x-bladewind::table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
