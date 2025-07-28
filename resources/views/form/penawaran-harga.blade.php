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
                            <input type="hidden" name="penyedia" value="{{ $penyedia->id }}" >
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
                        <table class="w-full">
                            <tr>
                                <th class="w-64">NO</th>
                                <th class="w-64">Uraian</th>
                                <th class="w-64">Volume</th>
                                <th class="w-64">Satuan</th>
                                <th class="w-64">Harga satuan <br></th>
                                <th class="w-64">Jumlah</th>
                            </tr>

                            @foreach ($pemberitahuan->belanja as $y => $k)
                                <tr>
                                    <td><input type="hidden" value="{{ $k['nomor'] }}"
                                            placeholder="{{ $k['nomor'] }}" readonly
                                            name="no[]">{{ $k['nomor'] }}</td>
                                    <td><input type="hidden" value="{{ $k['uraian'] }}"
                                            placeholder="{{ $k['uraian'] }}" readonly
                                            name='uraian[]'>{{ $k['uraian'] }} </td>
                                    <td><input type="hidden" value="{{ $k['volume'] }}"
                                            placeholder="{{ $k['volume'] }}" readonly
                                            name='volume[]'>{{ $k['volume'] }} </td>
                                    <td><input type="hidden" value="{{ $k['satuan'] }}"
                                            placeholder="{{ $k['satuan'] }}" readonly
                                            name='satuan[]'>{{ $k['satuan'] }} </td>
                                    <td class="text-right"><input type="number" min="0" name="harga_satuan[]"
                                            onblur="formatNumber(this)"
                                            nilai-sebelumnya="{{ old('harga_satuan[]', 0) }}"></td>
                                    <td class="text-right" name="format_number"></td>
                                    <td><input type="hidden" value="" id="total_input" name='total_input'></td>

                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td class="text-right" id="total"></td>
                            </tr>
                        </table>
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
