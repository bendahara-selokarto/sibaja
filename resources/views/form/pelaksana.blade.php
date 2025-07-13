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
</x-app-layout>
