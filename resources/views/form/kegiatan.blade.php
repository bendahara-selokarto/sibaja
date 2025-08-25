<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('From Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="post"
                        action="{{ $kegiatan->exists ? route('kegiatan.update', $kegiatan->id) : route('kegiatan.store') }}"
                        class="mt-6 space-y-6">
                        @csrf
                        @if ($kegiatan->exists)
                            @method('PATCH')
                        @endif
                        <div>
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
                        <div>
                            <x-input-label for="lokasi_kegiatan" :value="__('Lokasi Kegiatan')" />
                            <x-text-input id="lokasi_kegiatan" name="lokasi_kegiatan" type="text"
                                class="mt-1 block w-full" :value="old('lokasi_kegiatan', $kegiatan['lokasi_kegiatan'])" required autofocus
                                autocomplete="lokasi_kegiatan" />
                            <x-input-error class="mt-2" :messages="$errors->get('anggota_tpk')" />
                        </div>
                        <div>
                            <x-input-label for="ketua_tpk" :value="__('Ketua TPK')" />
                            <x-text-input id="ketua_tpk" name="ketua_tpk" type="text" class="mt-1 block w-full"
                                :value="old('ketua_tpk', $kegiatan['ketua_tpk'])" required autofocus autocomplete="ketua_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('ketua_tpk')" />
                        </div>
                        <div>
                            <x-input-label for="sekretaris_tpk" :value="__('Sekretaris TPK')" />
                            <x-text-input id="sekretaris_tpk" name="sekretaris_tpk" type="text"
                                class="mt-1 block w-full" :value="old('sekretaris_tpk', $kegiatan['sekretaris_tpk'])" required autofocus
                                autocomplete="sekretaris_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('sekretaris_tpk')" />
                        </div>
                        <div>
                            <x-input-label for="anggota_tpk" :value="__('Anggota TPK')" />
                            <x-text-input id="anggota_tpk" name="anggota_tpk" type="text" class="mt-1 block w-full"
                                :value="old('anggota_tpk', $kegiatan['anggota_tpk'])" required autofocus autocomplete="anggota_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('anggota_tpk')" />
                        </div>
                        <div>
                            <x-input-label for="nomor_sk_tpk" :value="__('Nomor SK TPK')" />
                            <x-text-input id="nomor_sk_tpk" name="nomor_sk_tpk" type="number" min="1" class="mt-1 block w-full"
                                :value="old('nomor_sk_tpk', $kegiatan['nomor_sk_tpk'])" required autofocus autocomplete="nomor_sk_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_sk_tpk')" />
                        </div>
                        <div>
                            <x-input-label for="tgl_sk_tpk" :value="__('Tanggal SK TPK')" />
                            <x-text-input id="tgl_sk_tpk" name="tgl_sk_tpk" type="date" class="mt-1 block w-full"
                                :value="old('tgl_sk_tpk', $kegiatan['tgl_sk_tpk'])" required autofocus autocomplete="tgl_sk_tpk" />
                            <x-input-error class="mt-2" :messages="$errors->get('tgl_sk_tpk')" />
                        </div>
                        <div>
                            <x-input-label for="pka" :value="__('PKA')" />
                            <x-text-input id="pka" name="pka" type="text" class="mt-1 block w-full"
                            :value="old('pka', $kegiatan['pka'])" required autofocus autocomplete="pka" />
                            <x-input-error class="mt-2" :messages="$errors->get('pka')" />
                        </div>
                        <div>
                            <x-input-label for="nomor_sk_pka" :value="__('Nomor SK PKA')" />
                            <x-text-input id="nomor_sk_pka" name="nomor_sk_pka" type="number" min="1" class="mt-1 block w-full"
                                :value="old('nomor_sk_pka', $kegiatan['nomor_sk_pka'])" required autofocus autocomplete="nomor_sk_pka" />
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_sk_pka')" />
                        </div>
                        <div>
                            <x-input-label for="tgl_sk_pka" :value="__('Tanggal SK PKA')" />
                            <x-text-input id="tgl_sk_pka" name="tgl_sk_pka" type="date" class="mt-1 block w-full"
                                :value="old('tgl_sk_pka', $kegiatan['tgl_sk_pka'])" required autofocus autocomplete="tgl_sk_pka" />
                            <x-input-error class="mt-2" :messages="$errors->get('tgl_sk_pka')" />
                        </div>
                        <div>
                            <x-primary-button>{{ $kegiatan->exists ? 'Ubah' : 'Simpan' }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
