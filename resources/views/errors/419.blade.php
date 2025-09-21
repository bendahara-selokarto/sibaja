@extends('errors::frameless')
@section('title', __('Halaman Kedaluwarsa'))
@section('code', '429')
@section('message')
     <x-bladewind::error
        heading="Kadaluwarsa"
        description="Maaf, halaman ini telah kedaluwarsa. Silakan login kembali."
        button_text="Kembali ke Login"
        button_url="/login">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/419.svg') }}" >
        </x-slot>
    </x-bladewind::error>
@endsection
@extends('errors::frameless')