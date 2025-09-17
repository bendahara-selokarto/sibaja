<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Penyedia') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                {{-- <div class="max-w-xl"> --}}
                    <form method="post" action="{{ $penyedia->exists ? route('penyedia.update', $penyedia->id ): route('penyedia.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf
                        @if($penyedia->exists)
                            @method('PATCH')                        
                        @endif
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <div>
                                <x-input-label for="nama_penyedia" :value="__('Nama Penyedia')" />
                                <x-text-input id="nama_penyedia" name="nama_penyedia" type="text" class="mt-1 block w-full" :value="old('nama_penyedia', $penyedia->nama_penyedia)" required autofocus autocomplete="nama_penyedia" />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_penyedia')" />
                            </div>
                            <div>
                                <x-input-label for="alamat_penyedia" :value="__('Alamat Penyedia')" />
                                <x-text-input id="alamat_penyedia" name="alamat_penyedia" type="text" class="mt-1 block w-full" :value="old('alamat_penyedia', $penyedia->alamat_penyedia)" required autocomplete="alamat_penyedia" />
                                <x-input-error class="mt-2" :messages="$errors->get('alamat_penyedia')" />
                            </div>
                            <div>
                                <x-input-label for="kabupaten" :value="__('Kabupaten')" />
                                <x-text-input id="kabupaten" name="kabupaten" type="text" class="mt-1 block w-full" :value="old('kabupaten', $penyedia->kabupaten)" required autocomplete="kabupaten" />
                                <x-input-error class="mt-2" :messages="$errors->get('kabupaten')" />
                            </div>
                            <div>
                                <x-input-label for="nama_pemilik" :value="__('Nama Pemilik')" />
                                <x-text-input id="nama_pemilik" name="nama_pemilik" type="text" class="mt-1 block w-full" :value="old('nama_pemilik', $penyedia->nama_pemilik)" required autocomplete="nama_pemilik" />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_pemilik')" />
                            </div>
                            <div>
                                <x-input-label for="alamat_pemilik" :value="__('Alamat Pemilik')" />
                                <x-text-input id="alamat_pemilik" name="alamat_pemilik" type="text" class="mt-1 block w-full" :value="old('alamat_pemilik', $penyedia->alamat_pemilik)" required autocomplete="alamat_pemilik" />
                                <x-input-error class="mt-2" :messages="$errors->get('alamat_pemilik')" />
                            </div>
                            <div>
                                <x-input-label for="nomor_hp" :value="__('Nomor HP')" />
                                <x-text-input id="nomor_hp" name="nomor_hp" type="text" class="mt-1 block w-full" :value="old('nomor_hp', $penyedia->nomor_hp)" required autocomplete="nomor_hp" />
                                <x-input-error class="mt-2" :messages="$errors->get('nomor_hp')" />
                            </div>
                        </div>
                        <div>
                            <div>
                                <x-input-label for="nomor_identitas" :value="__('Nomor KTP/SIM')" />
                                <x-text-input id="nomor_identitas" name="nomor_identitas" type="text" class="mt-1 block w-full" :value="old('nomor_identitas', $penyedia->nomor_identitas)" required autocomplete="nomor_identitas" />
                                <x-input-error class="mt-2" :messages="$errors->get('nomor_identitas')" />
                            </div>
                            <div>
                                <x-input-label for="nomor_npwp" :value="__('NPWP')" />
                                <x-text-input id="nomor_npwp" name="nomor_npwp" type="text" class="mt-1 block w-full" :value="old('nomor_npwp', $penyedia->nomor_npwp)" required autocomplete="nomor_npwp" />
                                <x-input-error class="mt-2" :messages="$errors->get('nomor_npwp')" />
                            </div>
                            <div>
                                <x-input-label for="no_SIUP" :value="__('No Izin Usaha')" />
                                <x-text-input id="no_SIUP" name="no_siup" type="text" class="mt-1 block w-full" :value="old('no_SIUP', $penyedia->nomor_izin_usaha)" required autocomplete="no_SIUP" />
                                <x-input-error class="mt-2" :messages="$errors->get('no_SIUP')" />
                            </div>
                            <div>
                                <x-input-label for="penerbit_SIUP" :value="__('Instansi Pemberi Izin')" />
                                <x-text-input id="penerbit_SIUP" name="penerbit_siup" type="text" class="mt-1 block w-full" :value="old('penerbit_SIUP', $penyedia->instansi_pemberi_izin_usaha)" required autocomplete="penerbit_SIUP" />
                                <x-input-error class="mt-2" :messages="$errors->get('penerbit_SIUP')" />
                            </div>
                            <div>
                                <x-input-label for="jabatan_pemilik" :value="__('Jabatan Pemilik')" />
                                <x-text-input id="jabatan_pemilik" name="jabatan_pemilik" type="text" class="mt-1 block w-full" :value="old('jabatan_pemilik', $penyedia->jabata_pemilik)" required autocomplete="jabatan_pemilik" />
                                <x-input-error class="mt-2" :messages="$errors->get('jabatan_pemilik')" />
                            </div>
                        </div>
                        <div>
                            <div>
                                <x-input-label for="rekening" :value="__('Rekening Bank')" />
                                <x-text-input id="rekening" name="rekening" type="text" class="mt-1 block w-full" :value="old('rekening', $penyedia->rekening)" required autocomplete="rekening" />
                                <x-input-error class="mt-2" :messages="$errors->get('rekening')" />
                            </div>
                            <div>
                                <x-input-label for="atas_nama" :value="__('Atas Nama')" />
                                <x-text-input id="atas_nama" name="atas_nama" type="text" class="mt-1 block w-full" :value="old('atas_nama', $penyedia->atas_nama)" required autofocus autocomplete="atas_nama" />
                                <x-input-error class="mt-2" :messages="$errors->get('atas_nama')" />
                            </div>
                            <div>
                                <x-input-label for="bank" :value="__('Nama Bank')" />
                                <x-text-input id="bank" name="bank" type="text" class="mt-1 block w-full" :value="old('bank', $penyedia->bank)" required autocomplete="bank" />
                                <x-input-error class="mt-2" :messages="$errors->get('bank')" />
                            </div>
                            <div>
                                @if ($penyedia->kop_surat)
                                    @php
                                        $namaFile = basename($penyedia->kop_surat);
                                        $tampilNama = substr($namaFile, 10); // pangkas 10 karakter pertama
                                    @endphp
                                    <div class="mb-2 text-sm text-green-600">
                                        <strong>Kop Surat {{ $tampilNama }}</strong>
                                    </div>
                                    @else
                                    <div class="mb-2 text-sm text-gray-600">
                                        <strong>Kop Surat </strong>
                                    </div>
                                    @endif
                                    </div>
                                    <div>
                                        <x-bladewind::filepicker
                                        accepted_file_types="image/*"
                                        name="kop_surat"
                                            />
                                    </div>
                        </div>
                    <div>                        
                        <x-primary-button>Simpan</x-primary-button>  
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @pushOnce('scripts')
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
