<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>   

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('pemberitahuan.store') }}" class="survey" method="POST">
                    @method('post')
                    @csrf
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                <div>
                                    <input type="hidden" required value="{{ $kegiatan->id }}" name="kegiatan_id">                                   
                                    <x-input-disable name="rekening_apbdes" type="text" class="mt-1 " required placeholder="{{ $kegiatan->rekening_apbdes }}"  value="{{ $kegiatan->rekening_apbdes }}"/>
                                    <x-input-disable name="kegiatan" type="text" class="mt-1 " required placeholder="{{ $kegiatan->kegiatan }}" value="{{ $kegiatan->kegiatan }}" />
                                    <x-input-disable name="ketua_tpk" type="text" class="mt-1 " required placeholder="{{ $kegiatan->ketua_tpk }}" value="{{ $kegiatan->ketua_tpk }}" />
                                    <x-input-disable name="pka" type="text" class="mt-1 " required placeholder="{{ $kegiatan->pka }}" value="{{ $kegiatan->pka }}"/>
                                </div>
                                <hr> 
                                <div>
                                    <x-input-label for="no_pbj" :value="__('Nomor Urut PBJ')" />
                                    <x-text-input id="no_pbj" name="no_pbj" type="number" min="0" value="{{ $nomor }}" class="mt-1 block " required autocomplete="no_pbj" />
                                    <x-input-error class="mt-2" :messages="$errors->get('no_pbj')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_pemberitahuan" :value="__('Tanggal Surat')" />
                                    <x-text-input id="tgl_pemberitahuan" name="tgl_pemberitahuan" type="date" min="{{ Auth::user()->tahun_anggaran . '-01-01' }}" max="{{ Auth::user()->tahun_anggaran . '-12-31' }}" class="mt-1 block " required autocomplete="tgl_pemberitahuan" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_pemberitahuan')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_batas_akhir_penawaran" :value="__('tgl batas akhir penawaran')" />
                                    <x-text-input id="tgl_batas_akhir_penawaran" name="tgl_batas_akhir_penawaran" type="date" class="mt-1 block " required autocomplete="tgl_batas_akhir_penawaran" />
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_batas_akhir_penawaran')" />
                                </div>
                                <br>
                                <p>Centang 2 Penyedia yang diberi penawaran :</p>
                                @foreach ($penyedia as  $p)
                                <x-bladewind::checkbox
                                name="penyedia[]"
                                value="{{ $p['id'] }}"
                                label="{{ $p['nama_penyedia'] }}" />            
                                @endforeach
                                
                                <div id="inputContainer">  
                                    <div class="input-group">                  
                                        <input type="text" name="inputField1[]" placeholder="Uraian" required>
                                        <input type="number" min="0" step="any" name="inputField2[]" placeholder="Vol" required>  
                                        <input type="text" name="inputField3[]" placeholder="Satuan" required>  
                                        <button type="button" onclick="removeInput(this)">Hapus</button>  
                                        <button type="button" onclick="addInput()"><x-bladewind::icon name="plus-circle" class="h-16 w-16 text-amber-500" />  
                                    </div>  
                                </div>    
                            </div>            
                        </div>
                        <br>
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                <div >
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
    function addInput() {  
   const inputContainer = document.getElementById('inputContainer');  
   const newInputGroup = document.createElement('div');  
   newInputGroup.className = 'input-group';  
   newInputGroup.innerHTML = `  
       <x-text-input class="mt-1" type="text" name="inputField1[]" required autofocus autocomplete="uraian" />
       <x-text-input class="mt-1" type="number" min="0" step="any" name="inputField2[]" required  autocomplete="Vol" />
       <x-text-input class="mt-1" type="text" name="inputField3[]" required  autocomplete="Satuan" />                  
       <button type="button" onclick="removeInput(this)"><x-bladewind::icon class="text-red-500" name="minus-circle"/></button> | <button type="button" onclick="addInput()"><x-bladewind::icon name="plus-circle" class="text-blue-500	"/></button>  
   `;  
   inputContainer.appendChild(newInputGroup);  
}  

function removeInput(button) {  
   const inputGroup = button.parentNode;  
   inputGroup.remove();  
}  
</script>
<script>
    const tglPemberitahuan = document.getElementById('tgl_pemberitahuan');
const tglBatasAkhirPenawaran = document.getElementById('tgl_batas_akhir_penawaran');

tglPemberitahuan.addEventListener('change', (e) => {
  const tanggalPemberitahuan = new Date(e.target.value);
  const tanggalBatasAkhirPenawaran = new Date(tanggalPemberitahuan);
  tanggalBatasAkhirPenawaran.setDate(tanggalBatasAkhirPenawaran.getDate() + 5);

  const tahun = tanggalBatasAkhirPenawaran.getFullYear();
  const bulan = tanggalBatasAkhirPenawaran.getMonth() + 1;
  const hari = tanggalBatasAkhirPenawaran.getDate();

  const tanggalBatasAkhirPenawaranFormat = `${tahun}-${bulan.toString().padStart(2, '0')}-${hari.toString().padStart(2, '0')}`;

  tglBatasAkhirPenawaran.min = tglPemberitahuan.value;
  tglBatasAkhirPenawaran.max = tanggalBatasAkhirPenawaranFormat;
    tglBatasAkhirPenawaran.value = tanggalBatasAkhirPenawaranFormat;
});
</script>

@endPushOnce
</x-app-layout>
