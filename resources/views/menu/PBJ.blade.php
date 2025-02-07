<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar PBJ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <x-secondary-button>TAMBAH | UBAH</x-secondary-button>
                </div>
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <x-bladewind::table>
                        <x-slot name="header">
                            <th>No</th>
                            <th>Kode Rekening</th>
                            <th>Kegiatan</th>
                            <th>Aksi</th>
                        </x-slot>
                        <tr>
                            <td>Alfred Rowe</td>
                            <td>Outsourcing</td>
                            <td>alfred@therowe.com</td>
                            <td><x-primary-button>Buat</x-primary-button></td>
                        </tr>
                        <tr>
                            <td>Michael K. Ocansey</td>
                            <td>Tech</td>
                            <td>kabutey@gmail.com</td>
                        </tr>
                    </x-bladewind::table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
