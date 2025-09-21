@extends('errors::frameless')
@section('title', __('Tidak tersedia'))
@section('code', '503')
@section('message')
     <x-bladewind::error
        heading="maintenance"
        description="Sibaja sedang dalam perbaikan. Silakan coba lagi nanti."
        button_text="Kembali ke Beranda"
        button_url="/">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/503.svg') }}" alt="503 image">
        </x-slot>
    </x-bladewind::error>
@endsection
