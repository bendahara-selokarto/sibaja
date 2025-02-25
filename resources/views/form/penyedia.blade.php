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
                                <x-input-label for="nama_pemilik" :value="__('Nama Pemilik')" />
                                <x-text-input id="nama_pemilik" name="nama_pemilik" type="text" class="mt-1 block w-full" :value="old('nama_pemilik', $penyedia->nama_pemilik)" required autofocus autocomplete="nama_pemilik" />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_pemilik')" />
                            </div>
                            <div>
                                <x-input-label for="alamat_pemilik" :value="__('Alamat Pemilik')" />
                                <x-text-input id="alamat_pemilik" name="alamat_pemilik" type="text" class="mt-1 block w-full" :value="old('alamat_pemilik', $penyedia->alamat_pemilik)" required autocomplete="alamat_pemilik" />
                                <x-input-error class="mt-2" :messages="$errors->get('alamat_pemilik')" />
                            </div>
                        </div>
                        <div>
                            <div>
                                <x-input-label for="nomor_hp" :value="__('Nomor HP')" />
                                <x-text-input id="nomor_hp" name="nomor_hp" type="text" class="mt-1 block w-full" :value="old('nomor_hp', $penyedia->nomor_hp)" required autofocus autocomplete="nomor_hp" />
                                <x-input-error class="mt-2" :messages="$errors->get('nomor_hp')" />
                            </div>
                            <div>
                                <x-input-label for="nomor_identitas" :value="__('Nomor KTP/SIM')" />
                                <x-text-input id="nomor_identitas" name="nomor_identitas" type="text" class="mt-1 block w-full" :value="old('nomor_identitas', $penyedia->nomor_identitas)" required autocomplete="nomor_identitas" />
                                <x-input-error class="mt-2" :messages="$errors->get('nomor_identitas')" />
                            </div>
                            <div>
                                <x-input-label for="nomor_npwp" :value="__('NPWP')" />
                                <x-text-input id="nomor_npwp" name="nomor_npwp" type="text" class="mt-1 block w-full" :value="old('nomor_npwp', $penyedia->nomor_npwp)" required autofocus autocomplete="nomor_npwp" />
                                <x-input-error class="mt-2" :messages="$errors->get('nomor_npwp')" />
                            </div>
                            <div>
                                <x-input-label for="no_SIUP" :value="__('No Izin Usaha')" />
                                <x-text-input id="no_SIUP" name="no_siup" type="text" class="mt-1 block w-full" :value="old('no_SIUP', $penyedia->nomor_izin_usaha)" required autocomplete="no_SIUP" />
                                <x-input-error class="mt-2" :messages="$errors->get('no_SIUP')" />
                            </div>
                        </div>
                        <div>
                            <div>
                                <x-input-label for="penerbit_SIUP" :value="__('Instansi Pemberi Izin')" />
                                <x-text-input id="penerbit_SIUP" name="penerbit_siup" type="text" class="mt-1 block w-full" :value="old('penerbit_SIUP', $penyedia->instansi_pemberi_izin_usaha)" required autofocus autocomplete="penerbit_SIUP" />
                                <x-input-error class="mt-2" :messages="$errors->get('penerbit_SIUP')" />
                            </div>
                            <div>
                                <x-input-label for="jabatan_pemilik" :value="__('Jabatan Pemilik')" />
                                <x-text-input id="jabatan_pemilik" name="jabatan_pemilik" type="text" class="mt-1 block w-full" :value="old('jabatan_pemilik', $penyedia->jabata_pemilik)" required autofocus autocomplete="jabatan_pemilik" />
                                <x-input-error class="mt-2" :messages="$errors->get('jabatan_pemilik')" />
                            </div>
                            <div>
                                <x-input-label for="logo_penyedia" :value="__('Logo')" />
                                <x-text-input id="logo_penyedia" name="logo_penyedia" type="file" class="mt-1 block w-full" :value="old('logo_penyedia', $penyedia->logo_penyedia)" autocomplete="logo_penyedia" />
                                <x-input-error class="mt-2" :messages="$errors->get('logo_penyedia')" />
                            </div>
                        </div>
                    {{-- </div> --}}
                    <div>                        
                        <x-primary-button>Simpan</x-primary-button>  
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
