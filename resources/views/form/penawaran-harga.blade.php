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
                <div class="max-w-full">
                    <form action="{{ isset($isEdit) ? route('penawaran.update' , $pemberitahuan->id) : route('penawaran.store') }}" class="survey" method="POST" id="form_id">
                        @method('post')
                        @csrf
                        @if(isset($isEdit))
                        @method('PATCH')

                        @endif
                        <div>
                            <x-input-label for="penyedia" :value="__('Penyedia')" />
                            <input class="w-full" type="text"  value="{{ $penyedia->nama_penyedia }}" readonly>
                            <input type="hidden" name="penyedia" value="{{ $penyedia->id }}" readonly>
                            <x-input-error class="mt-2" :messages="$errors->get('penyedia')" />
                        </div>
                        <div>                           
                            <label for="checkbox" class="inline-flex items-center">
                                <input type="checkbox" id="checkbox" name="pemenang" value="true"
                                    class="form-checkbox"
                                    {{ isset($penawaran) && $penawaran->is_winner ? 'checked' : '' }}>
                                <span class="ml-2">Tetapkan sebagai Pemenang</span>
                            </label>                            
                        </div>

                        <br>
                        <div>
                            <input type="hidden" name="pemberitahuan_id" value="{{ $pemberitahuan->id }}">
                        </div>
                        <div>
                            <x-input-label for="tgl_surat_penawaran" :value="__('Tanggal Surat Penawaran')" />
                           <x-text-input id="tgl_surat_penawaran" name="tgl_surat_penawaran" type="date"
                                min="{{ \Carbon\Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan)->format('Y-m-d') }}"
                                max="{{ \Carbon\Carbon::parse($pemberitahuan->tgl_batas_akhir_penawaran)->format('Y-m-d') }}"
                                class="mt-1 block" required
                                value="{{ old('tgl_persetujuan', 
                                    isset($penawaran) && $penawaran->tgl_penawaran
                                        ? \Carbon\Carbon::parse($penawaran->tgl_penawaran)->format('Y-m-d')
                                        : ''
                                ) }}"

                            />
                            <x-input-error class="mt-2" :messages="$errors->get('tgl_surat_penawaran')" />
                        </div>
                        <br>
                        <div>
                            <x-input-label for="no_penawaran" :value="__('Nomor Penawaran')" />
                            <x-text-input id="no_penawaran" name="no_penawaran" type="number" min="1"
                                class="mt-1 block " required 
                                value="{{ old('no_penawaran' , isset($penawaran) && $penawaran->no_penawaran ? $penawaran->no_penawaran : '')}}"
                                
                                />
                            <x-input-error class="mt-2" :messages="$errors->get('no_penawaran')" />
                        </div>
                        <br>
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border-collapse border border-gray-300 text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 min-w-[50px] border border-gray-300">NO</th>
                                        <th class="px-4 py-2 min-w-[250px] border border-gray-300">Uraian</th>
                                        <th class="px-4 py-2 min-w-[100px] border border-gray-300">Volume</th>
                                        <th class="px-4 py-2 min-w-[100px] border border-gray-300">Satuan</th>
                                        <th class="px-4 py-2 min-w-[150px] border border-gray-300 text-right">Harga Satuan</th>
                                        <th class="px-4 py-2 min-w-[150px] border border-gray-300 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($belanja as $k)
                                        <tr>
                                            <td class="px-4 py-2">
                                                {{ $loop->iteration  }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $k['uraian'] }}
                                                <input type="hidden" name="uraian[]" value="{{ $k['uraian'] }}">
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                {{ $k['volume'] }}
                                                <input type="hidden" name="volume[]" value="{{ $k['volume'] }}">
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $k['satuan'] }}
                                                <input type="hidden" name="satuan[]" value="{{ $k['satuan'] }}">
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <input type="number" min="0" step="any" value="{{old('harga_satuan[]', isset($k['harga_satuan']) ? $k['harga_satuan'] : '')}}" name="harga_satuan[]" class="w-40 rounded-md border-gray-300 text-right" onblur="formatNumber(this)">
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
                        <br>
                        <div>
                            <x-bladewind::button can_submit="true" 
                                type="{{ isset($isEdit) ? 'primary' : 'secondary' }}">
                                {{ isset($isEdit) ? 'Update' : 'Simpan' }}
                            </x-bladewind::button>
                        </div>
                </div>
                </form>
            </div>
        </div>
        <br>
    </div>
    @pushOnce('scripts')
        <script>
            function formatRupiah(angka) {
                return angka.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function hitungTotal() {
                let total = 0;

                document.querySelectorAll('tr').forEach(tr => {
                    const volumeInput = tr.querySelector('input[name="volume[]"]');
                    const hargaInput  = tr.querySelector('input[name="harga_satuan[]"]');
                    const cellTotal   = tr.querySelector('td[name="format_number"]');

                    if (!volumeInput || !hargaInput || !cellTotal) return;

                    const volume = parseFloat(volumeInput.value) || 0;
                    const harga  = parseInt(hargaInput.value.replace(/\D/g, '')) || 0;

                    const subtotal = volume * harga;
                    cellTotal.textContent = formatRupiah(subtotal);

                    total += subtotal;
                });

                document.getElementById('total').textContent = formatRupiah(total);
                document.getElementById('total_input').value = total;
            }

            // trigger setiap ada perubahan harga / volume
            document.addEventListener('input', function (e) {
                if (
                    e.target.name === 'harga_satuan[]' ||
                    e.target.name === 'volume[]'
                ) {
                    hitungTotal();
                }
            });

            // hitung awal (jika ada data lama)
            window.addEventListener('DOMContentLoaded', hitungTotal);




            // window.addEventListener('DOMContentLoaded', (event) => {
            //     const inputsHargaSatuan = document.querySelectorAll('input[name="harga_satuan[]"]');
            //     inputsHargaSatuan.forEach(input => {
            //         if (!input.getAttribute('nilai-sebelumnya')) {
            //             input.setAttribute('nilai-sebelumnya', 0);
            //         }
            //     });
            // });
            // var total = 0;

            // function formatNumber(input) {
            //     var tr = input.parentNode.parentNode;
            //     var volume = tr.querySelector('input[name="volume[]"]').value;
            //     var angka = input.value.replace(/[^0-9]/g, '');
            //     var formattedNumber = (angka * volume).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            //     tr.querySelector('td[name="format_number"]').textContent = formattedNumber;

            //     // Hitung nilai sebelumnya sebelum diubah
            //     var nilaiSebelumnya = input.value * volume; // Nilai input * volume
            //     input.setAttribute('nilai-sebelumnya', nilaiSebelumnya);

            //     updateTotal(formattedNumber, input);
            // }

            // function updateTotal(formattedNumber, input) {
            //     var tr = input.parentNode.parentNode;
            //     var nilaiSebelumnya = input.getAttribute('nilai-sebelumnya');

            //     console.log("Nilai Sebelumnya:", nilaiSebelumnya); // Debugging

            //     if (nilaiSebelumnya !== null && nilaiSebelumnya !== undefined && nilaiSebelumnya !== "") {
            //         total -= parseInt(nilaiSebelumnya.toString().replace(/\./g, ''));
            //     }

            //     total += parseInt(formattedNumber.replace(/\./g, ''));

            //     document.getElementById('total').textContent = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            //     document.getElementById('total_input').value = total;
            // }

            // var inputsHargaSatuan = document.querySelectorAll('input[name="harga_satuan[]"]');
            // inputsHargaSatuan.forEach(function(input) {
            //     input.addEventListener('focus', function() {
            //         var tr = this.parentNode.parentNode;
            //         var formattedNumber = tr.querySelector('td[name="format_number"]').textContent;
            //         formattedNumber = formattedNumber ? formattedNumber : '0';

            //         var volume = tr.querySelector('input[name="volume[]"]').value; // Ambil volume
            //         var nilaiSebelumnya = this.value * volume; // Hitung nilai sebelumnya

            //         if (nilaiSebelumnya !== null && nilaiSebelumnya !== undefined && nilaiSebelumnya !== "") {
            //             total -= parseInt(nilaiSebelumnya.toString().replace(/\./g, ''));
            //         }

            //         document.getElementById('total').textContent = total.toString().replace(
            //             /\B(?=(\d{3})+(?!\d))/g, ".");
            //         document.getElementById('total_input').value = total;
            //     });

            //     input.addEventListener('blur', function() {
            //         var tr = this.parentNode.parentNode;
            //         var formattedNumber = tr.querySelector('td[name="format_number"]').textContent;
            //         formattedNumber = formattedNumber ? formattedNumber : '0';

            //         var volume = tr.querySelector('input[name="volume[]"]').value; // Ambil volume
            //         var nilaiSaatIni = this.value * volume; // Hitung nilai saat ini
            //         this.setAttribute('nilai-sebelumnya', nilaiSaatIni); // Update nilai sebelumnya

            //         total += parseInt(formattedNumber.replace(/\./g, ''));
            //         document.getElementById('total').textContent = total.toString().replace(
            //             /\B(?=(\d{3})+(?!\d))/g, ".");
            //         document.getElementById('total_input').value = total;
            //     });
            // });
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
