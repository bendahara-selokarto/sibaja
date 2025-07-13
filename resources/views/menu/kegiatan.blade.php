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
                    <a href="{{ route('kegiatan.create') }}"><x-bladewind::button size='tiny'
                            color="cyan">Tambah</x-bladewind::button></a>

                </div>
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-7 text-stone">
                    <div class="overflow-scroll">

                        <x-bladewind::table searchable="true">
                            <x-slot name="header">
                                <th class="w-xl">No</th>
                                <th class="w-xl">Kode Rekening</th>
                                <th class="w-5xl">Kegiatan</th>
                            </x-slot>

                            @forelse ( $kegiatans as $kegiatan )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $kegiatan['rekening_apbdes'] }} <br>
                                        <a href="{{ route('kegiatan.edit', $kegiatan['id']) }}"> <x-bladewind::button
                                                size='tiny' outline="true" can_submit="true" color="yellow"
                                                size='tiny'>Ubah</x-bladewind::button></a>
                                        <br>
                                        <form action="{{ route('kegiatan.destroy', $kegiatan['id']) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-bladewind::button size='tiny' outline="true" can_submit="true"
                                                color="red" size='tiny'>Hapus</x-bladewind::button>
                                        </form><br>
                                        <ol>
                                            <li><a href="">1. download form pekerja</a></li>
                                            <li><a href="">2. download form buku material</a></li>
                                        </ol>
                                    </td>
                                    <td>
                                        <h1>{{ $kegiatan['kegiatan'] }}</h1><br>
                                        <ol>
                                            <li class="mb-4">
                                                @if (!$kegiatan->pemberitahuan)
                                                    <form class="inline"
                                                        action="{{ route('pemberitahuan.create', $kegiatan['id']) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('POST')
                                                        <x-bladewind::button size='tiny' icon="document-plus"
                                                            can_submit="true" color="green">Buat</x-bladewind::button>
                                                    </form>
                                                @else
                                                @endif
                                                <form class="inline"
                                                    action="{{ route('pemberitahuan.edit', $kegiatan['id']) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <x-bladewind::button size='tiny' icon="pencil-square"
                                                        can_submit="true" color="yellow">Ubah</x-bladewind::button>
                                                </form>
                                                <form class="inline"
                                                    action="{{ route('pemberitahuan.destroy', $kegiatan['id']) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-bladewind::button size='tiny' icon="trash" can_submit="true"
                                                        color="red">Hapus</x-bladewind::button>
                                                </form>
                                                <a target="_blank"
                                                    href="{{ route('pemberitahuan.render', $kegiatan['id']) }}"
                                                    class="text-blue-500 hover:underline"><x-bladewind::button
                                                        size='tiny' icon="printer" can_submit="true"
                                                        color="indigo">Cetak</x-bladewind::button></a>
                                                1. Pemberitahuan kepada 2 Penyedia"
                                            </li>
                                            @if ($kegiatan->pemberitahuan)
                                                <li class="mb-4">
                                                    @if (!$kegiatan->penawaran_1 || !$kegiatan->penawaran_2)
                                                        @if ($penyedia1status)
                                                            <form class="inline"
                                                                action="{{ route('penawaran.create', [$kegiatan['id'], $penyedia[0]->penyedia[0]]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('POST')

                                                                <x-bladewind::button size='tiny' icon="document-plus"
                                                                    can_submit="true" color="green">Penyedia 1
                                                                </x-bladewind::button>
                                                            </form>
                                                        @endif
                                                        @if ($penyedia2status)
                                                            <form class="inline"
                                                                action="{{ route('penawaran.create', [$kegiatan['id'], $penyedia[0]->penyedia[1]]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('POST')

                                                                <x-bladewind::button size='tiny' icon="document-plus"
                                                                    can_submit="true" color="green">Penyedia 2
                                                                </x-bladewind::button>
                                                            </form>
                                                        @endif

                                                        {{-- @foreach ($penyedia[0]->penyedia as $penyedia_item)
                                                            <form class="inline"
                                                                action="{{ route('penawaran.create', [$kegiatan['id'], $penyedia_item]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('POST')

                                                                <x-bladewind::button size='tiny' icon="document-plus"
                                                                    can_submit="true" color="green">Penyedia
                                                                    {{ $loop->iteration }}</x-bladewind::button>
                                                            </form>
                                                        @endforeach --}}
                                                    @else
                                                        <form class="inline"
                                                            action="{{ route('penawaran.edit', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('POST')
                                                            <x-bladewind::button size='tiny' icon="pencil-square"
                                                                can_submit="true"
                                                                color="yellow">Ubah</x-bladewind::button>
                                                        </form>
                                                        <form class="inline"
                                                            action="{{ route('penawaran.destroy', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-bladewind::button size='tiny' icon="trash"
                                                                can_submit="true"
                                                                color="red">hapus</x-bladewind::button>
                                                        </form>
                                                        <a target="_blank"
                                                            href="{{ route('penawaran.render', $kegiatan['id']) }}"
                                                            class="text-blue-500 hover:underline"><x-bladewind::button
                                                                size='tiny' icon="printer" can_submit="true"
                                                                color="indigo">Cetak</x-bladewind::button></a>
                                                    @endif
                                                    2. Penawaran Harga dari 2 Penyedia
                                                </li>
                                            @endif
                                            @if ($kegiatan->penawaran_1 && $kegiatan->penawaran_2)
                                                <li class="mb-4">
                                                    @if (!$kegiatan->negosiasiHarga)
                                                        <form class="inline"
                                                            action="{{ route('negosiasi.create', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('post')
                                                            <x-bladewind::button size='tiny' icon="document-plus"
                                                                can_submit="true"
                                                                color="green">Buat</x-bladewind::button>
                                                        </form>
                                                    @else
                                                        <form class="inline"
                                                            action="{{ route('negosiasi.edit', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('POST')
                                                            <x-bladewind::button size='tiny' icon="pencil-square"
                                                                can_submit="true"
                                                                color="yellow">Ubah</x-bladewind::button>
                                                        </form>
                                                        <form class="inline"
                                                            action="{{ route('negosiasi.destroy', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-bladewind::button size='tiny' icon="trash"
                                                                can_submit="true"
                                                                color="red">hapus</x-bladewind::button>
                                                        </form>

                                                        <a target="_blank"
                                                            href="{{ route('negosiasi.render', $kegiatan['id']) }}"
                                                            class="text-blue-500 hover:underline"><x-bladewind::button
                                                                size='tiny' icon="printer" can_submit="true"
                                                                color="indigo">cetak</x-bladewind::button></a>
                                                    @endif
                                                    3. Negosiasi Harga dengan Penyedia Terpilih
                                                </li>
                                            @endif
                                            @if ($kegiatan->negosiasiHarga)
                                                <li class="mb-4">
                                                    @if (!$kegiatan->pembayaran)
                                                        <form class="inline"
                                                            action="{{ route('pembayaran.create', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('post')
                                                            <x-bladewind::button size='tiny' icon="document-plus"
                                                                can_submit="true"
                                                                color="green">buat</x-bladewind::button>
                                                        </form>
                                                    @else
                                                        <form class="inline"
                                                            action="{{ route('pembayaran.edit', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('post')
                                                            <x-bladewind::button size='tiny' icon="pencil-square"
                                                                can_submit="true"
                                                                color="yellow">Ubah</x-bladewind::button>
                                                        </form>
                                                        <form class="inline"
                                                            action="{{ route('pembayaran.destroy', $kegiatan['id']) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-bladewind::button size='tiny' icon="trash"
                                                                can_submit="true"
                                                                color="red">Hapus</x-bladewind::button>
                                                        </form>

                                                        <a target="_blank"
                                                            href="{{ route('pembayaran.render', $kegiatan['id']) }}"><x-bladewind::button
                                                                size='tiny' icon="printer" can_submit="true"
                                                                color="indigo">cetak</x-bladewind::button></a>
                                                    @endif
                                                    4. Pembayaran
                                                </li>
                                            @endif
                                        </ol>
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
