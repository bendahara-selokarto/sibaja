<div>
    <x-input-label for="desa" :value="__('Desa')" />
    <x-text-input id="desa" name="desa" type="text" placeholder="nama desa" class="mt-1 block w-full" :value="old('desa', $user->desa)" required autofocus autocomplete="desa" />
    <x-input-error class="mt-2" :messages="$errors->get('desa')" />
</div>
<x-text-input name="kecamatan" type="hidden" value="Pecalungan" required />
<div>
    <x-input-label for="kepala_desa" :value="__('Kepala Desa')" />
    <x-text-input id="kepala_desa" name="kepala_desa" type="text"  placeholder="nama kades" class="mt-1 block w-full" :value="old('kepala_desa', $user->kepala_desa)" required autocomplete="kepala_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('Kepala Desa')" />
</div>
<div>
    <x-input-label for="sekretaris_desa" :value="__('Sekretaris Desa')" />
    <x-text-input id="sekretaris_desa" name="sekretaris_desa" type="text" placeholder="nama sekdes" class="mt-1 block w-full" :value="old('sekretaris_desa', $user->sekretaris_desa)" required autocomplete="sekretaris_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('sekretaris_desa')" />
</div>
<div>
    <x-input-label for="bendahara_desa" :value="__('Bendahara Desa')" />
    <x-text-input id="bendahara_desa" name="bendahara_desa" type="text" placeholder="nama bendahara" class="mt-1 block w-full" :value="old('bendahara_desa', $user->bendahara_desa)" required autocomplete="bendahara_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('bendahara_desa')" />
</div>
<div>
    <x-input-label for="website" :value="__('Website')" />
    <x-text-input id="website" name="website" type="text" placeholder="nama.desa.id" class="mt-1 block w-full" :value="old('website', $user->website)" required autocomplete="website" />
    <x-input-error class="mt-2" :messages="$errors->get('website')" />
</div>
<div>
    <x-input-label for="kode_desa" :value="__('Kode Desa')" />
    <x-text-input id="kode_desa" name="kode_desa" type="text" maxlength="10" minlength="10" placeholder="3325142000" class="mt-1 block w-full" :value="old('kode_desa', $user->kode_desa)" required autocomplete="kode_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('kode_desa')" />
</div>
<div>
    <x-input-label for="alamat_kantor" :value="__('Alamat Kantor')" />
    <x-text-input id="alamat_kantor" name="alamat_kantor" type="text" placeholder="Jl." class="mt-1 block w-full" :value="old('alamat_kantor', $user->alamat_kantor)" required autocomplete="alamat_kantor" />
    <x-input-error class="mt-2" :messages="$errors->get('alamat_kantor')" />
</div>
<div>
    <x-input-label for="tahun_anggaran" :value="__('tahun anggaran')" />
    <x-text-input type="number" min="2024" max="{{ date('Y') }}" id="tahun_anggaran" name="tahun_anggaran" placeholder="YYYY" min="2024" max="{{ date('Y') }}" class="mt-1 block w-full" :value="old('tahun_anggaran', $user->tahun_anggaran)" required autocomplete="tahun_anggaran" />
    <x-input-error class="mt-2" :messages="$errors->get('Tahun Anggaran')" />
</div>