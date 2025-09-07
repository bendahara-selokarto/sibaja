<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('From SK Tim Pelaksana Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form action="" method="post">
                        <div>
                            <x-input-label for="no_sk_tpk" :value="__('Nomor SK TPK')" />
                            <x-text-input id="no_sk_tpk" name="no_sk_tpk" type="number"  class="mt-1 block" required autocomplete="no_sk_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('no_sk_tpk')" />
                        </div>
                        <div>
                            <x-input-label for="tgl_sk_tpk" :value="__('Tanggal SK TPK')" />
                            <x-text-input id="tgl_sk_tpk" name="tgl_sk_tpk" type="date" max="{{ Auth::user()->tahun_anggaran . '-12-31' }}" class="mt-1 block" required autocomplete="tgl_sk_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('tgl_sk_tpk')" />
                        </div>                              
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    @pushOnce('scripts')
    <script>

        //konfirmasi sebelum meninggalkan halaman jika ada perubahan pada form
        let formChanged = false;

    document.querySelectorAll("form input, form textarea, form select").forEach(el => {
        el.addEventListener("change", () => {
            formChanged = true;
        });
    });

    window.addEventListener("beforeunload", function (e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = "Perubahan Anda belum disimpan. Yakin mau keluar?";
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
