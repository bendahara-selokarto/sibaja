@extends('errors::frameless')
@section('title', __('Tidak tersedia'))
@section('code', '404')
@section('message')
     <x-bladewind::error
        heading="sibaja tidak punya halaman ini"
        description="Maaf, halaman yang Anda cari tidak ada."
        button_text="Kembali ke Beranda"
        button_url="/">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/404.svg') }}" alt="404 image">
        </x-slot>
    </x-bladewind::error>
@endsection
