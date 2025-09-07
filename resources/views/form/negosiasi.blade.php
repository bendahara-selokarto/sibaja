<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Negosiasi') }}
        </h2>
    </x-slot>   

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-3">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <p>{{ $kegiatan->rekening_apbdes }} : {{ $kegiatan->kegiatan }}</p>
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
                        
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                                <x-text-input id="id" name="kegiatan_id" type="hidden" class="mt-1 block w-full" value="{{ $kegiatan->id }}" />
                                    <div>
                                        <x-input-label for="tgl_persetujuan" :value="__('Tanggal Persetujuan Penawaran')" />
                                        <x-text-input id="tgl_persetujuan" name="tgl_persetujuan" type="date"
                                            max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                            class="mt-1 block"
                                            min="{{ $kegiatan->tgl }}"
                                            required
                                            value="{{ old('tgl_persetujuan', isset($negosiasi) ? $negosiasi->tgl_persetujuan : $kegiatan->tgl) }}"
                                        />
                                        <x-input-error class="mt-2" :messages="$errors->get('tgl_persetujuan')" />
                                    </div>
                                    <div>
                                        <x-input-label for="tgl_negosiasi" :value="__('Tanggal Negosiasi Harga')" />
                                        <x-text-input id="tgl_negosiasi" name="tgl_negosiasi" type="date"
                                            max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                            class="mt-1 block"
                                            required
                                            {{-- autocomplete="tgl_negosiasi" --}}
                                            value="{{ old('tgl_negosiasi', isset($negosiasi) ? $negosiasi->tgl_negosiasi : '') }}"
                                        />
                                        <x-input-error class="mt-2" :messages="$errors->get('tgl_negosiasi')" />
                                    </div>


                                <div>
                                    <x-input-label for="tgl_akhir_perjanjian" :value="__('Tanggal Akhir Perjanjian')" />
                                    <x-text-input id="tgl_akhir_perjanjian" name="tgl_akhir_perjanjian" type="date"
                                        max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                        class="mt-1 block"
                                        required
                                        {{-- autocomplete="tgl_akhir_perjanjian" --}}
                                        value="{{ old('tgl_akhir_perjanjian', isset($negosiasi) ? $negosiasi->tgl_akhir_perjanjian : '') }}"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_akhir_perjanjian')" />
                                </div>
                                <br>
                                <div>
                                    <table class="w-full border border-gray-200 text-left">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="w-64 px-2 py-1">Uraian</th>
                                                <th class="w-64 px-2 py-1">Vol/Sat</th>
                                                <th class="w-64 px-2 py-1">Harga Penawaran</th>
                                                <th class="w-64 px-2 py-1">Harga Negosiasi</th>
                                            </tr>
                                        </thead>

                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach ($items as $i => $item)
                                            <tr class="odd:bg-gray-50 even:bg-white"> 
                                                <td class="px-2 py-1">{{ $item['uraian'] }}</td>
                                                <td class="px-2 py-1">{{ $item['volume'] }} {{ $item['satuan'] }}</td>
                                                <td class="px-2 py-1">{{ $item['harga_penawaran'] }}</td>
                                                <td class="px-2 py-1">
                                                    <input type="number" name="harga_satuan_negosiasi[]" required
                                                        value="{{ old('harga_satuan_negosiasi.' . $i, isset($item['harga_negosiasi']) ? $item['harga_negosiasi'] : '') }}"
                                                        class="w-full border border-gray-300 rounded px-2 py-1"
                                                    >
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <br>

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
        <script>
    (function () {
    // flag: ada perubahan pada form
    let formChanged = false;
    // flag: form sedang disubmit secara normal (boleh melewati warning)
    let isSubmitting = false;

    const selector = 'form input, form textarea, form select';

    // tandai perubahan: gunakan 'input' untuk text-like fields, 'change' untuk lainnya
    document.querySelectorAll(selector).forEach(el => {
        const tag = el.tagName.toLowerCase();
        if (tag === 'textarea' || (el.tagName.toLowerCase() === 'input' && [
            'text','search','email','number','password','tel','url'
        ].includes(el.type))) {
        el.addEventListener('input', () => formChanged = true);
        } else {
        el.addEventListener('change', () => formChanged = true);
        }
    });

    // Saat form disubmit secara normal, matikan warning
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
        isSubmitting = true;
        formChanged = false; // optional: reset supaya SPA/ajax tidak memicu warning
        });
    });

    // beforeunload handler — hanya tampilkan kalau ada perubahan dan bukan karena submit
    window.addEventListener('beforeunload', (e) => {
        if (!formChanged || isSubmitting) return;
        e.preventDefault();
        // modern browser mengabaikan pesan kustom; cukup set returnValue.
        e.returnValue = '';
    });

    // Helpers (panggil ini bila pakai AJAX / Livewire: setelah berhasil submit, panggil __disableUnloadWarning())
    window.__disableUnloadWarning = function () { formChanged = false; isSubmitting = true; };
    window.__enableUnloadWarning  = function () { formChanged = true;  isSubmitting = false; };
    })();
    </script>

    
@endPushOnce
</x-app-layout> 
