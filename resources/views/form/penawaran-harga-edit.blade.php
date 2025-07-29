<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penawaran Harga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ isset($penawaran) ? route('penawaran.update', $penawaran->id) : route('penawaran.store') }}" method="POST" id="form_id">
                    @csrf
                    @if(isset($penawaran))
                        @method('PUT')
                    @else
                        @method('POST')
                    @endif

                    {{-- Penyedia --}}
                    <div class="mb-4">
                        <x-input-label for="penyedia" :value="__('Penyedia')" />
                        <input type="text" value="{{ $penyedia->nama_penyedia }}" readonly class="w-full bg-gray-100 rounded px-3 py-2">
                        <input type="hidden" name="penyedia" value="{{ $penyedia->id }}">
                    </div>

                    {{-- Tetapkan Pemenang --}}
                    <div class="mb-4">
                        <label for="checkbox" class="inline-flex items-center">
                            <input type="checkbox" id="checkbox" name="pemenang" value="true" class="form-checkbox"
                                {{ old('pemenang', $penawaran->pemenang ?? false) ? 'checked' : '' }}>
                            <span class="ml-2">Tetapkan sebagai Pemenang</span>
                        </label>
                    </div>

                    {{-- Tanggal Surat --}}
                    <div class="mb-4">
                        <x-input-label for="tgl_surat_penawaran" :value="__('Tanggal Surat Penawaran')" />
                        <x-text-input id="tgl_surat_penawaran" name="tgl_surat_penawaran" type="date"
                            min="{{ \Carbon\Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan)->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::parse($pemberitahuan->tgl_batas_akhir_penawaran)->format('Y-m-d') }}"
                            value="{{ old('tgl_surat_penawaran', $penawaran->tgl_surat_penawaran ?? '') }}"
                            class="mt-1 block" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tgl_surat_penawaran')" />
                    </div>

                    {{-- No Penawaran --}}
                    <div class="mb-4">
                        <x-input-label for="no_penawaran" :value="__('Nomor Penawaran')" />
                        <x-text-input id="no_penawaran" name="no_penawaran" type="number" min="0"
                            value="{{ old('no_penawaran', $penawaran->no_penawaran ?? '') }}"
                            class="mt-1 block" required />
                        <x-input-error class="mt-2" :messages="$errors->get('no_penawaran')" />
                    </div>

                    {{-- Hidden pemberitahuan --}}
                    <input type="hidden" name="pemberitahuan_id" value="{{ $pemberitahuan->id }}">

                    {{-- Tabel Belanja --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 divide-y divide-gray-100 text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-4 py-2 w-10">NO</th>
                                    <th class="px-4 py-2 w-3/10">Uraian</th>
                                    <th class="px-4 py-2 w-1/10">Volume</th>
                                    <th class="px-4 py-2 w-1/10">Satuan</th>
                                    <th class="px-4 py-2 w-2/10 text-right">Harga Satuan</th>
                                    <th class="px-4 py-2 w-2/10 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($pemberitahuan->belanja as $i => $item)
                                    @php
                                        $hargaLama = $penawaran->item[$i]['harga_satuan'] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-2">{{ $item['nomor'] }}
                                            <input type="hidden" name="no[]" value="{{ $item['nomor'] }}">
                                        </td>
                                        <td class="px-4 py-2">{{ $item['uraian'] }}
                                            <input type="hidden" name="uraian[]" value="{{ $item['uraian'] }}">
                                        </td>
                                        <td class="px-4 py-2">{{ $item['volume'] }}
                                            <input type="hidden" name="volume[]" value="{{ $item['volume'] }}">
                                        </td>
                                        <td class="px-4 py-2">{{ $item['satuan'] }}
                                            <input type="hidden" name="satuan[]" value="{{ $item['satuan'] }}">
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <input type="number" min="0" step="any" name="harga_satuan[]"
                                                class="w-24 rounded-md border-gray-300 text-right harga-input"
                                                value="{{ old("harga_satuan.$i", $hargaLama) }}"
                                                onblur="hitungJumlah(this)">
                                        </td>
                                        <td class="px-4 py-2 text-right jumlah-output">0</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-50 font-semibold">
                                    <td colspan="4"></td>
                                    <td class="px-4 py-2 text-right">Total</td>
                                    <td class="px-4 py-2 text-right" id="total">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" id="total_input" name="total_input" value="0">

                    {{-- Tombol Simpan --}}
                    <div class="mt-6">
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
    <script>
        function hitungJumlah(input) {
            const tr = input.closest('tr');
            const volume = parseFloat(tr.querySelector('input[name="volume[]"]').value) || 0;
            const harga = parseFloat(input.value) || 0;
            const jumlah = volume * harga;
            const jumlahTd = tr.querySelector('.jumlah-output');
            jumlahTd.textContent = jumlah.toLocaleString('id-ID');

            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.jumlah-output').forEach(el => {
                const nilai = parseInt(el.textContent.replace(/\./g, '').replace(/,/g, '')) || 0;
                total += nilai;
            });
            document.getElementById('total').textContent = total.toLocaleString('id-ID');
            document.getElementById('total_input').value = total;
        }

        // Initial hitung kalau ada data lama
        document.querySelectorAll('.harga-input').forEach(input => {
            if (input.value) hitungJumlah(input);
        });
    </script>
    @endPushOnce
</x-app-layout>
