@extends('errors::frameless')
@section('title', __('internal server error'))
@section('code', '500')
@section('message')
     <x-bladewind::error
        heading="error server"
        description="Ada Kesalahan pada server. Silakan coba lagi nanti."
        button_text="Kembali ke Beranda"
        button_url="/">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/500.svg') }}" >
        </x-slot>
    </x-bladewind::error>
@endsection
