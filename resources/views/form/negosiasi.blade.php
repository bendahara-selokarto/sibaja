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
                                    <x-text-input id="id" name="kegiatan_id" type="hidden" class="mt-1 block w-full" value="{{ $kegiatan->id  }}" />
                                </div>
                                <div>
                                    <x-input-label for="harga_negosiasi" :value="__('Harga Negosiasi')" />
                                    <x-text-input id="harga_negosiasi" name="harga_negosiasi" type="number" value="{{ $kegiatan->harga_penawaran }}" min="0" class="mt-1 inline sm:block" required autocomplete="harga_negosiasi" />
                                    <div class="mt-1 inline sm:block text-green-700" id="format_curency"></div>
                                    <x-input-error class="mt-2" :messages="$errors->get('harga_negosiasi')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_persetujuan" :value="__('Tanggal Persetujuan Penawaran')" /> 
                                    <x-text-input id="tgl_persetujuan" name="tgl_persetujuan" type="date" max="{{ Auth::user()->tahun_anggaran . '-12-31' }}" class="mt-1 block" :value="old('tgl_persetujuan' , $kegiatan->tgl )" min="{{ $kegiatan->tgl }}" required autocomplete="tgl_persetujuan" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_persetujuan')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_negosiasi" :value="__('Tanggal Negosiasi Harga')" />
                                    <x-text-input id="tgl_negosiasi" name="tgl_negosiasi" type="date" max="{{ Auth::user()->tahun_anggaran . '-12-31' }}" class="mt-1 block" required autocomplete="tgl_negosiasi" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_negosiasi')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_perjanjian" :value="__('Tanggal Perjanjian')" />
                                    <x-text-input id="tgl_perjanjian" name="tgl_perjanjian" type="date" max="{{ Auth::user()->tahun_anggaran . '-12-31' }}" class="mt-1 block" required autocomplete="tgl_perjanjian" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_perjanjian')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_akhir_perjanjian" :value="__('Tanggal Akhir Perjanjian')" />
                                    <x-text-input id="tgl_akhir_perjanjian" name="tgl_akhir_perjanjian" type="date" max="{{ Auth::user()->tahun_anggaran . '-12-31' }}" class="mt-1 block" required autocomplete="tgl_akhir_perjanjian" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_akhir_perjanjian')" />
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
{{-- javascript --}}
@pushOnce('scripts')
<script>
    const hargaNegosiasi = document.getElementById('harga_negosiasi');
    const formatCurency = document.getElementById('format_curency');

    hargaNegosiasi.addEventListener('input', (e) => {
    const nilai = parseInt(e.target.value);
    const format = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(nilai);
    formatCurency.innerText = format;
    });
</script>
@endPushOnce
</x-app-layout> 
