<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penawaran Harga') }}
        </h2>
    </x-slot>
    <div class="py-12">
        @foreach ($pemberitahuan->penyedia as $p)
            @php
                $nama_penyedia = App\Models\Penyedia::select('nama_penyedia')->where('id', $p)->first();

            @endphp
        @endforeach
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form action="{{ route('penawaran.store') }}" class="survey" method="POST" id="form_id">
                        @method('post')
                        @csrf
                        <div>
                            <x-input-label for="penyedia" :value="__('Penyedia')" />
                            <input type="text" value="{{ $penyedia->nama_penyedia }}" readonly>
                            <input type="hidden" name="penyedia" value="{{ $penyedia->id }}" readonly>
                            {{-- <select id="penyedia" name="penyedia" class="mt-1 block w-full" required>
                                <option value="">pilih penyedia</option>
                                @foreach ($pemberitahuan->penyedia as $p)
                                    @php    
                                        $nama_penyedia = App\Models\Penyedia::select('nama_penyedia')->where('id', $p)->first();
                                    @endphp 
                                        <option value="{{$p}}">{{$nama_penyedia->nama_penyedia}}</option>
                                @endforeach
                            </select> --}}
                            <x-input-error class="mt-2" :messages="$errors->get('penyedia')" />
                        </div>
                        <div>                           
                                <label for="checkbox" class="inline-flex items-center">
                                    <input type="checkbox" id="checkbox" name="pemenang" value="true"
                                        class="form-checkbox">
                                    <span class="ml-2">Tetapkan sebagai Pemenang</span>
                                </label>                            
                        </div>
                        <br>
                        <div>
                            <input type="hidden" name="pemberitahuan_id" value="{{ $pemberitahuan->id }}">
                            <!-- <input type="hidden" name="id_penyedia" value="{{ $p }}"> -->
                        </div>
                        <div>
                            <x-input-label for="tgl_surat_penawaran" :value="__('Tanggal Surat Penawaran')" />
                            <x-text-input id="tgl_surat_penawaran" name="tgl_surat_penawaran" type="date"
                                min="{{ \Carbon\Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan)->format('Y-m-d') }}"
                                max="{{ \Carbon\Carbon::parse($pemberitahuan->tgl_batas_akhir_penawaran)->format('Y-m-d') }}"
                                class="mt-1 block " required autocomplete="tgl_surat_penawaran" />
                            <x-input-error class="mt-2" :messages="$errors->get('tgl_surat_penawaran')" />
                        </div>
                        <br>
                        <div>
                            <x-input-label for="no_penawaran" :value="__('Nomor Penawaran')" />
                            <x-text-input id="no_penawaran" name="no_penawaran" type="number" min="0"
                                class="mt-1 block " required autocomplete="no_penawaran" />
                            <x-input-error class="mt-2" :messages="$errors->get('no_penawaran')" />
                        </div>

                        <br>
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
                                    @foreach ($belanja as $y => $k)
                                        <tr>
                                            <td class="px-4 py-2">
                                                {{ $loop->iteration  }}
                                                {{-- <input type="hidden" name="no[]" value="{{ $k['nomor'] }}"> --}}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $k['uraian'] }}
                                                <input type="hidden" name="uraian[]" value="{{ $k['uraian'] }}">
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $k['volume'] }}
                                                <input type="hidden" name="volume[]" value="{{ $k['volume'] }}">
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $k['satuan'] }}
                                                <input type="hidden" name="satuan[]" value="{{ $k['satuan'] }}">
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <input type="number" min="0" step="any" name="harga_satuan[]" class="w-24 rounded-md border-gray-300 text-right" onblur="formatNumber(this)">
                                            </td>
                                            <td class="px-4 py-2 text-right" name="format_number"></td>
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
                        <div>
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                        </div>
                </div>
                </form>
            </div>
        </div>
        <br>

    </div>
    @pushOnce('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', (event) => {
                const inputsHargaSatuan = document.querySelectorAll('input[name="harga_satuan[]"]');
                inputsHargaSatuan.forEach(input => {
                    if (!input.getAttribute('nilai-sebelumnya')) {
                        input.setAttribute('nilai-sebelumnya', 0);
                    }
                });
            });
            var total = 0;

            function formatNumber(input) {
                var tr = input.parentNode.parentNode;
                var volume = tr.querySelector('input[name="volume[]"]').value;
                var angka = input.value.replace(/[^0-9]/g, '');
                var formattedNumber = (angka * volume).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                tr.querySelector('td[name="format_number"]').textContent = formattedNumber;

                // Hitung nilai sebelumnya sebelum diubah
                var nilaiSebelumnya = input.value * volume; // Nilai input * volume
                input.setAttribute('nilai-sebelumnya', nilaiSebelumnya);

                updateTotal(formattedNumber, input);
            }

            function updateTotal(formattedNumber, input) {
                var tr = input.parentNode.parentNode;
                var nilaiSebelumnya = input.getAttribute('nilai-sebelumnya');

                console.log("Nilai Sebelumnya:", nilaiSebelumnya); // Debugging

                if (nilaiSebelumnya !== null && nilaiSebelumnya !== undefined && nilaiSebelumnya !== "") {
                    total -= parseInt(nilaiSebelumnya.toString().replace(/\./g, ''));
                }

                total += parseInt(formattedNumber.replace(/\./g, ''));

                document.getElementById('total').textContent = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                document.getElementById('total_input').value = total;
            }

            var inputsHargaSatuan = document.querySelectorAll('input[name="harga_satuan[]"]');
            inputsHargaSatuan.forEach(function(input) {
                input.addEventListener('focus', function() {
                    var tr = this.parentNode.parentNode;
                    var formattedNumber = tr.querySelector('td[name="format_number"]').textContent;
                    formattedNumber = formattedNumber ? formattedNumber : '0';

                    var volume = tr.querySelector('input[name="volume[]"]').value; // Ambil volume
                    var nilaiSebelumnya = this.value * volume; // Hitung nilai sebelumnya

                    if (nilaiSebelumnya !== null && nilaiSebelumnya !== undefined && nilaiSebelumnya !== "") {
                        total -= parseInt(nilaiSebelumnya.toString().replace(/\./g, ''));
                    }

                    document.getElementById('total').textContent = total.toString().replace(
                        /\B(?=(\d{3})+(?!\d))/g, ".");
                    document.getElementById('total_input').value = total;
                });

                input.addEventListener('blur', function() {
                    var tr = this.parentNode.parentNode;
                    var formattedNumber = tr.querySelector('td[name="format_number"]').textContent;
                    formattedNumber = formattedNumber ? formattedNumber : '0';

                    var volume = tr.querySelector('input[name="volume[]"]').value; // Ambil volume
                    var nilaiSaatIni = this.value * volume; // Hitung nilai saat ini
                    this.setAttribute('nilai-sebelumnya', nilaiSaatIni); // Update nilai sebelumnya

                    total += parseInt(formattedNumber.replace(/\./g, ''));
                    document.getElementById('total').textContent = total.toString().replace(
                        /\B(?=(\d{3})+(?!\d))/g, ".");
                    document.getElementById('total_input').value = total;
                });
            });
            document.getElementById('penyedia').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var selectedText = selectedOption.text;
                var selectedValue = selectedOption.value;
                var checkbox = document.getElementById('checkbox');
                var checkboxLabelSpan = document.querySelector('label[for="checkbox"] span.ml-2');
                if (checkboxLabelSpan) {
                    checkboxLabelSpan.textContent = 'Tetapkan sebagai Pemenang: ' + selectedText;
                }
                if (checkbox) {
                    checkbox.value = selectedValue;
                }
            });
        </script>
    @endPushOnce
</x-app-layout>
