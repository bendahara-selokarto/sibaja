<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SK Tim Pelaksana Kegiatan') }}
        </h2>
    </x-slot>  

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <a href="{{ route('pelaksana.create') }}"><x-bladewind::button size='tiny' color="cyan">Tambah</x-bladewind::button></a>
                    
                </div>
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-7 text-stone">
                    <div class="overflow-scroll">
                       
                    
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
