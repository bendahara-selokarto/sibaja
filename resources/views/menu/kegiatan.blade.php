<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <a href="{{ route('kegiatan.create') }}">
                        <x-bladewind::button size='tiny' color="cyan">Tambah</x-bladewind::button>
                    </a>
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
                                <th class="w-4xl">Kegiatan</th>
                                <th class="w-xl">Aksi</th>
                            </x-slot>

                            @forelse ($kegiatans as $kegiatan)
                                <tr class="clickable-row hover:bg-gray-100 cursor-pointer"
                                    data-href="{{ route('kegiatan.show', $kegiatan['id']) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kegiatan['rekening_apbdes'] }}</td>
                                    <td><h1>{{ $kegiatan['kegiatan'] }}</h1></td>
                                    <td><a href="{{ route('kegiatan.edit', $kegiatan['id']) }}"> <x-bladewind::button
                                                size='tiny' outline="true" can_submit="true" color="yellow"
                                                size='tiny'>Ubah</x-bladewind::button></a>
                                        <form action="{{ route('kegiatan.destroy', $kegiatan['id']) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-bladewind::button size='tiny' outline="true" can_submit="true"
                                                color="red" size='tiny'>Hapus</x-bladewind::button>
                                        </form>
                                    </td>
                                    <td>
                                        <span>detail</span><x-bladewind::icon name="chevron-double-right" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada kegiatan</td>
                                </tr>
                            @endforelse

                        </x-bladewind::table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".clickable-row");
            rows.forEach(row => {
                row.addEventListener("click", function() {
                    window.location = this.dataset.href;
                });
            });
        });
    </script>
</x-app-layout>
