<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Negosiasi') }}
        </h2>
    </x-slot>   

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            </div>

                <form action="{{ isset($negosiasi) ? route('negosiasi.update', $negosiasi->id) : route('negosiasi.store') }}" method="POST">
                    @csrf
                    @if(isset($negosiasi))
                        @method('patch')
                    @else
                        @method('POST')
                    @endif

                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <p>{{ $kegiatan->rekening_apbdes }} : {{ $kegiatan->kegiatan }}</p>
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                                <x-text-input id="id" name="kegiatan_id" type="hidden" class="mt-1 block w-full" value="{{ $kegiatan->id }}" />

                                <div>
                                    <x-input-label for="tgl_negosiasi" :value="__('Tanggal Negosiasi Harga')" />
                                    <x-text-input id="tgl_negosiasi" name="tgl_negosiasi" type="date"
                                        max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                        class="mt-1 block"
                                        required
                                        autocomplete="tgl_negosiasi"
                                        value="{{ old('tgl_negosiasi', isset($negosiasi) ? $negosiasi->tgl_negosiasi : '') }}"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_negosiasi')" />
                                </div>

                                <div>
                                    <x-input-label for="tgl_persetujuan" :value="__('Tanggal Persetujuan Penawaran')" />
                                    <x-text-input id="tgl_persetujuan" name="tgl_persetujuan" type="date"
                                        max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                        class="mt-1 block"
                                        min="{{ $kegiatan->tgl }}"
                                        required
                                        autocomplete="tgl_persetujuan"
                                        value="{{ old('tgl_persetujuan', isset($negosiasi) ? $negosiasi->tgl_persetujuan : $kegiatan->tgl) }}"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_persetujuan')" />
                                </div>

                                <div>
                                    <x-input-label for="tgl_akhir_perjanjian" :value="__('Tanggal Akhir Perjanjian')" />
                                    <x-text-input id="tgl_akhir_perjanjian" name="tgl_akhir_perjanjian" type="date"
                                        max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                        class="mt-1 block"
                                        required
                                        autocomplete="tgl_akhir_perjanjian"
                                        value="{{ old('tgl_akhir_perjanjian', isset($negosiasi) ? $negosiasi->tgl_akhir_perjanjian : '') }}"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_akhir_perjanjian')" />
                                </div>

                                <div>
                                    <table class="w-full">
                                        <tr>
                                            <th class="w-64">Uraian</th>
                                            <th class="w-64">Vol/Sat</th>
                                            <th class="w-64">Harga Penawaran</th>
                                            <th class="w-64">Harga Negosiasi</th>
                                        </tr>
                                        @for ($i = 0; $i < count($item_penawaran['uraian']); $i++)
                                            <tr>
                                                <td>{{ $item_penawaran['uraian'][$i] }}</td>
                                                <td>{{ $item_penawaran['volume'][$i] }} {{ $item_penawaran['satuan'][$i] }}</td>
                                                <td>{{ $item_penawaran['harga_satuan'][$i] }}</td>
                                                <td>
                                                    <input type="number" name="harga_satuan_negosiasi[]" required
                                                        value="{{ old('harga_satuan_negosiasi.' . $i, isset($item_negosiasi['harga_negosiasi'][$i]) ? $item_negosiasi['harga_negosiasi'][$i] : '') }}"
                                                        class="w-full border border-gray-300 rounded px-2 py-1"
                                                    >
                                                </td>
                                            </tr>
                                        @endfor
                                    </table>
                                </div>

                                <div>
                                    <x-primary-button type='submit'>{{ isset($negosiasi) ? 'Perbarui' : 'Simpan' }}</x-primary-button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>  
{{-- javascript --}}
@pushOnce('scripts')
<script>
    const hargaNegosiasi = document.getElementById('harga_negosiasi');
    const formatCurency = document.getElementById('format_curency');

    hargaNegosiasi.addEventListener('input', (e) => {
    const nilai = parseInt(e.target.value);
    const format = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(nilai);
    formatCurency.innerText = format;
    });
</script>
@endPushOnce
</x-app-layout> 
