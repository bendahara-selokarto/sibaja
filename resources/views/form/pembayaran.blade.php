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

                                <div>
                                    <x-input-label for="tgl_invoice" :value="__('Tanggal Invoice')" />
                                    <input
                                        id="tgl_invoice"
                                        name="tgl_invoice"
                                        type="date"
                                        class="mt-1 block"
                                        min="{{ \Carbon\Carbon::parse($kegiatan->negosiasiHarga->tgl_akhir_perjanjian)->format('Y-m-d') }}"
                                        max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                        value="{{ old('tgl_invoice', isset($pembayaran) ? \Carbon\Carbon::parse($pembayaran->tgl_invoice)->format('Y-m-d') : '') }}"
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
                                        min="{{ \Carbon\Carbon::parse($kegiatan->negosiasiHarga->tgl_akhir_perjanjian)->format('Y-m-d') }}"
                                        max="{{ Auth::user()->tahun_anggaran . '-12-31' }}"
                                        value="{{ old('tgl_pembayaran_cms', isset($pembayaran) ? \Carbon\Carbon::parse($pembayaran->tgl_pembayaran_cms)->format('Y-m-d') : '') }}"
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
    @pushOnce('scripts')
    <script>
        document.getElementById('tgl_invoice').addEventListener('input', function () {
        const invoiceDate = this.value;
        const pembayaranInput = document.getElementById('tgl_pembayaran_cms');
        
        if (invoiceDate) {
            pembayaranInput.setAttribute('min', invoiceDate);
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
