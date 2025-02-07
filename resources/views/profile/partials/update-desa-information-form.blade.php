<div>
    <x-input-label for="desa" :value="__('desa')" />
    <x-text-input id="desa" name="desa" type="text" class="mt-1 block w-full" :value="old('desa', $user->desa)" required autofocus autocomplete="desa" />
    <x-input-error class="mt-2" :messages="$errors->get('desa')" />
</div>
<x-text-input name="kecamatan" type="hidden" value="Pecalungan" required />
<div>
    <x-input-label for="kepala_desa" :value="__('kepala_desa')" />
    <x-text-input id="kepala_desa" name="kepala_desa" type="text" class="mt-1 block w-full" :value="old('kepala_desa', $user->kepala_desa)" required autofocus autocomplete="kepala_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('kepala_desa')" />
</div>
<div>
    <x-input-label for="sekretaris_desa" :value="__('sekretaris_desa')" />
    <x-text-input id="sekretaris_desa" name="sekretaris_desa" type="text" class="mt-1 block w-full" :value="old('sekretaris_desa', $user->sekretaris_desa)" required autofocus autocomplete="sekretaris_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('sekretaris_desa')" />
</div>
<div>
    <x-input-label for="bendahara_desa" :value="__('bendahara_desa')" />
    <x-text-input id="bendahara_desa" name="bendahara_desa" type="text" class="mt-1 block w-full" :value="old('bendahara_desa', $user->bendahara_desa)" required autofocus autocomplete="bendahara_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('bendahara_desa')" />
</div>
<div>
    <x-input-label for="website" :value="__('website')" />
    <x-text-input id="website" name="website" type="text" class="mt-1 block w-full" :value="old('website', $user->website)" required autofocus autocomplete="website" />
    <x-input-error class="mt-2" :messages="$errors->get('website')" />
</div>
<div>
    <x-input-label for="kode_desa" :value="__('kode_desa')" />
    <x-text-input id="kode_desa" name="kode_desa" type="text" class="mt-1 block w-full" :value="old('kode_desa', $user->kode_desa)" required autofocus autocomplete="kode_desa" />
    <x-input-error class="mt-2" :messages="$errors->get('kode_desa')" />
</div>
<div>
    <x-input-label for="alamat_kantor" :value="__('alamat_kantor')" />
    <x-text-input id="alamat_kantor" name="alamat_kantor" type="text" class="mt-1 block w-full" :value="old('alamat_kantor', $user->alamat_kantor)" required autofocus autocomplete="alamat_kantor" />
    <x-input-error class="mt-2" :messages="$errors->get('alamat_kantor')" />
</div>
<div>
    <x-input-label for="tahun_anggaran" :value="__('tahun_anggaran')" />
    <x-text-input id="tahun_anggaran" name="tahun_anggaran" type="text" class="mt-1 block w-full" :value="old('tahun_anggaran', $user->tahun_anggaran)" required autofocus autocomplete="tahun_anggaran" />
    <x-input-error class="mt-2" :messages="$errors->get('tahun_anggaran')" />
</div>