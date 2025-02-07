<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Negosiasi') }}
        </h2>
    </x-slot>   

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('negosiasi.store') }}" method="POST">
                    @method('post')
                    @csrf                    
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <p>{{ $kegiatan->rekening_apbdes  }}</p>
                            <p>{{ $kegiatan->kegiatan  }}</p>
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">                                
                                <div>
                                    <x-text-input id="id" name="kegiatan_id" type="hidden" class="mt-1 block " value="{{ $kegiatan->id  }}" />
                                </div>
                                <div>
                                    <x-input-label for="harga_negosiasi" :value="__('Harga Negosiasi')" />
                                    <x-text-input id="harga_negosiasi" name="harga_negosiasi" type="number" class="mt-1 block " required autocomplete="harga_negosiasi" />
                                    <x-input-error class="mt-2" :messages="$errors->get('harga_negosiasi')" />
                                </div>
                                <div>                               
                                    <x-primary-button>Simpan</x-primary-button>
                                </div>
                            </div>            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>  
</x-app-layout>
