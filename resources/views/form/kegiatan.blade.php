<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('From Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <form method="post"
                        action="{{ $kegiatan->exists ? route('kegiatan.update', $kegiatan->id) : route('kegiatan.store') }}"
                        class="mt-6 space-y-6">
                        @csrf
                        @if ($kegiatan->exists)
                            @method('PATCH')
                        @endif
                        <div class="max-w-md">
                            <x-input-label for="rekening_apbdes" :value="__('Rekening APBDes')" />
                            <x-text-input id="rekening_apbdes" name="rekening_apbdes" type="text"
                                class="mt-1 block w-full" :value="old('rekening_apbdes', $kegiatan['rekening_apbdes'])" required autofocus
                                autocomplete="rekening_apbdes" />
                            <x-input-error class="mt-2" :messages="$errors->get('rekening_apbdes')" />
                        </div>
                        <div>
                            <x-input-label for="kegiatan" :value="__('Kegiatan')" />
                            <x-text-input id="kegiatan" name="kegiatan" type="text" class="mt-1 block w-full"
                                :value="old('kegiatan', $kegiatan['kegiatan'])" required autofocus autocomplete="kegiatan" />
                            <x-input-error class="mt-2" :messages="$errors->get('kegiatan')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="lokasi_kegiatan" :value="__('Lokasi Kegiatan')" />
                            <x-text-input id="lokasi_kegiatan" name="lokasi_kegiatan" type="text"
                                class="mt-1 block w-full" :value="old('lokasi_kegiatan', $kegiatan['lokasi_kegiatan'])" required autofocus
                                autocomplete="lokasi_kegiatan" />
                            <x-input-error class="mt-2" :messages="$errors->get('anggota_tpk')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="ketua_tpk" :value="__('Ketua TPK')" />
                            <x-text-input id="ketua_tpk" name="ketua_tpk" type="text" class="mt-1 block w-full"
                                :value="old('ketua_tpk', $kegiatan['ketua_tpk'])" required autofocus autocomplete="ketua_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('ketua_tpk')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="sekretaris_tpk" :value="__('Sekretaris TPK')" />
                            <x-text-input id="sekretaris_tpk" name="sekretaris_tpk" type="text"
                                class="mt-1 block w-full" :value="old('sekretaris_tpk', $kegiatan['sekretaris_tpk'])" required autofocus
                                autocomplete="sekretaris_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('sekretaris_tpk')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="anggota_tpk" :value="__('Anggota TPK')" />
                            <x-text-input id="anggota_tpk" name="anggota_tpk" type="text" class="mt-1 block w-full"
                                :value="old('anggota_tpk', $kegiatan['anggota_tpk'])" required autofocus autocomplete="anggota_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('anggota_tpk')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="nomor_sk_tpk" :value="__('Nomor SK TPK')" />
                            <x-text-input id="nomor_sk_tpk" name="nomor_sk_tpk" type="number" min="1" class="mt-1 block w-full"
                                :value="old('nomor_sk_tpk', $kegiatan['nomor_sk_tpk'])" required autofocus autocomplete="nomor_sk_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_sk_tpk')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="tgl_sk_tpk" :value="__('Tanggal SK TPK')" />
                            <x-text-input id="tgl_sk_tpk" name="tgl_sk_tpk" type="date" class="mt-1 block w-full"
                                :value="old('tgl_sk_tpk', \Carbon\Carbon::parse($kegiatan['tgl_sk_tpk'])->format('Y-m-d'))" required autofocus autocomplete="tgl_sk_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('tgl_sk_tpk')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="pka" :value="__('PKA')" />
                            <x-text-input id="pka" name="pka" type="text" class="mt-1 block w-full"
                            :value="old('pka', $kegiatan['pka'])" required autofocus autocomplete="pka" />
                            <x-input-error class="mt-2" :messages="$errors->get('pka')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="nomor_sk_pka" :value="__('Nomor SK PKA')" />
                            <x-text-input id="nomor_sk_pka" name="nomor_sk_pka" type="number" min="1" class="mt-1 block w-full"
                                :value="old('nomor_sk_pka', $kegiatan['nomor_sk_pka'])" required autofocus autocomplete="nomor_sk_pka" />
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_sk_pka')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="tgl_sk_pka" :value="__('Tanggal SK PKA')" />
                            <x-text-input id="tgl_sk_pka" name="tgl_sk_pka" type="date" class="mt-1 block w-full"
                                :value="old('tgl_sk_pka', \Carbon\Carbon::parse($kegiatan['tgl_sk_pka']))->format('Y-m-d')" required autofocus autocomplete="tgl_sk_pka" />
                            <x-input-error class="mt-2" :messages="$errors->get('tgl_sk_pka')" />
                        </div>
                        <div>
                        <div class="max-w-md">
                            <x-input-label for="pph_22" :value="__('PPh Pasal 22')" />
                            <select id="pph_22" name="pph_22" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="0.03" {{ old('pph_22', $kegiatan['pph_22']) == 0.03 ? 'selected' : '' }}>3 %</option>
                                <option value="0.015" {{ old('pph_22', $kegiatan['pph_22']) == 0.015 ? 'selected' : '' }}>1.5 %</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('pph_22')" />
                        </div>
                        <div class="max-w-md">
                            <x-input-label for="sumber_dana" :value="__('Sumber Dana')" />
                            <select id="sumber_dana" name="sumber_dana" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="dana desa" {{ old('sumber_dana', $kegiatan['sumber_dana']) == 'dana desa' ? 'selected' : '' }}>DD</option>
                                <option value="alokasi dana desa" {{ old('sumber_dana', $kegiatan['sumber_dana']) == 'alokasi dana desa' ? 'selected' : '' }}>ADD</option>
                                <option value="bantuan keuangan provinsi" {{ old('sumber_dana', $kegiatan['sumber_dana']) == 'bantuan keuang provinsi' ? 'selected' : '' }}>PBP</option>
                                <option value="bantuan keuangan kabupaten" {{ old('sumber_dana', $kegiatan['sumber_dana']) == 'bantuan keuang kabupaten' ? 'selected' : '' }}>PBK</option>
                                <option value="bagi hasil pajak dan retribusi daerah" {{ old('sumber_dana', $kegiatan['sumber_dana']) == 'bagi hasil pajak dan retribusi daerah' ? 'selected' : '' }}>PBH</option>
                                <option value="pendapatan asli desa" {{ old('sumber_dana', $kegiatan['sumber_dana']) == 'pendapatan asli desa' ? 'selected' : '' }}>PAD</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('sumber_dana')" />
                        </div>

                        <div>
                            <x-primary-button>{{ $kegiatan->exists ? 'Ubah' : 'Simpan' }}</x-primary-button>
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
