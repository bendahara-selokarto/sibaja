<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran') }}
        </h2>
    </x-slot>   

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('pembayaran.store') }}" method="POST">
                    @method('post')
                    @csrf                    
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">                           
                            <p>{{ $kegiatan->kegiatan  }}</p>
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">                                
                                <div>
                                    <x-text-input id="id" name="kegiatan_id" type="hidden" class="mt-1 block " value="{{ $kegiatan->id  }}" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_invoice" :value="__('Tanggal Invoice')" />
                                    <input id="tgl_invoice" name="tgl_invoice" type="date" class="mt-1 block " min="{{ \Carbon\Carbon::parse($kegiatan->negosiasiHarga->tgl_akhir_perjanjian)->format('Y-m-d') }}" required autocomplete="tgl_invoice" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_invoice')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_pembayaran_cms" :value="__('Tanggal Pembayaran CMS')" />
                                    <input id="tgl_pembayaran_cms" name="tgl_pembayaran_cms" type="date" class="mt-1 block " required autocomplete="tgl_pembayaran_cms" min="{{ \Carbon\Carbon::parse($kegiatan->negosiasiHarga->tgl_akhir_perjanjian)->format('Y-m-d') }}" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_pembayaran_cms')" />
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

    @endPushOnce
</x-app-layout>
