<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>   

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form 
                action="{{ isset($pemberitahuan) ? route('pemberitahuan.update', $pemberitahuan->id) : route('pemberitahuan.store') }}" 
                method="POST"
                class="survey"
                >
                    @method('post')
                    @csrf
                    @if(isset($pemberitahuan))
                        @method('PATCH')
                    @endif
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                <div>
                                    <input type="hidden" required value="{{ $kegiatan->id }}" name="kegiatan_id">                                   
                                    <x-input-disable name="rekening_apbdes" type="text" class="mt-1 " required placeholder="{{ $kegiatan->rekening_apbdes }}"  value="{{ $kegiatan->rekening_apbdes }}"/>
                                    <x-input-disable name="kegiatan" type="text" class="mt-1 w-full" required placeholder="{{ $kegiatan->kegiatan }}" value="{{ $kegiatan->kegiatan }}" />
                                    <x-input-disable name="ketua_tpk" type="hidden" class="mt-1 " required placeholder="{{ $kegiatan->ketua_tpk }}" value="{{ $kegiatan->ketua_tpk }}" />
                                    <x-input-disable name="pka" type="hidden" class="mt-1 " required placeholder="{{ $kegiatan->pka }}" value="{{ $kegiatan->pka }}"/>
                                </div>
                                <hr> 
                                <div>
                                    <x-input-label for="no_pbj" :value="__('Nomor Urut PBJ')" />
                                    <x-text-input 
                                        id="no_pbj" 
                                        name="no_pbj" 
                                        type="number" 
                                        value="{{ old('no_pbj', $pemberitahuan->no_pbj ?? '') }}" 
                                    />

                                    <x-input-error class="mt-2" :messages="$errors->get('no_pbj')" />
                                </div>
                                <div>
                                    <x-input-label for="tgl_pemberitahuan" :value="__('Tanggal Surat')" />
                                    <x-text-input 
                                    value="{{ old('tgl_pemberitahuan', $pemberitahuan->tgl_surat_pemberitahuan ?? '') }}"
                                    id="tgl_pemberitahuan" name="tgl_pemberitahuan" type="date" min="{{ Auth::user()->tahun_anggaran . '-01-01' }}" max="{{ Auth::user()->tahun_anggaran . '-12-31' }}" class="mt-1 inline " required autocomplete="tgl_pemberitahuan" /> <span id="hari-pemberitahuan"></span>
                                    <x-input-error class="mt-2" :messages="$errors->get('tgl_pemberitahuan')" />
                                </div>                                
                                <br>
                                <p>Centang 2 Penyedia yang diberi penawaran :</p>
                                @foreach ($penyedia as  $p)
                                <x-bladewind::checkbox
                                name="penyedia[]"
                                value="{{ $p['id'] }}"
                                label="{{ $p['nama_penyedia'] }}" 
                                :checked="in_array($p['id'], old('penyedia', $penyediaTerpilih ?? []))"
                                />            
                                @endforeach
                                
                                    @php
                                        $uraian = old('uraian', collect($belanja)->pluck('uraian')->toArray());
                                        $volume = old('volume', collect($belanja)->pluck('volume')->toArray());
                                        $satuan = old('satuan', collect($belanja)->pluck('satuan')->toArray());
                                    @endphp
                                <div id="inputContainer">  

                                    @foreach ($uraian as $index => $val)
                                        <div class="input-group">                  
                                            <input type="text" name="uraian[]" placeholder="Uraian" required value="{{ $val }}">
                                            <input type="number" min="0" step="any" name="volume[]" placeholder="Volume" required value="{{ $volume[$index] ?? '' }}">  
                                            <input type="text" name="satuan[]" placeholder="Satuan" required value="{{ $satuan[$index] ?? '' }}">  
                                            @if(!$loop->first)
                                                <button type="button" onclick="removeInput(this)">
                                                    <x-bladewind::icon class="text-red-500" name="minus-circle"/>
                                                </button> |
                                            @endif
                                            <button type="button" onclick="addInput()">
                                                <x-bladewind::icon name="plus-circle" class="text-blue-500" />  
                                            </button>
                                        </div>
                                    @endforeach
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
       <x-text-input class="mt-1" type="text" name="uraian[]" required autofocus autocomplete="uraian" />
       <x-text-input class="mt-1" type="number" min="0" step="any" name="volume[]" required  autocomplete="Vol" />
       <x-text-input class="mt-1" type="text" name="satuan[]" required  autocomplete="Satuan" />                  
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
const listHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

tglPemberitahuan.addEventListener('change', (e) => {
  const tanggalPemberitahuan = new Date(e.target.value);
  const tanggalBatasAkhirPenawaran = new Date(tanggalPemberitahuan);
  const hariPemberitahuan = listHari[tanggalPemberitahuan.getDay()];
  const spanPemberitahuan = document.getElementById('hari-pemberitahuan');
    spanPemberitahuan.textContent = `(${hariPemberitahuan})`;
    tanggalBatasAkhirPenawaran.setDate(tanggalBatasAkhirPenawaran.getDate() + 3);


    const hariIndex = tanggalBatasAkhirPenawaran.getDay();
  if (hariIndex === 0) {
    tanggalBatasAkhirPenawaran.setDate(tanggalBatasAkhirPenawaran.getDate() + 1);
  } else if (hariIndex === 6) {
    tanggalBatasAkhirPenawaran.setDate(tanggalBatasAkhirPenawaran.getDate() + 2);
  } else {
    tanggalBatasAkhirPenawaran.setDate(tanggalBatasAkhirPenawaran.getDate());
  }
  const hariBatasAkhirPenawaran = listHari[tanggalBatasAkhirPenawaran.getDay()];
  console.log(hariBatasAkhirPenawaran);
  const spanBatasAkhirPenawaran = document.getElementById('hari-batas-akhir-penawaran');
    spanBatasAkhirPenawaran.textContent = `(${hariBatasAkhirPenawaran})`;

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
