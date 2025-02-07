
@php
    // App\Models\PenawaranHarga::all()->dd();

@endphp
<x-bladewind::modal
ok_button_action="renderNegosiasiHarga()"
    name="negosiasi"
    backdrop_can_close="false"
    ok_button_label="Cetak"
    blur_size="none"
    size="large"
    title="Negosiasi Harga">
    <p>{{ $p->id }}</p>
    <form action="{{ route('negosiasi.render') }}" target="_blank" class="negosiasi" method="POST">
        @method('post')
        @csrf
        <div>
            <x-input-label for="harga_penawaran" :value="__('Harga Penawaran')" />
            <x-text-input id="harga_penawaran" name="harga_penawaran" type="number" class="mt-1 block w-full" required placeholder="" @readonly(true)/>
            <x-input-error class="mt-2" :messages="$errors->get('harga_penawaran')" />
        </div>
        <div>
            <x-input-label for="harga_negosiasi" :value="__('Harga Negosiasi')" />
            <x-text-input id="harga_negosiasi" name="harga_negosiasi" type="number" class="mt-1 block w-full" required placeholder="" />
            <x-input-error class="mt-2" :messages="$errors->get('harga_negosiasi')" />
        </div>
        
    </form>
</x-bladewind::modal>
@pushOnce('scripts')

<script>

renderNegosiasiHarga = () => {
    if(validateForm('.negosiasi')){
        domEl('.negosiasi').submit();
    } else {
        return false;
    }
}
</script>

@endPushOnce

