<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran') }}
        </h2>
    </x-slot>   

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ isset($pembayaran) ? route('pembayaran.update', $pembayaran->id) : route('pembayaran.store') }}" method="POST">
                    @csrf
                    @if(isset($pembayaran))
                        @method('PATCH')
                    @else
                        @method('POST')
                    @endif

                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <p>{{ $kegiatan->kegiatan }}</p>
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                
                                <x-text-input id="id" name="kegiatan_id" type="hidden" value="{{ $kegiatan->id }}" />
                                @php
                                    $negMin = optional($kegiatan->negosiasiHarga)->tgl_akhir_perjanjian
                                        ? \Carbon\Carbon::parse($kegiatan->negosiasiHarga->tgl_akhir_perjanjian)->format('Y-m-d')
                                        : '';
                                    $maxTahun = Auth::user()->tahun_anggaran . '-12-31';
                                    $valInvoice = old('tgl_invoice', (isset($pembayaran) && $pembayaran->tgl_invoice) ? \Carbon\Carbon::parse($pembayaran->tgl_invoice)->format('Y-m-d') : '');
                                    $valPembCms = old('tgl_pembayaran_cms', (isset($pembayaran) && $pembayaran->tgl_pembayaran_cms) ? \Carbon\Carbon::parse($pembayaran->tgl_pembayaran_cms)->format('Y-m-d') : '');
                                @endphp
                                <div>
                                    <x-input-label for="tgl_invoice" :value="__('Tanggal Invoice')" />
                                    <input
                                        id="tgl_invoice"
                                        name="tgl_invoice"
                                        type="date"
                                        class="mt-1 block"
                                        min="{{ $negMin }}"
                                        max="{{ $maxTahun }}"
                                        value="{{ $valInvoice }}"
                                        required
                                        autocomplete="tgl_invoice"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_invoice')" />
                                </div>

                               <div>
                                <x-input-label for="tgl_pembayaran_cms" :value="__('Tanggal Pembayaran CMS')" />
                                <input
                                    id="tgl_pembayaran_cms"
                                    name="tgl_pembayaran_cms"
                                    type="date"
                                    class="mt-1 block"
                                    min="{{ $negMin }}"
                                    max="{{ $maxTahun }}"
                                    value="{{ $valPembCms }}"
                                    required
                                    autocomplete="tgl_pembayaran_cms"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('tgl_pembayaran_cms')" />
                            </div>

                                <div>
                                    <x-primary-button>
                                        {{ isset($pembayaran) ? 'Perbarui' : 'Simpan' }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>  
    <!-- SCRIPTS: gabungkan sync + beforeunload, lebih aman -->
    @pushOnce('scripts')
    <script>
    (function () {
        // ambil elemen, guard kalau tidak ada
        const invoiceInput = document.getElementById('tgl_invoice');
        const pembayaranInput = document.getElementById('tgl_pembayaran_cms');
        if (!invoiceInput || !pembayaranInput) return; // mencegah error console

        // simpan min default dari server (negosiasi) untuk fallback
        const initialPembMin = pembayaranInput.getAttribute('min') || '';

        function syncPembayaran() {
            const invoiceDate = invoiceInput.value; // format yyyy-mm-dd
            if (invoiceDate) {
                // pilih min terbesar antara initialPembMin dan invoiceDate
                const newMin = (!initialPembMin || invoiceDate > initialPembMin) ? invoiceDate : initialPembMin;
                pembayaranInput.setAttribute('min', newMin);

                // set value hanya jika kosong atau lebih kecil dari min baru
                if (!pembayaranInput.value || pembayaranInput.value < newMin) {
                    pembayaranInput.value = newMin;
                }
            } else {
                // jika invoice kosong: kembalikan min ke nilai server (jika ada)
                if (initialPembMin) pembayaranInput.setAttribute('min', initialPembMin);
                // jangan hapus pembayaranInput.value agar tidak menimpa old() dari validasi
            }
        }

        // jalankan saat pertama load (script ini kemungkinan ditempatkan di footer)
        syncPembayaran();

        // update saat invoice berubah
        invoiceInput.addEventListener('change', syncPembayaran);
        invoiceInput.addEventListener('input', syncPembayaran);

        // ====== Warning jika ada perubahan form ======
        let formChanged = false;
        let isSubmitting = false;
        const selector = 'form input, form textarea, form select';

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

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                isSubmitting = true;
                formChanged = false;
            });
        });

        window.addEventListener('beforeunload', (e) => {
            if (!formChanged || isSubmitting) return;
            e.preventDefault();
            e.returnValue = '';
        });

        window.__disableUnloadWarning = function () { formChanged = false; isSubmitting = true; };
        window.__enableUnloadWarning  = function () { formChanged = true;  isSubmitting = false; };
    })();
    </script>
    @endPushOnce

</x-app-layout>
